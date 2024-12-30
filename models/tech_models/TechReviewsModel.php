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
            SELECT r.*, u.first_name, u.last_name, o.problem_description, o.device_type, o.updated_at AS submission_date FROM rating r LEFT JOIN orders o ON o.order_id = r.order_id LEFT JOIN users u ON u.user_id = o.user_id WHERE o.technician_id = :technician_id ORDER BY o.updated_at DESC;
        ";


        return $this->db->fetchAll($sql, [":technician_id" => $technician_id]);
    }
}
