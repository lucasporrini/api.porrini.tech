<?php

// Inclure les modèles
require_once RELATIVE_PATH_MODELS . '/ApiModel.php';

class ApiController
{
    private $apiModel;

    public function __construct() {
        $this->apiModel = new ApiModel();
    }

    public function github_webhook()
    {
        // Enregistrement du payload dans un fichier
        $payload = file_get_contents('php://input');
        $githubSignature = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : '';

        // On vérifie que le secret est présent dans le fichier .env
        $secret = getenv('GITHUB_SECRET');
        if(!$secret) {
            echo "Le secret n'est pas présent dans le fichier .env";
            return;
        }

        $hash = hash_hmac('sha1', $payload, $secret); // On génère le hash

        if (hash_equals('sha1=' . $hash, $githubSignature)) {
            file_put_contents('./logs/auto/payload.log', 'payload: ' . $payload . ';\nhash: ' . $hash . ';\nsecret: ' . $secret . ';\ngithubSignature: ' . $githubSignature . ';\n', FILE_APPEND);
            // La signature est valide, traiter le payload
            $data = json_decode($payload, true);
            return;
            // Votre logique de traitement ici
        } else {
            // La signature n'est pas valide, rejeter la requête
            file_put_contents('./logs/auto/payload.log', 'payload: ' . $payload . ';\nhash: ' . $hash . ';\nsecret: ' . $secret . ';\ngithubSignature: ' . $githubSignature . ';\n', FILE_APPEND);
            http_response_code(403); // Accès interdit
            die('Signature non valide'); 
        }

        // On verifie que le script est présent dans le dossier "automatic"
        if(!file_exists('./app/auto/autodeploy.sh')) {
            echo "Le script n'est pas présent dans le dossier 'auto'";
            return;
        }

        // On execute le script shell
        $output = shell_exec('./app/auto/autodeploy.sh');

        // On envoie un mail pour confirmer le déploiement
        $to = "2608lucas@gmail.com";
        $subject = "Déploiement du site";
        $message = "Le site a été déployé avec succès";
        $headers = "From: api.deploy@porrini.tech" . "\r\n";
        
        mail($to, $subject, $message, $headers);

        // On retourne le résultat
        echo $output;
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
