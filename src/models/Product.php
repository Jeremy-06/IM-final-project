<?php

require_once __DIR__ . '/BaseModel.php';

class Product extends BaseModel {
    
    protected $table = 'products';
    
    public function create($categoryId, $productName, $description, $costPrice, $sellingPrice, $supplierId = null, $imgPath = '') {
        $sql = "INSERT INTO products (category_id, product_name, description, cost_price, selling_price, supplier_id, img_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isssdis', $categoryId, $productName, $description, $costPrice, $sellingPrice, $supplierId, $imgPath);
        
        if ($stmt->execute()) {
            $productId = $this->conn->insert_id;
            $invSql = "INSERT INTO inventory (product_id, quantity_on_hand) VALUES (?, 0)";
            $invStmt = $this->conn->prepare($invSql);
            $invStmt->bind_param('i', $productId);
            $invStmt->execute();
            return $productId;
        }
        return false;
    }
    
    public function update($id, $categoryId, $productName, $description, $costPrice, $sellingPrice, $supplierId = null, $imgPath = null, $isActive = null) {
        $updates = [];
        $params = [];
        $types = '';
        
        $updates[] = "category_id = ?";
        $params[] = $categoryId;
        $types .= 'i';
        
        $updates[] = "product_name = ?";
        $params[] = $productName;
        $types .= 's';
        
        $updates[] = "description = ?";
        $params[] = $description;
        $types .= 's';
        
        $updates[] = "cost_price = ?";
        $params[] = $costPrice;
        $types .= 'd';
        
        $updates[] = "selling_price = ?";
        $params[] = $sellingPrice;
        $types .= 'd';
        
        $updates[] = "supplier_id = ?";
        $params[] = $supplierId;
        $types .= 'i';
        
        if ($imgPath !== null) {
            $updates[] = "img_path = ?";
            $params[] = $imgPath;
            $types .= 's';
        }
        
        if ($isActive !== null) {
            $updates[] = "is_active = ?";
            $params[] = $isActive;
            $types .= 'i';
        }
        
        $params[] = $id;
        $types .= 'i';
        
        $sql = "UPDATE products SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }
    
    public function getWithCategory() {
        $sql = "SELECT p.*, c.category_name, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                LEFT JOIN inventory i ON p.id = i.product_id 
                WHERE p.is_active = 1 
                ORDER BY p.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function searchAndSortProducts($search = '', $sortBy = 'created_at', $sortOrder = 'DESC') {
        $allowedColumns = ['product_name', 'category_name', 'supplier_name', 'cost_price', 'selling_price', 'quantity_on_hand', 'created_at', 'is_active'];
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'created_at';
        }
        
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        
        $sortColumn = 'p.' . $sortBy;
        if ($sortBy === 'category_name') {
            $sortColumn = 'c.category_name';
        } elseif ($sortBy === 'supplier_name') {
            $sortColumn = 's.supplier_name';
        } elseif ($sortBy === 'quantity_on_hand') {
            $sortColumn = 'i.quantity_on_hand';
        }
        
        $sql = "SELECT p.*, c.category_name, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                LEFT JOIN inventory i ON p.id = i.product_id";
        
        if (!empty($search)) {
            $sql .= " WHERE (p.product_name LIKE ? OR p.description LIKE ? OR c.category_name LIKE ? OR s.supplier_name LIKE ?)";
        }
        
        $sql .= " ORDER BY $sortColumn $sortOrder";
        
        $stmt = $this->conn->prepare($sql);

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getActiveProducts() {
        $sql = "SELECT p.*, c.category_name, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                INNER JOIN inventory i ON p.id = i.product_id 
                WHERE p.is_active = 1 AND i.quantity_on_hand > 0 
                ORDER BY p.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getActiveProductsPaginated($limit = 9, $offset = 0) {
        $sql = "SELECT p.*, c.category_name, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                INNER JOIN inventory i ON p.id = i.product_id 
                WHERE p.is_active = 1 AND i.quantity_on_hand > 0 
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function countActiveProducts() {
        $sql = "SELECT COUNT(*) 
                FROM products p 
                INNER JOIN inventory i ON p.id = i.product_id 
                WHERE p.is_active = 1 AND i.quantity_on_hand > 0";
        $result = $this->conn->query($sql);
        return $result->fetch_row()[0];
    }
    
    public function getByCategory($categoryId) {
        $sql = "SELECT p.*, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                INNER JOIN inventory i ON p.id = i.product_id 
                WHERE p.category_id = ? AND p.is_active = 1 AND i.quantity_on_hand > 0 
                ORDER BY p.product_name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getByCategoryPaginated($categoryId, $limit = 9, $offset = 0) {
        $sql = "SELECT p.*, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                INNER JOIN inventory i ON p.id = i.product_id 
                WHERE p.category_id = ? AND p.is_active = 1 AND i.quantity_on_hand > 0 
                ORDER BY p.product_name ASC
                LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iii', $categoryId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function countByCategory($categoryId) {
        $sql = "SELECT COUNT(*) 
                FROM products p 
                INNER JOIN inventory i ON p.id = i.product_id 
                WHERE p.category_id = ? AND p.is_active = 1 AND i.quantity_on_hand > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0];
    }
    
    public function search($keyword) {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT p.*, c.category_name, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                LEFT JOIN inventory i ON p.id = i.product_id 
                WHERE (p.product_name LIKE ? OR p.description LIKE ?) AND p.is_active = 1 
                ORDER BY p.product_name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function searchPaginated($keyword, $limit = 9, $offset = 0) {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT p.*, c.category_name, s.supplier_name, i.quantity_on_hand 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id
                LEFT JOIN inventory i ON p.id = i.product_id 
                WHERE (p.product_name LIKE ? OR p.description LIKE ?) AND p.is_active = 1 
                ORDER BY p.product_name ASC
                LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssii', $searchTerm, $searchTerm, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function countSearch($keyword) {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT COUNT(*) 
                FROM products p 
                LEFT JOIN inventory i ON p.id = i.product_id 
                WHERE (p.product_name LIKE ? OR p.description LIKE ?) AND p.is_active = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0];
    }
    
    public function updateInventory($productId, $quantity) {
        $sql = "UPDATE inventory SET quantity_on_hand = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $quantity, $productId);
        return $stmt->execute();
    }
    
    public function getInventory($productId) {
        $sql = "SELECT quantity_on_hand FROM inventory WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] ?? 0;
    }
    
    public function findByIdWithDetails($productId) {
        $sql = "SELECT p.*, c.category_name, s.supplier_name 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id 
                WHERE p.id = ? 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function hasOrderItems($productId) {
        $sql = "SELECT COUNT(*) FROM order_items WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }
    
    public function hasActiveOrderItems($productId) {
        $sql = "SELECT COUNT(*) 
                FROM order_items oi 
                INNER JOIN orders o ON oi.order_id = o.id 
                WHERE oi.product_id = ? 
                AND o.order_status NOT IN ('completed', 'cancelled')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }
    
    public function deleteWithOrderItems($productId) {
        $this->db->beginTransaction();
        try {
            $product = $this->findById($productId);
            $imagePath = $product['img_path'] ?? null;
            $productImages = $this->getProductImages($productId);
            
            $sql = "UPDATE order_items SET 
                    product_name = COALESCE(product_name, ?),
                    product_image = COALESCE(product_image, ?)
                    WHERE product_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssi', $product['product_name'], $product['img_path'], $productId);
            $stmt->execute();
            
            foreach ($productImages as $image) {
                $fullImagePath = __DIR__ . '/../../public/uploads/' . $image['image_path'];
                if (file_exists($fullImagePath)) {
                    @unlink($fullImagePath);
                }
            }
            
            $sql = "DELETE FROM product_images WHERE product_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            
            $sql = "DELETE FROM inventory WHERE product_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            
            if (!empty($imagePath)) {
                $fullImagePath = __DIR__ . '/../../public/uploads/' . $imagePath;
                if (file_exists($fullImagePath)) {
                    @unlink($fullImagePath);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function getProductImages($productId) {
        $sql = "SELECT * FROM product_images 
                WHERE product_id = ? 
                ORDER BY is_primary DESC, display_order ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function addProductImage($productId, $imagePath, $displayOrder = 0, $isPrimary = false) {
        if ($isPrimary) {
            $this->unsetPrimaryImage($productId);
        }
        
        $sql = "INSERT INTO product_images (product_id, image_path, display_order, is_primary) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $isPrimaryInt = (int)$isPrimary;
        $stmt->bind_param('isii', $productId, $imagePath, $displayOrder, $isPrimaryInt);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function deleteProductImage($imageId) {
        $sql = "SELECT image_path FROM product_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        
        if (!$image) {
            return false;
        }
        
        $sql = "DELETE FROM product_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $imageId);
        
        if ($stmt->execute()) {
            $fullImagePath = __DIR__ . '/../../public/uploads/' . $image['image_path'];
            if (file_exists($fullImagePath)) {
                @unlink($fullImagePath);
            }
            return true;
        }
        return false;
    }
    
    public function setPrimaryImage($imageId) {
        $sql = "SELECT product_id FROM product_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        
        if (!$image) {
            return false;
        }
        
        $this->unsetPrimaryImage($image['product_id']);
        
        $sql = "UPDATE product_images SET is_primary = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $imageId);
        return $stmt->execute();
    }
    
    private function unsetPrimaryImage($productId) {
        $sql = "UPDATE product_images SET is_primary = 0 WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        return $stmt->execute();
    }
    
    public function updateImageOrder($imageId, $displayOrder) {
        $sql = "UPDATE product_images SET display_order = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $displayOrder, $imageId);
        return $stmt->execute();
    }

    public function updateRating($productId, $averageRating, $reviewCount) {
        $sql = "UPDATE products SET average_rating = ?, review_count = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('dii', $averageRating, $reviewCount, $productId);
        return $stmt->execute();
    }

    public function hasUserReviewedProduct($userId, $productId) {
        $sql = "SELECT COUNT(*) FROM reviews WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_row()[0] > 0;
    }

    public function setReviewFlag($orderItemId, $hasReviewed) {
        $sql = "UPDATE order_items SET has_reviewed = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $hasReviewedInt = (int)$hasReviewed;
        $stmt->bind_param('ii', $hasReviewedInt, $orderItemId);
        return $stmt->execute();
    }
}
