<?php

require_once './controllers/tech_controllers/PartsController.php';
class TechRouter
{
    private $PartsController;

    public function __construct()
    {
        $this->PartsController = new PartsController();
    }

    public function handleRequest($url_part)
    {
        switch ($url_part) {
            case 'parts':
                $this->PartsController->handleRequest();
                break;
        }
    }
}
