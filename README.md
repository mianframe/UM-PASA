# UM-Pasa: Campus Marketplace

UM-Pasa is a Laravel-based campus marketplace system for the UM Tagum community. It allows students to post, browse, request, message, and complete student-to-student transactions for academic materials in a more organized and accountable way.

The system is designed for a BSIT project demonstration and follows Laravel's MVC structure using routes, controllers, models, migrations, Blade views, authentication, role-based access, and CRUD workflows.

## Overview

UM-Pasa addresses a common problem for students: academic materials such as books, uniforms, calculators, gadgets, laboratory tools, and course-related supplies are often expensive and sometimes only needed for one semester.

Instead of relying only on scattered social media posts or group chats, UM-Pasa provides a focused marketplace where students can:

- Post academic items for sale or rent
- Browse and filter listings by category, department, program, condition, price, and course code
- Send requests for available items
- Message buyers or sellers
- Coordinate campus meetups
- Receive notifications for important updates
- Track transaction history
- Leave ratings after completed transactions

## Objectives

- Provide a secure web-based marketplace for UM students
- Organize academic item exchange within one platform
- Support department, program, category, and course-code based filtering
- Allow students to request, approve, reject, and complete marketplace transactions
- Provide messaging and meetup proposal features
- Add notifications for requests, approvals, messages, completions, and ratings
- Support user ratings for accountability and trust
- Provide admin monitoring for users, items, and transactions

## Target Users

| User Type | Access |
|---|---|
| Guest | Can view the landing page, help page, marketplace, and item details |
| Student | Can register, log in, post items, request items, message users, manage transactions, receive notifications, and rate users |
| Admin | Can monitor users, items, transactions, reports, approve or reject listings, and remove inappropriate listings |

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 |
| Language | PHP 8.4+ |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Build Tool | Vite |
| Database | SQLite or MySQL, depending on `.env` configuration |
| Authentication | Laravel Breeze / Laravel Auth scaffolding |
| Testing | PHPUnit |
| Asset Storage | Laravel public disk for item images; private local disk for payment proofs |

## Main Features

- Student registration and login
- UM-style campus marketplace landing page
- Item listing CRUD
- Sale and rental listing support
- Marketplace search, sorting, and filters
- Item details page
- Request item transaction flow
- Seller approval/rejection of requests
- Payment method selection and payment proof upload
- Rental duration and rental due-date tracking
- Transaction completion and receipt page
- Buyer/seller messaging
- Meetup proposal acceptance/decline
- Notifications and unread notification count
- User rating and reputation system
- Student dashboard
- Admin listing moderation, monitoring pages, and reports
- Profile and password management

## Evaluation Rubric Alignment

This section maps UM-Pasa directly to the project criteria shown in the class rubric.

| Criteria | Evidence in UM-Pasa |
|---|---|
| Laravel Framework Utilization | Uses Laravel routes, controllers, Eloquent models, migrations, Blade templates, middleware, policies, form requests, services, seeders, storage, Vite assets, and Artisan commands. The system follows MVC through `routes/web.php`, controllers in `app/Http/Controllers`, models in `app/Models`, and Blade views in `resources/views`. |
| Functionality and Features | Provides working CRUD for listings, marketplace search and filters, item requests, seller approval/rejection, transaction completion, payment proof upload, rental tracking, messaging, meetup proposals, notifications, ratings, admin moderation, dashboards, and reports. |
| UI / UX Design | Uses Blade and Tailwind CSS for responsive pages, role-based navigation, reusable components, status badges, form validation messages, flash messages, marketplace cards, dashboards, and admin/student views. |
| Database Design and Security | Uses normalized tables for users, items, transactions, conversations, messages, notifications, and ratings. Migrations define foreign keys, indexes, unique constraints, and relationship integrity. Security uses Laravel authentication, CSRF protection, policies, admin middleware, request validation, mass-assignment rules, and environment-based configuration. |
| Code Quality and Best Practices | Keeps business workflows in service classes, validation in form request classes, access rules in policies/middleware, UI in Blade views, and data logic in Eloquent models. The code follows Laravel conventions, PSR autoloading, `.env` configuration, and includes feature tests for authentication, admin access, and marketplace workflows. |

## Required Project Outputs

Use the following content when preparing the pamphlet, full documentation, and class presentation.

### Pamphlet Content

A pamphlet should be short, visual, and easy to scan.

- Project name: UM-Pasa: Campus Marketplace
- Tagline: A secure student-to-student marketplace for academic items
- Problem: Students often need affordable books, uniforms, calculators, gadgets, and supplies for only one subject or semester
- Solution: UM-Pasa organizes buying, renting, messaging, meetups, ratings, and admin monitoring in one Laravel web system
- Target users: Guests, students, and admins
- Key features: item posting, marketplace filters, requests, messages, notifications, rental tracking, receipts, ratings, and admin moderation
- Technology used: Laravel 13, PHP 8.4, Blade, Tailwind CSS, Alpine.js, Vite, SQLite/MySQL, PHPUnit
- Security highlights: login system, role-based access, CSRF protection, validated forms, policies, admin middleware, and secure `.env` configuration
- Suggested visuals: logo, marketplace screenshot, item details screenshot, transaction flow diagram, admin moderation screenshot, and QR/link to repository or demo
- Team and institution: include the project team and University of Mindanao Tagum / UM Visayan Campus details

### Full Documentation Content

The documentation should be more detailed than the pamphlet and can follow this order:

1. Title page
2. Abstract or project summary
3. Introduction and background of the problem
4. Objectives of the system
5. Scope and limitations
6. Target users and user roles
7. System features and modules
8. System architecture and MVC explanation
9. Database design, ERD, tables, relationships, foreign keys, and indexes
10. Security design: authentication, authorization, CSRF, validation, and `.env` usage
11. UI/UX design notes and screenshots
12. Installation and setup guide
13. Demo accounts and test data
14. Main user workflows: listing, request, transaction, messaging, rating, and admin moderation
15. Testing summary and test cases
16. Future improvements
17. Team roles
18. References or appendix

### Presentation Outline

Recommended slide flow for a 7 to 12 minute report:

1. Title slide: UM-Pasa, team, course, instructor, and institution
2. Problem statement: why students need a campus marketplace
3. Objectives: what the system aims to solve
4. Target users: guest, student, admin
5. Feature overview: marketplace, requests, messaging, notifications, ratings, reports
6. Live demo flow: browse, post item, request item, approve request, message, complete, rate, admin moderation
7. Laravel implementation: MVC, routes, controllers, Eloquent, Blade, middleware, policies, form requests, services
8. Database and security: tables, relationships, foreign keys, indexes, authentication, authorization, CSRF, validation
9. UI/UX: responsive pages, dashboards, filters, status badges, and role-based navigation
10. Testing and limitations: test coverage, current limits, and future improvements
11. Conclusion: value to UM students and academic resource sharing
12. Q&A

## Project Structure

```text
UM-PASA/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ItemController.php
│   │   │   ├── MessageController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── RatingController.php
│   │   │   ├── ReportController.php
│   │   │   └── TransactionController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/
│   │       ├── SaveItemRequest.php
│   │       ├── StoreTransactionRequest.php
│   │       └── StoreMessageRequest.php
│   ├── Models/
│   │   ├── Conversation.php
│   │   ├── Item.php
│   │   ├── Message.php
│   │   ├── Notification.php
│   │   ├── Rating.php
│   │   ├── Transaction.php
│   │   └── User.php
│   ├── Policies/
│   └── Services/
├── config/
│   └── um_departments.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   └── UMPASALOGO.png
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── dashboard/
│       ├── items/
│       ├── layouts/
│       ├── marketplace/
│       ├── messages/
│       ├── notifications/
│       ├── profile/
│       ├── ratings/
│       ├── transactions/
│       ├── help.blade.php
│       └── home.blade.php
├── routes/
│   ├── auth.php
│   └── web.php
└── tests/
    └── Feature/
```

## MVC Architecture

UM-Pasa follows Laravel's MVC pattern:

| MVC Part | Description | Examples |
|---|---|---|
| Model | Represents database tables and relationships | `User`, `Item`, `Transaction`, `Conversation`, `Message`, `Notification`, `Rating` |
| View | Blade files that display the UI | `home.blade.php`, `marketplace/index.blade.php`, `items/show.blade.php`, `messages/index.blade.php` |
| Controller | Handles request logic and connects models to views | `ItemController`, `TransactionController`, `MessageController`, `RatingController` |

Example flow:

```text
User opens /marketplace
-> routes/web.php matches marketplace.index
-> ItemController@index runs search/filter query
-> Item model retrieves available listings
-> marketplace/index.blade.php displays results
```

## Laravel Components Used

| Component | Project Use |
|---|---|
| Routes | `routes/web.php` defines public, authenticated, and admin-only pages |
| Controllers | Handle page requests, dashboard logic, reports, CRUD, messages, ratings, and transactions |
| Models and Eloquent ORM | Represent users, items, transactions, conversations, messages, notifications, and ratings |
| Blade Templates | Render the marketplace, forms, dashboards, admin pages, receipts, and profile pages |
| Middleware | `AdminMiddleware` protects admin-only pages |
| Policies | Control item, transaction, conversation, and message permissions |
| Form Requests | Validate listing, transaction, payment proof, message, profile, rating, and rejection forms |
| Services | Keep transaction, messaging, notification, and rental notification workflows organized |
| Migrations and Seeders | Build the schema and provide demo data for project presentation |
| Artisan Commands | Includes `ExpireStaleRequests` for transaction maintenance |

## Important Routes

| Feature | Route Name | URL / Action |
|---|---|---|
| Landing page | `home` | `GET /` |
| Marketplace | `marketplace.index` | `GET /marketplace` |
| Item details | `marketplace.show` | `GET /marketplace/{item}` |
| Create item | `items.create` | `GET /items/create` |
| Store item | `items.store` | `POST /items` |
| Edit item | `items.edit` | `GET /items/{item}/edit` |
| Update item | `items.update` | `PUT/PATCH /items/{item}` |
| Delete item | `items.destroy` | `DELETE /items/{item}` |
| Request item | `transactions.store` | `POST /items/{item}/request` |
| Pending requests | `transactions.pending` | `GET /transactions/pending` |
| Transaction history | `transactions.history` | `GET /transactions/history` |
| Approve request | `transactions.approve` | `POST /transactions/{transaction}/approve` |
| Reject request | `transactions.reject` | `POST /transactions/{transaction}/reject` |
| Complete transaction | `transactions.complete` | `POST /transactions/{transaction}/complete` |
| Upload payment proof | `transactions.paymentProof` | `POST /transactions/{transaction}/payment-proof` |
| Transaction receipt | `transactions.show` | `GET /transactions/{transaction}` |
| Messages | `messages.index` | `GET /messages` |
| Conversation | `messages.show` | `GET /messages/{conversation}` |
| Send message | `messages.store` | `POST /messages` |
| Notifications | `notifications.index` | `GET /notifications` |
| Ratings | `ratings.create` | `GET /ratings/{transaction}/create` |
| Student reports | `reports.student` | `GET /reports` |
| Admin users | `admin.users` | `GET /admin/users` |
| Admin items | `admin.items` | `GET /admin/items` |
| Approve listing | `admin.items.approve` | `POST /admin/items/{item}/approve` |
| Reject listing | `admin.items.reject` | `POST /admin/items/{item}/reject` |
| Admin transactions | `admin.transactions` | `GET /admin/transactions` |
| Admin reports | `admin.reports` | `GET /admin/reports` |

## Database Tables

| Table | Purpose |
|---|---|
| `users` | Stores student and admin accounts |
| `items` | Stores posted marketplace listings |
| `transactions` | Stores item requests and transaction status |
| `conversations` | Stores chat threads between two users |
| `messages` | Stores messages and meetup proposals |
| `notifications` | Stores user updates |
| `ratings` | Stores transaction-based user reviews |
| `sessions` | Stores Laravel session data |
| `password_reset_tokens` | Supports password reset |
| `cache`, `jobs` | Laravel framework support tables |

## Main Database Relationships

- One user can post many items.
- One item belongs to one user.
- One item can have many transactions.
- One transaction connects a buyer, seller, and item.
- One user can have many notifications.
- One conversation connects a starter, recipient, and optionally an item.
- One conversation has many messages.
- One completed transaction can receive ratings.
- Ratings connect a reviewer, a reviewed user, and a transaction.

## Database and Security Notes

- Foreign keys connect users, items, transactions, conversations, messages, and ratings.
- Marketplace and transaction indexes improve filtering, sorting, dashboards, and notifications.
- Rating records use a unique transaction-reviewer constraint to avoid duplicate reviews.
- Admin pages are protected by authentication plus `AdminMiddleware`.
- Policies prevent unauthorized item, transaction, conversation, and message access.
- Form requests validate user input before storing listings, requests, payment proof, messages, ratings, and profile updates.
- Laravel Blade forms use CSRF protection.
- Eloquent query builder and validation are used instead of manually concatenated SQL.
- Sensitive configuration belongs in `.env`; `.env.example` documents safe defaults.

## Transaction Flow

```text
1. Buyer opens an available item.
2. Buyer clicks Request Item and selects an accepted payment method.
3. System validates the request and creates a pending transaction.
4. Seller receives a notification.
5. Buyer may upload payment proof.
6. Seller approves or rejects the request.
7. If approved, seller adds meetup location and time.
8. For rentals, the system calculates the rental due date.
9. After meetup, seller marks transaction as completed.
10. Buyer/seller may leave a rating.
```

## Demo Accounts

The seeder creates these demo accounts:

| Role | Name | Email | Password |
|---|---|---|---|
| Admin | Admin User | `admin@umindanao.edu.ph` | `password` |
| Student | Student User | `student@umindanao.edu.ph` | `password` |
| Seller | Ava Seller | `seller@umindanao.edu.ph` | `password` |
| Buyer | Marco Buyer | `buyer@umindanao.edu.ph` | `password` |
| Renter | Bea Renter | `renter@umindanao.edu.ph` | `password` |
| Second Seller | Jessa Seller | `seller2@umindanao.edu.ph` | `password` |

Seeder file:

```text
database/seeders/DatabaseSeeder.php
```

## Setup Guide

### 1. Install PHP dependencies

```bash
composer install
```

### 2. Install frontend dependencies

```bash
npm install
```

### 3. Create environment file

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure database

Open `.env` and configure your database.

For SQLite:

```env
DB_CONNECTION=sqlite
```

Make sure the database file exists:

```bash
touch database/database.sqlite
```

For MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=um_pasa
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Seed demo accounts

```bash
php artisan db:seed
```

### 8. Link public storage for item images

```bash
php artisan storage:link
```

### 9. Build frontend assets

```bash
npm run build
```

### 10. Start the Laravel server

```bash
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

Or use the project dev script:

```bash
composer run dev
```

Run tests:

```bash
composer test
```

Clear caches before demo:

```bash
php artisan optimize:clear
```

Recommended final demo prep:

```bash
php artisan optimize:clear
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
php artisan serve
```

## Demo Flow

1. Open the landing page and explain the campus marketplace problem.
2. Log in as `buyer@umindanao.edu.ph`.
3. Browse the marketplace and use search or filters.
4. Open an item details page.
5. Request an item and choose a payment method.
6. Upload payment proof from transaction history.
7. Check notifications.
8. Log in as `seller@umindanao.edu.ph`.
9. Open pending requests and approve or reject a request.
10. Open messages and show meetup proposals.
11. Complete an approved transaction after meetup.
12. View the receipt and leave a rating.
13. Log in as `admin@umindanao.edu.ph`.
14. Show admin users, items, transactions, reports, and listing moderation.
15. Log out.

## Scope and Limitations

Current scope:

- Campus marketplace for academic/student items
- Student item posting and browsing
- Item requests and transaction tracking
- Messaging and meetup proposals
- Notifications
- Ratings
- Admin monitoring, listing approval/rejection, and reports

Limitations:

- No online payment gateway
- No delivery service
- No real-time websocket chat
- Campus meetup safety still depends on user responsibility
- Admin tools are focused on monitoring, moderation, and reports, not full analytics

## Future Improvements

- Real-time chat using broadcasting/websockets
- Report item/user feature
- Stronger student verification
- Admin analytics charts
- More advanced moderation tools
- Mobile PWA support
- Better notification preferences
- Exportable reports for admin

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

Department of Computing Education  
Computer Science and Information Technology Program  
University of Mindanao Tagum / UM Visayan Campus  
Visayan Village, Tagum City, Davao del Norte  


## Compliance Note

UM-Pasa is designed with account-based access, user accountability, and role-based admin monitoring. As a student project, it should still be reviewed and improved further before production deployment, especially for privacy, moderation, and data protection requirements under the Data Privacy Act of 2012, R.A. 10173.

## Summary

UM-Pasa promotes affordability, sustainability, and mutual academic support within the UM campus by giving students a structured platform to exchange academic resources safely and efficiently.
