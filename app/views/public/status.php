<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="container">
    <div style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 50px;">
        <div style="width: 100%; max-width: 600px;">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2>Check Complaint Status</h2>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Enter your Reference Number below.</p>
                    </div>
                </div>
                
                <form action="<?php echo URLROOT; ?>/publiccomplaint/status" method="POST">
                    <div class="form-group">
                        <label for="complaint_no" class="form-label">Reference Number (e.g. COMP-XXXX)</label>
                        <input type="text" name="complaint_no" class="form-control" placeholder="Enter Reference Number" value="<?php echo isset($data['ref']) ? htmlspecialchars($data['ref']) : ''; ?>" required>
                    </div>
                    <?php if(!isLoggedIn()): ?>
                    <div class="form-group" style="margin-top: 15px;">
                        <label for="nic_or_mobile" class="form-label">NIC or Mobile Number (For Verification)</label>
                        <input type="text" name="nic_or_mobile" class="form-control" placeholder="Enter your NIC or Mobile Number" value="<?php echo isset($data['nic_or_mobile']) ? htmlspecialchars($data['nic_or_mobile']) : ''; ?>" required>
                    </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-search"></i> Check Status
                    </button>
                </form>

                <?php if(!empty($data['ref'])): ?>
                    <hr style="border: 0; border-top: 1px solid var(--panel-border); margin: 30px 0;">
                    <?php if($data['complaint']): ?>
                        <div class="alert alert-success">
                            <h4>Complaint Found!</h4>
                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($data['complaint']->subject); ?></p>
                            <p><strong>Status:</strong> <span class="badge status-<?php echo strtolower($data['complaint']->status); ?>"><?php echo htmlspecialchars($data['complaint']->status); ?></span></p>
                            <p><strong>Date Filed:</strong> <?php echo htmlspecialchars($data['complaint']->date); ?></p>
                        </div>
                        
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot active"></div>
                                <div class="timeline-content">
                                    <strong>Current Status: <?php echo htmlspecialchars($data['complaint']->status); ?></strong>
                                    <div class="timeline-time">As of today</div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($data['err']); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
