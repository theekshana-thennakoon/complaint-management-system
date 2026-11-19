<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-md-12">
            <?php flash('complaint_success'); ?>
            <?php flash('complaint_error'); ?>
            <?php flash('auth_error'); ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                <h2 class="mb-0">Assistant Officer Dashboard</h2>
            </div>
            
            <!-- ── Stat Cards ── -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background:linear-gradient(135deg,#b45309,#f59e0b); cursor:pointer;" onclick="filterAoByStatus('Pending')" title="Click to view Pending review">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-inbox fa-lg"></i></div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['pending']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.85;">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background:linear-gradient(135deg,#166534,#22c55e); cursor:pointer;" onclick="filterAoByStatus('Approved')" title="Click to view Approved reports">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-check-double fa-lg"></i></div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['approved']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.85;">Approved</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background:linear-gradient(135deg,#991b1b,#ef4444); cursor:pointer;" onclick="filterAoByStatus('Rejected')" title="Click to view Rejected reports">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-times-circle fa-lg"></i></div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['rejected']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.85;">Rejected</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6 col-lg-4 d-flex align-items-center">
                    <?php
                    $aoTotal = $data['stats']['approved'] + $data['stats']['rejected'];
                    $aoRate  = $aoTotal > 0 ? round($data['stats']['approved'] / $aoTotal * 100, 1) : 0;
                    ?>
                    <div class="bg-white rounded-4 shadow-sm border p-3 w-100">
                        <div class="small fw-bold text-muted text-uppercase mb-2">Your Approval Rate</div>
                        <div class="d-flex align-items-center gap-3">
                            <div style="font-size:1.9rem;font-weight:800;color:var(--primary-color);"><?php echo $aoRate; ?>%</div>
                            <div class="flex-grow-1">
                                <div style="height:8px;border-radius:99px;background:#e2e8f0;overflow:hidden;">
                                    <div style="width:<?php echo $aoRate; ?>%;height:100%;background:var(--primary-color);border-radius:99px;transition:width 1s;"></div>
                                </div>
                                <div class="small text-muted mt-1"><?php echo $data['stats']['approved']; ?> approved / <?php echo $aoTotal; ?> total decisions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Charts Row ── -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100">
                        <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-chart-pie me-2 text-primary"></i>Decision Breakdown</div>
                        <div class="text-muted mb-2" style="font-size:.72rem;">Your approve / reject / pending split</div>
                        <canvas id="aoDonut" style="max-height:200px;"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100">
                        <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-chart-bar me-2 text-info"></i>Pending Queue by Category</div>
                        <div class="text-muted mb-2" style="font-size:.72rem;">Current AO review queue breakdown</div>
                        <?php
                        $aoCatCount = [];
                        foreach(($data['complaints'] ?? []) as $c){
                            $n = !empty($c->category_name) ? $c->category_name : 'General';
                            $aoCatCount[$n] = ($aoCatCount[$n] ?? 0) + 1;
                        }
                        arsort($aoCatCount);
                        ?>
                        <?php if(empty($aoCatCount)): ?>
                        <div class="text-center py-4 text-muted"><i class="fas fa-check-circle fa-3x mb-2 opacity-25 text-success"></i><p class="mb-0 small">Queue is empty!</p></div>
                        <?php else: ?>
                        <canvas id="aoCatBar" style="max-height:200px;"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100">
                        <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-building me-2 text-primary"></i>Pending by Department</div>
                        <div class="text-muted mb-2" style="font-size:.72rem;">Department queue workload</div>
                        <?php
                        $aoDeptCount = [];
                        foreach(($data['complaints'] ?? []) as $c){
                            $d = !empty($c->department_name) ? $c->department_name : 'General / Direct';
                            $aoDeptCount[$d] = ($aoDeptCount[$d] ?? 0) + 1;
                        }
                        arsort($aoDeptCount);
                        $aoDeptLabels = array_slice(array_keys($aoDeptCount), 0, 5);
                        $aoDeptVals = [];
                        foreach($aoDeptLabels as $adl) {
                            $aoDeptVals[] = $aoDeptCount[$adl] ?? 0;
                        }
                        ?>
                        <?php if(empty($aoDeptVals)): ?>
                        <div class="text-center py-4 text-muted"><i class="fas fa-building fa-3x mb-2 opacity-25 text-primary"></i><p class="mb-0 small">No department data</p></div>
                        <?php else: ?>
                        <canvas id="aoDeptBar" style="max-height:200px;"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12 d-flex justify-content-end">
                    <form method="GET" action="" class="d-flex flex-wrap align-items-center bg-white py-2 px-3 rounded-4 shadow-sm border" style="gap: 12px; border-color: rgba(0,0,0,0.06) !important;">
                        <div class="input-group input-group-sm" style="width: auto; min-width: 170px;">
                            <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-1">
                                <i class="fas fa-filter"></i>
                            </span>
                            <select name="category_id" id="categoryFilter" class="form-select border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer;" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach($data['categories'] as $category): ?>
                                    <option value="<?php echo $category->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="vr bg-secondary opacity-25 d-none d-md-block" style="width: 1px; height: 26px;"></div>
                        
                        <!-- Date Filter -->
                        <div class="input-group input-group-sm" style="width: auto;">
                            <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-1" title="Filter by date">
                                <i class="fas fa-calendar-day"></i> <span class="d-none d-xl-inline ms-1 small">Date:</span>
                            </span>
                            <input type="date" name="date" id="dateFilter" class="form-control border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer;" value="<?php echo htmlspecialchars($data['date'] ?? ''); ?>" onchange="if(this.value) { const m = document.getElementById('monthFilter'); if(m) m.value = ''; } this.form.submit()">
                        </div>

                        <!-- Month Filter -->
                        <div class="input-group input-group-sm" style="width: auto;">
                            <span class="input-group-text bg-transparent border-0 text-primary fw-semibold pe-1" title="Filter by month">
                                <i class="fas fa-calendar-alt"></i> <span class="d-none d-xl-inline ms-1 small">Month:</span>
                            </span>
                            <input type="month" name="month" id="monthFilter" class="form-control border-0 bg-light rounded-pill px-3 fw-medium text-secondary" style="cursor: pointer;" value="<?php echo htmlspecialchars($data['month'] ?? ''); ?>" onchange="if(this.value) { const d = document.getElementById('dateFilter'); if(d) d.value = ''; } this.form.submit()">
                        </div>

                        <?php if(!empty($data['date']) || !empty($data['month']) || !empty($data['category_id'])): ?>
                            <a href="<?php echo URLROOT; ?>/ao?month=&date=&category_id=" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Clear filters">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <ul class="nav nav-tabs mb-4" id="aoDashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-primary" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab"><i class="fas fa-list-alt me-1"></i> Pending Review</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-success" id="submitted-tab" data-bs-toggle="tab" data-bs-target="#submitted" type="button" role="tab"><i class="fas fa-check-double me-1"></i> Submitted to GS</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-danger" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab"><i class="fas fa-times-circle me-1"></i> Rejected Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-info" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab"><i class="fas fa-list me-1"></i> All Complaints</button>
                </li>
            </ul>

            <div class="tab-content" id="aoDashboardTabsContent">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <div class="card shadow border-0 mb-4 rounded-4">
                        <div class="card-header bg-gradient bg-primary text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="mb-0 fw-bold">Pending Complaints (Administrative Officer)</h5>
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
                                                <th>Name</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['complaints'] as $complaint): ?>
                                                <tr style="transition: all 0.2s ease;">
                                                    <td class="ps-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td>
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/ao/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
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

                <!-- Submitted Tab -->
                <div class="tab-pane fade" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
                    <div class="card shadow border-0 mb-4 rounded-4">
                        <div class="card-header bg-gradient bg-success text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="mb-0 fw-bold">Submitted to GS</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['submitted_to_gs'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-4x mb-3 text-secondary opacity-50"></i>
                                    <h4>No Reports</h4>
                                    <p>You haven't submitted any complaints to the GS yet.</p>
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
                                            <?php foreach($data['submitted_to_gs'] as $complaint): ?>
                                                <tr style="transition: all 0.2s ease;">
                                                    <td class="ps-3"><span class="text-success fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span></td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/ao/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm">
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
                                                <th>Name</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['rejected_reports'] as $complaint): ?>
                                                <tr style="transition: all 0.2s ease;">
                                                    <td class="ps-3"><span class="text-danger fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> <?php echo htmlspecialchars($complaint->status); ?></span></td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/ao/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm">
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

                <!-- All Tab -->
                <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="card shadow border-0 mb-4 rounded-4">
                        <div class="card-header bg-gradient bg-info text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
                            <h5 class="mb-0 fw-bold">All System Complaints</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['all_complaints'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-4x mb-3 text-secondary opacity-50"></i>
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
                                                    <td class="ps-3"><span class="text-info fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                                    <td><?php echo htmlspecialchars($complaint->date); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                                    <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-1"><?php echo htmlspecialchars($complaint->status); ?></span></td>
                                                    <td>
                                                        <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm">
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

<script>
Chart.defaults.font.family = "'Inter','Outfit',sans-serif";

window.filterAoByStatus = function(statusLabel) {
    let tabId = 'pending-tab';
    if (statusLabel === 'Approved') tabId = 'submitted-tab';
    else if (statusLabel === 'Rejected') tabId = 'rejected-tab';
    
    const tabEl = document.getElementById(tabId);
    if (tabEl && typeof bootstrap !== 'undefined') {
        const tab = new bootstrap.Tab(tabEl);
        tab.show();
        const tabsContainer = document.getElementById('aoDashboardTabs');
        if (tabsContainer) {
            tabsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
};

(function(){
    const ctx = document.getElementById('aoDonut');
    if(!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: { labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{ data: [<?php echo $data['stats']['pending']; ?>, <?php echo $data['stats']['approved']; ?>, <?php echo $data['stats']['rejected']; ?>],
                backgroundColor: ['#f59e0b','#22c55e','#ef4444'], borderWidth:2, borderColor:'#fff', hoverOffset:6 }] },
        options: {
            responsive:true, maintainAspectRatio:true, cutout:'65%',
            plugins: {
                legend:{ position:'bottom', labels:{ padding:12, font:{size:11} } },
                tooltip:{ cornerRadius:8, callbacks:{ afterLabel: () => 'Click to switch to this tab' } }
            },
            onClick: (e, elements, chart) => {
                if (elements.length > 0) {
                    const lbl = chart.data.labels[elements[0].index];
                    filterAoByStatus(lbl);
                }
            },
            onHover: (e, elements) => {
                e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            }
        }
    });
})();
(function(){
    const ctx = document.getElementById('aoCatBar');
    if(!ctx) return;
    const labels = <?php echo json_encode(array_keys($aoCatCount)); ?>;
    const vals   = <?php echo json_encode(array_values($aoCatCount)); ?>;
    new Chart(ctx, {
        type:'bar', data:{ labels, datasets:[{ label:'Pending', data:vals,
            backgroundColor:'rgba(59,130,246,.75)', borderColor:'#3b82f6', borderWidth:1.5, borderRadius:8 }] },
        options:{ indexAxis:'y', responsive:true, maintainAspectRatio:true,
            plugins:{ legend:{display:false}, tooltip:{cornerRadius:8} },
            scales:{ x:{beginAtZero:true,grid:{color:'rgba(0,0,0,.05)'},ticks:{stepSize:1}}, y:{grid:{display:false}} } }
    });
})();
(function(){
    const ctx = document.getElementById('aoDeptBar');
    if(!ctx) return;
    const labels = <?php echo json_encode($aoDeptLabels ?? []); ?>;
    const vals   = <?php echo json_encode($aoDeptVals ?? []); ?>;
    new Chart(ctx, {
        type:'bar', data:{ labels, datasets:[{ label:'Complaints', data:vals,
            backgroundColor:'rgba(16,185,129,.75)', borderColor:'#10b981', borderWidth:1.5, borderRadius:8 }] },
        options:{ indexAxis:'y', responsive:true, maintainAspectRatio:true,
            plugins:{ legend:{display:false}, tooltip:{cornerRadius:8} },
            scales:{ x:{beginAtZero:true,grid:{color:'rgba(0,0,0,.05)'},ticks:{stepSize:1}}, y:{grid:{display:false}} } }
    });
})();
</script>
<?php require APPROOT . '/views/layout/footer.php'; ?>
