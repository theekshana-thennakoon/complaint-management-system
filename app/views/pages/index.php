<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="container">
    <section class="hero-section">
        <h2>Welcome to <?php echo SITENAME; ?></h2>
        <p>A secure and efficient platform to submit, track, and manage citizen complaints for the Governor's Office of the North Central Province.</p>
        
        <div class="hero-actions">
            <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-primary btn-lg">
                <i class="fas fa-file-signature"></i> Submit a Complaint
            </a>
            <a href="<?php echo URLROOT; ?>/publiccomplaint/status" class="btn btn-accent btn-lg">
                <i class="fas fa-search"></i> Check Status
            </a>
        </div>
    </section>

    <div class="features-grid">
        <div class="feature-card card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Secure & Confidential</h3>
            <p style="color: var(--text-secondary); margin-top: 10px;">Your complaints and personal details are handled with strict confidentiality and top-tier security.</p>
        </div>
        
        <div class="feature-card card">
            <div class="feature-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <h3>Fast Processing</h3>
            <p style="color: var(--text-secondary); margin-top: 10px;">Automated routing to relevant departments ensures your complaints are reviewed rapidly.</p>
        </div>
        
        <div class="feature-card card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3>Real-time Tracking</h3>
            <p style="color: var(--text-secondary); margin-top: 10px;">Track the status of your complaints anytime, anywhere with your unique reference number.</p>
        </div>
    </div>
   
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
