# 🏦 Laravel Account Management System

This project is a Laravel-based **Account Management System** that supports:
- Account creation & management
- Luhn-compliant account number generation
- Secure transactions (credit & debit)
- Fund transfers between accounts
- API authentication using Laravel Sanctum

---

## 🚀 Setup Instructions

# Clone the Repository

git clone https://github.com/firoz16/account-management.git
cd account-management


# Install Dependencies

composer install
npm install && npm run dev


# Configure the Environment
Copy the `.env.example` file and set up your database connection:

cp .env.example .env

Then, update `.env` with:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_accounts
DB_USERNAME=root
DB_PASSWORD=yourpassword


# Generate Application Key

php artisan key:generate


# Run Migrations & Seeders

php artisan migrate


# Start the Laravel Application

php artisan serve

> The API will be available at **`http://127.0.0.1:8000`**

---

## **📌 API Documentation**

### Authentication
| Method | Endpoint         | Description              |
|--------|-----------------|--------------------------|
| POST   | `/api/register` | Register a new user      |
| POST   | `/api/login`    | Authenticate a user      |

### Accounts
| Method | Endpoint                        | Description                      |
|--------|----------------------------------|----------------------------------|
| POST   | `/api/accounts`                  | Create a new account            |
| GET    | `/api/accounts/{account_number}` | Fetch account details           |
| PUT    | `/api/accounts/{account_number}` | Update account details          |
| DELETE | `/api/accounts/{account_number}` | Deactivate an account           |
| GET    | `/api/accounts`                  | List all accounts, created by same user|

### Transactions
| Method | Endpoint  | Description  |
|--------|----------|--------------|
| POST   | `/api/transactions` | Log a credit or debit transaction |
| GET    | `/api/transactions?account_id=X&from=YYYY-MM-DD&to=YYYY-MM-DD` | Get transaction history |

###  Fund Transfers
| Method | Endpoint  | Description  |
|--------|----------|--------------|
| POST   | `/api/transfers` | Transfer money between accounts |

---

## **✅ Running Tests**
Execute test cases using:

php artisan test


---



