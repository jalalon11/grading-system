# Grading System

A comprehensive web-based grading system built with Laravel for managing student grades, attendance, and academic records.

## Features

- User Management (Admin, Teacher Admin, Teachers)
- Student Management
- Section Management
- Subject Management
- Grade Recording and Management
- Attendance Tracking
- Report Generation

## Requirements

- PHP >= 8.2
- Laravel 12.x
- MySQL/MariaDB
- Composer
- Node.js & NPM

## Installation

1. Clone the repository
```bash
git clone https://github.com/YOUR_USERNAME/grading-system.git
cd grading-system
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Create environment file
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Configure your database in `.env` file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grading_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations
```bash
php artisan migrate
```

8. Build assets
```bash
npm run dev
```

9. Start the development server
```bash
php artisan serve
```

## Usage

Visit `http://localhost:8000` in your web browser.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
