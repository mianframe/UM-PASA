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
| Admin | Can view users, items, and transactions, and delete inappropriate listings |

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 |
| Language | PHP 8.3+ |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Build Tool | Vite |
| Database | SQLite or MySQL, depending on `.env` configuration |
| Authentication | Laravel Breeze / Laravel Auth scaffolding |
| Testing | PHPUnit |
| Asset Storage | Laravel public storage disk |

## Main Features

- Student registration and login
- UM-style campus marketplace landing page
- Item listing CRUD
- Marketplace search, sorting, and filters
- Item details page
- Request item transaction flow
- Seller approval/rejection of requests
- Transaction completion and receipt page
- Buyer/seller messaging
- Meetup proposal acceptance/decline
- Notifications and unread notification count
- User rating and reputation system
- Student dashboard
- Admin monitoring pages
- Profile and password management

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
│   │   │   └── TransactionController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── Conversation.php
│       ├── Item.php
│       ├── Message.php
│       ├── Notification.php
│       ├── Rating.php
│       ├── Transaction.php
│       └── User.php
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
| Transaction receipt | `transactions.show` | `GET /transactions/{transaction}` |
| Messages | `messages.index` | `GET /messages` |
| Notifications | `notifications.index` | `GET /notifications` |
| Ratings | `ratings.create` | `GET /ratings/{transaction}/create` |
| Admin users | `admin.users` | `GET /admin/users` |
| Admin items | `admin.items` | `GET /admin/items` |
| Admin transactions | `admin.transactions` | `GET /admin/transactions` |

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

## Transaction Flow

```text
1. Buyer opens an available item.
2. Buyer clicks Request Item.
3. System creates a pending transaction.
4. Seller receives a notification.
5. Seller approves or rejects the request.
6. If approved, seller adds meetup location and time.
7. After meetup, seller marks transaction as completed.
8. Buyer/seller may leave a rating.
```

## Demo Accounts

The seeder creates these demo accounts:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@umindanao.edu.ph` | `password` |
| Student | `student@umindanao.edu.ph` | `password` |

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

1. Open the landing page.
2. Explain UM-Pasa and the campus marketplace problem.
3. Register or log in as a student.
4. Open the dashboard.
5. Post a new item.
6. Browse the marketplace.
7. Search or filter listings.
8. Open an item details page.
9. Request the item or message the seller.
10. Check notifications.
11. As seller, approve a pending request.
12. Complete the transaction after meetup.
13. Leave a rating.
14. Log in as admin and show monitoring pages.
15. Log out.

## Scope and Limitations

Current scope:

- Campus marketplace for academic/student items
- Student item posting and browsing
- Item requests and transaction tracking
- Messaging and meetup proposals
- Notifications
- Ratings
- Admin monitoring

Limitations:

- No online payment gateway
- No delivery service
- No real-time websocket chat
- Campus meetup safety still depends on user responsibility
- Admin tools are focused on monitoring and item deletion, not full analytics

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
