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
            SELECT * FROM rating r LEFT JOIN orders o ON r.order_id=o.order_id AND o.technician_id=:technician_id;
        ";


        return $this->db->fetchAll($sql, [":technician_id" => $technician_id]);
    }
}
