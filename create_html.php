<?php
require_once('include/config.php');
require_once('include/function.php');
require_once('include/logparse.php');
require_once('include/array_column.php');
require_once('include/userdb.php');
require_once('include/tgdb.php');

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>";
echo "<script src=\"scripts/tablesort.js\"></script>\r\n";

if(isset($_COOKIE["svxrdb"])) {
    $LASTHEARD = $_COOKIE["svxrdb"];
}

$logs = array();
if(count($LOGFILES,0) >0) {
    for($i=0; $i<count($LOGFILES,0); $i++) {
        /* vérifier si la taille du nom de fichier est supérieure à zéro */
        if(empty($LOGFILES[$i])) { } else {
            $lastdata=getdata($LOGFILES[$i]);
            if(count($lastdata) >0) {
                $logs=array_merge($logs, $lastdata);
                $logs[] = array ('CALL' => "NEWLOGFILEDATA");
            }
        }/* FIN vérifier le nom du film vérifier la taille */
    }
} else { exit(0); }

/* Texte d'information du survol de la souris depuis userdb.php */
foreach ($logs as $i => $log) {
    $call = $log['CALL'];
    if (isset($userdb_array[$call])) {
        $logs[$i]['COMMENT'] = $userdb_array[$call];
    }
}

if (count($logs) >= 0){
    echo "<main><div class=\"table-container\"><table id=\"logtable\">\n\r<tr>\n\r";
    echo "<thead>\n\r<tr>\n\r";
    echo "<th onclick=tabSort(\"EAR\")>Callsign client</th>\n\r";
    echo "<th>Connected since</th>\n\r";

    if( (IPLIST == "SHOW") OR (IPLIST == "SHOWLONG")) {
        echo "<th>Network address</th>\n\r";
    }

    echo '<th class="state">state</th>'."\n\r";
    
    if( (TG == "SHOW") ) {
    	echo "<th>TG</th>\n\r";
    }

    echo "<th>TX on</th>\n\r";
    echo "<th onclick=tabSort(\"TOP\")>TX off</th>\n\r";

    if( (MON == "SHOW") ) {
    	echo "<th>Monitor TG</th>\n\r";
    }
    echo "</tr>\n\r</thead>\n\r";

    echo "<tbody>\n\r";
    for ($i=0; $i<count($logs, 0); $i++)
    {
        if( ($logs[$i]['CALL'] != "CALL") AND ($logs[$i]['CALL'] != '') ) {
            echo '<tr>';

            if($logs[$i]['CALL'] != 'NEWLOGFILEDATA') {

                if ( ($logs[$i]['STATUS'] === "ONLINE") OR ($logs[$i]['STATUS'] === "TX") ) {
                    echo '<td class="green"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }
                if ($logs[$i]['STATUS'] === "OFFLINE") {
                    echo '<td class="darkgrey"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }
                if ( ($logs[$i]['STATUS'] === "DOUBLE") OR ($logs[$i]['STATUS'] === "DENIED") ){
                    echo '<td class="red"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }
                if ($logs[$i]['STATUS'] === "ALREADY") {
                    echo '<td class="yellow"><div class="tooltip">'.$logs[$i]['CALL'].'<span class="tooltiptext">'.$logs[$i]['COMMENT'].'</span></div></td>';
                }

                echo '<td class="grey">'.$logs[$i]['LOGINOUTTIME'].'</td>';

                if( IPLIST == "SHOW") {
                    echo '<td class="grey">'.explode(":",$logs[$i]['IP'])[0].'</td>';
                }
                if( IPLIST == "SHOWSHORT") {
                    echo '<td class="grey">'.substr($logs[$i]['IP'], 0, 10).'</td>';
                }

                if (preg_match('/TX/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'tx\'></td>';
                }
                if (preg_match('/OFFLINE/i',$logs[$i]['STATUS'])) {
                    echo '<td class="grey"></td>';
                }

                if (preg_match('/ONLINE/i',$logs[$i]['STATUS'])) {
                    if ((preg_match('/'.$logs[$i]['CALL'].'/i' , $lastheard_call)) AND (preg_match('/'.$LASTHEARD.'/i', 'EAR')) ) {
                        echo '<td class="ear"></td>';
                    } else {
                        echo '<td class="grey"></td>';
                    }
                }

                if (preg_match('/DOUBLE/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'double\'></td>';
                }

                if (preg_match('/DENIED/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'denied\'></td>';
                }

                if (preg_match('/ALREADY/i',$logs[$i]['STATUS'])) {
                    echo '<td class=\'grey\'></td>';
                }
		
    		if( (TG == "SHOW") ) {
                    if(preg_match('/TX/i',$logs[$i]['STATUS'])) {
			echo '<td class=\'red\'>'.$logs[$i]['TG'].' '.$tgdb_array[$logs[$i]['TG']].'</td>';
		    } else {
			echo '<td class=\'grey\'>'.$logs[$i]['TG'].'</td>';
		    }
		}

                if(preg_match('/TX/i',$logs[$i]['STATUS'])) {
                    echo '<td class="yellow">'.$logs[$i]['TX_S'].'</td>';
                    echo '<td class="yellow">'.$logs[$i]['TX_E'].'</td>';
                } else {
                    echo '<td class="grey">'.$logs[$i]['TX_S'].'</td>';
                    echo '<td class="grey">'.$logs[$i]['TX_E'].'</td>';
		}

    		if( (MON == "SHOW") ) {
		    echo '<td class="grey">'.$logs[$i]['MON'].'</td>';
		}
                echo "</tr>\n\r";
            } // FIN DU NOUVEAU FICHIER JOURNAL DONNÉES FAUX
 
            // C’est le séparateur entre le tableau "Callsign client" et "SVXReflector-Dashboard"
        }
    }
    
    //Début du séparateur
    echo "<tr><th class='logline' colspan='8'></th></tr>\n\r";
    //Fin du séparateur
    
    if (preg_match('/' . REFRESHSTATUS . '/i', 'SHOW')) {
    
        $local_time = date("H:i:s");
        $utc_time = gmdate("H:i:s");
        $date = date("d M Y");
    
        echo "<tr><th colspan='8'>";
        echo "📅 Date: $date | 🕒 Local Time: $local_time | 🌍 UTC: $utc_time";
        echo "</th></tr>\n\r";
    }

    if (preg_match('/' . LOGFILETABLE . '/i', 'SHOW')) {
        $all_logs = array();
        if (count($LOGFILES, 0) > 0) {
            for ($i = 0; $i < count($LOGFILES); $i++) {
                // Lecture filtrée et ordonnée du log SVXReflector
                $lastlog = getlastlog_svxreflector($LOGFILES[$i], LOGLINECOUNT, null);
                $all_logs = array_merge($all_logs, $lastlog);
            }
        }
        
        // Formatage des logs avec icônes
        $formatted_logs = formatLogWithIcons($all_logs);
        
        
        echo "<tr><th colspan='8'>Reflector Events</th></tr>\n\r";
        echo "<tr><td class='logshow' colspan='8'><pre>" . implode("\n", $formatted_logs) . "</pre></td></tr>";
    }
echo "</tbody>\n\r";
echo "</table></div></main>\n\r";
}
// Affichage de la légende
echo '
<div class="legend-container">
    <div class="legend-box, tex02">
        <u><b>Legend Reflector Events :</b></u><br>
        🟩 : Talker start (TX).<br>
        🟥 : Talker stop.<br>
        🔵 : Login OK, connection successful.<br>
        🟠 : Disconnected.<br>
        🔴 : Login failed.<br>
        🟨 : TG number select.<br>
        🟦 : Monitor TG.<br>
        🟡 : Timeout.<br>
        ❌ : Error.<br>
        ⚠️ : Warning.<br>
        ⚪ : Default (other events).<br>
    </div>';

if( LEGEND == "FR") {
    echo '
    <div class="legend-box tex02">
        <u><b>Légende FR :</b></u><br>
        <img src="/icon/tx.gif"> : Station est actuellement en TX<br>
        <img src="./icon/ear.png"> : Dernière station entendue<br>
        <img src="./icon/double.png"> : Station est déjà en émission<br>
        <img src="./icon/accden.png"> : Accès non autorisé ! Contactez le sysop<br>
    </div>';
    echo '</div>';
}

if ( LEGEND == "FR-I") {
    echo '<div class="legend-box tex02">
        <u><b>Légende FR :</b></u><br>
        <img src="/icon/tx.gif"> : Station est actuellement en TX<br>
        <img src="./icon/ear.png"> : Dernière station entendue<br>
        <img src="./icon/double.png"> : Station est déjà en émission<br>
        <img src="./icon/accden.png"> : Accès non autorisé ! Contactez le sysop<br>
    </div>';
    
    echo '<div class="dtmf-help tex02">
        <u><b>📻 Indications DTMF :</b></u><br>
        <code>9*#</code> : État du TalkGroup.<br>
        <code>91#</code> : Sélectionne le TalkGroup précédent.<br>
        <code>91[TG]#</code> : Sélectionne le Tallgroup (91+Numero du TG suivi par #).<br>
        <code>92#</code> : QSY tous les participants actifs doivent passer au TalkGroup déterminé par le serveur.<br>
        <code>92[TG]#</code> : QSY de tous les participants actifs au TalkGroup <code>[TG]</code> (92+Numero du TG suivi par #).<br>
        <code>93#</code> : Répéter le dernier QSY.<br>
        <code>94[TG]#</code> : Écouter temporairement Tallgroup (94+Numero du TG suivi par #).
    </div>';
    echo '</div><br>';
}

if( LEGEND == "EN") {
    echo '
        <div class="legend-box tex02">
        <u><b>Legend EN :</b></u><br>
        <img src="/icon/tx.gif">Station is currently in TX<br>
        <img src="./icon/ear.png"></center>Last station heard<br>
        <img src="./icon/double.png">Another station is already talking<br>
        <img src="./icon/accden.png">Unauthorized access! Contact sysop<br>
    </div>';
    echo '</div>';
}

if( LEGEND == "EN-I") {
    echo '
        <div class="legend-box tex02">
        <u><b>Legend EN :</b></u><br>
        <img src="/icon/tx.gif">Station is currently in TX<br>
        <img src="./icon/ear.png"></center>Last station heard<br>
        <img src="./icon/double.png">Another station is already talking<br>
        <img src="./icon/accden.png">Unauthorized access! Contact sysop<br>
    </div>';
    
    echo '<div class="dtmf-help tex02">
        <u><b>📻 Indications DTMF :</b></u><br>
        <code>9*#</code> : TalkGroup status.<br>
        <code>91#</code> :  Select previous TalkGroup.<br>
        <code>91[TG]#</code> :  Select Tallgroup (91+TG number followed by #).<br>
        <code>92#</code> :  QSY all active participants should switch to a TalkGroup determined by the server.<br>
        <code>92[TG]#</code> :  QSY of all active participants to <code>[TG]</code> (92+TG number followed by #).<br>
        <code>93#</code> :  Repeat last QSY.<br>
        <code>94[TG]#</code> :  Temporarily listen to (94+TG number followed by #).
    </div>';
    echo '</div><br>';
}

?>
