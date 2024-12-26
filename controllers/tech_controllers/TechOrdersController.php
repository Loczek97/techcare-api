<?php

require_once './models/tech_models/TechOrdersModel.php';

class TechOrdersController
{
    private $TechOrdersModel;

    public function __construct()
    {
        $this->TechOrdersModel = new TechOrdersModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->getAllOrders();
                break;
            case 'PUT':
                $this->updateOrder();
                break;
            case 'DELETE':
                $this->deleteOrder();
                break;
            default:
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Nieprawidłowa metoda"]);
                break;
        }
    }

    private function getAllOrders()
    {
        $orders = $this->TechOrdersModel->getOrders();


        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $orders]);
    }

    private function deleteOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $order_id = $input['order_id'];

        $result = $this->TechOrdersModel->deleteOrder($order_id);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Zamówienie zostało usunięte"]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Nie udało się usunąć zamówienia"]);
        }
    }

    private function updateOrder()
    {
        $input = json_decode(file_get_contents("php://input"), true);

        $technician_id = $input['technician_id'];
        $order_id = $input['order_id'];
        $status = $input['status'];

        $result = $this->TechOrdersModel->updateOrder($technician_id, $order_id, $status);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Zamówienie zostało zaktualizowane', 'data' => $result]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się zaktualizować zamówienia']);
        }
    }
}
