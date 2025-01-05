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


    public function updateOrder($order_id, $user_id, $status, $short_specification, $device_type, $problem_description)
    {
        $params = [
            ':order_id' => $order_id,
            ':user_id' => $user_id,
        ];

        $setClause = [];

        if (isset($status)) {
            $setClause[] = "status = :status";
            $params[':status'] = $status;
        }
        if (isset($short_specification)) {
            $setClause[] = "short_specification = :short_specification";
            $params[':short_specification'] = $short_specification;
        }
        if (isset($device_type)) {
            $setClause[] = "device_type = :device_type";
            $params[':device_type'] = $device_type;
        }
        if (isset($problem_description)) {
            $setClause[] = "problem_description = :problem_description";
            $params[':problem_description'] = $problem_description;
        }

        if (empty($setClause)) {
            throw new Exception("Brak danych do aktualizacji zamówienia.");
        }

        $query = "UPDATE orders SET " . implode(', ', $setClause) . " WHERE order_id = :order_id AND user_id = :user_id";

        return $this->db->execute($query, $params);
    }


    public function getUserOrders($user_id)
    {
        $query = "SELECT o.*, 
                     t.first_name AS technician_name,
                     i.invoice_id, i.file_path
              FROM orders o
              LEFT JOIN users t ON o.technician_id = t.user_id
              LEFT JOIN invoices i ON o.order_id = i.order_id
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
