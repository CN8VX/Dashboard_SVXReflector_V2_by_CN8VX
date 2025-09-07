<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVXReflector Log Viewer by CN8VX</title>
    <link rel="icon" type="image/png" href="<?php echo $favicon_path_SLV; ?>">
    <link rel="stylesheet" href="/css/style.css">
    <meta name="description" content="SVXReflector Log Viewer by CN8VX - Real-time log viewing, quick search and activity tracking.">
    <meta name="author" content="CN8VX">
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="SVXReflector Log Viewer by CN8VX – Real-Time Log Monitoring">
    <meta property="og:description" content="SVXReflector Log Viewer by CN8VX - Real-time log viewing, quick search and activity tracking.">
    <meta property="og:image" content="/img/logviewer_by_CN8VX.png">
    <meta property="og:url" content="https://www.dmr-maroc.com/">
    <meta property="og:site_name" content="SVXReflector Log Viewer by CN8VX">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SVXReflector Log Viewer by CN8VX – Real-Time Log Monitoring">
    <meta name="twitter:description" content="SVXReflector Log Viewer by CN8VX - Real-time log viewing, quick search and activity tracking.">
    <meta name="twitter:image" content="/img/logviewer_by_CN8VX.png">
    <script>
        (function() {
            'use strict';
            // Récupérer le thème sauvegardé
            var savedTheme = 'light';
            try {
                savedTheme = localStorage.getItem('svx-theme') || 'light';
            } catch(e) {
                console.warn('LocalStorage not available:', e);
            }
            // Appliquer le thème immédiatement à l'élément HTML
            if (document.documentElement) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            }
            // Au cas où document.documentElement n'existe pas encore
            document.addEventListener('DOMContentLoaded', function() {
                if (document.documentElement) {
                    document.documentElement.setAttribute('data-theme', savedTheme);
                }
            });
            console.log('Theme pre-initialized:', savedTheme);
        })();
    </script>
</head>
<body data-refresh-interval="<?php echo $refresh_interval; ?>">
    
<!-- Header avec logo et titre -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="<?php echo LOGO_PATH; ?>" alt="Logo" class="logo">
                <h1 class="title"><?php echo $page_title_SLV; ?></h1>
            </div>
            <div class="theme-toggle" id="themeToggle">
                <div class="theme-slider"></div>
            </div>
        </div>
    </header>
    
    <!-- Navigation -->
     <div class="nvblgvwr">
    <?php include 'include/navbar.php'; ?>
    </div>

    <!-- Contenu principal -->
    <main class="container">
    <div>
        <h2 class="text-logged">Vous êtes connecté en tant que : <span class="logged"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h2>
        <a href="logout.php"><button class="button01">Logout</button></a>
    </div>
        <?php
        // Si c'est une requête AJAX, on ne retourne que le contenu de la table
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            // Mode AJAX - retourner seulement les données du tableau
            include 'include/table.php';
            exit; // Important: arrêter l'exécution pour éviter d'inclure le HTML complet
        } else {
            // Page complète
            include 'include/table.php';
        }
        ?>
    

    <!-- Scripts -->
    <script src="/scripts/main.js"></script>
</body>

</html>