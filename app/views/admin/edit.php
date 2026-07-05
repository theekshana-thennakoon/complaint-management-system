<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit User</h2>
            <a href="<?php echo URLROOT; ?>/admin" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/admin/edit/<?php echo $data['id']; ?>" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <sup>*</sup></label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username <sup>*</sup></label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
                            <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-muted">(Leave blank to keep unchanged)</span></label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_role_id" class="form-label">Role <sup>*</sup></label>
                            <select name="role_id" id="edit_role_id" class="form-select <?php echo (!empty($data['role_id_err'])) ? 'is-invalid' : ''; ?>">
                                <?php foreach($data['roles'] as $role): ?>
                                    <option value="<?php echo $role->id; ?>" <?php echo ($data['role_id'] == $role->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($role->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['role_id_err']; ?></span>
                        </div>
                    </div>
                    <div class="row" id="departmentSelectRow" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="" disabled selected>Select Department</option>
                                <?php if(isset($data['departments'])): ?>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?php echo $dept->id; ?>" <?php echo ($data['department_id'] == $dept->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept->name); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Required only for Department users.</div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('edit_role_id');
    const deptRow = document.getElementById('departmentSelectRow');
    
    function toggleDepartment() {
        if (roleSelect && roleSelect.value == '7') { // 7 = Department Officer
            deptRow.style.display = 'flex';
        } else {
            deptRow.style.display = 'none';
        }
    }
    
    if(roleSelect) {
        roleSelect.addEventListener('change', toggleDepartment);
        toggleDepartment(); // trigger on load
    }
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
