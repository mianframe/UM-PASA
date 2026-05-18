# UM-Pasa Group Defense Script With System and Code Demo

Use this version when the panel expects you to show the actual system and source code during the presentation.

## Before the Presentation

Prepare the demo data:

```bash
php artisan migrate:fresh --seed
```

Start the system:

```bash
php artisan serve --host=127.0.0.1 --port=8000
npm run dev
```

Open the system at:

```text
http://127.0.0.1:8000
```

All demo accounts use this password:

```text
password
```

Recommended accounts:

| Role | Email | Use During Demo |
| --- | --- | --- |
| Admin | admin@umindanao.edu.ph | Admin moderation and reports |
| Buyer | buyer@umindanao.edu.ph | Marketplace, requests, receipts |
| Seller | seller@umindanao.edu.ph | Pending requests and transactions |
| Renter | renter@umindanao.edu.ph | Rental history sample |

## Open These Tabs Before Starting

Browser tabs:

1. Brochure PDF: `UM-Pasa_System_Brochure.pdf`
2. Home page: `http://127.0.0.1:8000`
3. Marketplace: `http://127.0.0.1:8000/marketplace`
4. Login page: `http://127.0.0.1:8000/login`

Code tabs:

1. `routes/web.php`, lines 16-68
2. `resources/views/marketplace/index.blade.php`, lines 33-115
3. `app/Http/Controllers/ItemController.php`, lines 14-31 and 100-113
4. `app/Http/Controllers/TransactionController.php`, lines 43-108
5. `app/Services/TransactionWorkflowService.php`, lines 18-163
6. `app/Http/Middleware/AdminMiddleware.php`, lines 12-18
7. `app/Policies/TransactionPolicy.php`, lines 13-56
8. `app/Http/Requests/SaveItemRequest.php`, lines 16-49
9. `resources/views/admin/items.blade.php`, lines 52-68
10. `resources/views/transactions/receipt.blade.php`, lines 20-30 and 79-165
11. `resources/views/reports/admin.blade.php`, lines 16-54
12. `tests/Feature/MarketplaceWorkflowTest.php`, lines 95-134 and 187-220

## 1. Markpaul - Opening, Problem, and Project Overview

**Open first:** Brochure PDF, front panel and "What is UM-Pasa?" section.

Good day, everyone. We are Group 1 from IT 9A - Professional Track for IT 3, and today we will present our system called **UM-Pasa: University Marketplace System**.

UM-Pasa is a Laravel-based university marketplace designed for students who need a more organized way to buy, sell, rent, and manage academic items inside the university community.

The main problem we noticed is that students usually rely on scattered Facebook posts, group chats, or personal connections when looking for items like books, calculators, uniforms, gadgets, or laboratory tools. This can be confusing because there is no proper tracking, no organized search, and no clear transaction record.

That is why we created UM-Pasa. It provides a structured platform where students can browse listings, post items, coordinate with sellers, upload payment proof, track transactions, and receive receipts in one organized system.

**Show system:** Open `http://127.0.0.1:8000`, then open `http://127.0.0.1:8000/marketplace`.

As you can see, the system starts with a public home page and marketplace page. Guests can browse approved listings, while logged-in users can access transactions, messages, notifications, reports, and dashboards.

**Optional show code:** Open `routes/web.php`, lines 16-23.

Here in our route file, the public pages are declared first: home, about, help, marketplace, and item details.

**Transition:** To explain the design and interface of our system, I will now pass the discussion to Sophia.

## 2. Sophia - UI/UX Design and System Look

**Open first:** `http://127.0.0.1:8000/marketplace`.

Thank you, Markpaul.

For the UI and UX design, our goal was to make UM-Pasa clean, modern, and easy to use. We designed it as a student-friendly marketplace, but still professional enough for an academic system.

The visual style uses a dark modern layout, red and gold accents, rounded cards, clear buttons, search bars, category chips, and organized sections. This makes the system easier to scan and navigate.

**Show system:** On the Marketplace page, show the search bar, category filters, "For sale" and "For rent" buttons, and the filter drawer.

For example, students can search for an item like "Calculator", choose a category, filter by type, and sort results. This matches the brochure because the brochure highlights marketplace search, filters, responsive UI, and student-focused design.

**Show code:** Open `resources/views/marketplace/index.blade.php`, lines 33-115.

This Blade file shows the marketplace search panel, type buttons, filter button, and category chips. The view is built using Laravel Blade and Tailwind classes, so the interface is reusable and responsive.

**Transition:** Now, Ian will explain the target users and main marketplace features.

## 3. Ian - Target Users and Marketplace Features

**Open first:** `http://127.0.0.1:8000/login`.

Thank you, Sophia.

UM-Pasa has different users depending on their role.

First, we have the **Guest**. Guests can browse public pages and explore approved marketplace listings.

Second, we have the **Student**. Students can register, log in, create listings, request items, upload payment proof, send messages, view receipts, and receive notifications.

Third, we have the **student acting as seller**. A seller can manage posted items, approve or reject buyer requests, coordinate meetups, and complete transactions.

Lastly, we have the **Admin**. The admin reviews listings, manages users, monitors transactions, and generates reports.

**Show system:** Log in as:

```text
buyer@umindanao.edu.ph
password
```

Then open `http://127.0.0.1:8000/marketplace`, search for `Engineering Calculator`, and open the item.

Here we can see one of the main user features. A buyer can browse an item, check its details, view the seller, choose a payment method, and send a request. The system also supports sale and rental listings, messages, notifications, receipts, reports, and admin moderation.

**Show code:** Open `app/Http/Controllers/ItemController.php`, lines 14-31.

This part shows that marketplace listings only display items with available status and approved moderation status. That means rejected or pending listings are not shown publicly.

**Transition:** To explain how the transaction process works, Tejay will continue.

## 4. Tejay - Core Workflow and Transactions

**Open first:** Keep the buyer item detail page open, or open `http://127.0.0.1:8000/marketplace`.

Thank you, Ian.

The workflow of UM-Pasa starts when a student posts an item for sale or rent. The listing includes details such as item name, category, price, condition, listing type, accepted payment methods, and image.

After that, the listing does not immediately appear in the marketplace. It first goes through admin review. The admin can approve or reject the listing to keep the marketplace organized and trusted.

Once approved, buyers can browse, filter, and request the item. The buyer selects a payment method and may upload payment proof. Then the seller approves or rejects the request. If approved, both users can coordinate the meetup and complete the transaction.

**Show system:** Log out, then log in as:

```text
seller@umindanao.edu.ph
password
```

Open:

```text
http://127.0.0.1:8000/transactions/pending
```

Show the `Data Structures Textbook` pending request. Point out the meetup location field, meetup time field, approve button, reject button, and message buyer button.

This page shows the seller side of the workflow. The seller can approve with meetup details, reject the request, or message the buyer.

**Show code:** Open `app/Http/Controllers/TransactionController.php`, lines 43-70.

This controller receives the buyer request, seller approval, rejection, and completion actions. The controller stays short because the detailed workflow is handled by a service class.

**Show code:** Open `app/Services/TransactionWorkflowService.php`, lines 18-59, 62-86, and 113-163.

This service handles the main transaction rules. It creates the request, changes item status to pending, approves with meetup details, completes the transaction, updates the item status, uploads payment proof, and sends notifications.

**Transition:** Next, Rhena will explain how we implemented the system using Laravel MVC.

## 5. Rhena - Laravel MVC and Back-end Structure

**Open first:** `routes/web.php`, lines 16-68.

Thank you, Tejay.

For the back-end structure, UM-Pasa follows the **Laravel MVC architecture**.

The **routes** direct user requests to the correct part of the system. In `routes/web.php`, we separated public routes, authenticated routes, transaction routes, message routes, report routes, and admin routes.

**Show code:** Point to `routes/web.php`, lines 23-59.

These are authenticated routes. Users must log in before they can access dashboards, create items, request items, view transactions, send messages, view notifications, submit ratings, or generate student reports.

**Show code:** Point to `routes/web.php`, lines 61-68.

These are admin routes. They are protected by both authentication and `AdminMiddleware`.

The **controllers** handle the request flow. For example, `ItemController` manages marketplace listings, `TransactionController` handles requests and receipts, `MessageController` handles conversations, `AdminController` handles moderation, and `ReportController` handles reports.

**Show code:** Open `app/Http/Controllers/ItemController.php`, lines 100-113.

This part shows item posting. When a normal student posts an item, the moderation status becomes pending, so the item will only appear after admin approval.

The **models** represent database entities such as User, Item, Transaction, Conversation, Message, Notification, and Rating. The **views** are Blade templates in `resources/views`, which display the marketplace, dashboards, messages, reports, receipts, and admin pages.

**Transition:** Dongie will now discuss the database, security, and authorization part of the system.

## 6. Dongie - Database, Security, and Authorization

**Open first:** Database migration files.

Thank you, Rhena.

For the database design, UM-Pasa uses a normalized structure.

The `users` table stores student and admin accounts. The `items` table stores marketplace listings, categories, departments, item condition, listing type, status, and rental details. The `transactions` table connects buyers, sellers, and items. It also stores payment details, meetup information, transaction status, and rental due dates.

**Show code:** Open:

```text
database/migrations/2026_05_07_084500_create_items_table.php
database/migrations/2026_05_07_084501_create_transactions_table.php
database/migrations/2026_05_09_000002_create_conversations_table.php
database/migrations/2026_05_09_000003_create_messages_table.php
```

Point out the foreign keys in the migration files. Items belong to users, transactions connect buyer, seller, and item, and conversations and messages connect users for communication.

For security, UM-Pasa uses Laravel authentication to protect private pages. Admin pages are protected by admin middleware.

**Show code:** Open `app/Http/Middleware/AdminMiddleware.php`, lines 12-18.

This middleware checks if the user is logged in and if the user is an admin. If not, the system returns a 403 forbidden response.

**Show system:** While logged in as a regular buyer or seller, open:

```text
http://127.0.0.1:8000/admin/items
```

The system should block the regular user from accessing admin pages.

**Show code:** Open `app/Policies/TransactionPolicy.php`, lines 13-56.

Policies control what users can do with transactions. For example, only the buyer or seller can view a transaction, only the seller can approve or complete it, and only the buyer can upload payment proof during the allowed status.

**Show code:** Open `app/Http/Requests/SaveItemRequest.php`, lines 16-49.

Form Requests validate input before saving. This includes required title, category, department, course code, listing type, payment methods, rental rules, condition, price, and image restrictions.

**Transition:** To complete our discussion, Jorlan will explain reports, receipts, tests, rubric alignment, and closing.

## 7. Jorlan - Reports, Receipts, Rubric Alignment, and Closing

**Open first:** Log in as:

```text
buyer@umindanao.edu.ph
password
```

Then open:

```text
http://127.0.0.1:8000/transactions/history
```

Thank you, Dongie.

UM-Pasa also includes receipts and reports. The receipt feature helps users keep a clear record of completed transactions. In the transaction history, users can open completed transactions like `Java Programming Book` and view the receipt.

**Show system:** Click the receipt for `Java Programming Book`.

This receipt includes the transaction number, status, payment method, payment proof status, meetup information, buyer, seller, item details, price, department, course code, and rating option when applicable.

**Show code:** Open `resources/views/transactions/receipt.blade.php`, lines 20-30 and 79-165.

This Blade file shows the print button and the detailed receipt layout. It also shows how the system displays transaction details, buyer and seller information, item details, rental details, payment proof, and tracking status.

**Show system:** Log in as:

```text
admin@umindanao.edu.ph
password
```

Open:

```text
http://127.0.0.1:8000/admin/items
```

Show the `Nursing Uniform Set for Review` listing and the approve or reject actions.

**Show code:** Open `resources/views/admin/items.blade.php`, lines 52-68.

This view shows the admin moderation actions: view, approve, reject with reason, and delete.

**Show system:** Open:

```text
http://127.0.0.1:8000/admin/reports
```

The admin report summarizes marketplace activity, including total items, pending review, approved listings, transactions, completed transactions, and GCash payments.

**Show code:** Open `resources/views/reports/admin.blade.php`, lines 16-54.

This shows the report header, generated date, generated by, filters, and summary statistics. The report also has a print button for documentation.

**Show code if the panel asks about testing:** Open `tests/Feature/MarketplaceWorkflowTest.php`, lines 95-134 and 187-220.

This test file proves that the transaction can move from pending to approved to completed, and that payment proof can only be viewed through an authorized transaction route.

For rubric alignment, UM-Pasa targets the excellent category in several areas:

For **Laravel Framework Utilization**, we used MVC, routing, middleware, Eloquent ORM, Blade templates, Form Requests, Policies, service classes, migrations, seeders, and tests.

For **Functionality and Features**, the system includes listing creation, admin approval, item requests, messaging, payment proof upload, transaction completion, receipts, reports, notifications, ratings, and admin moderation.

For **UI/UX Design**, the system uses a responsive and modern interface with reusable components, clear buttons, organized cards, search, filters, status badges, dashboards, and print-friendly pages.

For **Database Design and Security**, we used normalized tables, relationships, foreign keys, validation, authentication, authorization, CSRF protection, password hashing, and private access to sensitive files.

For **Code Quality and Best Practices**, the system follows Laravel conventions and separates logic into routes, controllers, models, policies, requests, services, and Blade views.

For future improvements, UM-Pasa can be enhanced with real-time chat, stronger student verification, cloud storage, payment gateway integration, richer admin analytics, audit logs, and automated deployment.

In conclusion, UM-Pasa is not just a simple CRUD project. It is a complete Laravel marketplace system that supports real student needs through organized transactions, secure access, admin moderation, receipts, reports, and a modern user interface.

Thank you, and we are now ready for questions.

## Very Short Final Group Ending

All members can say together, or Markpaul can say:

"Again, we are Group 1, and this is UM-Pasa: University Marketplace System. Thank you."

## Emergency Short Demo Flow

Use this if the panel gives only 3 to 5 minutes for system demonstration:

1. Open `http://127.0.0.1:8000/marketplace` and show search/filter UI.
2. Log in as `seller@umindanao.edu.ph`, open `/transactions/pending`, and show approve/reject workflow.
3. Log in as `admin@umindanao.edu.ph`, open `/admin/items`, and show listing moderation.
4. Open `/admin/reports` and show printable report.
5. Open code: `routes/web.php`, `TransactionWorkflowService.php`, `TransactionPolicy.php`, and `MarketplaceWorkflowTest.php`.
