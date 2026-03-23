🚀 Laravel Project Setup

Follow these steps to set up the project locally.

📋 Prerequisites

Make sure you have installed:

PHP (>= 8.1)
Composer
MySQL 

📥 Installation

Clone the repository:
git clone https://github.com/poojanishad/zotel-booking.git
cd zotel-booking

Install PHP dependencies:

composer install
⚙️ Environment Setup

Copy .env file:

cp .env.example .env

Generate application key:

php artisan key:generate
🗄️ Database Setup

Update your .env file with database credentials:

DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

Run migrations:

php artisan migrate

php artisan db:seed

▶️ Run the Application

Start Laravel server:

php artisan serve

Visit:

http://127.0.0.1:8000
