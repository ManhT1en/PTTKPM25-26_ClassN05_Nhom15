# Database Configuration Guide

## 🔧 Setting Up Your Database Credentials

The Singleton pattern is implemented correctly, but you need to configure your database credentials.

## 📝 Configuration Steps

### Step 1: Update Database Credentials

Edit the file: `admin/inc/db_config.php`

Find these lines around line 24:
```php
private string $host = '127.0.0.1:3306';
private string $username = 'root';
private string $password = '';           // ← Update this
private string $database = 'doancoso';   // ← Update this if needed
```

### Step 2: Set Your Credentials

Replace with your actual database credentials:

```php
private string $host = '127.0.0.1:3306';     // Usually localhost or 127.0.0.1
private string $username = 'root';            // Your MySQL username
private string $password = 'your_password';   // Your MySQL password
private string $database = 'doancoso';        // Your database name
```

## 🔍 Common Configurations

### For WAMP (Default):
```php
private string $host = 'localhost';
private string $username = 'root';
private string $password = '';           // Usually empty for WAMP
private string $database = 'doancoso';
```

### For XAMPP (Default):
```php
private string $host = 'localhost';
private string $username = 'root';
private string $password = '';           // Usually empty for XAMPP
private string $database = 'doancoso';
```

### For Production Server:
```php
private string $host = 'localhost';
private string $username = 'your_db_user';
private string $password = 'strong_password_here';
private string $database = 'doancoso';
```

## ✅ Testing Your Configuration

### Option 1: Test in Browser

Create a file: `test_db_connection.php` in your root directory:

```php
<?php
require_once 'admin/inc/db_config.php';

echo "<h2>Database Connection Test</h2>";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    echo "<p style='color: green;'>✓ Connection successful!</p>";
    echo "<p>Host: " . $conn->host_info . "</p>";
    echo "<p>Server version: " . $conn->server_info . "</p>";
    
    // Test query
    $result = mysqli_query($conn, "SELECT DATABASE() as db_name");
    $row = mysqli_fetch_assoc($result);
    echo "<p>Connected to database: <strong>" . $row['db_name'] . "</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
}
?>
```

Then visit: `http://localhost/DoAnCoSo/test_db_connection.php`

### Option 2: Test in Command Line

```bash
php test_db_connection.php
```

## 🛠️ Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"

**Possible causes:**
1. Wrong password
2. MySQL user doesn't exist
3. MySQL server not running

**Solutions:**

#### Solution 1: Check if MySQL is running
- For WAMP: Check if the WAMP icon is green
- For XAMPP: Start MySQL from the control panel

#### Solution 2: Reset MySQL root password

**For WAMP:**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Go to User Accounts
3. Click "Edit privileges" for root@localhost
4. Click "Change password"
5. Set your password or leave empty
6. Update `db_config.php` with the same password

**For XAMPP:**
1. Open XAMPP Control Panel
2. Click "Shell"
3. Type: `mysqladmin -u root password "newpassword"`
4. Update `db_config.php` with the new password

#### Solution 3: Create new MySQL user

```sql
-- Login to MySQL as root
mysql -u root -p

-- Create new user
CREATE USER 'vietchill_user'@'localhost' IDENTIFIED BY 'strong_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON doancoso.* TO 'vietchill_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;
```

Then update `db_config.php`:
```php
private string $username = 'vietchill_user';
private string $password = 'strong_password';
```

### Error: "Unknown database 'doancoso'"

**Solution:** Create the database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" in the left sidebar
3. Database name: `doancoso`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

Or via command line:
```sql
mysql -u root -p
CREATE DATABASE doancoso CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Then import your SQL file:
```bash
mysql -u root -p doancoso < vietchill.sql
```

### Error: "Can't connect to MySQL server"

**Solutions:**
1. Start MySQL server (WAMP/XAMPP)
2. Check if port 3306 is correct
3. Try using `localhost` instead of `127.0.0.1`
4. Check Windows Firewall settings

## 📋 Quick Setup Checklist

- [ ] MySQL server is running (WAMP/XAMPP)
- [ ] Database `doancoso` exists
- [ ] Tables imported from `vietchill.sql`
- [ ] Credentials updated in `db_config.php`
- [ ] Test connection successful
- [ ] Website loads without errors

## 🎯 Verification

Once configured correctly, you should see:

```
✓ Connection successful!
✓ Connected to database: doancoso
✓ All existing functions work
✓ No errors in application
```

## 📞 Need More Help?

If you're still having issues:

1. Check your WAMP/XAMPP is running
2. Verify MySQL service is active
3. Try accessing phpMyAdmin
4. Check error logs in `C:\wamp64\logs\` or `C:\xampp\mysql\data\`

---

**Once your credentials are configured, the Singleton pattern will work perfectly!** 🎉
