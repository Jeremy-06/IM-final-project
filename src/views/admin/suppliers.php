<?php
$pageTitle = 'Manage Suppliers - Admin';
ob_start();

require_once __DIR__ . '/../../helpers/Session.php';
?>

<div class="row mb-4 mt-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="margin-top: 0;">Manage Suppliers</h2>
            <a href="admin.php?page=create_supplier" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Supplier
            </a>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form action="admin.php" method="GET" class="d-flex align-items-center gap-2">
            <input type="hidden" name="page" value="suppliers">
            
            <div class="input-group" style="max-width: 400px;">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search suppliers..." 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>"
                       style="border: 2px solid #8b5fbf; border-radius: 25px 0 0 25px; padding: 10px 20px;">
                <button type="submit" class="btn" style="background: #8b5fbf; color: white; border: 2px solid #8b5fbf; border-radius: 0 25px 25px 0; padding: 10px 20px;">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            
            <?php if (isset($search) && $search !== ''): ?>
                <a href="admin.php?page=suppliers" 
                   class="btn" style="background: #6c757d; color: white; border-radius: 25px; padding: 10px 20px; text-decoration: none;">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if (empty($suppliers)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No suppliers found.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Person</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($supplier['supplier_name']); ?></strong>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($supplier['contact_person'] ?? '-'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($supplier['phone'] ?? '-'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($supplier['email'] ?? '-'); ?>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo $supplier['product_count']; ?></span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="admin.php?page=edit_supplier&id=<?php echo $supplier['id']; ?>" 
                                   class="btn btn-sm" 
                                   style="background: linear-gradient(135deg, #b19cd9 0%, #d6b6ff 100%); color: white; border: none; border-radius: 20px 0 0 20px; padding: 8px 16px;"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($supplier['product_count'] == 0): ?>
                                    <a href="admin.php?page=delete_supplier&id=<?php echo $supplier['id']; ?>" 
                                       class="btn btn-sm" 
                                       style="background: #dc3545; color: white; border: none; border-radius: 0 20px 20px 0; padding: 8px 16px;"
                                       onclick="return confirm('Are you sure you want to delete this supplier?');" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm" 
                                            style="background: #6c757d; color: white; border: none; border-radius: 0 20px 20px 0; padding: 8px 16px;" 
                                            disabled title="Cannot delete - has products">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../admin_layout.php';
?>
