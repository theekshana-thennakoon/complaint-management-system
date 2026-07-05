<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Management</div>
        <a href="<?php echo URLROOT; ?>/complaints" class="sidebar-menu-item">
            <i class="fas fa-list"></i> All Complaints
        </a>
        <a href="<?php echo URLROOT; ?>/complaints/create" class="sidebar-menu-item">
            <i class="fas fa-plus"></i> New Complaint
        </a>
        <a href="<?php echo URLROOT; ?>/complaints/sent" class="sidebar-menu-item active">
            <i class="fas fa-paper-plane"></i> Sent to Departments
        </a>
    </aside>

    <main class="dashboard-content">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="fw-bold" style="color: var(--primary-color);"><i class="fas fa-paper-plane me-2"></i> Sent to Departments</h2>
        <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-secondary btn-lg rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow border-0 rounded-4 mb-5">
        <div class="card-header bg-white py-3 border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Dispatched Complaints</h5>
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
                                    <h5>No dispatched complaints found</h5>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['complaints'] as $complaint) : ?>
                                <tr>
                                    <td class="py-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span></td>
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
                                        <?php 
                                            $deptNames = [];
                                            if (isset($complaint->dispatched_departments) && !empty($complaint->dispatched_departments)) {
                                                foreach ($complaint->dispatched_departments as $d) {
                                                    $deptNames[] = $d->name;
                                                }
                                            }
                                        ?>
                                        <button class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm ms-1" title="View Departments" onclick='showDeptsModal(<?php echo json_encode($deptNames); ?>, "<?php echo htmlspecialchars($complaint->complaint_no, ENT_QUOTES); ?>")'>
                                            <i class="fas fa-building me-1"></i> Depts
                                        </button>
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

<!-- Departments Modal -->
<div class="modal fade" id="deptsModal" tabindex="-1" aria-labelledby="deptsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header rounded-top-4" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
                <h5 class="modal-title fw-bold text-dark" id="deptsModalLabel">
                    <i class="fas fa-building me-2"></i> Dispatched Departments
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3 small">Complaint: <span id="deptsComplaintNo" class="fw-bold text-primary"></span></p>
                <ul id="deptsList" class="list-group list-group-flush border rounded">
                </ul>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDeptsModal(departments, complaintNo) {
    document.getElementById('deptsComplaintNo').textContent = complaintNo;
    const list = document.getElementById('deptsList');
    list.innerHTML = '';
    
    if (departments && departments.length > 0) {
        departments.forEach(function(dept) {
            const li = document.createElement('li');
            li.className = 'list-group-item fw-semibold py-3';
            li.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>' + dept;
            list.appendChild(li);
        });
    } else {
        list.innerHTML = '<li class="list-group-item text-muted py-3">No departments found.</li>';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('deptsModal'));
    modal.show();
}

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
