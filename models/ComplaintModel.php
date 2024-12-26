<?php
require_once __DIR__ . '/../DatabaseController.php';

class ComplaintModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function addComplaint($order_id, $complaint_description, $complaint_status = 'złożone')
    {
        $query = "INSERT INTO complaints (order_id, complaint_description, complaint_status) 
                  VALUES (:order_id, :complaint_description, :complaint_status)";

        $params = [
            ':order_id' => $order_id,
            ':complaint_description' => $complaint_description,
            ':complaint_status' => $complaint_status
        ];

        return $this->db->execute($query, $params);
    }

    public function getComplaint($complaint_id)
    {
        $query = "SELECT c.*, o.short_specification AS order_name
                  FROM complaints c
                  JOIN orders o ON c.order_id = o.order_id
                  WHERE c.complaint_id = :complaint_id";

        return $this->db->fetch($query, [':complaint_id' => $complaint_id]);
    }

    public function getComplaintsByUser($user_id)
    {
        $query = "SELECT c.*, o.short_specification AS order_name
                  FROM complaints c
                  JOIN orders o ON c.order_id = o.order_id
                  WHERE o.user_id = :user_id";

        return $this->db->fetchAll($query, [':user_id' => $user_id]);
    }

    public function updateComplaintStatus($complaint_id, $complaint_status)
    {
        $allowed_statuses = ['złożone', 'w trakcie rozpatrywania', 'rozwiązane'];
        if (!in_array($complaint_status, $allowed_statuses)) {
            return false;
        }

        $query = "UPDATE complaints SET complaint_status = :complaint_status 
                  WHERE complaint_id = :complaint_id";

        $params = [
            ':complaint_status' => $complaint_status,
            ':complaint_id' => $complaint_id
        ];

        return $this->db->execute($query, $params);
    }

    public function updateComplaint($complaint_id, $user_id, $complaint_description, $complaint_status)
    {
        $params = [
            ':complaint_id' => $complaint_id,
            ':user_id' => $user_id,
            ':complaint_description' => $complaint_description
        ];

        $query = "UPDATE complaints SET complaint_description = :complaint_description 
                  WHERE complaint_id = :complaint_id AND user_id = :user_id";

        return $this->db->execute($query, $params);
    }

    public function complaintExists($order_id)
    {
        $query = "SELECT COUNT(*) AS count FROM complaints WHERE order_id = :order_id";
        $result = $this->db->fetch($query, [':order_id' => $order_id]);
        return $result && $result['count'] > 0;
    }

    public function getLastInsertedComplaint()
    {
        $query = "SELECT c.*, o.short_specification AS order_name
              FROM complaints c
              JOIN orders o ON c.order_id = o.order_id
              ORDER BY c.complaint_id DESC LIMIT 1";

        return $this->db->fetch($query);
    }
}
