# Database Setup Guide - HRIS ITK

## Jagoan Hosting Database Setup

### Step 1: Access phpMyAdmin on Jagoan Hosting

Login to phpMyAdmin:
```
https://stride.jagoanhosting.id:2083/cpsess8737209947/3rdparty/phpMyAdmin/index.php
```

### Step 2: Create Database for HRIS

1. **Create New Database**
   - Click on "Databases" tab at the top
   - Enter database name: `aryafatt_hris_db`
   - Click "Create" button
   - Note: The database will be prefixed with your username (e.g., `aryafatt_aryafatt_hris_db`)

2. **Create Database User**
   - Go to "Users" tab
   - Click "Add user account"
   - Username: `aryafatt_hris_user`
   - Hostname: Select "Local" (localhost)
   - Password: Generate a strong password (save this!)
   - Click "Create user"

3. **Grant Privileges**
   - After creating user, go to "Database-specific privileges"
   - Select the database you created (`aryafatt_hris_db`)
   - Check "Check All" for full privileges
   - Click "Go"

### Step 3: Import Database Schema

1. **Export from Local (Optional)**
   - If you have existing data in local database:
     - Open local phpMyAdmin: `http://localhost/phpmyadmin/`
     - Select database `hr_management`
     - Click "Export" tab
     - Choose "Quick" export method
     - Format: SQL
     - Click "Go"

2. **Import to Jagoan Hosting**
   - In Jagoan Hosting phpMyAdmin
   - Select your new database
   - Click "Import" tab
   - Choose the SQL file from your local export
   - Click "Go"

### Step 4: Update .env for Production

Edit `.env` file on Jagoan Hosting:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=aryafatt_hris_db
DB_USERNAME=aryafatt_hris_user
DB_PASSWORD=your_generated_password
```

## Local Laragon phpMyAdmin Configuration

### Access Local phpMyAdmin

Laragon typically runs phpMyAdmin at one of these URLs:
- `http://localhost/phpmyadmin/`
- `http://localhost:8080/phpmyadmin/`

### Configure Local Database

1. **Open Laragon**
   - Start Laragon
   - Click "Menu" > "phpMyAdmin"

2. **Create Local Database**
   - In phpMyAdmin, click "Databases"
   - Create database: `hr_management`
   - Click "Create"

3. **Update Local .env**
   - Edit `.env` file in your project root
   - Ensure database configuration matches:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hr_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### Laragon MySQL Configuration

If phpMyAdmin is not accessible, check Laragon configuration:

1. **Check MySQL Service**
   - Open Laragon
   - Ensure MySQL is running (green indicator)
   - Click "Menu" > "MySQL" > "Start All"

2. **Reset MySQL Root Password (if needed)**
   - Click "Menu" > "MySQL" > "Reset root password"
   - Default password is usually empty

3. **phpMyAdmin Configuration**
   - Laragon phpMyAdmin config is in:
     `C:\laragon\etc\phpMyAdmin\config.inc.php`
   - Default credentials:
     - Username: `root`
     - Password: (empty)

## Database Migration

### Run Migrations on Local

```bash
cd c:\laragon\www\HRIS-ITK-IJK
php artisan migrate
php artisan db:seed
```

### Run Migrations on Jagoan Hosting

```bash
# Via SSH or cPanel Terminal
cd public_html
php artisan migrate --force
php artisan db:seed --force
```

## Troubleshooting

### phpMyAdmin Access Issues

**Local phpMyAdmin not accessible:**
1. Check if Laragon MySQL is running
2. Try `http://localhost:8080/phpmyadmin/` instead of `http://localhost/phpmyadmin/`
3. Check firewall settings
4. Restart Laragon completely

**Jagoan Hosting phpMyAdmin not accessible:**
1. Clear browser cache
2. Try incognito/private mode
3. Check if cPanel session is still active
4. Contact Jagoan Hosting support

### Database Connection Errors

**Local:**
- Ensure MySQL service is running in Laragon
- Check `.env` database credentials
- Verify database name exists in phpMyAdmin

**Jagoan Hosting:**
- Verify database user has proper privileges
- Check database name includes correct prefix
- Ensure password is correct in `.env`

### Import Errors

**File too large:**
- Check phpMyAdmin upload limits
- Split large SQL files into smaller chunks
- Use SSH command line for large imports:
  ```bash
  mysql -u username -p database_name < backup.sql
  ```

**Character encoding issues:**
- Ensure SQL file uses UTF-8 encoding
- Check collation settings in phpMyAdmin

## Database Backup & Restore

### Backup Local Database
```bash
# Via command line
mysqldump -u root hr_management > backup.sql

# Via phpMyAdmin
- Select database
- Click "Export"
- Choose "Quick" export
- Download SQL file
```

### Restore to Jagoan Hosting
```bash
# Via SSH
mysql -u aryafatt_hris_user -p aryafatt_hris_db < backup.sql

# Via phpMyAdmin
- Select database
- Click "Import"
- Choose SQL file
- Click "Go"
```

## Security Best Practices

1. **Strong Passwords**
   - Use complex passwords for database users
   - Never use default passwords in production

2. **Limited Privileges**
   - Grant only necessary privileges to database users
   - Avoid using root user in production

3. **Regular Backups**
   - Schedule automatic database backups
   - Store backups in secure location

4. **SSL Connections**
   - Use SSL for database connections if available
   - Encrypt sensitive data at rest

## Contact Information

- **Jagoan Hosting Support**: Check cPanel support options
- **Laragon Documentation**: https://laragon.org/docs
- **phpMyAdmin Documentation**: https://docs.phpmyadmin.net/
