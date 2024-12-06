<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        try {
            $result = $conn->query("SELECT * FROM Amministratori");
            $administrators = $result->fetch_all(MYSQLI_ASSOC); // Per ottenere i dati come array associativo
            echo json_encode($administrators);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nel recupero degli amministratori.', 'error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome']) || empty($data['cognome']) || empty($data['telefono']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Tutti i campi sono obbligatori.']);
            exit;
        }

        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO Amministratori (Nome, Cognome, Telefono, Email, Password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $data['nome'], $data['cognome'], $data['telefono'], $data['email'], $hashedPassword);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(['message' => 'Amministratore creato con successo.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nella creazione dell\'amministratore.', 'error' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id']) || empty($data['nome']) || empty($data['cognome']) || empty($data['telefono']) || empty($data['email'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Tutti i campi sono obbligatori.']);
            exit;
        }

        try {
            $stmt = $conn->prepare("UPDATE Amministratori SET Nome = ?, Cognome = ?, Telefono = ?, Email = ? WHERE IdAmministratore = ?");
            $stmt->bind_param('sssss', $data['nome'], $data['cognome'], $data['telefono'], $data['email'], $data['id']);
            $stmt->execute();
            echo json_encode(['message' => 'Amministratore aggiornato con successo.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nell\'aggiornamento dell\'amministratore.', 'error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'ID è obbligatorio.']);
            exit;
        }

        try {
            $stmt = $conn->prepare("DELETE FROM Amministratori WHERE IdAmministratore = ?");
            $stmt->bind_param('s', $data['id']);
            $stmt->execute();
            echo json_encode(['message' => 'Amministratore eliminato con successo.']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nell\'eliminazione dell\'amministratore.', 'error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Metodo non supportato.']);
        break;
}
?>
