# Kitchen Ecommerce — API Contract

**Version:** 1.0.0  
**Base URL:** `https://web-e-commerce-online.onrender.com/api` (production)  
**Content-Type:** `application/json`  
**Auth:** Laravel Sanctum (token-based, Bearer token in `Authorization` header)

---

## Table of Contents

1. [Global Configuration](#1-global-configuration)
2. [Authentication](#2-authentication)
3. [Public Catalog](#3-public-catalog)
   - [Categories](#31-categories)
   - [Brands](#32-brands)
   - [Products](#33-products)
4. [Cart (Auth Required)](#4-cart-auth-required)
5. [Checkout & Orders (Auth Required)](#5-checkout--orders-auth-required)
   - [Checkout](#51-checkout)
   - [Orders](#52-orders)
6. [Admin CRUD (Auth + Admin Role)](#6-admin-crud-auth--admin-role)
   - [Admin Categories](#61-admin-categories)
   - [Admin Brands](#62-admin-brands)
   - [Admin Products](#63-admin-products)
7. [Global Error Codes](#7-global-error-codes)

---

## 1. Global Configuration

### Standard Response Wrapper

All **collection** endpoints and **single-resource** endpoints that use `->response()` wrap their data under a `data` key:

```json
{
  "data": { ... }
}
```

Paginated collections include `meta` and `links`:

```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 12,
    "total": 60
  },
  "links": {
    "first": "https://.../api/products?page=1",
    "last": "https://.../api/products?page=5",
    "prev": null,
    "next": "https://.../api/products?page=2"
  }
}
```

### Standard Error Shapes

| HTTP Code | Meaning | Response Body |
|-----------|---------|---------------|
| 401 | Unauthenticated | `{ "message": "Unauthenticated." }` |
| 403 | Forbidden | `{ "message": "Forbidden." }` |
| 404 | Not Found | `{ "message": "Resource not found." }` |
| 422 | Validation Error | `{ "message": "...", "errors": { "field": ["..."] } }` |
| 500 | Server Error | `{ "message": "Server Error" }` (debug off) |

### Authentication Header

```
Authorization: Bearer 1|abc123def456...
```

### Admin Role

Admin routes require both a valid Sanctum token **and** `role = "admin"` on the user record. Non-admin users receive `403 Forbidden`.

---

## 2. Authentication

### POST /api/register

Create a new user account and return a Sanctum token.

**Auth:** None

**Request Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "Password1!",
  "password_confirmation": "Password1!"
}
```

| Field | Type | Rules |
|-------|------|-------|
| `name` | string | required, max:255 |
| `email` | string | required, lowercase, email, max:255, unique:users |
| `password` | string | required, confirmed, Password defaults (min:8, mixed case, etc.) |

**Response `201`:**
```json
{
  "user": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "role": "user",
    "phone": null,
    "address": null,
    "avatar": null,
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  },
  "token": "1|abc123def456..."
}
```

**Errors:** `422`

---

### POST /api/login

Authenticate with email and password, return a Sanctum token.

**Auth:** None

**Request Body:**
```json
{
  "email": "jane@example.com",
  "password": "Password1!"
}
```

| Field | Type | Rules |
|-------|------|-------|
| `email` | string | required, email |
| `password` | string | required |

**Response `200`:**
```json
{
  "user": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "role": "user",
    "phone": null,
    "address": null,
    "avatar": null,
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  },
  "token": "2|xyz789..."
}
```

**Errors:** `422` (invalid credentials or validation failed)

---

### POST /api/logout

Revoke the current access token.

**Auth:** Bearer Token

**Response `200`:**
```json
{
  "message": "Logged out successfully."
}
```

**Errors:** `401`

---

### GET /api/user

Fetch the authenticated user's profile.

**Auth:** Bearer Token

**Response `200:**
```json
{
  "id": 1,
  "name": "Jane Doe",
  "email": "jane@example.com",
  "role": "user",
  "phone": "0123456789",
  "address": "123 Main St",
  "avatar": null,
  "created_at": "2026-06-13T00:00:00.000000Z",
  "updated_at": "2026-06-13T00:00:00.000000Z"
}
```

**Note:** This endpoint returns the user object **without** a `data` wrapper (response is flat).

**Errors:** `401`

---

## 3. Public Catalog

### 3.1 Categories

#### GET /api/categories

List all categories.

**Auth:** None

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Kitchen Appliances",
      "slug": "kitchen-appliances",
      "description": "Essential appliances for your kitchen",
      "image": null,
      "status": true,
      "created_at": "2026-06-13T00:00:00.000000Z",
      "updated_at": "2026-06-13T00:00:00.000000Z"
    }
  ]
}
```

---

#### GET /api/categories/{category}

Get a single category by ID.

**Auth:** None

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "Kitchen Appliances",
    "slug": "kitchen-appliances",
    "description": "Essential appliances for your kitchen",
    "image": null,
    "status": true,
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Errors:** `404`

---

### 3.2 Brands

#### GET /api/brands

List all brands.

**Auth:** None

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "KitchenPro",
      "slug": "kitchenpro",
      "description": "Premium kitchen appliances and cookware",
      "logo": null,
      "status": true,
      "created_at": "2026-06-13T00:00:00.000000Z",
      "updated_at": "2026-06-13T00:00:00.000000Z"
    }
  ]
}
```

---

#### GET /api/brands/{brand}

Get a single brand by ID.

**Auth:** None

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "KitchenPro",
    "slug": "kitchenpro",
    "description": "Premium kitchen appliances and cookware",
    "logo": null,
    "status": true,
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Errors:** `404`

---

### 3.3 Products

#### GET /api/products

List products with pagination, search, and filtering.

**Auth:** None

**Query Parameters:**

| Param | Type | Required | Description |
|-------|------|----------|-------------|
| `page` | int | No | Page number (default: 1) |
| `search` | string | No | Search by product name (LIKE) |
| `category_id` | int | No | Filter by category ID |
| `brand_id` | int | No | Filter by brand ID |
| `min_price` | float | No | Minimum price filter |
| `max_price` | float | No | Maximum price filter |

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Professional Blender 1200W",
      "slug": "professional-blender-1200w",
      "description": "High-performance blender with 1200W motor...",
      "price": 245000,
      "stock": 50,
      "image": "default-product.jpg",
      "status": true,
      "category_id": 1,
      "brand_id": 1,
      "category": {
        "data": {
          "id": 1,
          "name": "Kitchen Appliances",
          "slug": "kitchen-appliances",
          "description": "Essential appliances for your kitchen",
          "image": null,
          "status": true,
          "created_at": "2026-06-13T00:00:00.000000Z",
          "updated_at": "2026-06-13T00:00:00.000000Z"
        }
      },
      "brand": {
        "data": {
          "id": 1,
          "name": "KitchenPro",
          "slug": "kitchenpro",
          "description": "Premium kitchen appliances and cookware",
          "logo": null,
          "status": true,
          "created_at": "2026-06-13T00:00:00.000000Z",
          "updated_at": "2026-06-13T00:00:00.000000Z"
        }
      },
      "created_at": "2026-06-13T00:00:00.000000Z",
      "updated_at": "2026-06-13T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 12,
    "total": 10
  },
  "links": {
    "first": "http://localhost/api/products?page=1",
    "last": "http://localhost/api/products?page=1",
    "prev": null,
    "next": null
  }
}
```

**Errors:** None (returns empty `data` array if none match)

---

#### GET /api/products/{product}

Get a single product by ID with category and brand included.

**Auth:** None

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "Professional Blender 1200W",
    "slug": "professional-blender-1200w",
    "description": "High-performance blender with 1200W motor...",
    "price": 245000,
    "stock": 50,
    "image": "default-product.jpg",
    "status": true,
    "category_id": 1,
    "brand_id": 1,
    "category": {
      "data": {
        "id": 1,
        "name": "Kitchen Appliances",
        "slug": "kitchen-appliances",
        "description": "Essential appliances for your kitchen",
        "image": null,
        "status": true,
        "created_at": "2026-06-13T00:00:00.000000Z",
        "updated_at": "2026-06-13T00:00:00.000000Z"
      }
    },
    "brand": {
      "data": {
        "id": 1,
        "name": "KitchenPro",
        "slug": "kitchenpro",
        "description": "Premium kitchen appliances and cookware",
        "logo": null,
        "status": true,
        "created_at": "2026-06-13T00:00:00.000000Z",
        "updated_at": "2026-06-13T00:00:00.000000Z"
      }
    },
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Errors:** `404`

---

## 4. Cart (Auth Required)

All cart endpoints require a valid Bearer token. Users can only access their own cart items.

### GET /api/cart

Get the authenticated user's cart items with product details.

**Auth:** Bearer Token

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "product_id": 1,
      "quantity": 2,
      "product": {
        "data": {
          "id": 1,
          "name": "Professional Blender 1200W",
          "slug": "professional-blender-1200w",
          "description": "High-performance blender...",
          "price": 245000,
          "stock": 50,
          "image": "default-product.jpg",
          "status": true,
          "category_id": 1,
          "brand_id": 1,
          "category": null,
          "brand": null,
          "created_at": "2026-06-13T00:00:00.000000Z",
          "updated_at": "2026-06-13T00:00:00.000000Z"
        }
      },
      "created_at": "2026-06-13T00:00:00.000000Z",
      "updated_at": "2026-06-13T00:00:00.000000Z"
    }
  ]
}
```

**Errors:** `401`

---

### POST /api/cart

Add a product to cart. If the product already exists in the user's cart, the quantity is increased (merged).

**Auth:** Bearer Token

**Request Body:**
```json
{
  "product_id": 1,
  "quantity": 2
}
```

| Field | Type | Rules |
|-------|------|-------|
| `product_id` | int | required, exists:products,id |
| `quantity` | int | required, min:1 |

**Response `201`** (new item):
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "product_id": 1,
    "quantity": 2,
    "product": {
      "data": {
        "id": 1,
        "name": "Professional Blender 1200W",
        "price": 245000,
        "stock": 50,
        "image": "default-product.jpg",
        "status": true
      }
    },
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Response `200`** (existing item — quantity increased):
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "product_id": 1,
    "quantity": 5,
    "product": { "data": { ... } },
    "created_at": "...",
    "updated_at": "..."
  }
}
```

**Errors:** `401`, `422` (validation failed or insufficient stock)

---

### PUT /api/cart/{cart}

Update the quantity of a cart item.

**Auth:** Bearer Token

**Request Body:**
```json
{
  "quantity": 3
}
```

| Field | Type | Rules |
|-------|------|-------|
| `quantity` | int | required, min:1 |

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "product_id": 1,
    "quantity": 3,
    "product": { "data": { ... } },
    "created_at": "...",
    "updated_at": "..."
  }
}
```

**Errors:** `401`, `403` (not your cart item), `404`, `422`

---

### DELETE /api/cart/{cart}

Remove a cart item.

**Auth:** Bearer Token

**Response `200`:**
```json
{
  "message": "Cart item removed."
}
```

**Errors:** `401`, `403` (not your cart item), `404`

---

## 5. Checkout & Orders (Auth Required)

### 5.1 Checkout

#### POST /api/checkout

Create an order from the current cart with payment details. Clears the cart and reduces stock atomically (wrapped in a database transaction with row locking to prevent overselling).

**Auth:** Bearer Token

**Request Body:**
```json
{
  "shipping_address": "123 Test St",
  "phone_number": "1234567890",
  "payment_method": "cod"
}
```

| Field | Type | Rules |
|-------|------|-------|
| `shipping_address` | string | required, max:255 |
| `phone_number` | string | required, max:20 |
| `payment_method` | string | required, in:cod,bank_transfer |

**Payment Methods:**
| Value | Meaning | payment_status |
|-------|---------|----------------|
| `cod` | Cash on delivery | `unpaid` |
| `bank_transfer` | Bank transfer | `pending` |

**Response `201`:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "total_amount": 490000,
    "status": "pending",
    "shipping_address": "123 Test St",
    "phone_number": "1234567890",
    "payment_status": "unpaid",
    "payment_method": "cod",
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": 245000,
        "product": {
          "data": {
            "id": 1,
            "name": "Professional Blender 1200W",
            "slug": "professional-blender-1200w",
            "description": "...",
            "price": 245000,
            "stock": 48,
            "image": "default-product.jpg",
            "status": true,
            "category_id": 1,
            "brand_id": 1,
            "category": null,
            "brand": null,
            "created_at": "...",
            "updated_at": "..."
          }
        },
        "created_at": "2026-06-13T00:00:00.000000Z",
        "updated_at": "2026-06-13T00:00:00.000000Z"
      }
    ],
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Errors:** `401`, `422` (empty cart, validation failed, or insufficient stock)

---

### 5.2 Orders

#### GET /api/orders

List the authenticated user's orders with items and products, newest first.

**Auth:** Bearer Token

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "total_amount": 490000,
      "status": "pending",
      "shipping_address": "123 Test St",
      "phone_number": "1234567890",
      "payment_status": "unpaid",
      "payment_method": "cod",
      "items": [
        {
          "id": 1,
          "order_id": 1,
          "product_id": 1,
          "quantity": 2,
          "price": 245000,
          "product": { "data": { ... } },
          "created_at": "...",
          "updated_at": "..."
        }
      ],
      "created_at": "2026-06-13T00:00:00.000000Z",
      "updated_at": "2026-06-13T00:00:00.000000Z"
    }
  ]
}
```

**Errors:** `401`

---

#### POST /api/orders

Create an order from the current cart contents (without payment details). Equivalent to checkout with default values.

**Auth:** Bearer Token

**Request Body:**
```json
{
  "shipping_address": "123 Test St",
  "phone_number": "1234567890"
}
```

| Field | Type | Rules |
|-------|------|-------|
| `shipping_address` | string | required, max:255 |
| `phone_number` | string | required, max:20 |

**Response `201`:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "total_amount": 490000,
    "status": "pending",
    "shipping_address": "123 Test St",
    "phone_number": "1234567890",
    "payment_status": "unpaid",
    "payment_method": "cod",
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": 245000,
        "product": { "data": { ... } },
        "created_at": "...",
        "updated_at": "..."
      }
    ],
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Errors:** `401`, `422` (empty cart, validation failed, or insufficient stock)

---

#### GET /api/orders/{order}

Get a single order with items and products.

**Auth:** Bearer Token

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "user_id": 1,
    "total_amount": 490000,
    "status": "pending",
    "shipping_address": "123 Test St",
    "phone_number": "1234567890",
    "payment_status": "unpaid",
    "payment_method": "cod",
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": 245000,
        "product": { "data": { ... } },
        "created_at": "...",
        "updated_at": "..."
      }
    ],
    "created_at": "2026-06-13T00:00:00.000000Z",
    "updated_at": "2026-06-13T00:00:00.000000Z"
  }
}
```

**Errors:** `401`, `403` (not your order), `404`

---

## 6. Admin CRUD (Auth + Admin Role)

All admin endpoints require:
- A valid Sanctum Bearer token (`auth:sanctum`)
- User role must be `admin` (enforced by `AdminMiddleware`)
- Prefix: `/api/admin/...`

Non-admin authenticated users receive `403 Forbidden`.

### 6.1 Admin Categories

#### GET /api/admin/categories

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Kitchen Appliances",
      "slug": "kitchen-appliances",
      "description": "Essential appliances...",
      "image": null,
      "status": true,
      "created_at": "...",
      "updated_at": "..."
    }
  ]
}
```

#### POST /api/admin/categories

**Request Body:**
```json
{
  "name": "New Category",
  "slug": "new-category",
  "description": "Optional description",
  "image": null,
  "status": true
}
```

**Response `201`:**
```json
{
  "data": {
    "id": 6,
    "name": "New Category",
    "slug": "new-category",
    "description": "Optional description",
    "image": null,
    "status": true,
    "created_at": "...",
    "updated_at": "..."
  }
}
```

#### GET /api/admin/categories/{category}

**Response `200`:** Single category wrapped in `data`.

#### PUT /api/admin/categories/{category}

**Request Body:** (all fields optional)
```json
{
  "name": "Updated Name",
  "slug": "updated-slug"
}
```

**Response `200`:** Updated category wrapped in `data`.

#### DELETE /api/admin/categories/{category}

**Response `200`:**
```json
{
  "message": "Category deleted successfully."
}
```

**Errors:** `401`, `403`, `404`, `422`

---

### 6.2 Admin Brands

#### GET /api/admin/brands

**Response `200`:** Collection of brands wrapped in `data`.

#### POST /api/admin/brands

**Request Body:**
```json
{
  "name": "New Brand",
  "slug": "new-brand",
  "description": "Optional description",
  "logo": null,
  "status": true
}
```

**Response `201`:** Single brand wrapped in `data`.

#### GET /api/admin/brands/{brand}

**Response `200`:** Single brand wrapped in `data`.

#### PUT /api/admin/brands/{brand}

**Request Body:** (all fields optional)

**Response `200`:** Updated brand wrapped in `data`.

#### DELETE /api/admin/brands/{brand}

**Response `200`:**
```json
{
  "message": "Brand deleted successfully."
}
```

**Errors:** `401`, `403`, `404`, `422`

---

### 6.3 Admin Products

#### GET /api/admin/products

Paginated (12 per page). **Response `200`:**
```json
{
  "data": [ ... ],
  "meta": { ... },
  "links": { ... }
}
```

#### POST /api/admin/products

**Request Body:**
```json
{
  "name": "New Product",
  "slug": "new-product",
  "description": "Product description here",
  "price": 199000,
  "stock": 50,
  "category_id": 1,
  "brand_id": 1,
  "image": "optional-image.jpg",
  "status": true
}
```

| Field | Type | Rules |
|-------|------|-------|
| `name` | string | required, max:255 |
| `slug` | string | required, max:255, unique:products |
| `description` | string | required |
| `price` | numeric | required, min:0 |
| `stock` | integer | required, min:0 |
| `category_id` | integer | required, exists:categories,id |
| `brand_id` | integer | required, exists:brands,id |
| `image` | string|null | nullable, max:255 |
| `status` | boolean | nullable (default: true) |

**Response `201`:** Product wrapped in `data` with category and brand relations.

#### GET /api/admin/products/{product}

**Response `200`:** Single product wrapped in `data` with category and brand.

#### PUT /api/admin/products/{product}

**Request Body:** (all fields optional except those marked `sometimes`)

**Response `200`:** Updated product wrapped in `data`.

#### DELETE /api/admin/products/{product}

**Response `200`:**
```json
{
  "message": "Product deleted successfully."
}
```

**Errors:** `401`, `403`, `404`, `422`

---

## 7. Global Error Codes

| HTTP Code | Meaning | Typical Scenario |
|-----------|---------|-----------------|
| 200 | OK | Successful GET, PUT, DELETE |
| 201 | Created | Successful POST (resource created) |
| 401 | Unauthenticated | Missing or invalid Bearer token |
| 403 | Forbidden | Valid token but wrong user/role |
| 404 | Not Found | Resource ID does not exist |
| 422 | Unprocessable Entity | Validation failure or business logic error (e.g., insufficient stock, empty cart) |
| 500 | Internal Server Error | Unhandled exception (debug off) |
