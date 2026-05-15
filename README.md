# Seller Role — ShopHub Marketplace

## Overview
This is the Seller role implementation for the WebTech group project.
Built with PHP MVC architecture, MySQL, AJAX, and session-based RBAC.

## Developer
- **Role:** Seller / Vendor (Role 2)
- **Stack:** PHP, MySQL, JavaScript (XMLHttpRequest), CSS

## Features Implemented

### Authentication
- Seller registration with shop name, description, address, logo upload
- Admin approval flow — sellers can only login after approval
- Session-based login with role check
- Secure logout with session destroy

### Shop Profile
- View current shop info (name, description, address, logo, status)
- Update shop name, description, address
- Upload and replace shop logo (JPG/PNG/WEBP, max 2MB)

### Product Management
- List all products with category, price, stock, availability status
- Add new product with primary image + up to 4 additional images
- Edit product details and replace primary image
- Toggle product availability (active/inactive)
- Delete product — blocked if linked to active orders

### Stock Management (AJAX)
- Inline stock quantity update without page reload (XMLHttpRequest)
- Low stock alert checker — fetches products below threshold via AJAX

### Coupon Management
- Create promotional coupon codes with discount %, max uses, expiry date
- Live preview widget on create form
- Usage progress bar per coupon
- Activate / deactivate coupons
- Delete coupons

### Order Management
- View all incoming order items with status filter tabs
- Pending order badge counter
- Full order detail: customer info, shipping address, all items
- Confirm order items (pending → processing)
- Mark as shipped with tracking note (processing → shipped)

### Return Requests
- View all return requests for seller's products
- Inline approve / reject form with required reason
- Pending return counter

### Reviews
- View all customer reviews with star ratings
- Average rating stat card
- Reply to reviews inline
- Edit existing replies
- Unreplied reviews highlighted

### Analytics Dashboard (AJAX)
- Period filter: 7 / 30 / 90 days
- Total revenue, total orders, average order value
- Earnings summary: gross revenue, platform commission (10%), net payout
- Top 5 selling products with revenue share bar
- 7-day revenue bar chart loaded via AJAX (XMLHttpRequest + Chart.js)

## AJAX Features
| Endpoint | Method | Purpose |
|---|---|---|
| `api/stock_update.php` | POST | Update product stock quantity inline |
| `api/low_stock.php` | GET | Fetch products below stock threshold |
| `api/analytics_data.php` | GET | Fetch 7-day daily revenue for chart |

## MVC Structure