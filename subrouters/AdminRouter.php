<?php

require_once './controllers/admin_controllers/AdminUserController.php';
require_once './controllers/admin_controllers/GeneralInformationsController.php';
class AdminRouter
{
    private $AdminUserController;
    private $GeneralInformationsController;

    public function __construct()
    {
        $this->AdminUserController = new AdminUserController();
        $this->GeneralInformationsController = new GeneralInformationsController();
    }

    public function handleRequest($url_part)
    {
        switch ($url_part) {
            case 'users':
                $this->AdminUserController->handleRequest();
                break;
            case "general_informations":
                $this->GeneralInformationsController->handleRequest();
                break;
            default:
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Endpoint nie znaleziony']);
                break;
        }
    }
}
