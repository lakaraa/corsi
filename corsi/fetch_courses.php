<?php
include('../config/config.php');

$nome = isset($_GET['nome']) ? trim($_GET['nome']) : '';
$durata = isset($_GET['durata']) ? intval($_GET['durata']) : 0;
$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;

$defaultImagePath = '../resources/image/Default.png';  // Percorso immagine di default

try {
    $query = "SELECT * FROM Corso WHERE 1=1";
    $params = [];

    if ($nome) {
        $query .= " AND Nome LIKE :nome";
        $params[':nome'] = "%$nome%";
    }

    if ($durata) {
        $query .= " AND Durata <= :durata";
        $params[':durata'] = $durata;
    }

    if ($categoria) {
        $query .= " AND idCategoria = :categoria";
        $params[':categoria'] = $categoria;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($corsi) {
        foreach ($corsi as $corso) {
            $courseName = htmlspecialchars($corso['Nome']);
            $courseImagePath = "../resources/image/" . str_replace(' ', '', $courseName) . ".png";
        
            if (file_exists($courseImagePath)) {
                $imageToDisplay = $courseImagePath;
            } else {
                $imageToDisplay = $defaultImagePath;
            }
        
            echo "
            <div class='col-sm-6 col-md-4 mb-4'>
                <div class='card text-center border-0 shadow'>
                    <div class='services-terri-figure position-relative'>
                        <img src='$imageToDisplay' alt='{$courseName}' class='img-fluid rounded'>
                        <a href='javascript:void(0);' class='lens-icon position-absolute top-50 start-50 translate-middle' onclick=\"redirectToCourse({$corso['IdCorso']})\">
                            <i class='fas fa-search'></i>
                        </a>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title'>{$courseName}</h5>
                        <p class='card-text'>Durata: {$corso['Durata']} ore</p>
                    </div>
                </div>
            </div>";
        }
        
    } else {
        echo "<p class='text-center'>Nessun corso trovato con i criteri selezionati.</p>";
    }
} catch (PDOException $e) {
    echo "<p class='text-center'>Errore nella ricerca: " . $e->getMessage() . "</p>";
}
?>

<script>
    // Funzione per gestire il reindirizzamento
    function redirectToCourse(courseId) {
        const studentId = '<?= isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : '' ?>';

        if (!studentId) {
            // Se non è loggato, reindirizza alla pagina di login
            window.location.href = "../auth/login.php";
        } else {
            // Altrimenti, reindirizza alla pagina di iscrizione
            window.location.href = "../corsi/iscrizione_corso.php?corso_id=" + encodeURIComponent(courseId);
        }
    }
</script>
