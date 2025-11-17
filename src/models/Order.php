<?php

require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel {
    
    protected $table = 'orders';
    
    public function create($customerId, $subtotal, $shippingCost, $taxAmount, $totalAmount) {
        $orderNumber = 'ORD-' . time() . '-' . $customerId;
        
        $sql = "INSERT INTO orders (customer_id, order_number, subtotal, shipping_cost, tax_amount, total_amount) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("create prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }

        // 'isdddd' for integer, string, double, double, double, double
        mysqli_stmt_bind_param($stmt, 'isdddd', $customerId, $orderNumber, $subtotal, $shippingCost, $taxAmount, $totalAmount);
        
        if (mysqli_stmt_execute($stmt)) {
            $lastId = $this->db->getLastId(); // Assuming getLastId() works for mysqli
            mysqli_stmt_close($stmt);
            return $lastId;
        }
        ErrorHandler::log("create execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
        mysqli_stmt_close($stmt);
        return false;
    }
    
    public function addOrderItem($orderId, $productId, $quantity, $unitPrice) {
        $itemTotal = $quantity * $unitPrice;
        
        // Get product name and image to store as snapshot
        $productSql = "SELECT product_name, img_path FROM products WHERE id = ?";
        $productStmt = $this->db->prepare($productSql);
        
        if ($productStmt === false) {
            ErrorHandler::log("addOrderItem productStmt prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        mysqli_stmt_bind_param($productStmt, 'i', $productId);
        if (!mysqli_stmt_execute($productStmt)) {
            ErrorHandler::log("addOrderItem productStmt execute failed: " . mysqli_stmt_error($productStmt), 'ERROR');
            mysqli_stmt_close($productStmt);
            return false;
        }
        $result = mysqli_stmt_get_result($productStmt);
        if ($result === false) {
            ErrorHandler::log("addOrderItem productStmt get_result failed: " . mysqli_stmt_error($productStmt), 'ERROR');
            mysqli_stmt_close($productStmt);
            return false;
        }
        $product = mysqli_fetch_assoc($result);
        mysqli_stmt_close($productStmt);
        
        $productName = $product['product_name'] ?? 'Unknown Product';
        $productImage = $product['img_path'] ?? '';
        
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_image, quantity, unit_price, item_total) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            ErrorHandler::log("addOrderItem prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        // 'iissddd' for integer, integer, string, string, double, double, double
        mysqli_stmt_bind_param($stmt, 'iissddd', $orderId, $productId, $productName, $productImage, $quantity, $unitPrice, $itemTotal);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            ErrorHandler::log("addOrderItem execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
        }
        mysqli_stmt_close($stmt);
        return $success;
    }
    
    public function updateStatus($orderId, $status) {
        $sql = "UPDATE orders SET order_status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("updateStatus prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        // 'si' for string, integer
        mysqli_stmt_bind_param($stmt, 'si', $status, $orderId);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            ErrorHandler::log("updateStatus execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
        }
        mysqli_stmt_close($stmt);
        return $success;
    }
    
    public function getCustomerOrders($customerId) {
        $sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getCustomerOrders prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        mysqli_stmt_bind_param($stmt, 'i', $customerId);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getCustomerOrders execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getCustomerOrders get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function getOrderDetails($orderId) {
        $sql = "SELECT o.*, 
                u.email, 
                u.is_active,
                u.first_name, 
                u.last_name, 
                u.phone, 
                u.address, 
                u.city, 
                u.postal_code, 
                u.country,
                u.profile_picture
                FROM orders o 
                LEFT JOIN users u ON o.customer_id = u.id 
                WHERE o.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getOrderDetails prepare failed: " . $this->db->getError(), 'ERROR');
            return null;
        }
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getOrderDetails execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return null;
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getOrderDetails get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return null;
        }
        $orderDetails = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $orderDetails;
    }
    
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, 
                p.is_active, 
                p.product_name as current_product_name, 
                COALESCE(pi.image_path, p.img_path) as current_product_image,
                oi.has_reviewed
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                WHERE oi.order_id = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getOrderItems prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getOrderItems execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getOrderItems get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        
        foreach ($items as &$item) {
            $snapshotName = $item['product_name'] ?? '';
            $snapshotImage = $item['product_image'] ?? '';
            
            $item['display_name'] = !empty($snapshotName) ? $snapshotName : 
                                   (!empty($item['current_product_name']) ? $item['current_product_name'] : 'Deleted Product');
            $item['display_image'] = !empty($item['current_product_image']) ? $item['current_product_image'] : 
                                    (!empty($snapshotImage) ? $snapshotImage : '');
            
            $item['is_deleted'] = empty($item['current_product_name']);
            $item['use_placeholder'] = $item['is_deleted'] || ($item['is_active'] == 0);
        }
        unset($item);
        
        return $items;
    }
    
    public function getAllOrders() {
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count 
                FROM orders o 
                LEFT JOIN users u ON o.customer_id = u.id 
                LEFT JOIN order_history oh ON o.id = oh.order_id 
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";
        $result = $this->db->query($sql); // Renamed $stmt to $result
        
        if ($result === false) {
            ErrorHandler::log("getAllOrders query failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result); // Free the result set
        return $orders;
    }
    
    public function getAllOrdersSorted($sortBy = 'created_at', $sortOrder = 'DESC') {
        $allowedColumns = ['order_number', 'email', 'created_at', 'item_count', 'total_amount', 'order_status'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        $sortColumn = 'o.' . $sortBy;
        if ($sortBy === 'email') {
            $sortColumn = 'COALESCE(u.email, oh.customer_email)';
        } else if ($sortBy === 'item_count') {
            $sortColumn = 'item_count';
        }
        
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count 
                FROM orders o 
                LEFT JOIN users u ON o.customer_id = u.id 
                LEFT JOIN order_history oh ON o.id = oh.order_id 
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                GROUP BY o.id 
                ORDER BY {$sortColumn} {$sortOrder}";
        $result = $this->db->query($sql); // Renamed $stmt to $result
        
        if ($result === false) {
            ErrorHandler::log("getAllOrdersSorted query failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result); // Free the result set
        return $orders;
    }
    
    public function getOrdersByStatus($status) {
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_history oh ON o.id = oh.order_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.order_status = ?
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getOrdersByStatus prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        mysqli_stmt_bind_param($stmt, 's', $status);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getOrdersByStatus execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getOrdersByStatus get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function getOrdersByStatusSorted($status, $sortBy = 'created_at', $sortOrder = 'DESC') {
        $allowedColumns = ['order_number', 'email', 'created_at', 'item_count', 'total_amount', 'order_status'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        $sortColumn = 'o.' . $sortBy;
        if ($sortBy === 'email') {
            $sortColumn = 'COALESCE(u.email, oh.customer_email)';
        } else if ($sortBy === 'item_count') {
            $sortColumn = 'item_count';
        }
        
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_history oh ON o.id = oh.order_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.order_status = ?
                GROUP BY o.id
                ORDER BY {$sortColumn} {$sortOrder}";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getOrdersByStatusSorted prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        mysqli_stmt_bind_param($stmt, 's', $status);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getOrdersByStatusSorted execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getOrdersByStatusSorted get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function searchOrders($search, $status = '') {
        $searchTerm = '%' . $search . '%';
        
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_history oh ON o.id = oh.order_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE (o.order_number LIKE ? 
                    OR COALESCE(u.email, oh.customer_email) LIKE ? 
                    OR CAST(o.total_amount AS CHAR) LIKE ?)";
        
        $params = [$searchTerm, $searchTerm, $searchTerm];
        $types = 'sss'; // three strings for the search terms
        
        if (!empty($status)) {
            $sql .= " AND o.order_status = ?";
            $params[] = $status;
            $types .= 's'; // add string type for status
        }
        
        $sql .= " GROUP BY o.id
                  ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("searchOrders prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        
        // Dynamically bind parameters
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("searchOrders execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("searchOrders get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function searchOrdersSorted($search, $status = '', $sortBy = 'created_at', $sortOrder = 'DESC') {
        $allowedColumns = ['order_number', 'email', 'created_at', 'item_count', 'total_amount', 'order_status'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        $sortColumn = 'o.' . $sortBy;
        if ($sortBy === 'email') {
            $sortColumn = 'COALESCE(u.email, oh.customer_email)';
        } else if ($sortBy === 'item_count') {
            $sortColumn = 'item_count';
        }
        
        $searchTerm = '%' . $search . '%';
        
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_history oh ON o.id = oh.order_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE (o.order_number LIKE ? 
                    OR COALESCE(u.email, oh.customer_email) LIKE ? 
                    OR CAST(o.total_amount AS CHAR) LIKE ?)";
        
        $params = [$searchTerm, $searchTerm, $searchTerm];
        $types = 'sss'; // three strings for the search terms
        
        if (!empty($status)) {
            $sql .= " AND o.order_status = ?";
            $params[] = $status;
            $types .= 's'; // add string type for status
        }
        
        $sql .= " GROUP BY o.id
                  ORDER BY {$sortColumn} {$sortOrder}";
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("searchOrdersSorted prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        
        // Dynamically bind parameters
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("searchOrdersSorted execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("searchOrdersSorted get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function getOrdersByCustomerAndStatus($customerId, $statuses) {
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = "SELECT * FROM orders 
                WHERE customer_id = ? AND order_status IN ($placeholders)";
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getOrdersByCustomerAndStatus prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }

        $params = array_merge([$customerId], $statuses);
        $types = 'i' . str_repeat('s', count($statuses)); // 'i' for customerId, 's' for each status

        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getOrdersByCustomerAndStatus execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getOrdersByCustomerAndStatus get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function markAsCompleted($orderId, $customerId) {
        $sql = "UPDATE orders 
                SET order_status = 'completed' 
                WHERE id = ? AND customer_id = ? AND order_status IN ('delivered','shipped')";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("markAsCompleted prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        // 'ii' for two integers
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $customerId);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            ErrorHandler::log("markAsCompleted execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
        }
        mysqli_stmt_close($stmt);
        return $success;
    }

    /**
     * Archive a completed order into order_history as a JSON snapshot of items and customer info.
     * Does not delete the original order or items; acts as a backup for display when products are removed.
     *
     * @param int $orderId
     * @return bool
     */
    public function archiveCompletedOrder($orderId) {
        // Get order details (includes customer info)
        $order = $this->getOrderDetails($orderId);
        if (!$order) {
            ErrorHandler::log("archiveCompletedOrder: order not found: {$orderId}", 'WARNING');
            return false;
        }

        // Fetch order items snapshot from order_items
        $sql = "SELECT product_name, quantity, unit_price FROM order_items WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            ErrorHandler::log("archiveCompletedOrder prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("archiveCompletedOrder execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return false;
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("archiveCompletedOrder get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return false;
        }
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        // Normalize items for history (keep product_name, quantity, unit_price as strings)
        $historyItems = [];
        foreach ($items as $it) {
            $historyItems[] = [
                'product_name' => $it['product_name'] ?? 'Unknown Product',
                'quantity' => (int)($it['quantity'] ?? 0),
                'unit_price' => number_format((float)($it['unit_price'] ?? 0), 2, '.', '')
            ];
        }

        $itemsJson = json_encode($historyItems, JSON_UNESCAPED_UNICODE);

        $customerName = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
        $customerEmail = $order['email'] ?? '';
        $totalAmount = isset($order['total_amount']) ? (float)$order['total_amount'] : 0.0;

        $insertSql = "INSERT INTO order_history (order_id, customer_name, customer_email, total_amount, items) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $this->db->prepare($insertSql);
        if ($insertStmt === false) {
            ErrorHandler::log("archiveCompletedOrder insert prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }

        // 'issds' -> integer, string, string, double, string
        mysqli_stmt_bind_param($insertStmt, 'issds', $orderId, $customerName, $customerEmail, $totalAmount, $itemsJson);
        $success = mysqli_stmt_execute($insertStmt);
        if (!$success) {
            ErrorHandler::log("archiveCompletedOrder insert execute failed: " . mysqli_stmt_error($insertStmt), 'ERROR');
        }
        mysqli_stmt_close($insertStmt);
        return $success;
    }
    
    public function cancelOrder($orderId) {
        $sql = "UPDATE orders SET order_status = 'cancelled' WHERE id = ? AND order_status = 'pending'";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("cancelOrder prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        // 'i' for integer
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            ErrorHandler::log("cancelOrder execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
        }
        mysqli_stmt_close($stmt);
        return $success;
    }
    
    public function getCompletedOrdersCount() {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE order_status = 'completed'";
        $result = $this->db->query($sql);
        
        if ($result === false) {
            ErrorHandler::log("getCompletedOrdersCount query failed: " . $this->db->getError(), 'ERROR');
            return 0;
        }
        $row = mysqli_fetch_row($result);
        mysqli_free_result($result);
        return $row[0] ?? 0;
    }
    
    public function getCompletedOrdersTotal() {
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'completed'";
        $result = $this->db->query($sql);
        
        if ($result === false) {
            ErrorHandler::log("getCompletedOrdersTotal query failed: " . $this->db->getError(), 'ERROR');
            return 0;
        }
        $row = mysqli_fetch_row($result);
        mysqli_free_result($result);
        return $row[0] ?? 0;
    }
    
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
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getSalesByPeriod prepare failed: " . $this->db->getError(), 'ERROR');
            return null;
        }
        // 'ss' for two strings
        mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getSalesByPeriod execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return null;
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getSalesByPeriod get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return null;
        }
        $salesData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $salesData;
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
        $start = date('Y-m-d 00:00:00', strtotime($startDate));
        $end = date('Y-m-d 23:59:59', strtotime($endDate));
        return $this->getSalesByPeriod($start, $end);
    }
    
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 10) {
        $sql = "SELECT 
                oi.product_name,
                COALESCE(pi.image_path, NULLIF(oi.product_image, ''), p.img_path) as product_image,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.item_total) as total_revenue,
                COUNT(DISTINCT oi.order_id) as order_count,
                COALESCE(p.is_active, 0) as product_active,
                CASE WHEN p.id IS NULL THEN 1 ELSE 0 END as product_deleted
                FROM order_items oi
                INNER JOIN orders o ON oi.order_id = o.id
                LEFT JOIN products p ON oi.product_id = p.id
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE o.order_status = 'completed'";
        
        $params = [];
        $types = '';

        if ($startDate && $endDate) {
            $sql .= " AND o.created_at >= ? AND o.created_at <= ?";
            $params[] = $startDate;
            $params[] = $endDate;
            $types .= 'ss'; // two strings for start and end date
        }
        
        $sql .= " GROUP BY oi.product_id, oi.product_name, COALESCE(pi.image_path, NULLIF(oi.product_image, ''), p.img_path), COALESCE(p.is_active, 0), CASE WHEN p.id IS NULL THEN 1 ELSE 0 END
                  ORDER BY total_quantity DESC
                  LIMIT ?";
        $params[] = (int)$limit;
        $types .= 'i'; // integer for limit
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getTopSellingProducts prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        
        // Dynamically bind parameters
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getTopSellingProducts execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getTopSellingProducts get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $products;
    }    
    public function getSalesOrdersList($startDate, $endDate) {
        $sql = "SELECT o.*, 
                COALESCE(u.email, oh.customer_email) as email, 
                u.is_active, 
                COUNT(oi.id) as item_count
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN order_history oh ON o.id = oh.order_id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.order_status = 'completed'";
        
        $params = [];
        $types = '';
        
        if ($startDate && $endDate) {
            $sql .= " AND o.created_at >= ? AND o.created_at <= ?";
            $params = [$startDate, $endDate];
            $types = 'ss';
        }
        
        $sql .= " GROUP BY o.id ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getSalesOrdersList prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getSalesOrdersList execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getSalesOrdersList get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }    
    public function hasUserPurchasedProduct($userId, $productId) {
    // Only consider orders with status 'completed' as eligible purchases for reviews
    $sql = "SELECT COUNT(*) 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        WHERE o.customer_id = ? 
        AND oi.product_id = ? 
        AND o.order_status = 'completed'";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("hasUserPurchasedProduct prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }

        mysqli_stmt_bind_param($stmt, 'ii', $userId, $productId); // 'ii' for two integers
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("hasUserPurchasedProduct execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return false;
        }

        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("hasUserPurchasedProduct get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return false;
        }

        $row = mysqli_fetch_row($result);
        mysqli_stmt_close($stmt);

        $count = isset($row[0]) ? (int)$row[0] : 0;
        return $count > 0;
    }

    public function getSalesReportFromHistory($startDate, $endDate) {
        $sql = "SELECT 
                COUNT(*) as order_count,
                SUM(total_amount) as total_sales
                FROM order_history";
        
        $params = [];
        $types = '';
        
        if ($startDate && $endDate) {
            $sql .= " WHERE created_at >= ? AND created_at <= ?";
            $params = [$startDate, $endDate];
            $types = 'ss';
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getSalesReportFromHistory prepare failed: " . $this->db->getError(), 'ERROR');
            return ['order_count' => 0, 'total_sales' => 0];
        }
        
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getSalesReportFromHistory execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return ['order_count' => 0, 'total_sales' => 0];
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getSalesReportFromHistory get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return ['order_count' => 0, 'total_sales' => 0];
        }
        $salesData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $salesData;
    }

    public function getAllTimeSales() {
        $sql = "SELECT COUNT(*) as order_count, SUM(total_amount) as total_sales, AVG(total_amount) as avg_order_value
                FROM orders WHERE order_status = 'completed'";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getAllTimeSales prepare failed: " . $this->db->getError(), 'ERROR');
            return ['order_count' => 0, 'total_sales' => 0, 'avg_order_value' => 0];
        }
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getAllTimeSales execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return ['order_count' => 0, 'total_sales' => 0, 'avg_order_value' => 0];
        }
        
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getAllTimeSales get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return ['order_count' => 0, 'total_sales' => 0, 'avg_order_value' => 0];
        }
        
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $data ?: ['order_count' => 0, 'total_sales' => 0, 'avg_order_value' => 0];
    }
    
    public function getCompletedOrdersFromHistory($limit = 1000) {
        $sql = "SELECT * FROM order_history ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getCompletedOrdersFromHistory prepare failed: " . $this->db->getError(), 'ERROR');
            return [];
        }
        
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getCompletedOrdersFromHistory execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getCompletedOrdersFromHistory get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return [];
        }
        
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $orders;
    }
    
    public function getOrderFromHistory($orderId) {
        $sql = "SELECT * FROM order_history WHERE order_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getOrderFromHistory prepare failed: " . $this->db->getError(), 'ERROR');
            return null;
        }
        
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("getOrderFromHistory execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return null;
        }
        
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("getOrderFromHistory get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return null;
        }
        
        $order = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $order;
    }
    
    public function hasPendingOrders($customerId) {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE customer_id = ? AND order_status IN ('pending', 'processing', 'shipped')";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt === false) {
            ErrorHandler::log("hasPendingOrders prepare failed: " . $this->db->getError(), 'ERROR');
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $customerId);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorHandler::log("hasPendingOrders execute failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return false;
        }
        $result = mysqli_stmt_get_result($stmt);
        if ($result === false) {
            ErrorHandler::log("hasPendingOrders get_result failed: " . mysqli_stmt_error($stmt), 'ERROR');
            mysqli_stmt_close($stmt);
            return false;
        }
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return ($row['count'] ?? 0) > 0;
    }
}
