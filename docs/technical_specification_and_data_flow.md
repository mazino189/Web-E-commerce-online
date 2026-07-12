# Technical Specification & Data Flow Guide

This document describes the technical architecture, database design, and end-to-end data flows for the **Voltaire / Tech E-commerce Platform**. It is intended as a reference for developers, engineers, and architects onboarding to the codebase.

---

## 1. System Architecture

Voltaire / Tech operates under a decoupled, headless client-server architecture:

```mermaid
graph TD
    subgraph Client-Side (React SPA)
        React[React 19 View Components]
        Context[Auth & Cart Contexts]
        AxiosClient[apiClient.ts / Fetch]
        React --> Context
        Context --> AxiosClient
    end

    subgraph Server-Side (Laravel API)
        Router[routes/api.php]
        Middleware[Sanctum & Admin Middleware]
        Controllers[API Controllers]
        Eloquent[Eloquent ORM Models]
        Router --> Middleware
        Middleware --> Controllers
        Controllers --> Eloquent
    end

    subgraph Infrastructure
        DB[(SQLite database.sqlite)]
        Cloudinary[Cloudinary API]
        Eloquent --> DB
        Controllers --> Cloudinary
    end

    AxiosClient <-->|JSON over HTTP / Bearer Auth| Router
```

### 1.1 Technology Summary
- **Frontend SPA**: React 19 + TypeScript + Vite 8.
- **Backend API**: Laravel 13.x + PHP 8.3.
- **Database**: SQLite, featuring active row-locking (`lockForUpdate`) for inventory control.
- **Media Hosting**: Cloudinary, facilitating instant uploads for admin product image fields.
- **Production Server**: Dockerized Apache container running on Render with SQLite database volume storage.

---

## 2. Database Design & Relationships

The database is built on normalized SQLite tables linked via Eloquent relationships:

```mermaid
erDiagram
    USERS ||--o{ CARTS : "manages"
    USERS ||--o{ ORDERS : "places"
    CATEGORIES ||--o{ PRODUCTS : "groups"
    BRANDS ||--o{ PRODUCTS : "manufactures"
    PRODUCTS ||--o{ CARTS : "stored-in"
    PRODUCTS ||--o{ ORDER_ITEMS : "details"
    ORDERS ||--o{ ORDER_ITEMS : "contains"

    USERS {
        int id PK
        string name
        string email UK
        string password
        string role
        string phone
        string address
        string avatar
    }
    CATEGORIES {
        int id PK
        string name
        string slug UK
        string description
    }
    BRANDS {
        int id PK
        string name
        string slug UK
        string description
    }
    PRODUCTS {
        int id PK
        string name
        string slug UK
        decimal price
        int stock
        string image
        int category_id FK
        int brand_id FK
        text description
    }
    CARTS {
        int id PK
        int user_id FK
        int product_id FK
        int quantity
    }
    ORDERS {
        int id PK
        int user_id FK
        decimal total_amount
        string status
        string shipping_address
        string phone_number
        string payment_status
        string payment_method
    }
    ORDER_ITEMS {
        int id PK
        int order_id FK
        int product_id FK
        int quantity
        decimal price
    }
```

---

## 3. End-to-End Data Flows

### 3.1 Authentication & Session Management
All authenticated operations use Laravel Sanctum API tokens.

```mermaid
sequenceDiagram
    autonumber
    actor Client as React Client (User)
    participant UI as AuthContext / LocalStorage
    participant Route as routes/api.php
    participant Auth as AuthController
    participant DB as SQLite DB

    Client->>UI: Enter Credentials (Email / Password)
    UI->>Route: POST /api/login
    Route->>Auth: login(Request)
    Auth->>DB: Query User record
    DB-->>Auth: User Match & Password Valid
    Auth->>Auth: Generate Sanctum token (createToken)
    Auth-->>UI: Return User Info & Token (JSON)
    UI->>UI: Save Token to localStorage
    UI->>UI: Update Auth State Context
```

* **Token Attachment**: The frontend [apiClient.ts](file:///D:/Website%20Ecomerce%20online/frontend/src/apiClient.ts) intercepts outgoing requests and appends the token:
  ```typescript
  const token = localStorage.getItem('token');
  const headers = {
      'Accept': 'application/json',
      ...(token && { Authorization: `Bearer ${token}` }),
  };
  ```

---

### 3.2 Shopping Cart Operations
The shopping cart flow updates local React context state while syncing directly to the SQLite backend.

```mermaid
sequenceDiagram
    autonumber
    actor Client as React Client (User)
    participant CartCtx as CartContext
    participant Route as routes/api.php
    participant CartCtrl as CartController
    participant DB as SQLite DB

    Client->>CartCtx: Click "Add to Cart" (Product X, Qty Y)
    CartCtx->>Route: POST /api/cart { product_id, quantity }
    Route->>CartCtrl: store(CartRequest)
    CartCtrl->>DB: Check Product stock vs total cart quantity
    alt Insufficient Stock
        CartCtrl-->>Client: HTTP 422 "Insufficient stock"
    else Stock Available
        CartCtrl->>DB: Upsert cart item quantity
        DB-->>CartCtrl: Cart Row Saved
        CartCtrl-->>CartCtx: Return updated CartResource JSON
        CartCtx->>CartCtx: Synchronize Context State
    end
```

---

### 3.3 Checkout & Order Processing (Transaction Flow)
To prevent race conditions where multiple users buy the last item simultaneously, the checkout controller employs database transactions combined with SQLite row locking (`lockForUpdate`).

```mermaid
sequenceDiagram
    autonumber
    actor Client as React Client (User)
    participant Route as routes/api.php
    participant CheckCtrl as CheckoutController
    participant DB as SQLite DB

    Client->>Route: POST /api/checkout { shipping_address, phone_number, payment_method }
    Route->>CheckCtrl: store(CheckoutRequest)
    CheckCtrl->>DB: Begin Database Transaction
    CheckCtrl->>DB: Fetch and lock cart products (Product::lockForUpdate()->get())
    
    loop For each Item
        CheckCtrl->>DB: Compare cart quantity vs locked product stock
        alt Stock Exceeded
            CheckCtrl->>DB: Rollback Transaction
            CheckCtrl-->>Client: HTTP 422 "Insufficient stock"
        else Stock OK
            CheckCtrl->>DB: Decrement Product Stock
            CheckCtrl->>DB: Write Order & OrderItem record
        end
    end
    
    CheckCtrl->>DB: Clear User Cart (delete rows)
    CheckCtrl->>DB: Commit Transaction
    DB-->>CheckCtrl: Transaction Successful
    CheckCtrl-->>Client: HTTP 201 Created (Order details)
```

#### Code Snippet - Row Locking:
Found in [`app/Http/Controllers/CheckoutController.php`](file:///D:/Website%20Ecomerce%20online/app/Http/Controllers/CheckoutController.php#L27-L68):
```php
$order = DB::transaction(function () use ($user, $cartItems, $request, $paymentStatus) {
    $productIds = $cartItems->pluck('product_id');
    // Lock rows to prevent double-spending stock
    $products = Product::lockForUpdate()->whereIn('id', $productIds)->get()->keyBy('id');

    $total = 0;
    foreach ($cartItems as $cartItem) {
        $product = $products->get($cartItem->product_id);
        if ($cartItem->quantity > $product->stock) {
            throw new \RuntimeException("Insufficient stock.");
        }
        $total += $product->price * $cartItem->quantity;
    }
    // ... creates order & decrements stock ...
});
```

---

### 3.4 Support & Ticketing Flow
Visitors can submit support messages, which are indexed and managed via the Admin Support Panel.

```mermaid
graph LR
    A[Visitor UI] -->|POST /api/support/contact| B(ContactMessageController::store)
    B -->|Insert status: pending| C[(SQLite DB)]
    C -->|GET /api/admin/support| D[Admin Dashboard]
    D -->|PUT /api/admin/support/id/resolve| E(ContactMessageController::resolve)
    E -->|Update status: resolved| C
```

---

## 4. Environment Configuration (`.env`)

For a successful setup, ensure the following core variables are configured:

| Key | Purpose | Suggested Value (Local) |
|---|---|---|
| `APP_ENV` | Application environment | `local` |
| `DB_CONNECTION` | Database type | `sqlite` |
| `DB_DATABASE` | SQLite path | `D:\Website Ecomerce online\database\database.sqlite` |
| `CLOUDINARY_URL` | Image Storage Key | `cloudinary://<API_KEY>:<API_SECRET>@<CLOUD_NAME>` |
| `VITE_API_URL` | React API Target | `http://127.0.0.1:8000/api` |
