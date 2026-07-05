<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Management</div>
        <a href="<?php echo URLROOT; ?>/complaints" class="sidebar-menu-item">
            <i class="fas fa-list"></i> All Complaints
        </a>
        <a href="<?php echo URLROOT; ?>/complaints/create" class="sidebar-menu-item active">
            <i class="fas fa-plus"></i> New Complaint
        </a>
        

    </aside>

    <main class="dashboard-content">
        <a href="<?php echo URLROOT; ?>/complaints" class="btn btn-secondary" style="margin-bottom: 20px;">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="card" style="max-width: 800px;">
            <div class="card-header">
                <h3>Add New Complaint (Internal)</h3>
            </div>
            
            <form action="<?php echo URLROOT; ?>/complaints/create" method="POST">
                
                <?php if(!empty($data['err'])) : ?>
                    <div class="alert alert-danger" style="margin-top: 15px;"><?php echo $data['err']; ?></div>
                <?php endif; ?>

                <h4 style="margin: 20px 0 15px; color: var(--primary-color);">Applicant Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name" class="form-label">Applicant Name *</label>
                        <input type="text" name="applicant_name" class="form-control" value="<?php echo $data['applicant_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nic" class="form-label">NIC *</label>
                        <input type="text" name="nic" class="form-control" value="<?php echo $data['nic']; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile" class="form-label">Contact Number (Mobile) *</label>
                        <input type="text" name="mobile" class="form-control" value="<?php echo $data['mobile']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2"><?php echo $data['address']; ?></textarea>
                </div>

                <h4 style="margin: 20px 0 15px; color: var(--primary-color);">Complaint Details</h4>
                <div class="form-group">
                    <label for="subject" class="form-label">Subject *</label>
                    <input type="text" name="subject" class="form-control" value="<?php echo $data['subject']; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category *</label>
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach($data['categories'] as $category) : ?>
                                <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="forward_department_id" class="form-label">Forward To Department *</label>
                        <select name="forward_department_id" class="form-control">
                            <option value="">Select Department</option>
                            <?php foreach($data['departments'] as $department) : ?>
                                <option value="<?php echo $department->id; ?>" <?php echo ($data['forward_department_id'] == $department->id) ? 'selected' : ''; ?>><?php echo $department->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                

                
                <h4 style="margin: 30px 0 15px; color: var(--primary-color);">Additional Details</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detailsTable">
                        <thead>
                            <tr>
                                <th style="width: 10%;">අනු අංක</th>
                                <th style="width: 30%;">ලිපියේ අංකය</th>
                                <th style="width: 50%;">නම හා කාරණය</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="detailsTbody">
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
                                <td><input type="text" name="detail_name[]" class="form-control"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-secondary btn-sm" id="addRowBtn" style="margin-top: 10px;">
                        <i class="fas fa-plus"></i> Add Row
                    </button>
                </div>
                
                <input type="hidden" name="direct_forward" id="direct_forward" value="">

                <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <button type="submit" class="btn btn-primary" onclick="document.getElementById('direct_forward').value=''">
                        <i class="fas fa-save me-1"></i> Save &amp; Submit to CC
                    </button>
                    <div style="width: 1px; height: 36px; background: var(--gray-200);"></div>
                    <button type="submit" class="btn btn-warning" onclick="document.getElementById('direct_forward').value='ao'">
                        <i class="fas fa-user-tie me-1"></i> Forward Directly to AO
                    </button>
                    <button type="submit" class="btn btn-info" onclick="document.getElementById('direct_forward').value='gs'">
                        <i class="fas fa-landmark me-1"></i> Forward Directly to GS
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
