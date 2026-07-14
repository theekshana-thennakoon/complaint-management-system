<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-md-12">
            <?php flash('complaint_success'); ?>
            <?php flash('complaint_error'); ?>
            <?php flash('auth_error'); ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <h2 class="mb-0">Government Secretary Dashboard</h2>
            </div>
            
            <div class="row mb-4">
                <div class="col">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Pending Approval</div>
                                    <div class="text-lg fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['pending']; ?></div>
                                </div>
                                <i class="fas fa-inbox fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Approved by You</div>
                                    <div class="text-lg fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['approved']; ?></div>
                                </div>
                                <i class="fas fa-check-double fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="me-3">
                                    <div class="text-white-75 small">Rejected by You</div>
                                    <div class="text-lg fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['rejected']; ?></div>
                                </div>
                                <i class="fas fa-times-circle fa-2x text-white-50"></i>
                            </div>
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

            <ul class="nav nav-tabs mb-4" id="gsDashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-primary" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab"><i class="fas fa-list-alt me-1"></i> Pending Review</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-success" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab"><i class="fas fa-check-double me-1"></i> Forwarded to Subject and Governor</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-danger" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab"><i class="fas fa-times-circle me-1"></i> Rejected Reports</button>
                </li>
            </ul>

            <div class="tab-content" id="gsDashboardTabsContent">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="card shadow border-0 mb-4 rounded-4">
                        <div class="card-header bg-gradient bg-primary text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="mb-0 fw-bold">Pending Complaints (Government Secretary)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['complaints'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fa-4x mb-3 text-success opacity-50"></i>
                                    <h4>All Caught Up!</h4>
                                    <p>There are no complaints pending your review at the moment.</p>
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
                                            <?php foreach($data['complaints'] as $complaint): ?>
                                                <tr style="transition: all 0.2s ease;">
                                                    <td class="ps-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2"><i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($complaint->category_name); ?></span>
                                                        <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/gs/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                            <i class="fas fa-search me-1"></i> Review
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

                <!-- Approved Tab -->
                <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                    <div class="card shadow border-0 mb-4 rounded-4">
                        <div class="card-header bg-gradient bg-success text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="mb-0 fw-bold">Forwarded to CC</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['approved_reports'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-4x mb-3 text-secondary opacity-50"></i>
                                    <h4>No Reports</h4>
                                    <p>You haven't approved any complaints yet.</p>
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
                                            <?php foreach($data['approved_reports'] as $complaint): ?>
                                                <tr style="transition: all 0.2s ease;">
                                                    <td class="ps-3"><span class="text-success fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2"><i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($complaint->category_name); ?></span>
                                                        <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                                    </td>
                                                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span></td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/gs/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
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

                <!-- Rejected Tab -->
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <div class="card shadow border-0 mb-4 rounded-4">
                        <div class="card-header bg-gradient bg-danger text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="mb-0 fw-bold">Rejected Reports</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['rejected_reports'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fa-4x mb-3 text-success opacity-50"></i>
                                    <h4>Good Job!</h4>
                                    <p>You have no rejected reports.</p>
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
                                            <?php foreach($data['rejected_reports'] as $complaint): ?>
                                                <tr style="transition: all 0.2s ease;">
                                                    <td class="ps-3"><span class="text-danger fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-2"><i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($complaint->category_name); ?></span>
                                                        <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                                    </td>
                                                    <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span></td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/gs/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
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
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
