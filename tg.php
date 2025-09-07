<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
if ( !file_exists('include/config.php') ) { die("ERROR: File include/config.php not found...exiting"); }
else { include_once 'include/config.php'; }

include_once 'include/tgdb.php';
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Dashboard SVXReflector, SvxLink, Svx Link Reflector, Svx, svx link, svx,  SvxLink par CN8VX, Dashboard SVXReflector by CN8VX " />
    <meta name="title" content="Dashboard SVXReflector by CN8VX V2" />
    <meta name="description" content="Dashboard SVXReflector by CN8VX for Moroccan Amteur Radio Repeaters Interco by CN8VX." />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="scripts/scrstyle.js"></script>
    <link rel="shortcut icon" href="<?php echo $favicon_path; ?>">
    <link id="stylefile" rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php echo "<title>$page_title</title>"; ?>
</head>

<body>
    <div>
        <div id="bannere">
            <a href="https://www.dmr-maroc.com/repeaters_simplex_svxlink.php" target="_blank">
                <img class="icm" src="<?php echo LOGO_PATH; ?>" alt="Logo">
            </a>
            <span class="logo-title">Talk Groups</span>
    </div>

    <div class="container-tg">
        <!-- Les boutons -->
        <div class="button-container">
        <a href="index.php"><button class="btn02"><i class="fa fa-home"></i>&nbsp;Home</button></a>
        </div>
        &nbsp;
        
        <?php
            echo '<center><div>'."\n";
            include "include/tabletg.php";
            echo '</div></center>'."\n";
            echo "<br />\n";
        ?>
    </div>

</body>

<!-- Début du Footer -->
<?php include('include/footer.php'); ?>
<!-- Fin du Footer -->

</html>
