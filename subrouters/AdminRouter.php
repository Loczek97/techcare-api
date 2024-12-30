<?php

require_once './controllers/admin_controllers/AdminUsersController.php';
require_once './controllers/admin_controllers/GeneralInformationsController.php';
require_once './controllers/admin_controllers/ServicesController.php';
class AdminRouter
{
    private $AdminUsersController;
    private $GeneralInformationsController;
    private $ServicesController;

    public function __construct()
    {
        $this->AdminUsersController = new AdminUsersController();
        $this->GeneralInformationsController = new GeneralInformationsController();
        $this->ServicesController = new ServicesController();
    }

    public function handleRequest($url_part)
    {
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
        }
    }
}
