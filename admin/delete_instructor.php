<?php
// Include la connessione al database
include('../config/config.php');

// Verifica che l'ID dell'istruttore sia stato inviato
if (isset($_POST['instructorId'])) {
    $instructorId = $_POST['instructorId'];

    // Inizia una transazione
    $pdo->beginTransaction();

    try {
        // Prima rimuovi i corsi assegnati all'istruttore
        $deleteCoursesQuery = "UPDATE corso SET IdIstruttore = NULL WHERE IdIstruttore = :instructorId";
        $deleteCoursesStmt = $pdo->prepare($deleteCoursesQuery);
        $deleteCoursesStmt->bindParam(':instructorId', $instructorId, PDO::PARAM_INT);
        $deleteCoursesStmt->execute();

        // Ora elimina l'istruttore
        $deleteInstructorQuery = "DELETE FROM istruttore WHERE IdIstruttore = :instructorId";
        $deleteInstructorStmt = $pdo->prepare($deleteInstructorQuery);
        $deleteInstructorStmt->bindParam(':instructorId', $instructorId, PDO::PARAM_INT);
        $deleteInstructorStmt->execute();

        // Completare la transazione
        $pdo->commit();

        // Reindirizza alla pagina di gestione istruttori
        header('Location: ../admin/amministratoreDashboard.php');
        exit();
    } catch (Exception $e) {
        // Annulla la transazione in caso di errore
        $pdo->rollBack();
        echo "Errore durante l'eliminazione dell'istruttore: " . $e->getMessage();
    }
} else {
    echo "ID istruttore non fornito.";
}
?>
