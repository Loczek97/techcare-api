<?php

class TechOrdersModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getOrders()
    {
        $sql = "SELECT * FROM orders";
        $result = $this->db->fetchAll($sql);
        return $result;
    }

    public function deleteOrder($order_id)
    {
        $sql = "DELETE FROM orders WHERE order_id = :order_id";
        $result = $this->db->execute($sql, [":order_id" => $order_id]);
        return $result;
    }

    public function updateOrder($technician_id, $order_id, $status)
    {
        $validStatuses = ["Oczekujące", "W trakcie realizacji", "Zakończone", "Anulowane"];

        if (!in_array($status, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Nieprawidłowy status zamówienia']);
            die();
        }

        $sql = "UPDATE orders SET technician_id = :technician_id, status = :status WHERE order_id = :order_id";
        $result = $this->db->execute($sql, [
            ":technician_id" => $technician_id,
            ":order_id" => $order_id,
            ":status" => $status
        ]);

        $sql = "SELECT * FROM orders WHERE order_id = :order_id";

        $result = $this->db->fetch($sql, [":order_id" => $order_id]);

        return $result;
    }
}
