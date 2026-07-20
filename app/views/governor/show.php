<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <?php flash('complaint_error'); ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #6f42c1;">
                        <i class="fas fa-file-alt me-2"></i>Complaint: <?php echo htmlspecialchars($data['complaint']->complaint_no); ?>
                    </h5>
                    <div class="d-flex gap-2">
                        <button onclick="window.open('<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $data['complaint']->id; ?>?action=print', 'PrintPDF', 'width=1000,height=800');" class="btn btn-sm btn-primary">
                            <i class="fas fa-print"></i> Print PDF
                        </button>
                        <a href="<?php echo URLROOT; ?>/governor" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Read-only badge -->
                <div class="px-4 pt-3">
                    <span class="badge rounded-pill px-3 py-2 mb-2" style="background: #f0ebff; color: #6f42c1; border: 1px solid #d8c9f5;">
                        <i class="fas fa-eye me-1"></i> View Only ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Governor Report
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 mb-2 ms-1">
                        <i class="fas fa-check me-1"></i> <?php echo htmlspecialchars($data['complaint']->status); ?>
                    </span>
                </div>

                <div class="card-body pt-2">
                    <!-- Applicant Information -->
                    <h6 class="border-bottom pb-2 mb-3 text-muted mt-2">Applicant Information</h6>
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
                            <p class="mb-1 text-muted small">Mobile</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->mobile); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Email</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->email ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1 text-muted small">Address</p>
                            <p class="fw-semibold"><?php echo nl2br(htmlspecialchars($data['complaint']->address)); ?></p>
                        </div>
                    </div>

                    <!-- Complaint Details -->
                    <h6 class="border-bottom pb-2 mb-3 mt-4 text-muted">Complaint Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Category</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($data['complaint']->category_name); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Department</p>
                            <p class="fw-semibold">
                                <?php echo htmlspecialchars($data['complaint']->department_name ?? 'N/A'); ?>
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

                    <!-- Letter Preview -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <p class="mb-2 fw-bold" style="color: #6f42c1;"><i class="fas fa-scroll me-1"></i> Letter Preview</p>
                            <div class="border rounded overflow-auto shadow-sm" style="background: #fff;">
                                <?php
                                ob_start();
                                require APPROOT . '/views/complaints/pdf_template.php';
                                $letter_html = ob_get_clean();
                                ?>
                                <iframe srcdoc="<?php echo htmlspecialchars($letter_html, ENT_QUOTES, 'UTF-8'); ?>" width="100%" scrolling="no" onload="this.style.height = (this.contentWindow.document.documentElement.scrollHeight + 50) + 'px';" style="border: none; display: block; overflow: hidden; min-height: 800px; min-width: 820px;"></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Rejection History (if any) -->
                    <?php if (!empty($data['rejections'])): ?>
                        <hr>
                        <div class="alert alert-warning mb-4 shadow-sm border-warning border-start border-5 rounded">
                            <h6 class="alert-heading text-warning-emphasis fw-bold mb-2"><i class="fas fa-history"></i> Previous Rejection History</h6>
                            <?php foreach($data['rejections'] as $log): ?>
                                <div class="mb-2 pb-2 border-bottom border-warning-subtle">
                                    <small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($log->created_at)); ?></small>
                                    <p class="mb-0 small"><?php echo htmlspecialchars($log->remarks); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Read-only notice (no action for Governor) -->
                    <div class="alert border-0 rounded-3 d-flex align-items-center gap-3 mt-3" style="background: #f0ebff; color: #6f42c1;">
                        <i class="fas fa-info-circle fa-lg"></i>
                        <div>
                            <strong>Governor's View</strong><br>
                            <small>This complaint has been approved by the Governor's Secretary and is being processed by the Chief Clerk. No action required from the Governor.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
