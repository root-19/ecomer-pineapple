<?php

class Validation {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registration($first_name, $last_name, $email, $password, $con_password, 
                                $phone_number, $province, $city, $baranggay, 
                                $municipality, $street_address, $zip_code, $user_image, 
                                $role = 'user', $verify_code, $age ) {
        
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || 
            empty($con_password) || empty($phone_number) || empty($province) || 
            empty($city) || empty($baranggay) || empty($municipality) || 
            empty($street_address) || empty($zip_code) || empty($user_image) || empty($age))  {
            throw new Exception("Please fill all fields.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if ($age < 18) {
            // Set session variable for error message
            $_SESSION['age_error'] = "You must be at least 18 years old to register.";
            // Redirect to the signup page
            header("location: ../public/signup.php");
            exit();
        }
      
        if ($password !== $con_password) {
            throw new Exception("Passwords do not match.");
        }

        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name, last_name, age, email, password, con_password,
                phone_number, province, city, baranggay, municipality, 
                street_address, zip_code, user_image, role, verify_code) 
                VALUES (:first_name, :last_name, :age, :email, :password, :con_password,
                :phone_number, :province, :city, :baranggay, 
                :municipality, :street_address, :zip_code, 
                :user_image, :role, :verify_code)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);
        $stmt->bindParam(':con_password', $con_password);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':province', $province);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':baranggay', $baranggay);
        $stmt->bindParam(':municipality', $municipality);
        $stmt->bindParam(':street_address', $street_address);
        $stmt->bindParam(':zip_code', $zip_code);
        $stmt->bindParam(':user_image', $user_image);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':verify_code', $verify_code);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            throw new Exception("Insert failed: " . implode(", ", $stmt->errorInfo()));
        }
    }

    public function generateVerificationCode() {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}