    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?> - Governor's Office, North Central Province. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo URLROOT; ?>/js/main.js"></script>
    
    <?php if(isset($_SESSION['sweet_success'])) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $_SESSION['sweet_success']; ?>',
            html: 'Your Reference Number: <b><?php echo $_SESSION['sweet_ref']; ?></b>',
            confirmButtonColor: 'var(--primary-color)'
        });
    </script>
    <?php 
        unset($_SESSION['sweet_success']);
        unset($_SESSION['sweet_ref']);
    endif; ?>
</body>
</html>
