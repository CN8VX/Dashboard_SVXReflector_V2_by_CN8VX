<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
require_once 'functions.php';

// Debug: Vérifier si les fichiers de logs existent
$debugInfo = [];
foreach ($LOGFILES_SLV as $logFile) {
    $debugInfo[] = [
        'file' => $logFile,
        'exists' => file_exists($logFile),
        'readable' => file_exists($logFile) ? is_readable($logFile) : false
    ];
}

// Get search filters
$filters = [];
if (!empty($_GET['action'])) $filters['action'] = $_GET['action'];
if (!empty($_GET['search'])) $filters['message'] = $_GET['search'];

// Get current page
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Read and parse log files
$entries = readLogFiles($LOGFILES_SLV);

// Apply filters
$filteredEntries = filterLogEntries($entries, $filters);

// Paginate results
$paginationData = paginateEntries($filteredEntries, $currentPage, $lines_per_page);
?>

<div class="search-container">
    <form method="GET" class="search-form" id="searchForm">
        <div class="search-row">
            <select name="action" class="search-select">
                <option value="">Toutes les actions</option>
                <option value="Login OK" <?php echo ($_GET['action'] ?? '') === 'Login OK' ? 'selected' : ''; ?>>Login OK</option>
                <option value="Talker" <?php echo ($_GET['action'] ?? '') === 'Talker' ? 'selected' : ''; ?>>Talker start & stop</option>
                <option value="Talker start" <?php echo ($_GET['action'] ?? '') === 'Talker start' ? 'selected' : ''; ?>>Talker start</option>
                <option value="Talker stop" <?php echo ($_GET['action'] ?? '') === 'Talker stop' ? 'selected' : ''; ?>>Talker stop</option>
                <option value="Connected" <?php echo ($_GET['action'] ?? '') === 'Connected' ? 'selected' : ''; ?>>Connected</option>
                <option value="Disconnected" <?php echo ($_GET['action'] ?? '') === 'Disconnected' ? 'selected' : ''; ?>>Disconnected</option>
            </select>
            <input type="text" name="search" placeholder="Recherche générale: message, date (23. = 23.**.****, 23.05 = 23.05.****), heure (10: = 10:**:**, 10:00 = 10:00:**), indicatif (CN8EAA trouve CN8EAA-L), TG..." 
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="search-input">
            <button type="submit" class="search-btn">Rechercher</button>
            <button type="button" class="clear-btn" onclick="clearSearch()">Effacer</button>
        </div>
    </form>
</div>

<div class="table-containerlgvwr">
    <table class="log-tablelgvwr" id="logTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Indicatif</th>
                <th>TG</th>
                <th>Action</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($paginationData['entries'])): ?>
                <tr>
                    <td colspan="6" class="no-data">Aucune entrée trouvée</td>
                </tr>
            <?php else: ?>
                <?php foreach ($paginationData['entries'] as $entry): ?>
                    <tr class="log-row action-<?php echo strtolower(str_replace([' ', '_'], '-', $entry['action'])); ?>">
                        <td class="date-cell"><?php echo htmlspecialchars($entry['date']); ?></td>
                        <td class="time-cell"><?php echo htmlspecialchars($entry['time']); ?></td>
                        <td class="indicatif-cell">
                            <?php if (!empty($entry['indicatif_complet'])): ?>
                                <span class="indicatif"><?php echo htmlspecialchars($entry['indicatif_complet']); ?></span>
                            <?php elseif (!empty($entry['indicatif'])): ?>
                                <span class="indicatif"><?php echo htmlspecialchars($entry['indicatif']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="tg-cell">
                            <?php if (!empty($entry['tg'])): ?>
                                <span class="tg">TG#<?php echo htmlspecialchars($entry['tg']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="action-cell">
                            <span class="action-badge action-<?php echo strtolower(str_replace([' ', '_'], '-', $entry['action'])); ?>">
                                <?php echo htmlspecialchars($entry['action']); ?>
                            </span>
                        </td>
                        <td class="message-cell"><?php echo htmlspecialchars($entry['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($paginationData['totalPages'] > 1): ?>
    <div class="pagination-container">
        <?php echo generatePagination($paginationData); ?>
        <div class="pagination-info">
            Affichage de <?php echo ($paginationData['currentPage'] - 1) * $paginationData['perPage'] + 1; ?>
            à <?php echo min($paginationData['currentPage'] * $paginationData['perPage'], $paginationData['totalEntries']); ?>
            sur <?php echo $paginationData['totalEntries']; ?> entrées
        </div>
    </div>
<?php endif; ?>

<script>
// Update entries count in navbar
const entriesCountElement = document.getElementById('entriesCount');
if (entriesCountElement) {
    entriesCountElement.textContent = 'Entrées: <?php echo $paginationData['totalEntries']; ?>';
}

// Clear search function
function clearSearch() {
    const actionSelect = document.querySelector('select[name="action"]');
    const searchInput = document.querySelector('input[name="search"]');
    
    if (actionSelect) actionSelect.value = '';
    if (searchInput) searchInput.value = '';
    
    window.location.href = window.location.pathname;
}
</script>
</main>
<!-- Début du Footer -->
<?php include 'include/footer.php'; ?>
<!-- Fin du Footer -->