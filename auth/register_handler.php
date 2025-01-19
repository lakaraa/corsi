<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include the PDO database connection
include('../config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $nome = trim($_POST['name']);
    $cognome = trim($_POST['surname']);
    $telefono = trim($_POST['tel']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the input data (basic validation)
    if (empty($nome) || empty($cognome) || empty($telefono) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required!";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        // Save the error message in session
        session_start();
        $_SESSION['passwordMismatchError'] = "Le password non corrispondono. Per favore, riprova.";
        header("Location: ../pages/register.php");
        exit;
    }

    // Password strength check: Minimum 8 characters, at least one number, and one special character
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[^\w\d]/", $password)) {
        // Store the password error and redirect back to register page
        session_start();
        $_SESSION['passwordError'] = "La password deve essere lunga almeno 8 caratteri e contenere lettere, numeri e caratteri speciali.";
        header("Location: ../pages/register.php");
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

        // Save the SQL query to the file
        $sqlQuery = sprintf(
            "INSERT INTO studente (Nome, Cognome, Telefono, Email, Password) VALUES ('%s', '%s', '%s', '%s', '%s');\n",
            $nome, $cognome, $telefono, $email, $hashedPassword
        );
        file_put_contents('../sql_insert.sql', $sqlQuery, FILE_APPEND);


        // Redirect to the login page with success message
        echo "<script>alert('Registrazione avvenuta con successo!'); window.location.href='../pages/login.php';</script>";

    } catch (PDOException $e) {
        // Handle errors
        echo "<script>alert('Errore: " . addslashes($e->getMessage()) . "'); window.location.href='../pages/register.php';</script>";
    }
} else {
    echo "Invalid request method!";
}
?>
