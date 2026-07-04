<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Welcome, <?php echo $_SESSION['user_name']; ?></h2>
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
                <a class="text-white stretched-link" href="#">View Details</a>
                <div class="text-white"><i class="ri-arrow-right-s-line"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow border-0 mb-4 rounded-4">
            <div class="card-header bg-gradient bg-primary text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                <h5 class="mb-0 fw-bold"><i class="fas fa-file-alt me-2"></i> My Submitted Complaints</h5>
                <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-light btn-sm text-primary fw-bold rounded-pill shadow-sm px-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($data['user_complaints'])): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-4x mb-3 text-secondary opacity-50"></i>
                        <h4>No Complaints Yet</h4>
                        <p>You haven't submitted any complaints. Click "Submit Complaint" to get started.</p>
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
                                        <td class="ps-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span></td>
                                        <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                        <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                        <td><span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2"><i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($complaint->category_name); ?></span></td>
                                        <td>
                                            <?php if ($complaint->status == 'Rejected by CC'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                            <?php elseif (strpos($complaint->status, 'Approved') !== false): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($complaint->status == 'Rejected by CC'): ?>
                                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-exclamation-circle me-1"></i> Fix Rejection
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                    <i class="fas fa-search me-1"></i> View
                                                </a>
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
</div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
