<?php

class AssignPartsToOrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function assignPartsToOrder($order_id, $parts_ids)
    {
        $this->db->beginTransaction();

        try {
            foreach ($parts_ids as $parts_id) {
                $sql = "INSERT INTO order_parts (order_id, parts_id) VALUES (:order_id, :parts_id);";
                $this->db->execute($sql, [":order_id" => $order_id, ":parts_id" => $parts_id]);

                $update_sql = "
                    UPDATE parts
                    SET quantity = quantity - 1
                    WHERE parts_id = :parts_id AND quantity > 0;
                ";

                $this->db->execute($update_sql, [":parts_id" => $parts_id]);
            }

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function removePartFromOrder($order_id, $parts_id)
    {
        $this->db->beginTransaction();

        try {
            $delete_sql = "DELETE FROM order_parts WHERE order_id = :order_id AND parts_id = :parts_id;";
            $this->db->execute($delete_sql, [":order_id" => $order_id, ":parts_id" => $parts_id]);

            $update_sql = "
                UPDATE parts
                SET quantity = quantity + 1
                WHERE parts_id = :parts_id;
            ";

            $this->db->execute($update_sql, [":parts_id" => $parts_id]);

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
