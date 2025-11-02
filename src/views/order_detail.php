<?php
$pageTitle = 'Order Details - Lotus Plushies';
ob_start();

require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/UIHelper.php';
?>

<style>
@media print {
    /* Hide navigation, buttons, and non-receipt elements */
    .no-print, nav, footer, .btn, .card-header {
        display: none !important;
    }
    
    /* Reset margins and padding for compact layout */
    body {
        margin: 0;
        padding: 10px;
        background: white !important;
        color: #000 !important;
        font-size: 11px !important;
    }
    
    /* Reset card styles for printing */
    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .card-body {
        padding: 5px !important;
    }
    
    /* Show receipt header */
    .receipt-header {
        display: block !important;
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
    
    .receipt-title {
        font-size: 22px;
        font-weight: bold;
        color: #000;
        margin: 0;
        padding: 0;
    }
    
    .receipt-subtitle {
        font-size: 11px;
        color: #333;
        margin: 2px 0;
    }
    
    /* Compact order info */
    .order-info-print {
        display: grid !important;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ddd;
        background: #f9f9f9;
    }
    
    .order-info-print > div {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .order-info-print h6,
    .order-info-print p {
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1.3 !important;
    }
    
    /* Compact item cards */
    .order-item-card {
        border: 1px solid #ddd !important;
        margin-bottom: 5px !important;
        padding: 8px !important;
        page-break-inside: avoid;
    }
    
    .order-item-card img {
        max-height: 40px !important;
        width: auto !important;
    }
    
    .order-item-card .row {
        margin: 0 !important;
    }
    
    .order-item-card .col-md-2,
    .order-item-card .col-md-4 {
        padding: 2px !important;
    }
    
    /* Compact summary */
    .order-summary-print {
        margin-top: 15px;
        padding: 10px;
        border: 2px solid #000;
        background: #f9f9f9;
    }
    
    .order-summary-print .d-flex {
        margin-bottom: 5px !important;
        padding: 3px 0 !important;
        line-height: 1.2 !important;
    }
    
    /* Make text print-friendly */
    h2, h5, h6, p, span {
        color: #000 !important;
        margin: 0 !important;
    }
    
    h6 {
        font-size: 11px !important;
    }
    
    h5 {
        font-size: 12px !important;
    }
    
    .small, small {
        font-size: 9px !important;
    }
    
    /* Remove excessive spacing */
    .mb-1, .mb-2, .mb-3, .mb-4,
    .mt-1, .mt-2, .mt-3, .mt-4,
    .pb-3, .pt-3 {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Compact borders */
    .border-bottom {
        border-bottom: 1px dashed #999 !important;
        padding-bottom: 3px !important;
        margin-bottom: 3px !important;
    }
    
    .border-top {
        border-top: 2px solid #000 !important;
        padding-top: 5px !important;
        margin-top: 5px !important;
    }
    
    /* Hide row gaps */
    .g-3 {
        gap: 0 !important;
    }
    
    /* Fit everything on one page */
    @page {
        margin: 0.5cm;
        size: auto;
    }
}

/* Hide receipt header on screen */
.receipt-header {
    display: none;
}
</style>

<!-- Receipt Header (only visible when printing) -->
<div class="receipt-header">
    <div class="receipt-title">ORDER RECEIPT</div>
    <div class="receipt-subtitle">Lotus Plushies</div>
    <div class="receipt-subtitle">Order #<?php echo htmlspecialchars($order['order_number']); ?> | <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></div>
</div>

<div class="row mb-4 no-print align-items-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center">
            <div class="me-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-receipt" style="font-size: 2rem; color: white;"></i>
            </div>
            <div>
                <h2 class="mb-1" style="color: var(--purple-dark); font-weight: 700;">Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
                <p class="mb-0 text-muted" style="font-size: 0.95rem;">
                    <i class="far fa-calendar me-1"></i>
                    <?php echo date('F d, Y \a\t H:i A', strtotime($order['created_at'])); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-end">
        <a href="index.php?page=order_history" class="btn" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); color: white; border: none; border-radius: 20px; padding: 10px 25px; font-weight: 600;">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>
</div>

<!-- Order Info Card -->
<div class="card shadow-sm mb-4" style="border: none; border-radius: 20px; overflow: hidden; border-top: 5px solid var(--purple-dark);">
    <div class="card-header no-print" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); color: white; padding: 1.5rem; border: none;">
        <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                    <p class="text-muted small mb-2 fw-600"><i class="fas fa-hashtag me-2" style="color: var(--purple-dark);"></i>Order Number</p>
                    <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.1rem;"><?php echo htmlspecialchars($order['order_number']); ?></h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                    <p class="text-muted small mb-2 fw-600"><i class="far fa-calendar-alt me-2" style="color: var(--purple-dark);"></i>Order Date</p>
                    <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.1rem;"><?php echo UIHelper::formatDate($order['created_at']); ?></h6>
                    <p class="text-muted small mb-0 mt-1"><?php echo date('h:i A', strtotime($order['created_at'])); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                    <p class="text-muted small mb-2 fw-600"><i class="fas fa-tag me-2" style="color: var(--purple-dark);"></i>Status</p>
                    <?php echo UIHelper::renderOrderStatusBadge($order['order_status']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Order Items Card -->
        <div class="card shadow-sm mb-4" style="border: none; border-radius: 20px; overflow: hidden; border-top: 5px solid var(--purple-dark);">
            <div class="card-header no-print" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); color: white; padding: 1.5rem; border: none;">
                <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-shopping-bag me-2"></i>Order Items</h5>
            </div>
            <div class="card-body p-3">
                <?php foreach ($orderItems as $index => $item): ?>
                <div class="card mb-3 shadow-sm order-item-card" style="border: 2px solid var(--purple-light); border-radius: 15px; overflow: hidden; transition: all 0.3s;">
                    <div class="card-body p-3" style="background: #fafbfc;">
                        <div class="row align-items-center g-3">
                            <div class="col-md-2 text-center">
                                <?php if (!empty($item['use_placeholder'])): ?>
                                    <!-- Placeholder for deleted/inactive product -->
                                    <div class="d-flex align-items-center justify-content-center rounded position-relative mx-auto" style="height: 90px; width: 90px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(108, 117, 125, 0.2) 100%); border: 2px dashed #dc3545; overflow: hidden;">
                                        <div class="position-absolute" style="top: -30%; right: -20%; width: 50px; height: 50px; background: rgba(220, 53, 69, 0.2); border-radius: 50%; filter: blur(15px);"></div>
                                        <div class="text-center position-relative">
                                            <i class="fas fa-image-slash" style="font-size: 1.5rem; color: #dc3545; display: block; margin-bottom: 2px;"></i>
                                            <small style="font-size: 0.6rem; color: #6c757d; font-weight: 600;">Unavailable</small>
                                        </div>
                                    </div>
                                <?php elseif (!empty($item['img_path'])): ?>
                                    <?php 
                                    // Handle both old paths (without products/) and new paths (with products/)
                                    $imagePath = $item['img_path'];
                                    if (strpos($imagePath, 'products/') !== 0 && strpos($imagePath, 'profiles/') !== 0) {
                                        $imagePath = 'products/' . $imagePath;
                                    }
                                    ?>
                                    <img src="uploads/<?php echo htmlspecialchars($imagePath); ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                         class="img-fluid rounded" 
                                         style="max-height: 90px; max-width: 90px; object-fit: contain; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="d-none align-items-center justify-content-center rounded position-relative mx-auto" style="height: 90px; width: 90px; background: linear-gradient(135deg, rgba(156, 39, 176, 0.1) 0%, rgba(233, 30, 99, 0.1) 100%); border: 2px dashed var(--purple-medium); overflow: hidden;">
                                        <div class="position-absolute" style="top: -30%; right: -20%; width: 50px; height: 50px; background: rgba(156, 39, 176, 0.15); border-radius: 50%; filter: blur(15px);"></div>
                                        <div class="text-center position-relative">
                                            <i class="fas fa-image" style="font-size: 1.5rem; color: var(--purple-medium); display: block; margin-bottom: 2px;"></i>
                                            <small style="font-size: 0.6rem; color: #6c757d; font-weight: 600;">No Image</small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Placeholder for product with null image -->
                                    <div class="d-flex align-items-center justify-content-center rounded position-relative mx-auto" style="height: 90px; width: 90px; background: linear-gradient(135deg, rgba(156, 39, 176, 0.1) 0%, rgba(233, 30, 99, 0.1) 100%); border: 2px dashed var(--purple-medium); overflow: hidden;">
                                        <div class="position-absolute" style="top: -30%; right: -20%; width: 50px; height: 50px; background: rgba(156, 39, 176, 0.15); border-radius: 50%; filter: blur(15px);"></div>
                                        <div class="text-center position-relative">
                                            <i class="fas fa-image" style="font-size: 1.5rem; color: var(--purple-medium); display: block; margin-bottom: 2px;"></i>
                                            <small style="font-size: 0.6rem; color: #6c757d; font-weight: 600;">No Image</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-2 fw-bold" style="color: var(--purple-dark); font-size: 1.05rem;">
                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                </h6>
                                <?php if (!empty($item['product_id'])): ?>
                                    <p class="text-muted small mb-2">ID: <span style="color: #666; font-weight: 500;"><?php echo $item['product_id']; ?></span></p>
                                <?php endif; ?>
                                <?php if (!empty($item['is_deleted']) || !empty($item['use_placeholder'])): ?>
                                    <div class="alert alert-warning p-2 mb-0" style="border-left: 4px solid #dc3545; background-color: #fff3cd; border-radius: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-exclamation-triangle me-1" style="color: #dc3545;"></i>
                                        <strong>Unavailable</strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="text-muted small mb-2" style="font-size: 0.9rem;">Qty</p>
                                <div class="p-2 rounded-2" style="background: white; border: 2px solid var(--purple-medium);">
                                    <h6 class="mb-0 fw-bold" style="color: var(--purple-dark); font-size: 1.1rem;">×<?php echo $item['quantity']; ?></h6>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <p class="text-muted small mb-2" style="font-size: 0.9rem;">Price</p>
                                <h6 class="mb-0 fw-bold" style="color: var(--purple-dark); font-size: 1rem;"><?php echo UIHelper::formatCurrency($item['unit_price']); ?></h6>
                            </div>
                            <div class="col-md-2 text-end">
                                <p class="text-muted small mb-2" style="font-size: 0.9rem;">Subtotal</p>
                                <h5 class="mb-0 fw-bold" style="color: var(--purple-dark); font-size: 1.1rem;"><?php echo UIHelper::formatCurrency($item['item_total']); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Order Summary Card -->
        <div class="card shadow-sm sticky-top" style="border: none; border-radius: 20px; overflow: hidden; border-top: 5px solid var(--purple-dark); top: 20px;">
            <div class="card-header no-print" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); color: white; padding: 1.5rem; border: none;">
                <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
            </div>
            <div class="card-body p-4 order-summary-print">
                <div class="p-4 rounded-3" style="background: linear-gradient(135deg, rgba(139, 95, 191, 0.1) 0%, rgba(255, 159, 191, 0.1) 100%); border: 2px solid var(--purple-medium); text-align: center;">
                    <p class="text-muted small mb-2">Total Amount</p>
                    <h2 class="mb-0 fw-bold" style="color: var(--purple-dark); font-size: 2.5rem;">
                        <?php echo UIHelper::formatCurrency($order['total_amount']); ?>
                    </h2>
                </div>
                
                <?php if ($order['order_status'] === 'delivered'): ?>
                <div class="mt-4 pt-4" style="border-top: 2px solid #e0e0e0;">
                    <a href="index.php?page=order&action=confirm_receipt&id=<?php echo $order['id']; ?>" 
                       class="btn btn-success w-100"
                       style="border-radius: 15px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; padding: 14px; font-weight: 600; font-size: 1.05rem;"
                       onclick="return confirm('Confirm that you have received this order?');">
                        <i class="fas fa-check-circle me-2"></i>Mark as Received
                    </a>
                    <p class="text-muted small text-center mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i>Confirm order delivery
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>


