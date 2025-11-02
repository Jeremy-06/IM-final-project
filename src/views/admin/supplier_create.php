<?php
$pageTitle = 'Add New Supplier - Admin';
ob_start();

require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/CSRF.php';
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-4">Add New Supplier</h3>
                
                <form method="POST" action="admin.php?page=create_supplier">
                    <?php echo CSRF::getTokenField(); ?>
                    
                    <div class="form-group mb-3">
                        <label for="supplier_name">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" 
                               placeholder="Enter supplier name" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="contact_person">Contact Person</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" 
                               placeholder="Enter contact person name">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               placeholder="Enter phone number">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Enter email address">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" 
                                  placeholder="Enter supplier address"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Supplier
                        </button>
                        <a href="admin.php?page=suppliers" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../admin_layout.php';
?>
