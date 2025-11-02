<?php

require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel {
    
    protected $table = 'orders';
    
    public function create($customerId, $subtotal, $shippingCost, $taxAmount, $totalAmount) {
        $orderNumber = 'ORD-' . time() . '-' . $customerId;
        
        $sql = "INSERT INTO orders (customer_id, order_number, subtotal, shipping_cost, tax_amount, total_amount) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'isdddd', $customerId, $orderNumber, $subtotal, $shippingCost, $taxAmount, $totalAmount);
        
        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }
    
    public function addOrderItem($orderId, $productId, $quantity, $unitPrice) {
        $itemTotal = $quantity * $unitPrice;
        
        // Get product name and image to store as snapshot
        $productSql = "SELECT product_name, img_path FROM products WHERE id = ?";
        $productStmt = mysqli_prepare($this->conn, $productSql);
        mysqli_stmt_bind_param($productStmt, 'i', $productId);
        mysqli_stmt_execute($productStmt);
        $productResult = mysqli_stmt_get_result($productStmt);
        $product = mysqli_fetch_assoc($productResult);
        
        $productName = $product['product_name'] ?? 'Unknown Product';
        $productImage = $product['img_path'] ?? '';
        
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, unit_price, item_total) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iissidd', $orderId, $productId, $productName, $productImage, $quantity, $unitPrice, $itemTotal);
        return mysqli_stmt_execute($stmt);
    }
    
    public function updateStatus($orderId, $status) {
        $sql = "UPDATE orders SET order_status = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $status, $orderId);
        return mysqli_stmt_execute($stmt);
    }
    
    public function getCustomerOrders($customerId) {
        $sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $customerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getOrderDetails($orderId) {
        $sql = "SELECT o.*, 
                u.email, 
                u.first_name, 
                u.last_name, 
                u.phone, 
                u.address, 
                u.city, 
                u.postal_code, 
                u.country,
                u.profile_picture
                FROM orders o 
                INNER JOIN users u ON o.customer_id = u.id 
                WHERE o.id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    public function getOrderItems($orderId) {
        // Get order items with product info (if product still exists)
        $sql = "SELECT oi.*, 
                p.is_active, 
                p.product_name as current_product_name, 
                p.img_path as current_product_image
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        foreach ($items as &$item) {
            // Use snapshot name/image from order_items, fallback to current if snapshot is empty
            $snapshotName = $item['product_name'] ?? '';
            $snapshotImage = $item['product_image'] ?? '';
            
            $item['display_name'] = !empty($snapshotName) ? $snapshotName : 
                                   (!empty($item['current_product_name']) ? $item['current_product_name'] : 'Deleted Product');
            $item['display_image'] = !empty($snapshotImage) ? $snapshotImage : 
                                    (!empty($item['current_product_image']) ? $item['current_product_image'] : '');
            
            // is_deleted: product doesn't exist in products table anymore
            $item['is_deleted'] = empty($item['current_product_name']) ? 1 : 0;
            
            // Show unavailable if: product deleted OR inactive
            $item['use_placeholder'] = $item['is_deleted'] || ($item['is_active'] == 0);
        }
        unset($item); // Break reference
        
        return $items;
    }
    
    public function getAllOrders() {
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count 
                FROM orders o 
                INNER JOIN users u ON o.customer_id = u.id 
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getAllOrdersSorted($sortBy = 'created_at', $sortOrder = 'DESC') {
        // Validate sort column
        $allowedColumns = ['order_number', 'email', 'created_at', 'item_count', 'total_amount', 'order_status'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort order
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        // Map sort columns
        $sortColumn = $sortBy;
        if ($sortBy === 'order_number' || $sortBy === 'created_at' || $sortBy === 'total_amount' || $sortBy === 'order_status') {
            $sortColumn = 'o.' . $sortBy;
        } else if ($sortBy === 'email') {
            $sortColumn = 'u.email';
        } else if ($sortBy === 'item_count') {
            $sortColumn = 'item_count';
        }
        
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count 
                FROM orders o 
                INNER JOIN users u ON o.customer_id = u.id 
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                GROUP BY o.id 
                ORDER BY {$sortColumn} {$sortOrder}";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getOrdersByStatus($status) {
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count
                FROM orders o
                INNER JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.order_status = ?
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $status);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getOrdersByStatusSorted($status, $sortBy = 'created_at', $sortOrder = 'DESC') {
        // Validate sort column
        $allowedColumns = ['order_number', 'email', 'created_at', 'item_count', 'total_amount', 'order_status'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort order
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        // Map sort columns
        $sortColumn = $sortBy;
        if ($sortBy === 'order_number' || $sortBy === 'created_at' || $sortBy === 'total_amount' || $sortBy === 'order_status') {
            $sortColumn = 'o.' . $sortBy;
        } else if ($sortBy === 'email') {
            $sortColumn = 'u.email';
        } else if ($sortBy === 'item_count') {
            $sortColumn = 'item_count';
        }
        
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count
                FROM orders o
                INNER JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.order_status = ?
                GROUP BY o.id
                ORDER BY {$sortColumn} {$sortOrder}";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $status);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function searchOrders($search, $status = '') {
        $searchTerm = '%' . $search . '%';
        
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count
                FROM orders o
                INNER JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE (o.order_number LIKE ? 
                    OR u.email LIKE ? 
                    OR CAST(o.total_amount AS CHAR) LIKE ?)";
        
        if (!empty($status)) {
            $sql .= " AND o.order_status = ?";
        }
        
        $sql .= " GROUP BY o.id
                  ORDER BY o.created_at DESC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if (!empty($status)) {
            mysqli_stmt_bind_param($stmt, 'ssss', $searchTerm, $searchTerm, $searchTerm, $status);
        } else {
            mysqli_stmt_bind_param($stmt, 'sss', $searchTerm, $searchTerm, $searchTerm);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function searchOrdersSorted($search, $status = '', $sortBy = 'created_at', $sortOrder = 'DESC') {
        // Validate sort column
        $allowedColumns = ['order_number', 'email', 'created_at', 'item_count', 'total_amount', 'order_status'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort order
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        // Map sort columns
        $sortColumn = $sortBy;
        if ($sortBy === 'order_number' || $sortBy === 'created_at' || $sortBy === 'total_amount' || $sortBy === 'order_status') {
            $sortColumn = 'o.' . $sortBy;
        } else if ($sortBy === 'email') {
            $sortColumn = 'u.email';
        } else if ($sortBy === 'item_count') {
            $sortColumn = 'item_count';
        }
        
        $searchTerm = '%' . $search . '%';
        
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count
                FROM orders o
                INNER JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE (o.order_number LIKE ? 
                    OR u.email LIKE ? 
                    OR CAST(o.total_amount AS CHAR) LIKE ?)";
        
        if (!empty($status)) {
            $sql .= " AND o.order_status = ?";
        }
        
        $sql .= " GROUP BY o.id
                  ORDER BY {$sortColumn} {$sortOrder}";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if (!empty($status)) {
            mysqli_stmt_bind_param($stmt, 'ssss', $searchTerm, $searchTerm, $searchTerm, $status);
        } else {
            mysqli_stmt_bind_param($stmt, 'sss', $searchTerm, $searchTerm, $searchTerm);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getOrdersByCustomerAndStatus($customerId, $statuses) {
        // Get orders for a customer with specific statuses
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = "SELECT * FROM orders 
                WHERE customer_id = ? AND order_status IN ($placeholders)";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        // Bind parameters dynamically
        $types = 'i' . str_repeat('s', count($statuses));
        $params = array_merge([$customerId], $statuses);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function markAsCompleted($orderId, $customerId) {
    // Verify that the order belongs to the customer and is delivered or shipped
    // Allow customers to confirm receipt if the order is in 'delivered' or 'shipped' state
    $sql = "UPDATE orders 
        SET order_status = 'completed' 
        WHERE id = ? AND customer_id = ? AND order_status IN ('delivered','shipped')";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $customerId);
        return mysqli_stmt_execute($stmt);
    }
    
    public function cancelOrder($orderId) {
        $sql = "UPDATE orders SET order_status = 'cancelled' WHERE id = ? AND order_status = 'pending'";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        return mysqli_stmt_execute($stmt);
    }
    
    public function getCompletedOrdersCount() {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE order_status = 'completed'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] ?? 0;
    }
    
    public function getCompletedOrdersTotal() {
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'completed'";
        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }
    
    // Sales Report Methods
    public function getSalesByPeriod($startDate, $endDate) {
        $sql = "SELECT 
                COUNT(*) as order_count,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as avg_order_value,
                SUM(subtotal) as subtotal,
                SUM(shipping_cost) as shipping_total,
                SUM(tax_amount) as tax_total
                FROM orders 
                WHERE order_status = 'completed' 
                AND created_at >= ? 
                AND created_at <= ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    public function getDailySales($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';
        return $this->getSalesByPeriod($startDate, $endDate);
    }
    
    public function getWeeklySales() {
        $startDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $endDate = date('Y-m-d 23:59:59', strtotime('sunday this week'));
        return $this->getSalesByPeriod($startDate, $endDate);
    }
    
    public function getMonthlySales($month = null, $year = null) {
        if (!$month) $month = date('m');
        if (!$year) $year = date('Y');
        $startDate = "$year-$month-01 00:00:00";
        $endDate = date('Y-m-t 23:59:59', strtotime($startDate));
        return $this->getSalesByPeriod($startDate, $endDate);
    }
    
    public function getYearlySales($year = null) {
        if (!$year) $year = date('Y');
        $startDate = "$year-01-01 00:00:00";
        $endDate = "$year-12-31 23:59:59";
        return $this->getSalesByPeriod($startDate, $endDate);
    }
    
    public function getCustomRangeSales($startDate, $endDate) {
        // Ensure proper datetime format
        $start = date('Y-m-d 00:00:00', strtotime($startDate));
        $end = date('Y-m-d 23:59:59', strtotime($endDate));
        return $this->getSalesByPeriod($start, $end);
    }
    
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 10) {
        $sql = "SELECT 
                oi.product_name,
                COALESCE(NULLIF(oi.product_image, ''), p.img_path) as product_image,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.item_total) as total_revenue,
                COUNT(DISTINCT oi.order_id) as order_count,
                p.is_active as product_active,
                CASE WHEN p.id IS NULL THEN 1 ELSE 0 END as product_deleted
                FROM order_items oi
                INNER JOIN orders o ON oi.order_id = o.id
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE o.order_status = 'completed'";
        
        if ($startDate && $endDate) {
            $sql .= " AND o.created_at >= ? AND o.created_at <= ?";
        }
        
        $sql .= " GROUP BY oi.product_id, oi.product_name, COALESCE(NULLIF(oi.product_image, ''), p.img_path), p.is_active, CASE WHEN p.id IS NULL THEN 1 ELSE 0 END
                  ORDER BY total_quantity DESC
                  LIMIT ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($startDate && $endDate) {
            mysqli_stmt_bind_param($stmt, 'ssi', $startDate, $endDate, $limit);
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $limit);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getSalesOrdersList($startDate, $endDate) {
        $sql = "SELECT o.*, u.email, COUNT(oi.id) as item_count
                FROM orders o
                INNER JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.order_status = 'completed'
                AND o.created_at >= ?
                AND o.created_at <= ?
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Archive completed order to history table
    public function archiveCompletedOrder($orderId) {
        try {
            // Get order details
            $orderSql = "SELECT o.id, o.total_amount, o.created_at, u.first_name, u.last_name, u.email 
                        FROM orders o 
                        LEFT JOIN users u ON o.customer_id = u.id 
                        WHERE o.id = ?";
            
            $orderStmt = mysqli_prepare($this->conn, $orderSql);
            mysqli_stmt_bind_param($orderStmt, 'i', $orderId);
            mysqli_stmt_execute($orderStmt);
            $orderResult = mysqli_stmt_get_result($orderStmt);
            $order = mysqli_fetch_assoc($orderResult);
            
            if (!$order) return false;
            
            // Get order items
            $itemsSql = "SELECT product_name, quantity, unit_price FROM order_items WHERE order_id = ?";
            $itemsStmt = mysqli_prepare($this->conn, $itemsSql);
            mysqli_stmt_bind_param($itemsStmt, 'i', $orderId);
            mysqli_stmt_execute($itemsStmt);
            $itemsResult = mysqli_stmt_get_result($itemsStmt);
            $items = mysqli_fetch_all($itemsResult, MYSQLI_ASSOC);
            
            // Format items as JSON
            $itemsJson = json_encode($items);
            $customerName = ($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? '');
            
            // Save to history table
            $historySql = "INSERT INTO order_history (order_id, customer_name, customer_email, total_amount, items, created_at) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            
            $historyStmt = mysqli_prepare($this->conn, $historySql);
            mysqli_stmt_bind_param($historyStmt, 'issdss', 
                $orderId, 
                $customerName, 
                $order['email'], 
                $order['total_amount'], 
                $itemsJson, 
                $order['created_at']
            );
            
            return mysqli_stmt_execute($historyStmt);
            
        } catch (Exception $e) {
            error_log("Archive error: " . $e->getMessage());
            return false;
        }
    }
    
    // Get completed orders from history (works even if user/product deleted)
    public function getCompletedOrdersFromHistory($limit = 50) {
        $sql = "SELECT * FROM order_history ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Get sales report from history (includes deleted users/products)
    public function getSalesReportFromHistory($startDate = null, $endDate = null) {
        $sql = "SELECT 
                DATE(created_at) as sale_date,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as avg_order_value,
                COUNT(DISTINCT customer_email) as unique_customers
                FROM order_history";
        
        if ($startDate && $endDate) {
            $sql .= " WHERE created_at >= ? AND created_at <= ?";
        }
        
        $sql .= " GROUP BY DATE(created_at) ORDER BY created_at DESC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($startDate && $endDate) {
            mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Get total sales from history
    public function getTotalSalesFromHistory($startDate = null, $endDate = null) {
        $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as avg_order_value,
                COUNT(DISTINCT customer_email) as total_customers
                FROM order_history";
        
        if ($startDate && $endDate) {
            $sql .= " WHERE created_at >= ? AND created_at <= ?";
        }
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($startDate && $endDate) {
            mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    // Get top products sold from history
    public function getTopProductsFromHistory($limit = 10) {
        $sql = "SELECT 
                JSON_UNQUOTE(JSON_EXTRACT(oi.item, '$.product_name')) as product_name,
                SUM(CAST(JSON_EXTRACT(oi.item, '$.quantity') AS DECIMAL)) as total_quantity,
                SUM(CAST(JSON_EXTRACT(oi.item, '$.unit_price') AS DECIMAL) * 
                    CAST(JSON_EXTRACT(oi.item, '$.quantity') AS DECIMAL)) as total_revenue
                FROM order_history oh
                CROSS JOIN JSON_TABLE(
                    oh.items,
                    '$[*]' COLUMNS (
                        item JSON PATH '$'
                    )
                ) oi
                GROUP BY product_name
                ORDER BY total_revenue DESC
                LIMIT ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}