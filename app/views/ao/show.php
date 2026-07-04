<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php flash('complaint_error'); ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="fas fa-file-alt"></i> Review Complaint: <?php echo htmlspecialchars($data['complaint']->complaint_no); ?></h5>
                    <div>
                        <a href="<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $data['complaint']->id; ?>" class="btn btn-sm btn-accent" target="_blank"><i class="fas fa-file-pdf"></i> Generate PDF Letter</a>
                        <a href="<?php echo URLROOT; ?>/ao" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Applicant Name</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->applicant_name); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">NIC</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->nic); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Category</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->category_name); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Department</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->department_name); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small">Subject</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->subject); ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small">Description</p>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(htmlspecialchars($data['complaint']->description)); ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($data['details'])): ?>
                    <h6 class="border-bottom pb-2 mb-3">Additional Details</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Letter No</th>
                                    <th>Name/Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['details'] as $detail): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detail->letter_no); ?></td>
                                        <td><?php echo htmlspecialchars($detail->name); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                    <hr>

                    <?php if (!empty($data['rejections'])): ?>
                        <div class="alert alert-warning mb-4 shadow-sm border-warning border-start border-5 rounded">
                            <h6 class="alert-heading text-warning-emphasis fw-bold mb-2"><i class="fas fa-history"></i> Previous Rejection History</h6>
                            <ul class="mb-0 small">
                                <?php foreach($data['rejections'] as $rej): ?>
                                    <li><strong><?php echo date('Y-m-d h:i A', strtotime($rej->created_at)); ?>:</strong> <?php echo htmlspecialchars($rej->remarks); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <h5 class="mb-3"><i class="fas fa-user-tie"></i> Administrative Officer Decision</h5>
                    <?php if ($data['complaint']->current_role_id == 4): ?>
                        <form action="<?php echo URLROOT; ?>/ao/approve/<?php echo $data['complaint']->id; ?>" method="post" id="decisionForm">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks (Required for rejection)</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter any comments or reasoning for approval/rejection"></textarea>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-danger" onclick="submitDecision('reject')">
                                    <i class="fas fa-times-circle"></i> Reject (Return to CC)
                                </button>
                                <button type="button" class="btn btn-success" onclick="submitDecision('approve')">
                                    <i class="fas fa-check-circle"></i> Approve & Forward to GS
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info rounded-3 border-info border-opacity-25 bg-info bg-opacity-10 text-info-emphasis">
                            <i class="fas fa-info-circle me-2"></i> You have already processed this complaint. 
                            <br><strong>Current Status:</strong> <?php echo htmlspecialchars($data['complaint']->status); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function submitDecision(action) {
    const form = document.getElementById('decisionForm');
    const remarks = document.getElementById('remarks').value.trim();
    
    if (action === 'reject') {
        if (remarks === '') {
            alert('Please provide remarks before rejecting the complaint.');
            return;
        }
        form.action = '<?php echo URLROOT; ?>/ao/reject/<?php echo $data['complaint']->id; ?>';
        if(confirm('Are you sure you want to REJECT this complaint and return it to the CC?')) {
            form.submit();
        }
    } else {
        form.action = '<?php echo URLROOT; ?>/ao/approve/<?php echo $data['complaint']->id; ?>';
        if(confirm('Are you sure you want to APPROVE and forward this complaint to the Government Secretary?')) {
            form.submit();
        }
    }
}
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
