<?php
/**
 * Parse a log line and extract relevant information
 * @param string $line The log line to parse
 * @return array|null Parsed data or null if line doesn't match pattern
 */
function parseLogLine($line) {
    // Pattern to match: DD.MM.YYYY HH:MM:SS: message
    if (!preg_match('/^(\d{2}\.\d{2}\.\d{4})\s+(\d{2}:\d{2}:\d{2}):\s+(.+)$/', $line, $matches)) {
        return null;
    }
    
    $date = $matches[1];
    $time = $matches[2];
    $message = $matches[3];
    
    $parsed = [
        'date' => $date,
        'time' => $time,
        'datetime' => $date . ' ' . $time,
        'message' => $message,
        'indicatif' => '',
        'tg' => '',
        'action' => '',
        'ip' => ''
    ];
    
    // Extract indicatif (callsign before -)
    if (preg_match('/([A-Z0-9_]+)-[A-Z0-9]+/', $message, $callMatches)) {
        $parsed['indicatif'] = $callMatches[1];
        $parsed['indicatif_complet'] = $callMatches[0]; // Stocker aussi l'indicatif complet
    } elseif (preg_match('/([A-Z0-9_]+)/', $message, $callMatches)) {
        // Au cas où il n'y a pas de tiret
        $parsed['indicatif'] = $callMatches[1];
        $parsed['indicatif_complet'] = $callMatches[1];
    }
    
    // Extract TG number
    if (preg_match('/TG\s*#?(\d+)/', $message, $tgMatches)) {
        $parsed['tg'] = $tgMatches[1];
    }
    
    // Extract IP address  
    if (preg_match('/(\d+\.\d+\.\d+\.\d+)/', $message, $ipMatches)) {
        $parsed['ip'] = $ipMatches[1];
    }
    
    // Determine action type - ORDRE IMPORTANT : vérifier "disconnected" AVANT "connected"
    if (strpos($message, 'Login OK') !== false) {
        $parsed['action'] = 'Login OK';
    } elseif (strpos($message, 'Talker start') !== false) {
        $parsed['action'] = 'Talker start';
    } elseif (strpos($message, 'Talker stop') !== false) {
        $parsed['action'] = 'Talker stop';
    } elseif (strpos($message, 'disconnected') !== false) {
        // IMPORTANT: Vérifier "disconnected" AVANT "connected"
        $parsed['action'] = 'Disconnected';
    } elseif (strpos($message, 'connected') !== false) {
        $parsed['action'] = 'Connected';
    } elseif (strpos($message, 'Select TG') !== false) {
        $parsed['action'] = 'Select TG';
    } elseif (strpos($message, 'Monitor TG') !== false) {
        $parsed['action'] = 'Monitor TG';
    } elseif (strpos($message, 'WARNING') !== false) {
        $parsed['action'] = 'Warning';
    }  elseif (strpos($message, 'timeout') !== false) {
        $parsed['action'] = 'Timeout';
    }else {
        $parsed['action'] = 'Other';
    }
    
    return $parsed;
}

/**
 * Read and parse log files
 * @param array $logFiles Array of log file paths
 * @param int $maxLines Maximum number of lines to return (0 = no limit)
 * @return array Array of parsed log entries
 */
function readLogFiles($logFiles, $maxLines = 0) {
    $entries = [];
    
    foreach ($logFiles as $logFile) {
        if (!file_exists($logFile)) {
            continue;
        }
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            continue;
        }
        
        foreach ($lines as $line) {
            $parsed = parseLogLine($line);
            if ($parsed) {
                $entries[] = $parsed;
            }
        }
    }
    
    // Sort by datetime (newest first)
    usort($entries, function($a, $b) {
        $dateA = DateTime::createFromFormat('d.m.Y H:i:s', $a['datetime']);
        $dateB = DateTime::createFromFormat('d.m.Y H:i:s', $b['datetime']);
        return $dateB <=> $dateA;
    });
    
    return $maxLines > 0 ? array_slice($entries, 0, $maxLines) : $entries;
}

/**
 * Filter log entries based on search criteria
 * @param array $entries Array of log entries
 * @param array $filters Array of filter criteria
 * @return array Filtered entries
 */
function filterLogEntries($entries, $filters) {
    if (empty($filters)) {
        return $entries;
    }
    
    return array_filter($entries, function($entry) use ($filters) {
        foreach ($filters as $field => $value) {
            if (empty($value)) {
                continue;
            }
            
            switch ($field) {
                case 'action':
                    // Traitement spécial pour "connected" et "disconnected" dans le champ action
                    if (strtolower($value) === 'connected') {
                        // Recherche exacte de "connected" qui n'est PAS "disconnected"
                        if (!(stripos($entry['action'], 'connected') !== false && stripos($entry['action'], 'disconnected') === false)) {
                            return false;
                        }
                    }
                    elseif (strtolower($value) === 'disconnected') {
                        // Recherche de "disconnected"
                        if (stripos($entry['action'], 'disconnected') === false) {
                            return false;
                        }
                    }
                    else {
                        // Recherche normale pour tous les autres termes
                        if (stripos($entry['action'], $value) === false) {
                            return false;
                        }
                    }
                    break;
                case 'message':
                    // Recherche générale : chercher dans message, date, heure, indicatif, TG
                    $searchFound = false;
                    
                    // D'abord vérifier si c'est un pattern spécifique d'heure
                    $isTimePattern = false;
                    
                    // Pattern HH:MM (comme 10:30)
                    if (preg_match('/^(\d{1,2}):(\d{1,2})$/', $value, $timeMatches)) {
                        $isTimePattern = true;
                        $hour = str_pad($timeMatches[1], 2, '0', STR_PAD_LEFT);
                        $minute = str_pad($timeMatches[2], 2, '0', STR_PAD_LEFT);
                        $timePattern = $hour . ':' . $minute;
                        
                        // Vérifier que l'heure commence exactement par ce pattern
                        if (substr($entry['time'], 0, 5) === $timePattern) {
                            $searchFound = true;
                        }
                    }
                    // Pattern HH: (comme 10:)
                    elseif (preg_match('/^(\d{1,2}):$/', $value, $timeMatches)) {
                        $isTimePattern = true;
                        $hour = str_pad($timeMatches[1], 2, '0', STR_PAD_LEFT);
                        
                        // Vérifier que l'heure commence exactement par HH:
                        if (substr($entry['time'], 0, 3) === $hour . ':') {
                            $searchFound = true;
                        }
                    }
                    
                    // D'abord vérifier si c'est un pattern spécifique de date
                    $isDatePattern = false;
                    
                    // Pattern DD.MM (comme 23.05)
                    if (!$isTimePattern && preg_match('/^(\d{1,2})\.(\d{1,2})$/', $value, $dateMatches)) {
                        $isDatePattern = true;
                        $day = str_pad($dateMatches[1], 2, '0', STR_PAD_LEFT);
                        $month = str_pad($dateMatches[2], 2, '0', STR_PAD_LEFT);
                        $datePattern = $day . '.' . $month;
                        
                        // Vérifier que la date commence exactement par ce pattern
                        if (substr($entry['date'], 0, 5) === $datePattern) {
                            $searchFound = true;
                        }
                    }
                    // Pattern DD. (comme 23.)
                    elseif (!$isTimePattern && preg_match('/^(\d{1,2})\.$/', $value, $dateMatches)) {
                        $isDatePattern = true;
                        $day = str_pad($dateMatches[1], 2, '0', STR_PAD_LEFT);
                        
                        // Vérifier que la date commence exactement par DD.
                        if (substr($entry['date'], 0, 3) === $day . '.') {
                            $searchFound = true;
                        }
                    }
                    
                    // Si ce n'est ni un pattern d'heure ni un pattern de date, faire la recherche normale
                    if (!$isTimePattern && !$isDatePattern && !$searchFound) {
                        // Traitement spécial pour "connected" et "disconnected" pour éviter les faux positifs
                        if (strtolower($value) === 'connected') {
                            // Recherche exacte de "connected" qui n'est PAS "disconnected"
                            if (stripos($entry['message'], 'connected') !== false && stripos($entry['message'], 'disconnected') === false) {
                                $searchFound = true;
                            }
                            // Vérifier aussi dans l'action
                            elseif (stripos($entry['action'], 'connected') !== false && stripos($entry['action'], 'disconnected') === false) {
                                $searchFound = true;
                            }
                        }
                        elseif (strtolower($value) === 'disconnected') {
                            // Recherche de "disconnected"
                            if (stripos($entry['message'], 'disconnected') !== false) {
                                $searchFound = true;
                            }
                            // Vérifier aussi dans l'action
                            elseif (stripos($entry['action'], 'disconnected') !== false) {
                                $searchFound = true;
                            }
                        }
                        else {
                            // Recherche normale pour tous les autres termes
                            // Chercher dans le message
                            if (stripos($entry['message'], $value) !== false) {
                                $searchFound = true;
                            }
                            // Chercher dans la date (recherche normale)
                            elseif (stripos($entry['date'], $value) !== false) {
                                $searchFound = true;
                            }
                            // Chercher dans l'heure (recherche normale)
                            elseif (stripos($entry['time'], $value) !== false) {
                                $searchFound = true;
                            }
                            // Chercher dans l'indicatif
                            elseif (!empty($entry['indicatif']) && stripos($entry['indicatif'], $value) !== false) {
                                $searchFound = true;
                            }
                            // Chercher aussi dans la partie complète de l'indicatif (partie avant le -)
                            elseif (!empty($entry['message'])) {
                                // Extraire l'indicatif complet du message (avec la partie après le -)
                                if (preg_match('/([A-Z0-9_]+(?:-[A-Z0-9]+)?)/', $entry['message'], $fullCallMatches)) {
                                    $fullIndicatif = $fullCallMatches[1];
                                    if (stripos($fullIndicatif, $value) !== false) {
                                        $searchFound = true;
                                    }
                                }
                            }
                            // Chercher dans le TG
                            elseif (!empty($entry['tg']) && stripos($entry['tg'], $value) !== false) {
                                $searchFound = true;
                            }
                            // Chercher dans l'action
                            elseif (stripos($entry['action'], $value) !== false) {
                                $searchFound = true;
                            }
                        }
                    }
                    
                    if (!$searchFound) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    });
}

/**
 * Paginate results
 * @param array $entries Array of entries
 * @param int $page Current page number (1-based)
 * @param int $perPage Items per page
 * @return array Paginated data
 */
function paginateEntries($entries, $page = 1, $perPage = 20) {
    $totalEntries = count($entries);
    $totalPages = ceil($totalEntries / $perPage);
    $offset = ($page - 1) * $perPage;
    
    return [
        'entries' => array_slice($entries, $offset, $perPage),
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalEntries' => $totalEntries,
        'perPage' => $perPage
    ];
}

/**
 * Generate pagination HTML
 * @param array $paginationData Pagination data from paginateEntries
 * @return string HTML for pagination
 */
function generatePagination($paginationData) {
    $currentPage = $paginationData['currentPage'];
    $totalPages = $paginationData['totalPages'];
    
    if ($totalPages <= 1) {
        return '';
    }
    
    // Conserver les paramètres de recherche actuels
    $currentParams = $_GET;
    unset($currentParams['page']); // Retirer le paramètre page pour le reconstruire
    $queryString = http_build_query($currentParams);
    $queryString = $queryString ? '&' . $queryString : '';
    
    $html = '<div class="pagination">';
    
    // Previous button
    if ($currentPage > 1) {
        $html .= '<a href="?page=' . ($currentPage - 1) . $queryString . '" class="page-btn">‹ Précédent</a>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= '<a href="?page=1' . $queryString . '" class="page-btn">1</a>';
        if ($start > 2) {
            $html .= '<span class="page-dots">...</span>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $class = ($i == $currentPage) ? 'page-btn active' : 'page-btn';
        $html .= '<a href="?page=' . $i . $queryString . '" class="' . $class . '">' . $i . '</a>';
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<span class="page-dots">...</span>';
        }
        $html .= '<a href="?page=' . $totalPages . $queryString . '" class="page-btn">' . $totalPages . '</a>';
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<a href="?page=' . ($currentPage + 1) . $queryString . '" class="page-btn">Suivant ›</a>';
    }
    
    $html .= '</div>';
    
    return $html;
}
?>