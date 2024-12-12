<?php

require_once './DatabaseController.php';

class TechReviewsModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getReviewsByTechnician($technician_id)
    {
        $sql = "
            SELECT o.order_id, o.status, r.review_text, r.rating, r.created_at
            FROM orders o
            LEFT JOIN reviews r ON o.order_id = r.order_id
            WHERE o.technician_id = :technician_id
            ORDER BY r.created_at DESC;
        ";

        // Wykonanie zapytania i zwrócenie wyników
        return $this->db->fetchAll($sql, [":technician_id" => $technician_id]);
    }
}
