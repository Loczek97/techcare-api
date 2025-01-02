<?php

class TechComplaintsModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getComplaints($complaint_id = null, $order_id = null)
    {
        $sql = "SELECT * FROM complaints WHERE 1=1";
        $params = [];

        if ($complaint_id) {
            $sql .= " AND complaint_id = :complaint_id";
            $params[':complaint_id'] = $complaint_id;
        }

        if ($order_id) {
            $sql .= " AND order_id = :order_id";
            $params[':order_id'] = $order_id;
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function updateComplaint($complaint_id, $data)
    {
        $sql = "UPDATE complaints SET 
                    complaint_status = :complaint_status, 
                    complaints_return_message = :complaints_return_message, 
                    technician_id = :technician_id, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE complaint_id = :complaint_id";

        $params = [
            ':complaint_status' => $data['complaint_status'],
            ':complaints_return_message' => $data['complaints_return_message'],
            ':technician_id' => $data['technician_id'],
            ':complaint_id' => $complaint_id
        ];

        return $this->db->execute($sql, $params);
    }

    public function deleteComplaint($complaint_id)
    {
        $sql = "DELETE FROM complaints WHERE complaint_id = :complaint_id";
        return $this->db->execute($sql, [':complaint_id' => $complaint_id]);
    }
}
