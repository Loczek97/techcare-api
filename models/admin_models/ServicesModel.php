<?php

require_once './DatabaseController.php';

class ServicesModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function getServices()
    {
        $sql = "
            SELECT service_id, service_name, price, is_available FROM services;
        ";

        return $this->db->fetchAll($sql);
    }

    public function addService($service_name, $price, $is_available)
    {
        $sql = "
            INSERT INTO services (service_name, price, is_available) 
            VALUES (:service_name, :price, :is_available);
        ";

        return $this->db->execute($sql, [
            ":service_name" => $service_name,
            ":price" => $price,
            ":is_available" => $is_available
        ]);
    }

    public function updateService($service_id, $service_name = null, $price = null, $is_available = null)
    {
        $setClause = [];
        $params = [":service_id" => $service_id];

        if ($service_name !== null) {
            $setClause[] = "service_name = :service_name";
            $params[":service_name"] = $service_name;
        }
        if ($price !== null) {
            $setClause[] = "price = :price";
            $params[":price"] = $price;
        }
        if ($is_available !== null) {
            $setClause[] = "is_available = :is_available";
            $params[":is_available"] = $is_available;
        }

        if (empty($setClause)) {
            throw new Exception("No fields to update.");
        }

        $sql = "
        UPDATE services
        SET " . implode(", ", $setClause) . "
        WHERE service_id = :service_id;
    ";

        return $this->db->execute($sql, $params);
    }


    public function deleteService($service_id)
    {
        $sql = "
            DELETE FROM services WHERE service_id = :service_id;
        ";

        return $this->db->execute($sql, [":service_id" => $service_id]);
    }
}
