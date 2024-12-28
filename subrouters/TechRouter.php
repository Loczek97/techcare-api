<?php

require_once './controllers/tech_controllers/PartsController.php';
require_once './controllers/tech_controllers/TechOrdersController.php';
require_once './controllers/tech_controllers/TechReviewsController.php';
class TechRouter
{
    private $PartsController;
    private $TechOrdersController;
    private $TechReviewsController;

    public function __construct()
    {
        $this->PartsController = new PartsController();
        $this->TechOrdersController = new TechOrdersController();
        $this->TechReviewsController = new TechReviewsController();
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
        }
    }
}
