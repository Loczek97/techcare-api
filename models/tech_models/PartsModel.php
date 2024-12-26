<?php

class PartsModel
{
    private $db;
    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getParts()
    {
        $sql = "SELECT * FROM parts";

        $result = $this->db->fetchAll($sql);

        return $result;
    }

    public function getPartsById($id)
    {
        $sql = "SELECT part_name, quantity_in_stock, selling_price, purchase_price, updated_at FROM parts WHERE part_id = :part_id";

        $result = $this->db->fetch($sql, [':part_id' => $id]);

        return $result;
    }

    public function addPart($part_name, $category, $selling_price, $purchase_price, $quantity_in_stock = 0)
    {
        $sql = 'INSERT INTO parts (part_name, category, quantity_in_stock, selling_price, purchase_price, updated_at) VALUES (:part_name, :category, :quantity_in_stock, :selling_price, :purchase_price, :updated_at)';

        $result = $this->db->execute($sql, [
            ':part_name' => $part_name,
            ':category' => $category,
            ':quantity_in_stock' => $quantity_in_stock,
            ':selling_price' => $selling_price,
            ':purchase_price' => $purchase_price,
            ':updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            $sql = "SELECT * FROM parts WHERE part_id = :part_id";
            $part = $this->db->fetch($sql, [':part_id' => $this->db->lastInsertId()]);
            return $part;
        }
    }


    public function deletePart($part_id)
    {
        $sql = "DELETE FROM parts WHERE part_id = :part_id";

        $result = $this->db->execute($sql, [':part_id' => $part_id]);

        return $result;
    }

    public function updatePart($part_id, $part_name = null, $category = null, $selling_price = null, $purchase_price = null, $quantity_in_stock = null)
    {
        $sql = "UPDATE parts SET ";
        $params = [':part_id' => $part_id];

        // Check for each parameter and add only if not null
        if ($part_name !== null) {
            $sql .= "part_name = :part_name, ";
            $params[':part_name'] = $part_name;
        }
        if ($category !== null) {
            $sql .= "category = :category, ";
            $params[':category'] = $category;
        }
        if ($quantity_in_stock !== null) {
            $sql .= "quantity_in_stock = :quantity_in_stock, ";
            $params[':quantity_in_stock'] = $quantity_in_stock;
        }
        if ($selling_price !== null) {
            $sql .= "selling_price = :selling_price, ";
            $params[':selling_price'] = $selling_price;
        }
        if ($purchase_price !== null) {
            $sql .= "purchase_price = :purchase_price, ";
            $params[':purchase_price'] = $purchase_price;
        }

        $sql .= "updated_at = :updated_at ";
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE part_id = :part_id";

        $params[':updated_at'] = date('Y-m-d H:i:s');

        $result = $this->db->execute($sql, $params);

        return $result;
    }
}
