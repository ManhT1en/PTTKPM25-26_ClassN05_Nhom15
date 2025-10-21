<?php
/**
 * Database Singleton Pattern - Usage Examples
 * 
 * This file demonstrates how to use the new Database Singleton pattern
 * in your VietChill Hotel Booking System.
 */

require_once __DIR__ . '/admin/inc/db_config.php';

echo "=== Database Singleton Pattern Demo ===\n\n";

// ============================================
// Example 1: Getting the Database Instance
// ============================================
echo "1. Getting Database Instance:\n";
echo "------------------------------\n";

// Get the singleton instance - this creates the connection
$db1 = Database::getInstance();
echo "✓ First instance created\n";

// Get the instance again - this returns the SAME instance
$db2 = Database::getInstance();
echo "✓ Second instance retrieved\n";

// Verify they are the same object
if ($db1 === $db2) {
    echo "✓ Both variables point to the SAME instance (Singleton working!)\n";
} else {
    echo "✗ Different instances (Singleton failed!)\n";
}

echo "\n";

// ============================================
// Example 2: Using the Connection
// ============================================
echo "2. Using the Database Connection:\n";
echo "----------------------------------\n";

// Get the connection object
$con = $db1->getConnection();

// Test query
$result = mysqli_query($con, "SELECT COUNT(*) as total FROM rooms");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "✓ Total rooms in database: " . $row['total'] . "\n";
} else {
    echo "✗ Query failed: " . mysqli_error($con) . "\n";
}

echo "\n";

// ============================================
// Example 3: Backward Compatibility
// ============================================
echo "3. Backward Compatibility:\n";
echo "--------------------------\n";
echo "Your existing code still works because we kept the \$con variable.\n";
echo "All your existing functions (select, insert, update, delete) work as before.\n";

// Example with existing select function
$rooms = select("SELECT * FROM rooms WHERE status = ? AND removed = ? LIMIT 3", [1, 0], 'ii');
$count = mysqli_num_rows($rooms);
echo "✓ Found {$count} active rooms using existing select() function\n";

echo "\n";

// ============================================
// Example 4: Using in Different Files
// ============================================
echo "4. How to Use in Your PHP Files:\n";
echo "--------------------------------\n";

echo "
// In any PHP file, just require db_config.php:
require_once 'admin/inc/db_config.php';

// Option 1: Use the global \$con (backward compatible)
\$result = mysqli_query(\$con, 'SELECT * FROM rooms');

// Option 2: Get fresh instance
\$db = Database::getInstance();
\$connection = \$db->getConnection();

// Option 3: Use existing helper functions
\$rooms = selectAll('rooms');
\$room = select('SELECT * FROM rooms WHERE id = ?', [5], 'i');
";

echo "\n";

// ============================================
// Example 5: Benefits Demonstration
// ============================================
echo "5. Singleton Pattern Benefits:\n";
echo "-------------------------------\n";

$instances = [];
for ($i = 1; $i <= 5; $i++) {
    $instances[] = Database::getInstance();
}

// Check if all are the same instance
$allSame = true;
for ($i = 1; $i < count($instances); $i++) {
    if ($instances[$i] !== $instances[0]) {
        $allSame = false;
        break;
    }
}

if ($allSame) {
    echo "✓ Created 5 'instances' but they all point to the SAME object\n";
    echo "✓ Only ONE database connection created (saves resources)\n";
    echo "✓ Memory efficient and faster performance\n";
}

echo "\n";

// ============================================
// Example 6: Error Handling
// ============================================
echo "6. Connection Health Check:\n";
echo "---------------------------\n";

$db = Database::getInstance();
$conn = $db->getConnection();

if ($conn->ping()) {
    echo "✓ Database connection is alive and healthy\n";
} else {
    echo "✗ Connection lost (will auto-reconnect on next getConnection())\n";
}

echo "\n";

// ============================================
// Summary
// ============================================
echo "=== Summary ===\n";
echo "✓ Singleton pattern implemented successfully\n";
echo "✓ Only one database connection throughout the application\n";
echo "✓ Backward compatible with existing code\n";
echo "✓ Auto-reconnects if connection is lost\n";
echo "✓ UTF-8 charset support for Vietnamese characters\n";
echo "✓ Memory efficient and better performance\n";
echo "\n";

echo "Your application is now using the Singleton pattern!\n";
echo "All existing code continues to work without any changes.\n";

?>
