# Kitchen Ecommerce — Frontend Design System

> **Theme:** Sleek Tech (Slate & Indigo)
> **API Base:** `http://localhost:8000/api`
> **Auth:** Bearer token (`Authorization: Bearer <token>`)

---

## 1. Core Visual System

### Colors
| Token | Value | Usage |
|-------|-------|-------|
| `bg-slate-50` | `#f8fafc` | Page backgrounds |
| `bg-white` | `#ffffff` | Card / container backgrounds |
| `text-slate-900` | `#0f172a` | Headings |
| `text-slate-600` | `#475569` | Body text |
| `bg-indigo-600` | `#4f46e5` | Primary buttons, links, active states |
| `hover:bg-indigo-700` | `#4338ca` | Primary button hover |
| `text-indigo-600` | `#4f46e5` | Interactive text (links) |
| `border-slate-200` | `#e2e8f0` | Card borders |
| `border-indigo-500` | `#6366f1` | Card hover border |

### Status Indicators
| Context | Class | Meaning |
|---------|-------|---------|
| Success / In stock | `text-emerald-600` | `stock > 5` |
| Low stock warning | `text-amber-600` | `1 <= stock <= 5` |
| Out of stock / Error | `text-rose-600` | `stock === 0` or `payment_status === 'failed'` |

---

## 2. Typography

- **Font stack:** `font-sans` — Inter (preferred) or Geist (fallback)
- **Headings:** `font-semibold text-slate-900`
- **Body:** `text-sm text-slate-600`
- **Price:** `text-lg font-bold text-slate-900`
- **No serif fonts** anywhere in the UI.

---

## 3. Data Binding Map

> All API responses are wrapped in a `data` key (exception: `GET /api/user` returns flat user, `auth` endpoints return `user` + `token`).

### 3.1 Authentication

```
POST /api/register
Request:  { name, email, password, password_confirmation }
Response: { user: { id, name, email, role, phone, address, avatar, created_at, updated_at }, token }

POST /api/login
Request:  { email, password }
Response: { user: { id, name, email, role, phone, address, avatar, created_at, updated_at }, token }

POST /api/logout
Response: { message }

GET /api/user  (flat — no "data" wrapper)
Response: { id, name, email, role, phone, address, avatar, created_at, updated_at }
```

### 3.2 Product Catalog

```
GET /api/products?page=&search=&category_id=&brand_id=&min_price=&max_price=
Response: {
  data: [{
    id, name, slug, description, price (float), stock, image, status,
    category_id, brand_id,
    category: { id, name, slug, description, image, status, created_at, updated_at },
    brand:    { id, name, slug, description, logo, status, created_at, updated_at },
    created_at, updated_at
  }],
  meta: { current_page, last_page, per_page, total },
  links: { first, last, prev, next }
}

GET /api/products/{id}
Response: { data: { ...single product object... } }
```

#### Product Card Component
| UI Element | Data Binding | Notes |
|-----------|-------------|-------|
| Image | `product.image` | Source URL string |
| Title | `product.name` | Plain text, max 2 lines |
| Brand subtitle | `product.brand.name` | Light grey text below title |
| Category label | `product.category.name` | Small badge/chip |
| Price | `product.price` | Format as currency (VND: `new Intl.NumberFormat('vi-VN')`); value is `float` |
| Stock badge | `product.stock` | `> 5` → emerald "In Stock", `1-5` → amber "Low Stock", `0` → rose "Out of Stock" |
| Container | `border border-slate-200/80 rounded-lg hover:border-indigo-500 hover:shadow-lg transition-all duration-300` | |

### 3.3 Categories & Brands

```
GET /api/categories
Response: { data: [{ id, name, slug, description, image, status, created_at, updated_at }] }

GET /api/brands
Response: { data: [{ id, name, slug, description, logo, status, created_at, updated_at }] }
```

### 3.4 Cart

```
GET /api/cart
Response: { data: [{ id, user_id, product_id, quantity, product: { ...nested product... }, created_at, updated_at }] }

POST /api/cart
Request:  { product_id, quantity }
Response: { data: { id, user_id, product_id, quantity, product: { ... }, created_at, updated_at } }
  → 201 new item, 200 quantity increased (merged)

PUT /api/cart/{id}
Request:  { quantity }
Response: { data: { ...updated cart item... } }

DELETE /api/cart/{id}
Response: { message: "Cart item removed." }
```

#### Cart Card Component
| UI Element | Data Binding | Notes |
|-----------|-------------|-------|
| Image | `item.product.image` | Nested inside `product` |
| Title | `item.product.name` | |
| Unit price | `item.product.price` | |
| Quantity | `item.quantity` | Integer, min 1 |
| Subtotal | `item.product.price * item.quantity` | Compute client-side, NOT from API |

### 3.5 Orders & Checkout

```
POST /api/checkout
Request:  { shipping_address, phone_number, payment_method }
Response: { data: { id, user_id, total_amount (float), status, shipping_address, phone_number, payment_status, payment_method, items: [...], created_at, updated_at } }

GET /api/orders
Response: { data: [{ ...order objects... }] }

GET /api/orders/{id}
Response: { data: { ...single order object... } }

POST /api/orders
Request:  { shipping_address, phone_number }
Response: { data: { ...order object... } }
```

#### Order Summary Component
| UI Element | Data Binding | Notes |
|-----------|-------------|-------|
| Order ID | `order.id` | Display as `#<id>` |
| Total | `order.total_amount` | **Use `total_amount`** — NOT `total` |
| Shipping address | `order.shipping_address` | Full address string |
| Phone | `order.phone_number` | **Use `phone_number`** — NOT `phone` |
| Status badge | `order.status` | `pending` / `confirmed` / `shipped` / `delivered` / `cancelled` |
| Payment status | `order.payment_status` | `unpaid` / `pending` / `paid` / `failed` |
| Payment method | `order.payment_method` | `cod` / `bank_transfer` |
| Items list | `order.items` | Array of OrderItem objects |
| Item image | `item.product.image` | |
| Item name | `item.product.name` | |
| Item price | `item.price` | Price at time of purchase (float) |
| Item quantity | `item.quantity` | |

#### OrderItem Object Shape
```
{
  id, order_id, product_id, quantity, price (float),
  product: { id, name, slug, description, price, stock, image, status, category_id, brand_id, created_at, updated_at },
  created_at, updated_at
}
```

---

## 4. HTTP Error Handling

| Code | Meaning | Frontend Action |
|------|---------|-----------------|
| 200 | OK | Success (GET, PUT, DELETE) |
| 201 | Created | Success (POST — resource created) |
| 401 | Unauthenticated | Redirect to login page |
| 403 | Forbidden | Show "Access denied" toast |
| 404 | Not Found | Show "Resource not found" |
| 422 | Validation | Display field-level errors from `errors` object |
| 500 | Server Error | Show generic error toast |

**Error response shape:**
```json
{ "message": "Human-readable message", "errors": { "field": ["error1", "error2"] } }
```

---

## 5. Response Wrapping Convention

| Endpoint Group | Wrapped in `data`? | Notes |
|---------------|-------------------|-------|
| GET /api/user | **No** (flat) | Direct user object |
| POST /api/register, /api/login | **No** (flat) | `{ user: {...}, token: "..." }` |
| All other API responses | **Yes** | Access via `response.data` |
| Paginated collections | **Yes** | Also includes `meta` and `links` at root level |
