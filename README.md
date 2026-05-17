# UM-Pasa: University Marketplace

UM-Pasa is a Laravel-based university marketplace for the University of Mindanao community. It helps students browse, sell, rent, request, message, transact, upload payment proof, view receipts, leave ratings, and allows administrators to monitor and moderate marketplace activity.

This project is built for an academic BSIT presentation. The target is an **excellent academic system**: complete core workflows, clean Laravel MVC structure, secure role-based access, readable UI, and practical deployment readiness without unnecessary enterprise features.

## Project Summary

| Item | Details |
|---|---|
| Project name | UM-Pasa |
| System type | University marketplace web application |
| Target users | Guests, students, sellers, buyers, renters, administrators |
| Framework | Laravel 13 |
| Backend | PHP 8.4+, Eloquent ORM, Laravel authentication |
| Frontend | Blade, Tailwind CSS, Alpine.js, Vite |
| Database | SQLite for local/demo or MySQL depending on `.env` |
| Testing | PHPUnit feature tests |
| Deployment target | Railway deployment supported through environment variables |

## Core Problem

Students often need books, calculators, uniforms, gadgets, laboratory tools, and academic supplies for only one course or semester. These items can be expensive, and posts in social media groups are hard to search, verify, and monitor.

UM-Pasa solves this by providing one organized university marketplace where students can list academic items, request items, coordinate meetups, track transactions, receive notifications, view receipts, and build trust through ratings.

## Main Features

- Guest marketplace browsing and item details
- Student registration, login, logout, and profile management
- UM email domain validation during registration
- Item listing CRUD for sale and rental listings
- Marketplace search, category filters, sorting, and item status display
- Admin approval/rejection workflow for listings
- Buyer request workflow for available items
- Seller approve/reject workflow for item requests
- Payment method selection and payment proof upload
- Private payment proof access through an authorized Laravel route
- Rental duration and rental due-date tracking
- Transaction history, receipts, and printable report pages
- Buyer/seller messaging and meetup proposals
- Notifications for important account and transaction updates
- Transaction-based ratings to support accountability
- Student dashboard with listings, notifications, and statistics
- Admin dashboard with user, listing, transaction, and report monitoring

## User Roles

| Role | Permissions |
|---|---|
| Guest | View the landing page, help page, marketplace, and item details |
| Student | Register, log in, create listings, request items, message users, manage transactions, upload payment proof, view receipts, and rate completed transactions |
| Admin | View dashboards, manage users, moderate listings, monitor transactions, and generate reports |

## Demo Accounts

All seeded demo accounts use this password:

```text
password
```

| Role | Name | Email |
|---|---|---|
| Admin | Admin User | `admin@umindanao.edu.ph` |
| Student | Student User | `student@umindanao.edu.ph` |
| Seller | Ava Seller | `seller@umindanao.edu.ph` |
| Buyer | Marco Buyer | `buyer@umindanao.edu.ph` |
| Renter | Bea Renter | `renter@umindanao.edu.ph` |
| Second seller | Jessa Seller | `seller2@umindanao.edu.ph` |

Demo accounts and sample marketplace data are created in:

```text
database/seeders/DatabaseSeeder.php
```

## Quick Start

### 1. Install dependencies

```bash
composer install
npm install
```

### 2. Create environment file

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure the database

For SQLite:

```env
DB_CONNECTION=sqlite
```

Then create the database file:

```bash
touch database/database.sqlite
```

For MySQL, configure these values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=um_pasa
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run database setup

```bash
php artisan migrate --seed
```

### 5. Link public storage

This is needed for public item images. Payment proofs are stored privately and served only through an authorized route.

```bash
php artisan storage:link
```

### 6. Build assets and start the app

```bash
npm run build
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Development Commands

Run Laravel and Vite separately:

```bash
php artisan serve
npm run dev
```

Or run the project development script:

```bash
composer run dev
```

Run tests:

```bash
php artisan test
```

Run Pint formatting check:

```bash
./vendor/bin/pint --test
```

Build frontend assets:

```bash
npm run build
```

Clear cached config, routes, views, and events before a local demo:

```bash
php artisan optimize:clear
```

## Verification Checklist

Before presentation, these commands should pass:

```bash
composer validate --strict
composer audit
npm audit
./vendor/bin/pint --test
php artisan test
npm run build
php artisan route:list
php artisan migrate:fresh --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize:clear
```

Notes:

- `migrate:fresh --seed` resets the local database, so use it only when you are preparing demo data.
- `optimize:clear` is included at the end so the local environment returns to a clean demo state after cache testing.
- Do not commit real `.env` secrets, database files, uploaded payment proofs, or local Railway artifacts.

## Railway Deployment Note

The system can run outside local development through Railway as long as Railway environment variables are configured correctly. Keep secrets in Railway variables, not in the repository.

Important Railway values normally include:

- `APP_KEY`
- `APP_ENV`
- `APP_DEBUG`
- `APP_URL`
- database connection variables
- mail variables, if email features are enabled

This README documents the expected setup but does not change Railway configuration files or production settings.

## Laravel MVC Architecture

UM-Pasa follows Laravel's MVC pattern:

| Layer | Project usage |
|---|---|
| Model | Eloquent models represent users, items, transactions, conversations, messages, notifications, and ratings |
| View | Blade templates render the marketplace, dashboards, forms, reports, receipts, and admin pages |
| Controller | Controllers receive requests, validate access, call models/services, and return views or redirects |

Example marketplace flow:

```text
GET /marketplace
-> routes/web.php matches marketplace.index
-> ItemController@index prepares search/filter queries
-> Item model retrieves approved listings
-> resources/views/marketplace/index.blade.php displays the listing cards
```

## Laravel Features Used

| Laravel feature | How UM-Pasa uses it |
|---|---|
| Routes | `routes/web.php` and `routes/auth.php` organize public, authenticated, and admin routes |
| Controllers | Separate controllers manage items, transactions, messages, ratings, reports, profiles, dashboards, and admin pages |
| Eloquent ORM | Models define fillable fields, relationships, scopes, and database access |
| Blade | Views and layouts build reusable UI for marketplace, dashboards, forms, receipts, and reports |
| Middleware | Auth middleware protects student routes; admin middleware protects admin routes |
| Form Requests | Request classes validate listings, transactions, payment proofs, messages, ratings, rejections, and profile updates |
| Policies | Policies protect item, transaction, conversation, and message access |
| Services | Workflow services keep transaction, messaging, notification, and rental logic out of Blade views |
| Seeders | DatabaseSeeder creates demo accounts, sample listings, transactions, messages, ratings, and notifications |
| Storage | Public disk stores item images; private local storage protects payment proof files |
| Tests | Feature tests verify authentication, admin access, marketplace workflows, and payment proof authorization |

## Important Routes

| Feature | Route name | URL / action |
|---|---|---|
| Landing page | `home` | `GET /` |
| Marketplace | `marketplace.index` | `GET /marketplace` |
| Item details | `marketplace.show` | `GET /marketplace/{item}` |
| Create listing | `items.create` | `GET /items/create` |
| Store listing | `items.store` | `POST /items` |
| Edit listing | `items.edit` | `GET /items/{item}/edit` |
| Update listing | `items.update` | `PUT/PATCH /items/{item}` |
| Delete listing | `items.destroy` | `DELETE /items/{item}` |
| Request item | `transactions.store` | `POST /items/{item}/request` |
| Pending requests | `transactions.pending` | `GET /transactions/pending` |
| Transaction history | `transactions.history` | `GET /transactions/history` |
| Approve request | `transactions.approve` | `POST /transactions/{transaction}/approve` |
| Reject request | `transactions.reject` | `POST /transactions/{transaction}/reject` |
| Complete transaction | `transactions.complete` | `POST /transactions/{transaction}/complete` |
| Upload payment proof | `transactions.paymentProof` | `POST /transactions/{transaction}/payment-proof` |
| View payment proof | `transactions.paymentProof.show` | `GET /transactions/{transaction}/payment-proof` |
| Receipt | `transactions.show` | `GET /transactions/{transaction}` |
| Messages | `messages.index` | `GET /messages` |
| Conversation | `messages.show` | `GET /messages/{conversation}` |
| Send message | `messages.store` | `POST /messages` |
| Notifications | `notifications.index` | `GET /notifications` |
| Rating form | `ratings.create` | `GET /ratings/{transaction}/create` |
| Student report | `reports.student` | `GET /reports` |
| Admin users | `admin.users` | `GET /admin/users` |
| Admin listings | `admin.items` | `GET /admin/items` |
| Admin transactions | `admin.transactions` | `GET /admin/transactions` |
| Admin reports | `admin.reports` | `GET /admin/reports` |

## Database Design

| Table | Purpose |
|---|---|
| `users` | Stores student and admin accounts |
| `items` | Stores marketplace listings and moderation status |
| `transactions` | Stores buyer requests, seller decisions, payment proof paths, rental dates, and completion status |
| `conversations` | Stores message threads between users |
| `messages` | Stores chat messages and meetup proposal data |
| `notifications` | Stores user notifications |
| `ratings` | Stores transaction-based user reviews |
| `sessions` | Stores Laravel session records |
| `password_reset_tokens` | Supports password reset |
| `cache`, `jobs` | Laravel framework support tables |

Main relationships:

- A user can create many items.
- An item belongs to one seller.
- An item can have many transactions.
- A transaction belongs to one buyer, one seller, and one item.
- A conversation connects two users and can optionally be related to an item.
- A conversation has many messages.
- A transaction can receive ratings after completion.
- Ratings connect a reviewer, a reviewed user, and a completed transaction.

## Security Highlights

- Laravel authentication protects account-only routes.
- Admin routes require authentication plus `AdminMiddleware`.
- Student registration does not allow users to mass assign the `role` field.
- Blade forms use CSRF tokens.
- Form requests validate incoming listing, transaction, message, payment proof, profile, and rating data.
- Policies prevent users from viewing or changing resources they do not own.
- Eloquent query builder is used instead of manually concatenated SQL.
- Payment proof uploads are validated by file type and size.
- Payment proof files are stored privately and accessed through an authorized controller action.
- Sensitive values belong in `.env` or Railway variables, not committed files.

## Presentation Demo Flow

1. Open the landing page and explain the university marketplace problem.
2. Log in as `buyer@umindanao.edu.ph`.
3. Browse the marketplace and use search or filters.
4. Open an item details page.
5. Request an item and choose a payment method.
6. Upload payment proof from transaction history.
7. Check notifications.
8. Log in as `seller@umindanao.edu.ph`.
9. Open pending requests and approve or reject a request.
10. Open messages and show meetup proposal handling.
11. Complete an approved transaction after meetup.
12. View the receipt and submit a rating.
13. Log in as `admin@umindanao.edu.ph`.
14. Show the admin dashboard, users, listings, transactions, reports, and listing moderation.
15. Explain Laravel MVC, database relationships, security, and UI/UX decisions.

## Academic Rubric Alignment

| Criteria | Evidence in UM-Pasa |
|---|---|
| Laravel Framework Utilization | Uses MVC structure, resource-style routes, controllers, middleware, Eloquent relationships, Blade views, form requests, policies, services, seeders, storage, tests, and Artisan commands |
| Functionality and Features | Includes listing CRUD, marketplace browsing, search/filter/sort, request workflow, approval/rejection, payment proof upload, receipts, messaging, meetup proposals, notifications, ratings, admin moderation, and reports |
| UI / UX Design | Uses a consistent dark UM-Pasa visual identity, responsive layouts, status badges, dashboard cards, readable forms, loading/disabled submit states, and role-based navigation |
| Database Design and Security | Uses relational tables, foreign keys, indexes, unique constraints, validation, CSRF protection, authorization checks, private payment proof handling, and safe mass-assignment rules |
| Code Quality and Best Practices | Keeps business logic in controllers/services/models instead of Blade, follows Laravel naming conventions, uses `.env` configuration, includes tests, and uses Pint formatting |

## Required Presentation Materials

Use these existing files while preparing class requirements:

| File | Purpose |
|---|---|
| `README.md` | Main project overview, setup, features, routes, security, and demo guide |
| `DEMO_FLOW.md` | Short live-demo script and account guide |
| `DEFENSE_SCRIPT.md` | Speaking script for MVC, database, security, and UI/UX defense |
| `SETUP_GUIDE.md` | More detailed local setup and troubleshooting notes |

Suggested pamphlet sections:

- Project name and tagline
- Problem and solution
- Target users
- Core features
- Technology stack
- Security highlights
- Screenshots of marketplace, item details, transaction receipt, and admin dashboard
- Team members and institution
- Repository or Railway demo link

Suggested presentation slide flow:

1. Title and team
2. Problem statement
3. Objectives
4. Target users
5. Main features
6. Live demo flow
7. Laravel MVC implementation
8. Database relationships and security
9. UI/UX design
10. Testing, limitations, and future improvements
11. Conclusion and Q&A

## Scope and Limitations

Current scope:

- University marketplace for academic items
- Student listings, requests, transaction tracking, receipts, messaging, notifications, ratings, admin moderation, and reports

Current limitations:

- No real online payment gateway
- No delivery management
- No real-time websocket chat
- No full commercial audit logging
- Payment proof handling is safer than public storage, but future versions can move private files to a dedicated cloud storage provider
- Admin analytics are designed for academic monitoring, not enterprise business intelligence

## Future Improvements

- Student ID or email verification workflow
- Real-time chat through Laravel broadcasting
- Report item/user feature
- More detailed admin analytics and export options
- Stronger moderation tools
- Mobile PWA support
- Notification preferences
- Cloud private file storage for payment proofs

## Project Team

| Name | Role |
|---|---|
| Terante, Markpaul | Project Leader |
| Tuyac, Sophia Khym O. | Front-end Developer |
| Coronia, Ian Miguel T. | Front-end Developer |
| Bacunlay, Tejay R. | Back-end Developer |
| Nalzaro, Rhena Mae T. | Back-end Developer |
| Arapoc, Dongie Ana Marie A. | Back-end Developer |
| Villarosa, Jorlan | Back-end Developer |

## Institution

- Department of Computing Education
- Computer Science and Information Technology Program
- UM Tagum College| UM Visayan Campus
- Visayan Village, Tagum City, Davao del Norte

## Final Note

UM-Pasa is ready to present as an academic Laravel marketplace system when the verification checklist passes and the demo database is seeded. It demonstrates Laravel MVC, functional marketplace workflows, protected admin access, normalized database relationships, secure form handling, private payment proof access, and a polished UI suitable for a BSIT project defense.
