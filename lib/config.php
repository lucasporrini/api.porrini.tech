<?php
//Configuration du site
define('SITE_NAME',                     "api.porrini");
define('SITE_URL_NAME',                 "api.porrini.tech");
define('SITE_URL',                      "https://www." . SITE_URL_NAME . "/");
define('SITE_LOGO',                     "logo.png");
define('SITE_HEBERGEUR',                "ovh.fr");
define('SITE_DEBUG',                    true); // Mettre à "false" en production

// Configuration CORS (Cross Origin Resource Sharing)
header("Access-Control-Allow-Origin: https://portfolio.porrini.tech"); // Autoriser les requêtes de cette origine
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Configuration des relatives paths
define('DS',                            DIRECTORY_SEPARATOR);
define('BASE_URL',                      dirname(__DIR__) . DS);
define('RELATIVE_PATH_PUBLIC',          BASE_URL . 'public' . DS);
define('RELATIVE_PATH_APP',             BASE_URL . 'app' . DS);
define('RELATIVE_PATH_LIB',             BASE_URL . DS . 'lib' . DS);

// Configuration des relatives paths "app"
define('RELATIVE_PATH_VIEWS',           RELATIVE_PATH_APP . 'views' . DS);
define('RELATIVE_PATH_MODELS',          RELATIVE_PATH_APP . 'models' . DS);
define('RELATIVE_PATH_CONTROLLERS',     RELATIVE_PATH_APP . 'controllers' . DS);
define('RELATIVE_PATH_ROUTER',          RELATIVE_PATH_APP . 'router' . DS);
define('RELATIVE_PATH_TEMPLATE',        RELATIVE_PATH_APP . 'template' . DS);

// Configuration des relatives paths "assets"
define('RELATIVE_PATH_ASSETS',          RELATIVE_PATH_PUBLIC . 'assets' . DS);
define('RELATIVE_PATH_CSS',             RELATIVE_PATH_ASSETS . 'css' . DS);
define('RELATIVE_PATH_JS',              RELATIVE_PATH_ASSETS . 'js' . DS);
define('RELATIVE_PATH_IMG',             RELATIVE_PATH_ASSETS . 'img' . DS);
define('RELATIVE_PATH_ICONS',           RELATIVE_PATH_ASSETS . 'icons' . DS);
define('RELATIVE_PATH_FONTS',           RELATIVE_PATH_ASSETS . 'fonts' . DS);
define('RELATIVE_PATH_UPLOADS',         RELATIVE_PATH_ASSETS . 'uploads' . DS);

// Configuration des relatives paths "partials"
define('RELATIVE_PATH_PARTIALS',        RELATIVE_PATH_PUBLIC . 'partials' . DS);

// Configuration des relatives paths "functions"
define('RELATIVE_PATH_FUNCTIONS',       RELATIVE_PATH_PUBLIC . 'functions' . DS);

// Lancement de la SESSION
session_start();

// Importer données du .env;
$dotEnv = fopen('.env', 'r') or die("Unable to open file!");

// Parcourir le fichier .env
while(!feof($dotEnv)) {
    $line = fgets($dotEnv);
    if (trim($line) != '') {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        putenv("$key=$value");
    }
}

fclose($dotEnv);

// Configuration de la connexion à la base de données
$_CONFIG['db'] =        array(
    'host' =>       getenv('DB_HOST'),
    'name' =>       getenv('DB_NAME'),
    'user' =>       getenv('DB_USER'),
    'pass' =>       getenv('DB_PASS')
);