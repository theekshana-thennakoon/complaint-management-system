<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i> Edit Department</h5>
                    <a href="<?php echo URLROOT; ?>/admin" class="btn btn-sm btn-light rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Back</a>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="<?php echo URLROOT; ?>/admin/editDepartment/<?php echo $data['id']; ?>" method="post">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($data['name']); ?>">
                            <span class="invalid-feedback"><?php echo isset($data['name_err']) ? $data['name_err'] : ''; ?></span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold shadow-sm"><i class="fas fa-save me-2"></i> Update Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
