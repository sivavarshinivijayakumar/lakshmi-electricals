# Lakshmi Electricals and Hardwares

Responsive PHP/MySQL shop catalogue and order-request website designed for XAMPP. The initial version includes customer registration/login, role-protected admin tools, category/product management, stock fields, a session cart, order requests and order-status management. It does not take online payments.

## Requirements
- XAMPP with Apache, MySQL and PHP 8.1+ (PDO MySQL and Fileinfo extensions enabled)
- A modern browser

## Install locally
1. Copy the `lakshmi-electricals` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Choose **Import**, select `database/schema.sql`, and run it. The script creates the `lakshmi_electricals` database and the ten initial categories. No products or prices are fabricated.
5. Open `config/database.php`. The defaults are for a typical local XAMPP installation (`root` with a blank password). If your MySQL credentials differ, update the values or set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` environment variables. Never commit production credentials.
6. Create the first administrator from a terminal. In Command Prompt, run:
   ```bat
   cd C:\xampp\htdocs\lakshmi-electricals
   C:\xampp\php\php.exe database\create_admin.php
   ```
   Enter the admin name, email and a strong password of at least 12 characters. This script refuses web execution; delete or move it outside the web root after creating the account if you prefer.
7. Open `http://localhost/lakshmi-electricals/`.
8. Sign in with the admin account, add categories/products and set real prices and stock. Customer registration is available from the header.

## Main routes
- `/index.php` — homepage
- `/products/index.php` — searchable product catalogue
- `/login.php`, `/register.php`, `/logout.php` — customer/admin session
- `/admin/dashboard.php` — admin summary
- `/admin/categories.php` — category CRUD
- `/admin/products.php` — product, price and stock management
- `/admin/orders.php` — order status management
- `/admin/users.php` — activate/deactivate customers
- `/cart/index.php` — cart
- `/cart/submit-order.php` — order request (login required)
- `/user/orders.php` — customer order history
- `/contact.php` — phone, email and supplied Google Maps link

## Branding
The referenced brief described an official logo image, but no logo image file was included with the text attachment used to build this package. The header currently uses a compact text-based LEH mark as a temporary placeholder. Replace it with the official logo image when available; do not treat the placeholder as the supplied logo.

## Security and deployment notes
- Passwords use `password_hash()` / `password_verify()`.
- Database operations use PDO prepared statements.
- Forms that mutate state use CSRF tokens; admin endpoints enforce the admin role server-side.
- Uploads accept JPG/PNG/WEBP up to 3 MB, use random filenames and have an `.htaccess` rule to block executable extensions.
- User-provided text is HTML-escaped.
- Product/category deletion is handled conservatively; products are hidden instead of physically deleted, and categories with assigned products cannot be deleted.
- Order items preserve the submitted product name and price as a snapshot. This initial version checks stock at submission but does not decrement stock automatically; the shop should adjust inventory when fulfilment is confirmed.
- Before public deployment: use HTTPS, set a strong database password and least-privilege DB user, move credentials outside the web root/environment, configure secure session cookies, review Apache rules for the chosen host, add backups, test upload rules on the host, and run a security review. The local `.htaccess` rules assume Apache 2.4.
- The app uses `/lakshmi-electricals` as its local base path. If deploying at a different URL path, update those paths in the PHP includes and assets.
- The shop's phone, email and supplied Maps URL are used as provided. No street address, opening hours, product prices, reviews or offers have been invented.

## Smoke-test checklist
1. Import schema and create admin.
2. Log in as admin; add a category and a product with an actual shop-confirmed price and stock.
3. Open the public catalogue and confirm the product/category appear.
4. Register a separate customer, add a product to cart and submit an order request.
5. Log in as admin and change the order status.
6. Confirm the customer can see the updated status in My Orders.
7. Try opening an `/admin/` page while signed out or as a customer; it should be blocked or redirect to login.
