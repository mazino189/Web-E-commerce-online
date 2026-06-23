# ⚡ VOLTAIRE / TECH — Premium Electronics eCommerce Platform

This is a modern, full-stack, highly secure, and performance-optimized eCommerce marketplace. It features a Laravel API Backend, a React + Vite + TypeScript Frontend styled in a premium Cyber-Minimalist dark theme, and secure Cloudinary cloud image storage.

---

## 🚀 Quick Start Guide (For Team Members)

Follow these steps sequentially to clone, configure, and run the entire project locally on your machine.

---

### 📂 Step 1: Clone the Repository
Open your terminal and run:
```bash
git clone https://github.com/mazino189/Web-E-commerce-online.git
cd Web-E-commerce-online
```

### 🐘 Step 2: Backend Setup (PHP & Laravel)
The backend is powered by Laravel 13.x and PHP 8.3.

1. **Install Dependencies:**
```bash
composer install
```

2. **Configure Environment:**
Copy the example environment file and set up your application key:
```bash
cp .env.example .env
php artisan key:generate
```
*(Ensure `DB_CONNECTION=sqlite` is set in your `.env` for local development, or point it to your preferred database).*

3. **Database Migration & Seeding:**
Create the SQLite database file and populate it with initial data (Admin/User accounts, Categories, Brands, Products):
```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```
*Note: The seeder automatically creates an admin account (`admin@gmail.com` / `password`).*

4. **Link Storage:**
```bash
php artisan storage:link
```

---

### ⚛️ Step 3: Frontend Setup (React & Vite)
The frontend uses React with TypeScript, Vite, and Tailwind CSS.

1. **Navigate to the Frontend Directory:**
```bash
cd frontend
```

2. **Install Node Dependencies:**
```bash
npm install
```

3. **Build the Frontend (Optional but recommended for testing production):**
```bash
npm run build
```

---

### 🏃 Step 4: Run the Application Locally

To start developing, you need to run both the Laravel backend server and the Vite frontend server.

**Start the Backend API:**
Open a terminal in the root directory and run:
```bash
php artisan serve
```
*(The API will be available at http://localhost:8000)*

**Start the Frontend Dev Server:**
Open a separate terminal in the `frontend/` directory and run:
```bash
npm run dev
```
*(The React UI will be available at the URL provided by Vite, usually http://localhost:5173)*

---

## 🏗️ Architecture & Technology Stack
- **Framework**: Laravel 13.x, PHP ^8.3, SQLite
- **Frontend**: Vite + React + TypeScript + Tailwind CSS v3 + Alpine.js + Bootstrap 5
- **Auth**: Laravel Sanctum / Breeze API authentication
- **Deployment**: Docker (`php:8.3-apache`) configured for Render deployments.

## 🛠️ Key Artisan Commands
| Command | Purpose |
|---|---|
| `php artisan db:seed` | Seeds database with initial CSV products, admin user, categories, and brands. |
| `php artisan test` | Runs PHPUnit test suite (uses in-memory SQLite, no `.env` required). |
| `composer pint` | Runs Laravel Pint for PSR-12 code style enforcement. |

---

*For further assistance or API documentation, please refer to the internal team wiki or reach out to the DevOps team.*
