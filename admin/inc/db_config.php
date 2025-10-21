<?php

/**
 * Database Singleton Pattern Implementation
 * 
 * This class ensures only one database connection exists throughout the application.
 * It provides a global point of access to the database connection and includes
 * helper methods for common database operations.
 * 
 * @author VietChill Team
 * @version 2.0
 */
class Database {
    // Hold the single instance of the class
    private static ?Database $instance = null;
    
    // Database connection object
    private ?mysqli $connection = null;
    
    // Database configuration
    private string $host = '127.0.0.1:3306';
    private string $username = 'root';
    private string $password = 'NewStrongPass!';
    private string $database = 'vietchill';

    /**
     * Private constructor to prevent direct instantiation
     * This is the key to Singleton pattern - constructor must be private
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}

    /**
     * Prevent unserialization of the instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * Get the single instance of Database (Singleton Pattern)
     * 
     * @return Database The single instance
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     * 
     * @throws Exception if connection fails
     */
    private function connect(): void {
        $this->connection = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_error) {
            die("Cannot Connect to Database: " . $this->connection->connect_error);
        }

        // Set charset to UTF-8 for proper character handling
        $this->connection->set_charset("utf8mb4");
    }

    /**
     * Get the MySQL connection object
     * 
     * @return mysqli The database connection
     */
    public function getConnection(): mysqli {
        // Check if connection is still alive, reconnect if needed
        if (!$this->connection->ping()) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Close the database connection
     */
    public function close(): void {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    /**
     * Destructor - close connection when object is destroyed
     */
    public function __destruct() {
        $this->close();
    }
}

// Get the singleton instance and connection for backward compatibility
$db = Database::getInstance();
$con = $db->getConnection();

  function filteration($data) {
    foreach($data as $key => $value){
      $value = trim($value);
      $value = htmlspecialchars($value);
      $value = stripslashes($value);
      $value = strip_tags($value);
      $data[$key] = $value;
    }
    return $data;
  }
  
  function selectAll($table) {
    $con = $GLOBALS['con'];
    $res = mysqli_query($con, "SELECT * FROM $table");
    return $res;
  }
  
  function select($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con, $sql)){
      mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Execute");
      }
    }
    else{
      die("Query cannot be executed - Select");
    }
  }

  function update($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con, $sql)){
      mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Update");
      }
    }
    else{
      die("Query cannot be executed - Update");
    }
  }

  function insert($sql,$values,$datatypes) {
		$con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con, $sql)){
      mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Insert");
      }
    }
    else{
      die("Query cannot be executed - Insert");
    }
	}

  function delete($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con, $sql)){
      mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Delete");
      }
    }
    else{
      die("Query cannot be executed - Delete");
    }
  }
?>