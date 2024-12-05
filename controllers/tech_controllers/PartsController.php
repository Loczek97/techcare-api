<?php

require_once './models/tech_models/PartsModel.php';


class PartsController
{
    private $PartsModel;

    public function __construct()
    {
        $this->PartsModel = new PartsModel();
    }

    public function handleRequest()
    {
        $mehtod = $_SERVER['REQUEST_METHOD'];

        switch ($mehtod) {
            case 'GET':
                $this->getParts();
                break;
            case 'POST':
                $this->addPart();
                break;
            case 'PUT':
                $this->updatePart();
                break;
            case "DELETE":
                $this->deletePart();
                break;
        }
    }


    private function getParts()
    {
        $parts = $this->PartsModel->getParts();
        echo json_encode(['status' => 'success', 'data' => $parts]);
    }

    private function addPart()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['part_name']) || !isset($input['category']) || !isset($input['quantity_in_stock']) || !isset($input['selling_price']) || !isset($input['purchase_price'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych pól']);
            return;
        }

        $part_name = $input['part_name'];
        $category = $input['category'];
        $quantity_in_stock = $input['quantity_in_stock'];
        $selling_price = $input['selling_price'];
        $purchase_price = $input['purchase_price'];

        $part = $this->PartsModel->addPart($part_name, $category, $selling_price, $purchase_price, $quantity_in_stock);

        if ($part) {
            echo json_encode(['status' => 'success', 'data' => $part]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się dodać części']);
        }
    }

    private function updatePart()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $part_id = $input['part_id'];
        $part_name = $input['part_name'] ?? null;
        $category = $input['category'] ?? null;
        $quantity_in_stock = $input['quantity_in_stock'] ?? null;
        $selling_price = $input['selling_price'] ?? null;
        $purchase_price = $input['purchase_price'] ?? null;

        // Use updatePart instead of addPart
        $part = $this->PartsModel->updatePart($part_id, $part_name, $category, $quantity_in_stock, $selling_price, $purchase_price);

        if ($part) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Część zaktualizowana']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się zaktualizować części']);
        }
    }


    private function deletePart()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $part_id = $input['part_id'];

        $result = $this->PartsModel->deletePart($part_id);

        if ($result) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Część usunięta']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Nie udało się usunąć części']);
        }
    }
}
