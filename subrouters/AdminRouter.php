<?php

require_once './controllers/admin_controllers/AdminUserController.php';
class AdminRouter
{
    private $AdminUserController;

    public function __construct()
    {
        $this->AdminUserController = new AdminUserController();
    }

    public function handleRequest($url_part)
    {
        switch ($url_part) {
            case 'users':
                $this->AdminUserController->handleRequest();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Endpoint nie znaleziony']);
                break;
        }
    }
}
