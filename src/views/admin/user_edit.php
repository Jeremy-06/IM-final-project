<?php
$pageTitle = 'Edit User - Admin';
ob_start();

require_once __DIR__ . '/../../helpers/Session.php';
require_once __DIR__ . '/../../helpers/CSRF.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2>Edit User</h2>
        <p class="text-muted">Change role for <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
    </div>
</div>

<?php if ($user): ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="admin.php?page=edit_user&id=<?php echo $user['id']; ?>">
                    <?php echo CSRF::getTokenField(); ?>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-control">
                            <option value="customer" <?php echo ($user['role'] === 'customer') ? 'selected' : ''; ?>>Customer</option>
                            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="admin.php?page=users" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-warning">User not found.</div>
<a href="admin.php?page=users" class="btn btn-secondary">Back</a>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../admin_layout';
?>


