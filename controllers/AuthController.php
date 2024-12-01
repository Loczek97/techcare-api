<?php
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController
{
    private $AuthModel;

    public function __construct()
    {
        $this->AuthModel = new AuthModel();
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['email'], $input['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $email = trim($input['email']);
        $password = $input['password'];

        $userData = $this->AuthModel->login($email, $password);

        if ($userData) {
            $_SESSION['user'] = $userData;
            $_SESSION['session_cookie'] = bin2hex(random_bytes(32));

            error_log("Sesja użytkownika: " . print_r($_SESSION, true));

            setcookie('session_cookie', $_SESSION['session_cookie'], [
                'expires' => time() + 1800,
                'path' => '/',
                'httponly' => true,
                'secure' => false,
                'samesite' => 'Strict'
            ]);

            echo json_encode(['status' => 'success', 'user' => $userData]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowy e-mail lub hasło']);
        }
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['email'], $input['password'], $input['password2'], $input['first_name'], $input['last_name'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $email = $input['email'];
        $password = $input['password'];
        $password2 = $input['password2'];
        $first_name = $input['first_name'];
        $last_name = $input['last_name'];
        $phone = $input['phone'] ?? null;
        $address = $input['address'] ?? null;

        $result = $this->AuthModel->register($email, $password, $password2, $first_name, $last_name, $phone, $address);

        if ($result['status'] === 'success') {
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
    }

    public function handleLogout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
            return;
        }

        // Usunięcie sesji i ciasteczka
        session_destroy();
        setcookie('session_cookie', '', time() - 3600, '/');
        echo json_encode(['status' => 'success', 'message' => 'Pomyślnie wylogowano']);
    }
}
