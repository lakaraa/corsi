<?php
// Connessione al database
$dbHost = 'localhost';
$dbUser = 'corsi';
$dbPass = 'password.123'; // Cambia con la tua password
$dbName = 'corsi';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Parsing della richiesta
$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

header('Content-Type: application/json');

switch ($requestMethod) {
    case 'GET':
        switch ($path) {
            case 'corsi':
                // Restituisci tutti i corsi
                $result = $conn->query("SELECT Nome FROM corso");
                $corsi = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($corsi);
                break;
            case 'categorie':
                // Restituisci tutte le categorie
                $result = $conn->query("SELECT NomeCategoria FROM categoria");
                $categorie = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($categorie);
                break;
            case 'istruttori':
                // Restituisci tutti gli istruttori
                $result = $conn->query("SELECT Nome,Cognome FROM istruttore");
                $istruttori = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($istruttori);
                break;
            case 'studenti':
                // Restituisci tutti gli studenti
                $result = $conn->query("SELECT Nome, Cognome FROM studente");
                $studenti = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($studenti);
                break;
            default:
                http_response_code(404);
                echo json_encode(["message" => "Endpoint non trovato"]);
        }
        break;
    case 'POST':
        switch ($path) {
            case 'aggiungi-corso':
                // Aggiungi un corso
                $data = json_decode(file_get_contents('php://input'), true);
                $nome = $data['nome'] ?? '';
                $durata = $data['durata'] ?? '';
                $dataInizio = $data['data_inizio'] ?? '';
                $idIstruttore = $data['id_istruttore'] ?? '';
                $idCategoria = $data['id_categoria'] ?? '';
                $idAmministratore = $data['id_amministratore'] ?? '';
                $dataFine = $data['data_fine'] ?? '';

                $stmt = $conn->prepare("INSERT INTO corso (Nome, Durata, DataInizio, IdIstruttore, IdCategoria, DataFine, IdAmministratore) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssiisi", $nome, $durata, $dataInizio, $idIstruttore, $idCategoria, $dataFine, $idAmministratore);

                if ($stmt->execute()) {
                    echo json_encode(["message" => "Corso aggiunto con successo"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Errore nell'aggiunta del corso"]);
                }
                $stmt->close();
                break;
            case 'aggiungi-istruttore':
            case 'aggiungi-amministratore':
                // Aggiungi istruttore o amministratore
                $data = json_decode(file_get_contents('php://input'), true);
                $nome = $data['nome'] ?? '';
                $cognome = $data['cognome'] ?? '';
                $telefono = $data['telefono'] ?? '';
                $email = $data['email'] ?? '';
                $password = password_hash($data['password'] ?? '', PASSWORD_BCRYPT);

                $tabella = ($path === 'aggiungi-istruttore') ? 'istruttori' : 'amministratori';

                $stmt = $conn->prepare("INSERT INTO $tabella (Nome, Cognome, Telefono, Email, Password) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $nome, $cognome, $telefono, $email, $password);

                if ($stmt->execute()) {
                    echo json_encode(["message" => "$tabella aggiunto con successo"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Errore nell'aggiunta a $tabella"]);
                }
                $stmt->close();
                break;
            default:
                http_response_code(404);
                echo json_encode(["message" => "Endpoint non trovato"]);
        }
        break;
    case 'PUT':
        // Modifica un corso
        if ($path === 'modifica-corso') {
            $data = json_decode(file_get_contents('php://input'), true);
            $idCorso = $data['id_corso'] ?? '';
            $nome = $data['nome'] ?? '';
            $durata = $data['durata'] ?? '';
            $dataInizio = $data['data_inizio'] ?? '';
            $idIstruttore = $data['id_istruttore'] ?? '';
            $idCategoria = $data['id_categoria'] ?? '';
            $dataFine = $data['data_fine'] ?? '';

            $stmt = $conn->prepare("UPDATE corso SET Nome = ?, Durata = ?, DataInizio = ?, IdIstruttore = ?, IdCategoria = ?, DataFine = ? WHERE IdCorso = ?");
            $stmt->bind_param("sssiisi", $nome, $durata, $dataInizio, $idIstruttore, $idCategoria, $dataFine, $idCorso);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Corso aggiornato con successo"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Errore nell'aggiornamento del corso"]);
            }
            $stmt->close();
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Endpoint non trovato"]);
        }
        break;
    case 'DELETE':
        switch ($path) {
            case 'elimina-corso':
                $idCorso = $_GET['id_corso'] ?? '';
                if (!empty($idCorso)) {
                    $stmt = $conn->prepare("DELETE FROM corso WHERE IdCorso = ?");
                    $stmt->bind_param("i", $idCorso);

                    if ($stmt->execute()) {
                        echo json_encode(["message" => "Corso eliminato con successo"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["message" => "Errore nell'eliminazione del corso"]);
                    }
                    $stmt->close();
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Id corso non fornito"]);
                }
                break;
            case 'elimina-istruttore':
            case 'elimina-studente':
                $id = $_GET['id'] ?? '';
                $tabella = ($path === 'elimina-istruttore') ? 'istruttori' : 'studenti';

                if (!empty($id)) {
                    $stmt = $conn->prepare("DELETE FROM $tabella WHERE Id = ?");
                    $stmt->bind_param("i", $id);

                    if ($stmt->execute()) {
                        echo json_encode(["message" => "$tabella eliminato con successo"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["message" => "Errore nell'eliminazione da $tabella"]);
                    }
                    $stmt->close();
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Id non fornito"]);
                }
                break;
            default:
                http_response_code(404);
                echo json_encode(["message" => "Endpoint non trovato"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Metodo non consentito"]);
}

$conn->close();
?>
