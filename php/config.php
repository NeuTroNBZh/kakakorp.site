<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'kakakorp');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration des emails
define('EMAIL_FROM', 'contact@kakakorp.site');
define('EMAIL_REPLY_TO', 'contact@kakakorp.site');
define('EMAIL_SUBJECT_PREFIX', 'KAKAKORP Esports - ');

// Configuration du site
define('SITE_NAME', 'KAKAKORP Esports');
define('SITE_URL', 'https://kakakorp.site');
define('SITE_DESCRIPTION', 'Une équipe eSport dynamique et talentueuse');

// Configuration des chemins
define('ROOT_PATH', dirname(__DIR__));
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('PHP_PATH', ROOT_PATH . '/php');

// Configuration de la sécurité
define('SESSION_LIFETIME', 3600); // 1 heure
define('CSRF_TOKEN_LIFETIME', 3600); // 1 heure
define('PASSWORD_MIN_LENGTH', 8);

// Configuration des logs
define('LOG_PATH', ROOT_PATH . '/logs');
define('LOG_LEVEL', 'DEBUG'); // DEBUG, INFO, WARNING, ERROR

// Fonction de connexion à la base de données
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        // Log l'erreur
        error_log("Erreur de connexion à la base de données: " . $e->getMessage());
        return null;
    }
}

// Fonction de log
function logMessage($level, $message, $context = []) {
    if (!file_exists(LOG_PATH)) {
        mkdir(LOG_PATH, 0777, true);
    }

    $logFile = LOG_PATH . '/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$level] $message " . json_encode($context) . PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
?> 