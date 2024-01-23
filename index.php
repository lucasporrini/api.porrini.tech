<?php
// charger la configuration
require_once 'lib/config.php';

// Tester le mode debug
if (SITE_DEBUG === true) {
    // On active les erreurs PHP
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// charger la connexion à la base de données
require_once 'lib/conn.php';

// charger le router
require_once 'app/router/Router.php';

// charger les routes
$routes = require 'app/router/Route.php';

// charger les Controllers
require_once 'app/controllers/ApiController.php';

// charger les models
$router = new Router($_GET['url']);
$ApiController = new ApiController();


// Importation des routes
require_once 'app/router/routes.php';
?>