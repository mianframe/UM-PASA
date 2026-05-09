# UM-Pasa: Academic Resource Marketplace System
## Complete Setup & Testing Guide

---

## 📋 PROJECT COMPLETE

All components of the UM-Pasa capstone system have been successfully built:
- ✅ 5 Database Migrations
- ✅ 5 Models with Relationships
- ✅ 5 Controllers
- ✅ 13 Blade Views
- ✅ 2 Authorization Policies
- ✅ Admin Middleware
- ✅ Complete Transaction Flow
- ✅ Rating System
- ✅ Notification System

---

## 🚀 SETUP INSTRUCTIONS

### 1. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 2. Environment Setup
```bash
# If you don't have a .env file
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 3. Database Setup
```bash
# Run all migrations
php artisan migrate

# Optional: Seed with test data
php artisan db:seed
```

### 4. Storage Setup
```bash
# Create symbolic link for image storage
php artisan storage:link
```

### 5. Create a Test Admin User (Optional)
```bash
php artisan tinker

# In the Tinker shell:
>>> $user = User::create([
...     'name' => 'Admin User',
...     'email' => 'admin@umindanao.edu.ph',
...     'password' => Hash::make('password'),
...     'role' => 'admin'
... ]);

>>> exit
```

### 6. Run Development Server
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## 👥 TEST ACCOUNTS

### Student Account
- Email: `student@umindanao.edu.ph`
- Password: `password`
- Role: Student (default)

### Admin Account
- Email: `admin@umindanao.edu.ph`
- Password: `password`
- Role: Admin

> ⚠️ **Note**: Email validation requires `@umindanao.edu.ph` domain

---

## 🎯 KEY FEATURES TO TEST

### 1. Authentication (Register/Login)
- ✅ Must use @umindanao.edu.ph email
- ✅ Password confirmation required
- ✅ Auto-redirects to dashboard

**Test Path:**
```
/ → Register → Student Dashboard
```

### 2. Item Management
- ✅ Post new items (Sell/Rent/Swap)
- ✅ Upload item image (jpg/png)
- ✅ Edit own items
- ✅ Delete own items
- ✅ Filter by department, course code, listing type

**Test Path:**
```
Dashboard → Items → Create Item
→ Fill form → Submit
→ See item in marketplace
```

### 3. Transaction Flow (Core Feature)
- ✅ Request item from another student
- ✅ Seller receives notification
- ✅ Seller approves/rejects with meetup details
- ✅ Buyer sees approved transaction
- ✅ Seller marks completed
- ✅ Item status changes to "sold"

**Test Path:**
```
1. Login as Student A
2. Create Item → Post
3. Logout, Login as Student B
4. Browse Items → Click Request
5. Logout, Login as Student A
6. Transactions → Pending → Approve (add meetup details)
7. Logout, Login as Student B
8. Transactions → History → View Receipt
```

### 4. Rating System
- ✅ Rate after completed transaction
- ✅ Star rating (1-5)
- ✅ Optional comment
- ✅ View profile ratings

**Test Path:**
```
Transaction Receipt → Leave a Rating
→ Profile → See Your Ratings
```

### 5. Notifications
- ✅ Request received → Seller notified
- ✅ Request approved → Buyer notified
- ✅ Request rejected → Buyer notified
- ✅ Transaction completed → Buyer notified
- ✅ Rating received → Seller notified

**Test Path:**
```
Dashboard → Notifications → All notifications appear
→ Click "Mark all read" to mark as read
```

### 6. Admin Panel
- ✅ View all users
- ✅ View all transactions
- ✅ Moderate items (delete inappropriate)

**Test Path:**
```
Login as Admin → Dashboard → View Users/Transactions/Items
→ Delete Item (if needed)
```

---

## 📱 USER WORKFLOWS

### Student Workflow (Seller)
```
1. Register with UM email
2. Login to Dashboard
3. Post Item (Sell/Rent/Swap)
4. Receive requests from other students
5. Approve request with meetup details
6. Mark transaction as completed
7. Receive rating from buyer
8. View profile ratings
```

### Student Workflow (Buyer)
```
1. Register with UM email
2. Login to Dashboard
3. Browse marketplace items
4. Request item from seller
5. Wait for seller approval
6. View transaction receipt
7. Meet seller at agreed location
8. Leave rating after transaction
```

### Admin Workflow
```
1. Login as admin
2. Review all users
3. Monitor transactions
4. Delete inappropriate items
5. Manage marketplace health
```

---

## 💾 DATABASE STRUCTURE

### Users Table
- id, name, email, password, role (student/admin), created_at, updated_at

### Items Table
- id, user_id (seller), title, description, department, course_code
- listing_type (sell/rent/swap), price, image, status (available/pending/sold)
- created_at, updated_at

### Transactions Table
- id, buyer_id, seller_id, item_id, status (pending/approved/rejected/completed)
- meetup_location, meetup_time, created_at, updated_at

### Ratings Table
- id, reviewer_id, reviewed_user_id, transaction_id, rating (1-5)
- comment, created_at, updated_at

### Notifications Table
- id, user_id, message, type, related_id, is_read, created_at, updated_at

---

## 🎨 UI COMPONENTS

All views use:
- ✅ Tailwind CSS for styling
- ✅ Blade templating
- ✅ Responsive design (mobile-friendly)
- ✅ Simple, clean cards layout
- ✅ Form validation messages
- ✅ Success/error flash messages

---

## 🔐 SECURITY FEATURES

- ✅ Email domain validation (@umindanao.edu.ph)
- ✅ Role-based access control (middleware)
- ✅ Authorization policies (can edit/delete own items)
- ✅ CSRF protection (Laravel default)
- ✅ Password hashing (Laravel default)
- ✅ Prevent duplicate requests (same user, same item)
- ✅ Prevent requesting sold items
- ✅ Prevent requesting own items

---

## 📊 TRANSACTION FLOW DIAGRAM

```
1. BUYER REQUESTS ITEM
   Buyer views marketplace → Clicks "Request Item"
   → Item status changes to "pending"
   → Seller receives notification

2. SELLER APPROVES/REJECTS
   Seller views pending requests
   → If approves: Enters meetup location & time
   → Item stays "pending"
   → Buyer receives notification

3. TRANSACTION COMPLETED
   Seller marks transaction as "completed"
   → Item status changes to "sold"
   → Buyer receives notification

4. RATING (Optional)
   After completion, either party can leave rating
   → Rating appears on profile
   → Seller/Buyer receives notification
```

---

## 🎓 DEMO DEFENSE NOTES

### System Overview (2 min)
"UM-Pasa is a university marketplace where students can buy, sell, rent, and swap academic resources using their official university email."

### Key Features (3 min)
1. **Registration**: UM email only
2. **Item Management**: Post items with images, edit, delete
3. **Transactions**: Request → Approve → Complete → Rate
4. **Notifications**: Real-time system notifications
5. **Admin Panel**: Moderation and oversight

### Database Design (2 min)
"Five main tables with proper relationships:
- Users (student/admin roles)
- Items (with listing types)
- Transactions (multi-stage flow)
- Ratings (post-transaction)
- Notifications (event-driven)"

### Transaction Flow (3 min)
[Walk through a complete transaction from request to rating]

---

## 🐛 TROUBLESHOOTING

### "Storage link not working"
```bash
php artisan storage:link
```

### "Email validation fails"
Make sure email ends with `@umindanao.edu.ph` (case-insensitive)

### "Images not showing"
```bash
# Check storage link
ls -la storage/app/public

# Run this if needed
php artisan storage:link
```

### "Migrations fail"
```bash
# Reset and re-migrate
php artisan migrate:reset
php artisan migrate
```

### "Cannot delete user"
Admin users cannot be deleted via UI (cascade protection).
Use: `php artisan tinker` → `User::find(id)->delete()`

---

## 📝 NOTES FOR DEMO

✅ The system is intentionally simple and beginner-friendly
✅ Code is clean, well-organized, and easy to explain
✅ All features work end-to-end
✅ Ready for live demonstration
✅ Can scale up later if needed

**Estimated Demo Time**: 10-15 minutes

---

## 📚 FILE STRUCTURE REFERENCE

```
app/
├── Models/
│   ├── User.php
│   ├── Item.php
│   ├── Transaction.php
│   ├── Rating.php
│   └── Notification.php
├── Http/
│   ├── Controllers/
│   │   ├── ItemController.php
│   │   ├── TransactionController.php
│   │   ├── RatingController.php
│   │   ├── NotificationController.php
│   │   ├── AdminController.php
│   │   ├── DashboardController.php
│   │   └── Auth/RegisteredUserController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/
└── Policies/
    ├── ItemPolicy.php
    └── TransactionPolicy.php

resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── items/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── transactions/
│   ├── history.blade.php
│   ├── pending.blade.php
│   └── receipt.blade.php
├── ratings/
│   └── create.blade.php
├── dashboard/
│   ├── student.blade.php
│   └── admin.blade.php
├── admin/
│   ├── users.blade.php
│   ├── items.blade.php
│   └── transactions.blade.php
└── layouts/
    ├── app.blade.php
    └── navigation.blade.php

database/migrations/
├── 2026_05_07_084500_create_items_table.php
├── 2026_05_07_084501_create_transactions_table.php
├── 2026_05_07_084502_create_ratings_table.php
├── 2026_05_07_084503_create_notifications_table.php
└── 2026_05_07_084504_add_role_to_users_table.php
```

---

**System Status**: ✅ COMPLETE AND READY FOR DEFENSE
**Last Updated**: May 7, 2026
