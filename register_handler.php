<?php
// Include the PDO database connection
require_once 'config.php'; // Ensure this is the correct path to your config.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $nome = $_POST['name'];
    $cognome = $_POST['surname'];
    $telefono = $_POST['tel'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the input data (basic validation)
    if (empty($nome) || empty($cognome) || empty($telefono) || empty($email) || empty($password)) {
        echo "All fields are required!";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Hash the password using password_hash() function
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare the SQL query to insert the data into the `studente` table
        $query = "INSERT INTO studente (Nome, Cognome, Telefono, Email, Password) 
                  VALUES (:nome, :cognome, :telefono, :email, :password)";

        // Prepare the statement
        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword); // Use the hashed password

        // Execute the statement
        $stmt->execute();

        // Redirect to the login page with success message
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";

    } catch (PDOException $e) {
        // Handle errors
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='register.php';</script>";
    }
} else {
    echo "Invalid request method!";
}
?>