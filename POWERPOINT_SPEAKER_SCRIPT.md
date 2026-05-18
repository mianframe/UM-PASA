# UM-Pasa PowerPoint Speaker Script

Randomized speaking allocation for the 16-slide PowerPoint deck. Everyone has at least two speaking parts.

## Speaker Allocation Summary

| Member | Assigned Slides |
| --- | --- |
| Terante, Markpaul | Slides 1, 9, 16 |
| Coronia, Ian Miguel T. | Slides 2, 13 |
| Nalzaro, Rhena Mae T. | Slides 3, 11 |
| Tuyac, Sophia Khym O. | Slides 4, 8 |
| Bacunlay, Tejay R. | Slides 5, 10 |
| Villarosa, Jorlan | Slides 6, 14 |
| Arapoc, Dongie Ana Marie A. | Slides 7, 12, 15 |

## Slide 1: Title Slide

**Speaker: Terante, Markpaul**

Good day everyone. We are Group ____ and today we will present our Laravel project, **UM-Pasa: University Marketplace**. UM-Pasa is a web-based marketplace made for the University of Mindanao community. It helps students buy, sell, rent, request, message, complete transactions, and rate academic items such as books, calculators, uniforms, gadgets, and laboratory tools. Our goal is to provide a more organized and reliable alternative to scattered group chats and social media posts.

For this presentation, we will explain the problem, the users, the system workflow, the Laravel implementation, the database and security features, the user interface, and how the project matches the rubric.

**Transition:** I will now pass the discussion to Ian for the project overview.

## Slide 2: Project Overview

**Speaker: Coronia, Ian Miguel T.**

UM-Pasa solves a common student problem. Many students need academic items for only one subject, one semester, or one school year. Usually, they search through Facebook groups, group chats, or friends, but those posts can be hard to search, hard to verify, and difficult to monitor.

Our system provides one structured marketplace where students can browse available items, search and filter listings, request items, coordinate with sellers, upload payment proof, receive notifications, and build trust through ratings. It is built using Laravel 13, PHP, Eloquent ORM, Blade, Tailwind CSS, Alpine.js, Vite, and PHPUnit.

**Transition:** Next, Rhena Mae will explain the target users and roles.

## Slide 3: Target Users and Roles

**Speaker: Nalzaro, Rhena Mae T.**

UM-Pasa has different users with different permissions. A guest can view public pages, browse the marketplace, and open item details. A student can register, log in, create listings, request items, upload payment proof, send messages, view receipts, and rate completed transactions.

Sellers can manage buyer requests by approving, rejecting, or completing transactions. Admins have a separate role where they can manage users, moderate listings, monitor transactions, and generate reports. This role separation helps keep the system organized and secure.

**Transition:** Sophia will now walk us through the main marketplace workflow.

## Slide 4: Core Workflow

**Speaker: Tuyac, Sophia Khym O.**

The main workflow starts when a student posts an item for sale or rent. Before the listing appears in the marketplace, the admin reviews it and decides whether to approve or reject it. Once approved, another student can browse, filter, and request that item.

The buyer selects a payment method and can upload payment proof. The seller then approves or rejects the request and may provide meetup details. After that, both users can communicate through messages. When the transaction is completed, the system provides a receipt and allows ratings.

**Transition:** Tejay will explain how this workflow follows Laravel MVC.

## Slide 5: Laravel MVC Architecture

**Speaker: Bacunlay, Tejay R.**

UM-Pasa follows Laravel's MVC architecture. The routes are defined in `routes/web.php`, where we separated public pages, authenticated pages, transaction routes, message routes, report routes, and admin routes.

The controllers handle the request flow. For example, `ItemController` manages marketplace listings, `TransactionController` handles requests and receipts, `MessageController` handles conversations, and `AdminController` handles moderation. The models represent the database tables, such as users, items, transactions, conversations, messages, notifications, and ratings. The views are Blade templates that display the actual pages to the users.

**Transition:** Jorlan will continue with the Laravel framework features used in the system.

## Slide 6: Laravel Framework Utilization

**Speaker: Villarosa, Jorlan**

The project uses many Laravel features required for an excellent rating in the rubric. We used Eloquent ORM for database relationships and queries. We used Blade templates and reusable components for the interface. We also used Form Request classes for validation and authorization.

For security and access control, we used Policies and Middleware. We also used route model binding, migrations, seeders, service classes, and an Artisan command. This means the project is not only a basic Laravel setup. It uses Laravel's core tools properly and follows the framework's conventions.

**Transition:** Dongie Ana Marie will discuss the actual system features.

## Slide 7: Functionality and Features

**Speaker: Arapoc, Dongie Ana Marie A.**

The system includes complete user-facing features. Students can browse the marketplace, search for items, filter by category or other details, and open item pages. They can create, edit, and delete listings, including sale and rental listings.

The system also supports admin listing approval, buyer request workflow, payment method selection, payment proof upload, seller approval or rejection, transaction history, receipts, reports, messages, meetup proposals, notifications, and ratings. These connected features show that the system handles a full marketplace process, not just simple create, read, update, and delete operations.

**Transition:** Sophia will now explain the UI and UX design.

## Slide 8: UI/UX Design

**Speaker: Tuyac, Sophia Khym O.**

For the interface, we designed UM-Pasa to be student-friendly and admin-friendly. The pages use Blade and Tailwind CSS, so the layouts are responsive for both desktop and mobile screens. Reusable components are used for buttons, inputs, cards, dropdowns, flash messages, modals, and navigation.

The marketplace uses clear item cards, search fields, category filters, status labels, and empty states. Students have their own dashboard and transaction pages, while admins have tools for users, listings, transactions, and reports. Receipts and reports are also print-friendly.

**Transition:** Markpaul will now explain the database design.

## Slide 9: Database Design

**Speaker: Terante, Markpaul**

The database is organized into normalized tables. The `users` table stores student and admin accounts. The `items` table stores listings, categories, departments, conditions, listing type, status, moderation status, and rental details.

The `transactions` table connects buyers, sellers, and items. It also stores payment details, transaction status, meetup information, and rental due dates. We also have tables for conversations, messages, notifications, and ratings. This structure keeps the system organized and avoids placing unrelated data in one table.

**Transition:** Tejay will discuss the relationships and indexes in the database.

## Slide 10: Database Relationships and Indexes

**Speaker: Bacunlay, Tejay R.**

The database uses proper relationships. Each item belongs to a user and can have many transactions. Each transaction belongs to a buyer, a seller, and an item. Ratings belong to a reviewer, a reviewed user, and a transaction. Conversations connect two users and may also be connected to an item. Messages belong to conversations and users.

The migrations include foreign keys to protect data integrity. The system also includes indexes for marketplace filtering, transaction lookup, notifications, conversations, and messages. These indexes help the system handle common queries more efficiently.

**Transition:** Rhena Mae will explain the security and authorization features.

## Slide 11: Security and Authorization

**Speaker: Nalzaro, Rhena Mae T.**

Security is handled using Laravel's built-in tools and our own access rules. Laravel authentication protects private pages and account actions. Admin pages are protected by `AdminMiddleware`, so regular students cannot access admin functions.

Policies are used to restrict item updates, item requests, transaction viewing, approval, completion, payment proof upload, ratings, and meetup proposal responses. Form Requests validate input before saving. Laravel also provides CSRF protection, password hashing, and login rate limiting. Payment proofs are stored privately and can only be viewed through an authorized route.

**Transition:** Dongie Ana Marie will continue with code quality and best practices.

## Slide 12: Code Quality and Best Practices

**Speaker: Arapoc, Dongie Ana Marie A.**

For code quality, the project separates responsibilities clearly. Controllers focus on receiving requests and returning responses. More complex workflows are placed in service classes, such as the transaction, messaging, notification, and rental workflows.

The models use constants for statuses and listing types, which makes the code more consistent. Validation is placed in Form Request classes, while authorization rules are centralized in Policies and Middleware. Models also define relationships, casts, helper methods, and fillable fields. Reusable Blade components reduce duplicate interface code.

**Transition:** Ian will now explain testing and demo readiness.

## Slide 13: Testing and Demo Readiness

**Speaker: Coronia, Ian Miguel T.**

UM-Pasa is prepared for presentation and testing. The project includes feature tests for authentication, profile management, admin access, and marketplace workflows. These tests help verify that important parts of the system work as expected.

The project also includes seeders for demo accounts and sample marketplace data. This makes our demonstration repeatable because we can log in as an admin, student, seller, buyer, renter, and second seller. We also prepared a demo flow so the class can see the system from browsing up to transaction completion.

**Transition:** Jorlan will connect the project directly to the rubric.

## Slide 14: Rubric Alignment

**Speaker: Villarosa, Jorlan**

This slide shows how UM-Pasa matches the rubric. For Laravel Framework Utilization, the system uses MVC, routes, Eloquent, Blade, Middleware, Policies, Form Requests, services, and an Artisan command. For Functionality and Features, it includes listing, request, payment proof, messaging, receipts, ratings, reports, and admin moderation.

For UI and UX, the system has responsive pages, reusable components, dashboards, status labels, and print-friendly pages. For Database Design and Security, it uses normalized tables, foreign keys, indexes, role checks, validation, CSRF protection, hashed passwords, and private payment proof access. For Code Quality, it follows Laravel conventions and separates responsibilities properly.

**Transition:** Dongie Ana Marie will discuss the limitations and possible improvements.

## Slide 15: Limitations and Future Improvements

**Speaker: Arapoc, Dongie Ana Marie A.**

Although UM-Pasa is complete for an academic marketplace project, there are still possible improvements. The system currently does not have real-time chat, so a future version can use Laravel broadcasting or WebSockets. We can also add stronger student verification, such as student ID verification.

Another improvement is to integrate a real payment gateway and production cloud storage for private files. The admin side can also be improved with audit logs, analytics, exportable reports, and CI/CD for automated testing and deployment.

**Transition:** Markpaul will now close the presentation.

## Slide 16: Conclusion

**Speaker: Terante, Markpaul**

To conclude, UM-Pasa is a complete Laravel MVC marketplace system for the university community. It supports realistic student workflows, including item posting, admin moderation, item requests, payment proof, messaging, transaction completion, receipts, and ratings.

The system uses proper authentication, authorization, validation, Eloquent relationships, database normalization, private file handling, reusable Blade components, and feature tests. Because of this, UM-Pasa directly addresses the excellent-level expectations in the rubric. Thank you, and we are ready for your questions.
