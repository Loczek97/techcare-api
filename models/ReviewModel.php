<?php
require_once __DIR__ . '/../DatabaseController.php';

class ReviewModel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseController();
    }

    public function addRating($user_id, $order_id, $rating_text, $rating_score)
    {
        // Sprawdź, czy zamówienie posiada już opinię
        if ($this->hasOrderRating($order_id)) {
            return false;
        }

        $query = "INSERT INTO rating (user_id, order_id, rating_text, rating_score) 
                  VALUES (:user_id, :order_id, :rating_text, :rating_score)";

        $params = [
            ':user_id' => $user_id,
            ':order_id' => $order_id,
            ':rating_text' => $rating_text,
            ':rating_score' => $rating_score
        ];

        if ($this->db->execute($query, $params)) {
            $query = "SELECT * FROM rating WHERE rating_id = LAST_INSERT_ID()";
            return $this->db->fetch($query);
        }

        return false;
    }

    public function getRating($rating_id)
    {
        $query = "SELECT * FROM rating WHERE rating_id = :rating_id";
        return $this->db->fetch($query, [':rating_id' => $rating_id]);
    }

    public function getRatingsByUser($user_id)
    {
        $query = "SELECT r.*, o.short_specification AS order_name 
                  FROM rating r 
                  JOIN orders o ON r.order_id = o.order_id 
                  WHERE r.user_id = :user_id";
        return $this->db->fetchAll($query, [':user_id' => $user_id]);
    }

    public function updateRating($rating_id, $rating_text, $rating_score)
    {
        $query = "UPDATE rating SET rating_text = :rating_text, rating_score = :rating_score 
                  WHERE rating_id = :rating_id";

        $params = [
            ':rating_text' => $rating_text,
            ':rating_score' => $rating_score,
            ':rating_id' => $rating_id
        ];

        return $this->db->execute($query, $params);
    }

    public function deleteRating($rating_id)
    {
        $query = "DELETE FROM rating WHERE rating_id = :rating_id";
        return $this->db->execute($query, [':rating_id' => $rating_id]);
    }

    public function isOrderCompleted($order_id)
    {
        $query = "SELECT status FROM orders WHERE order_id = :order_id";
        $result = $this->db->fetch($query, [':order_id' => $order_id]);
        return $result && $result['status'] === 'Zakończone';
    }

    public function hasOrderRating($order_id)
    {
        $query = "SELECT COUNT(*) AS count FROM rating WHERE order_id = :order_id";
        $result = $this->db->fetch($query, [':order_id' => $order_id]);
        return $result && $result['count'] > 0;
    }
}
