# Samay PMS Deployment Guide

## Why Vercel Doesn't Work

Vercel is a **serverless platform** designed for Node.js, Python, Go, and other modern languages. It **does not support PHP runtime**, which is why your application is downloading the `index.php` file instead of executing it.

## Recommended Deployment Options

### Option 1: Railway (Recommended)

Railway supports PHP applications and provides MySQL databases.

#### Steps:
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Click "Deploy from GitHub repo"
4. Select your `Final-Minical-PMS` repository
5. Add a MySQL database service
6. Set environment variables:
   ```
   ENVIRONMENT=production
   DATABASE_HOST=<railway-db-host>
   DATABASE_USER=<railway-db-user>
   DATABASE_PASS=<railway-db-password>
   DATABASE_NAME=<railway-db-name>
   PROJECT_URL=https://<your-app-domain>
   API_URL=https://<your-app-domain>/api
   ```

### Option 2: Render

Render supports PHP applications with MySQL.

#### Steps:
1. Go to [render.com](https://render.com)
2. Sign up with GitHub
3. Create a new "Web Service"
4. Connect your GitHub repository
5. Use these settings:
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php -S 0.0.0.0:$PORT public/index.php`
6. Add a MySQL database
7. Set environment variables

### Option 3: DigitalOcean App Platform

#### Steps:
1. Go to [DigitalOcean App Platform](https://cloud.digitalocean.com/apps)
2. Create a new app from GitHub
3. Select PHP as the runtime
4. Add a MySQL database
5. Configure environment variables

### Option 4: Traditional VPS

Deploy to a traditional VPS with:
- **DigitalOcean Droplet**
- **Linode**
- **AWS EC2**
- **Google Cloud Compute**

#### Requirements:
- PHP 7.3+
- MySQL/MariaDB
- Nginx or Apache
- Composer

## Environment Variables Needed

```bash
ENVIRONMENT=production
DATABASE_HOST=your-db-host
DATABASE_USER=your-db-user
DATABASE_PASS=your-db-password
DATABASE_NAME=your-db-name
PROJECT_URL=https://your-domain.com/public
API_URL=https://your-domain.com/api
DEVMODE_PASS=your-secure-password
```

## Database Setup

After deployment, you'll need to:
1. Run the database migrations
2. Set up the initial data
3. Configure the application

## Quick Start with Railway

1. **Deploy to Railway**: https://railway.app
2. **Add MySQL Database**: In Railway dashboard, add MySQL service
3. **Set Environment Variables**: Copy the database credentials
4. **Deploy**: Railway will automatically build and deploy your app

Your app will be available at: `https://your-app-name.railway.app`

## Troubleshooting

- **PHP Errors**: Check the logs in your hosting platform
- **Database Connection**: Verify environment variables
- **File Permissions**: Ensure uploads directory is writable
- **Extensions**: Make sure all extensions are properly registered

## Support

For deployment issues, check:
- Platform-specific documentation
- PHP version compatibility
- Database connection settings
- Environment variable configuration
