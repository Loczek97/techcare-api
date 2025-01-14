<?php

class TechComplaintsModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getComplaints()
    {
        $sql = "SELECT 
                c.complaint_id,
                c.complaint_date,
                c.complaint_description,
                c.complaint_status,
                c.complaints_return_message,
                c.updated_at AS complaint_updated_at,
                c.technician_id,
                t.first_name AS complaint_technician_first_name,
                t.last_name AS complaint_technician_last_name,
                o.order_id,
                o.status AS order_status,
                o.problem_description,
                o.device_type,
                o.short_specification,
                o.created_at AS order_created_at,
                o.updated_at AS order_updated_at,
                t2.first_name AS order_technician_first_name,
                t2.last_name AS order_technician_last_name,
                i.invoice_id,
                i.invoice_date,
                i.file_path AS invoice_file_path
            FROM 
                complaints c
            LEFT JOIN 
                users t ON c.technician_id = t.user_id
            LEFT JOIN 
                orders o ON c.order_id = o.order_id
            LEFT JOIN 
                users t2 ON o.technician_id = t2.user_id
            LEFT JOIN 
                invoices i ON o.order_id = i.order_id;
                ";

        return $this->db->fetchAll($sql);
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
