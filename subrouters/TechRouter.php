<?php

require_once './controllers/tech_controllers/PartsController.php';
require_once './controllers/tech_controllers/TechOrdersController.php';
require_once './controllers/tech_controllers/TechReviewsController.php';
require_once './controllers/tech_controllers/AssignPartsToOrder.php';
class TechRouter
{
    private $PartsController;
    private $TechOrdersController;
    private $TechReviewsController;
    private $AssignPartsToOrderController;

    public function __construct()
    {
        $this->PartsController = new PartsController();
        $this->TechOrdersController = new TechOrdersController();
        $this->TechReviewsController = new TechReviewsController();
        $this->AssignPartsToOrderController = new AssignPartsToOrderController();
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
        }
    }
}
