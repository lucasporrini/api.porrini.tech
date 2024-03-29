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
        $secret = $_ENV['GITHUB_SECRET'];
        if(!$secret) { 
            write_log('tracking_deploy', 'Error', 'Le secret n\'est pas présent dans le fichier .env', 'red');
            echo "Le secret n'est pas présent dans le fichier .env";
            return;
        }

        $hash = hash_hmac('sha1', $payload, $secret); // On génère le hash

        if (hash_equals('sha1=' . $hash, $githubSignature)) {
            // On integre un message de validation avec la date et l'heure
            $date = date('d/m/Y H:i:s');
            $data = $date . ' - ' . $payload;

            // On enregistre le payload dans un fichier
            write_log('payload', 'Valid payload', $data, 'green');

            // On verifie que le script est présent dans le dossier "automatic"
            if(!file_exists('./app/auto/autodeploy.sh')) {
                write_log('tracking_deploy', 'Error', 'Le script n\'est pas présent dans le dossier "auto"', 'red');
                echo "Le script n'est pas présent dans le dossier 'auto'";
                return;
            }

            // execution du script
            shell_exec('./app/auto/autodeploy.sh');

            // On récupère les données du dernier commit pour les enregistrer dans un fichier
            $payload = json_decode($payload, true);
            $lastcommit = $payload['head_commit']['id'] . ' - ' . $payload['head_commit']['message'];

            // add the data in tracking_deploy.log
            write_log('tracking_deploy', 'Success', $lastcommit, 'green');

            // On envoie un mail pour confirmer le déploiement
            $to = DEV_MAIL;
            $subject = "Valid - Déploiement du site";
            $message = "Le site a été déployé avec succès\n\nDernier commit: " . $lastcommit;
            $headers = "From: api.deploy@porrini.tech" . "\r\n";
            
            mail($to, $subject, $message, $headers) ? write_log('mail', 'Mail sent', '(' . $date . '): ' . $lastcommit, 'green') : write_log('mail', 'Mail not sent', '(' . $date . '): ' . $lastcommit, 'red');
        } else { 
            // La signature n'est pas valide, rejeter la requête
            $date = date('d/m/Y H:i:s');
            $data = $date . ' - ' . $payload;
            write_log('payload', 'Unvalid payload', $data, 'red');

            // On récupère les données du dernier commit pour les enregistrer dans un fichier
            $payload = json_decode($payload, true);
            $lastcommit = $payload['head_commit']['id'] . ' - ' . $payload['head_commit']['message'];

            // add the data in tracking_deploy.log
            write_log('tracking_deploy', 'Error', $lastcommit, 'red');

            // On envoie un mail d'echec
            $to = DEV_MAIL;
            $subject = "Echec - Déploiement du site"; 
            $message = "Le site n\'a pu être déployé\n\nDernier commit: " . $lastcommit;
            $headers = "From: api.deploy@porrini.tech" . "\r\n";
            
            mail($to, $subject, $message, $headers) ? write_log('mail', 'Mail sent', '(' . $date . '): ' . $lastcommit, 'green') : write_log('mail', 'Mail not sent', '(' . $date . '): ' . $lastcommit, 'red');
        }
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
        // get token from header
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
