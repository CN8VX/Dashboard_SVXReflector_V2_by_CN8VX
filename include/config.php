<?php
/**
 * Configuration for SVXReflector Dashboard V2 and Log Viewer
 * Configuration pour SVXReflector Dashboard V2 et Log Viewer
 * By / Par CN8VX
 */

// ====================================================================
// GENERAL CONFIGURATION / CONFIGURATION GÉNÉRALE
// ====================================================================

// Set the default timezone
// Définition du fuseau horaire par défaut
// You can find timezones at: https://www.php.net/manual/en/timezones.php
// Vous pouvez trouver les fuseaux horaires sur : https://www.php.net/manual/en/timezones.php
date_default_timezone_set('Africa/Casablanca');

// ====================================================================
// INTERFACE CONFIGURATION / CONFIGURATION DE L'INTERFACE
// ====================================================================

// Page titles for Dashboard SVXReflector
// Titres des pages pour Dashboard SVXReflector
$page_title = "SVXReflector Dashboard V2 by CN8VX";

// Page titles for SVXReflector Log Viewer
// Titres des pages pour SVXReflector Log Viewer
$page_title_SLV = "SVXReflector Log Viewer by CN8VX";

// Logo path / Chemin du logo
define("LOGO_PATH", "/img/logo.png");

// Favicon path for Dashboard SVXReflector
// Chemin du favicon pour Dashboard SVXReflector
$favicon_path = "/img/favicon.png";

// Favicon path for SVXReflector Log Viewer
// Chemin du favicon pour SVXReflector Log Viewer
$favicon_path_SLV = "/img/favicon_SLV.png";

// ====================================================================
// LOG FILES CONFIGURATION / CONFIGURATION DES FICHIERS DE LOGS
// ====================================================================

// Log files for Dashboard SVXReflector
// Fichiers de logs pour Dashboard SVXReflector
// Default path is: '/var/log/svxreflector'
// Le chemin par défaut est : '/var/log/svxreflector'
$LOGFILES = array('/var/log/svxreflector');

// Log files for SVXReflector Log Viewer
// Fichiers de logs pour SVXReflector Log Viewer
// Full path to the log file, 'file2', 'file3', 'file4' ...
// Chemin complet vers le fichier journal, 'file2', 'file3', 'file4' ...
// Full paths to log files (with rotation)
// Chemins complets vers les fichiers journaux (avec rotation)
$LOGFILES_SLV = array(
    '/var/log/svxreflector',
    '/var/log/svxreflector.1',
    '/var/log/svxreflector.2',
    '/var/log/svxreflector.3',
    '/var/log/svxreflector.4',
    '/var/log/svxreflector.5',
    '/var/log/svxreflector.6',
    '/var/log/svxreflector.7',
    '/var/log/svxreflector.8',
    '/var/log/svxreflector.9'
);

// ====================================================================
// DISPLAY CONFIGURATION for Dashboard SVXReflector
// CONFIGURATION D'AFFICHAGE pour Dashboard SVXReflector
// ====================================================================

// Language and legend display
// Langue et affichage de la légende
// "FR" for French, "FR-I" for French with indication,
// "FR" pour Français, "FR-I" pour Français avec indication,
// "EN" for English or "EN-I" for English with indication.
// "EN" pour Anglais ou "EN-I" pour Anglais avec indication.
// If define("LEGEND", "") the legend is not displayed
// Si define("LEGEND", "") la légende ne s'affiche pas
define("LEGEND", "EN-I");

// Display log file lines in the "Logfile" section
// Affichage des lignes du fichier log dans la partie "Logfile"
// Set to "SHOW" to display
// Définir sur "SHOW" pour afficher
define("LOGFILETABLE", "SHOW");

// Number of lines to display in the "Logfile" section for Dashboard SVXReflector
// Nombre de lignes à afficher dans la partie "Logfile" pour Dashboard SVXReflector
define("LOGLINECOUNT", "30");

// Display IP addresses of connected repeaters
// Affichage des adresses IP des répéteurs connectés
// "SHOW" to display, "SHOWNO" to hide
// "SHOW" pour afficher, "SHOWNO" pour ne pas afficher
define("IPLIST", "SHOWNO");

// Display refresh time status line
// Affichage de la ligne d'état de l'heure de rafraîchissement
// Set to "SHOW" to see
// Définir sur "SHOW" pour voir
define("REFRESHSTATUS", "SHOW");

// Display monitoring talkgroup ("MON"=MonitorTG)
// Affichage du groupe de discussion de surveillance ("MON"=MonitorTG)
// "SHOW" for yes, "SHOWNO" for no
// "SHOW" pour oui, "SHOWNO" pour non
define("MON", "SHOW");

// Display talkgroup (TG)
// Affichage du groupe de discussion (TG)
// "SHOW" for yes, "SHOWNO" for no
// "SHOW" pour oui, "SHOWNO" pour non
define("TG", "SHOW");

// ========================================================================
// DATA RECOVERY CONFIGURATION for Dashboard SVXReflector
// CONFIGURATION DE LA RÉCUPÉRATION DE DONNÉES pour Dashboard SVXReflector
// ========================================================================

// Data recovery after log rotation
// Récupération des données après rotation du journal
// Set to "YES" to save client data and recover it after log rotation
// Définir sur "YES" pour enregistrer les données du client et les récupérer
// in the directory www/svxrdb/recover_data_xxxxxx
// après la rotation du journal dans le répertoire www/svxrdb/recover_data_xxxxxx
// For SD card users, this is not recommended due to size issues
// Pour les utilisateurs de carte SD, c'est déconseillé à cause de la taille
define("RECOVER", "NO");

// ====================================================================
// TRANSMISSION CONFIGURATION for Dashboard SVXReflector
// CONFIGURATION DES TRANSMISSIONS pour Dashboard SVXReflector
// ====================================================================

// Last heard transmission configuration
// Configuration de la dernière transmission entendue
// "EAR" to mark with last transmission icon
// "EAR" pour marquer avec l'icône de la dernière transmission
// "TOP" to see last transmission first in the list
// "TOP" pour voir la dernière transmission en premier dans la liste
// Changeable at runtime by clicking on "Callsign client" or "state" header
// Changeable en cliquant sur l'en-tête "Callsign client" ou "state"
$LASTHEARD = "TOP";

// ====================================================================
// SVXReflector LOG VIEWER CONFIGURATION
// CONFIGURATION SVXReflector LOG VIEWER
// ====================================================================

// Refresh interval in seconds
// Intervalle de rafraîchissement en secondes
$refresh_interval = 5;

// Number of lines per page (20-120)
// Nombre de lignes par page (20-120)
$lines_per_page = 20;

// ====================================================================
// AUTHENTICATION CONFIGURATION for SVXReflector Log Viewer
// CONFIGURATION D'AUTHENTIFICATION pour SVXReflector Log Viewer
// ====================================================================

$CONFIG = [
    // User list ("username" => "password")
    // Liste des utilisateurs ("nom_utilisateur" => "mot_de_passe")
    // These are just examples, you can use any username and password
    // Ce ne sont que des exemples, vous pouvez mettre n'importe quel nom d'utilisateur et mot de passe
    'users' => [
        "admin" => "admin",
        "user" => "123456",
        "user1" => "user123"
    ]
];

// ====================================================================
// SYSOP INFORMATION for FOOTER
// INFORMATIONS SYSOP pour FOOTER
// ====================================================================

// System operator callsign
// Indicatif du SYSOP du système
$SYSOP = "CN8VX,";

// System operator name
// Nom du SYSOP du système
$SYSOPNAME = "Youness";

?>
