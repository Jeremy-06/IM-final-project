<?php
$pageTitle = 'Manage Orders - Admin';
ob_start();

require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/UIHelper.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-box me-2"></i>Manage Orders</h2>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <?php
        // Calculate badge counts using UIHelper
        $statusCounts = UIHelper::calculateStatusCounts($allOrders);
        ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 10px;">
            <!-- Search Form -->
            <form method="GET" action="admin.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="page" value="orders">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($_GET['sort'] ?? 'created_at'); ?>">
                <input type="hidden" name="order" value="<?php echo htmlspecialchars($_GET['order'] ?? 'DESC'); ?>">
                <?php if (isset($_GET['status'])): ?>
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($_GET['status']); ?>">
                <?php endif; ?>
                
                <div class="input-group" style="max-width: 250px;">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search orders..." 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                           style="border: 2px solid #8b5fbf; border-radius: 25px 0 0 25px; padding: 10px 20px; outline: none; box-shadow: none;">
                    <button type="submit" class="btn" style="background: #8b5fbf; color: white; border: 2px solid #8b5fbf; border-radius: 0 25px 25px 0; padding: 10px 20px; outline: none; box-shadow: none;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <?php if (isset($_GET['search']) && $_GET['search'] !== ''): ?>
                    <a href="admin.php?page=orders<?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?>&sort=<?php echo htmlspecialchars($_GET['sort'] ?? 'created_at'); ?>&order=<?php echo htmlspecialchars($_GET['order'] ?? 'DESC'); ?>" 
                       class="btn" style="background: #6c757d; color: white; border-radius: 25px; padding: 10px 20px; text-decoration: none; outline: none; box-shadow: none;">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
            
            <!-- Status Filter Buttons -->
            <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                <a href="admin.php?page=orders&status=pending<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) . '&order=' . htmlspecialchars($_GET['order'] ?? 'DESC') : ''; ?>" 
                   class="d-flex align-items-center" 
                   style="border-radius: 20px; padding: 10px 20px; text-decoration: none; <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'background: #fef3c7; color: #92400e !important; border: 2px solid #fbbf24; box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);' : 'background: #8b5fbf; color: white !important; border: 2px solid #8b5fbf;'; ?>">
                    <i class="fas fa-clock me-2" style="color: inherit;"></i> 
                    <span class="me-2" style="color: inherit;">Pending</span>
                    <?php if ($statusCounts['pending'] > 0): ?>
                        <span class="badge" style="background: <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'rgba(146, 64, 14, 0.2)' : 'white'; ?>; color: <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? '#92400e' : '#8b5fbf'; ?>; font-size: 0.9rem; padding: 6px 12px; border-radius: 12px; font-weight: 700;"><?php echo $statusCounts['pending']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin.php?page=orders&status=processing<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) . '&order=' . htmlspecialchars($_GET['order'] ?? 'DESC') : ''; ?>" 
                   class="d-flex align-items-center" 
                   style="border-radius: 20px; padding: 10px 20px; text-decoration: none; <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'background: #d1ecf1; color: #0c5460 !important; border: 2px solid #bee5eb; box-shadow: 0 4px 12px rgba(190, 229, 235, 0.2);' : 'background: #8b5fbf; color: white !important; border: 2px solid #8b5fbf;'; ?>">
                    <i class="fas fa-spinner me-2" style="color: inherit;"></i> 
                    <span class="me-2" style="color: inherit;">Processing</span>
                    <?php if ($statusCounts['processing'] > 0): ?>
                        <span class="badge" style="background: <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'rgba(12, 84, 96, 0.2)' : 'white'; ?>; color: <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? '#0c5460' : '#8b5fbf'; ?>; font-size: 0.9rem; padding: 6px 12px; border-radius: 12px; font-weight: 700;"><?php echo $statusCounts['processing']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin.php?page=orders&status=shipped<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) . '&order=' . htmlspecialchars($_GET['order'] ?? 'DESC') : ''; ?>" 
                   class="d-flex align-items-center" 
                   style="border-radius: 20px; padding: 10px 20px; text-decoration: none; <?php echo (isset($_GET['status']) && $_GET['status'] == 'shipped') ? 'background: #dbeafe; color: #1e40af !important; border: 2px solid #93c5fd; box-shadow: 0 4px 12px rgba(147, 197, 253, 0.2);' : 'background: #8b5fbf; color: white !important; border: 2px solid #8b5fbf;'; ?>">
                    <i class="fas fa-shipping-fast me-2" style="color: inherit;"></i> 
                    <span class="me-2" style="color: inherit;">Shipped</span>
                    <?php if ($statusCounts['shipped'] > 0): ?>
                        <span class="badge" style="background: <?php echo (isset($_GET['status']) && $_GET['status'] == 'shipped') ? 'rgba(30, 64, 175, 0.2)' : 'white'; ?>; color: <?php echo (isset($_GET['status']) && $_GET['status'] == 'shipped') ? '#1e40af' : '#8b5fbf'; ?>; font-size: 0.9rem; padding: 6px 12px; border-radius: 12px; font-weight: 700;"><?php echo $statusCounts['shipped']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin.php?page=orders&status=completed<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) . '&order=' . htmlspecialchars($_GET['order'] ?? 'DESC') : ''; ?>" 
                   class="d-flex align-items-center" 
                   style="border-radius: 20px; padding: 10px 20px; text-decoration: none; <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'background: #d1fae5; color: #065f46 !important; border: 2px solid #6ee7b7; box-shadow: 0 4px 12px rgba(110, 231, 183, 0.2);' : 'background: #8b5fbf; color: white !important; border: 2px solid #8b5fbf;'; ?>">
                    <i class="fas fa-check-circle me-2" style="color: inherit;"></i> 
                    <span class="me-2" style="color: inherit;">Completed</span>
                    <?php if ($statusCounts['completed'] > 0): ?>
                        <span class="badge" style="background: <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'rgba(6, 95, 70, 0.2)' : 'white'; ?>; color: <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? '#065f46' : '#8b5fbf'; ?>; font-size: 0.9rem; padding: 6px 12px; border-radius: 12px; font-weight: 700;"><?php echo $statusCounts['completed']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin.php?page=orders&status=cancelled<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?><?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) . '&order=' . htmlspecialchars($_GET['order'] ?? 'DESC') : ''; ?>" 
                   class="d-flex align-items-center" 
                   style="border-radius: 20px; padding: 10px 20px; text-decoration: none; <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'background: #fecaca; color: #991b1b !important; border: 2px solid #fca5a5; box-shadow: 0 4px 12px rgba(252, 165, 165, 0.2);' : 'background: #8b5fbf; color: white !important; border: 2px solid #8b5fbf;'; ?>">
                    <i class="fas fa-times-circle me-2" style="color: inherit;"></i> 
                    <span class="me-2" style="color: inherit;">Cancelled</span>
                    <?php if ($statusCounts['cancelled'] > 0): ?>
                        <span class="badge" style="background: <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'rgba(153, 27, 27, 0.2)' : 'white'; ?>; color: <?php echo (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? '#991b1b' : '#8b5fbf'; ?>; font-size: 0.9rem; padding: 6px 12px; border-radius: 12px; font-weight: 700;"><?php echo $statusCounts['cancelled']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin.php?page=orders<?php echo isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) . '&order=' . htmlspecialchars($_GET['order'] ?? 'DESC') : ''; ?>" 
                   class="d-flex align-items-center" 
                   style="border-radius: 20px; padding: 10px 20px; text-decoration: none; <?php echo (!isset($_GET['status'])) ? 'background: linear-gradient(135deg, #8b5fbf 0%, #b19cd9 100%); color: white !important; border: 2px solid #8b5fbf;' : 'background: #8b5fbf; color: white !important; border: 2px solid #8b5fbf;'; ?>">
                    <i class="fas fa-list me-2" style="color: inherit;"></i> 
                    <span class="me-2" style="color: inherit;">All Orders</span>
                    <span class="badge" style="background: white; color: #8b5fbf;"><?php echo count($allOrders); ?></span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($orders)): ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>
                    <a href="admin.php?page=orders&sort=order_number&order=<?php echo ($_GET['sort'] ?? '') === 'order_number' && ($_GET['order'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="text-white text-decoration-none">
                        Order Number <?php if (($_GET['sort'] ?? '') === 'order_number') echo ($_GET['order'] ?? 'DESC') === 'ASC' ? '▲' : '▼'; ?>
                    </a>
                </th>
                <th>
                    <a href="admin.php?page=orders&sort=email&order=<?php echo ($_GET['sort'] ?? '') === 'email' && ($_GET['order'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="text-white text-decoration-none">
                        Customer <?php if (($_GET['sort'] ?? '') === 'email') echo ($_GET['order'] ?? 'DESC') === 'ASC' ? '▲' : '▼'; ?>
                    </a>
                </th>
                <th>
                    <a href="admin.php?page=orders&sort=created_at&order=<?php echo ($_GET['sort'] ?? '') === 'created_at' && ($_GET['order'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="text-white text-decoration-none">
                        Date <?php if (($_GET['sort'] ?? 'created_at') === 'created_at') echo ($_GET['order'] ?? 'DESC') === 'ASC' ? '▲' : '▼'; ?>
                    </a>
                </th>
                <th>
                    <a href="admin.php?page=orders&sort=item_count&order=<?php echo ($_GET['sort'] ?? '') === 'item_count' && ($_GET['order'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="text-white text-decoration-none">
                        Items <?php if (($_GET['sort'] ?? '') === 'item_count') echo ($_GET['order'] ?? 'DESC') === 'ASC' ? '▲' : '▼'; ?>
                    </a>
                </th>
                <th>
                    <a href="admin.php?page=orders&sort=total_amount&order=<?php echo ($_GET['sort'] ?? '') === 'total_amount' && ($_GET['order'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="text-white text-decoration-none">
                        Total Amount <?php if (($_GET['sort'] ?? '') === 'total_amount') echo ($_GET['order'] ?? 'DESC') === 'ASC' ? '▲' : '▼'; ?>
                    </a>
                </th>
                <th>
                    <a href="admin.php?page=orders&sort=order_status&order=<?php echo ($_GET['sort'] ?? '') === 'order_status' && ($_GET['order'] ?? 'DESC') === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="text-white text-decoration-none">
                        Status <?php if (($_GET['sort'] ?? '') === 'order_status') echo ($_GET['order'] ?? 'DESC') === 'ASC' ? '▲' : '▼'; ?>
                    </a>
                </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                <td><?php echo htmlspecialchars($order['email']); ?></td>
                <td><?php echo UIHelper::formatDate($order['created_at']); ?></td>
                <td><?php echo $order['item_count']; ?></td>
                <td><?php echo UIHelper::formatCurrency($order['total_amount']); ?></td>
                <td>
                    <?php echo UIHelper::renderSimpleStatusBadge($order['order_status']); ?>
                </td>
                <td>
                    <a href="admin.php?page=order_detail&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> View
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="alert alert-info text-center">
    No orders found.
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../admin_layout.php';
?>