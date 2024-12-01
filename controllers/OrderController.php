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
                $this->cancelOrder();
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


    private function cancelOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input) || !isset($input['order_id'], $input['user_id'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowe dane wejściowe']);
            return;
        }

        $order_id = $input['order_id'];
        $user_id = $input['user_id'];

        $result = $this->OrderModel->cancelOrder($order_id, $user_id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Zamówienie zostało anulowane']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Błąd podczas anulowania zamówienia']);
        }
    }

    private function getUserOrders()
    {
        $user_id = $_SESSION['user']['user_id'];
        $orders = $this->OrderModel->getUserOrders($user_id);

        if ($orders) {
            echo json_encode(['status' => 'success', 'data' => $orders]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Brak zamówień']);
        }
    }
}
