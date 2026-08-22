# NOIR THREADS — T-Shirt E-Commerce Store

A complete PHP + MySQL e-commerce project: home page with a sliding photo banner,
product catalog with filters, cart, checkout, customer accounts, order history,
and a full admin panel for managing products and orders.

---

## සිංහලෙන් Setup කරන විදිය (Step by Step)

### 1. XAMPP install කරන්න
[https://www.apachefriends.org](https://www.apachefriends.org) වෙතින් XAMPP download කරලා install කරන්න (Windows/Mac/Linux ඕනෑම එකක් වැඩ).

### 2. Project folder එක copy කරන්න
`tshirt-store` folder එක සම්පූර්ණයෙන්ම copy කරලා දාන්න:
- **Windows:** `C:\xampp\htdocs\tshirt-store`
- **Mac:** `/Applications/XAMPP/htdocs/tshirt-store`

(`htdocs` folder එක ඇතුලේ `tshirt-store` කියලා folder එකක් තිබිය යුතුයි.)

### 3. XAMPP start කරන්න
XAMPP Control Panel එක open කරලා **Apache** සහ **MySQL** දෙකම **Start** කරන්න.

### 4. Database එක create කරන්න
1. Browser එකේ මෙතනට යන්න: `http://localhost/phpmyadmin`
2. උඩ "Import" tab එක click කරන්න
3. "Choose File" click කරලා `database/tshirt_store.sql` file එක select කරන්න
4. පහළ "Go" button එක click කරන්න
5. දැන් `tshirt_store` කියලා database එකක් සහ tables + sample products ඔටோමැටිකලි create වෙනවා

### 5. Site එක open කරන්න
Browser එකේ මෙතනට යන්න:
```
http://localhost/tshirt-store/index.php
```

### 6. Admin Panel එකට යන විදිය
```
http://localhost/tshirt-store/admin/login.php
```
- Username: `admin`
- Password: `admin123`

Admin panel එකෙන් products add/edit/delete කරන්නත්, orders වල status (Pending → Processing → Shipped → Delivered) update කරන්නත් පුළුවන්.

---

## What's included / Features

**Customer side**
- Home page with an auto-sliding hero banner (photo slider)
- **20 products** across Men, Women, Unisex, and Kids categories
- Product catalog with category, size, price filters, and pagination
- Product detail page with size/color selection, quantity picker, and image zoom-on-hover
- Multi-photo gallery per product — thumbnails, prev/next arrows, and touch swipe on mobile
- Star ratings & customer reviews (write, view, delete your own)
- Toast notifications ("Added to cart!") — no page reloads
- Coupon/discount codes at checkout
- Live stock awareness — "Out of Stock" / "Only X left" badges, can't order more than available
- Shopping cart (add, update quantity, remove)
- Checkout with delivery details form
- Customer registration/login (passwords are hashed, never stored in plain text)
- Order history for logged-in customers
- Working Delivery Info, Returns, and Contact Us pages (contact form saves to the database)

**Admin side**
- Login-protected admin panel with a proper sidebar (Dashboard, Products, Categories, Orders, Reviews, Coupons, Messages, Settings)
- Add / edit / delete products, with low-stock highlighting, search, and pagination
- **Category management** — add, rename, delete (blocked if products still use it)
- View and update order status
- Moderate reviews (delete any review)
- Create and manage discount coupons (percentage or fixed amount, with optional expiry)
- **View & manage contact form messages** from customers
- **Change admin password** from Settings
- Dashboard with quick stats, low-stock/unread-message alerts, a **Top Selling Products** table, and a 6-month sales chart (Chart.js)

**Tech stack**
- PHP (procedural, easy to read and modify) + MySQLi with prepared statements
- Plain HTML/CSS/JS — no frameworks, so it's easy to explain in a viva
- Chart.js (via CDN) for the admin sales graph
- Sessions used for the cart and coupon (no login required to add items to cart)

**Sample coupon codes** (seeded in the database, manageable from Admin → Coupons):
- `WELCOME10` — 10% off
- `FLAT500` — Rs. 500 off

## Notes for your project report

- **About the product photos:** every product ships with a color-coded placeholder
  photo (main + 2 gallery images), generated consistently so the site looks
  organized out of the box. These are **not real garment photos** — swap them out
  before your final submission for a more authentic look. See the "Replacing
  placeholder photos" section below for exactly how.
- **Product photo galleries:** in Admin → Products → Add/Edit, the "Image URL"
  field is the main cover photo (shown on listings). Below it there's an "Extra
  Gallery Photos" box where you can paste more image URLs — one per line (2–4
  recommended). On the product page, customers can click the thumbnails, use the
  arrows, or swipe on mobile to browse all the photos.
- If you already imported the database earlier (before this update), import
  `database/add_features.sql` too — it adds the new tables/columns needed for
  reviews, coupons, categories management, contact messages, etc. Fresh installs
  can skip this and just import `tshirt_store.sql`, which already includes
  everything.
- Card/online payment is a **visual demo only** — it doesn't connect to a real
  payment gateway, which is standard for a university project. You can mention
  this clearly in your report/viva if asked.
- Feel free to rename "NOIR THREADS" to your own brand name — it appears in
  `includes/header.php`, `includes/footer.php`, and the `<title>` tags.

## Replacing placeholder photos with real ones

1. Go to **unsplash.com** or **pexels.com** (both free, no attribution required
   for commercial use).
2. Search for the product type (e.g. "black t-shirt", "streetwear t-shirt").
3. Open a photo you like, right-click it, and choose **"Copy Image Address"**.
4. In Admin → Products → Edit Product, paste that link into the **Image URL**
   field (and optionally add 2–3 more into "Extra Gallery Photos").
5. Save. Repeat for each product — or leave it as-is; the placeholders are
   still clean and consistent, they're just not real photography.

## Folder structure

```
tshirt-store/
├── database/tshirt_store.sql   ← import this first
├── config/db.php               ← database connection settings
├── includes/                   ← shared header/footer
├── admin/                      ← admin panel (products, orders, dashboard)
├── css/style.css               ← all styling
├── js/script.js                ← slider + cart quantity controls
├── index.php                   ← home page
├── products.php                ← shop/listing page
├── product.php                 ← single product page
├── cart.php, checkout.php      ← cart & checkout flow
├── login.php, register.php     ← customer auth
└── orders.php                  ← customer order history
```
