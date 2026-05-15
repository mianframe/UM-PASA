# UM-Pasa Demo Flow

Run the seeders:

```bash
php artisan migrate --seed
```

Rerun only the walkthrough data:

```bash
php artisan db:seed --class=FlowDemoSeeder
```

All demo accounts use the password `password`.

## Accounts

| Role | Name | Email |
| --- | --- | --- |
| Admin | Admin User | admin@umindanao.edu.ph |
| General student | Student User | student@umindanao.edu.ph |
| Seller | Ava Seller | seller@umindanao.edu.ph |
| Buyer | Marco Buyer | buyer@umindanao.edu.ph |
| Renter | Bea Renter | renter@umindanao.edu.ph |
| Second seller | Jessa Seller | seller2@umindanao.edu.ph |

## Seeded Listings

| Listing | Owner | Type | Status | Moderation |
| --- | --- | --- | --- | --- |
| Engineering Calculator FX-991ES | Ava Seller | Sell | Available | Approved |
| Arduino Starter Kit Rental | Ava Seller | Rent | Available | Approved |
| Nursing Uniform Set for Review | Ava Seller | Sell | Available | Pending |
| Rejected Photocopied Lab Manual | Ava Seller | Sell | Available | Rejected |
| Data Structures Textbook | Ava Seller | Sell | Pending | Approved |
| Java Programming Book | Ava Seller | Sell | Sold | Approved |
| Accounting Review Handouts | Jessa Seller | Sell | Pending | Approved |
| Tourism Presentation Clicker | Jessa Seller | Sell | Available | Approved |

## Seeded Transactions

| Item | Buyer | Seller | Status | Use Case |
| --- | --- | --- | --- | --- |
| Data Structures Textbook | Marco Buyer | Ava Seller | Pending | Seller can approve or reject |
| Accounting Review Handouts | Bea Renter | Jessa Seller | Approved | Seller can complete after meetup |
| Java Programming Book | Marco Buyer | Ava Seller | Completed | Receipt and ratings are available |
| Arduino Starter Kit Rental | Bea Renter | Ava Seller | Completed | Rental history and due notification sample |

## Walkthrough Script

1. Login as `buyer@umindanao.edu.ph`.
2. Open Marketplace and request `Engineering Calculator FX-991ES`.
3. Upload payment proof from the transaction history page.
4. Login as `seller@umindanao.edu.ph`.
5. Open Pending Requests and approve or reject `Data Structures Textbook`.
6. Open Messages to show seeded conversations and meetup proposals.
7. Open Transaction History and view the completed `Java Programming Book` receipt.
8. Open Profile Ratings to show seeded feedback.
9. Login as `admin@umindanao.edu.ph`.
10. Open Admin Items and approve `Nursing Uniform Set for Review` or review the rejected listing reason.
