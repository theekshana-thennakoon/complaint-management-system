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
    <!-- About Section -->
    <section class="about-section card">
        <div class="about-content">
            <h2>About <?php echo SITENAME; ?></h2>
            <p>Government Complaint System is a dedicated initiative by the Governor's Office of the North Central Province designed to bridge the gap between citizens and government authorities. Our platform ensures that every voice is heard, addressing public grievances with transparency, accountability, and unprecedented speed.</p>
            <ul class="about-highlights">
                <li><i class="fas fa-check-circle"></i> Transparent processing</li>
                <li><i class="fas fa-check-circle"></i> Direct departmental routing</li>
                <li><i class="fas fa-check-circle"></i> Status tracking anytime</li>
            </ul>
        </div>
        <div class="about-image">
            <div class="image-placeholder"><i class="fas fa-landmark"></i></div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-header">
            <h2>Get in Touch</h2>
            <p style="color: var(--text-secondary);">We are here to assist you. Reach out to our office for any general inquiries.</p>
        </div>
        <div class="contact-grid">
            <div class="contact-card card">
                <i class="fas fa-map-marker-alt contact-icon"></i>
                <h4>Visit Us</h4>
                <p>Governor's Office, North Central Province,<br>Anuradhapura, Sri Lanka</p>
            </div>
            <div class="contact-card card">
                <i class="fas fa-phone-alt contact-icon"></i>
                <h4>Call Us</h4>
                <p>+94 25 222 2222<br>+94 25 222 2223</p>
            </div>
            <div class="contact-card card">
                <i class="fas fa-envelope contact-icon"></i>
                <h4>Email Us</h4>
                <p>info@ncp.gov.lk<br>support@ncp.gov.lk</p>
            </div>
        </div>
        
        <div class="contact-map card">
            <iframe src="https://maps.google.com/maps?q=Governor's%20Office,%20North%20Central%20Province,%20Anuradhapura,%20Sri%20Lanka&t=&z=14&ie=UTF8&iwloc=&output=embed" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </section>
   
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
