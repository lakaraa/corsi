<?php
// Include the PDO database connection
require_once 'config.php'; // Make sure the path to config.php is correct

// Start the session to track the user after login
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the input data
    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required!'); window.location.href='login.php';</script>";
        exit;
    }

    try {
        // Prepare the query to select the user by email
        $query = "SELECT * FROM studente WHERE Email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if the user exists
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password (using password_verify to compare the hashed password)
            if (password_verify($password, $user['Password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['IdStudente'];  // Assuming the user table has an 'ID' column
                $_SESSION['user_email'] = $user['Email'];
                $_SESSION['user_name'] = $user['Nome'];  // Assuming the user's name is stored in the 'Nome' column
                
                // Redirect to the user's dashboard or home page
                echo "<script>alert('Login successful!'); window.location.href='index.php';</script>";
                exit;
            } else {
                // Invalid password
                echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
                exit;
            }
        } else {
            // No user found with this email
            echo "<script>alert('No user found with that email!'); window.location.href='login.php';</script>";
            exit;
        }

    } catch (PDOException $e) {
        // Handle any error that occurs during the query execution
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='login.php';</script>";
        exit;
    }
} else {
    // If the form was not submitted, redirect to login page
    header('Location: login.php');
    exit;
}
?>
