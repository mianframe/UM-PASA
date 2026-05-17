# UM-Pasa Defense Script

## Opening

Good day. Our project is **UM-Pasa: Campus Marketplace**, a Laravel-based web application for the University of Mindanao community. It helps students buy, rent, request, message, and complete transactions for academic items such as books, uniforms, calculators, gadgets, and course materials.

The problem we focused on is that students often rely on scattered group chats or social media posts when looking for academic items. UM-Pasa gives them one organized platform with user accounts, marketplace listings, transaction tracking, messaging, notifications, ratings, and admin moderation.

## MVC Architecture

UM-Pasa follows Laravel's MVC architecture.

The **routes** are defined in `routes/web.php`. Public pages include the home page, about page, help page, marketplace, and item details. Authenticated routes include dashboards, item posting, transactions, messages, notifications, ratings, reports, and profile management. Admin routes are grouped separately and protected by authentication plus admin middleware.

The **controllers** are in `app/Http/Controllers`. Examples are:

- `ItemController` for marketplace listing, creation, editing, filtering, and item display
- `TransactionController` for item requests, approvals, completion, receipts, and payment proof access
- `MessageController` for conversations and meetup proposals
- `AdminController` for user, item, and transaction monitoring
- `ReportController` for printable student and admin reports

The **models** are in `app/Models`. The main models are `User`, `Item`, `Transaction`, `Conversation`, `Message`, `Notification`, and `Rating`. These models represent the database tables and define relationships such as user-to-items, item-to-transactions, conversation-to-messages, and transaction-to-ratings.

The **views** are Blade templates in `resources/views`. They display the marketplace, dashboards, forms, admin pages, messages, notifications, receipts, reports, and profile pages.

## Laravel Features Used

We used Laravel features beyond basic routing:

- Eloquent ORM for database operations and relationships
- Blade templates and components for reusable UI
- Form Requests for validation
- Policies and middleware for authorization
- Route model binding for cleaner controller methods
- Service classes for transaction, messaging, notification, and rental workflows
- Migrations and seeders for database setup and demo data
- Artisan command and scheduler for expiring stale requests
- CSRF protection on forms
- Laravel authentication and password handling

## Database Design

The database is normalized into separate tables:

- `users` stores student and admin accounts
- `items` stores marketplace listings
- `transactions` stores item requests and transaction status
- `conversations` stores chat threads
- `messages` stores chat messages and meetup proposals
- `notifications` stores system updates
- `ratings` stores feedback after completed transactions

Foreign keys connect the records properly. For example, each item belongs to a user, each transaction connects a buyer, seller, and item, and each rating belongs to a transaction. Indexes are added for marketplace filtering, transaction lookups, notifications, conversations, and messages.

## Security

Security was handled using Laravel's built-in protections and project-specific rules:

- Users must log in before posting, requesting, messaging, rating, or viewing private pages
- Admin pages are protected by `AdminMiddleware`
- Policies restrict access to items, transactions, messages, and conversations
- Form Requests validate inputs before saving data
- CSRF tokens protect POST forms
- Passwords are hashed by Laravel
- Login requests are rate-limited
- Marketplace queries use Eloquent/query builder instead of raw SQL
- Uploaded item images are validated by file type and size
- Payment proofs are accessed through an authorized transaction route instead of a direct public storage link
- User roles are not mass assignable, reducing privilege escalation risk

## UI / UX Design

The interface was designed to be understandable for students and admins.

For students, the marketplace has search, filters, category chips, item cards, item detail pages, transaction history, pending request actions, notifications, messages, and receipts.

For admins, the system includes dashboard statistics, item moderation, user monitoring, transaction overview, and printable reports.

The design uses:

- Responsive layouts for desktop and mobile
- Reusable buttons, cards, badges, inputs, and navigation components
- Status labels for pending, approved, rejected, completed, available, sold, and review states
- Empty states when no records exist
- Flash messages for feedback
- Loading/disabled submit states to prevent repeated form submissions
- Print-friendly receipts and reports

## Demo Flow

1. Open the home page and explain the marketplace purpose.
2. Log in as a buyer and browse the marketplace.
3. Open an item, select a payment method, and request it.
4. Upload payment proof from transaction history.
5. Log in as the seller and review pending requests.
6. Approve a request with meetup details or reject it with a reason.
7. Open messages and show the meetup proposal feature.
8. Complete an approved transaction.
9. View the receipt and leave a rating.
10. Log in as admin and show item moderation, reports, users, and transactions.

## Limitations and Future Improvements

UM-Pasa is designed as an excellent academic system, not a full commercial SaaS platform yet. Current limitations include:

- No real payment gateway
- No real-time chat
- No delivery tracking
- No cloud storage setup
- No full admin audit log
- No CI/CD pipeline

Future improvements can include real-time chat, stronger student verification, more advanced analytics, private cloud file storage, exportable reports, and richer admin audit history.

## Closing

In summary, UM-Pasa demonstrates Laravel MVC, authentication, authorization, validation, Eloquent relationships, CRUD workflows, database design, security practices, responsive UI, and real marketplace workflows. The system is suitable for academic demonstration and meets the goal of a polished student marketplace project.
