<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div style="width: 100%; max-width: 450px;">
        <div class="card">
            <div class="card-header" style="justify-content: center; border-bottom: none;">
                <div style="text-align: center;">
                    <i class="fas fa-key" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                    <h2>Change Password</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Please enter your old and new password.</p>
                </div>
            </div>
            
            <div style="padding: 0 10px;">
                <form action="<?php echo URLROOT; ?>/auth/change_password" method="POST">
                    
                    <!-- Username -->
                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>" placeholder="Enter username">
                        <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
                    </div>

                    <!-- Old Password -->
                    <div class="form-group mb-3">
                        <label for="old_password" class="form-label">Old Password</label>
                        <input type="password" name="old_password" class="form-control <?php echo (!empty($data['old_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter old password">
                        <span class="invalid-feedback"><?php echo $data['old_password_err']; ?></span>
                    </div>
                    
                    <!-- New Password -->
                    <div class="form-group mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Enter new password">
                        <span class="invalid-feedback"><?php echo $data['new_password_err']; ?></span>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Confirm new password">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-save"></i> Change Password
                        </button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px; border-top: 1px solid var(--panel-border); padding-top: 20px;">
                    <a href="<?php echo URLROOT; ?>/auth/login" style="font-size: 0.9rem;"><i class="fas fa-arrow-left"></i> Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
