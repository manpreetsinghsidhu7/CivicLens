# CivicLens – 360° Feedback System for Government News

A Laravel MVC web application for publishing Indian government news and collecting structured 360-degree feedback from citizens.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade Templates, Bootstrap 5, Chart.js
- **Database:** PostgreSQL (Supabase) / SQLite (local)
- **API Integration:** NewsData.io (Indian news)

## Features

- **Authentication:** Register, Login, Logout with role-based access (Admin/User)
- **News Management:** CRUD operations, NewsData.io API import, search & filter
- **Feedback System:** 360° structured feedback (Trust, Clarity, Bias, Sentiment)
- **Analytics Dashboard:** Chart.js visualizations (Pie, Bar, Doughnut charts)
- **REST API:** JSON endpoints for news and feedback
- **Email Notifications:** Feedback confirmation emails
- **Infinite Scroll:** YouTube-style lazy loading with skeleton placeholders

## Setup Instructions

### 1. Clone & Install
```bash
cd CivicLens
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database Configuration
Edit `.env` file with your database credentials:

**For Supabase (PostgreSQL):**
```
DB_CONNECTION=pgsql
DB_HOST=your-project.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

**For Local SQLite:**
```
DB_CONNECTION=sqlite
```

### 3. Run Migrations & Seed
```bash
php artisan migrate:fresh --seed
```

### 4. Start Server
```bash
php artisan serve
```
Visit: http://localhost:8000

## Login Credentials

| Role  | Email              | Password |
|-------|--------------------|----------|
| Admin | admin@civiclens.in | password |
| User  | aarav@example.com  | password |

## API Endpoints

| Method | Endpoint          | Description          |
|--------|-------------------|----------------------|
| GET    | /api/news         | List all news        |
| GET    | /api/news/{id}    | Show single news     |
| POST   | /api/feedback     | Submit feedback      |
| GET    | /api/feedback     | List all feedback    |

## Project Structure

```
app/
├── Http/Controllers/
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── NewsController.php
│   ├── FeedbackController.php
│   ├── AdminController.php
│   └── Api/ApiController.php
├── Http/Middleware/
│   └── AdminMiddleware.php
├── Mail/
│   └── FeedbackConfirmation.php
└── Models/
    ├── User.php
    ├── News.php
    └── Feedback.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   └── admin.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── news/
│   ├── index.blade.php
│   └── show.blade.php
├── admin/
│   ├── dashboard.blade.php
│   ├── analytics.blade.php
│   ├── news/ (index, create, edit)
│   └── feedback/ (index)
├── user/
│   └── dashboard.blade.php
├── partials/
│   └── news-card.blade.php
├── emails/
│   └── feedback-confirmation.blade.php
└── home.blade.php
```

## Laravel Concepts Used

MVC Architecture, Routing (named, grouped, resource), Middleware, Blade Templates, Eloquent ORM, Migrations, Seeders, CSRF Protection, Form Validation, Sessions, Flash Messages, REST APIs, Email (Mailable), Authentication, Pagination.

## NewsData.io API

The app integrates NewsData.io to fetch real Indian government news. Admin can import articles via the dashboard "Fetch from NewsData.io" button.
