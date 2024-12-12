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

        // Użycie explode do parsowania URI
        $uriSegments = explode("/", $_SERVER['REQUEST_URI']);

        // Obsługuje różne metody HTTP
        switch ($method) {
            case 'GET':
                $this->getAllUsers();  // Pobiera wszystkich użytkowników
                break;
            case 'PUT':
                $this->editPermissions();  // Obsługuje PUT do edycji uprawnień
                break;
            default:
                http_response_code(405);  // Błąd 405 dla nieobsługiwanej metody
                echo json_encode(['status' => 'error', 'message' => 'Metoda nieobsługiwana']);
                break;
        }
    }

    // Endpoint do pobrania wszystkich użytkowników
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

    // Endpoint do edycji uprawnień użytkownika
    private function editPermissions()
    {
        // Dekodowanie danych JSON
        $input = json_decode(file_get_contents('php://input'), true);

        // Sprawdzamy, czy mamy dane akcji
        $action = isset($input['action']) ? $input['action'] : null;

        if (!$action) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Brak pola action']);
            return;
        }

        $result = null;

        // Obsługuje różne akcje: add, edit, remove
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
                // Upewnij się, że przekazano wszystkie dane
                if (!isset($input['user_id']) || !isset($input['current_permission_id']) || !isset($input['new_permission_id'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych pól dla akcji edit']);
                    return;
                }

                $user_id = $input['user_id'];
                $current_permission_id = $input['current_permission_id'];
                $new_permission_id = $input['new_permission_id'];
                $result = $this->AdminUsersModel->editPermissionsForUser($user_id, $current_permission_id, $new_permission_id);
                break;

            case 'remove':
                if (!isset($input['user_id']) || !isset($input['permission_id'])) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych pól dla akcji remove']);
                    return;
                }

                $user_id = $input['user_id'];
                $permission_id = $input['permission_id'];
                $result = $this->AdminUsersModel->deletePermissionFromUser($user_id, $permission_id);
                break;

            default:
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Nieznana akcja']);
                return;
        }

        // Sprawdzamy wynik operacji
        if ($result) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Operacja zakończona sukcesem']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Wystąpił błąd podczas operacji', 'details' => $result]);
        }
    }
}
