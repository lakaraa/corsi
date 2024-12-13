<?php
session_start();
// Connessione al database con PDO
require_once 'config.php'; // Connessione PDO con le credenziali giuste
include('navbar.php');
// Verifica se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ID dello studente loggato
$student_id = $_SESSION['user_id'];

// Verifica se è stato passato l'ID del corso
if (!isset($_GET['corso_id']) || empty($_GET['corso_id'])) {
    echo "ID Corso non valido.";
    exit();
}

// ID del corso scelto
$corso_id = $_GET['corso_id'];

try {
    // Ora utilizziamo la connessione che abbiamo già configurato in config.php
    // Non è necessario fare una nuova connessione, poiché è già disponibile tramite $pdo

    // Verifica se l'utente è già iscritto al corso
    $query = "SELECT * FROM iscrizione WHERE IdStudente = :student_id AND IdCorso = :corso_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':corso_id', $corso_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Se l'utente è già iscritto, mostra un messaggio
        echo "Sei già iscritto a questo corso!";
    } else {
        // Altrimenti, effettua la prenotazione
        $data_iscrizione = date('Y-m-d'); // data odierna
        $livello = "Base"; // Puoi personalizzare il livello

        // Inserisci l'iscrizione
        $query = "INSERT INTO iscrizione (DataIscrizione, Livello, IdCorso, IdStudente) VALUES (:data_iscrizione, :livello, :corso_id, :student_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':data_iscrizione', $data_iscrizione);
        $stmt->bindParam(':livello', $livello);
        $stmt->bindParam(':corso_id', $corso_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Prenotazione riuscita
            echo "Prenotazione effettuata con successo!";
            // Redirigi l'utente alla pagina dei corsi o altra pagina
            header("Location: corsi.php");
            exit();
        } else {
            echo "Errore durante la prenotazione.";
        }
    }
} catch (PDOException $e) {
    echo "Errore nella connessione al database: " . $e->getMessage();
}
?>
