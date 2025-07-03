<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property User_model $User_model
 */

class Auth extends CI_Controller
{
    private $key = "SECRETKEY";

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->email) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["message" => "Email dan password wajib diisi"]);
            return;
        }

        $this->load->model('User_model');
        $user = $this->User_model->get_user_by_email($data->email);

        if (!$user) {
            echo json_encode(["debug" => "User tidak ditemukan"]);
            return;
        }

        log_message('error', 'Input password: ' . $data->password);
        log_message('error', 'Stored hash: ' . $user->password);

        $verify = password_verify($data->password, $user->password);
        log_message('error', 'Verify result: ' . ($verify ? 'true' : 'false'));


        log_message('error', 'Password from DB: ' . $user->password);
        if (!password_verify($data->password, $user->password)) {

            echo json_encode(["debug" => "Password salah"]);
            return;
        }

        $payload = [
            "id" => $user->id,
            "email" => $user->email,
            "iat" => time(),
            "exp" => time() + 3600
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');
        echo json_encode(["token" => $token]);
    }
}
