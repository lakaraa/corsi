<?php
// Include la connessione al database
include('config.php');

// Avvia la sessione per poter usare i messaggi
session_start();

// Verifica che l'ID dello studente sia stato inviato
if (isset($_POST['studentId'])) {
    $studentId = $_POST['studentId'];

    // Inizia una transazione per garantire che entrambe le operazioni siano atomiche
    $pdo->beginTransaction();

    try {
        // 1. Elimina prima i record dalla tabella iscrizione che fanno riferimento a questo studente
        $deleteIscrizioneQuery = "DELETE FROM iscrizione WHERE IdStudente = :studentId";
        $deleteIscrizioneStmt = $pdo->prepare($deleteIscrizioneQuery);
        $deleteIscrizioneStmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $deleteIscrizioneStmt->execute();

        // 2. Ora elimina lo studente dalla tabella studente
        $deleteStudentQuery = "DELETE FROM studente WHERE IdStudente = :studentId";
        $deleteStudentStmt = $pdo->prepare($deleteStudentQuery);
        $deleteStudentStmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $deleteStudentStmt->execute();

        // Commit della transazione se entrambe le operazioni sono andate a buon fine
        $pdo->commit();

        // Messaggio di successo
        $_SESSION['message'] = 'Studente eliminato con successo!';
        header('Location: amministratoreDashboard.php');
        exit();

    } catch (PDOException $e) {
        // In caso di errore, rollback della transazione
        $pdo->rollBack();
        $_SESSION['message'] = 'Errore durante l\'eliminazione dello studente: ' . $e->getMessage();
        header('Location: amministratoreDashboard.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'ID studente non fornito.';
    header('Location: amministratoreDashboard.php');
    exit();
}
?>
