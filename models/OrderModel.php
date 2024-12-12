<?php
require_once __DIR__ . '/../DatabaseController.php';

class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function addOrder($user_id, $device_type, $short_specification, $problem_description, $status = 'Oczekujące')
    {
        $query = "INSERT INTO orders (user_id, device_type, short_specification, problem_description, status) 
                  VALUES (:user_id, :device_type, :short_specification, :problem_description, :status)";

        $params = [
            ':user_id' => $user_id,
            ':device_type' => $device_type,
            ':short_specification' => $short_specification,
            ':problem_description' => $problem_description,
            ':status' => $status,
        ];

        return $this->db->execute($query, $params);
    }

    public function cancelOrder($order_id, $user_id)
    {
        $query = "UPDATE orders SET status = 'Anulowane'
                  WHERE order_id = :order_id AND user_id = :user_id";

        $params = [
            ':order_id' => $order_id,
            ':user_id' => $user_id,
        ];

        return $this->db->execute($query, $params);
    }

    public function getUserOrders($user_id)
    {
        $query = "SELECT o.*, 
                     t.first_name AS technician_name
              FROM orders o
              LEFT JOIN users t ON o.technician_id = t.user_id
              WHERE o.user_id = :user_id";
        $result = $this->db->fetchAll($query, [':user_id' => $user_id]);

        return $result;
    }


    public function getLastUserOrder($user_id)
    {
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
        return $this->db->fetch($query, [':user_id' => $user_id]);
    }
}
