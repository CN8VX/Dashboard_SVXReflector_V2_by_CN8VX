<?php
require_once('include/config.php');
error_reporting(0);

# dateDifference
/*
	$qso_time = dateDifference("01.09.2017 19:00:10","01.09.2017 18:00:00");
	print $qso_time->total_sec;
*/
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);
	$interval = date_diff($datetime1, $datetime2);
    $t1 = strtotime($date_1);
    $t2 = strtotime($date_2);
    $dtd = new stdClass();
    $dtd->interval = $t2 - $t1;
    $dtd->total_sec = abs($t2-$t1);

	return $dtd;
}

function getlastlog_svxreflector($logfile, $logcount, $include = null) {
    $lines = [];
    $buffer = '';
    $fp = fopen($logfile, 'r');

    if (!$fp) return [];

    $excludes = [
        "Dropping out of sequence frame with",
        "UDP frame(s) lost. Expected",
        "Can not read user data from json file: /tmp/cached_index.json",
        " Using configuration file:"
    ];

    fseek($fp, 0, SEEK_END);
    $pos = ftell($fp);

    while ($pos > 0 && count($lines) < $logcount) {
        $pos--;
        fseek($fp, $pos, SEEK_SET);
        $char = fgetc($fp);

        if ($char === "\n") {
            if ($buffer !== '') {
                $line = strrev($buffer);
                $buffer = '';

                // Exclusion de certaines lignes
                $excludeLine = false;
                foreach ($excludes as $ex) {
                    if (stripos($line, $ex) !== false) {
                        $excludeLine = true;
                        break;
                    }
                }

                // Masquer les adresses IP dans la ligne
                if (!$excludeLine) {
                    // Supprimer toutes les adresses IP et leurs ports de la ligne
                    $line = preg_replace('/\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}(?::[0-9]+)?\b/', '', $line);
                    // Nettoyer les espaces multiples et les espaces en début/fin
                    $line = trim(preg_replace('/\s+/', ' ', $line));
                    // Nettoyer les "from " ou "from" orphelins
                    $line = preg_replace('/\bfrom\s*$/', '', $line);
                    $line = preg_replace('/\bfrom\s+with/', 'with', $line);
                    $line = trim($line);
                }
                
                // Inclusion si non exclue, si filtre (optionnel), et si la ligne n'est pas vide après nettoyage
                if (!$excludeLine && (!$include || stripos($line, $include) !== false) && !empty(trim($line))) {
                    $lines[] = $line;
                }
            }
        } else {
            $buffer .= $char;
        }
        
    }
    
    // Dernière ligne (si pas de \n final)
    if ($buffer !== '' && count($lines) < $logcount) {
        $line = strrev($buffer);
        $excludeLine = false;
        foreach ($excludes as $ex) {
            if (stripos($line, $ex) !== false) {
                $excludeLine = true;
                break;
            }
        }
        if (!$excludeLine && (!$include || stripos($line, $include) !== false)) {
            $lines[] = $line;
        }
    }

    fclose($fp);
    return $lines; // Déjà dans l’ordre du plus récent au plus ancien
}


# 01.09.2017-18:02:47 FORMAT
function logtounixtime($timestring) {
    $to=$timestring;
    list($part1,$part2) = explode('-', $to);
    list($day, $month, $year) = explode('.', $part1);
    list($hours, $minutes,$seconds) = explode(':', $part2);
    $timeto =  mktime($hours, $minutes, $seconds, $month, $day, $year);
    return $timeto;
}
?>

<?php
// Fonction d'affichage des logs avec des icônes
function formatLogWithIcons($logLines) {
    $formattedLines = [];
    
    foreach ($logLines as $line) {
        $icon = '⚪'; // Icône par défaut
        
        // Détection du type d'événement et attribution de l'icône
        if (stripos($line, 'Talker start') !== false || stripos($line, 'start') !== false) {
            $icon = '🟩'; // Vert pour démarrage
        } elseif (stripos($line, 'Talker stop') !== false || stripos($line, 'stop') !== false) {
            $icon = '🟥'; // Rouge pour arrêt
        } elseif (stripos($line, 'disconnected') !== false || stripos($line, 'disconnect') !== false) {
            $icon = '🟠'; // Orange pour déconnexion
        } elseif (stripos($line, 'Login OK') !== false || stripos($line, 'connected') !== false) {
            $icon = '🔵'; // Bleu pour connexion réussie
        } elseif (stripos($line, 'Login failed') !== false || stripos($line, 'failed') !== false) {
            $icon = '🔴'; // Rouge foncé pour échec
        } elseif (stripos($line, 'timeout') !== false) {
            $icon = '🟡'; // Jaune pour timeout
        } elseif (stripos($line, 'select') !== false || stripos($line, 'Select') !== false) {
            $icon = '🟨'; // Icône de 'Select en carré jaune plein
        } elseif (stripos($line, 'monitor') !== false || stripos($line, 'MONITOR') !== false) {
            $icon = '🟦'; // Carré bleu plein pour monitoring
        } elseif (stripos($line, 'error') !== false || stripos($line, 'ERROR') !== false) {
            $icon = '❌'; // Croix pour erreur
        } elseif (stripos($line, 'warning') !== false || stripos($line, 'WARNING') !== false) {
            $icon = '⚠️'; // Attention pour avertissement
        }
        
        // Ajouter l'icône au début de la ligne
        $formattedLines[] = $icon . ' ' . $line;
    }
    
    return $formattedLines;
}
?>