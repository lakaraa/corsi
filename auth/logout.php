<?php
// Inizia la sessione
session_start();

// Distruggi tutte le variabili di sessione
session_unset();

// Distruggi la sessione
session_destroy();

// Reindirizza alla pagina di login
header("Location: ../pages/login.php");
exit();
?>
