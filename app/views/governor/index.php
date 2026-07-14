<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-md-12">
            <?php flash('complaint_success'); ?>
            <?php flash('complaint_error'); ?>
            <?php flash('auth_error'); ?>

            <!-- Page Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-crown fa-2x me-3" style="color: #6f42c1;"></i>
                    <div>
                        <h4 class="mb-0 fw-bold" style="color: #6f42c1;">Governor's Reports</h4>
                        <small class="text-muted">Complaints approved by Government Secretary</small>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card text-white h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #6f42c1, #8e5fe8);">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small opacity-75">Total GS-Approved (Selected Month)</div>
                                    <div class="fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['approved']; ?></div>
                                </div>
                                <i class="fas fa-file-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-info text-white h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small opacity-75">All System Complaints (Selected Month)</div>
                                    <div class="fw-bold" style="font-size: 2rem;"><?php echo $data['stats']['total']; ?></div>
                                </div>
                                <i class="fas fa-layer-group fa-2x opacity-50"></i>
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

            <!-- Reports Table -->
            <div class="card shadow border-0 rounded-4">
                <div class="card-header text-white py-3 rounded-top-4" style="background: linear-gradient(135deg, #6f42c1, #8e5fe8);">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list-alt me-2"></i>GS-Approved Complaint Reports</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($data['reports'])): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-4x mb-3 opacity-50" style="color: #6f42c1;"></i>
                            <h4>No Reports Yet</h4>
                            <p>No complaints have been approved by the Government Secretary yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive p-2">
                            <table class="table table-hover align-middle border-bottom">
                                <thead class="table-light text-uppercase text-secondary" style="font-size: 0.85rem;">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Complaint No</th>
                                        <th>Date</th>
                                        <th>Applicant</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach($data['reports'] as $complaint): ?>
                                        <tr style="transition: all 0.2s ease;">
                                            <td class="ps-3 text-muted"><?php echo $i++; ?></td>
                                            <td><span class="fw-bold" style="color: #6f42c1;"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                            <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3 py-1"><i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($complaint->category_name); ?></span>
                                                <?php if(!empty($complaint->letter_type)): ?><br><small class="text-muted text-lowercase"><?php echo htmlspecialchars($complaint->letter_type); ?></small><?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1"><i class="fas fa-check me-1"></i><?php echo htmlspecialchars($complaint->status); ?></span></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/governor/show/<?php echo $complaint->id; ?>" class="btn btn-sm rounded-pill px-3 text-white shadow-sm" style="background: #6f42c1;">
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

            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-header text-white py-3 rounded-top-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i> All System Complaints</h5>
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

<?php require APPROOT . '/views/layout/footer.php'; ?>
