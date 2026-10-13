<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Management</div>
        
        <a href="<?php echo URLROOT; ?>/complaints/create" class="sidebar-menu-item">
            <i class="fas fa-plus"></i> New Complaint
        </a>
        <a href="<?php echo URLROOT; ?>/complaints/sent" class="sidebar-menu-item">
            <i class="fas fa-paper-plane"></i> Sent to Departments
        </a>
        

    </aside>

    <main class="dashboard-content">
        <?php flash('complaint_success'); ?>
        <?php flash('complaint_error'); ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="<?php echo URLROOT; ?>/complaints" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <?php 
                $badge_class = 'status-pending';
                if(strpos($data['complaint']->status, 'Approved') !== false) $badge_class = 'status-resolved';
                if(strpos($data['complaint']->status, 'Rejected') !== false) $badge_class = 'status-rejected';
                if(strpos($data['complaint']->status, 'Sent to CC') !== false) $badge_class = 'status-info-requested';
                if(strpos(strtolower($data['complaint']->status), 'department') !== false) $badge_class = 'status-resolved';
            ?>
            <div>
                <strong>Status:</strong> <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($data['complaint']->status); ?></span>
            </div>
        </div>

        <?php if(strpos($data['complaint']->status, 'Rejected') !== false && !empty($data['reject_log'])): ?>
            <div class="alert alert-danger shadow-sm border-danger border-start border-5 mb-4 rounded d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="alert-heading text-danger fw-bold mb-1"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($data['complaint']->status); ?></h5>
                    <p class="mb-0"><strong>Reason:</strong> <?php echo htmlspecialchars($data['reject_log']->remarks); ?></p>
                </div>
                <?php if ($data['complaint']->created_by == $_SESSION['user_id']): ?>
                <div>
                    <a href="<?php echo URLROOT; ?>/complaints/edit/<?php echo $data['complaint']->id; ?>" class="btn btn-danger">
                        <i class="fas fa-edit"></i> Edit & Resubmit
                    </a>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($data['complaint']->status == 'Draft'): ?>
            <div class="alert alert-info shadow-sm border-info border-start border-5 mb-4 rounded d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="alert-heading text-info fw-bold mb-1"><i class="fas fa-file-signature"></i> Draft Complaint</h5>
                    <p class="mb-0">This complaint is in draft status. You can edit and submit it to the Chief Clerk.</p>
                </div>
                <div>
                    <a href="<?php echo URLROOT; ?>/complaints/edit/<?php echo $data['complaint']->id; ?>" class="btn btn-info text-white fw-bold">
                        <i class="fas fa-edit"></i> Edit & Submit to CC
                    </a>
                </div>
            </div>
        <?php endif; ?>

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

        <div class="card">
            <div class="card-header">
                <h3>Complaint Details: <?php echo $data['complaint']->complaint_no; ?></h3>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <!-- <a href="<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $data['complaint']->id; ?>" class="btn btn-accent btn-sm">
                        <i class="fas fa-file-pdf"></i> Generate PDF Letter
                    </a> -->
                    <?php if ($data['complaint']->status == 'Approved by GS' || strpos(strtolower($data['complaint']->status), 'department') !== false || $data['complaint']->status == 'Reply Received'): ?>
                    <button type="button" class="btn btn-warning btn-sm fw-semibold"
                        onclick="openDispatchModal(<?php echo $data['complaint']->id; ?>, '<?php echo htmlspecialchars($data['complaint']->complaint_no, ENT_QUOTES); ?>')"
                        style="background: linear-gradient(135deg,#f7971e,#ffd200); border:none; color:#333;">
                        <i class="fas fa-paper-plane me-1"></i> Send to Department
                    </button>
                    <button type="button" class="btn btn-sm fw-semibold" id="openEmailModalBtn"
                        onclick="openEmailModal()"
                        style="background:linear-gradient(135deg, var(--primary-color, #2d9150), var(--primary-dark, #1b5c36));border:none;color:#fff;box-shadow:0 2px 8px rgba(45,145,80,0.3);">
                        <i class="fas fa-envelope me-1"></i> Send via Email
                    </button>
                    <?php endif; ?>
                    <?php if ($data['complaint']->status == 'Reply Received' || !empty($data['reply'])): ?>
                    <button type="button" class="btn btn-info btn-sm text-white fw-semibold"
                        onclick="openReplyModal(<?php echo $data['complaint']->id; ?>, '<?php echo htmlspecialchars($data['complaint']->complaint_no, ENT_QUOTES); ?>')">
                        <i class="fas fa-reply me-1"></i> Update Reply
                    </button>
                    <?php endif; ?>
                </div>
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
                        <?php if(!empty($data['complaint']->signatory_name)): ?>
                        <tr>
                            <th>Sign Person</th>
                            <td>
                                <strong><?php echo htmlspecialchars($data['complaint']->signatory_name); ?></strong>
                                <?php if (!empty($data['complaint']->signatory_title)): ?>
                                    <br><small class="text-muted"><?php echo nl2br(htmlspecialchars($data['complaint']->signatory_title)); ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <?php if(!empty($data['attachments'])): ?>
                    <h4 style="color: var(--primary-color); margin-top: 20px; margin-bottom: 10px;">Attachments</h4>
                    <table class="data-table">
                        <?php foreach($data['attachments'] as $attachment): ?>
                        <tr>
                            <td>
                                <a href="<?php echo URLROOT; ?>/<?php echo $attachment->file_path; ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> <?php echo htmlspecialchars($attachment->file_name); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($data['reply']) || $data['complaint']->status == 'Reply Received'): ?>
            <div class="card mb-4 border-0 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(2,132,199,0.25) !important;">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h4 class="mb-0 fw-bold d-flex align-items-center gap-2" style="color: #0284c7; font-size: 1.15rem;">
                        <i class="fas fa-reply"></i> Department Reply
                    </h4>
                    <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-sm fw-semibold"
                        onclick="openReplyModal(<?php echo $data['complaint']->id; ?>, '<?php echo htmlspecialchars($data['complaint']->complaint_no, ENT_QUOTES); ?>')">
                        <i class="fas fa-edit me-1"></i> Update Reply
                    </button>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($data['reply'])): ?>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Department</small>
                                <span class="fw-semibold text-dark"><?php echo htmlspecialchars($data['reply']->department_name ?? 'N/A'); ?></span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Replied On</small>
                                <span class="text-dark"><?php echo date('Y-m-d h:i A', strtotime($data['reply']->created_at)); ?></span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Replied By</small>
                                <span class="text-dark"><?php echo htmlspecialchars($data['reply']->replier_name ?? 'Staff'); ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size:0.75rem;">Reply Message</small>
                            <div class="p-3 bg-light rounded-3 border" style="white-space: pre-line;"><?php echo htmlspecialchars($data['reply']->reply_text); ?></div>
                        </div>

                        <?php if (!empty($data['reply']->attachment_path)): ?>
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size:0.75rem;">Attachment</small>
                            <a href="<?php echo URLROOT; ?>/<?php echo $data['reply']->attachment_path; ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fas fa-paperclip me-1"></i> <?php echo htmlspecialchars($data['reply']->attachment_name ?? 'Download Attachment'); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">Status is marked as Reply Received, but no reply message has been submitted yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

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

<?php if ($data['complaint']->status == 'Approved by GS' || strpos(strtolower($data['complaint']->status), 'department') !== false || $data['complaint']->status == 'Reply Received'): ?>
<!-- Send to Department Modal -->
<div class="modal fade" id="dispatchModal" tabindex="-1" aria-labelledby="dispatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header rounded-top-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)); padding: 1rem 1.5rem;">
                <h5 class="modal-title fw-bold text-white mb-0" id="dispatchModalLabel">
                    <i class="fas fa-paper-plane me-2"></i> Send to Department(s)
                </h5>
                <div class="ms-auto me-3" style="max-width: 280px; width: 100%;">
                    <input type="text" id="popupDeptSearch" class="form-control form-control-sm border-0 shadow-sm" placeholder="🔍 Search department..." style="border-radius: 20px; padding: 6px 15px;">
                </div>
                <button type="button" class="btn-close btn-close-white ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dispatchForm" method="POST" action="<?php echo URLROOT; ?>/complaints/dispatch/<?php echo $data['complaint']->id; ?>" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 mb-4 rounded-3 border" style="background-color: #f8f9fa;">
                        <div>
                            <p class="text-muted small mb-1">Complaint Number: <span class="fw-bold fs-6" style="color: var(--primary-color);"><?php echo htmlspecialchars($data['complaint']->complaint_no); ?></span></p>
                            <p class="text-muted small mb-0">Select one or more departments to forward this complaint letter to:</p>
                        </div>
                        <div class="bg-white p-2 rounded-3 border shadow-sm" style="min-width: 320px; max-width: 420px; flex: 1;">
                            <label for="dispatchAttachments" class="form-label fw-bold small text-primary mb-1 d-flex align-items-center">
                                <i class="fas fa-paperclip me-2"></i> Attach Files / Documents (Optional)
                            </label>
                            <input type="file" form="dispatchForm" name="attachments[]" id="dispatchAttachments" multiple class="form-control form-control-sm border" style="font-size:0.82rem; background:#ffffff;">
                            <div class="form-text mt-1" style="font-size:0.72rem; color:#6c757d;">You can select one or more files to attach.</div>
                        </div>
                    </div>

                    <?php
                    $offices = [];
                    $ministries = [];
                    $depts = [];

                    foreach($data['departments'] as $dept) {
                        if ($dept->type === 'office') {
                            $offices[] = $dept;
                        } elseif ($dept->type === 'ministries') {
                            $ministries[] = $dept;
                        } else {
                            $depts[] = $dept;
                        }
                    }
                    ?>

                    <div class="row g-3">
                        <!-- Offices Column -->
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--primary-color);"><i class="fas fa-building me-2"></i> Offices</h6>
                            <div class="d-flex flex-column gap-2" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                                <?php if (empty($offices)): ?>
                                    <span class="text-muted small">No offices defined</span>
                                <?php else: ?>
                                    <?php foreach($offices as $dept): ?>
                                        <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer; transition: all 0.2s;">
                                            <input class="form-check-input dept-checkbox mt-1" type="checkbox"
                                                   name="department_ids[]"
                                                   value="<?php echo $dept->id; ?>"
                                                   id="dept_<?php echo $dept->id; ?>"
                                                   style="flex-shrink: 0; margin-left: 0;">
                                            <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer; margin: 0; line-height: 1.4;">
                                                <span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ministries Column -->
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--primary-dark);"><i class="fas fa-landmark me-2"></i> Ministries</h6>
                            <div class="d-flex flex-column gap-2" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                                <?php if (empty($ministries)): ?>
                                    <span class="text-muted small">No ministries defined</span>
                                <?php else: ?>
                                    <?php foreach($ministries as $dept): ?>
                                        <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer; transition: all 0.2s;">
                                            <input class="form-check-input dept-checkbox mt-1" type="checkbox"
                                                   name="department_ids[]"
                                                   value="<?php echo $dept->id; ?>"
                                                   id="dept_<?php echo $dept->id; ?>"
                                                   style="flex-shrink: 0; margin-left: 0;">
                                            <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer; margin: 0; line-height: 1.4;">
                                                <span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Departments Column -->
                        <div class="col-md-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--accent-color);"><i class="fas fa-sitemap me-2"></i> Departments</h6>
                            <div class="d-flex flex-column gap-2" style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                                <?php if (empty($depts)): ?>
                                    <span class="text-muted small">No departments defined</span>
                                <?php else: ?>
                                    <?php foreach($depts as $dept): ?>
                                        <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer; transition: all 0.2s;">
                                            <input class="form-check-input dept-checkbox mt-1" type="checkbox"
                                                   name="department_ids[]"
                                                   value="<?php echo $dept->id; ?>"
                                                   id="dept_<?php echo $dept->id; ?>"
                                                   style="flex-shrink: 0; margin-left: 0;">
                                            <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer; margin: 0; line-height: 1.4;">
                                                <span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div id="deptError" class="text-danger small mt-3 d-none">
                        <i class="fas fa-exclamation-circle me-1"></i> Please select at least one department.
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold" onclick="submitDispatch()">
                        <i class="fas fa-paper-plane me-1"></i> Send Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.dept-check-item:has(.dept-checkbox:checked) {
    background: var(--primary-50) !important;
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 2px rgba(45,145,80,0.18);
}
.dept-check-item:hover { background: var(--primary-50) !important; border-color: var(--primary-300) !important; }
</style>

<script>
function openDispatchModal(complaintId, complaintNo) {
    document.querySelectorAll('.dept-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('deptError').classList.add('d-none');
    const att = document.getElementById('dispatchAttachments');
    if (att) att.value = '';
    
    // Clear search and reset visibility
    const searchInput = document.getElementById('popupDeptSearch');
    if (searchInput) {
        searchInput.value = '';
        const items = document.querySelectorAll('#dispatchModal .dept-check-item');
        items.forEach(item => {
            item.style.setProperty('display', 'flex', 'important');
        });
    }
    
    new bootstrap.Modal(document.getElementById('dispatchModal')).show();
}
function submitDispatch() {
    const checked = document.querySelectorAll('.dept-checkbox:checked');
    if (checked.length === 0) {
        document.getElementById('deptError').classList.remove('d-none');
        return;
    }
    document.getElementById('deptError').classList.add('d-none');
    document.getElementById('dispatchForm').submit();
}

function filterDepts() {
    const query = this.value.toLowerCase().trim();
    const items = document.querySelectorAll('#dispatchModal .dept-check-item');
    items.forEach(item => {
        const label = item.querySelector('.form-check-label');
        if (label) {
            const labelText = label.textContent.toLowerCase();
            if (labelText.includes(query)) {
                item.style.setProperty('display', 'flex', 'important');
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        }
    });
}

function initPopupSearch() {
    const searchInput = document.getElementById('popupDeptSearch');
    if (searchInput) {
        searchInput.removeEventListener('input', filterDepts);
        searchInput.addEventListener('input', filterDepts);
    }
}

// Run immediately and on DOMContentLoaded
initPopupSearch();
document.addEventListener('DOMContentLoaded', initPopupSearch);
</script>
<?php endif; ?>

<?php if ($data['complaint']->status == 'Approved by GS' || strpos(strtolower($data['complaint']->status), 'department') !== false): ?>
<!-- ════════════════════════════════════════════════
     Send via Email Modal
════════════════════════════════════════════════ -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">

            <!-- Header -->
            <div class="modal-header border-0 px-4 pt-4 pb-3"
                 style="background:linear-gradient(135deg, var(--primary-dark, #1b5c36), var(--primary-color, #2d9150));">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-0" id="emailModalLabel">
                        <i class="fas fa-envelope me-2"></i>Send via Email
                    </h5>
                    <p class="text-white mb-0" style="font-size:12px;opacity:0.9;margin-top:3px;">
                        Forward complaint details to an external organisation
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Complaint ref pill -->
            <div class="px-4 pt-3">
                <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                    <i class="fas fa-hashtag" style="color:var(--primary-color, #2d9150);font-size:13px;"></i>
                    <span style="font-size:13px;color:#166534;">
                        Complaint: <strong><?php echo htmlspecialchars($data['complaint']->complaint_no); ?></strong>
                    </span>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 pt-3 pb-2">

                <!-- Toast message area -->
                <div id="emailToast" class="d-none mb-3 rounded-3 p-3" style="font-size:13px;"></div>

                <div id="emailFormFields">
                    <!-- Org Name -->
                    <div class="mb-3">
                        <label for="emailOrgName" class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                            <i class="fas fa-building me-1" style="color:var(--primary-color, #2d9150);"></i> Organisation / Department Name
                            <span class="text-danger">*</span>
                        </label>
                        <div class="modern-input-wrapper" id="emailOrgNameWrapper">
                            <span class="modern-input-icon">
                                <i class="fas fa-building"></i>
                            </span>
                            <input type="text" id="emailOrgName" class="modern-input-field"
                                   placeholder="e.g. Ministry of Health">
                        </div>
                        <div id="emailOrgNameErr" class="text-danger mt-1 d-none" style="font-size:12px;">
                            <i class="fas fa-exclamation-circle me-1"></i>Organisation name is required.
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="emailOrgEmail" class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                            <i class="fas fa-at me-1" style="color:var(--primary-color, #2d9150);"></i> Email Address
                            <span class="text-danger">*</span>
                        </label>
                        <div class="modern-input-wrapper" id="emailOrgEmailWrapper">
                            <span class="modern-input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="emailOrgEmail" class="modern-input-field"
                                   placeholder="e.g. info@organisation.lk">
                        </div>
                        <div id="emailOrgEmailErr" class="text-danger mt-1 d-none" style="font-size:12px;">
                            <i class="fas fa-exclamation-circle me-1"></i>A valid email address is required.
                        </div>
                    </div>

                    <p style="font-size:11.5px;color:#9ca3af;margin-top:8px;">
                        <i class="fas fa-info-circle me-1"></i>
                        A formally formatted email containing all complaint details will be sent to the provided address.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 px-4 pb-4 pt-1">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal" style="font-size:14px;">Cancel</button>
                <button type="button" id="sendEmailBtn" class="btn rounded-pill px-4 fw-semibold text-white"
                        onclick="submitEmail()"
                        style="background:linear-gradient(135deg, var(--primary-color, #2d9150), var(--primary-dark, #1b5c36));border:none;font-size:14px;box-shadow:0 2px 8px rgba(45,145,80,0.3);">
                    <span id="sendEmailBtnText"><i class="fas fa-paper-plane me-1"></i> Send Email</span>
                    <span id="sendEmailBtnSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Sending...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

<style>
/* Modern Designer Input Borders for Email Modal */
.modern-input-wrapper {
    display: flex;
    align-items: stretch;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.modern-input-wrapper:hover {
    border-color: #cbd5e1;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
}

.modern-input-wrapper:focus-within {
    border-color: var(--primary-color, #2d9150) !important;
    background: #ffffff !important;
    box-shadow: 0 0 0 4px rgba(45, 145, 80, 0.16), 0 2px 8px rgba(45, 145, 80, 0.08) !important;
}

.modern-input-wrapper.is-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
}

.modern-input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 14px;
    background: #f8fafc;
    border-right: 1.5px solid #e2e8f0;
    color: #64748b;
    font-size: 14px;
    transition: all 0.25s ease;
    flex-shrink: 0;
}

.modern-input-wrapper:focus-within .modern-input-icon {
    background: #f0fdf4;
    border-right-color: rgba(45, 145, 80, 0.35);
    color: var(--primary-color, #2d9150);
}

.modern-input-wrapper.is-invalid .modern-input-icon {
    background: #fef2f2;
    border-right-color: rgba(239, 68, 68, 0.35);
    color: #ef4444;
}

.modern-input-field {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    padding: 12px 16px;
    font-size: 14.5px;
    font-weight: 500;
    color: #1e293b;
    width: 100%;
}

.modern-input-field::placeholder {
    color: #94a3b8;
    font-weight: 400;
}

.modern-input-field:focus {
    outline: none;
    box-shadow: none;
}
</style>

<script>
function openEmailModal() {
    // Reset
    document.getElementById('emailOrgName').value = '';
    document.getElementById('emailOrgEmail').value = '';
    ['emailOrgNameErr','emailOrgEmailErr'].forEach(id => document.getElementById(id).classList.add('d-none'));
    ['emailOrgNameWrapper','emailOrgEmailWrapper'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('is-invalid');
    });
    document.getElementById('emailToast').className = 'd-none mb-3 rounded-3 p-3';
    document.getElementById('emailToast').innerHTML = '';
    document.getElementById('emailFormFields').classList.remove('d-none');
    const sendBtn = document.getElementById('sendEmailBtn');
    sendBtn.disabled = false;
    sendBtn.classList.remove('d-none');
    document.getElementById('sendEmailBtnText').classList.remove('d-none');
    document.getElementById('sendEmailBtnSpinner').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('emailModal')).show();
}

function validateEmailForm() {
    let valid = true;
    const orgName   = document.getElementById('emailOrgName');
    const orgEmail  = document.getElementById('emailOrgEmail');
    const nameErr   = document.getElementById('emailOrgNameErr');
    const emailErr  = document.getElementById('emailOrgEmailErr');
    const nameWrap  = document.getElementById('emailOrgNameWrapper');
    const emailWrap = document.getElementById('emailOrgEmailWrapper');

    if (!orgName.value.trim()) {
        nameErr.classList.remove('d-none');
        if (nameWrap) nameWrap.classList.add('is-invalid');
        valid = false;
    } else {
        nameErr.classList.add('d-none');
        if (nameWrap) nameWrap.classList.remove('is-invalid');
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!orgEmail.value.trim() || !emailRegex.test(orgEmail.value.trim())) {
        emailErr.classList.remove('d-none');
        if (emailWrap) emailWrap.classList.add('is-invalid');
        valid = false;
    } else {
        emailErr.classList.add('d-none');
        if (emailWrap) emailWrap.classList.remove('is-invalid');
    }
    return valid;
}

function showEmailToast(msg, success) {
    const toast = document.getElementById('emailToast');
    if (success) {
        toast.style.cssText = 'background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;';
        toast.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + msg;
    } else {
        toast.style.cssText = 'background:#fef2f2;border:1px solid #fecaca;color:#991b1b;';
        toast.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + msg;
    }
    toast.className = 'mb-3 rounded-3 p-3';
}

function submitEmail() {
    if (!validateEmailForm()) return;

    const btn = document.getElementById('sendEmailBtn');
    document.getElementById('sendEmailBtnText').classList.add('d-none');
    document.getElementById('sendEmailBtnSpinner').classList.remove('d-none');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('org_name',  document.getElementById('emailOrgName').value.trim());
    formData.append('org_email', document.getElementById('emailOrgEmail').value.trim());

    fetch('<?php echo URLROOT; ?>/complaints/sendEmail/<?php echo $data['complaint']->id; ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('sendEmailBtnText').classList.remove('d-none');
        document.getElementById('sendEmailBtnSpinner').classList.add('d-none');
        btn.disabled = false;

        showEmailToast(data.message, data.success);

        if (data.success) {
            // Hide form fields after success; keep modal open to show success message
            document.getElementById('emailFormFields').classList.add('d-none');
            btn.classList.add('d-none');
        }
    })
    .catch(err => {
        document.getElementById('sendEmailBtnText').classList.remove('d-none');
        document.getElementById('sendEmailBtnSpinner').classList.add('d-none');
        btn.disabled = false;
        showEmailToast('Network error. Please try again.', false);
    });
}

// Live validation on blur
document.addEventListener('DOMContentLoaded', function() {
    const orgNameInput = document.getElementById('emailOrgName');
    const orgEmailInput = document.getElementById('emailOrgEmail');
    if (orgNameInput) orgNameInput.addEventListener('blur', validateEmailForm);
    if (orgEmailInput) orgEmailInput.addEventListener('blur', validateEmailForm);
});
</script>
<?php endif; ?>

<!-- View / Update Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0" id="replyModalLabel">
                    <i class="fas fa-reply"></i>
                    <span>Department Reply — <span id="replyModalComplaintNo" class="text-warning"></span></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyUpdateForm" method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <!-- Reply Meta Info Card -->
                    <div class="p-3 mb-3 rounded-3 border bg-light d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Replying Department</small>
                            <span id="replyDeptName" class="fw-bold text-dark fs-6">Loading...</span>
                        </div>
                        <div class="text-sm-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Received Date & Time</small>
                            <span id="replyDate" class="text-muted small fw-semibold"></span>
                            <span id="replyReplier" class="text-muted small"></span>
                        </div>
                    </div>

                    <!-- Current Attachment Display -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Attached Document / File</label>
                        <div id="modalCurrentAttachment">
                            <span class="text-muted small">Loading attachment...</span>
                        </div>
                    </div>

                    <!-- Reply Text -->
                    <div class="mb-3">
                        <label for="modalReplyText" class="form-label fw-bold text-secondary small text-uppercase mb-1">
                            Reply Message / Remarks <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control rounded-3 border" id="modalReplyText" name="reply_text" rows="5" placeholder="Enter or update department reply remarks..." required style="resize:vertical;"></textarea>
                    </div>

                    <!-- Upload New / Update Attachment -->
                    <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                        <label for="modalReplyAttachment" class="form-label fw-bold small text-primary mb-1 d-flex align-items-center">
                            <i class="fas fa-paperclip me-2"></i> Update or Attach New File (Optional)
                        </label>
                        <input type="file" class="form-control form-control-sm border" id="modalReplyAttachment" name="attachment" style="background:#fff;">
                        <div class="form-text small text-muted mt-1">Upload a PDF, document, or image to replace or add attachment to this reply.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="fas fa-save me-1"></i> Save & Update Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReplyModal(complaintId, complaintNo) {
    document.getElementById('replyModalComplaintNo').textContent = complaintNo;
    const form = document.getElementById('replyUpdateForm');
    form.action = '<?php echo URLROOT; ?>/complaints/updateReply/' + complaintId;

    // Reset fields to loading state
    document.getElementById('modalReplyText').value = 'Loading...';
    document.getElementById('replyDeptName').textContent = 'Loading...';
    document.getElementById('replyDate').textContent = '';
    document.getElementById('replyReplier').textContent = '';
    document.getElementById('modalCurrentAttachment').innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2"></div> Loading...';
    const fileInput = document.getElementById('modalReplyAttachment');
    if (fileInput) fileInput.value = '';

    const modal = new bootstrap.Modal(document.getElementById('replyModal'));
    modal.show();

    fetch('<?php echo URLROOT; ?>/complaints/getReply/' + complaintId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.reply) {
                const r = data.reply;
                document.getElementById('modalReplyText').value = r.reply_text || '';
                document.getElementById('replyDeptName').textContent = r.department_name || 'N/A';
                document.getElementById('replyDate').textContent = r.created_at ? new Date(r.created_at).toLocaleString() : '';
                document.getElementById('replyReplier').textContent = r.replier_name ? ' (' + r.replier_name + ')' : '';

                if (r.attachment_path) {
                    const dlUrl = '<?php echo URLROOT; ?>/' + r.attachment_path;
                    document.getElementById('modalCurrentAttachment').innerHTML = `
                        <div class="alert alert-light border d-flex align-items-center justify-content-between p-2 mb-0 rounded-3">
                            <div class="d-flex align-items-center text-truncate me-2">
                                <i class="fas fa-paperclip text-primary me-2 fs-5"></i>
                                <span class="fw-semibold text-truncate small">${r.attachment_name || 'Attachment'}</span>
                            </div>
                            <a href="${dlUrl}" target="_blank" download class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-shrink-0">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    `;
                } else {
                    document.getElementById('modalCurrentAttachment').innerHTML = '<span class="text-muted small">No attachment uploaded yet.</span>';
                }
            } else {
                document.getElementById('modalReplyText').value = '';
                document.getElementById('replyDeptName').textContent = 'Not specified';
                document.getElementById('modalCurrentAttachment').innerHTML = '<span class="text-muted small">No reply details recorded yet.</span>';
            }
        })
        .catch(err => {
            console.error('Error fetching reply:', err);
            document.getElementById('modalReplyText').value = '';
            document.getElementById('modalCurrentAttachment').innerHTML = '<span class="text-danger small">Failed to load reply data.</span>';
        });
}
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
