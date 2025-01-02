<?php

require_once './models/tech_models/AssignPartsToOrderModel.php';

class AssignPartsToOrderController
{
    private $AssignPartsToOrderModel;

    public function __construct()
    {
        $this->AssignPartsToOrderModel = new AssignPartsToOrderModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'PUT':
                $this->assignPartsToOrder();
                break;
            case 'DELETE':
                $this->removePartFromOrder();
                break;
            default:
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Nieprawidłowa metoda"]);
                break;
        }
    }

    private function assignPartsToOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $order_id = $input['order_id'];
        $parts = $input['parts'];

        $result = $this->AssignPartsToOrderModel->assignPartsToOrder($order_id, $parts);

        if ($result === true) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Części zostały przypisane do zlecenia"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => $result]);
        }
    }

    private function removePartFromOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $order_id = $input['order_id'];

        $result = $this->AssignPartsToOrderModel->removePartFromOrder($order_id);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Wszystkie części zostały usunięte ze zlecenia"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nie udało się usunąć części ze zlecenia"]);
        }
    }
}
