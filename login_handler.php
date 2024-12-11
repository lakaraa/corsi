<?php
// Include la connessione al database
require_once 'config.php'; // Assicurati che il percorso sia corretto

// Avvia la sessione
session_start();

// Verifica se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera i dati del form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validazione dei dati
    if (empty($email) || empty($password)) {
        echo "<script>alert('Entrambi i campi sono obbligatori!'); window.location.href='login.php';</script>";
        exit;
    }

    try {
        // Verifica l'email nell'amministratore
        $query = "SELECT * FROM amministratore WHERE Email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica l'email nell'istruttore
        $query = "SELECT * FROM istruttore WHERE Email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $instr = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica l'email nello studente
        $query = "SELECT * FROM studente WHERE Email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se l'utente è stato trovato in una delle tabelle
        if ($admin) {
            // Se l'utente è un amministratore
            if (password_verify($password, $admin['Password'])) {  // Verifica la password hashata
                $_SESSION['user_id'] = $admin['IdAmministratore'];
                $_SESSION['user_email'] = $admin['Email'];
                $_SESSION['user_name'] = $admin['Nome'];
                $_SESSION['user_role'] = 'admin';
                // Redirige l'amministratore al suo dashboard
                header("Location: amministratoreDashboard.php");
                exit;
            } else {
                echo "<script>alert('Password errata per l\'amministratore!'); window.location.href='login.php';</script>";
                exit;
            }
        } elseif ($instr) {
            // Se l'utente è un istruttore
            if (password_verify($password, $instr['Password'])) {  // Verifica la password hashata
                $_SESSION['user_id'] = $instr['IdIstruttore'];
                $_SESSION['user_email'] = $instr['Email'];
                $_SESSION['user_name'] = $instr['Nome'];
                $_SESSION['user_role'] = 'istruttore';
                // Redirige l'istruttore al suo dashboard
                header("Location: istruttoreDashboard.php");
                exit;
            } else {
                echo "<script>alert('Password errata per l\'istruttore!'); window.location.href='login.php';</script>";
                exit;
            }
        } elseif ($student) {
            // Se l'utente è uno studente
            if (password_verify($password, $student['Password'])) {  // Verifica la password hashata
                $_SESSION['user_id'] = $student['IdStudente'];
                $_SESSION['user_email'] = $student['Email'];
                $_SESSION['user_name'] = $student['Nome'];
                $_SESSION['user_role'] = 'studente';
                // Redirige lo studente al suo dashboard
                header("Location: studentDashboard.php");
                exit;
            } else {
                echo "<script>alert('Password errata per lo studente!'); window.location.href='login.php';</script>";
                exit;
            }
        } else {
            // Nessun utente trovato con quell'email
            echo "<script>alert('Nessun utente trovato con questa email!'); window.location.href='login.php';</script>";
            exit;
        }

    } catch (PDOException $e) {
        // Gestisci eventuali errori
        echo "<script>alert('Errore: " . addslashes($e->getMessage()) . "'); window.location.href='login.php';</script>";
        exit;
    }
} else {
    // Se il form non è stato inviato, redirigi alla pagina di login
    header('Location: login.php');
    exit;
}
?>
