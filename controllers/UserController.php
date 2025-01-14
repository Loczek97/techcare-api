<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->getUser();
                break;
            case 'PUT':
                $this->updateUser();
                break;
            case 'DELETE':
                $this->deleteUser();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
        }
    }

    private function getUser()
    {
        $result = $this->UserModel->getUser($_SESSION['user']['user_id']);
        echo json_encode($result);
    }

    private function updateUser()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $currentUser = $_SESSION['user'];

        $email = isset($input['email']) ? trim($input['email']) : $currentUser['email'];
        $phone = isset($input['phone']) ? trim($input['phone']) : $currentUser['phone'];
        $first_name = isset($input['first_name']) ? trim($input['first_name']) : $currentUser['first_name'];
        $last_name = isset($input['last_name']) ? trim($input['last_name']) : $currentUser['last_name'];
        $address = isset($input['address']) ? trim($input['address']) : $currentUser['address'];
        $current_password = isset($input['current_password']) ? trim($input['current_password']) : null;
        $password = isset($input['password']) ? trim($input['password']) : null;
        $password2 = isset($input['password2']) ? trim($input['password2']) : null;

        $result = $this->UserModel->updateUser(
            $currentUser['user_id'],
            $email,
            $phone,
            $first_name,
            $last_name,
            $address,
            $current_password,
            $password,
            $password2
        );

        echo json_encode($result);
    }


    private function deleteUser()
    {
        $result = $this->UserModel->deleteUser($_SESSION['user']['user_id']);
        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Użytkownik usunięty' : 'Błąd podczas usuwania użytkownika'
        ]);
    }
}
