<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property User_model $User_model
 */

class User extends CI_Controller
{
    private $key = "SECRETKEY";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            show_error('Unauthorized', 401);
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            JWT::decode($token, new Key($this->key, 'HS256'));
        } catch (Exception $e) {
            show_error('Invalid Token', 401);
        }
    }

    public function index()
    {
        echo json_encode($this->User_model->get_all_users());
    }

    public function show($id)
    {
        echo json_encode($this->User_model->get_user($id));
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->User_model->insert_user($data);
        echo json_encode(["message" => "User created"]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->User_model->update_user($id, $data);
        echo json_encode(["message" => "User updated"]);
    }

    public function delete($id)
    {
        $this->User_model->delete_user($id);
        echo json_encode(["message" => "User deleted"]);
    }
}
