# Deployment Guide for Meeting Room Booking System

## ⚠️ Important Note About Vercel

**Vercel has LIMITED support for PHP/Laravel applications.** While it's possible to deploy Laravel on Vercel using community-maintained PHP runtimes, there are significant limitations:

- ⚠️ Database connections may be slow/unreliable
- ⚠️ File storage issues (use external storage like S3)
- ⚠️ No queue workers
- ⚠️ No scheduled tasks (cron jobs)
- ⚠️ Cold starts can be slow
- ⚠️ Limited PHP extensions

## ✅ Recommended Platforms for Laravel

### 1. **Railway** (Recommended - Easiest)
- ✅ Native PHP/Laravel support
- ✅ PostgreSQL database included
- ✅ Automatic deployments from GitHub
- ✅ Free tier available

**Setup:**
1. Go to [railway.app](https://railway.app)
2. Connect your GitHub repository
3. Railway will auto-detect Laravel
4. Add PostgreSQL database service
5. Set environment variables
6. Deploy!

### 2. **Render** (Good Alternative)
- ✅ Native PHP/Laravel support
- ✅ PostgreSQL database
- ✅ Free tier available

**Setup:**
1. Go to [render.com](https://render.com)
2. Connect GitHub repository
3. Select "Web Service"
4. Use the `render.yaml` configuration file
5. Add PostgreSQL database
6. Set environment variables

### 3. **Fly.io** (Great for Global Distribution)
- ✅ Docker-based deployment
- ✅ Global edge locations
- ✅ PostgreSQL support

### 4. **DigitalOcean App Platform**
- ✅ Native Laravel support
- ✅ Managed PostgreSQL

## 🚀 Vercel Deployment (If You Still Want to Try)

### ⚠️ Important: Vercel Build Limitation
**Vercel's build environment doesn't have PHP/Composer available.** You must commit the `vendor` directory to your repository for Vercel to work. This is not ideal but necessary.

### Prerequisites:
1. Your code must be in a Git repository (GitHub, GitLab, or Bitbucket)
2. Database must be hosted separately (Supabase, Railway, etc.)
3. File storage should use external service (S3, Cloudinary, etc.)

### Steps:

1. **Commit the vendor directory** (Required for Vercel):
   ```bash
   # Remove vendor from .gitignore temporarily
   sed -i.bak '/^\/vendor$/d' .gitignore
   
   # Install dependencies locally
   composer install --no-dev --optimize-autoloader
   
   # Commit vendor directory
   git add vendor/ .gitignore
   git commit -m "Add vendor directory for Vercel deployment"
   
   # Restore .gitignore (optional, or keep vendor committed)
   # mv .gitignore.bak .gitignore
   ```

2. **Create the API directory** (already done):
   ```bash
   mkdir -p api
   # api/index.php is already created
   ```

3. **Push to GitHub:**
   ```bash
   git add .
   git commit -m "Add Vercel configuration"
   git push
   ```

4. **Deploy on Vercel:**
   - Go to [vercel.com](https://vercel.com)
   - Click "Add New Project"
   - Import your GitHub repository
   - **Important**: In Project Settings:
     - **General Settings:**
       - Set **Node.js Version** to `22.x` (required for vercel-php@0.7.4)
     - **Framework Settings:**
       - Set **Framework Preset** to "Other"
       - Set **Root Directory** to "." (or leave blank)
       - Set **Output Directory** to "." (or leave blank) - Laravel doesn't use a dist folder
       - **DO NOT override** Build/Install commands (leave them as default or empty)
       - The `vendor` directory will be used from your repository
   - The `vercel.json` configuration will also be used (runtime: vercel-php@0.7.4)

5. **Set Environment Variables in Vercel Dashboard:**
   ```
   APP_KEY=base64:your-generated-key
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-project.vercel.app
   DB_CONNECTION=pgsql
   DB_HOST=your-supabase-host
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   DB_SSLMODE=require
   SESSION_DRIVER=cookie
   CACHE_DRIVER=array
   LOG_CHANNEL=stderr
   ```

5. **Run Migrations:**
   - Use Vercel CLI: `vercel env pull .env.local`
   - Or connect via SSH and run: `php artisan migrate`

## 📋 Pre-Deployment Checklist

1. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

2. **Optimize for Production:**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Database:**
   - Ensure your Supabase database is accessible
   - Run migrations: `php artisan migrate`
   - Seed data (optional): `php artisan db:seed`

4. **Environment Variables:**
   - Set all required variables in Vercel dashboard
   - Never commit `.env` file

## 🔧 Environment Variables Template

```env
APP_NAME="Meeting Room Booking"
APP_ENV=production
APP_KEY=base64:your-key-here
APP_DEBUG=false
APP_URL=https://your-project.vercel.app

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=db.clcwmggtmphfhlrrmdlk.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.clcwmggtmphfhlrrmdlk
DB_PASSWORD=your-password
DB_SSLMODE=require

SESSION_DRIVER=cookie
SESSION_LIFETIME=120

CACHE_DRIVER=array
QUEUE_CONNECTION=sync
```

## 📚 Additional Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Vercel PHP Runtime](https://github.com/vercel-community/php)
- [Railway Documentation](https://docs.railway.app)
- [Render Documentation](https://render.com/docs)
