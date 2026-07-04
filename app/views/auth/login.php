<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div style="width: 100%; max-width: 450px;">
        <div class="card">
            <div class="card-header" style="justify-content: center; border-bottom: none;">
                <div style="text-align: center;">
                    <i class="fas fa-user-shield" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                    <h2>Officer Portal Access</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Please enter your credentials to login.</p>
                </div>
            </div>
            
            <div style="padding: 0 10px;">
                <?php if(!empty($data['username_err'])): ?>
                    <div class="alert alert-danger"><?php echo $data['username_err']; ?></div>
                <?php endif; ?>
                <?php if(!empty($data['password_err'])): ?>
                    <div class="alert alert-danger"><?php echo $data['password_err']; ?></div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/auth/login" method="POST">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $data['username']; ?>" placeholder="Enter username">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-sign-in-alt"></i> Login to Portal
                        </button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px; border-top: 1px solid var(--panel-border); padding-top: 20px;">
                    <a href="<?php echo URLROOT; ?>/auth/register" style="font-size: 0.9rem;">Don't have an account? Register here.</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
