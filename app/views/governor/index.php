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

            <!-- ── Filter Bar (Clean & User-Friendly) ── -->
            <div class="filter-bar mb-4">
                <form method="GET" action="" class="d-flex flex-wrap align-items-center gap-2 mb-0" id="govFilterForm">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($data['status_filter'] ?? ''); ?>">

                    <div class="d-flex align-items-center gap-2 me-1">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: rgba(111,66,193,0.12); color: #6f42c1;">
                            <i class="fas fa-sliders-h fa-sm"></i>
                        </span>
                        <span class="fw-bold text-dark small text-uppercase d-none d-sm-inline" style="letter-spacing: 0.5px;">Filter:</span>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-pill-item" style="max-width: 220px;">
                        <i class="fas fa-folder filter-icon me-1" style="color: #6f42c1;"></i>
                        <span class="filter-label d-none d-md-inline">Category:</span>
                        <select name="category_id" id="categoryFilter" class="form-select form-select-sm" style="cursor: pointer;" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php foreach($data['categories'] as $category): ?>
                                <option value="<?php echo $category->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="filter-pill-item" style="max-width: 195px;" title="Filter by specific date">
                        <i class="fas fa-calendar-day filter-icon me-1" style="color: #6f42c1;"></i>
                        <span class="filter-label">Date:</span>
                        <input type="date" name="date" id="dateFilter" class="form-control form-control-sm" value="<?php echo htmlspecialchars($data['date'] ?? ''); ?>" onchange="if(this.value) { const m = document.getElementById('monthFilter'); if(m) m.value = ''; } this.form.submit()">
                    </div>

                    <span class="text-muted small fw-bold d-none d-lg-inline text-uppercase" style="font-size:0.68rem; opacity:0.6;">or</span>

                    <!-- Month Filter -->
                    <div class="filter-pill-item" style="max-width: 195px;" title="Filter by entire month">
                        <i class="fas fa-calendar-alt filter-icon me-1" style="color: #6f42c1;"></i>
                        <span class="filter-label">Month:</span>
                        <input type="month" name="month" id="monthFilter" class="form-control form-control-sm" value="<?php echo htmlspecialchars($data['month'] ?? ''); ?>" onchange="if(this.value) { const d = document.getElementById('dateFilter'); if(d) d.value = ''; } this.form.submit()">
                    </div>

                    <!-- Clear Filter -->
                    <?php if(!empty($data['date']) || !empty($data['month']) || !empty($data['category_id']) || !empty($data['status_filter'])): ?>
                    <a href="<?php echo URLROOT; ?>/governor?month=&date=&category_id=&status=" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 shadow-sm d-inline-flex align-items-center gap-1" title="Reset all filters">
                        <i class="fas fa-times"></i>
                        <span class="small fw-semibold">Reset</span>
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #6f42c1, #8e5fe8); cursor: pointer;" onclick="applyGovStatusFilter('Approved by GS')" title="Click to filter by GS-Approved">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-crown fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['approved']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">GS-Approved</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #1e3c72, #2a5298); cursor: pointer;" onclick="applyGovStatusFilter('')" title="Click to view all complaints">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-layer-group fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['total']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">Total System</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #b45309, #f59e0b); cursor: pointer;" onclick="applyGovStatusFilter('Pending')" title="Click to filter by In Review">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['pending']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">In Review</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #991b1b, #ef4444); cursor: pointer;" onclick="applyGovStatusFilter('Rejected')" title="Click to filter by Rejected">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-times-circle fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['rejected']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">Rejected</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1: Status & Category -->
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100">
                        <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-chart-pie me-2" style="color: #6f42c1;"></i>Status Overview</div>
                        <div class="text-muted mb-2" style="font-size:.72rem;">GS-Approved, In Review & Rejected ratio</div>
                        <canvas id="govDonut" style="max-height:210px;"></canvas>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100">
                        <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-chart-bar me-2 text-success"></i>GS-Approved Complaints by Category</div>
                        <div class="text-muted mb-2" style="font-size:.72rem;">Classification of Governor-ready reports</div>
                        <?php
                        $govCatCount = [];
                        foreach(($data['reports'] ?? []) as $c){
                            $n = !empty($c->category_name) ? $c->category_name : 'General';
                            $govCatCount[$n] = ($govCatCount[$n] ?? 0) + 1;
                        }
                        arsort($govCatCount);
                        ?>
                        <?php if(empty($govCatCount)): ?>
                            <div class="text-center py-4 text-muted"><i class="fas fa-folder-open fa-3x mb-2 opacity-25" style="color: #6f42c1;"></i><p class="mb-0 small">No report category data available</p></div>
                        <?php else: ?>
                            <canvas id="govCatBar" style="max-height:210px;"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2: Department-wise -->
            <?php
            $govDeptCount = [];
            foreach(($data['all_complaints'] ?? []) as $c) {
                $d = !empty($c->department_name) ? $c->department_name : 'General / Direct';
                $govDeptCount[$d] = ($govDeptCount[$d] ?? 0) + 1;
            }
            arsort($govDeptCount);
            $govDeptLabels = array_slice(array_keys($govDeptCount), 0, 7);
            $govDeptVals = [];
            foreach($govDeptLabels as $gdl) {
                $govDeptVals[] = $govDeptCount[$gdl] ?? 0;
            }
            ?>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <div class="bg-white rounded-4 shadow-sm border p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-building me-2 text-primary"></i>Department-wise Complaints Distribution</div>
                                <div class="text-muted" style="font-size:.72rem;">Complaint volume across key provincial departments</div>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 small"><?php echo count($govDeptCount); ?> Departments</span>
                        </div>
                        <?php if(empty($govDeptVals)): ?>
                            <div class="text-center py-4 text-muted"><p class="mb-0 small">No department data recorded</p></div>
                        <?php else: ?>
                            <canvas id="govDeptBar" style="max-height:180px;"></canvas>
                        <?php endif; ?>
                    </div>
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
                                        <th class="ps-3">Complaint No</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['reports'] as $complaint): ?>
                                        <tr style="transition: all 0.2s ease;">
                                            <td class="ps-3"><span class="fw-bold" style="color: #6f42c1;"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                            <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->subject); ?></td>
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

            <?php if(!empty($data['status_filter'])): ?>
            <div class="alert alert-primary bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 d-flex align-items-center justify-content-between p-3 mt-4 mb-0 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:10px;background:#6f42c1;color:#fff;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">Filtered by Status: <span class="badge rounded-pill px-3 py-1 ms-1 text-white" style="background:#6f42c1;"><?php echo htmlspecialchars($data['status_filter']); ?></span></div>
                        <small class="text-muted">Displaying matching complaint details in the table below</small>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>/governor?month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id'] ?? ''); ?>#allComplaintsTable" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
                    <i class="fas fa-times me-1"></i> Clear Filter
                </a>
            </div>
            <?php endif; ?>

            <div class="card shadow border-0 rounded-4 mt-4" id="allComplaintsTable">
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
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Status</th>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.font.size = 11;

    window.applyGovStatusFilter = function(statusLabel) {
        let target = statusLabel;
        if (statusLabel === 'GS-Approved') target = 'Approved by GS';
        else if (statusLabel === 'In Review') target = 'Pending';
        
        const url = new URL(window.location.href);
        const curr = url.searchParams.get('status') || '';
        if (curr.toLowerCase() === target.toLowerCase()) {
            url.searchParams.delete('status');
        } else {
            url.searchParams.set('status', target);
        }
        url.hash = 'allComplaintsTable';
        window.location.href = url.toString();
    };

    // Status Donut
    const donutCtx = document.getElementById('govDonut');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['GS-Approved', 'In Review', 'Rejected'],
                datasets: [{
                    data: [
                        <?php echo (int)($data['stats']['approved'] ?? 0); ?>,
                        <?php echo (int)($data['stats']['pending'] ?? 0); ?>,
                        <?php echo (int)($data['stats']['rejected'] ?? 0); ?>
                    ],
                    backgroundColor: ['#6f42c1', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } },
                    tooltip: { callbacks: { afterLabel: () => 'Click to filter and view details' } }
                },
                cutout: '65%',
                onClick: (e, elements, chart) => {
                    if (elements.length > 0) {
                        const lbl = chart.data.labels[elements[0].index];
                        applyGovStatusFilter(lbl);
                    }
                },
                onHover: (e, elements) => {
                    e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                }
            }
        });
    }

    // Category Bar Chart
    const catCtx = document.getElementById('govCatBar');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($govCatCount)); ?>,
                datasets: [{
                    label: 'Approved Complaints',
                    data: <?php echo json_encode(array_values($govCatCount)); ?>,
                    backgroundColor: 'rgba(111, 66, 193, 0.75)',
                    borderColor: '#6f42c1',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Department Bar Chart
    const deptCtx = document.getElementById('govDeptBar');
    if (deptCtx) {
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($govDeptLabels); ?>,
                datasets: [{
                    label: 'Complaints',
                    data: <?php echo json_encode($govDeptVals); ?>,
                    backgroundColor: 'rgba(30, 60, 114, 0.75)',
                    borderColor: '#1e3c72',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
