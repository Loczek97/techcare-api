<?php

require_once './models/tech_models/OrderServicesModel.php';

class OrderServicesController
{
    private $OrderServicesModel;

    public function __construct()
    {
        $this->OrderServicesModel = new OrderServicesModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                $this->addServicesToOrder();
                break;
            case 'DELETE':
                $this->removeServicesFromOrder();
                break;
            case 'GET':
                $this->getServicesForOrder();
                break;
            default:
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Nieprawidłowa metoda"]);
                break;
        }
    }

    private function addServicesToOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $order_id = $input['order_id'];
        $services = $input['services'];

        $result = $this->OrderServicesModel->addServicesToOrder($order_id, $services);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Usługi zostały przypisane do zlecenia"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nie udało się przypisać usług do zlecenia"]);
        }
    }

    private function removeServicesFromOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $order_id = $input['order_id'];
        $services = $input['services'];

        $result = $this->OrderServicesModel->removeServicesFromOrder($order_id, $services);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Usługi zostały usunięte ze zlecenia"]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nie udało się usunąć usług ze zlecenia"]);
        }
    }

    private function getServicesForOrder()
    {
        $order_id = $_GET['order_id'];

        $result = $this->OrderServicesModel->getServicesForOrder($order_id);

        if ($result) {
            http_response_code(200);
            echo json_encode(["status" => "success", "data" => $result]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Nie znaleziono usług dla podanego zlecenia"]);
        }
    }
}
