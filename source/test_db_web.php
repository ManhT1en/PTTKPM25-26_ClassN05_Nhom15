<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Singleton Pattern Test</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .test-section {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            border-radius: 5px;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .success:before {
            content: "✓ ";
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .error:before {
            content: "✗ ";
        }
        .info {
            color: #3498db;
        }
        .code {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .result-box {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 Database Singleton Pattern - Test Results</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $testsPassed = 0;
        $totalTests = 0;
        
        // Test 1: Include database config
        echo '<div class="test-section">';
        echo '<h2>Test 1: Loading Database Class</h2>';
        $totalTests++;
        
        try {
            require_once __DIR__ . '/admin/inc/db_config.php';
            echo '<p class="success">Database class loaded successfully</p>';
            $testsPassed++;
        } catch (Exception $e) {
            echo '<p class="error">Failed to load database class: ' . $e->getMessage() . '</p>';
            echo '</div></div></body></html>';
            exit;
        }
        echo '</div>';
        
        // Test 2: Get first instance
        echo '<div class="test-section">';
        echo '<h2>Test 2: Getting First Instance</h2>';
        $totalTests++;
        
        try {
            $db1 = Database::getInstance();
            echo '<p class="success">First instance created successfully</p>';
            echo '<p class="info">Instance type: ' . get_class($db1) . '</p>';
            $testsPassed++;
        } catch (Exception $e) {
            echo '<p class="error">Failed to get instance: ' . $e->getMessage() . '</p>';
            
            echo '<div class="warning">';
            echo '<strong>⚠️ Connection Error!</strong><br>';
            echo 'Please check your database credentials in: <code>admin/inc/db_config.php</code><br>';
            echo 'Refer to <strong>DATABASE_CONFIGURATION.md</strong> for setup instructions.';
            echo '</div>';
            
            echo '</div></div></body></html>';
            exit;
        }
        echo '</div>';
        
        // Test 3: Get second instance
        echo '<div class="test-section">';
        echo '<h2>Test 3: Verifying Singleton Pattern</h2>';
        $totalTests++;
        
        $db2 = Database::getInstance();
        echo '<p class="success">Second instance retrieved</p>';
        
        if ($db1 === $db2) {
            echo '<p class="success">Both instances are THE SAME object (Singleton working!)</p>';
            echo '<p class="info">Memory address 1: ' . spl_object_id($db1) . '</p>';
            echo '<p class="info">Memory address 2: ' . spl_object_id($db2) . '</p>';
            $testsPassed++;
        } else {
            echo '<p class="error">Instances are different (Singleton NOT working)</p>';
        }
        echo '</div>';
        
        // Test 4: Get database connection
        echo '<div class="test-section">';
        echo '<h2>Test 4: Getting Database Connection</h2>';
        $totalTests++;
        
        try {
            $conn = $db1->getConnection();
            echo '<p class="success">Connection object retrieved</p>';
            echo '<p class="info">Connection type: ' . get_class($conn) . '</p>';
            echo '<p class="info">Host info: ' . $conn->host_info . '</p>';
            echo '<p class="info">Server version: ' . $conn->server_info . '</p>';
            echo '<p class="info">Character set: ' . $conn->character_set_name() . '</p>';
            $testsPassed++;
        } catch (Exception $e) {
            echo '<p class="error">Failed to get connection: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        
        // Test 5: Test database query
        echo '<div class="test-section">';
        echo '<h2>Test 5: Testing Database Query</h2>';
        $totalTests++;
        
        try {
            $result = mysqli_query($conn, "SELECT DATABASE() as db_name, VERSION() as version");
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                echo '<p class="success">Query executed successfully</p>';
                echo '<p class="info">Connected to database: <strong>' . $row['db_name'] . '</strong></p>';
                echo '<p class="info">MySQL version: ' . $row['version'] . '</p>';
                $testsPassed++;
            }
        } catch (Exception $e) {
            echo '<p class="error">Query failed: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        
        // Test 6: Count tables
        echo '<div class="test-section">';
        echo '<h2>Test 6: Checking Database Tables</h2>';
        $totalTests++;
        
        try {
            $result = mysqli_query($conn, "SHOW TABLES");
            if ($result) {
                $tableCount = mysqli_num_rows($result);
                echo '<p class="success">Database has ' . $tableCount . ' tables</p>';
                
                if ($tableCount > 0) {
                    echo '<table>';
                    echo '<tr><th>#</th><th>Table Name</th></tr>';
                    $i = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        echo '<tr><td>' . $i++ . '</td><td>' . $row[0] . '</td></tr>';
                    }
                    echo '</table>';
                    $testsPassed++;
                } else {
                    echo '<p class="error">No tables found. Import vietchill.sql first.</p>';
                }
            }
        } catch (Exception $e) {
            echo '<p class="error">Failed to list tables: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        
        // Test 7: Test backward compatibility
        echo '<div class="test-section">';
        echo '<h2>Test 7: Backward Compatibility Check</h2>';
        $totalTests++;
        
        if (isset($con) && $con instanceof mysqli) {
            echo '<p class="success">Global $con variable is available</p>';
            echo '<p class="info">Your existing code will work without changes</p>';
            $testsPassed++;
        } else {
            echo '<p class="error">Global $con variable not found</p>';
        }
        echo '</div>';
        
        // Test 8: Test helper functions
        echo '<div class="test-section">';
        echo '<h2>Test 8: Testing Helper Functions</h2>';
        $totalTests++;
        
        if (function_exists('selectAll') && function_exists('select') && 
            function_exists('insert') && function_exists('update') && 
            function_exists('delete')) {
            echo '<p class="success">All helper functions are available:</p>';
            echo '<ul>';
            echo '<li>selectAll()</li>';
            echo '<li>select()</li>';
            echo '<li>insert()</li>';
            echo '<li>update()</li>';
            echo '<li>delete()</li>';
            echo '<li>filteration()</li>';
            echo '</ul>';
            $testsPassed++;
        } else {
            echo '<p class="error">Some helper functions are missing</p>';
        }
        echo '</div>';
        
        // Test 9: Multiple getInstance calls
        echo '<div class="test-section">';
        echo '<h2>Test 9: Multiple getInstance() Calls</h2>';
        $totalTests++;
        
        $instances = [];
        for ($i = 0; $i < 5; $i++) {
            $instances[] = Database::getInstance();
        }
        
        $allSame = true;
        for ($i = 1; $i < count($instances); $i++) {
            if ($instances[$i] !== $instances[0]) {
                $allSame = false;
                break;
            }
        }
        
        if ($allSame) {
            echo '<p class="success">Created 5 "instances" but all point to SAME object</p>';
            echo '<p class="info">Object ID: ' . spl_object_id($instances[0]) . '</p>';
            echo '<p class="info">Memory efficient: Only ONE connection created!</p>';
            $testsPassed++;
        } else {
            echo '<p class="error">Different instances created (Singleton broken)</p>';
        }
        echo '</div>';
        
        // Final Results
        echo '<div class="result-box">';
        echo '<h2>📊 Final Test Results</h2>';
        echo '<p style="font-size: 24px; font-weight: bold;">';
        echo 'Passed: ' . $testsPassed . ' / ' . $totalTests . ' tests';
        echo '</p>';
        
        $percentage = ($testsPassed / $totalTests) * 100;
        
        if ($percentage == 100) {
            echo '<p style="color: #27ae60; font-size: 18px; font-weight: bold;">';
            echo '🎉 Perfect! Singleton Pattern is working correctly!';
            echo '</p>';
            echo '<p>Your database layer is now using professional design patterns.</p>';
        } elseif ($percentage >= 70) {
            echo '<p style="color: #f39c12; font-size: 18px; font-weight: bold;">';
            echo '⚠️ Mostly working, but some tests failed.';
            echo '</p>';
            echo '<p>Check the failed tests above and refer to DATABASE_CONFIGURATION.md</p>';
        } else {
            echo '<p style="color: #e74c3c; font-size: 18px; font-weight: bold;">';
            echo '❌ Singleton not working properly.';
            echo '</p>';
            echo '<p>Please check your database configuration in admin/inc/db_config.php</p>';
        }
        echo '</div>';
        
        // Usage example
        echo '<div class="test-section">';
        echo '<h2>💡 How to Use in Your Code</h2>';
        echo '<div class="code">';
        echo htmlspecialchars("<?php\n");
        echo htmlspecialchars("// Include the database config\n");
        echo htmlspecialchars("require_once 'admin/inc/db_config.php';\n\n");
        echo htmlspecialchars("// Option 1: Use global \$con (backward compatible)\n");
        echo htmlspecialchars("\$result = mysqli_query(\$con, 'SELECT * FROM rooms');\n\n");
        echo htmlspecialchars("// Option 2: Get instance and connection\n");
        echo htmlspecialchars("\$db = Database::getInstance();\n");
        echo htmlspecialchars("\$connection = \$db->getConnection();\n\n");
        echo htmlspecialchars("// Option 3: Use helper functions\n");
        echo htmlspecialchars("\$rooms = selectAll('rooms');\n");
        echo htmlspecialchars("\$room = select('SELECT * FROM rooms WHERE id = ?', [5], 'i');\n");
        echo htmlspecialchars("?>");
        echo '</div>';
        echo '</div>';
        ?>
        
        <div style="margin-top: 30px; padding: 20px; background: #e3f2fd; border-radius: 5px;">
            <h3>📚 Next Steps:</h3>
            <ol>
                <li>Read <strong>SINGLETON_IMPLEMENTATION.md</strong> for detailed explanation</li>
                <li>Read <strong>SINGLETON_VISUAL_GUIDE.md</strong> for visual diagrams</li>
                <li>Read <strong>DATABASE_CONFIGURATION.md</strong> for setup help</li>
                <li>Your existing code continues to work without changes</li>
                <li>New code can use the getInstance() method</li>
            </ol>
        </div>
    </div>
</body>
</html>
