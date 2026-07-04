<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            
            <?php if($data['complaint']->status == 'Rejected by CC' && $data['reject_log']): ?>
            <div class="alert alert-danger shadow-sm border-danger border-start border-5 mb-4 rounded">
                <h5 class="alert-heading text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Rejected by Chief Clerk</h5>
                <p class="mb-0 mt-2"><strong>Reason for Rejection:</strong> <?php echo htmlspecialchars($data['reject_log']->remarks); ?></p>
                <hr>
                <p class="mb-0 small text-danger">Please correct the information below based on the remarks and resubmit.</p>
            </div>
            <?php endif; ?>

            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient bg-primary text-white py-3 rounded-top-4">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Edit & Resubmit Complaint</h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?php echo URLROOT; ?>/complaints/edit/<?php echo $data['id']; ?>" method="POST">
                        
                        <?php if(!empty($data['err'])) : ?>
                            <div class="alert alert-danger"><?php echo $data['err']; ?></div>
                        <?php endif; ?>

                        <h5 class="text-primary mb-3 border-bottom pb-2">Applicant Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="applicant_name" class="form-label fw-bold">Applicant Name *</label>
                                <input type="text" name="applicant_name" class="form-control" value="<?php echo htmlspecialchars($data['applicant_name']); ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="nic" class="form-label fw-bold">NIC *</label>
                                <input type="text" name="nic" class="form-control" value="<?php echo htmlspecialchars($data['nic']); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="mobile" class="form-label fw-bold">Contact Number (Mobile) *</label>
                                <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($data['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($data['address']); ?></textarea>
                        </div>

                        <h5 class="text-primary mb-3 border-bottom pb-2">Complaint Details</h5>
                        <div class="form-group mb-3">
                            <label for="subject" class="form-label fw-bold">Subject *</label>
                            <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($data['subject']); ?>" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="category_id" class="form-label fw-bold">Category *</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="forward_department_id" class="form-label fw-bold">Forward To Department *</label>
                                <select name="forward_department_id" class="form-control" required>
                                    <option value="">Select Department</option>
                                    <?php foreach($data['departments'] as $department) : ?>
                                        <option value="<?php echo $department->id; ?>" <?php echo ($data['forward_department_id'] == $department->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($department->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        

                        
                        <h5 class="text-primary mb-3 border-bottom pb-2">Additional Details</h5>
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
                                    <?php if(!empty($data['details'])): ?>
                                        <?php $i = 1; foreach($data['details'] as $detail): ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><input type="text" name="detail_letter_no[]" class="form-control" value="<?php echo htmlspecialchars($detail->letter_no); ?>"></td>
                                                <td><input type="text" name="detail_name[]" class="form-control" value="<?php echo htmlspecialchars($detail->name); ?>"></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php $i++; endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
                                            <td><input type="text" name="detail_name[]" class="form-control"></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-secondary btn-sm" id="addRowBtn" style="margin-bottom: 20px;">
                                <i class="fas fa-plus"></i> Add Row
                            </button>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Resubmit Complaint
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let rowCount = document.getElementById('detailsTbody').children.length;
    document.getElementById('addRowBtn').addEventListener('click', function() {
        rowCount++;
        const tbody = document.getElementById('detailsTbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${rowCount}</td>
            <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
            <td><input type="text" name="detail_name[]" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('detailsTable').addEventListener('click', function(e) {
        if(e.target.closest('.remove-row')) {
            const tr = e.target.closest('tr');
            if(document.getElementById('detailsTbody').children.length > 1) {
                tr.remove();
                const rows = document.getElementById('detailsTbody').querySelectorAll('tr');
                let count = 0;
                rows.forEach(row => {
                    count++;
                    row.cells[0].innerText = count;
                });
                rowCount = count;
            } else {
                tr.querySelector('input[name="detail_letter_no[]"]').value = '';
                tr.querySelector('input[name="detail_name[]"]').value = '';
            }
        }
    });
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
