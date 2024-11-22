<?php
include "../../Controller/Database/Database.php";

class Admin {
    private $db;

    public function __construct() {
        $database = new Database(); 
        $this->db = $database->connect(); 
    }

    public function addProduct(Product $product) {
        // Prepare the statement using PDO
        $stmt = $this->db->prepare("INSERT INTO products (image, name, price, type, quantity, date) VALUES (?, ?, ?, ?, ?, ?)");

        // Bind the values using bindValue(), with unique index for each placeholder
        $stmt->bindValue(1, $product->image);
        $stmt->bindValue(2, $product->name);
        $stmt->bindValue(3, $product->price); // This should bind to the price, not type
        $stmt->bindValue(4, $product->type);  // Bind type to index 4
        $stmt->bindValue(5, $product->quantity, PDO::PARAM_INT);
        $stmt->bindValue(6, $product->date);

        // Execute the statement
        if ($stmt->execute()) {
            return true; 
        } else {
            return false;
        }
    }

    public function __destruct() {
        $this->db = null; 
    }
}
?>