<?php
include("include/config.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="all" />
    <meta name="keywords" content="Dashboard SVXReflector V2, SvxLink, Svx Link Reflector, SvxLink par CN8VX , Dashboard SVXReflector V2 by CN8VX" />
    <meta name="title" content="Dashboard SVXReflector V2 by CN8VX" />
    <meta name="description" content="Dashboard SVXReflector V2 by CN8VX for Moroccan Amateur Radio Repeaters Interco." />
    <title>Dashboard SVXReflector V2 by CN8VX</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="scripts/scrstyle.js"></script>
    <link rel="shortcut icon" href="<?php echo $favicon_path; ?>">
    <link id="stylefile" rel="stylesheet" href="css/style.css">
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Dashboard SVXReflector V2">
    <meta property="og:description" content="Dashboard SVXReflector V2 by CN8VX for Moroccan Amateur Radio Repeaters Interco.">
    <meta property="og:image" content="img/Dashboard_SVXReflector_V2.png">
    <meta property="og:url" content="https://www.dmr-maroc.com/">
    <meta property="og:site_name" content="SVXReflector Log Viewer by CN8VX">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SVXReflector Log Viewer by CN8VX – Real-Time Log Monitoring">
    <meta name="twitter:description" content="Dashboard SVXReflector V2 by CN8VX for Moroccan Amateur Radio Repeaters Interco.">
    <meta name="twitter:image" content="/img/Dashboard_SVXReflector_V2.png">
</head>

<body>
    
    <div class="container-idx">
        <div id="bannere">
            <a href="https://www.dmr-maroc.com/repeaters_simplex_svxlink.php" target="_blank"><img class="icm" src="<?php echo LOGO_PATH; ?>" alt="Logo">
            </a>
            <span class="logo-title"><?php echo $page_title; ?></span>
        </div>

        <div class="theme-toggle" id="themeToggle" onclick="toggleTheme()">
                <div class="theme-slider">
        </div>
        </div><br>

        <!-- Navigation -->
        <?php include 'include/navbar.php'; ?>

        <!-- Texte défilant -->
        <div class="txdefl">
            <div>Bienvenue sur le <u>Dashboard SVXReflector V2 by CN8VX</u> et bon trafic. | Welcome to the SVXReflector <u>Dashboard V2 by CN8VX</u> and good traffic.</div>
        </div>

        <!-- Informations -->
        <div class="info-container">
            <!--<p class="tex01">
                <b>- Your Text or Description</b>
                <b>- Your Text or Description</b>
                <b>- Your Text or Description</b>
                 b>- Your Text or Description</b>
            </p>-->

            <p class="tex01">
                <b>- Votre temps de transmission doit être inférieur à 3 min pour ne pas être stoppé par l'Anti-bavard (TOT=180sec)</b><br>
                <b>- Veuillez laisser un blanc de 5 à 8 secs entre chaque passage</b><br>
                <b>- Veuillez vous présenter et vous annoncer durant les blancs</b><br>
                <b>- Veuillez respecter la règle d'annoncer votre indicatif et celui à qui vous aimeriez passer le micro</b><br>
                <b>- Veuillez rester à l'écoute entre chaque fin de QSO</b>
            </p>
        </div>

        <!-- Tableau principal -->
        <div id="create_html"></div>
    
    </div>
</body>

<!-- Début du Footer -->
<?php include('include/footer.php'); ?>
<!-- Fin du Footer -->

</html>