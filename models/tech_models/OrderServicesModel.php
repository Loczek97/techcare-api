<?php

class OrderServicesModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function addServicesToOrder($order_id, $services)
    {
        try {
            if (!is_array($services) || empty($services)) {
                throw new Exception("Invalid services format");
            }

            $this->db->beginTransaction();

            // Usuń istniejące usługi dla danego zamówienia
            $clear_sql = "DELETE FROM order_services WHERE order_id = :order_id;";
            $this->db->execute($clear_sql, [':order_id' => $order_id]);

            // Dodaj nowe usługi
            foreach ($services as $service_id) {
                if (!is_numeric($service_id)) {
                    throw new Exception("Invalid service ID: " . json_encode($service_id));
                }

                $sql = "INSERT INTO order_services (order_id, service_id) VALUES (:order_id, :service_id);";
                $this->db->execute($sql, [
                    ':order_id' => $order_id,
                    ':service_id' => $service_id
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in addServicesToOrder: " . $e->getMessage());
            return false;
        }
    }


    public function removeServicesFromOrder($order_id, $services)
    {
        try {
            $this->db->beginTransaction();

            foreach ($services as $service_id) {
                $sql = "DELETE FROM order_services WHERE order_id = :order_id AND service_id = :service_id;";
                $this->db->execute($sql, [
                    ':order_id' => $order_id,
                    ':service_id' => $service_id
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getServicesForOrder($order_id)
    {
        $sql = "SELECT s.service_id, s.service_name, s.price, s.is_available 
                FROM services s 
                INNER JOIN order_services os ON s.service_id = os.service_id 
                WHERE os.order_id = :order_id;";
        return $this->db->fetchAll($sql, [
            ':order_id' => $order_id
        ]);
    }
}
