<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div style="width: 100%; max-width: 550px;">
        <div class="card">
            <div class="card-header" style="justify-content: center; border-bottom: none;">
                <div style="text-align: center;">
                    <i class="fas fa-user-plus" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                    <h2>Register Account</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Create a new officer account.</p>
                </div>
            </div>
            
            <div style="padding: 0 10px;">
                <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name'] ?? ''; ?>" placeholder="Enter full name">
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['name_err'] ?? ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="nic" class="form-label">NIC</label>
                        <input type="text" name="nic" class="form-control <?php echo (!empty($data['nic_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['nic'] ?? ''; ?>" placeholder="Enter NIC number">
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['nic_err'] ?? ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>" placeholder="Choose a username">
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['username_err']; ?></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Create password">
                            <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['password_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Confirm password">
                            <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['confirm_password_err']; ?></span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-check-circle"></i> Register
                        </button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px; border-top: 1px solid var(--panel-border); padding-top: 20px;">
                    <a href="<?php echo URLROOT; ?>/auth/login" style="font-size: 0.9rem;">Already have an account? Login here.</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
