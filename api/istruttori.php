<?php
require_once 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        try {
            $stmt = $conn->query("SELECT * FROM Istruttori");
            $instructors = $stmt->fetch_all(PDO::FETCH_ASSOC);
            echo json_encode($instructors);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nel recupero degli istruttori.', 'error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nome']) || empty($data['cognome']) || empty($data['telefono']) || empty($data['email'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Tutti i campi sono obbligatori.']);
            exit;
        }

        try {
            $stmt = $conn->prepare(
                "INSERT INTO Istruttori (Nome, Cognome, Telefono, Email) VALUES (:nome, :cognome, :telefono, :email)"
            );
            $stmt->bind_param(':nome', $data['nome']);
            $stmt->bind_param(':cognome', $data['cognome']);
            $stmt->bind_param(':telefono', $data['telefono']);
            $stmt->bind_param(':email', $data['email']);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(['message' => 'Istruttore creato con successo.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nella creazione dell\'istruttore.', 'error' => $e->getMessage()]);
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
            $stmt = $conn->prepare(
                "UPDATE Istruttori SET Nome = :nome, Cognome = :cognome, Telefono = :telefono, Email = :email WHERE IdIstruttore = :id"
            );
            $stmt->bind_param(':nome', $data['nome']);
            $stmt->bind_param(':cognome', $data['cognome']);
            $stmt->bind_param(':telefono', $data['telefono']);
            $stmt->bind_param(':email', $data['email']);
            $stmt->bind_param(':id', $data['id']);
            $stmt->execute();
            echo json_encode(['message' => 'Istruttore aggiornato con successo.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nell\'aggiornamento dell\'istruttore.', 'error' => $e->getMessage()]);
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
            $stmt = $conn->prepare("DELETE FROM Istruttori WHERE IdIstruttore = :id");
            $stmt->bind_param(':id', $data['id']);
            $stmt->execute();
            echo json_encode(['message' => 'Istruttore eliminato con successo.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Errore nell\'eliminazione dell\'istruttore.', 'error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Metodo non supportato.']);
        break;
}
