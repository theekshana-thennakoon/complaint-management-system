    <footer class="main-footer">
        <div class="container" style="padding: 16px 24px;">
            <p>&copy; <?php echo date('Y'); ?> <strong style="color: rgba(255,255,255,0.7);"><?php echo SITENAME; ?></strong> &mdash; Governor's Office, North Central Province. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo URLROOT; ?>/js/main.js"></script>
    
    <?php if(isset($_SESSION['sweet_success'])) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $_SESSION['sweet_success']; ?>',
            <?php if(isset($_SESSION['sweet_html'])): ?>
            html: '<?php echo addslashes($_SESSION['sweet_html']); ?>',
            <?php elseif(isset($_SESSION['sweet_ref'])): ?>
            html: 'Your Reference Number: <b><?php echo $_SESSION['sweet_ref']; ?></b>',
            <?php endif; ?>
            confirmButtonColor: 'var(--primary-color)'
        });
    </script>
    <?php 
        unset($_SESSION['sweet_success']);
        unset($_SESSION['sweet_ref']);
        unset($_SESSION['sweet_html']);
    endif; ?>
</body>
</html>
