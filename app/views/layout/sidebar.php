<div class="border-end bg-dark text-white" id="sidebar-wrapper">
    <div class="sidebar-heading border-bottom bg-dark text-white p-3 fw-bold fs-5">
        <i class="ri-government-line"></i> Mahajana Dinaya
    </div>
    <div class="list-group list-group-flush pt-3">
        <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="<?php echo URLROOT; ?>/dashboard">
            <i class="ri-dashboard-line"></i> Dashboard
        </a>
        
        <?php if($_SESSION['user_level'] == 10) : // Subject Officer ?>
            <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="<?php echo URLROOT; ?>/complaints/create">
                <i class="ri-file-add-line"></i> New Complaint
            </a>
        <?php endif; ?>


        <?php if($_SESSION['user_level'] >= 100) : // Admin ?>
            <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="<?php echo URLROOT; ?>/departments">
                <i class="ri-building-line"></i> Departments
            </a>
            <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="<?php echo URLROOT; ?>/users">
                <i class="ri-group-line"></i> Users
            </a>
            <a class="list-group-item list-group-item-action bg-dark text-white p-3" href="<?php echo URLROOT; ?>/settings">
                <i class="ri-settings-3-line"></i> Settings
            </a>
        <?php endif; ?>
    </div>
</div>
