<?php

require_once './models/tech_models/TechReviewsModel.php';

class TechReviewsController
{
    private $TechReviewsModel;

    public function __construct()
    {
        $this->TechReviewsModel = new TechReviewsModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->getReviews();
                break;
            default:
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Nieprawidłowa metoda"]);
                break;
        }
    }

    private function getReviews()
    {

        $technician_id = $_SESSION['user']['user_id'];

        $reviews = $this->TechReviewsModel->getReviewsByTechnician($technician_id);

        echo json_encode(["status" => "success", "data" => $reviews]);
    }
}
