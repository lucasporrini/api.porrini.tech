<?php

require_once RELATIVE_PATH_LIB . 'config.php';
require_once RELATIVE_PATH_LIB . 'Database.php';

class ApiModel
{
    private $db;
    
    public function __construct()
    {
        global $_CONFIG;
        global $conn;
        $this->db = $conn;
    }

    public function verify_token($token)
    {
        return $this->db->simpleSelect('*', 'token', ['value' => $token]);
    }

    public function middleware_auth($token)
    {
        // On vérifie que le token existe
        if(!isset($token)) {
            write_log('api', 'Error', 'Vous devez être connecté pour accéder à cette page', 'red');
            http_response_code(401);
            echo json_encode(['error' => 'Vous devez être connecté pour accéder à cette page']);
            return false;
        }

        // On vérifie que le token est bien un bearer token
        if(isset($token)) {
            $pattern = '/Bearer\s(\S+)/';

            if(preg_match($pattern, $token, $matches)) {
                write_log('api', 'Success', 'L\'authentification a réussi', 'green');
                $token = $matches[1];
            } else {
                write_log('api', 'Error', 'L\'authentification a échoué', 'red');
                http_response_code(401);
                echo json_encode(['error' => 'L\'authentification a échoué']);
                return false;
            }
        }

        // On vérifie que le token est valide
        $token_in_db = $this->verify_token($token);
        if(!$token_in_db) {
            write_log('api', 'Error', 'Le token n\'est pas renseigné ou n\'est pas valide', 'red');
            http_response_code(401);
            echo json_encode(['error' => 'Le token n\'est pas renseigné ou n\'est pas valide']);
            return false;
        }
        
        if($token_in_db['value'] !== $token) {
            write_log('api', 'Error', 'L\'authentification a échoué', 'red');
            http_response_code(401);
            echo json_encode(['error' => 'L\'authentification a échoué']);
            return false;
        }

        write_log('api', 'Success', 'L\'authentification a réussi', 'green');
        
        return true;
    }
    
    public function get_nav()
    {
        return $this->db->select('*', 'nav', ['active' => '1']);
    }

    public function get_chatbot_messages()
    {
        return $this->db->select('*', 'chatbot', ['chatbot_bloc' => '1']);
    }

    public function get_chatbot_buttons()
    {
        return $this->db->select('*', 'chatbot', ['chatbot_type' => 'chatbot_button']);
    }

    public function chatbot_response_to_message($id)
    {
        return $this->db->select('*', 'chatbot', ['chatbot_type' => 'chatbot_response', 'chatbot_bloc' => '2', 'chatbot_parent_id' => $id]);
    }
}