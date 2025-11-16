<?php
$pageTitle = 'Order Details - Admin';
ob_start();

require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/CSRF.php';
?>

<!-- Header Section -->
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center">
            <div class="me-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-receipt" style="font-size: 2rem; color: white;"></i>
            </div>
            <div>
                <h2 class="mb-1" style="color: var(--purple-dark); font-weight: 700;">Order #<?php echo htmlspecialchars($order['order_number'] ?? 'N/A'); ?></h2>
                <p class="mb-0 text-muted" style="font-size: 0.95rem;">
                    <i class="far fa-calendar me-1"></i>
                    <?php echo !empty($order['created_at']) ? date('F d, Y \a\t H:i A', strtotime($order['created_at'])) : 'N/A'; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-end">
        <a href="admin.php?page=orders" class="btn" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); color: white; border: none; border-radius: 20px; padding: 10px 25px; font-weight: 600;">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
    </div>
</div>

<!-- Status Badge -->
<div class="row mb-4">
    <div class="col-md-12">
        <?php
        $statusConfig = [
            'pending' => ['color' => '#ffc107', 'icon' => 'fa-clock', 'label' => 'Pending'],
            'processing' => ['color' => '#17a2b8', 'icon' => 'fa-spinner', 'label' => 'Processing'],
            'shipped' => ['color' => '#007bff', 'icon' => 'fa-shipping-fast', 'label' => 'Shipped'],
            'completed' => ['color' => '#28a745', 'icon' => 'fa-check-double', 'label' => 'Completed'],
            'cancelled' => ['color' => '#dc3545', 'icon' => 'fa-times-circle', 'label' => 'Cancelled']
        ];
        $config = $statusConfig[$order['order_status'] ?? 'pending'] ?? ['color' => '#6c757d', 'icon' => 'fa-question', 'label' => 'Unknown'];
        ?>
        <div class="alert d-inline-flex align-items-center" style="background: <?php echo $config['color']; ?>15; border: 2px solid <?php echo $config['color']; ?>; border-radius: 15px; padding: 15px 25px;">
            <i class="fas <?php echo $config['icon']; ?> me-2" style="color: <?php echo $config['color']; ?>; font-size: 1.3rem;"></i>
            <span style="color: <?php echo $config['color']; ?>; font-weight: 600; font-size: 1.1rem;">
                Order Status: <?php echo $config['label']; ?>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8">
        <!-- Customer Information -->
        <div class="card shadow-sm mb-4" style="border-radius: 20px; border: none; overflow: hidden; border-top: 5px solid var(--purple-dark);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border: none; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-user me-2"></i>Customer Information</h5>
                <a href="admin.php?page=generate_receipt&id=<?php echo $order['id']; ?>" class="btn btn-light btn-sm" style="border-radius: 20px; padding: 8px 20px; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <i class="fas fa-download me-2"></i>Generate Receipt
                </a>
            </div>
            <div class="card-body" style="padding: 25px;">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                            <small class="text-muted d-block mb-2 fw-600"><i class="fas fa-user me-2" style="color: var(--purple-dark);"></i>Full Name</small>
                            <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.05rem;">
                                <?php 
                                    $fullName = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
                                    echo htmlspecialchars($fullName ?: 'Not provided');
                                ?>
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                            <small class="text-muted d-block mb-2 fw-600"><i class="fas fa-envelope me-2" style="color: var(--purple-dark);"></i>Email Address</small>
                            <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.05rem;">
                                <?php echo htmlspecialchars($order['email'] ?? 'Not provided'); ?>
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                            <small class="text-muted d-block mb-2 fw-600"><i class="fas fa-phone me-2" style="color: var(--purple-dark);"></i>Phone Number</small>
                            <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.05rem;">
                                <?php echo htmlspecialchars($order['phone'] ?? 'Not provided'); ?>
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                            <small class="text-muted d-block mb-2 fw-600"><i class="far fa-calendar-alt me-2" style="color: var(--purple-dark);"></i>Order Date</small>
                            <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.05rem;">
                                <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?>
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid var(--purple-medium);">
                            <small class="text-muted d-block mb-2 fw-600"><i class="fas fa-map-marker-alt me-2" style="color: var(--purple-dark);"></i>Delivery Address</small>
                            <h6 class="fw-bold mb-0" style="color: var(--purple-dark); font-size: 1.05rem;">
                                <?php 
                                    $address = trim(($order['address'] ?? '') . ', ' . ($order['city'] ?? ''));
                                    if (!empty($order['postal_code'])) {
                                        $address .= ' ' . $order['postal_code'];
                                    }
                                    if (!empty($order['country'])) {
                                        $address .= ', ' . $order['country'];
                                    }
                                    echo htmlspecialchars($address ?: 'Not provided');
                                ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="card shadow-sm mb-4" style="border-radius: 20px; border: none; overflow: hidden; border-top: 5px solid var(--purple-dark);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border: none; padding: 20px; font-weight: 700;">
                <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-shopping-bag me-2"></i>Order Items</h5>
            </div>
            <div class="card-body" style="padding: 25px;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border-radius: 15px;">
                                <th style="border: none; padding: 15px; color: white; font-weight: 600; border-radius: 15px 0 0 15px;">Product</th>
                                <th style="border: none; padding: 15px; color: white; font-weight: 600; text-align: center;">Quantity</th>
                                <th style="border: none; padding: 15px; color: white; font-weight: 600; text-align: right;">Unit Price</th>
                                <th style="border: none; padding: 15px; color: white; font-weight: 600; text-align: right; border-radius: 0 15px 15px 0;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 20px; vertical-align: top;">
                                    <div class="d-flex align-items-flex-start gap-3">
                                        <div>
                                            <?php if (!empty($item['use_placeholder'])): ?>
                                                <!-- RED Placeholder for DELETED product only -->
                                                <div class="d-flex align-items-center justify-content-center position-relative" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.15) 0%, rgba(108, 117, 125, 0.2) 100%); border-radius: 12px; overflow: hidden; border: 2px dashed #dc3545;">
                                                    <div class="position-absolute" style="top: -10px; right: -10px; width: 40px; height: 40px; background: rgba(220, 53, 69, 0.1); border-radius: 50%; filter: blur(15px);"></div>
                                                    <div class="position-absolute" style="bottom: -10px; left: -10px; width: 35px; height: 35px; background: rgba(108, 117, 125, 0.15); border-radius: 50%; filter: blur(12px);"></div>
                                                    <div class="position-relative text-center">
                                                        <i class="fas fa-image-slash" style="font-size: 1.8rem; color: #dc3545; display: block; margin-bottom: 4px;"></i>
                                                        <small style="font-size: 0.7rem; color: #6c757d; font-weight: 600;">Unavailable</small>
                                                    </div>
                                                </div>
                                            <?php elseif (!empty($item['display_image'])): ?>
                                                <?php 
                                                // Handle both old paths (without products/) and new paths (with products/)
                                                $imagePath = $item['display_image'];
                                                if (strpos($imagePath, 'products/') !== 0 && strpos($imagePath, 'profiles/') !== 0) {
                                                    // Old format - assume it's a product image
                                                    $imagePath = 'products/' . $imagePath;
                                                }
                                                ?>
                                                <img src="uploads/<?php echo htmlspecialchars($imagePath); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['display_name']); ?>" 
                                                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            <?php else: ?>
                                                <!-- PURPLE Placeholder for products without images (but still active) -->
                                                <div class="d-flex align-items-center justify-content-center position-relative" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(139, 95, 191, 0.15) 0%, rgba(255, 159, 191, 0.2) 100%); border-radius: 12px; overflow: hidden;">
                                                    <div class="position-absolute" style="top: -10px; right: -10px; width: 40px; height: 40px; background: rgba(139, 95, 191, 0.1); border-radius: 50%; filter: blur(15px);"></div>
                                                    <div class="position-absolute" style="bottom: -10px; left: -10px; width: 35px; height: 35px; background: rgba(255, 159, 191, 0.15); border-radius: 50%; filter: blur(12px);"></div>
                                                    <div class="position-relative" style="animation: float 3s ease-in-out infinite;">
                                                        <i class="fas fa-box-open" style="font-size: 2rem; background: linear-gradient(135deg, var(--purple-dark) 0%, var(--pink-medium) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #333; margin-bottom: 8px;"><?php echo htmlspecialchars($item['display_name']); ?></div>
                                            <?php if (!empty($item['is_deleted']) || !empty($item['use_placeholder'])): ?>
                                                <div style="background: #fff3cd; border-left: 4px solid #dc3545; border-radius: 8px; padding: 10px 12px; display: inline-block;">
                                                    <small style="color: #721c24;">
                                                        <i class="fas fa-exclamation-triangle me-1" style="color: #dc3545;"></i>
                                                        <strong>Note:</strong> Product no longer available
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 20px; text-align: center; vertical-align: middle;">
                                    <span class="badge" style="background: var(--purple-medium); padding: 8px 16px; border-radius: 20px; font-size: 0.95rem;">
                                        ×<?php echo $item['quantity']; ?>
                                    </span>
                                </td>
                                <td style="padding: 20px; text-align: right; vertical-align: middle; color: #666;">
                                    ₱<?php echo number_format($item['unit_price'], 2); ?>
                                </td>
                                <td style="padding: 20px; text-align: right; vertical-align: middle;">
                                    <strong style="color: var(--purple-dark); font-size: 1.05rem;">
                                        ₱<?php echo number_format($item['item_total'] ?? ($item['quantity'] * $item['unit_price']), 2); ?>
                                    </strong>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="col-md-4">
        <!-- Order Summary -->
        <div class="card shadow-sm mb-4" style="border-radius: 20px; border: none; overflow: hidden; border-top: 5px solid var(--purple-dark);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border: none; padding: 20px; font-weight: 700;">
                <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-calculator me-2"></i>Order Summary</h5>
            </div>
            <div class="card-body" style="padding: 25px;">
                <div class="p-4 rounded-3" style="background: linear-gradient(135deg, rgba(139, 95, 191, 0.1) 0%, rgba(255, 159, 191, 0.1) 100%); border: 2px solid var(--purple-medium); text-align: center;">
                    <p class="text-muted small mb-2">Total Amount</p>
                    <h2 class="mb-0 fw-bold" style="color: var(--purple-dark); font-size: 2.5rem;">
                        ₱<?php echo number_format($order['total_amount'], 2); ?>
                    </h2>
                </div>
            </div>
        </div>
        
        <!-- Update Status -->
        <?php if ($order['order_status'] !== 'completed' && $order['order_status'] !== 'cancelled'): ?>
        <div class="card shadow-sm" style="border-radius: 20px; border: none; overflow: hidden; border-top: 5px solid var(--purple-dark);">
            <div class="card-header" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); border: none; padding: 20px; font-weight: 700;">
                <h5 class="mb-0" style="color: white; font-weight: 700;"><i class="fas fa-edit me-2"></i>Update Status</h5>
            </div>
            <div class="card-body" style="padding: 25px;">
                <form method="POST" action="admin.php?page=update_order">
                    <?php echo CSRF::getTokenField(); ?>
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    
                    <div class="mb-4">
                        <label style="color: var(--purple-dark); font-weight: 600; margin-bottom: 18px; display: block; font-size: 1.05rem;">Select New Status</label>
                        
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <!-- Pending Option -->
                            <label style="position: relative; cursor: pointer; margin-bottom: 0;">
                                <input type="radio" name="status" value="pending" 
                                       <?php echo ($order['order_status'] == 'pending') ? 'checked' : ''; ?>
                                       style="position: absolute; opacity: 0; cursor: pointer; z-index: 10; width: 100%; height: 100%;">
                                <div style="padding: 16px 18px; 
                                           border: 2px solid #e0e0e0; 
                                           border-radius: 12px; 
                                           background: white; 
                                           cursor: pointer; 
                                           transition: all 0.3s ease;
                                           display: flex;
                                           align-items: center;
                                           gap: 14px;"
                                    class="status-option-pending">
                                    <div style="width: 24px; height: 24px; 
                                               border: 2px solid #ffc107; 
                                               border-radius: 50%; 
                                               display: flex; 
                                               align-items: center; 
                                               justify-content: center; 
                                               flex-shrink: 0; 
                                               background: white; 
                                               transition: all 0.3s;">
                                        <i class="fas fa-check" style="color: #ffc107; font-size: 0.7rem; opacity: 0; transition: opacity 0.3s;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #333; font-size: 0.95rem;">Pending</div>
                                        <div style="font-size: 0.85rem; color: #999; margin-top: 2px;">Order awaiting processing</div>
                                    </div>
                                    <i class="fas fa-clock" style="color: #ffc107; font-size: 1.3rem; opacity: 0.6;"></i>
                                </div>
                            </label>

                            <!-- Processing Option -->
                            <label style="position: relative; cursor: pointer; margin-bottom: 0;">
                                <input type="radio" name="status" value="processing" 
                                       <?php echo ($order['order_status'] == 'processing') ? 'checked' : ''; ?>
                                       style="position: absolute; opacity: 0; cursor: pointer; z-index: 10; width: 100%; height: 100%;">
                                <div style="padding: 16px 18px; 
                                           border: 2px solid #e0e0e0; 
                                           border-radius: 12px; 
                                           background: white; 
                                           cursor: pointer; 
                                           transition: all 0.3s ease;
                                           display: flex;
                                           align-items: center;
                                           gap: 14px;"
                                    class="status-option-processing">
                                    <div style="width: 24px; height: 24px; 
                                               border: 2px solid #17a2b8; 
                                               border-radius: 50%; 
                                               display: flex; 
                                               align-items: center; 
                                               justify-content: center; 
                                               flex-shrink: 0; 
                                               background: white; 
                                               transition: all 0.3s;">
                                        <i class="fas fa-check" style="color: #17a2b8; font-size: 0.7rem; opacity: 0; transition: opacity 0.3s;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #333; font-size: 0.95rem;">Processing</div>
                                        <div style="font-size: 0.85rem; color: #999; margin-top: 2px;">Order is being prepared</div>
                                    </div>
                                    <i class="fas fa-spinner" style="color: #17a2b8; font-size: 1.3rem; opacity: 0.6;"></i>
                                </div>
                            </label>

                            <!-- Shipped Option -->
                            <label style="position: relative; cursor: pointer; margin-bottom: 0;">
                                <input type="radio" name="status" value="shipped" 
                                       <?php echo ($order['order_status'] == 'shipped') ? 'checked' : ''; ?>
                                       style="position: absolute; opacity: 0; cursor: pointer; z-index: 10; width: 100%; height: 100%;">
                                <div style="padding: 16px 18px; 
                                           border: 2px solid #e0e0e0; 
                                           border-radius: 12px; 
                                           background: white; 
                                           cursor: pointer; 
                                           transition: all 0.3s ease;
                                           display: flex;
                                           align-items: center;
                                           gap: 14px;"
                                    class="status-option-shipped">
                                    <div style="width: 24px; height: 24px; 
                                               border: 2px solid #007bff; 
                                               border-radius: 50%; 
                                               display: flex; 
                                               align-items: center; 
                                               justify-content: center; 
                                               flex-shrink: 0; 
                                               background: white; 
                                               transition: all 0.3s;">
                                        <i class="fas fa-check" style="color: #007bff; font-size: 0.7rem; opacity: 0; transition: opacity 0.3s;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #333; font-size: 0.95rem;">Shipped</div>
                                        <div style="font-size: 0.85rem; color: #999; margin-top: 2px;">Order is on the way</div>
                                    </div>
                                    <i class="fas fa-shipping-fast" style="color: #007bff; font-size: 1.3rem; opacity: 0.6;"></i>
                                </div>
                            </label>

                            <!-- Delivered Option -->
                            <label style="position: relative; cursor: pointer; margin-bottom: 0;">
                                <input type="radio" name="status" value="delivered" 
                                       <?php echo ($order['order_status'] == 'delivered') ? 'checked' : ''; ?>
                                       style="position: absolute; opacity: 0; cursor: pointer; z-index: 10; width: 100%; height: 100%;">
                                <div style="padding: 16px 18px; 
                                           border: 2px solid #e0e0e0; 
                                           border-radius: 12px; 
                                           background: white; 
                                           cursor: pointer; 
                                           transition: all 0.3s ease;
                                           display: flex;
                                           align-items: center;
                                           gap: 14px;"
                                    class="status-option-delivered">
                                    <div style="width: 24px; height: 24px; 
                                               border: 2px solid #28a745; 
                                               border-radius: 50%; 
                                               display: flex; 
                                               align-items: center; 
                                               justify-content: center; 
                                               flex-shrink: 0; 
                                               background: white; 
                                               transition: all 0.3s;">
                                        <i class="fas fa-check" style="color: #28a745; font-size: 0.7rem; opacity: 0; transition: opacity 0.3s;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #333; font-size: 0.95rem;">Delivered</div>
                                        <div style="font-size: 0.85rem; color: #999; margin-top: 2px;">Order has been delivered (will auto-complete)</div>
                                    </div>
                                    <i class="fas fa-check" style="color: #28a745; font-size: 1.3rem; opacity: 0.6;"></i>
                                </div>
                            </label>

                            <!-- Cancelled Option -->
                            <label style="position: relative; cursor: pointer; margin-bottom: 0;">
                                <input type="radio" name="status" value="cancelled" 
                                       <?php echo ($order['order_status'] == 'cancelled') ? 'checked' : ''; ?>
                                       style="position: absolute; opacity: 0; cursor: pointer; z-index: 10; width: 100%; height: 100%;">
                                <div style="padding: 16px 18px; 
                                           border: 2px solid #e0e0e0; 
                                           border-radius: 12px; 
                                           background: white; 
                                           cursor: pointer; 
                                           transition: all 0.3s ease;
                                           display: flex;
                                           align-items: center;
                                           gap: 14px;"
                                    class="status-option-cancelled">
                                    <div style="width: 24px; height: 24px; 
                                               border: 2px solid #dc3545; 
                                               border-radius: 50%; 
                                               display: flex; 
                                               align-items: center; 
                                               justify-content: center; 
                                               flex-shrink: 0; 
                                               background: white; 
                                               transition: all 0.3s;">
                                        <i class="fas fa-check" style="color: #dc3545; font-size: 0.7rem; opacity: 0; transition: opacity 0.3s;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #333; font-size: 0.95rem;">Cancelled</div>
                                        <div style="font-size: 0.85rem; color: #999; margin-top: 2px;">Order has been cancelled</div>
                                    </div>
                                    <i class="fas fa-times-circle" style="color: #dc3545; font-size: 1.3rem; opacity: 0.6;"></i>
                                </div>
                            </label>
                        </div>
                        
                        <style>
                            input[type="radio"][name="status"]:checked + div {
                                border-color: var(--purple-dark) !important;
                                background: linear-gradient(135deg, rgba(139, 95, 191, 0.15) 0%, rgba(255, 159, 191, 0.12) 100%) !important;
                                box-shadow: 0 4px 12px rgba(139, 95, 191, 0.2) !important;
                            }

                            input[type="radio"][name="status"]:checked + div > div:first-child {
                                background: var(--purple-dark) !important;
                                border-color: var(--purple-dark) !important;
                            }

                            input[type="radio"][name="status"]:checked + div > div:first-child i.fas {
                                opacity: 1 !important;
                                color: white !important;
                            }

                            input[type="radio"][name="status"]:checked + div i.fas:last-child {
                                opacity: 1 !important;
                            }

                            .status-option-pending:hover,
                            .status-option-processing:hover,
                            .status-option-shipped:hover,
                            .status-option-delivered:hover,
                            .status-option-cancelled:hover {
                                border-color: var(--purple-medium) !important;
                                box-shadow: 0 2px 8px rgba(139, 95, 191, 0.1) !important;
                            }
                        </style>
                        
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const radioButtons = document.querySelectorAll('input[type="radio"][name="status"]');
                            
                            radioButtons.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    // Reset all options
                                    document.querySelectorAll('[class^="status-option-"]').forEach(option => {
                                        option.style.borderColor = '#e0e0e0';
                                        option.style.background = 'white';
                                        option.style.boxShadow = 'none';
                                        const checkmark = option.querySelector('i.fas.fa-check');
                                        if (checkmark) checkmark.style.opacity = '0';
                                        const circle = option.querySelector('div:first-child');
                                        if (circle) {
                                            circle.style.background = 'white';
                                        }
                                    });
                                    
                                    // Update the selected option
                                    const div = this.parentElement.querySelector('div');
                                    if (div) {
                                        div.style.borderColor = 'var(--purple-dark)';
                                        div.style.background = 'linear-gradient(135deg, rgba(139, 95, 191, 0.08) 0%, rgba(255, 159, 191, 0.08) 100%)';
                                        div.style.boxShadow = '0 4px 12px rgba(139, 95, 191, 0.15)';
                                        
                                        const circle = div.querySelector('div:first-child');
                                        if (circle) {
                                            circle.style.background = 'var(--purple-dark)';
                                            circle.style.borderColor = 'var(--purple-dark)';
                                        }
                                        
                                        const checkmark = div.querySelector('i.fas.fa-check');
                                        if (checkmark) checkmark.style.opacity = '1';
                                    }
                                });
                            });
                            
                            // Trigger change on page load to highlight selected option
                            const checkedRadio = document.querySelector('input[type="radio"][name="status"]:checked');
                            if (checkedRadio) {
                                checkedRadio.dispatchEvent(new Event('change'));
                            }
                        });
                        </script>
                        
                        <small class="text-muted mt-4 d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Customers can mark shipped orders as completed.
                        </small>
                    </div>
                    
                    <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, var(--purple-dark) 0%, var(--purple-medium) 100%); color: white; border: none; border-radius: 15px; padding: 14px; font-weight: 600; font-size: 1.05rem;">
                        <i class="fas fa-save me-2"></i>Update Order Status
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../admin_layout.php';
?>