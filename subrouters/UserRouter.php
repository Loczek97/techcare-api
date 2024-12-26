<?php

require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../controllers/ComplaintController.php';
require_once __DIR__ . '/../controllers/ReviewController.php';

class UserRouter
{
    private $UserController;
    private $OrderController;
    private $ComplaintController;
    private $ReviewController;

    public function __construct()
    {
        $this->UserController = new UserController();
        $this->OrderController = new OrderController();
        $this->ComplaintController = new ComplaintController();
        $this->ReviewController = new ReviewController();
    }

    public function handleRequest($url_part)
    {
        switch ($url_part) {
            case ('user'):
                $this->UserController->handleRequest();
                break;
            case ('order'):
                $this->OrderController->handleRequest();
                break;
            case ('complaint'):
                $this->ComplaintController->handleRequest();
                break;
            case ('review'):
                $this->ReviewController->handleRequest();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Endpoint nie znaleziony']);
        }
    }
}
