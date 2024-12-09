<?php

require_once './controllers/tech_controllers/PartsController.php';
require_once './controllers/tech_controllers/TechOrdersController.php';
class TechRouter
{
    private $PartsController;
    private $TechOrdersController;

    public function __construct()
    {
        $this->PartsController = new PartsController();
        $this->TechOrdersController = new TechOrdersController();
    }

    public function handleRequest($url_part)
    {
        switch ($url_part) {
            case 'parts':
                $this->PartsController->handleRequest();
                break;
            case 'orders':
                $this->TechOrdersController->handleRequest();
                break;
            default:
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Endpoint nie znaleziony']);
                break;
        }
    }
}
