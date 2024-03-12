Installation

Step 1: Install Dependencies
Run the following command to install project dependencies:
composer install

Step 2: Configure Environment
Copy the .env.example file and rename it to .env:
cp .env.example .env

Step 3: Generate Application Key & Setup Database name 
Generate the application key by running the following command:
php artisan key:generate

Step 4: Run Migrations
Run database migrations to set up the database schema:
php artisan migrate

Step 5: Run Project
php artisan serve
