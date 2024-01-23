<?php
// Routes de l'application

// Route pour afficher les pages du site
//$router->method('url', [$MainController, 'method_name'], $requireAuth=false, $composedUrl = false);
$router->get('/', [$ApiController, 'index']);
$router->get('/404', [$ApiController, 'error']);

$router->get('/get_nav', [$ApiController, 'get_nav']);
$router->get('/get_chatbot_messages', [$ApiController, 'get_chatbot_messages']);
$router->get('/get_chatbot_response_to_message/:id', [$ApiController, 'get_chatbot_response_to_message']);
$router->get('/get_chatbot_buttons', [$ApiController, 'get_chatbot_buttons']);

$router->run();