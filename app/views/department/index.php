<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-md-12">
            <?php flash('complaint_success'); ?>
            <?php flash('auth_error'); ?>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-building fa-2x me-3 text-primary"></i>
                <div>
                    <h4 class="mb-0 fw-bold text-primary">Department Letters</h4>
                    <small class="text-muted">Letters dispatched to your department</small>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small opacity-75">Total Letters Received</div>
                                    <div class="fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['total']; ?></div>
                                </div>
                                <i class="fas fa-envelope-open-text fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small opacity-75">This Month</div>
                                    <div class="fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['this_month']; ?></div>
                                </div>
                                <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Letters Table -->
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient bg-primary text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list-alt me-2"></i>Letters Dispatched to Your Department</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($data['dispatched'])): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-4x mb-3 opacity-50 text-primary"></i>
                            <h4>No Letters Yet</h4>
                            <p>No complaint letters have been dispatched to your department yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive p-2">
                            <table class="table table-hover align-middle border-bottom">
                                <thead class="table-light text-uppercase text-secondary" style="font-size: 0.85rem;">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Complaint No</th>
                                        <th>Received On</th>
                                        <th>Applicant</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($data['dispatched'] as $complaint): ?>
                                        <tr style="transition: all 0.2s ease;">
                                            <td class="ps-3 text-muted small"><?php echo $i++; ?></td>
                                            <td><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span></td>
                                            <td><?php echo date('Y-m-d', strtotime($complaint->dispatched_at)); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1">
                                                    <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($complaint->category_name); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?php echo URLROOT; ?>/department/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                        <i class="fas fa-eye me-1"></i> View Letter
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                                    </a>
                                                </div>
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

<?php require APPROOT . '/views/layout/footer.php'; ?>
