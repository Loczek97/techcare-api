<?php

require_once './models/admin_models/ServicesModel.php';

class ServicesController
{
    private $ServicesModel;

    public function __construct()
    {
        $this->ServicesModel = new ServicesModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->getServices();
                break;
            case 'POST':
                $this->addService();
                break;
            case 'PUT':
                $this->updateService();
                break;
            case 'DELETE':
                $this->deleteService();
                break;
        }
    }

    private function getServices()
    {
        $services = $this->ServicesModel->getServices();
        echo json_encode(["status" => "success", "data" => $services]);
    }

    private function addService()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $service_name = $input['service_name'];
        $price = $input['price'];
        $is_available = $input['is_available'];

        if ($service_name && $price && isset($is_available)) {
            $result = $this->ServicesModel->addService($service_name, $price, $is_available);

            echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Usługa dodana pomyślnie." : "Nie udało się dodać usługi."]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Nieprawidłowe dane wejściowe."]);
        }
    }

    private function updateService()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $service_id = $input['service_id'];
        $service_name = $input['service_name'] ?? null;
        $price = $input['price'] ?? null;
        $is_available = $input['is_available'] ?? null;

        if ($service_id) {
            $result = $this->ServicesModel->updateService($service_id, $service_name, $price, $is_available);
            echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Usługa zaktualizowana pomyślnie." : "Nie udało się zaktualizować usługi."]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Nieprawidłowe dane wejściowe."]);
        }
    }

    private function deleteService()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $service_id = $input['service_id'] ?? null;

        if ($service_id) {
            $result = $this->ServicesModel->deleteService($service_id);
            echo json_encode(["status" => $result ? "success" : "error", "message" => $result ? "Usługa usunięta pomyślnie." : "Nie udało się usunąć usługi."]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Nieprawidłowe dane wejściowe."]);
        }
    }
}
