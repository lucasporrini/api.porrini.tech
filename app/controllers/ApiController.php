<?php

// Inclure les modèles
require_once RELATIVE_PATH_MODELS . '/ApiModel.php';

class ApiController
{
    private $apiModel;

    public function __construct() {
        $this->apiModel = new ApiModel();
    }

    public function index()
    {
        // Retourner une page d'accueil
        echo "Bienvenue sur l'API de Porrini Lucas";
    }

    public function error()
    {
        // Retourner une page d'accueil
        echo "Page d'erreur";
    }

    public function get_nav()
    {
        // On récupère le token dans le header
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        if($this->apiModel->middleware_auth($token)) {
            // Récupérer les données
            $data = $this->apiModel->get_nav();

            // On trie les données en fonction de $data['nav_position']
            usort($data, function($a, $b) {
                return $a['nav_position'] <=> $b['nav_position'];
            });

            // Retourner les données en json
            header('Content-Type: application/json');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }

    public function get_chatbot_buttons()
    {
        // On récupère le token dans le header
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        if($this->apiModel->middleware_auth($token)) {
            // Récupérer les données
        $data = $this->apiModel->get_chatbot_buttons();

        // Retourner les données en json
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }

    public function get_chatbot_messages()
    {
        // On récupère le token dans le header
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        if($this->apiModel->middleware_auth($token)) {
            // Récupérer les données
            $data = $this->apiModel->get_chatbot_messages();

            // On trie les données en fonction de $data['nav_position']
            usort($data, function($a, $b) {
                return $a['chatbot_position'] <=> $b['chatbot_position'];
            });

            // Retourner les données en json
            header('Content-Type: application/json');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }

    public function get_chatbot_response_to_message($id)
    {
        // On récupère le token dans le header
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        if($this->apiModel->middleware_auth($token)) {
            // Récupérer les données
            $data = $this->apiModel->chatbot_response_to_message($id);

            // Retourner les données en json
            header('Content-Type: application/json');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
}
