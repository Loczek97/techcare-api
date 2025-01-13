<?php

require_once './controllers/admin_controllers/AdminUsersController.php';
require_once './controllers/admin_controllers/GeneralInformationsController.php';
require_once './controllers/admin_controllers/ServicesController.php';
require_once './controllers/admin_controllers/PermissionController.php';

class AdminRouter
{
    private $AdminUsersController;
    private $GeneralInformationsController;
    private $ServicesController;
    private $PermissionController;

    public function __construct()
    {
        $this->AdminUsersController = new AdminUsersController();
        $this->GeneralInformationsController = new GeneralInformationsController();
        $this->ServicesController = new ServicesController();
        $this->PermissionController = new PermissionController();
    }

    public function handleRequest($url_part)
    {
        if (CheckUserPermission(5)) {
            switch ($url_part) {
                case 'users':
                    $this->AdminUsersController->handleRequest();
                    break;
                case 'general-informations':
                    $this->GeneralInformationsController->handleRequest();
                    break;
                case 'services':
                    $this->ServicesController->handleRequest();
                    break;
                case 'permissions':
                    $this->PermissionController->handleRequest();
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Nieprawidłowy URL"]);
                    break;
            }
        } else {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "Brak uprawnień do tej sekcji"]);
        }
    }
}
