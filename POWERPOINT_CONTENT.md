# UM-Pasa PowerPoint Content

Use this content for a Laravel project defense. The slides are arranged to match the rubric categories: Laravel Framework Utilization, Functionality & Features, UI/UX Design, Database Design & Security, and Code Quality & Best Practices.

## Slide 1: Title Slide

**UM-Pasa: University Marketplace**

- Group No.: __________
- Team Name: __________
- Team Members: __________
- Course/Subject: __________

**Presenter Notes:**  
Good day. We are presenting UM-Pasa, a Laravel-based university marketplace designed for the University of Mindanao community. The system helps students buy, sell, rent, request, message, transact, and review academic items in one organized platform.

## Slide 2: Project Overview

**What UM-Pasa Solves**

- Students often rely on scattered group chats and social media posts to find academic items.
- Items such as books, calculators, uniforms, gadgets, and laboratory tools may only be needed for one subject or semester.
- UM-Pasa provides a structured marketplace with listings, requests, moderation, messaging, transactions, receipts, notifications, and ratings.

**Presenter Notes:**  
The main problem is the lack of an organized, searchable, and moderated campus marketplace. UM-Pasa centralizes these activities and adds trust through account access, transaction tracking, admin review, and user ratings.

## Slide 3: Target Users and Roles

**Role-Based System**

- **Guest:** Can view the home page, about page, help page, marketplace, and item details.
- **Student:** Can register, log in, create listings, request items, upload payment proof, send messages, view receipts, and rate completed transactions.
- **Seller:** Can approve, reject, and complete buyer requests.
- **Admin:** Can manage users, moderate listings, monitor transactions, and generate reports.

**Presenter Notes:**  
The system separates access by role. Guests can explore, students can transact, sellers can manage requests, and admins can oversee marketplace quality and safety.

## Slide 4: Core Workflow

**Marketplace Transaction Flow**

1. Student posts an item for sale or rent.
2. Admin reviews and approves or rejects the listing.
3. Buyer browses, filters, and requests an approved item.
4. Buyer selects payment method and may upload payment proof.
5. Seller approves or rejects the request with meetup details.
6. Buyer and seller communicate through messages.
7. Seller completes the transaction.
8. Users view receipt and leave ratings.

**Presenter Notes:**  
This workflow proves the system is not only CRUD-based. It supports a complete marketplace process from listing creation to moderation, payment proof, meetup coordination, receipt, and rating.

## Slide 5: Laravel MVC Architecture

**How the Project Follows MVC**

- **Routes:** `routes/web.php` defines public, authenticated, transaction, message, profile, report, and admin routes.
- **Controllers:** Controllers handle request flow, such as `ItemController`, `TransactionController`, `MessageController`, `AdminController`, and `ReportController`.
- **Models:** `User`, `Item`, `Transaction`, `Conversation`, `Message`, `Notification`, and `Rating` represent database entities and relationships.
- **Views:** Blade templates in `resources/views` display marketplace pages, dashboards, forms, receipts, reports, messages, and admin pages.

**Presenter Notes:**  
UM-Pasa follows Laravel MVC strictly. Routes direct requests, controllers coordinate actions, models handle data and relationships, and Blade views present the interface.

## Slide 6: Laravel Framework Utilization

**Laravel Features Used**

- Eloquent ORM relationships and query builder for database access.
- Blade templates and reusable components for UI consistency.
- Form Request classes for validation and authorization.
- Policies for item, transaction, message, and conversation permissions.
- Middleware for admin-only access.
- Route model binding for cleaner controller actions.
- Service classes for transaction, messaging, notification, and rental workflows.
- Migrations, seeders, Artisan command, and application service provider setup.

**Presenter Notes:**  
The project uses Laravel beyond basic setup. It applies core framework features properly, including Eloquent, Blade, Form Requests, Policies, Middleware, service classes, migrations, seeders, and Artisan commands.

## Slide 7: Functionality and Features

**Complete User-Facing Features**

- Marketplace browsing with search, filters, sorting, and item details.
- Item creation, editing, deletion, rental settings, images, and payment options.
- Admin listing approval and rejection with reasons.
- Buyer request workflow with payment method and proof upload.
- Seller pending-request management with approve, reject, and complete actions.
- Transaction history, receipts, printable reports, notifications, messages, meetup proposals, and ratings.

**Presenter Notes:**  
The core workflows are complete and connected. Users can move from browsing to requesting, paying, messaging, completing, receiving notifications, and rating.

## Slide 8: UI/UX Design

**Student-Friendly and Admin-Friendly Interface**

- Responsive Blade and Tailwind layouts for desktop and mobile.
- Reusable UI components for buttons, inputs, cards, dropdowns, flash messages, modals, and navigation.
- Clear marketplace cards, category filters, search fields, status labels, and empty states.
- Separate dashboards for students and admins.
- Print-friendly receipts and reports.
- Feedback messages and disabled/loading states help prevent repeated form submissions.

**Presenter Notes:**  
The UI is organized around student and admin tasks. The marketplace is easy to browse, dashboards summarize important information, and the interface provides clear feedback after user actions.

## Slide 9: Database Design

**Normalized Data Structure**

- `users` stores student and admin accounts.
- `items` stores listings, categories, departments, condition, listing type, status, moderation status, and rental details.
- `transactions` connects buyers, sellers, items, payment details, status, meetup data, and rental due dates.
- `conversations` and `messages` support private communication and meetup proposals.
- `notifications` stores system updates and related records.
- `ratings` stores transaction-based feedback with unique review protection.

**Presenter Notes:**  
The database is separated into clear tables with proper relationships. This avoids storing everything in one table and supports maintainable marketplace operations.

## Slide 10: Database Relationships and Indexes

**Relationship and Performance Evidence**

- Each item belongs to a user and has many transactions.
- Each transaction belongs to a buyer, seller, and item.
- Ratings belong to reviewer, reviewed user, and transaction.
- Conversations connect two users and optionally an item.
- Messages belong to conversations and users.
- Foreign keys protect record integrity.
- Indexes support marketplace filters, transaction lookup, notifications, conversations, and messages.

**Presenter Notes:**  
This supports the rubric’s database design criteria because the schema has relationships, foreign keys, unique constraints, and performance indexes for common queries.

## Slide 11: Security and Authorization

**Protection Measures**

- Laravel authentication protects private pages and account actions.
- Admin pages are protected by `AdminMiddleware`.
- Policies restrict item updates, item requests, transaction viewing, approval, completion, proof upload, ratings, and proposal responses.
- Form Requests validate inputs before saving.
- CSRF protection is used by Laravel forms.
- Passwords are hashed by Laravel.
- Login requests are rate-limited through Laravel authentication.
- Payment proofs are stored privately and served only through an authorized route.
- Eloquent/query builder helps avoid raw SQL injection risks.

**Presenter Notes:**  
Security is built into the workflow. A user cannot approve someone else’s transaction, view another user’s private payment proof, request their own item, or access admin pages without permission.

## Slide 12: Code Quality and Best Practices

**Maintainable Laravel Code**

- Controllers are focused on request handling.
- Business workflows are moved into service classes.
- Constants are used for statuses and listing types.
- Form Requests keep validation separate from controllers.
- Policies keep authorization rules centralized.
- Models define relationships, casts, helper methods, and fillable fields.
- Reusable Blade components reduce duplicate UI code.
- Uses PSR-4 autoloading and Laravel conventions.

**Presenter Notes:**  
The codebase is organized and readable. It separates concerns by using controllers, models, services, policies, requests, migrations, seeders, and Blade components.

## Slide 13: Testing, Demo Data, and Deployment Readiness

**Verification and Presentation Support**

- Feature tests cover authentication, profiles, admin access, and marketplace workflows.
- Seeders provide demo accounts and sample marketplace data.
- Demo flow includes buyer, seller, renter, and admin scenarios.
- Build tools include Vite, Tailwind CSS, Alpine.js, PHPUnit, and Laravel Pint.
- Railway deployment configuration is available through environment variables.

**Presenter Notes:**  
The project is prepared for defense because it has demo accounts, seeded data, a walkthrough flow, feature tests, and deployment notes.

## Slide 14: Rubric Alignment

**How UM-Pasa Targets the Excellent Column**

| Rubric Criterion | Evidence in UM-Pasa |
| --- | --- |
| Laravel Framework Utilization | MVC, routing, Eloquent, Blade, Middleware, Policies, Form Requests, Service Provider, Artisan command |
| Functionality & Features | Complete listing, request, payment proof, messaging, receipt, rating, reports, and admin moderation workflows |
| UI/UX Design | Responsive Tailwind interface, reusable components, status labels, empty states, dashboards, and print-friendly pages |
| Database Design & Security | Normalized schema, foreign keys, indexes, role checks, validation, CSRF, hashed passwords, private proof access |
| Code Quality & Best Practices | Service classes, constants, policies, requests, casts, reusable Blade components, tests, seeders, Laravel conventions |

**Presenter Notes:**  
This slide directly connects the project to the rubric. Each scoring category has concrete evidence from the actual implementation.

## Slide 15: Limitations and Future Improvements

**Next Development Steps**

- Add real-time chat using broadcasting or WebSockets.
- Add stronger student verification.
- Integrate a real payment gateway for online payments.
- Add cloud storage for private file handling in production.
- Add richer admin audit logs and analytics.
- Add CI/CD for automated testing and deployment.

**Presenter Notes:**  
The current system is complete for an academic marketplace defense. Future improvements can make it closer to a production-grade marketplace.

## Slide 16: Closing

**Conclusion**

- UM-Pasa demonstrates a complete Laravel MVC marketplace system.
- It uses proper authentication, authorization, validation, database relationships, and reusable UI.
- It supports realistic student marketplace workflows from listing to transaction completion.
- It directly addresses the rubric’s excellent-level requirements.

**Presenter Notes:**  
In conclusion, UM-Pasa is not just a simple CRUD application. It is a functional Laravel marketplace with organized architecture, complete workflows, secure access, database relationships, readable UI, and maintainable code.
