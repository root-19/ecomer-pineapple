<?php

class Cart {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    public function addToCart($userId, $productId, $quantity, $firstName, $lastName) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, name, image, price, type, quantity, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$userId, $product['id'], $product['name'], $product['image'], $product['price'], $product['type'], $quantity, $firstName, $lastName]);
        }

        return false; 
    }
}    