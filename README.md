# Oda POS Backend API

A Laravel 12 REST API backend for a Point of Sale (POS) system with MySQL database.

## Features

- **Categories Management**: Organize products by categories (Food, Drinks, Bakery, Desserts)
- **Products Management**: Full CRUD for products with modifiers support
- **Table Management**: Interactive floor plan with table status tracking
- **Order Management**: Create, update, and track orders with real-time status
- **Payment Processing**: Support for multiple payment methods (Cash, Card, Other)
- **User Management**: Role-based access control (Admin, Manager, Cashier, Waiter)

## Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL 9.4
- **PHP**: 8.4.13

## Installation

### Prerequisites

- PHP 8.4+
- Composer
- MySQL 9.4+

### Setup

1. **Clone and navigate to backend directory**
   ```bash
   cd backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   - Database is already configured to use MySQL
   - Database name: `oda_pos`
   - Connection: `mysql://root@127.0.0.1:3306`

4. **Run migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed database with sample data**
   ```bash
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

   Server will run at: `http://127.0.0.1:8000`

## Database Schema

### Tables

- **users**: Staff members with roles (admin, manager, cashier, waiter)
- **categories**: Product categories
- **products**: Menu items with prices and modifiers
- **tables**: Restaurant tables with floor plan positions
- **orders**: Customer orders with status tracking
- **order_items**: Individual items in orders
- **payments**: Payment records for orders

## API Endpoints

### Categories
- `GET /api/categories` - List all categories with products
- `POST /api/categories` - Create category
- `GET /api/categories/{id}` - Get category details
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category

### Products
- `GET /api/products` - List all products (supports filtering by category, search, availability)
- `POST /api/products` - Create product
- `GET /api/products/{id}` - Get product details
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

### Tables
- `GET /api/tables` - List all tables with current orders
- `POST /api/tables` - Create table
- `GET /api/tables/{id}` - Get table details with order history
- `PUT /api/tables/{id}` - Update table
- `DELETE /api/tables/{id}` - Delete table

### Orders
- `GET /api/orders` - List all orders (supports filtering by status, table)
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order
- `POST /api/orders/{id}/send-to-kitchen` - Send order to kitchen
- `POST /api/orders/{id}/complete` - Mark order as completed

### Payments
- `GET /api/payments` - List all payments
- `POST /api/payments` - Process payment
- `GET /api/payments/{id}` - Get payment details
- `PUT /api/payments/{id}` - Update payment
- `DELETE /api/payments/{id}` - Delete payment

## Sample Data

The seeder creates:

### Users (PIN for quick login)
- **Admin**: admin@oda-pos.com / PIN: 123456
- **Manager**: manager@oda-pos.com / PIN: 111111
- **Cashier**: cashier@oda-pos.com / PIN: 222222
- **Waiter**: waiter@oda-pos.com / PIN: 333333

All users have password: `password`

### Sample Products
- 4 Categories with 15+ products
- Products include modifiers (e.g., "No sugar", "Extra spicy")
- Price range: $2.49 - $14.99

### Tables
- 12 tables configured in a 4x3 grid
- Capacities ranging from 2 to 8 seats

## Example API Usage

### Create an Order
```bash
curl -X POST http://127.0.0.1:8000/api/orders \
  -H "Content-Type: application/json" \
  -d '{
    "table_id": 1,
    "user_id": 1,
    "items": [
      {
        "product_id": 1,
        "quantity": 2,
        "modifiers": ["No onions"]
      },
      {
        "product_id": 5,
        "quantity": 1
      }
    ]
  }'
```

### Process Payment
```bash
curl -X POST http://127.0.0.1:8000/api/payments \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": 1,
    "payment_method": "cash",
    "amount": 28.97
  }'
```

## Order Status Flow

1. **pending** - Initial order state
2. **sent_to_kitchen** - Order sent to kitchen for preparation
3. **preparing** - Kitchen is preparing the order
4. **ready** - Order is ready for pickup/delivery
5. **completed** - Order completed and paid
6. **cancelled** - Order was cancelled
7. **on_hold** - Order temporarily on hold

## Table Status

- **available** - Table is free
- **occupied** - Table has active order
- **reserved** - Table is reserved

Tables automatically update status based on order state.

## Development Notes

- CORS is enabled for frontend integration
- API routes are prefixed with `/api`
- All timestamps are stored in UTC
- JSON responses include nested relationships where appropriate
- Validation errors return 422 status with error details

## Next Steps

For the complete POS system, you'll need to build the Next.js frontend that consumes these API endpoints. The frontend should implement:

1. Order entry screen with menu grid
2. Table management floor plan
3. Order control panel
4. Payment screen with split payment support
5. User authentication with PIN/password
6. Role-based UI features

---

**Built with Laravel 12 for Oda POS System**
