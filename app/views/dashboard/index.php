<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Welcome, <?php echo $_SESSION['user_name']; ?></h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Pending Letters</div>
                        <div class="text-lg fw-bold"><?php echo $data['stats']['pending']; ?></div>
                    </div>
                    <i class="ri-file-list-3-line fa-2x text-white-50" style="font-size: 2rem;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="<?php echo URLROOT; ?>/complaints">View Details</a>
                <div class="text-white"><i class="ri-arrow-right-s-line"></i></div>
            </div>
        </div>
    </div>
    <div class="col mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Approved Letters</div>
                        <div class="text-lg fw-bold"><?php echo $data['stats']['approved']; ?></div>
                    </div>
                    <i class="ri-check-double-line fa-2x text-white-50" style="font-size: 2rem;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="#">View Details</a>
                <div class="text-white"><i class="ri-arrow-right-s-line"></i></div>
            </div>
        </div>
    </div>
    <div class="col mb-4">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Rejected Letters</div>
                        <div class="text-lg fw-bold"><?php echo $data['stats']['rejected']; ?></div>
                    </div>
                    <i class="ri-close-circle-line fa-2x text-white-50" style="font-size: 2rem;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="#">View Details</a>
                <div class="text-white"><i class="ri-arrow-right-s-line"></i></div>
            </div>
        </div>
    </div>
    <div class="col mb-4">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Sent to Departments</div>
                        <div class="text-lg fw-bold"><?php echo $data['stats']['sent']; ?></div>
                    </div>
                    <i class="ri-send-plane-fill fa-2x text-white-50" style="font-size: 2rem;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="<?php echo URLROOT; ?>/complaints/sent">View Details</a>
                <div class="text-white"><i class="ri-arrow-right-s-line"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-end">
        <form method="GET" action="" class="d-flex flex-wrap align-items-center bg-white py-2 px-3 rounded-pill shadow-sm border" style="gap: 15px; border-color: rgba(0,0,0,0.05) !important;">
            <div class="input-group input-group-sm" style="width: auto; flex: 1; min-width: 200px;">
                <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-2">
                    <i class="fas fa-filter"></i>
                </span>
                <select name="category_id" id="categoryFilter" class="form-select border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach($data['categories'] as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="vr bg-secondary opacity-25 d-none d-md-block" style="width: 2px; border-radius: 2px; margin-top: 4px; margin-bottom: 4px;"></div>
            
            <div class="input-group input-group-sm" style="width: auto;">
                <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-2">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                <input type="month" name="month" id="monthFilter" class="form-control border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);" value="<?php echo isset($data['month']) ? $data['month'] : date('Y-m'); ?>" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <?php flash('complaint_success'); ?>
        <?php flash('complaint_error'); ?>
        
        <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-primary" id="my-tab" data-bs-toggle="tab" data-bs-target="#my-complaints" type="button" role="tab"><i class="fas fa-file-alt me-1"></i> My Submitted Complaints</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-success" id="external-tab" data-bs-toggle="tab" data-bs-target="#external-complaints" type="button" role="tab"><i class="fas fa-external-link-alt me-1"></i> External Complaints</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-info" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-complaints" type="button" role="tab"><i class="fas fa-list me-1"></i> All System Complaints</button>
            </li>
        </ul>

        <div class="tab-content" id="dashboardTabsContent">
            <!-- My Complaints Tab -->
            <div class="tab-pane fade show active" id="my-complaints" role="tabpanel" aria-labelledby="my-tab">
                <div class="card shadow border-0 mb-4 rounded-4">
                    <div class="card-header bg-gradient bg-primary text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                        <h5 class="mb-0 fw-bold">My Submitted Complaints</h5>
                        <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-light btn-sm text-primary fw-bold rounded-pill shadow-sm px-3">
                            <i class="fas fa-plus me-1"></i> Add New
                        </a>
                    </div>
            <div class="card-body">
                <?php if (empty($data['user_complaints'])): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-4x mb-3 text-secondary opacity-50"></i>
                        <h4>No Complaints Yet</h4>
                        <p>You haven't submitted any complaints. Click "Add New" to get started.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive p-2">
                        <table class="table table-hover align-middle border-bottom">
                            <thead class="table-light text-uppercase text-secondary" style="font-size: 0.85rem;">
                                <tr>
                                    <th class="ps-3">Complaint No</th>
                                    <th>Date</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['user_complaints'] as $complaint): ?>
                                    <tr style="transition: all 0.2s ease;">
                                        <td class="ps-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                        <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                        <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2"><i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($complaint->category_name); ?></span>
                                            <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($complaint->status == 'Rejected by CC'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                            <?php elseif (strpos($complaint->status, 'Approved') !== false): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-flex gap-2 flex-wrap">
                                            <?php if ($complaint->status == 'Rejected by CC'): ?>
                                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-exclamation-circle me-1"></i> Fix Rejection
                                                </a>
                                            <?php elseif ($complaint->status == 'Draft'): ?>
                                                <a href="<?php echo URLROOT; ?>/complaints/edit/<?php echo $complaint->id; ?>" class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-search me-1"></i> View
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($complaint->status == 'Approved by GS'): ?>
                                                <!-- <button type="button"
                                                    class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm fw-semibold"
                                                    onclick="openDispatchModal(<?php echo $complaint->id; ?>, '<?php echo htmlspecialchars($complaint->complaint_no, ENT_QUOTES); ?>')">
                                                    <i class="fas fa-paper-plane me-1"></i> Send to Department
                                                </button> -->
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
                </div>
            </div>

            <!-- External Complaints Tab -->
            <div class="tab-pane fade" id="external-complaints" role="tabpanel" aria-labelledby="external-tab">
                <div class="card shadow border-0 rounded-4 mb-4">
                    <div class="card-header bg-gradient bg-success text-white py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold">External Complaints</h5>
                    </div>
            <div class="card-body">
                <?php if (empty($data['external_complaints'])): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-4x mb-3 opacity-50"></i>
                        <h4>No External Complaints Found</h4>
                    </div>
                <?php else: ?>
                    <div class="table-responsive p-2">
                        <table class="table table-hover align-middle border-bottom">
                            <thead class="table-light text-uppercase text-secondary" style="font-size: 0.85rem;">
                                <tr>
                                    <th class="ps-3">Complaint No</th>
                                    <th>Date</th>
                                    <th>Applicant</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Current Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['external_complaints'] as $complaint): ?>
                                    <tr style="transition: all 0.2s ease;">
                                        <td class="ps-3"><span class="fw-bold text-success"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                        <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                        <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                        <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1"><?php echo htmlspecialchars($complaint->category_name); ?></span>
                                            <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-1"><?php echo htmlspecialchars($complaint->status); ?></span></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
                </div>
            </div>

            <!-- All Complaints Tab -->
            <div class="tab-pane fade" id="all-complaints" role="tabpanel" aria-labelledby="all-tab">
                <div class="card shadow border-0 rounded-4 mb-4">
                    <div class="card-header bg-gradient bg-info text-white py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold">All System Complaints</h5>
                    </div>
            <div class="card-body">
                <?php if (empty($data['all_complaints'])): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-4x mb-3 opacity-50"></i>
                        <h4>No Complaints Found</h4>
                    </div>
                <?php else: ?>
                    <div class="table-responsive p-2">
                        <table class="table table-hover align-middle border-bottom">
                            <thead class="table-light text-uppercase text-secondary" style="font-size: 0.85rem;">
                                <tr>
                                    <th class="ps-3">Complaint No</th>
                                    <th>Date</th>
                                    <th>Applicant</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Current Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['all_complaints'] as $complaint): ?>
                                    <tr style="transition: all 0.2s ease;">
                                        <td class="ps-3"><span class="fw-bold text-primary"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                        <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                        <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                        <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1"><?php echo htmlspecialchars($complaint->category_name); ?></span>
                                            <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-1"><?php echo htmlspecialchars($complaint->status); ?></span></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            <form id="dispatchForm" method="POST" action="">
                <div class="modal-body p-4">
                    <p class="text-muted mb-1 small">Complaint: <span id="modalComplaintNo" class="fw-bold" style="color: var(--primary-color);"></span></p>
                    <p class="text-muted small mb-3">Select one or more departments to forward this complaint letter to:</p>

                    <?php
                    $offices = [];
                    $ministries = [];
                    $depts = [];
                    foreach($data['departments'] as $dept) {
                        if ($dept->type === 'office') $offices[] = $dept;
                        elseif ($dept->type === 'ministries') $ministries[] = $dept;
                        else $depts[] = $dept;
                    }
                    ?>

                    <div class="row g-3">
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--primary-color);"><i class="fas fa-building me-2"></i> Offices</h6>
                            <div class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto; padding-right: 6px;">
                                <?php if (empty($offices)): ?>
                                    <span class="text-muted small">No offices defined</span>
                                <?php else: foreach($offices as $dept): ?>
                                    <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer; transition: all 0.2s;">
                                        <input class="form-check-input dept-checkbox mt-1" type="checkbox" name="department_ids[]" value="<?php echo $dept->id; ?>" id="dept_<?php echo $dept->id; ?>" style="flex-shrink:0; margin-left:0;">
                                        <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer; margin:0; line-height:1.4;">
                                            <span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--primary-dark);"><i class="fas fa-landmark me-2"></i> Ministries</h6>
                            <div class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto; padding-right: 6px;">
                                <?php if (empty($ministries)): ?>
                                    <span class="text-muted small">No ministries defined</span>
                                <?php else: foreach($ministries as $dept): ?>
                                    <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer; transition: all 0.2s;">
                                        <input class="form-check-input dept-checkbox mt-1" type="checkbox" name="department_ids[]" value="<?php echo $dept->id; ?>" id="dept_<?php echo $dept->id; ?>" style="flex-shrink:0; margin-left:0;">
                                        <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer; margin:0; line-height:1.4;">
                                            <span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom" style="color: var(--accent-color);"><i class="fas fa-sitemap me-2"></i> Departments</h6>
                            <div class="d-flex flex-column gap-2" style="max-height: 380px; overflow-y: auto; padding-right: 6px;">
                                <?php if (empty($depts)): ?>
                                    <span class="text-muted small">No departments defined</span>
                                <?php else: foreach($depts as $dept): ?>
                                    <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer; transition: all 0.2s;">
                                        <input class="form-check-input dept-checkbox mt-1" type="checkbox" name="department_ids[]" value="<?php echo $dept->id; ?>" id="dept_<?php echo $dept->id; ?>" style="flex-shrink:0; margin-left:0;">
                                        <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer; margin:0; line-height:1.4;">
                                            <span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; endif; ?>
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
.dept-check-item:hover {
    background: var(--primary-50) !important;
    border-color: var(--primary-300) !important;
}
</style>

<script>
function openDispatchModal(complaintId, complaintNo) {
    document.getElementById('modalComplaintNo').textContent = complaintNo;
    document.getElementById('dispatchForm').action = '<?php echo URLROOT; ?>/complaints/dispatch/' + complaintId;
    document.querySelectorAll('.dept-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('deptError').classList.add('d-none');
    const s = document.getElementById('popupDeptSearch');
    if (s) { s.value = ''; document.querySelectorAll('#dispatchModal .dept-check-item').forEach(i => i.style.setProperty('display','flex','important')); }
    new bootstrap.Modal(document.getElementById('dispatchModal')).show();
}

function submitDispatch() {
    const checked = document.querySelectorAll('.dept-checkbox:checked');
    if (checked.length === 0) { document.getElementById('deptError').classList.remove('d-none'); return; }
    document.getElementById('deptError').classList.add('d-none');
    document.getElementById('dispatchForm').submit();
}

function filterDepts() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#dispatchModal .dept-check-item').forEach(item => {
        const label = item.querySelector('.form-check-label');
        if (label) item.style.setProperty('display', label.textContent.toLowerCase().includes(q) ? 'flex' : 'none', 'important');
    });
}

(function bindSearch() {
    const s = document.getElementById('popupDeptSearch');
    if (s) { s.removeEventListener('input', filterDepts); s.addEventListener('input', filterDepts); }
    else document.addEventListener('DOMContentLoaded', bindSearch);
})();
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
