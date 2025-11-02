<?php

require_once __DIR__ . '/BaseModel.php';

class Supplier extends BaseModel {
    
    protected $table = 'suppliers';
    
    public function create($supplierName, $contactPerson = null, $phone = null, $email = null, $address = null) {
        $sql = "INSERT INTO suppliers (supplier_name, contact_person, phone, email, address) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss', $supplierName, $contactPerson, $phone, $email, $address);
        
        if (mysqli_stmt_execute($stmt)) {
            return mysqli_insert_id($this->conn);
        }
        return false;
    }
    
    public function update($id, $supplierName, $contactPerson = null, $phone = null, $email = null, $address = null) {
        $sql = "UPDATE suppliers 
                SET supplier_name = ?, contact_person = ?, phone = ?, email = ?, address = ?
                WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssssi', $supplierName, $contactPerson, $phone, $email, $address, $id);
        return mysqli_stmt_execute($stmt);
    }
    
    public function delete($id) {
        // Check if supplier is used in products
        $sql = "SELECT COUNT(*) as count FROM products WHERE supplier_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        if ($row['count'] > 0) {
            return false; // Cannot delete supplier with associated products
        }
        
        $sql = "DELETE FROM suppliers WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt);
    }
    
    public function getAll() {
        $sql = "SELECT * FROM suppliers ORDER BY supplier_name ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function search($keyword) {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT * FROM suppliers 
                WHERE supplier_name LIKE ? OR contact_person LIKE ? OR email LIKE ? OR phone LIKE ?
                ORDER BY supplier_name ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getProductCount($supplierId) {
        $sql = "SELECT COUNT(*) as count FROM products WHERE supplier_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $supplierId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] ?? 0;
    }
}
