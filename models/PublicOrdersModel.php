<?php

require_once __DIR__ . "/../DatabaseController.php";


class PublicOrdersModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getOrdersCount()
    {
        $statuses = ['W trakcie realizacji', 'Oczekujące', 'Zrealizowane', 'Anulowane'];
        $counts = [];

        foreach ($statuses as $status) {
            $query = "
            SELECT COUNT(*) as count FROM orders WHERE status = :status
        ";

            $result = $this->db->fetch($query, [':status' => $status]);


            $counts[$status] = $result['count'];
        }

        echo json_encode([
            'status' => 'success',
            'data' => $counts
        ]);
    }
}
