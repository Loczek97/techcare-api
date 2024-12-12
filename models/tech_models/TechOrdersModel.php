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
        $orders_sql = "
            SELECT 
                o.order_id AS order_id,
                o.status AS order_status,
                o.problem_description AS order_problem_description,
                o.device_type AS order_device_type,
                o.short_specification AS order_short_specification,
                o.created_at AS order_created_at,
                o.updated_at AS order_updated_at,

                cl.user_id AS client_id,
                cl.first_name AS client_first_name,
                cl.last_name AS client_last_name,
                cl.email AS client_email,
                cl.phone AS client_phone,
                cl.address AS client_address,

                technician.user_id AS technician_id,
                technician.first_name AS technician_first_name,
                technician.last_name AS technician_last_name,
                technician.email AS technician_email,
                technician.phone AS technician_phone
            FROM 
                orders o
            LEFT JOIN 
                users cl ON o.user_id = cl.user_id
            LEFT JOIN 
                users technician ON o.technician_id = technician.user_id;
        ";

        return $this->db->fetchAll($orders_sql);
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
