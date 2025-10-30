<?php

// Update this path to wherever the actual Database class is located
require_once __DIR__ . '/../../config/database.php'; // or wherever it actually is

class OrderItem
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findByOrderId($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($orderId, $productId, $quantity, $price)
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$orderId, $productId, $quantity, $price]);
    }
}