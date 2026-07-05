<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-5">
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="fw-bold" style="color: var(--primary-color);"><i class="fas fa-users-cog me-2"></i> Admin Dashboard</h2>
            <button type="button" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus me-2"></i> Add New User</button>
        </div>
        
        <?php flash('admin_message'); ?>

        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 text-uppercase fw-bold mb-1">Total Users</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $data['stats']['total']; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 text-uppercase fw-bold mb-1">Active Users</h6>
                            <h2 class="display-5 fw-bold mb-0"><?php echo $data['stats']['active']; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="text-muted text-uppercase fw-bold mb-3">Role Distribution</h6>
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <h3 class="fw-bold text-primary mb-0"><?php echo $data['stats']['gs']; ?></h3>
                                <span class="small text-muted fw-semibold">Gov. Secretary</span>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <h3 class="fw-bold text-info mb-0"><?php echo $data['stats']['ao']; ?></h3>
                                <span class="small text-muted fw-semibold">Admin. Officer</span>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <h3 class="fw-bold text-success mb-0"><?php echo $data['stats']['cc']; ?></h3>
                                <span class="small text-muted fw-semibold">Chief Clerk</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-5">
            <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4">
                <h5 class="mb-0 fw-bold text-secondary">Managed Users List</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="py-3 rounded-start">User Details</th>
                                <th class="py-3">Role</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php if(empty($data['users'])): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 text-light"></i>
                                        <h5>No users found</h5>
                                        <p>Click "Add New User" to create one.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data['users'] as $user): ?>
                                    <tr>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px; font-size: 1.2rem;">
                                                    <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($user->name); ?></h6>
                                                    <span class="text-muted small">@<?php echo htmlspecialchars($user->username); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-primary rounded-pill px-3 py-2 fw-semibold shadow-sm">
                                                <i class="fas fa-user-shield me-1"></i> <?php echo htmlspecialchars($user->role_name); ?>
                                                <?php if(!empty($user->department_name)): ?>
                                                    - <small><?php echo htmlspecialchars($user->department_name); ?></small>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <?php if($user->status == 'active'): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 text-end">
                                            <a href="<?php echo URLROOT; ?>/admin/edit/<?php echo $user->id; ?>" class="btn btn-sm btn-light text-primary rounded-circle p-2 mx-1 shadow-sm" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo URLROOT; ?>/admin/delete/<?php echo $user->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2 mx-1 shadow-sm" title="Delete User">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 py-3">
                <h5 class="modal-title fw-bold text-primary" id="addUserModalLabel"><i class="fas fa-user-plus me-2"></i> Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form action="<?php echo URLROOT; ?>/admin/create" method="post" id="addUserForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
                            <span class="invalid-feedback"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control rounded-3 <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['username']) ? $data['username'] : ''; ?>">
                            <span class="invalid-feedback"><?php echo isset($data['username_err']) ? $data['username_err'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-3 <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">
                            <span class="invalid-feedback"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role_id" class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select rounded-3 <?php echo (!empty($data['role_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="" disabled selected>Select Role</option>
                                <?php if(isset($data['roles'])): ?>
                                    <?php foreach($data['roles'] as $role): ?>
                                        <option value="<?php echo $role->id; ?>" <?php echo (isset($data['role_id']) && $data['role_id'] == $role->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($role->name); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo isset($data['role_id_err']) ? $data['role_id_err'] : ''; ?></span>
                        </div>
                    </div>
                    <div class="row" id="departmentSelectRow" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label for="department_id" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select rounded-3">
                                <option value="" disabled selected>Select Department</option>
                                <?php if(isset($data['departments'])): ?>
                                    <?php foreach($data['departments'] as $dept): ?>
                                        <option value="<?php echo $dept->id; ?>" <?php echo (isset($data['department_id']) && $data['department_id'] == $dept->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept->name); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Required only for Department users.</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-3 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addUserForm" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fas fa-save me-2"></i> Save User</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role_id"]');
    const deptRow = document.getElementById('departmentSelectRow');
    
    function toggleDepartment() {
        if (roleSelect.value == '6') { // 6 = Subject Officer / Department
            deptRow.style.display = 'flex';
        } else {
            deptRow.style.display = 'none';
        }
    }
    
    if(roleSelect) {
        roleSelect.addEventListener('change', toggleDepartment);
        toggleDepartment();
    }
});
</script>

<?php if(isset($data['show_modal']) && $data['show_modal'] == true): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please correct the errors in the form before saving.',
            confirmButtonColor: 'var(--primary-color)'
        }).then((result) => {
            var myModal = new bootstrap.Modal(document.getElementById('addUserModal'), {
                keyboard: false
            });
            myModal.show();
        });
    });
</script>
<?php endif; ?>

<?php require APPROOT . '/views/layout/footer.php'; ?>
