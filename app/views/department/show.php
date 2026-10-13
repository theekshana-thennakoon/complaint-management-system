<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Department</div>
        <a href="<?php echo URLROOT; ?>/department" class="sidebar-menu-item">
            <i class="fas fa-list"></i> Department Letters
        </a>
    </aside>

    <main class="dashboard-content">
        <?php flash('auth_error'); ?>
        <?php flash('reply_error'); ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="<?php echo URLROOT; ?>/department" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <?php 
                $badge_class = 'status-pending';
                if($data['complaint']->status == 'Approved') $badge_class = 'status-resolved';
                if($data['complaint']->status == 'Rejected') $badge_class = 'status-rejected';
                if($data['complaint']->status == 'Sent to CC') $badge_class = 'status-info-requested';
                if($data['complaint']->status == 'Reply Received') $badge_class = 'badge bg-info text-white';
            ?>
            <div>
                <strong>Status:</strong> <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($data['complaint']->status); ?></span>
            </div>
        </div>

        <!-- Department Reply Section -->
        <div class="card mb-4 shadow-sm border-0 rounded-4" style="border-left: 5px solid #0d6efd !important;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-reply me-2"></i> Department Reply &amp; Actions</h5>
                <?php if (!empty($data['reply'])): ?>
                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Reply Sent</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i> Awaiting Reply</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($data['reply'])): ?>
                    <div class="alert alert-light border rounded-3 p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong><i class="fas fa-building me-1 text-primary"></i> <?php echo htmlspecialchars($data['reply']->department_name ?? 'Your Department'); ?></strong>
                            <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> <?php echo date('M d, Y h:i A', strtotime($data['reply']->created_at)); ?></small>
                        </div>
                        <p class="mb-2" style="white-space: pre-wrap; font-size: 0.95rem;"><?php echo nl2br(htmlspecialchars($data['reply']->reply_text)); ?></p>
                        <?php if (!empty($data['reply']->attachment_path)): ?>
                            <div class="mt-2 pt-2 border-top">
                                <a href="<?php echo URLROOT . '/' . htmlspecialchars($data['reply']->attachment_path); ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-paperclip me-1"></i> <?php echo htmlspecialchars($data['reply']->attachment_name ?? 'View Attached File'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-edit me-1 text-secondary"></i> Send New / Updated Reply</h6>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/department/reply/<?php echo $data['complaint']->id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="reply_text" class="form-label fw-bold">Reply Details / Action Taken <span class="text-danger">*</span></label>
                        <textarea name="reply_text" id="reply_text" class="form-control" rows="4" placeholder="Enter your response, progress update, or actions taken regarding this complaint..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label fw-bold">Attach Document / Report (Optional)</label>
                        <input type="file" name="attachment" id="attachment" class="form-control">
                        <div class="form-text">You can attach PDF, Word documents, images, or scanned letters.</div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                        <i class="fas fa-paper-plane me-2"></i> Submit Reply
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Complaint Details: <?php echo $data['complaint']->complaint_no; ?></h3>
            </div>

            <div class="form-row" style="margin-bottom: 30px;">
                <div style="flex: 1;">
                    <h4 style="color: var(--primary-color); margin-bottom: 10px;">Applicant Information</h4>
                    <table class="data-table">
                        <tr>
                            <th width="30%">Name</th>
                            <td><?php echo $data['complaint']->applicant_name; ?></td>
                        </tr>
                        <tr>
                            <th>NIC</th>
                            <td><?php echo $data['complaint']->nic; ?></td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td><?php echo $data['complaint']->mobile; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo nl2br($data['complaint']->address); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div style="flex: 1;">
                    <h4 style="color: var(--primary-color); margin-bottom: 10px;">Complaint Details</h4>
                    <table class="data-table">
                        <tr>
                            <th width="30%">Subject</th>
                            <td><?php echo $data['complaint']->subject; ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo $data['complaint']->category_name; ?></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>
                                <?php echo htmlspecialchars($data['complaint']->department_name ?? 'N/A'); ?>
                                <?php if(!empty($data['complaint']->person)) : ?>
                                    <span class="text-muted"> (<?php echo htmlspecialchars($data['complaint']->person); ?>)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo $data['complaint']->date; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="color: var(--primary-color); margin: 0;">Letter Preview</h4>
                    <button onclick="window.open('<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $data['complaint']->id; ?>?action=print', 'PrintPDF', 'width=1000,height=800');" class="btn btn-sm btn-primary">
                        <i class="fas fa-print"></i> Print PDF
                    </button>
                </div>
                <div style="border: 1px solid var(--panel-border); background: #fff; border-radius: 8px; overflow-x: auto; overflow-y: hidden;">
                    <?php
                    ob_start();
                    require APPROOT . '/views/complaints/pdf_template.php';
                    $letter_html = ob_get_clean();
                    ?>
                    <iframe srcdoc="<?php echo htmlspecialchars($letter_html, ENT_QUOTES, 'UTF-8'); ?>" width="100%" scrolling="no" onload="this.style.height = (this.contentWindow.document.documentElement.scrollHeight + 50) + 'px';" style="border: none; display: block; overflow: hidden;"></iframe>
                </div>
            </div>
        </div>

    </main>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
