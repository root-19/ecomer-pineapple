<?php
class Account {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch user data by user ID
    public function getUserData($userId) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user data
    public function updateUserData($userId, $data) {
        $query = "UPDATE users SET 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email, 
            phone_number = :phone_number, 
            province = :province, 
            city = :city, 
            baranggay = :baranggay, 
            municipality = :municipality, 
            street_address = :street_address, 
            zip_code = :zip_code,
            con_password = :con_password
            WHERE id = :id";
            
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone_number', $data['phone_number']);
        $stmt->bindParam(':province', $data['province']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':baranggay', $data['baranggay']);
        $stmt->bindParam(':municipality', $data['municipality']);
        $stmt->bindParam(':street_address', $data['street_address']);
        $stmt->bindParam(':zip_code', $data['zip_code']);
        $stmt->bindParam(':con_password', $data['con_password']);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Verify user password
    public function verifyPassword($userId, $password) {
     
        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Use password_verify to check if the provided password matches the stored hash
            return password_verify($password, $user['password']);
        }

        return false;
    }
}
?>
