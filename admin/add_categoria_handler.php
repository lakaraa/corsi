<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera il nome della categoria dal form
    $nomeCategoria = $_POST['nome_categoria'];

    // Prepara la query per inserire la nuova categoria
    $query = $pdo->prepare("INSERT INTO categoria (NomeCategoria) VALUES (:nomeCategoria)");
    $query->execute(['nomeCategoria' => $nomeCategoria]);

    // Reindirizza alla pagina dei corsi (o dove vuoi tu)
    header('Location: ../admin/amministratoreDashboard.php'); // Puoi cambiare la destinazione della redirezione
    exit;
}
