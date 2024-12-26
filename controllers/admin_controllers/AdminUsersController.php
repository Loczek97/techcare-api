<?php

require_once './models/admin_models/AdminUsersModel.php';

class AdminUsersController
{
    private $AdminUsersModel;

    public function __construct()
    {
        $this->AdminUsersModel = new AdminUsersModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->getAllUsers();
                break;
            case 'PUT':
                $this->editPermissions();
                break;
            case 'DELETE':
                $this->deleteUser();
                break;
            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Metoda nieobsługiwana']);
                break;
        }
    }

    private function getAllUsers()
    {
        $users = $this->AdminUsersModel->getAllUsers();

        if ($users) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'data' => $users]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się pobrać danych użytkowników']);
        }
    }

    private function deleteUser()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $user_id = $input['user_id'];

        $result = $this->AdminUsersModel->deleteUser($user_id);

        if ($result) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Użytkownik został usunięty']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się usunąć użytkownika']);
        }
    }

    private function editPermissions()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $action = isset($input['action']) ? $input['action'] : null;

        if (!$action) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Brak pola action']);
            return;
        }

        $result = null;

        switch ($action) {
            case 'add':
                if (!isset($input['user_id']) || !isset($input['permission_id'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych pól dla akcji add']);
                    return;
                }

                $user_id = $input['user_id'];
                $permission_id = $input['permission_id'];
                $result = $this->AdminUsersModel->addPermissionToUser($user_id, $permission_id);
                break;

            case 'edit':
                if (!isset($input['user_id']) || !isset($input['current_permission_id']) || !isset($input['new_permission_id'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych pól dla akcji edit']);
                    return;
                }

                $user_id = $input['user_id'];
                $current_permission_id = $input['current_permission_id'];
                $new_permission_id = $input['new_permission_id'];
                $email = $input['email'] ?? null;
                $phone = $input['phone'] ?? null;
                $address = $input['address'] ?? null;
                $password = $input['password'] ?? null;

                $result = $this->AdminUsersModel->editUser(
                    $user_id,
                    $current_permission_id,
                    $new_permission_id,
                    $email,
                    $phone,
                    $address,
                    $password
                );

                echo json_encode([
                    'status' => $result ? 'success' : 'error',
                    'message' => $result ? 'Dane użytkownika zostały zaktualizowane!' : 'Wystąpił błąd podczas aktualizacji!',
                    'data' => $result
                ]);
                break;
            default:
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Nieznana akcja']);
                return;
        }
    }
}
