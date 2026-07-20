<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php flash('complaint_error'); ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="fas fa-file-alt"></i> Review Complaint: <?php echo htmlspecialchars($data['complaint']->complaint_no); ?></h5>
                    <div>
                        <button onclick="window.open('<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $data['complaint']->id; ?>?action=print', 'PrintPDF', 'width=1000,height=800');" class="btn btn-sm btn-primary">
                            <i class="fas fa-print"></i> Print PDF
                        </button>
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
                            <p class="fw-semibold">
                                <?php echo htmlspecialchars($data['complaint']->department_name); ?>
                                <?php if(!empty($data['complaint']->person)) : ?>
                                    <span class="text-muted"> (<?php echo htmlspecialchars($data['complaint']->person); ?>)</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small">Subject</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->subject); ?></p>
                        </div>
                    </div>

                    <?php if(!empty($data['attachments'])): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small">Attachments</p>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach($data['attachments'] as $attachment): ?>
                                    <a href="<?php echo URLROOT; ?>/<?php echo $attachment->file_path; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i> <?php echo htmlspecialchars($attachment->file_name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small fw-bold">Letter Preview</p>
                            <div class="border rounded overflow-auto shadow-sm" style="background: #fff;">
                                <?php
                                ob_start();
                                require APPROOT . '/views/complaints/pdf_template.php';
                                $letter_html = ob_get_clean();
                                ?>
                                <iframe srcdoc="<?php echo htmlspecialchars($letter_html, ENT_QUOTES, 'UTF-8'); ?>" width="100%" scrolling="no" onload="this.style.height = (this.contentWindow.document.documentElement.scrollHeight + 50) + 'px';" style="border: none; display: block; overflow: hidden;"></iframe>
                            </div>
                        </div>
                    </div>



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
            Swal.fire({icon: 'warning', title: 'Remarks Required', text: 'Please provide remarks before rejecting the complaint.'});
            return;
        }
        form.action = '<?php echo URLROOT; ?>/ao/reject/<?php echo $data['complaint']->id; ?>';
        Swal.fire({
            title: 'Are you sure?',
            text: 'Are you sure you want to REJECT this complaint and return it to the CC?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    } else {
        form.action = '<?php echo URLROOT; ?>/ao/approve/<?php echo $data['complaint']->id; ?>';
        Swal.fire({
            title: 'Are you sure?',
            text: 'Are you sure you want to APPROVE and forward this complaint to the Government Secretary?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
}
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
