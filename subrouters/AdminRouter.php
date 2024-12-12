<?php

require_once './controllers/admin_controllers/AdminUsersController.php';
require_once './controllers/admin_controllers/GeneralInformationsController.php';
class AdminRouter
{
    private $AdminUsersController;
    private $GeneralInformationsController;

    public function __construct()
    {
        $this->AdminUsersController = new AdminUsersController();
        $this->GeneralInformationsController = new GeneralInformationsController();
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
        }
    }
}
