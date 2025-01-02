<?php

class AssignPartsToOrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function assignPartsToOrder($order_id, $parts)
    {
        $this->db->beginTransaction();

        try {
            //pobierz istniejące części przypisane do zlecenia
            $get_existing_parts_sql = "SELECT part_id, part_quantity FROM order_parts WHERE order_id = :order_id;";
            $existing_parts = $this->db->fetchAll($get_existing_parts_sql, [":order_id" => $order_id]);

            //przywróć ilość części do magazynu
            foreach ($existing_parts as $part) {
                $restore_sql = "
                UPDATE parts
                SET quantity_in_stock = quantity_in_stock + :quantity
                WHERE part_id = :part_id;
            ";
                $this->db->execute($restore_sql, [
                    ":quantity" => $part['part_quantity'],
                    ":part_id" => $part['part_id']
                ]);
            }

            //usuń istniejące części przypisane do zlecenia (z tabeli łącznikowej)
            $clear_sql = "DELETE FROM order_parts WHERE order_id = :order_id;";
            $this->db->execute($clear_sql, [":order_id" => $order_id]);

            //sprawdzanie czy jest wystarczająca ilość części w magazynie
            foreach ($parts as $part) {
                $check_stock_sql = "
                SELECT quantity_in_stock 
                FROM parts 
                WHERE part_id = :part_id;
            ";
                $stock = $this->db->fetch($check_stock_sql, [":part_id" => $part['part_id']]);

                if (!$stock || $stock['quantity_in_stock'] < $part['quantity']) {
                    throw new Exception("Brak wystarczającej ilości części ID: {$part['part_id']} w magazynie.");
                }

                //dodaj nowe części do zlecenia (do tabeli łącznikowej)
                $insert_sql = "
                INSERT INTO order_parts (order_id, part_id, part_quantity)
                VALUES (:order_id, :part_id, :quantity);
            ";
                $this->db->execute($insert_sql, [
                    ":order_id" => $order_id,
                    ":part_id" => $part['part_id'],
                    ":quantity" => $part['quantity']
                ]);

                //zmniejsz stany magazynowe
                $decrement_sql = "
                UPDATE parts
                SET quantity_in_stock = quantity_in_stock - :quantity
                WHERE part_id = :part_id;
            ";
                $this->db->execute($decrement_sql, [
                    ":quantity" => $part['quantity'],
                    ":part_id" => $part['part_id']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return $e->getMessage();
        }
    }

    public function removePartFromOrder($order_id)
    {
        $this->db->beginTransaction();

        try {
            $get_existing_parts_sql = "SELECT part_id, part_quantity FROM order_parts WHERE order_id = :order_id;";
            $existing_parts = $this->db->fetchAll($get_existing_parts_sql, [":order_id" => $order_id]);

            foreach ($existing_parts as $part) {
                $restore_sql = "
                UPDATE parts
                SET quantity_in_stock = quantity_in_stock + :quantity
                WHERE part_id = :part_id;
            ";
                $this->db->execute($restore_sql, [
                    ":quantity" => $part['part_quantity'],
                    ":part_id" => $part['part_id']
                ]);
            }

            $delete_sql = "DELETE FROM order_parts WHERE order_id = :order_id;";
            $this->db->execute($delete_sql, [":order_id" => $order_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
