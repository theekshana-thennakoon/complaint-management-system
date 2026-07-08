<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Management</div>
        <a href="<?php echo URLROOT; ?>/complaints" class="sidebar-menu-item active">
            <i class="fas fa-list"></i> All Complaints
        </a>
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
                if($data['complaint']->status == 'Approved') $badge_class = 'status-resolved';
                if($data['complaint']->status == 'Rejected') $badge_class = 'status-rejected';
                if($data['complaint']->status == 'Sent to CC') $badge_class = 'status-info-requested';
            ?>
            <div>
                <strong>Status:</strong> <span class="badge <?php echo $badge_class; ?>"><?php echo $data['complaint']->status; ?></span>
            </div>
        </div>

        <?php if($data['complaint']->status == 'Rejected by CC' && !empty($data['reject_log'])): ?>
            <div class="alert alert-danger shadow-sm border-danger border-start border-5 mb-4 rounded d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="alert-heading text-danger fw-bold mb-1"><i class="fas fa-exclamation-triangle"></i> Rejected by Chief Clerk</h5>
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
                    <?php if ($data['complaint']->status == 'Approved by GS'): ?>
                    <button type="button" class="btn btn-warning btn-sm fw-semibold"
                        onclick="openDispatchModal(<?php echo $data['complaint']->id; ?>, '<?php echo htmlspecialchars($data['complaint']->complaint_no, ENT_QUOTES); ?>')"
                        style="background: linear-gradient(135deg,#f7971e,#ffd200); border:none; color:#333;">
                        <i class="fas fa-paper-plane me-1"></i> Send to Department
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
                <div style="border: 1px solid var(--panel-border); background: #fff; border-radius: 8px; overflow: hidden;">
                    <?php
                    ob_start();
                    require APPROOT . '/views/complaints/pdf_template.php';
                    $letter_html = ob_get_clean();
                    ?>
                    <iframe srcdoc="<?php echo htmlspecialchars($letter_html, ENT_QUOTES, 'UTF-8'); ?>" width="100%" scrolling="no" onload="this.style.height = this.contentWindow.document.documentElement.scrollHeight + 'px';" style="border: none; display: block; overflow: hidden; min-height: 800px;"></iframe>
                </div>
            </div>
        </div>


    </main>
</div>

<?php if ($data['complaint']->status == 'Approved by GS'): ?>
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
            <form id="dispatchForm" method="POST" action="<?php echo URLROOT; ?>/complaints/dispatch/<?php echo $data['complaint']->id; ?>">
                <div class="modal-body p-4">
                    <p class="text-muted small mb-1">Complaint: <span class="fw-bold" style="color: var(--primary-color);"><?php echo htmlspecialchars($data['complaint']->complaint_no); ?></span></p>
                    <p class="text-muted small mb-3">Select one or more departments to forward this complaint letter to:</p>

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

<?php require APPROOT . '/views/layout/footer.php'; ?>
