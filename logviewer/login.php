<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

session_start();

// Utiliser les utilisateurs définis dans config.php
$USERS = $CONFIG['users'];

if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    if (isset($USERS[$user]) && $USERS[$user] === $pass) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user; // Pour afficher le nom de l'utilisateur
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Login">
    <meta name="title" content="Login" />
    <link rel="shortcut icon" href="/img/document_search_15698.ico">
    <!-- Balises Open Graph pour l'aperçu -->
    <meta property="og:image" content="/img/document_search_15698.ico">
    <!-- fin Balises Open Graph pour l'aperçu -->
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <!--<link rel="stylesheet" href="css/style.css">-->
</head>
<body>


    <br>
    <div class="log-container">
        <h1>Login</h1>
        <div class="theme-toggle" id="themeToggle">
                <div class="theme-slider"></div>
            </div>
        &nbsp;
        <div>
        <?php if (!empty($error)) { echo "<p class='text02 clr-red';'>$error</p>"; } ?>
        <form method="post">
            <label class="text02"><p>User name:</p>
            <input class="log-input" type="text" name="username" autocomplete="username" required>
            </label><br><br>
        <br>
            <label class="text02"><p>Password:</p>
            <input class="log-input" type="password" name="password" autocomplete="current-password" required>
            </label><br><br>
        &nbsp;<br>
            <button class="button01" type="submit">Log in</button>
        </form>
        </div>
    </div>
    <!-- Scripts -->
    <script src="/scripts/main.js"></script>
</body>
<?php include("include/footer.php") ?>
</html>
