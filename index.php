<?php
// load the configuration
require_once 'lib/config.php';

// test debug mode
if (SITE_DEBUG === true) {
    // turn on all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// load the global functions
require_once 'lib/preprocess.php';

// load the database connection
require_once 'lib/conn.php';

// load the router
require_once 'app/router/Router.php';
$routes = require 'app/router/Route.php';

// load the controllers
require_once 'app/controllers/ApiController.php';

// load the router
$router = new Router($_GET['url']);
$ApiController = new ApiController();


// import the routes
require_once 'app/router/routes.php';
?>