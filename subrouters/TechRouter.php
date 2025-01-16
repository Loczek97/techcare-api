<?php

require_once './controllers/tech_controllers/PartsController.php';
require_once './controllers/tech_controllers/TechOrdersController.php';
require_once './controllers/tech_controllers/TechReviewsController.php';
require_once './controllers/tech_controllers/AssignPartsToOrder.php';
require_once './controllers/tech_controllers/OrderServicesController.php';
require_once './controllers/tech_controllers/TechComplaintsController.php';
require_once './controllers/tech_controllers/InvoicesController.php';

class TechRouter
{
    private $PartsController;
    private $TechOrdersController;
    private $TechReviewsController;
    private $AssignPartsToOrderController;
    private $OrderServicesController;
    private $TechComplaintsController;
    private $InvoicesController;

    public function __construct()
    {
        $this->PartsController = new PartsController();
        $this->TechOrdersController = new TechOrdersController();
        $this->TechReviewsController = new TechReviewsController();
        $this->AssignPartsToOrderController = new AssignPartsToOrderController();
        $this->OrderServicesController = new OrderServicesController();
        $this->TechComplaintsController = new TechComplaintsController();
        $this->InvoicesController = new InvoicesController();
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
            case 'reviews':
                $this->TechReviewsController->handleRequest();
                break;
            case 'assign-parts':
                $this->AssignPartsToOrderController->handleRequest();
                break;
            case 'order-services':
                $this->OrderServicesController->handleRequest();
                break;
            case 'invoices':
                $this->InvoicesController->handleRequest();
                break;
            default:
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Nieprawidłowy URL"]);
                break;
        }
    }
}
