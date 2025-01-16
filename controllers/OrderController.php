<?php
require_once __DIR__ . '/../models/OrderModel.php';


class OrderController
{
    private $OrderModel;

    public function __construct()
    {
        $this->OrderModel = new OrderModel();
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->addOrder();
                break;
            case 'PUT':
                $this->updateOrder();
                break;
            case 'GET':
                $this->getUserOrders();
                break;
            default:
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Metoda niedozwolona']);
        }
    }

    private function addOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['user_id'], $input['device_type'], $input['short_specification'], $input['problem_description'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $user_id = $input['user_id'];
        $device_type = $input['device_type'];
        $short_specification = $input['short_specification'];
        $problem_description = $input['problem_description'];

        $result = $this->OrderModel->addOrder($user_id, $device_type, $short_specification, $problem_description);

        if ($result) {
            $newOrder = $this->OrderModel->getLastUserOrder($user_id);
            if ($newOrder) {
                echo json_encode(['status' => 'success', 'message' => 'Zamówienie zostało dodane', 'data' => $newOrder]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Nie udało się pobrać danych nowego zamówienia']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania zamówienia']);
        }
    }


    private function updateOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['order_id'], $input['user_id'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $order_id = $input['order_id'];
        $user_id = $input['user_id'];

        $status = $input['status'] ?? null;
        $problem_description = $input['problem_description'] ?? null;
        $device_type = $input['device_type'] ?? null;
        $short_specification = $input['short_specification'] ?? null;

        if (isset($problem_description) && isset($device_type) && isset($short_specification)) {
        }

        $result = $this->OrderModel->updateOrder($order_id, $user_id, $status, $short_specification, $device_type, $problem_description);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Zamówienie zostało zaktualizowane']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji zamówienia']);
        }
    }

    private function getUserOrders()
    {
        $user_id = $_SESSION['user']['user_id'];
        $orders = $this->OrderModel->getUserOrders($user_id);

        echo json_encode(['status' => 'success', 'data' => $orders]);
    }
}
