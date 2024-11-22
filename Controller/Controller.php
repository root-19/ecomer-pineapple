<?php
session_start();
require_once '../Controller/Database/Database.php'; // Database connection file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "../vendor/autoload.php";

$message = '';

include './Validation.php'; 
$database = new Database(); 
$conn = $database->connect(); 
$validation = new Validation($conn); 

// Handle user registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'register') {
            // Registration logic
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $age = $_POST['age'];
            $email = $_POST['email'];
            $password = $_POST['password']; 
            $con_password = $_POST['con_password'];
            $phone_number = $_POST['phone_number'];
            $province = $_POST['province'];
            $city = $_POST['city'];
            $baranggay = $_POST['baranggay'];
            $municipality = $_POST['municipality'];
            $street_address = $_POST['str_hno_floor_unit'];
            $zip_code = $_POST['zip_code'];
            $user_image = $_POST['user_image'];

            // Handle image upload
            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['user_image']['tmp_name'];
                $file_name = basename($_FILES['user_image']['name']);
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
                // Validate file type
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($file_ext, $allowed_extensions)) {
                    $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
                    header("Location: ../public/signup.php");
                    exit();
                }
        
                $upload_dir = '../Views/uploads/';
                $new_file_name = uniqid() . '.' . $file_ext;
        
                // Check if upload directory exists and is writable
                if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
                    $_SESSION['error'] = "Upload directory does not exist or is not writable.";
                    header("Location: ../public/signup.php");
                    exit();
                }
        
                if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                    $user_image = $new_file_name; 
                } else {
                    $_SESSION['error'] = "Failed to upload the image.";
                    header("Location: ../public/signup.php");
                    exit();
                }
            } else {
                // Handle case where no image is uploaded
                $user_image = 'default_image.png'; // Set a default image if none is uploaded
            }

            try {
                // Generate verification code once
                $verification_code = $validation->generateVerificationCode();

                // Register user with verification code saved in the database
                $user_id = $validation->registration(
                    $first_name, $last_name, $email, $password, $con_password, 
                    $phone_number, $province, $city, $baranggay, $municipality, 
                    $street_address, $zip_code, $user_image, 'user', $verification_code, $age
                );

                // Send verification email with the same code
                sendVerificationEmail($email, $verification_code);

                // Store user ID in session
                $_SESSION['user_id'] = $user_id;
                header("Location: ../public/verify.php"); // Redirect to verification page
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Registration failed: " . $e->getMessage();
                header("Location: ../public/signup.php");
                exit();
            }
        } elseif ($_POST['action'] === 'login') {
            // Login logic
            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                // Retrieve the user by email
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    if ($user['verify_code'] == NULL) {
                        // Store user information in session
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['city'] = $user['city'];  // Add this
                        $_SESSION['province'] = $user['province'];  // Add this
                        $_SESSION['role'] = $user['role'];
                        // Redirect based on the user's role
                        if ($user['role'] === 'admin') {
                            header("Location: ../Views/admin/overview.php"); 
                        } elseif ($user['role'] === 'rider') {
                            header("Location: ../Views/rider/index.php");  
                            header("Location: ../Views/user/index.php"); 
                        }
                        exit();
                    } else {
                        $_SESSION['error'] = "Please verify your account.";
                        header("Location: ../public/signin.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Invalid email or password.";
                    header("Location: ../public/signin.php");
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Login failed: " . $e->getMessage();
                header("Location: ../public/signin.php");
                exit();
            }
        }
    }
}

// Function to send verification email
function sendVerificationEmail($email, $code) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hperformanceexhaust@gmail.com'; // Replace with your SMTP email
        $mail->Password   = 'wolv wvyy chhl rvvm';  // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('hperformanceexhaust@gmail.com', 'Your Site Name');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Verification';
        $mail->Body    = "Your verification code is: <strong>$code</strong>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>

