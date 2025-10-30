<?php

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validation.php';
require_once __DIR__ . '/../helpers/CSRF.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $userModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->userModel = new User();
    }

    public function dashboard() {
        $orders = $this->orderModel->getAllOrders();
        $products = $this->productModel->getWithCategory();
        $customers = $this->userModel->getAllCustomers();
        $totalProducts = count($products);
        $totalOrders = count($orders);
        $totalCustomers = count($customers);
        $pendingOrders = 0;
        foreach ($orders as $o) {
            if (($o['order_status'] ?? '') === 'pending') {
                $pendingOrders++;
            }
        }
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function products() {
        $products = $this->productModel->getWithCategory();
        $categories = $this->categoryModel->getActive();
        include __DIR__ . '/../views/admin/products.php';
    }

    public function createProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                Session::setFlash('message', 'Invalid request');
                header('Location: admin.php?page=create_product');
                exit();
            }
            $validator = new Validation();
            $validator->required('product_name', $_POST['product_name'] ?? '')
                      ->required('category_id', $_POST['category_id'] ?? '')
                      ->required('cost_price', $_POST['cost_price'] ?? '')
                      ->required('selling_price', $_POST['selling_price'] ?? '');

            if ($validator->hasErrors()) {
                Session::setFlash('message', 'Please fill in all required fields');
                header('Location: admin.php?page=create_product');
                exit();
            }

            $imgPath = '';
            if (!empty($_FILES['img_path']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $filename = time() . '_' . basename($_FILES['img_path']['name']);
                if (move_uploaded_file($_FILES['img_path']['tmp_name'], $uploadDir . $filename)) {
                    $imgPath = $filename;
                }
            }

            $created = $this->productModel->create(
                intval($_POST['category_id']),
                trim($_POST['product_name']),
                trim($_POST['description'] ?? ''),
                floatval($_POST['cost_price']),
                floatval($_POST['selling_price']),
                $imgPath
            );

            if ($created) {
                // Apply initial inventory if provided
                if (isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
                    $this->productModel->updateInventory($created, intval($_POST['quantity']));
                }
                Session::setFlash('success', 'Product created');
                header('Location: admin.php?page=products');
            } else {
                Session::setFlash('message', 'Failed to create product');
                header('Location: admin.php?page=create_product');
            }
            exit();
        }
        $categories = $this->categoryModel->getActive();
        include __DIR__ . '/../views/admin/product_create.php';
    }

    public function updateProduct() {
        if (!isset($_GET['id'])) {
            header('Location: admin.php?page=products');
            exit();
        }
        $id = intval($_GET['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                Session::setFlash('message', 'Invalid request');
                header('Location: admin.php?page=edit_product&id=' . $id);
                exit();
            }
            $imgPath = null;
            if (!empty($_FILES['img_path']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $filename = time() . '_' . basename($_FILES['img_path']['name']);
                if (move_uploaded_file($_FILES['img_path']['tmp_name'], $uploadDir . $filename)) {
                    $imgPath = $filename;
                }
            }
            $ok = $this->productModel->update(
                $id,
                intval($_POST['category_id']),
                trim($_POST['product_name']),
                trim($_POST['description'] ?? ''),
                floatval($_POST['cost_price']),
                floatval($_POST['selling_price']),
                $imgPath
            );
            if ($ok) {
                if (isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
                    $this->productModel->updateInventory($id, intval($_POST['quantity']));
                }
                Session::setFlash('success', 'Product updated');
            } else {
                Session::setFlash('message', 'Failed to update product');
            }
            header('Location: admin.php?page=products');
            exit();
        }
        $product = $this->productModel->findById($id);
        $categories = $this->categoryModel->getActive();
        $inventory = $this->productModel->getInventory($id);
        include __DIR__ . '/../views/admin/product_edit';
    }

    public function deleteProduct() {
        if (!isset($_GET['id'])) {
            header('Location: admin.php?page=products');
            exit();
        }
        $id = intval($_GET['id']);
        if ($this->productModel->delete($id)) {
            Session::setFlash('success', 'Product deleted');
        } else {
            Session::setFlash('message', 'Failed to delete product');
        }
        header('Location: admin.php?page=products');
        exit();
    }

    public function orders() {
        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $status = $_GET['status'];
            $orders = $this->orderModel->getOrdersByStatus($status);
        } else {
            $orders = $this->orderModel->getAllOrders();
        }
        include __DIR__ . '/../views/admin/orders.php';
    }

    public function orderDetail() {
        if (!isset($_GET['id'])) {
            header('Location: admin.php?page=orders');
            exit();
        }
        $orderId = intval($_GET['id']);
        $order = $this->orderModel->getOrderDetails($orderId);
        $orderItems = $this->orderModel->getOrderItems($orderId);
        include __DIR__ . '/../views/admin/order_detail.php';
    }

    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                Session::setFlash('message', 'Invalid request');
                header('Location: admin.php?page=orders');
                exit();
            }
            $orderId = intval($_POST['order_id'] ?? 0);
            $status = $_POST['status'] ?? 'pending';
            if ($orderId && $this->orderModel->updateStatus($orderId, $status)) {
                Session::setFlash('success', 'Order status updated');
            } else {
                Session::setFlash('message', 'Failed to update order');
            }
            header('Location: admin.php?page=order_detail&id=' . $orderId);
            exit();
        }
        header('Location: admin.php?page=orders');
        exit();
    }

    public function customers() {
        $customers = $this->userModel->getAllCustomers();
        include __DIR__ . '/../views/admin/customers.php';
    }

    public function users() {
        $users = $this->userModel->getAllUsers();
        include __DIR__ . '/../views/admin/users.php';
    }

    public function editUser() {
        if (!isset($_GET['id'])) {
            header('Location: admin.php?page=users');
            exit();
        }
        $id = intval($_GET['id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
                Session::setFlash('message', 'Invalid request');
                header('Location: admin.php?page=edit_user&id=' . $id);
                exit();
            }
            $role = $_POST['role'] === 'admin' ? 'admin' : 'customer';
            if ($this->userModel->updateRole($id, $role)) {
                Session::setFlash('success', 'User role updated');
            } else {
                Session::setFlash('message', 'Failed to update user');
            }
            header('Location: admin.php?page=users');
            exit();
        }
        // Simple fetch by id using BaseModel
        $user = $this->userModel->findById($id);
        include __DIR__ . '/../views/admin/user_edit.php';
    }

    public function deleteUser() {
        if (!isset($_GET['id'])) {
            header('Location: admin.php?page=users');
            exit();
        }
        $id = intval($_GET['id']);
        // Prevent self-deletion for safety
        if ($id === Session::getUserId()) {
            Session::setFlash('message', 'You cannot delete your own account');
            header('Location: admin.php?page=users');
            exit();
        }
        if ($this->userModel->delete($id)) {
            Session::setFlash('success', 'User deleted');
        } else {
            Session::setFlash('message', 'Failed to delete user');
        }
        header('Location: admin.php?page=users');
        exit();
    }
}