<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include la connessione al database
include('../config.php');
session_start();

// Verifica se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera i dati del form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validazione dei dati
    if (empty($email) || empty($password)) {
        echo "<script>alert('Entrambi i campi sono obbligatori!'); window.location.href='../pages/login.php';</script>";
        exit;
    }

    try {
        // Array di tabelle da verificare con priorità
        $user_types = [
            'amministratore' => ['id_col' => 'IdAmministratore', 'dashboard' => '../admin/amministratoreDashboard.php'],
            'istruttore' => ['id_col' => 'IdIstruttore', 'dashboard' => '../admin/istruttoreDashboard.php'],
            'studente' => ['id_col' => 'IdStudente', 'dashboard' => '../studenti/studentDashboard.php']
        ];

        foreach ($user_types as $type => $info) {
            // Verifica l'utente nella tabella specifica
            $query = "SELECT * FROM $type WHERE Email = :email LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        
            if ($user) {
                // Controlla se la password è corretta
                if (password_verify($password, $user['Password']) || $password === $user['Password']) {
                    // Imposta la sessione
                    $_SESSION['user_id'] = $user[$info['id_col']];
                    $_SESSION['user_email'] = $user['Email'];
                    $_SESSION['user_name'] = $user['Nome'];
                    $_SESSION['user_role'] = $type; // Memorizza il ruolo dell'utente
                    
                    // Salva la query nel file sql_insert.sql
                    $sqlQuery = sprintf(
                        "SELECT * FROM %s WHERE Email = '%s' LIMIT 1;\n",
                        $type,
                        $email
                    );
                    file_put_contents('../sql_insert.sql', $sqlQuery, FILE_APPEND);
    
                    // Redirigi al dashboard appropriato
                    header("Location: {$info['dashboard']}");
                    exit;
                } else {
                    echo "<script>alert('Password errata per $type!'); window.location.href='../pages/login.php';</script>";
                    exit;
                }
            }
        }

        // Nessun utente trovato con quell'email
        echo "<script>alert('Nessun utente trovato con questa email!'); window.location.href='../pages/login.php';</script>";
        exit;

    } catch (PDOException $e) {
        // Gestisci eventuali errori
        echo "<script>alert('Errore: " . addslashes($e->getMessage()) . "'); window.location.href='../pages/login.php';</script>";
        exit;
    }
} else {
    // Se il form non è stato inviato, redirigi alla pagina di login
    header('Location: ../pages/login.php');
    exit;
}
?>
