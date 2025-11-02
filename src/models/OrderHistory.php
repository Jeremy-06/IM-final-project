<?php

/**
 * OrderHistory Model Methods
 * 
 * This file contains the methods to be added to the Order model
 * for handling order history archiving and retrieval
 */

// ============================================================================
// ADD THESE METHODS TO src/models/Order.php
// ============================================================================

/**
 * Archive a completed order to the order_history tables
 * 
 * @param int $orderId The order ID to archive
 * @return bool|int Archive ID on success, false on failure
 */
public function archiveCompletedOrder($orderId) {
    try {
        // Check if order exists and is completed
        $order = $this->getOrderDetails($orderId);
        
        if (!$order || $order['order_status'] !== 'completed') {
            ErrorHandler::log("Cannot archive order {$orderId}: not found or not completed", 'WARNING');
            return false;
        }
        
        // Check if already archived
        $sql = "SELECT id FROM order_history WHERE order_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $orderId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            ErrorHandler::log("Order {$orderId} already archived", 'WARNING');
            return false;
        }
        
        // Get order items count
        $itemCountSql = "SELECT COUNT(*) as count FROM order_items WHERE order_id = ?";
        $itemCountStmt = mysqli_prepare($this->conn, $itemCountSql);
        mysqli_stmt_bind_param($itemCountStmt, 'i', $orderId);
        mysqli_stmt_execute($itemCountStmt);
        $itemCountResult = mysqli_stmt_get_result($itemCountStmt);
        $itemCountRow = mysqli_fetch_assoc($itemCountResult);
        $itemCount = $itemCountRow['count'] ?? 0;
        
        // Extract customer info from order (safe handling if user was deleted)
        $customerId = $order['customer_id'] ?? null;
        $customerEmail = $order['email'] ?? 'Unknown';
        $customerFirstName = $order['first_name'] ?? 'Unknown';
        $customerLastName = $order['last_name'] ?? 'Unknown';
        $customerPhone = $order['phone'] ?? '';
        $customerAddress = $order['address'] ?? '';
        $customerCity = $order['city'] ?? '';
        $customerPostalCode = $order['postal_code'] ?? '';
        $customerCountry = $order['country'] ?? 'Unknown';
        
        // Start transaction
        mysqli_begin_transaction($this->conn);
        
        try {
            // Insert into order_history
            $archiveSql = "INSERT INTO order_history (
                order_id,
                original_order_number,
                customer_id,
                customer_email,
                customer_first_name,
                customer_last_name,
                customer_phone,
                customer_address,
                customer_city,
                customer_postal_code,
                customer_country,
                order_status,
                subtotal,
                shipping_cost,
                tax_amount,
                total_amount,
                item_count,
                order_created_at,
                order_completed_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $archiveStmt = mysqli_prepare($this->conn, $archiveSql);
            
            if ($archiveStmt === false) {
                throw new Exception("Archive prepare failed: " . mysqli_error($this->conn));
            }
            
            mysqli_stmt_bind_param(
                $archiveStmt,
                'isissssssssddddi ss',
                $orderId,
                $order['order_number'],
                $customerId,
                $customerEmail,
                $customerFirstName,
                $customerLastName,
                $customerPhone,
                $customerAddress,
                $customerCity,
                $customerPostalCode,
                $customerCountry,
                $order['order_status'],
                $order['subtotal'],
                $order['shipping_cost'],
                $order['tax_amount'],
                $order['total_amount'],
                $itemCount,
                $order['created_at'],
                $order['updated_at']
            );
            
            if (!mysqli_stmt_execute($archiveStmt)) {
                throw new Exception("Archive execute failed: " . mysqli_error($this->conn));
            }
            
            $archiveId = mysqli_insert_id($this->conn);
            
            // Archive order items
            $this->archiveOrderItems($archiveId, $orderId);
            
            // Mark order as archived
            $updateSql = "UPDATE orders SET archived = TRUE WHERE id = ?";
            $updateStmt = mysqli_prepare($this->conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, 'i', $orderId);
            
            if (!mysqli_stmt_execute($updateStmt)) {
                throw new Exception("Update archive flag failed: " . mysqli_error($this->conn));
            }
            
            // Commit transaction
            mysqli_commit($this->conn);
            
            ErrorHandler::log("Order {$orderId} archived successfully with archive ID {$archiveId}", 'INFO');
            return $archiveId;
            
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            ErrorHandler::log("Archive transaction failed: " . $e->getMessage(), 'ERROR', ['order_id' => $orderId]);
            return false;
        }
        
    } catch (Exception $e) {
        ErrorHandler::log("archiveCompletedOrder exception: " . $e->getMessage(), 'ERROR', ['order_id' => $orderId]);
        return false;
    }
}

/**
 * Archive order items for a completed order
 * 
 * @param int $archiveId The order_history ID
 * @param int $orderId The original order ID
 * @return bool Success status
 */
private function archiveOrderItems($archiveId, $orderId) {
    try {
        $sql = "INSERT INTO order_history_items (
            order_history_id,
            original_item_id,
            product_id,
            product_name,
            product_image,
            product_category,
            product_cost_price,
            product_selling_price,
            quantity,
            unit_price,
            item_total
        )
        SELECT 
            ? as order_history_id,
            oi.id,
            oi.product_id,
            COALESCE(oi.product_name, p.product_name, 'Unknown Product'),
            COALESCE(oi.product_image, p.img_path, ''),
            COALESCE(c.category_name, 'Unknown'),
            p.cost_price,
            oi.unit_price,
            oi.quantity,
            oi.unit_price,
            oi.item_total
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE oi.order_id = ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("Archive items prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return false;
        }
        
        mysqli_stmt_bind_param($stmt, 'ii', $archiveId, $orderId);
        return mysqli_stmt_execute($stmt);
        
    } catch (Exception $e) {
        ErrorHandler::log("archiveOrderItems exception: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

/**
 * Get all completed orders for a specific customer
 * 
 * @param int $customerId The customer ID
 * @return array List of completed orders
 */
public function getCompletedOrdersByCustomer($customerId) {
    try {
        $sql = "SELECT * FROM orders 
                WHERE customer_id = ? AND order_status = 'completed' 
                ORDER BY created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getCompletedOrdersByCustomer prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return [];
        }
        
        mysqli_stmt_bind_param($stmt, 'i', $customerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    } catch (Exception $e) {
        ErrorHandler::log("getCompletedOrdersByCustomer exception: " . $e->getMessage(), 'ERROR');
        return [];
    }
}

/**
 * Get archived orders by customer email (survives user deletion)
 * 
 * @param string $customerEmail The customer email
 * @return array List of archived orders
 */
public function getArchivedOrdersByEmail($customerEmail) {
    try {
        $sql = "SELECT * FROM order_history 
                WHERE customer_email = ? 
                ORDER BY archived_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getArchivedOrdersByEmail prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return [];
        }
        
        mysqli_stmt_bind_param($stmt, 's', $customerEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    } catch (Exception $e) {
        ErrorHandler::log("getArchivedOrdersByEmail exception: " . $e->getMessage(), 'ERROR');
        return [];
    }
}

/**
 * Get all archived orders with their items
 * 
 * @param int $limit Maximum number of orders to return
 * @param int $offset Pagination offset
 * @return array List of archived orders
 */
public function getAllArchivedOrders($limit = 50, $offset = 0) {
    try {
        $sql = "SELECT * FROM order_history 
                ORDER BY archived_at DESC 
                LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getAllArchivedOrders prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return [];
        }
        
        mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    } catch (Exception $e) {
        ErrorHandler::log("getAllArchivedOrders exception: " . $e->getMessage(), 'ERROR');
        return [];
    }
}

/**
 * Get archived order items by archive ID
 * 
 * @param int $archiveId The order_history ID
 * @return array List of items
 */
public function getArchivedOrderItems($archiveId) {
    try {
        $sql = "SELECT * FROM order_history_items 
                WHERE order_history_id = ? 
                ORDER BY id ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getArchivedOrderItems prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return [];
        }
        
        mysqli_stmt_bind_param($stmt, 'i', $archiveId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    } catch (Exception $e) {
        ErrorHandler::log("getArchivedOrderItems exception: " . $e->getMessage(), 'ERROR');
        return [];
    }
}

/**
 * Get sales summary from archived orders (survives deletions)
 * 
 * @param string $startDate Start date (YYYY-MM-DD)
 * @param string $endDate End date (YYYY-MM-DD)
 * @return array|null Sales summary
 */
public function getArchivedSalesSummary($startDate, $endDate) {
    try {
        $sql = "SELECT 
                COUNT(*) as order_count,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as avg_order_value,
                SUM(subtotal) as subtotal,
                SUM(shipping_cost) as shipping_total,
                SUM(tax_amount) as tax_total,
                COUNT(DISTINCT customer_email) as unique_customers
                FROM order_history 
                WHERE archived_at >= ? 
                AND archived_at <= ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getArchivedSalesSummary prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return null;
        }
        
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';
        
        mysqli_stmt_bind_param($stmt, 'ss', $startDateTime, $endDateTime);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
        
    } catch (Exception $e) {
        ErrorHandler::log("getArchivedSalesSummary exception: " . $e->getMessage(), 'ERROR');
        return null;
    }
}

/**
 * Get top selling products from archived orders (survives product deletion)
 * 
 * @param string $startDate Start date (YYYY-MM-DD)
 * @param string $endDate End date (YYYY-MM-DD)
 * @param int $limit Maximum number of products
 * @return array List of top products
 */
public function getArchivedTopSellingProducts($startDate, $endDate, $limit = 10) {
    try {
        $sql = "SELECT 
                ohi.product_id,
                ohi.product_name,
                ohi.product_image,
                ohi.product_category,
                SUM(ohi.quantity) as total_quantity,
                SUM(ohi.item_total) as total_revenue,
                COUNT(DISTINCT ohi.order_history_id) as order_count,
                AVG(ohi.unit_price) as avg_price
                FROM order_history_items ohi
                INNER JOIN order_history oh ON ohi.order_history_id = oh.id
                WHERE oh.archived_at >= ? 
                AND oh.archived_at <= ?
                GROUP BY ohi.product_id, ohi.product_name
                ORDER BY total_quantity DESC
                LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getArchivedTopSellingProducts prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return [];
        }
        
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';
        
        mysqli_stmt_bind_param($stmt, 'ssi', $startDateTime, $endDateTime, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    } catch (Exception $e) {
        ErrorHandler::log("getArchivedTopSellingProducts exception: " . $e->getMessage(), 'ERROR');
        return [];
    }
}

/**
 * Get customer lifetime value from archived orders (survives user deletion)
 * 
 * @param string $customerEmail Customer email
 * @return array|null Customer lifetime stats
 */
public function getCustomerLifetimeValueArchived($customerEmail) {
    try {
        $sql = "SELECT 
                COUNT(*) as lifetime_orders,
                SUM(total_amount) as lifetime_revenue,
                AVG(total_amount) as avg_order_value,
                MIN(archived_at) as first_order_date,
                MAX(archived_at) as last_order_date
                FROM order_history 
                WHERE customer_email = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt === false) {
            ErrorHandler::log("getCustomerLifetimeValueArchived prepare failed: " . mysqli_error($this->conn), 'ERROR');
            return null;
        }
        
        mysqli_stmt_bind_param($stmt, 's', $customerEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
        
    } catch (Exception $e) {
        ErrorHandler::log("getCustomerLifetimeValueArchived exception: " . $e->getMessage(), 'ERROR');
        return null;
    }
}
