# Quick Setup Guide

## Issues Fixed

The following issues have been resolved:
1. ❌ Removed Wayfinder plugin (causing PHP artisan command failures)
2. ❌ Fixed Vite configuration 
3. ❌ Updated package.json dependencies
4. ❌ Added dashboard stats API endpoint

## Installation Steps

### 1. Install PHP Dependencies
```bash
composer install --ignore-platform-reqs
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doc_sys
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Install Node Dependencies
```bash
npm install
```

### 6. Start Development Server
```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server
npm run dev
```

## Access the Application
- Frontend: http://localhost:8000
- API: http://localhost:8000/api

## Node.js Version Issue
If you encounter Node.js version issues, you have two options:

### Option 1: Upgrade Node.js (Recommended)
```bash
# Using nvm (recommended)
nvm install 22.12.0
nvm use 22.12.0

# Or download from https://nodejs.org
```

### Option 2: Downgrade Vite (Temporary Fix)
```bash
npm install vite@^5.0.0 --save-dev
```

## Troubleshooting

### "vendor/autoload.php not found"
Run: `composer install`

### "php artisan command not found"
Make sure you're in the project directory and run `composer install`

### Vite build errors
- Ensure Node.js version is 20.19+ or 22.12+
- Run `npm install` to update dependencies
- Clear cache: `npm run build && rm -rf node_modules/.vite`

### Database connection errors
- Verify database credentials in `.env`
- Ensure database server is running
- Create the database if it doesn't exist

### Permission issues
On Linux/Mac:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Development Features Working

✅ Dashboard with stats
✅ Student Management CRUD
✅ Faculty Management CRUD  
✅ Course/Curriculum Management
✅ Scheduling System
✅ Events Management
✅ Comprehensive Search
✅ Responsive UI with Dark Mode
✅ API Endpoints for all modules

## Next Steps

1. Run the setup commands above
2. Access the application at http://localhost:8000
3. Start exploring the system features
4. Check the API documentation at `/api` routes
