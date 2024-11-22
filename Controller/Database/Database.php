<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host = "localhost";      
        $this->db_name = "pineapple_db"; 
        $this->username = "root";        
        $this->password = "";             
    }

    public function connect() {
        $this->conn = null;

        try {
            // Attempt to establish a PDO connection
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Log the error message
            error_log("Database connection error: " . $exception->getMessage());
            // Provide a user-friendly message
            echo "Could not connect to the database. Please try again later.";
            exit(); // Terminate the script
        }

        return $this->conn; // Return the connection object
    }
}
