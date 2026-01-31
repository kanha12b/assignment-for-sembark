# URL Shortener (Laravel 10)

This project is a URL Shortener built using Laravel 10 with
SuperAdmin, Admin, and Member roles and company-based data isolation.

---

## How to Run Project Locally

### 1. Clone Repository

```bash
git clone https://github.com/kanha12b/assignment-for-sembark.git
cd assignment-for-sembark


2. Install Dependencies

composer install
npm install
npm run build


3. Environment Setup

cp .env.example .env
php artisan key:generate

Update database details in .env:

DB_DATABASE=sembark_tech_db
DB_USERNAME=root
DB_PASSWORD=

4. Database Setup

Option 1 (Recommended): Run Migrations

php artisan migrate
php artisan db:seed

This will create all tables and seed the SuperAdmin user.

Option 2 (If Migration Fails)

php artisan migrate:fresh --seed

Option 3 (Manual Database Import)

If migrations still fail:

1. Create a database in MySQL
2. Import the SQL file from the /db folder
3. Update database credentials in .env


5. Run Application

php artisan serve


  Default SuperAdmin Login

    'email' => 'superadmin@sembark.com'
    'password' => 'SuperAdmin@123',




AI tools (e.g. ChatGPT) were used to understand syntax and assist with UI implementation in some parts of the project due to time constraints.
