# Northstar Order Management System

Northstar is a polished, responsive order-management frontend and Laravel 11 API for the practical coding test brief. The frontend is runnable without a build step so the workflow can be reviewed immediately: open `index.html` in a browser.

## Included workflow

- Admin and Staff demo login states with role-aware product deletion.
- Vendor dashboard with partner CRUD, rating/status tracking, and vendor demo login.
- Dashboard metrics for customers, products, orders, sales, and low stock.
- Searchable product, customer, and order tables.
- Multi-item order creation with live totals, stock limits, stock deduction, and success/error feedback.
- Local persistence through `localStorage`, so a placed order survives a refresh.
- Responsive layout for desktop and mobile.

Demo accounts: `admin@northstar.test` / `password`, `staff@northstar.test` / `password`, and `vendor@northstar.test` / `password`.

## Laravel API contract

The UI is intentionally decoupled from the backend boundary. Replace the local store functions in `app.js` with requests to these Laravel routes:

| Method | Route | Access |
| --- | --- | --- |
| POST | `/api/login`, `/api/logout` | Public / authenticated |
| GET/POST | `/api/products` | Authenticated |
| GET/PATCH/DELETE | `/api/products/{product}` | Admin for delete |
| GET/POST | `/api/customers` | Authenticated |
| GET/PATCH/DELETE | `/api/customers/{customer}` | Admin for delete |
| GET/POST | `/api/vendors` | Admin |
| GET/PATCH/DELETE | `/api/vendors/{vendor}` | Admin |
| GET/POST | `/api/orders` | Admin or Staff |
| GET | `/api/dashboard` | Authenticated |

Suggested Laravel structure: `ProductController`, `CustomerController`, `OrderController`, `DashboardController`, request classes for validation, policies for role checks, and a dedicated `OrderService` for stock-aware order processing. Add migrations for users, products, customers, orders, and order_items, plus seeders for the credentials and sample catalog.

## Run the Laravel API

Requirements: PHP 8.2+, Composer, MySQL 8+, and a generated `APP_KEY`.

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The API is then available at `http://127.0.0.1:8000/api`. Send the token returned by `/api/login` as `Authorization: Bearer <token>`. Product and customer list endpoints accept `?search=` and return Laravel pagination metadata. `GET /api/products/{product}` and `GET /api/customers/{customer}` provide detail views.

## Vercel deployment

The repository includes `vercel.json` and `api/index.php`, which route `/api/*` requests into Laravel’s public entrypoint while serving the existing frontend as a static page. Configure `APP_KEY`, `APP_ENV`, `APP_DEBUG=false`, `APP_URL`, `DB_*`, `SANCTUM_STATEFUL_DOMAINS`, and `FRONTEND_URL` as Vercel environment variables. Use a managed MySQL provider because Vercel functions do not provide persistent local storage. Run migrations against that database from a deployment job or a trusted local environment before using the API.

## Stock concurrency answer

A plain `if ($product->stock >= $quantity)` check is unsafe because two requests can read the same stock before either request writes. Both can pass the check and oversell the last units. The Laravel service should start `DB::transaction`, lock each product row with `Product::whereKey($id)->lockForUpdate()->firstOrFail()`, re-check the available stock after the lock, create the order and order items, decrement stock, and commit. A cancellation should use another transaction and `lockForUpdate()` before restoring stock. The database should also enforce positive quantities and foreign-key integrity.

If the API returns `409 Conflict` with `stock_unavailable`, the React UI should keep the customer and valid lines, mark the affected item as unavailable, refresh that product's stock, recalculate the displayed total, and let the user adjust or remove the line. It should not retry the order blindly.

## Backend hardening checklist

- Laravel Sanctum or session authentication with CSRF protection.
- Form Requests for SKU uniqueness, non-negative price/stock, customer fields, and order item quantities.
- Policies for admin-only destructive actions.
- Server-calculated totals; ignore any total supplied by the browser.
- Feature tests for concurrent stock, cancellation restoration, validation, and role authorization.
- API resources with consistent JSON errors and pagination metadata.