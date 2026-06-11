# Kitchen Ecommerce — API Contract

**Base URL:** `http://localhost:8000/api`  
**Auth:** Sanctum token-based (Bearer token in `Authorization` header)  
**Response Format:** JSON

---

## 1. Authentication

### POST /api/register

Create a new user account and return a Sanctum token.

**Request:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "Password1!",
  "password_confirmation": "Password1!"
}
```

**Response `201`:**
```json
{
  "user": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "role": "user",
    "created_at": "2026-06-09T00:00:00.000000Z",
    "updated_at": "2026-06-09T00:00:00.000000Z"
  },
  "token": "1|abc123..."
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 422  | Validation failed (missing/invalid fields, email taken, password mismatch) |

---

### POST /api/login

Authenticate with email and password, return a Sanctum token.

**Request:**
```json
{
  "email": "jane@example.com",
  "password": "Password1!"
}
```

**Response `200`:**
```json
{
  "user": { "id": 1, "name": "Jane Doe", "email": "jane@example.com" },
  "token": "2|xyz789..."
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 422  | Invalid credentials or validation failed |

---

### POST /api/logout

Revoke the current access token.

- **Auth:** Required (Bearer token)

**Response `200`:**
```json
{
  "message": "Logged out successfully."
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |

---

### GET /api/user

Fetch the authenticated user's profile.

- **Auth:** Required (Bearer token)

**Response `200`:**
```json
{
  "id": 1,
  "name": "Jane Doe",
  "email": "jane@example.com",
  "role": "user",
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |

---

## 2. Products

### GET /api/products

List products with pagination (12 per page), search, and filtering.

**Query Parameters:**
| Param | Type | Description |
|-------|------|-------------|
| `page` | int | Page number (default: 1) |
| `search` | string | Search by product name |
| `category_id` | int | Filter by category ID |
| `brand_id` | int | Filter by brand ID |
| `min_price` | float | Minimum price |
| `max_price` | float | Maximum price |

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Premium Blender",
      "slug": "premium-blender",
      "description": "High-speed blender for smoothies and soups.",
      "price": "89.99",
      "stock": 25,
      "image": "products/abc-123.jpg",
      "status": true,
      "category_id": 1,
      "brand_id": 1,
      "category": {
        "id": 1,
        "name": "Kitchen Appliances",
        "slug": "kitchen-appliances",
        "description": null,
        "image": null,
        "status": true,
        "created_at": "2026-06-09T00:00:00.000000Z",
        "updated_at": "2026-06-09T00:00:00.000000Z"
      },
      "brand": {
        "id": 1,
        "name": "Philips",
        "slug": "philips",
        "description": null,
        "logo": null,
        "status": true,
        "created_at": "2026-06-09T00:00:00.000000Z",
        "updated_at": "2026-06-09T00:00:00.000000Z"
      },
      "created_at": "2026-06-09T00:00:00.000000Z",
      "updated_at": "2026-06-09T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 12,
    "total": 60
  },
  "links": {
    "first": "http://localhost/api/products?page=1",
    "last": "http://localhost/api/products?page=5",
    "prev": null,
    "next": "http://localhost/api/products?page=2"
  }
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 422  | Invalid filter values |

---

### GET /api/products/{product}

Get a single product by ID with category and brand included.

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "Premium Blender",
    "slug": "premium-blender",
    "description": "High-speed blender for smoothies and soups.",
    "price": "89.99",
    "stock": 25,
    "image": "products/abc-123.jpg",
    "status": true,
    "category_id": 1,
    "brand_id": 1,
    "category": { "id": 1, "name": "Kitchen Appliances" },
    "brand": { "id": 1, "name": "Philips" },
    "created_at": "2026-06-09T00:00:00.000000Z",
    "updated_at": "2026-06-09T00:00:00.000000Z"
  }
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 404  | Product not found |

---

## 3. Categories

### GET /api/categories

List all categories.

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Kitchen Appliances",
      "slug": "kitchen-appliances",
      "description": "All kitchen appliances including blenders, mixers, etc.",
      "image": "categories/kitchen-appliances.jpg",
      "status": true,
      "created_at": "2026-06-09T00:00:00.000000Z",
      "updated_at": "2026-06-09T00:00:00.000000Z"
    }
  ]
}
```

### GET /api/categories/{category}

Get a single category by ID.

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "Kitchen Appliances",
    "slug": "kitchen-appliances",
    "description": "All kitchen appliances including blenders, mixers, etc.",
    "image": "categories/kitchen-appliances.jpg",
    "status": true,
    "created_at": "2026-06-09T00:00:00.000000Z",
    "updated_at": "2026-06-09T00:00:00.000000Z"
  }
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 404  | Category not found |

---

## 4. Brands

### GET /api/brands

List all brands.

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Philips",
      "slug": "philips",
      "description": "Leading electronics brand.",
      "logo": "brands/philips.png",
      "status": true,
      "created_at": "2026-06-09T00:00:00.000000Z",
      "updated_at": "2026-06-09T00:00:00.000000Z"
    }
  ]
}
```

### GET /api/brands/{brand}

Get a single brand by ID.

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "Philips",
    "slug": "philips",
    "description": "Leading electronics brand.",
    "logo": "brands/philips.png",
    "status": true,
    "created_at": "2026-06-09T00:00:00.000000Z",
    "updated_at": "2026-06-09T00:00:00.000000Z"
  }
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 404  | Brand not found |

---

## 5. Cart

All cart endpoints require **Bearer token** (auth:sanctum).

### GET /api/cart

Get the authenticated user's cart items with product details.

**Response `200`:**
```json
[
  {
    "id": 1,
    "user_id": 1,
    "product_id": 1,
    "quantity": 2,
    "status": null,
    "product": {
      "id": 1,
      "name": "Premium Blender",
      "slug": "premium-blender",
      "price": "89.99",
      "stock": 25,
      "image": "products/abc-123.jpg",
      "status": true
    },
    "created_at": "2026-06-09T00:00:00.000000Z",
    "updated_at": "2026-06-09T00:00:00.000000Z"
  }
]
```

---

### POST /api/cart

Add a product to cart (or increase quantity if already in cart).

**Request:**
```json
{
  "product_id": 1,
  "quantity": 2
}
```

**Response `201`** (new item):
```json
{
  "id": 1,
  "user_id": 1,
  "product_id": 1,
  "quantity": 2,
  "status": null,
  "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Response `200`** (existing item — quantity increased):
```json
{
  "id": 1,
  "user_id": 1,
  "product_id": 1,
  "quantity": 5,
  "status": null,
  "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |
| 422  | Validation failed or insufficient stock |
| 404  | Product not found |

---

### PUT /api/cart/{cart}

Update the quantity of a cart item.

**Request:**
```json
{
  "quantity": 3
}
```

**Response `200`:**
```json
{
  "id": 1,
  "user_id": 1,
  "product_id": 1,
  "quantity": 3,
  "status": null,
  "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |
| 403  | Forbidden (not your cart item) |
| 404  | Cart item not found |
| 422  | Validation failed or insufficient stock |

---

### DELETE /api/cart/{cart}

Remove a cart item.

**Response `200`:**
```json
{
  "message": "Cart item removed."
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |
| 403  | Forbidden (not your cart item) |
| 404  | Cart item not found |

---

## 6. Orders

### GET /api/orders

List the authenticated user's orders with items and products.

**Response `200`:**
```json
[
  {
    "id": 1,
    "user_id": 1,
    "total_amount": "179.98",
    "status": "pending",
    "shipping_address": "123 Test St",
    "phone_number": "1234567890",
    "payment_status": null,
    "payment_method": null,
    "items": [
      {
        "id": 1,
        "order_id": 1,
        "product_id": 1,
        "quantity": 2,
        "price": "89.99",
        "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
        "created_at": "2026-06-09T00:00:00.000000Z",
        "updated_at": "2026-06-09T00:00:00.000000Z"
      }
    ],
    "created_at": "2026-06-09T00:00:00.000000Z",
    "updated_at": "2026-06-09T00:00:00.000000Z"
  }
]
```

---

### POST /api/orders

Create an order from the current cart contents (without payment details).

**Request:**
```json
{
  "shipping_address": "123 Test St",
  "phone_number": "1234567890"
}
```

**Response `201`:**
```json
{
  "id": 1,
  "user_id": 1,
  "total_amount": "179.98",
  "status": null,
  "shipping_address": "123 Test St",
  "phone_number": "1234567890",
  "payment_status": null,
  "payment_method": null,
  "items": [
    {
      "id": 1,
      "order_id": 1,
      "product_id": 1,
      "quantity": 2,
      "price": "89.99",
      "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
      "created_at": "2026-06-09T00:00:00.000000Z",
      "updated_at": "2026-06-09T00:00:00.000000Z"
    }
  ],
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |
| 422  | Empty cart, validation failed, or insufficient stock |

---

### GET /api/orders/{order}

Get a single order with items and products.

**Response `200`:**
```json
{
  "id": 1,
  "user_id": 1,
  "total_amount": "179.98",
  "status": null,
  "shipping_address": "123 Test St",
  "phone_number": "1234567890",
  "payment_status": null,
  "payment_method": null,
  "items": [
    {
      "id": 1,
      "order_id": 1,
      "product_id": 1,
      "quantity": 2,
      "price": "89.99",
      "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
      "created_at": "2026-06-09T00:00:00.000000Z",
      "updated_at": "2026-06-09T00:00:00.000000Z"
    }
  ],
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |
| 403  | Forbidden (not your order) |
| 404  | Order not found |

---

## 7. Checkout

### POST /api/checkout

Create an order with payment details from the current cart contents.

**Request:**
```json
{
  "shipping_address": "123 Test St",
  "phone_number": "1234567890",
  "payment_method": "cod"
}
```

**Payment methods:** `cod` (cash on delivery) or `bank_transfer`

**Response `201`:**
```json
{
  "id": 1,
  "user_id": 1,
  "total_amount": "179.98",
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
      "price": "89.99",
      "product": { "id": 1, "name": "Premium Blender", "price": "89.99" },
      "created_at": "2026-06-09T00:00:00.000000Z",
      "updated_at": "2026-06-09T00:00:00.000000Z"
    }
  ],
  "created_at": "2026-06-09T00:00:00.000000Z",
  "updated_at": "2026-06-09T00:00:00.000000Z"
}
```

**Errors:**
| Code | Description |
|------|-------------|
| 401  | Unauthenticated |
| 422  | Empty cart, validation failed, or insufficient stock |

---

## Global Error Codes

| HTTP Code | Meaning |
|-----------|---------|
| 200       | Success |
| 201       | Created |
| 401       | Unauthenticated (missing/invalid token) |
| 403       | Forbidden (not your resource) |
| 404       | Resource not found |
| 422       | Validation failed |

## Authentication Header

```
Authorization: Bearer 1|abc123...
```

All routes under the `auth:sanctum` middleware require this header.
