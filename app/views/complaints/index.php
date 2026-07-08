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
