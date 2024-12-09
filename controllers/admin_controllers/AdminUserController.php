<?php

require_once './models/admin_models/AdminUserModel.php';

class AdminUserController
{
    private $AdminUserModel;

    public function __construct()
    {
        $this->AdminUserModel = new AdminUserModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->getAllUsers();
                break;
            case 'PUT':
                $this->updateUser();
                break;
            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Metoda nieobsługiwana']);
                break;
        }
    }

    private function getAllUsers()
    {
        $users = $this->AdminUserModel->getAllUsers();

        if ($users) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $users]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się pobrać danych użytkowników']);
        }
    }

    private function updateUser()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['user_id']) || !isset($input['username']) || !isset($input['email']) || !isset($input['permission_ids'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych pól']);
            return;
        }

        $user_id = $input['user_id'];
        $username = $input['username'];
        $email = $input['email'];
        $permission_ids = $input['permission_ids'];

        $user = $this->AdminUserModel->updateUser($user_id, $email, $permission_ids);

        if ($user) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Użytkownik został zaktualizowany', 'data' => $user]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się zaktualizować użytkownika']);
        }
    }
}
