<?php

class InvoicesModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function saveInvoicePath($orderId, $filePath)
    {
        $query = "
            INSERT INTO invoices (order_id, file_path)
            VALUES (:order_id, :file_path)";
        $params = [
            ':file_path' => $filePath,
            ':order_id' => $orderId
        ];

        return $this->db->execute($query, $params);
    }
}
