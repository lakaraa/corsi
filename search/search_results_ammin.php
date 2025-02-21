<?php
include_once('../config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');
include_once('search_query.php'); // Funzionalità di ricerca centralizzata

// Recupera i risultati della ricerca se presente una query
$searchQuery = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$results = [];

if ($searchQuery) {
    try {
        // Chiama la funzione centralizzata per eseguire la ricerca
        $results = searchDatabase($searchQuery);
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Errore durante la ricerca: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Risultati della Ricerca</h1>
        <p class="hero-subtext">Trova corsi, studenti o istruttori.</p>
    </div>
</header>

<!-- Search Results Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <!-- Barra di ricerca -->
        <div class="row mb-5">
            <div class="col-md-12">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" class="search-bar p-3 border rounded shadow-sm bg-light">
                    <div class="d-flex align-items-center" style="gap: 10px;">
                        <input type="text" name="search_query" class="form-control search-input" 
                               placeholder="Cerca corsi, categorie, istruttori o studenti..." 
                               value="<?php echo htmlspecialchars($searchQuery); ?>" 
                               style="flex: 1; padding: 12px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da;">
                        <button type="submit" class="btn btn-primary search-button">Cerca</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Risultati della ricerca -->
<div class="row">
    <div class="col-md-12">
        <h3 class="mb-4">Risultati per "<?php echo htmlspecialchars($searchQuery); ?>"</h3>
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $table => $rows): ?>
                <h4 class="mt-4"><?php echo htmlspecialchars($table); ?>:</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <?php 
                            // Mostra i nomi delle colonne per la tabella corrente
                            $columns = array_keys($rows[0]); // Estrai le chiavi del primo risultato come nomi delle colonne
                            foreach ($columns as $column): 
                                // Usa la funzione per ottenere i nomi più significativi delle colonne
                                $displayName = getColumnDisplayName($column);
                            ?>
                                <th><?php echo htmlspecialchars($displayName); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $result): ?>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <td><?php echo htmlspecialchars($result[$column]); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Nessun risultato trovato per "<?php echo htmlspecialchars($searchQuery); ?>".</p>
        <?php endif; ?>
    </div>
</div>

    </div>
</section>

<?php include('../templates/template_footer.php'); ?>
