# Design System

## Brand Colors

### Primary
```css
--color-primary: #1F2937;      /* Navy */
--color-primary-hover: #111827;
--color-primary-light: #374151;
```
**Used for:**
- Header
- Navigation
- Footer
- Main text
- Secondary buttons

### Accent (Commerce Action)
```css
--color-accent: #F59E0B;
--color-accent-hover: #D97706;
--color-accent-light: #FEF3C7;
```
**Used only for:**
- Add to Cart
- Buy Now
- Promotions
- Sale badges
- Important CTAs

---

## Neutral Scale
The neutral scale will do 80% of the visual work.

```css
--gray-50:  #FAFAF9;
--gray-100: #F5F5F4;
--gray-200: #E7E5E4;
--gray-300: #D6D3D1;
--gray-400: #A8A29E;
--gray-500: #78716C;
--gray-600: #57534E;
--gray-700: #44403C;
--gray-800: #292524;
--gray-900: #1C1917;
```

---

## Semantic Colors

### Success
```css
--success: #10B981;
--success-bg: #ECFDF5;
```
**Examples:** In stock, Order completed, Payment success

### Warning
```css
--warning: #F59E0B;
--warning-bg: #FFFBEB;
```
**Examples:** Limited stock, Expiring promotion

### Error
```css
--danger: #EF4444;
--danger-bg: #FEF2F2;
```
**Examples:** Payment failed, Validation errors

---

## Surface Colors

### Page
```css
--background: #FAFAF9;
```

### Cards
```css
--surface: #FFFFFF;
```

### Hover Surface
```css
--surface-hover: #F8FAFC;
```

### Border
```css
--border: #E5E7EB;
```

---

## Typography

### Font
I would recommend:
- **Inter Font**
- or **Geist Font**

### Scale
```css
--text-xs: 12px;
--text-sm: 14px;
--text-md: 16px;
--text-lg: 18px;
--text-xl: 20px;
--text-2xl: 24px;
--text-3xl: 30px;
--text-4xl: 36px;
```

### Font Weights
- **400** Regular
- **500** Medium
- **600** Semibold
- **700** Bold

> Avoid ultra-bold everywhere.

---

## Buttons

### Primary Button
```css
background: #F59E0B;
color: white;
```
**Visual:** 🟧 Add to Cart

### Primary Hover
```css
background: #D97706;
```

### Secondary Button
```css
background: white;
border: 1px solid #E5E7EB;
color: #1F2937;
```
**Visual:** ⬜ Save for Later

### Ghost Button
```css
background: transparent;
color: #1F2937;
```
**Used for:** Wishlist, Filters, Less important actions

---

## Header Design

```text
┌─────────────────────────────────────┐
│ LOGO    Search Bar      Cart User   │
├─────────────────────────────────────┤
│ Categories Navigation               │
└─────────────────────────────────────┘
```

**Header:**
```css
background: white;
border-bottom: 1px solid #E5E7EB;
```

**Search Focus:**
```css
border: 2px solid #F59E0B;
```
> This creates a strong commerce feel similar to major marketplaces.

---

## Product Card

```css
background: white;
border: 1px solid #E5E7EB;
border-radius: 16px;
```

**Hover:**
```css
transform: translateY(-2px);
box-shadow: 0 10px 30px rgba(0,0,0,.08);
```

**Structure:**
```text
┌────────────────┐
│ Product Image  │
├────────────────┤
│ Product Name   │
│ Rating ★★★★★   │
│ Price          │
│ Add to Cart    │
└────────────────┘
```

---

## Spacing System

Use an 8px grid.

- 4px
- 8px
- 16px
- 24px
- 32px
- 48px
- 64px
- 96px

**Examples:**
- Card Padding: `24px`
- Section Gap: `64px`
- Button Padding: `12px 20px`

---

## Border Radius

- **Small:** 8px
- **Medium:** 12px
- **Large:** 16px
- **XL:** 24px

**Recommended:**
- Buttons: `12px`
- Inputs: `12px`
- Cards: `16px`

---

## Shadows

### Small
```css
0 1px 3px rgba(0,0,0,.05)
```

### Medium
```css
0 4px 12px rgba(0,0,0,.08)
```

### Large
```css
0 10px 30px rgba(0,0,0,.12)
```

> Use shadows sparingly. Most modern e-commerce sites rely more on spacing and borders than heavy shadows.

---

## Recommended Color Distribution

A useful rule:
- **70%** Neutral backgrounds
- **20%** Navy (`#1F2937`)
- **10%** Orange (`#F59E0B`)
