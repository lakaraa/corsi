<?php
// Connessione al database
$host = 'localhost'; 
$user = 'corsi';
$password = 'password.123';
$dbname = 'corsi'; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Creiamo la query per ottenere i corsi dal database
$sql = "SELECT IdCorso, Nome, Durata, DataInizio, DataFine FROM Corso";
$result = $conn->query($sql);

// Creiamo un array per memorizzare i corsi
$corsi = [];

if ($result->num_rows > 0) {
    while ($course = $result->fetch_assoc()) {
        $courseName = htmlspecialchars($course['Nome']);
        
        // Rimuoviamo gli spazi e creiamo il nome del file immagine
        $courseImage = "image/" . str_replace(' ', '', $courseName) . ".png";
        
        // Aggiungiamo il corso all'array con tutte le informazioni
        $corsi[] = [
            'IdCorso' => $course['IdCorso'],
            'Nome' => $course['Nome'],
            'Durata' => $course['Durata'],
            'DataInizio' => $course['DataInizio'],
            'DataFine' => $course['DataFine'],
            'Immagine' => $courseImage // Aggiungiamo il percorso dell'immagine
        ];
    }
    // Restituiamo i corsi in formato JSON
    echo json_encode($corsi);
} else {
    echo json_encode([]);
}

$conn->close();
?>
