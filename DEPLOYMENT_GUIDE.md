# Deployment Guide - Jagoan Hosting

## Prerequisites
- Jagoan Hosting cPanel access
- PHP 8.1+ installed on server
- MySQL/MariaDB database access
- SSH access (optional but recommended)

## Step 1: Database Setup via phpMyAdmin

1. **Login to phpMyAdmin**
   - Access: `https://stride.jagoanhosting.id:2083/cpsess8737209947/3rdparty/phpMyAdmin/index.php`
   - Use your cPanel credentials

2. **Create Database**
   - Click on "Databases" tab
   - Enter database name (e.g., `aryafatt_hris_db`)
   - Click "Create"
   - Note down the database name

3. **Create Database User**
   - Go to "Users" tab
   - Click "Add user account"
   - Enter username (e.g., `aryafatt_hris_user`)
   - Generate strong password
   - Select "Local" for hostname
   - Click "Create user"

4. **Grant Privileges**
   - In "Database-specific privileges" section
   - Select the database you created
   - Check "Check All" for full privileges
   - Click "Go"

5. **Import Database Schema**
   - Select your database from the left sidebar
   - Click "Import" tab
   - Choose the SQL export file from your local development
   - Click "Go"

## Step 2: Upload Files to Jagoan Hosting

### Option A: Using File Manager
1. Login to cPanel
2. Navigate to "File Manager"
3. Go to `public_html` or your desired subdirectory
4. Upload the following files/folders:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `public/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `vendor/`
   - `.env.production` (rename to `.env`)
   - `artisan`
   - `composer.json`
   - `composer.lock`

### Option B: Using Git (Recommended)
```bash
# On your local machine
git init
git add .
git commit -m "Initial commit"

# Add remote repository
git remote add origin https://github.com/aryafatthurahman4-collab/hris-itk.git
git push -u origin main

# On server via SSH
cd public_html
git clone https://github.com/aryafatthurahman4-collab/hris-itk.git .
```

## Step 3: Configure Environment

1. **Update .env file**
   ```bash
   cd public_html
   cp .env.production .env
   nano .env
   ```

2. **Edit the following values:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=aryafatt_hris_db
   DB_USERNAME=aryafatt_hris_user
   DB_PASSWORD=your_strong_password
   
   APP_KEY=base64:your_generated_key
   ```

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

## Step 4: Install Dependencies

```bash
# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies (if needed)
npm install
npm run build
```

## Step 5: Set Permissions

```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# On some servers, you might need:
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## Step 6: Run Migrations and Seeders

```bash
# Run database migrations
php artisan migrate --force

# Run seeders (optional)
php artisan db:seed --force
```

## Step 7: Clear and Cache Configuration

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Step 8: Configure Web Server

### Apache (Default on Jagoan Hosting)
Ensure `.htaccess` exists in `public/` directory:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Set Document Root
In cPanel:
1. Go to "Domains"
2. Select your domain
3. Set document root to `public_html/public` (if using subdirectory)
4. Or keep `public_html` and ensure `index.php` is in the root

## Step 9: SSL Certificate (HTTPS)

1. In cPanel, go to "SSL/TLS Status"
2. Enable "AutoSSL" for your domain
3. Force HTTPS by adding to `.env`:
   ```env
   APP_URL=https://your-domain.com
   ```

## Step 10: Test the Application

1. Visit your domain: `https://your-domain.com`
2. Test login with demo credentials:
   - Admin: `admin@hr.com` / `password`
   - Atasan: `atasan@hr.com` / `password`
   - Karyawan: `karyawan@hr.com` / `password`

## Troubleshooting

### 500 Internal Server Error
- Check `.env` file permissions (should be 644)
- Verify `storage/` and `bootstrap/cache/` permissions (755)
- Check Laravel logs: `storage/logs/laravel.log`

### Database Connection Error
- Verify database credentials in `.env`
- Ensure database user has proper privileges
- Check if MySQL/MariaDB is running

### CSS/JS Not Loading
- Run `npm run build` on server
- Check `public/build/` directory exists
- Verify `APP_URL` in `.env` matches your domain

### File Upload Issues
- Check PHP upload limits in `php.ini`
- Verify `storage/app/public` permissions
- Create symbolic link: `php artisan storage:link`

## Maintenance Commands

### Backup Database
```bash
# Via SSH
mysqldump -u username -p database_name > backup.sql

# Via phpMyAdmin
- Select database
- Click "Export"
- Choose "Quick" export method
- Click "Go"
```

### Update Application
```bash
# Pull latest changes
git pull origin main

# Install new dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Monitor Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Error logs (cPanel)
tail -f ~/public_html/error_log
```

## Security Recommendations

1. **Disable Debug Mode**
   ```env
   APP_DEBUG=false
   ```

2. **Use Strong Passwords**
   - Database user password
   - Admin account password

3. **Enable HTTPS**
   - Use SSL certificate
   - Force HTTPS redirects

4. **Regular Backups**
   - Daily database backups
   - Weekly file backups

5. **Keep Dependencies Updated**
   ```bash
   composer update
   npm update
   ```

6. **File Permissions**
   - Never set 777 permissions
   - Use 755 for directories, 644 for files

## Contact Support

If you encounter issues:
- Jagoan Hosting Support: Check cPanel support options
- Laravel Documentation: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com/docs

## Additional Resources

- Laravel Deployment: https://laravel.com/docs/deployment
- phpMyAdmin Guide: https://docs.phpmyadmin.net/
- Jagoan Hosting Documentation: Check your hosting provider's knowledge base
