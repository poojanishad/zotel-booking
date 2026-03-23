## 🚀 Laravel Zotel Booking Project

Follow the steps below to set up the project locally on your machine.

---

### 📋 Prerequisites

Ensure the following dependencies are installed:

* PHP (>= 8.1)
* Composer
* MySQL

---

### 📥 Installation

Clone the repository and navigate into the project directory:

```bash
git clone https://github.com/poojanishad/zotel-booking.git
cd zotel-booking
```

Install the required PHP dependencies:

```bash
composer install
```

---

### ⚙️ Environment Configuration

Create a copy of the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

---

### 🗄️ Database Setup

Update your `.env` file with your database credentials:

```
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Run database migrations:

```bash
php artisan migrate
```

(Optional) Seed the database with sample data:

```bash
php artisan db:seed
```

---

### ▶️ Running the Application

Start the local development server:

```bash
php artisan serve
```

Access the application in your browser:

```
http://127.0.0.1:8000
```

---
