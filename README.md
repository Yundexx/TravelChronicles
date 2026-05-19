# Travel Chronicles: A Platform for Sharing Travel Routes

**Project Overview**  
*Travel Chronicles* is a platform where users can share walking and travel routes, add notes, tips, and reviews. Users can create their own routes with map markers, specifying the number of participants, food/water supplies, and estimated travel time. The platform helps plan trips, making them more comfortable and safer through community-shared experiences.

## Key Features:
- **Route Creation**: Set map markers, plan time, supplies, and route difficulty.
- **Route Navigation**: Personal mode without publishing or using existing routes with comments and obstacle confirmations.
- **Surveys**: Before, during, and after the route to record tips, photos, and feedback.
- **Route Evaluation**: Rated by difficulty, scenery, and popularity.

*Travel Chronicles* makes route planning and navigation engaging and convenient, connecting travelers through shared experiences and recommendations.

---

# Project Setup Guide

This guide walks you through getting your computer ready to run this Laravel-based project.

---

## Requirements

Make sure you have the following installed:

- PHP >= 8.1
- Composer
- Laravel CLI
- MySQL or other supported database
- Node.js + npm (for front-end asset compilation)
- Git (optional, for version control)

---

### 1. Clone the Repository in existing folder

```bash
git clone https://github.com/DaYundexTuxi/TravelChronicles.git
cd TravelChronicles (or yourself named folder)
```

> 🔁 Replace with your actual repository URL.

---

### 2. Install PHP Dependencies

```bash
composer install
```

---

### 3. Copy Environment File

```bash
cp .env.example .env
```

Get a template for `.env` and edit file to match your local database settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database 
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

### 5. Run Database Migrations (Optional if database is already created)

```bash
php artisan migrate
```

---

### 6. Install Node Dependencies and Compile Assets

```bash
npm install
npm run dev
```

> 💡 Use `npm run build` for production.

---

### 7. Start the Development Server (on different terminal)

```bash
php artisan serve
```

Open your browser and navigate to:

```
http://localhost:8000
```

You should see the welcome page (`welcome.blade.php`).

---

## 🐛 Troubleshooting

- Run `php artisan config:clear` if config values seem off.
- Use `php artisan migrate:fresh` if you need to reset the DB.
- Use Laravel logs (`storage/logs/`) for debugging issues.
