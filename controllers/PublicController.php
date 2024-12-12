<?php

require_once __DIR__ . '/../models/PublicPricingModel.php';
require_once __DIR__ . '/../models/PublicOrdersModel.php';

class PublicController
{
    private $PublicPricingModel;
    private $PublicOrdersModel;

    public function __construct()
    {
        $this->PublicPricingModel = new PublicPricingModel();
        $this->PublicOrdersModel = new PublicOrdersModel();
    }

    public function handleRequest()
    {
        $url_parts = explode('/', $_SERVER['REQUEST_URI']);
        $action = isset($url_parts[4]) ? $url_parts[4] : null;

        switch ($action) {
            case 'pricing':
                $this->PublicPricingModel->getPricing();
                break;
            case 'orders-count':
                $this->PublicOrdersModel->getOrdersCount();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Endpoint nie istnieje']);
        }
    }
}
