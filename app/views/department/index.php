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
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #1e3c72, #2a5298); cursor: pointer;" onclick="filterDeptTableByStatus('')" title="Click to show all letters">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-envelope-open-text fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['total']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">Total Received</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #0284c7, #38bdf8);">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-calendar-check fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['this_month']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">This Month</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #d97706, #f59e0b); cursor: pointer;" onclick="filterDeptTableByStatus('Pending Reply')" title="Click to filter by Pending Reply">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-hourglass-half fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['pending_reply']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">Pending Reply</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-white border-0 shadow rounded-4 h-100" style="background: linear-gradient(135deg, #166534, #22c55e); cursor: pointer;" onclick="filterDeptTableByStatus('Replied')" title="Click to filter by Replied">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-reply-all fa-lg"></i>
                                </div>
                                <div>
                                    <div style="font-size:2rem;font-weight:800;line-height:1;"><?php echo $data['stats']['replied']; ?></div>
                                    <div class="small fw-semibold text-uppercase" style="letter-spacing:.5px;opacity:.9;">Replied (<?php echo $data['stats']['reply_rate']; ?>%)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-chart-pie me-2 text-primary"></i>Response Status</div>
                            <div class="text-muted mb-2" style="font-size:.72rem;">Replied vs Pending Response breakdown (click to filter)</div>
                            <canvas id="deptDonut" style="max-height:175px;"></canvas>
                        </div>
                        <div class="d-flex justify-content-around mt-3 pt-2 border-top text-center">
                            <div onclick="filterDeptTableByStatus('Pending Reply')" style="cursor: pointer;" title="Click to filter by Pending Reply">
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-2.5 py-1 small">
                                    <i class="fas fa-hourglass-half me-1"></i><?php echo $data['stats']['pending_reply']; ?> Pending
                                </span>
                            </div>
                            <div onclick="filterDeptTableByStatus('Replied')" style="cursor: pointer;" title="Click to filter by Replied">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-2.5 py-1 small">
                                    <i class="fas fa-check-circle me-1"></i><?php echo $data['stats']['replied']; ?> Replied
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="bg-white rounded-4 shadow-sm border p-3 h-100">
                        <div class="fw-bold mb-1" style="font-size:.88rem;"><i class="fas fa-chart-bar me-2 text-success"></i>Dispatched Letters by Category</div>
                        <div class="text-muted mb-2" style="font-size:.72rem;">Subject categories of received letters</div>
                        <?php
                        $deptCatCount = [];
                        foreach(($data['dispatched'] ?? []) as $c){
                            $n = !empty($c->category_name) ? $c->category_name : 'General';
                            $deptCatCount[$n] = ($deptCatCount[$n] ?? 0) + 1;
                        }
                        arsort($deptCatCount);
                        ?>
                        <?php if(empty($deptCatCount)): ?>
                            <div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-3x mb-2 opacity-25 text-primary"></i><p class="mb-0 small">No letters received yet</p></div>
                        <?php else: ?>
                            <canvas id="deptCatBar" style="max-height:200px;"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Filter Alert Banner -->
            <div id="deptFilterBanner" class="alert alert-primary bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm d-none">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:38px;height:38px;border-radius:10px;background:var(--primary-color);color:#fff;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-primary">Filtered by Status: <span id="deptFilterBannerText" class="badge bg-primary rounded-pill px-3 py-1 ms-1"></span></div>
                        <small class="text-muted">Displaying matching dispatched letters below</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="filterDeptTableByStatus('')">
                    <i class="fas fa-times me-1"></i> Clear Filter
                </button>
            </div>

            <!-- Letters Table -->
            <div class="card shadow border-0 rounded-4" id="lettersTable">
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
                                        <th class="ps-3">Complaint No</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['dispatched'] as $complaint): ?>
                                        <tr class="dept-letter-row" data-status="<?php echo htmlspecialchars($complaint->status); ?>" style="transition: all 0.2s ease;">
                                            <td class="ps-3"><span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span><br><small class="text-muted" style="font-size: 0.85em;"><?php echo !empty($complaint->district) ? htmlspecialchars($complaint->district) : ""; ?></small></td>
                                            <td><?php echo date('Y-m-d', strtotime($complaint->dispatched_at)); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                                            <td><?php echo htmlspecialchars($complaint->subject); ?></td>
                                            <td>
                                                <?php if ($complaint->status === 'Reply Received'): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1">
                                                        <i class="fas fa-check-circle me-1"></i> <?php echo htmlspecialchars($complaint->status); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-1">
                                                        <?php echo htmlspecialchars($complaint->status); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?php echo URLROOT; ?>/department/show/<?php echo $complaint->id; ?>#reply_text" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                                        <i class="fas fa-reply me-1"></i> Reply
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/department/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </a>
                                                    <button type="button" onclick="window.open('<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $complaint->id; ?>?action=print', 'PrintPDF', 'width=1000,height=800');" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                        <i class="fas fa-print me-1"></i> Print / PDF
                                                    </button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.font.size = 11;

    // Response Status Donut
    const donutCtx = document.getElementById('deptDonut');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Replied', 'Pending Reply'],
                datasets: [{
                    data: [
                        <?php echo (int)($data['stats']['replied'] ?? 0); ?>,
                        <?php echo (int)($data['stats']['pending_reply'] ?? 0); ?>
                    ],
                    backgroundColor: ['#22c55e', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } }
                },
                cutout: '65%'
            }
        });
    }

    // Category Bar Chart
    const catCtx = document.getElementById('deptCatBar');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($deptCatCount)); ?>,
                datasets: [{
                    label: 'Letters',
                    data: <?php echo json_encode(array_values($deptCatCount)); ?>,
                    backgroundColor: 'rgba(2, 132, 199, 0.75)',
                    borderColor: '#0284c7',
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
