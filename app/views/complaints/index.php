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
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="fw-bold" style="color: var(--primary-color);"><i class="fas fa-list me-2"></i> All Complaints</h2>
        <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4">
            <i class="fas fa-plus me-2"></i> Add New Complaint
        </a>
    </div>

    <?php flash('complaint_message'); ?>

    <!-- Unified Status Summary Dropdown & Filter Bar -->
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end align-items-center flex-wrap gap-2">
            <!-- Status Summary Dropdown -->
            <?php if(!empty($data['status_summary'])): ?>
            <div class="dropdown">
                <button class="btn btn-white border shadow-sm rounded-pill px-3 py-2 fw-semibold dropdown-toggle d-flex align-items-center gap-2" type="button" id="statusSummaryDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background:#fff;">
                    <i class="fas fa-chart-pie text-primary"></i>
                    <span>Complaints Status Summary: <strong class="text-primary"><?php echo !empty($data['status_filter']) ? htmlspecialchars($data['status_filter']) : 'All'; ?></strong></span>
                    <span class="badge bg-primary rounded-pill"><?php 
                        $sf = $data['status_filter'] ?? '';
                        if ($sf === 'Draft') echo $data['status_summary']['draft'];
                        elseif ($sf === 'Pending CC') echo $data['status_summary']['pending_cc'];
                        elseif ($sf === 'Approved by CC (Pending AO)') echo $data['status_summary']['approved_cc'];
                        elseif ($sf === 'Approved by AO (Pending GS)') echo $data['status_summary']['approved_ao'];
                        elseif ($sf === 'Approved by GS') echo $data['status_summary']['approved_gs'];
                        elseif ($sf === 'Rejected') echo $data['status_summary']['rejected_total'];
                        elseif ($sf === 'Approved') echo $data['status_summary']['approved_total'];
                        else echo $data['status_summary']['total'];
                    ?></span>
                </button>
                <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2" aria-labelledby="statusSummaryDropdown" style="min-width: 290px; z-index: 1060;">
                    <li><h6 class="dropdown-header text-uppercase small fw-bold text-muted">Complaints Status Summary</h6></li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo empty($data['status_filter']) ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-list-ul me-2 <?php echo empty($data['status_filter']) ? 'text-white' : 'text-secondary'; ?>"></i> All Complaints</span>
                            <span class="badge <?php echo empty($data['status_filter']) ? 'bg-white text-primary' : 'bg-secondary'; ?> rounded-pill"><?php echo $data['status_summary']['total']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo (($data['status_filter'] ?? '') === 'Draft') ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?status=Draft&month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-file-alt me-2 text-muted"></i> Draft</span>
                            <span class="badge bg-secondary rounded-pill"><?php echo $data['status_summary']['draft']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo (($data['status_filter'] ?? '') === 'Pending CC') ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?status=Pending CC&month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-clock me-2 text-warning"></i> Pending CC</span>
                            <span class="badge bg-warning text-dark rounded-pill"><?php echo $data['status_summary']['pending_cc']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo (($data['status_filter'] ?? '') === 'Approved by CC (Pending AO)') ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?status=<?php echo urlencode('Approved by CC (Pending AO)'); ?>&month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-check me-2 text-info"></i> Approved by CC</span>
                            <span class="badge bg-info text-white rounded-pill"><?php echo $data['status_summary']['approved_cc']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo (($data['status_filter'] ?? '') === 'Approved by AO (Pending GS)') ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?status=<?php echo urlencode('Approved by AO (Pending GS)'); ?>&month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-user-check me-2 text-primary"></i> Approved by AO</span>
                            <span class="badge bg-primary text-white rounded-pill"><?php echo $data['status_summary']['approved_ao']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo (($data['status_filter'] ?? '') === 'Approved by GS') ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?status=<?php echo urlencode('Approved by GS'); ?>&month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-check-circle me-2 text-success"></i> Approved by GS</span>
                            <span class="badge bg-success text-white rounded-pill"><?php echo $data['status_summary']['approved_gs']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo (($data['status_filter'] ?? '') === 'Rejected') ? 'active bg-primary text-white' : ''; ?>" href="<?php echo URLROOT; ?>/complaints?status=Rejected&month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id']); ?>">
                            <span><i class="fas fa-times-circle me-2 text-danger"></i> Rejected</span>
                            <span class="badge bg-danger text-white rounded-pill"><?php echo $data['status_summary']['rejected_total']; ?></span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2" href="<?php echo URLROOT; ?>/complaints/sent">
                            <span><i class="fas fa-paper-plane me-2 text-dark"></i> Sent to Departments</span>
                            <span class="badge bg-dark text-white rounded-pill"><?php echo $data['status_summary']['dispatched']; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <!-- Filter Bar -->
            <form method="GET" action="" class="d-flex flex-wrap align-items-center bg-white py-2 px-3 rounded-4 shadow-sm border" style="gap: 12px; border-color: rgba(0,0,0,0.06) !important;">
                <input type="hidden" name="status" value="<?php echo htmlspecialchars($data['status_filter'] ?? ''); ?>">
                <div class="input-group input-group-sm" style="width: auto; min-width: 170px;">
                    <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-1">
                        <i class="fas fa-filter"></i>
                    </span>
                    <select name="category_id" id="categoryFilter" class="form-select border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer;" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php if(!empty($data['categories'])): ?>
                            <?php foreach($data['categories'] as $category): ?>
                                <option value="<?php echo $category->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="vr bg-secondary opacity-25 d-none d-md-block" style="width: 1px; height: 26px;"></div>
                
                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-1" title="Filter by date">
                        <i class="fas fa-calendar-day"></i> <span class="d-none d-xl-inline ms-1 small">Date:</span>
                    </span>
                    <input type="date" name="date" id="dateFilter" class="form-control border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer;" value="<?php echo htmlspecialchars($data['date'] ?? ''); ?>" onchange="if(this.value) { const m = document.getElementById('monthFilter'); if(m) m.value = ''; } this.form.submit()">
                </div>

                <div class="input-group input-group-sm" style="width: auto;">
                    <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-1" title="Filter by month">
                        <i class="fas fa-calendar-alt"></i> <span class="d-none d-xl-inline ms-1 small">Month:</span>
                    </span>
                    <input type="month" name="month" id="monthFilter" class="form-control border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer;" value="<?php echo htmlspecialchars($data['month'] ?? ''); ?>" onchange="if(this.value) { const d = document.getElementById('dateFilter'); if(d) d.value = ''; } this.form.submit()">
                </div>

                <?php if(!empty($data['date']) || !empty($data['month']) || !empty($data['category_id']) || !empty($data['status_filter'])): ?>
                    <a href="<?php echo URLROOT; ?>/complaints" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Clear filters">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card shadow border-0 rounded-4 mb-5">
        <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Complaints Registry</h5>
            <div class="input-group" style="max-width: 300px;">
                <input type="text" class="form-control" placeholder="Search complaints..." id="searchTable">
                <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
            </div>
        </div>
        
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="complaintsTable">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-3 rounded-start">Reference No</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Applicant Name</th>
                            <th class="py-3">Subject</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end rounded-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if(empty($data['complaints'])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                    <h5>No complaints found</h5>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['complaints'] as $complaint) : ?>
                                <tr>
                                    <td class="py-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                    <td class="py-3"><?php echo htmlspecialchars($complaint->date); ?></td>
                                    <td class="py-3 fw-semibold"><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                    <td class="py-3 text-muted"><?php echo htmlspecialchars($complaint->subject); ?></td>
                                    <td class="py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1">
                                            <?php echo htmlspecialchars($complaint->category_name); ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <?php 
                                            $status = $complaint->status;
                                            if (strpos($status, 'Rejected') !== false) {
                                                echo '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i> ' . htmlspecialchars($status) . '</span>';
                                            } elseif (strpos($status, 'Approved') !== false) {
                                                echo '<span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> ' . htmlspecialchars($status) . '</span>';
                                            } else {
                                                echo '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> ' . htmlspecialchars($status) . '</span>';
                                            }
                                        ?>
                                    </td>
                                    <td class="py-3 text-end">
                                        <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-light text-primary rounded-pill px-3 shadow-sm" title="View Details">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchTable');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            let filter = searchInput.value.toLowerCase();
            let rows = document.querySelectorAll('#complaintsTable tbody tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if(text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
    </main>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
