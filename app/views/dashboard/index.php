<?php require APPROOT . '/views/layout/header.php'; ?>

<?php
// ── PHP data → JS-safe JSON ──────────────────────────────────────────────
$ss   = $data['all_status_summary'];
$doughnut_labels = ['Pending', 'Approved', 'Rejected', 'Sent to Dept', 'Reply Received'];
$doughnut_values = [
    max(0, $ss['pending_cc'] + $ss['approved_cc'] + $ss['approved_ao']),
    max(0, $ss['approved_gs']),
    max(0, $ss['rejected_total']),
    max(0, $ss['dispatched']),
    max(0, $ss['reply_received'] ?? 0),
];

$trend_labels    = array_map(fn($r) => $r['label'], $data['trend_data']);
$trend_total     = array_map(fn($r) => $r['total'],    $data['trend_data']);
$trend_approved  = array_map(fn($r) => $r['approved'], $data['trend_data']);
$trend_rejected  = array_map(fn($r) => $r['rejected'], $data['trend_data']);
$trend_pending   = array_map(fn($r) => $r['pending'],  $data['trend_data']);

$cat_labels = array_keys($data['category_counts']);
$cat_vals   = array_values($data['category_counts']);

// Department stats for charts
$dept_breakdown = $data['dept_status_breakdown'] ?? [];
$dept_labels = array_slice(array_keys($dept_breakdown), 0, 8); // Top 8 departments
$dept_total    = [];
$dept_pending  = [];
$dept_approved = [];
$dept_replied  = [];
$dept_rejected = [];

foreach($dept_labels as $dlbl) {
    $dinfo = $dept_breakdown[$dlbl] ?? ['total'=>0,'pending'=>0,'approved'=>0,'replied'=>0,'rejected'=>0];
    $dept_total[]    = $dinfo['total'];
    $dept_pending[]  = $dinfo['pending'];
    $dept_approved[] = $dinfo['approved'];
    $dept_replied[]  = $dinfo['replied'];
    $dept_rejected[] = $dinfo['rejected'];
}

// Detailed Status stats for charts
$detailed_status = $data['detailed_status_counts'] ?? [];
$status_labels = array_keys($detailed_status);
$status_vals   = array_values($detailed_status);

$workflow_labels = ['Draft','Pending CC','Approved CC','Approved AO','Approved GS','Rejected'];
$workflow_vals   = [
    $ss['draft'],
    $ss['pending_cc'],
    $ss['approved_cc'],
    $ss['approved_ao'],
    $ss['approved_gs'],
    $ss['rejected_total'],
];
?>

<style>
/* ── Dashboard Enhancements ─────────────────────────── */
.stat-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    transition: transform .22s ease, box-shadow .22s ease;
    cursor: default;
}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px -8px rgba(0,0,0,.18) !important; }
.stat-card .icon-wrap {
    width: 56px; height: 56px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.45rem; flex-shrink: 0;
    background: rgba(255,255,255,0.22);
}
.stat-card .stat-num {
    font-size: 2.4rem;
    font-weight: 800;
    line-height: 1;
    color: #ffffff !important;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.22);
}
.stat-card .stat-lbl { font-size: .78rem; font-weight: 600; letter-spacing: .6px; text-transform: uppercase; opacity: .82; }
.stat-card .card-footer { background: rgba(0,0,0,.12); border: none; padding: 8px 20px; }
.stat-card .card-footer a { color: rgba(255,255,255,.88); font-size: .78rem; font-weight: 600; text-decoration: none; }
.stat-card .card-footer a:hover { color: #fff; }

.chart-card {
    border: none; border-radius: 20px;
    background: #fff;
    box-shadow: 0 4px 24px -4px rgba(15,45,26,.09);
    transition: box-shadow .22s;
}
.chart-card:hover { box-shadow: 0 8px 32px -6px rgba(15,45,26,.15); }
.chart-card .chart-header {
    padding: 18px 22px 0;
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
}
.chart-card .chart-title { font-size: .92rem; font-weight: 700; color: var(--text-primary); margin: 0; }
.chart-card .chart-sub { font-size: .72rem; color: var(--text-muted); margin: 2px 0 0; }
.chart-card .chart-body { padding: 18px 18px 20px; position: relative; }

.progress-mini { height: 7px; border-radius: 99px; background: var(--gray-100); overflow: hidden; }
.progress-mini .bar { height: 100%; border-radius: 99px; transition: width 1s ease; }

.rate-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 99px; font-size: .72rem; font-weight: 700;
}

.workflow-step {
    display: flex; align-items: center; gap: 10px; padding: 10px 14px;
    border-radius: 12px; transition: background .18s;
}
.workflow-step:hover { background: var(--primary-50); }
.workflow-step .step-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}
.workflow-step .step-count {
    margin-left: auto; font-weight: 700; font-size: .9rem;
}
.workflow-step .step-bar { flex: 1; max-width: 90px; }

/* ── Filter bar ─────────────────────────── */
.filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 16px -2px rgba(15,45,26,.06);
    padding: 12px 18px;
    transition: all 0.2s ease;
}
.filter-bar:hover {
    box-shadow: 0 6px 20px -2px rgba(15,45,26,.09);
    border-color: #cbd5e1;
}
.filter-pill-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 3px 12px;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.filter-pill-item:focus-within, .filter-pill-item:hover {
    border-color: var(--primary-color);
    background: #ffffff;
    box-shadow: 0 0 0 2px rgba(30, 60, 114, 0.08);
}
.filter-pill-item .form-select,
.filter-pill-item .form-control {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    font-size: 0.82rem;
    font-weight: 500;
    color: #334155;
    padding: 4px 6px;
    height: auto;
}
.filter-pill-item .filter-icon {
    color: var(--primary-color);
    font-size: 0.85rem;
    flex-shrink: 0;
}
.filter-pill-item .filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    margin-right: 4px;
    letter-spacing: 0.4px;
    white-space: nowrap;
}

/* ── Tab improvements ─────────────────────────── */
.dashboard-tabs .nav-link {
    border: none; border-radius: 10px 10px 0 0;
    font-weight: 600; font-size: .86rem; padding: 10px 20px; color: var(--text-secondary);
    transition: all .2s;
}
.dashboard-tabs .nav-link.active { background: #fff; color: var(--primary-color); }

/* ── Status badge improvements ─────────────────────────── */
.status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 99px; font-size: .75rem; font-weight: 600;
    white-space: nowrap;
}

/* ── Responsive canvas fix ─────────────────────────── */
canvas { max-height: 240px !important; }
.chart-body.tall canvas { max-height: 300px !important; }
</style>

<div class="container-fluid mt-4 px-4 pb-5">

<!-- ── Top heading ──────────────────────────────────────────── -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="mb-0 fw-bold" style="color:var(--primary-dark);">
            <i class="fas fa-tachometer-alt me-2" style="color:var(--primary-color);"></i>
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </h2>
        <p class="text-muted mb-0 small mt-1">
            <?php echo date('l, F j, Y'); ?> &nbsp;·&nbsp;
            <?php $m = $data['month'] ?: date('Y-m'); echo date('F Y', strtotime($m.'-01')); ?> overview
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
            <i class="fas fa-plus me-2"></i>New Complaint
        </a>
    </div>
</div>

<?php flash('complaint_success'); ?>
<?php flash('complaint_error'); ?>

<!-- ══════════════════════════════════════════════════════════════
     ROW 1 — FILTER BAR (Clean & User-Friendly)
     ══════════════════════════════════════════════════════════════ -->
<div class="filter-bar mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        
        <!-- Filter Controls Form -->
        <form method="GET" action="" class="d-flex flex-wrap align-items-center gap-2 mb-0" id="filterForm">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($data['status_filter'] ?? ''); ?>">

            <!-- Filter Label / Icon -->
            <div class="d-flex align-items-center gap-2 me-1">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 32px; height: 32px;">
                    <i class="fas fa-sliders-h fa-sm"></i>
                </span>
                <span class="fw-bold text-dark small text-uppercase d-none d-sm-inline" style="letter-spacing: 0.5px;">Filter:</span>
            </div>

            <!-- Status Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm filter-pill-item dropdown-toggle text-start" type="button" id="statusSummaryDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <i class="fas fa-chart-pie filter-icon me-2"></i>
                    <span class="filter-label">Status:</span>
                    <strong class="text-primary me-1"><?php echo !empty($data['status_filter']) ? htmlspecialchars($data['status_filter']) : 'All'; ?></strong>
                </button>
                <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2" aria-labelledby="statusSummaryDropdown" style="min-width:270px; z-index:1060;">
                    <li><h6 class="dropdown-header small fw-bold text-muted text-uppercase">Filter by Status</h6></li>
                    <?php
                    $sf = $data['status_filter'] ?? '';
                    $qs = 'month='.urlencode($data['month']).'&date='.urlencode($data['date'] ?? '').'&category_id='.urlencode($data['category_id']);
                    $statusLinks = [
                        '' => ['All Complaints', 'fas fa-list-ul', 'secondary', $ss['total']],
                        'Draft' => ['Draft', 'fas fa-file-alt', 'secondary', $ss['draft']],
                        'Pending CC' => ['Pending CC', 'fas fa-clock', 'warning', $ss['pending_cc']],
                        'Approved by CC (Pending AO)' => ['Approved by CC', 'fas fa-check', 'info', $ss['approved_cc']],
                        'Approved by AO (Pending GS)' => ['Approved by AO', 'fas fa-user-check', 'primary', $ss['approved_ao']],
                        'Approved by GS' => ['Approved by GS', 'fas fa-check-circle', 'success', $ss['approved_gs']],
                        'Rejected' => ['Rejected', 'fas fa-times-circle', 'danger', $ss['rejected_total']],
                        'Sent to Department' => ['Sent to Dept', 'fas fa-paper-plane', 'dark', $ss['dispatched']],
                        'Reply Received' => ['Reply Received', 'fas fa-reply', 'info', $ss['reply_received'] ?? 0],
                    ];
                    foreach($statusLinks as $key => [$label, $icon, $color, $count]):
                        $active = ($sf === $key) ? 'active bg-primary text-white' : '';
                        $href = URLROOT.'/dashboard?status='.urlencode($key).'&'.$qs;
                    ?>
                    <li>
                        <a class="dropdown-item rounded-3 d-flex justify-content-between align-items-center py-2 <?php echo $active; ?>" href="<?php echo $href; ?>">
                            <span><i class="<?php echo $icon; ?> me-2 text-<?php echo $active ? 'white' : $color; ?>"></i><?php echo $label; ?></span>
                            <span class="badge bg-<?php echo $active ? 'white text-primary' : $color; ?> rounded-pill"><?php echo $count; ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Category Filter -->
            <div class="filter-pill-item" style="max-width: 220px;">
                <i class="fas fa-folder filter-icon me-1"></i>
                <span class="filter-label d-none d-md-inline">Category:</span>
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()" style="cursor: pointer;">
                    <option value="">All Categories</option>
                    <?php foreach($data['categories'] as $cat): ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo ($data['category_id'] == $cat->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Specific Date Filter -->
            <div class="filter-pill-item" style="max-width: 195px;" title="Filter by specific day">
                <i class="fas fa-calendar-day filter-icon me-1"></i>
                <span class="filter-label">Date:</span>
                <input type="date" name="date" id="dateFilter" class="form-control form-control-sm" value="<?php echo htmlspecialchars($data['date'] ?? ''); ?>" onchange="if(this.value){document.getElementById('monthFilter').value='';}this.form.submit()">
            </div>

            <span class="text-muted small fw-bold d-none d-lg-inline text-uppercase" style="font-size:0.68rem; opacity:0.6;">or</span>

            <!-- Month Filter -->
            <div class="filter-pill-item" style="max-width: 195px;" title="Filter by entire month">
                <i class="fas fa-calendar-alt filter-icon me-1"></i>
                <span class="filter-label">Month:</span>
                <input type="month" name="month" id="monthFilter" class="form-control form-control-sm" value="<?php echo htmlspecialchars($data['month'] ?? ''); ?>" onchange="if(this.value){document.getElementById('dateFilter').value='';}this.form.submit()">
            </div>

            <!-- Clear Button -->
            <?php if(!empty($data['date']) || !empty($data['month']) || !empty($data['category_id']) || !empty($data['status_filter'])): ?>
            <a href="<?php echo URLROOT; ?>/dashboard?month=&date=&category_id=&status=" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 shadow-sm d-inline-flex align-items-center gap-1" title="Reset all filters">
                <i class="fas fa-times"></i>
                <span class="small fw-semibold">Reset</span>
            </a>
            <?php endif; ?>
        </form>

        <!-- Resolution & Reply Rate Pills -->
        <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
            <span class="rate-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25" title="Overall Resolution Rate">
                <i class="fas fa-check-circle"></i> Resolution: <strong><?php echo $data['resolution_rate']; ?>%</strong>
            </span>
            <span class="rate-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25" title="Department Reply Rate">
                <i class="fas fa-reply"></i> Reply Rate: <strong><?php echo $data['reply_rate']; ?>%</strong>
            </span>
        </div>

    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     ROW 2 — STAT CARDS (6 cards)
     ══════════════════════════════════════════════════════════════ -->
<?php
$ass = $data['all_status_summary'] ?? [];
$allTotal    = $ass['total']          ?? 0;
$allPending  = $ass['pending_total']  ?? 0;
$allApproved = $ass['approved_total'] ?? 0;
$allRejected = $ass['rejected_total'] ?? 0;
$allSent     = $ass['dispatched']     ?? 0;
$allReplied  = $ass['reply_received'] ?? 0;
?>
<div class="row g-3 mb-4">

    <!-- Total -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-white h-100 shadow" style="background:linear-gradient(135deg,#1e3c72,#2a5298);">
            <div class="card-body d-flex flex-column gap-2 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="fas fa-layer-group"></i></div>
                    <div>
                        <div class="stat-num"><?php echo $data['stats']['total']; ?></div>
                        <div class="stat-lbl">Total Letters</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-white border-opacity-25 mt-1">
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-user me-1"></i>Mine</span>
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-globe me-1"></i>Total: <strong><?php echo $allTotal; ?></strong></span>
                </div>
            </div>
            <div class="card-footer"><a href="<?php echo URLROOT; ?>/dashboard">View All &rarr;</a></div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-white h-100 shadow" style="background:linear-gradient(135deg,#b45309,#f59e0b);">
            <div class="card-body d-flex flex-column gap-2 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="fas fa-hourglass-half"></i></div>
                    <div>
                        <div class="stat-num"><?php echo $data['stats']['pending']; ?></div>
                        <div class="stat-lbl">Pending</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-white border-opacity-25 mt-1">
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-user me-1"></i>Mine</span>
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-globe me-1"></i>Total: <strong><?php echo $allPending; ?></strong></span>
                </div>
            </div>
            <div class="card-footer"><a href="<?php echo URLROOT; ?>/dashboard?status=Pending">View &rarr;</a></div>
        </div>
    </div>

    <!-- Approved -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-white h-100 shadow" style="background:linear-gradient(135deg,#166534,#22c55e);">
            <div class="card-body d-flex flex-column gap-2 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="fas fa-check-double"></i></div>
                    <div>
                        <div class="stat-num"><?php echo $data['stats']['approved']; ?></div>
                        <div class="stat-lbl">Approved</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-white border-opacity-25 mt-1">
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-user me-1"></i>Mine</span>
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-globe me-1"></i>Total: <strong><?php echo $allApproved; ?></strong></span>
                </div>
            </div>
            <div class="card-footer"><a href="<?php echo URLROOT; ?>/dashboard?status=Approved">View &rarr;</a></div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-white h-100 shadow" style="background:linear-gradient(135deg,#991b1b,#ef4444);">
            <div class="card-body d-flex flex-column gap-2 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="fas fa-times-circle"></i></div>
                    <div>
                        <div class="stat-num"><?php echo $data['stats']['rejected']; ?></div>
                        <div class="stat-lbl">Rejected</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-white border-opacity-25 mt-1">
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-user me-1"></i>Mine</span>
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-globe me-1"></i>Total: <strong><?php echo $allRejected; ?></strong></span>
                </div>
            </div>
            <div class="card-footer"><a href="<?php echo URLROOT; ?>/dashboard?status=Rejected">View &rarr;</a></div>
        </div>
    </div>

    <!-- Sent -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-white h-100 shadow" style="background:linear-gradient(135deg,#1e40af,#3b82f6);">
            <div class="card-body d-flex flex-column gap-2 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="fas fa-paper-plane"></i></div>
                    <div>
                        <div class="stat-num"><?php echo $data['stats']['sent']; ?></div>
                        <div class="stat-lbl">Sent to Dept</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-white border-opacity-25 mt-1">
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-user me-1"></i>Mine</span>
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-globe me-1"></i>Total: <strong><?php echo $allSent; ?></strong></span>
                </div>
            </div>
            <div class="card-footer"><a href="<?php echo URLROOT; ?>/complaints/sent">View &rarr;</a></div>
        </div>
    </div>

    <!-- Reply Received -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-white h-100 shadow" style="background:linear-gradient(135deg,#0e7490,#06b6d4);">
            <div class="card-body d-flex flex-column gap-2 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-wrap"><i class="fas fa-reply"></i></div>
                    <div>
                        <div class="stat-num"><?php echo $data['stats']['replied']; ?></div>
                        <div class="stat-lbl">Replied</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-white border-opacity-25 mt-1">
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-user me-1"></i>Mine</span>
                    <span style="font-size:.7rem;opacity:.8;"><i class="fas fa-globe me-1"></i>Total: <strong><?php echo $allReplied; ?></strong></span>
                </div>
            </div>
            <div class="card-footer"><a href="<?php echo URLROOT; ?>/dashboard?status=Reply+Received">View &rarr;</a></div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     ROW 3 — CHARTS (Trend + Doughnut + Workflow)
     ══════════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Bar/Line Trend Chart (wider) -->
    <div class="col-lg-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-chart-bar me-2 text-primary"></i>Monthly Complaint Trends</div>
                    <div class="chart-sub">Total, approved, pending & rejected per month</div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-1" style="font-size:.7rem;" onclick="toggleTrendType('bar')">Bar</button>
                    <button class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-1" style="font-size:.7rem;" onclick="toggleTrendType('line')">Line</button>
                </div>
            </div>
            <div class="chart-body tall">
                <?php if(empty($data['trend_data'])): ?>
                <div class="text-center py-5 text-muted"><i class="fas fa-chart-bar fa-3x mb-3 opacity-25"></i><p>No data for this period</p></div>
                <?php else: ?>
                <canvas id="trendChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart -->
    <div class="col-lg-3 col-md-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-chart-pie me-2 text-warning"></i>Status Breakdown</div>
                    <div class="chart-sub">All system complaints</div>
                </div>
            </div>
            <div class="chart-body">
                <?php if(array_sum($doughnut_values) == 0): ?>
                <div class="text-center py-5 text-muted"><i class="fas fa-chart-pie fa-3x mb-3 opacity-25"></i><p>No data yet</p></div>
                <?php else: ?>
                <canvas id="statusDoughnut"></canvas>
                <?php endif; ?>
            </div>
            <!-- Legend -->
            <?php if(array_sum($doughnut_values) > 0): ?>
            <div class="px-3 pb-3">
                <?php
                $dl = [
                    ['Pending','#f59e0b'],['Approved','#22c55e'],['Rejected','#ef4444'],['Sent to Dept','#3b82f6'],['Reply Received','#06b6d4']
                ];
                foreach($dl as $i => [$lbl,$clr]):
                    $pct = array_sum($doughnut_values) > 0 ? round($doughnut_values[$i]/array_sum($doughnut_values)*100,1) : 0;
                    $isActiveSt = (isset($data['status_filter']) && strtolower($data['status_filter']) === strtolower($lbl));
                ?>
                <div class="d-flex align-items-center justify-content-between py-1 px-2 rounded-2 mb-1 <?php echo $isActiveSt ? 'bg-primary bg-opacity-10 border border-primary' : ''; ?>" onclick="applyStatusFilter('<?php echo $lbl; ?>')" style="cursor:pointer;" title="Click to filter by <?php echo $lbl; ?>">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:<?php echo $clr; ?>;display:inline-block;flex-shrink:0;"></span>
                        <span class="small <?php echo $isActiveSt ? 'fw-bold text-primary' : ''; ?>"><?php echo $lbl; ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold small <?php echo $isActiveSt ? 'text-primary' : ''; ?>"><?php echo $doughnut_values[$i]; ?></span>
                        <span class="text-muted" style="font-size:.7rem;"><?php echo $pct; ?>%</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Workflow Pipeline -->
    <div class="col-lg-3 col-md-6">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-project-diagram me-2" style="color:#8b5cf6;"></i>Workflow Pipeline</div>
                    <div class="chart-sub">Approval stage distribution (click to filter)</div>
                </div>
            </div>
            <div class="chart-body">
                <?php
                $wf = [
                    ['Draft',       $ss['draft'],        '#94a3b8'],
                    ['Pending CC',  $ss['pending_cc'],   '#f59e0b'],
                    ['CC Approved', $ss['approved_cc'],  '#3b82f6'],
                    ['AO Approved', $ss['approved_ao'],  '#8b5cf6'],
                    ['GS Approved', $ss['approved_gs'],  '#22c55e'],
                    ['Rejected',    $ss['rejected_total'],'#ef4444'],
                ];
                $wfMax = max(1, ...array_column($wf, 1));
                foreach($wf as [$wlbl, $wval, $wclr]):
                    $wPct = round($wval / $wfMax * 100);
                    $isActiveWf = (isset($data['status_filter']) && (
                        strtolower($data['status_filter']) === strtolower($wlbl) ||
                        ($wlbl === 'CC Approved' && strpos(strtolower($data['status_filter']), 'cc') !== false && strpos(strtolower($data['status_filter']), 'approve') !== false) ||
                        ($wlbl === 'AO Approved' && strpos(strtolower($data['status_filter']), 'ao') !== false && strpos(strtolower($data['status_filter']), 'approve') !== false) ||
                        ($wlbl === 'GS Approved' && strpos(strtolower($data['status_filter']), 'gs') !== false && strpos(strtolower($data['status_filter']), 'approve') !== false)
                    ));
                ?>
                <div class="workflow-step mb-1 p-1 px-2 rounded-2 <?php echo $isActiveWf ? 'bg-primary bg-opacity-10 border border-primary' : ''; ?>" onclick="applyStatusFilter('<?php echo $wlbl; ?>')" style="cursor:pointer;" title="Click to filter by <?php echo $wlbl; ?>">
                    <span class="step-dot" style="background:<?php echo $wclr; ?>;"></span>
                    <span class="small fw-semibold flex-grow-1 <?php echo $isActiveWf ? 'text-primary fw-bold' : ''; ?>"><?php echo $wlbl; ?></span>
                    <div class="step-bar progress-mini flex-shrink-0" style="width:70px;">
                        <div class="bar" style="width:<?php echo $wPct; ?>%;background:<?php echo $wclr; ?>;"></div>
                    </div>
                    <span class="step-count fw-bold small" style="color:<?php echo $wclr; ?>; min-width:28px; text-align:right;"><?php echo $wval; ?></span>
                </div>
                <?php endforeach; ?>
                <!-- Resolution progress -->
                <hr class="my-3">
                <div class="mb-1 d-flex justify-content-between">
                    <span class="small fw-semibold">Resolution Rate</span>
                    <span class="small fw-bold text-success"><?php echo $data['resolution_rate']; ?>%</span>
                </div>
                <div class="progress-mini mb-3"><div class="bar" style="width:<?php echo $data['resolution_rate']; ?>%;background:#22c55e;"></div></div>
                <div class="mb-1 d-flex justify-content-between">
                    <span class="small fw-semibold">Dept Reply Rate</span>
                    <span class="small fw-bold text-info"><?php echo $data['reply_rate']; ?>%</span>
                </div>
                <div class="progress-mini"><div class="bar" style="width:<?php echo $data['reply_rate']; ?>%;background:#06b6d4;"></div></div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     ROW 4 — Category + My Activity bars
     ══════════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Horizontal Category Bar Chart -->
    <div class="col-lg-7">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-tags me-2 text-info"></i>Complaints by Category</div>
                    <div class="chart-sub">Top categories this period (all system)</div>
                </div>
            </div>
            <div class="chart-body tall">
                <?php if(empty($cat_labels)): ?>
                <div class="text-center py-5 text-muted"><i class="fas fa-tags fa-3x mb-3 opacity-25"></i><p>No category data</p></div>
                <?php else: ?>
                <canvas id="categoryChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- My Activity mini -->
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-user-circle me-2 text-primary"></i>My Activity Summary</div>
                    <div class="chart-sub">Your submitted complaints this period</div>
                </div>
            </div>
            <div class="chart-body">
                <?php
                $myS = $data['status_summary'];
                $myItems = [
                    ['My Total',      $myS['total'],           '#1e3c72', 'fas fa-layer-group'],
                    ['My Pending',    $myS['pending_total'],   '#f59e0b', 'fas fa-hourglass-half'],
                    ['My Approved',   $myS['approved_total'],  '#22c55e', 'fas fa-check-double'],
                    ['My Rejected',   $myS['rejected_total'],  '#ef4444', 'fas fa-times-circle'],
                    ['Sent to Dept',  $myS['dispatched'],      '#3b82f6', 'fas fa-paper-plane'],
                    ['Replies',       $myS['reply_received'] ?? 0, '#06b6d4', 'fas fa-reply'],
                ];
                $myMax = max(1, ...array_column($myItems, 1));
                foreach($myItems as [$mlbl, $mval, $mclr, $micon]):
                    $mPct = round($mval / $myMax * 100);
                ?>
                <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                    <div style="width:34px;height:34px;border-radius:10px;background:<?php echo $mclr; ?>20;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="<?php echo $micon; ?> small" style="color:<?php echo $mclr; ?>;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold"><?php echo $mlbl; ?></span>
                            <span class="small fw-bold" style="color:<?php echo $mclr; ?>;"><?php echo $mval; ?></span>
                        </div>
                        <div class="progress-mini">
                            <div class="bar" style="width:<?php echo $mPct; ?>%;background:<?php echo $mclr; ?>;"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     ROW 5 — DEPARTMENT-WISE & STATUS-WISE CHARTS
     ══════════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
    <!-- Department-wise Stacked Bar Chart -->
    <div class="col-lg-7">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-building me-2 text-primary"></i>Department-wise Complaints & Status</div>
                    <div class="chart-sub">Volume and resolution/reply progress across departments</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 small"><?php echo count($data['department_counts'] ?? []); ?> Departments</span>
                </div>
            </div>
            <div class="chart-body tall">
                <?php if(empty($dept_labels)): ?>
                <div class="text-center py-5 text-muted"><i class="fas fa-building fa-3x mb-3 opacity-25"></i><p>No department data for this period</p></div>
                <?php else: ?>
                <canvas id="deptChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Status-wise Detailed Distribution -->
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-header">
                <div>
                    <div class="chart-title"><i class="fas fa-layer-group me-2 text-warning"></i>Status-wise Lifecycle Breakdown</div>
                    <div class="chart-sub">Complete count across all complaint stages</div>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="detailedStatusChart" style="max-height:210px;"></canvas>
            </div>
            <div class="px-3 pb-3">
                <div class="row g-1 pt-2 border-top">
                    <?php
                    $statusBadgeColors = [
                        'Draft' => 'secondary',
                        'Pending CC' => 'warning',
                        'Pending AO' => 'info',
                        'Pending GS' => 'primary',
                        'Approved by GS' => 'success',
                        'Sent to Dept' => 'dark',
                        'Reply Received' => 'info',
                        'Rejected' => 'danger'
                    ];
                    foreach(($data['detailed_status_counts'] ?? []) as $stKey => $stVal):
                        $bgCl = $statusBadgeColors[$stKey] ?? 'secondary';
                    ?>
                    <div class="col-6">
                        <?php $isActiveBadge = (isset($data['status_filter']) && strtolower($data['status_filter']) === strtolower($stKey)); ?>
                        <div class="d-flex align-items-center justify-content-between p-1 px-2 rounded-2 mb-1 <?php echo $isActiveBadge ? 'bg-primary bg-opacity-10 border border-primary' : 'bg-light'; ?>" onclick="applyStatusFilter('<?php echo $stKey; ?>')" style="cursor:pointer;" title="Click to filter by <?php echo $stKey; ?>">
                            <span class="small text-truncate me-1 <?php echo $isActiveBadge ? 'text-primary fw-bold' : 'text-muted fw-medium'; ?>" style="font-size:0.75rem;"><?php echo $stKey; ?></span>
                            <span class="badge bg-<?php echo $bgCl; ?> rounded-pill" style="font-size:0.72rem;"><?php echo $stVal; ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════
     ACTIVE FILTER BANNER
     ══════════════════════════════════════════════════════════════ -->
<?php if(!empty($data['status_filter'])): ?>
<div class="alert alert-primary bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 d-flex align-items-center justify-content-between p-3 mb-4 shadow-sm" id="activeFilterBanner">
    <div class="d-flex align-items-center gap-3">
        <div style="width:42px;height:42px;border-radius:12px;background:var(--primary-color);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-filter fa-lg"></i>
        </div>
        <div>
            <div class="fw-bold text-primary">Filtered by Status: <span class="badge bg-primary rounded-pill px-3 py-1 ms-1"><?php echo htmlspecialchars($data['status_filter']); ?></span></div>
            <small class="text-muted">Displaying matching complaints below. Click "Clear Filter" or click the status in the chart again to reset.</small>
        </div>
    </div>
    <a href="<?php echo URLROOT; ?>/dashboard?month=<?php echo urlencode($data['month']); ?>&date=<?php echo urlencode($data['date'] ?? ''); ?>&category_id=<?php echo urlencode($data['category_id'] ?? ''); ?>#dashboardTabs" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm fw-semibold">
        <i class="fas fa-times me-1"></i> Clear Filter
    </a>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════════
     ROW 6 — COMPLAINT TABLES (tabs)
     ══════════════════════════════════════════════════════════════ -->
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
        <ul class="nav dashboard-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="my-tab" data-bs-toggle="tab" data-bs-target="#my-complaints" type="button" role="tab">
                    <i class="fas fa-file-alt me-1 text-primary"></i> My Submitted
                    <span class="badge bg-primary rounded-pill ms-1"><?php echo count($data['user_complaints']); ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="external-tab" data-bs-toggle="tab" data-bs-target="#external-complaints" type="button" role="tab">
                    <i class="fas fa-external-link-alt me-1 text-success"></i> External
                    <span class="badge bg-success rounded-pill ms-1"><?php echo count($data['external_complaints']); ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-complaints" type="button" role="tab">
                    <i class="fas fa-list me-1 text-info"></i> All System
                    <span class="badge bg-info rounded-pill ms-1"><?php echo count($data['all_complaints']); ?></span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content px-3 pb-3" id="dashboardTabsContent">

        <!-- My Complaints Tab -->
        <div class="tab-pane fade show active" id="my-complaints" role="tabpanel" aria-labelledby="my-tab">
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-file-alt me-2"></i>My Submitted Complaints</h6>
                <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-plus me-1"></i>Add New
                </a>
            </div>
            <?php if(empty($data['user_complaints'])): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-folder-open fa-4x mb-3 opacity-25"></i>
                <h5>No Complaints Yet</h5>
                <p>Click "Add New" to submit your first complaint.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle" style="font-size:.88rem;">
                    <thead class="table-light text-uppercase text-muted" style="font-size:.75rem;">
                        <tr>
                            <th class="ps-3">Complaint No</th>
                            <th>Date</th>
                            <th>Applicant</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['user_complaints'] as $complaint): ?>
                        <tr>
                            <td class="ps-3">
                                <span class="text-primary fw-bold"><?php echo htmlspecialchars($complaint->complaint_no); ?></span>
                                <?php if(!empty($complaint->district)): ?><br><small class="text-muted"><?php echo htmlspecialchars($complaint->district); ?></small><?php endif; ?>
                            </td>
                            <td><span class="text-muted small"><?php echo htmlspecialchars($complaint->date); ?></span></td>
                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                            <td><?php echo htmlspecialchars(mb_strimwidth($complaint->subject, 0, 40, '…')); ?></td>
                            <td><?php
                                $st = $complaint->status;
                                if(strpos($st,'Reject')!==false)       echo '<span class="status-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="fas fa-times-circle me-1"></i>'.htmlspecialchars($st).'</span>';
                                elseif($st=='Reply Received')           echo '<span class="status-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="fas fa-reply me-1"></i>'.htmlspecialchars($st).'</span>';
                                elseif(strpos($st,'Department')!==false)echo '<span class="status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="fas fa-paper-plane me-1"></i>'.htmlspecialchars($st).'</span>';
                                elseif(strpos($st,'Approved')!==false)  echo '<span class="status-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="fas fa-check-circle me-1"></i>'.htmlspecialchars($st).'</span>';
                                else                                    echo '<span class="status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i class="fas fa-hourglass-half me-1"></i>'.htmlspecialchars($st).'</span>';
                            ?></td>
                            <td class="d-flex gap-2 flex-wrap">
                                <?php if($complaint->status == 'Draft'): ?>
                                <a href="<?php echo URLROOT; ?>/complaints/edit/<?php echo $complaint->id; ?>" class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm"><i class="fas fa-edit me-1"></i>Edit</a>
                                <?php elseif(strpos($complaint->status,'Reject')!==false): ?>
                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm"><i class="fas fa-exclamation-circle me-1"></i>Fix</a>
                                <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm"><i class="fas fa-eye me-1"></i>View</a>
                                <?php endif; ?>
                                <?php if($complaint->status == 'Reply Received'): ?>
                                <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-sm" onclick="openReplyModal(<?php echo $complaint->id; ?>,'<?php echo htmlspecialchars($complaint->complaint_no,ENT_QUOTES); ?>')"><i class="fas fa-reply me-1"></i>Reply</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- External Complaints Tab -->
        <div class="tab-pane fade" id="external-complaints" role="tabpanel" aria-labelledby="external-tab">
            <div class="py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-success"><i class="fas fa-external-link-alt me-2"></i>External Complaints</h6>
            </div>
            <?php if(empty($data['external_complaints'])): ?>
            <div class="text-center py-5 text-muted"><i class="fas fa-folder-open fa-4x mb-3 opacity-25"></i><h5>No External Complaints Found</h5></div>
            <?php else: ?>
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle" style="font-size:.88rem;">
                    <thead class="table-light text-uppercase text-muted" style="font-size:.75rem;">
                        <tr><th class="ps-3">Complaint No</th><th>Date</th><th>Applicant</th><th>Subject</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['external_complaints'] as $complaint): ?>
                        <tr>
                            <td class="ps-3"><span class="fw-bold text-success"><?php echo htmlspecialchars($complaint->complaint_no); ?></span></td>
                            <td><span class="text-muted small"><?php echo htmlspecialchars($complaint->date); ?></span></td>
                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                            <td><?php echo htmlspecialchars(mb_strimwidth($complaint->subject,0,40,'…')); ?></td>
                            <td><?php
                                if($complaint->status=='Reply Received') echo '<span class="status-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="fas fa-reply me-1"></i>Reply Received</span>';
                                else echo '<span class="status-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">'.htmlspecialchars($complaint->status).'</span>';
                            ?></td>
                            <td class="d-flex gap-2">
                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 shadow-sm"><i class="fas fa-eye me-1"></i>View</a>
                                <?php if($complaint->status=='Reply Received'): ?>
                                <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-sm" onclick="openReplyModal(<?php echo $complaint->id; ?>,'<?php echo htmlspecialchars($complaint->complaint_no,ENT_QUOTES); ?>')"><i class="fas fa-reply me-1"></i>Reply</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <!-- All Complaints Tab -->
        <div class="tab-pane fade" id="all-complaints" role="tabpanel" aria-labelledby="all-tab">
            <div class="py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-info"><i class="fas fa-list me-2"></i>All System Complaints</h6>
            </div>
            <?php if(empty($data['all_complaints'])): ?>
            <div class="text-center py-5 text-muted"><i class="fas fa-folder-open fa-4x mb-3 opacity-25"></i><h5>No Complaints Found</h5></div>
            <?php else: ?>
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle" style="font-size:.88rem;">
                    <thead class="table-light text-uppercase text-muted" style="font-size:.75rem;">
                        <tr><th class="ps-3">Complaint No</th><th>Date</th><th>Applicant</th><th>Subject</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['all_complaints'] as $complaint): ?>
                        <tr>
                            <td class="ps-3"><span class="fw-bold text-primary"><?php echo htmlspecialchars($complaint->complaint_no); ?></span></td>
                            <td><span class="text-muted small"><?php echo htmlspecialchars($complaint->date); ?></span></td>
                            <td><?php echo htmlspecialchars($complaint->applicant_name); ?></td>
                            <td><?php echo htmlspecialchars(mb_strimwidth($complaint->subject,0,40,'…')); ?></td>
                            <td><?php
                                $st = $complaint->status;
                                if(strpos($st,'Reject')!==false)        echo '<span class="status-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">'.htmlspecialchars($st).'</span>';
                                elseif($st=='Reply Received')            echo '<span class="status-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25"><i class="fas fa-reply me-1"></i>Reply Received</span>';
                                elseif(strpos($st,'Department')!==false) echo '<span class="status-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">'.htmlspecialchars($st).'</span>';
                                elseif(strpos($st,'Approved')!==false)   echo '<span class="status-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">'.htmlspecialchars($st).'</span>';
                                else                                     echo '<span class="status-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">'.htmlspecialchars($st).'</span>';
                            ?></td>
                            <td class="d-flex gap-2">
                                <a href="<?php echo URLROOT; ?>/complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm"><i class="fas fa-eye me-1"></i>View</a>
                                <?php if($complaint->status=='Reply Received'): ?>
                                <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-sm" onclick="openReplyModal(<?php echo $complaint->id; ?>,'<?php echo htmlspecialchars($complaint->complaint_no,ENT_QUOTES); ?>')"><i class="fas fa-reply me-1"></i>Reply</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </div><!-- /tab-content -->
</div><!-- /card -->

</div><!-- /container-fluid -->


<!-- ══════════════════════════════════════════════════════════════
     MODALS (unchanged)
     ══════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="dispatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header rounded-top-4 d-flex justify-content-between align-items-center" style="background:linear-gradient(135deg,var(--primary-dark),var(--primary-color));padding:1rem 1.5rem;">
                <h5 class="modal-title fw-bold text-white mb-0"><i class="fas fa-paper-plane me-2"></i>Send to Department(s)</h5>
                <div class="ms-auto me-3" style="max-width:280px;width:100%;">
                    <input type="text" id="popupDeptSearch" class="form-control form-control-sm border-0 shadow-sm" placeholder="🔍 Search department..." style="border-radius:20px;padding:6px 15px;">
                </div>
                <button type="button" class="btn-close btn-close-white ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dispatchForm" method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 mb-4 rounded-3 border" style="background-color:#f8f9fa;">
                        <div>
                            <p class="text-muted small mb-1">Complaint Number: <span id="modalComplaintNo" class="fw-bold fs-6" style="color:var(--primary-color);"></span></p>
                            <p class="text-muted small mb-0">Select one or more departments to forward this complaint letter to:</p>
                        </div>
                        <div class="bg-white p-2 rounded-3 border shadow-sm" style="min-width:320px;max-width:420px;flex:1;">
                            <label for="dispatchAttachments" class="form-label fw-bold small text-primary mb-1 d-flex align-items-center"><i class="fas fa-paperclip me-2"></i>Attach Files / Documents (Optional)</label>
                            <input type="file" form="dispatchForm" name="attachments[]" id="dispatchAttachments" multiple class="form-control form-control-sm border" style="font-size:.82rem;">
                            <div class="form-text mt-1" style="font-size:.72rem;">You can select one or more files to attach.</div>
                        </div>
                    </div>
                    <?php
                    $offices=[]; $ministries=[]; $depts=[];
                    foreach($data['departments'] as $dept){
                        if($dept->type==='office') $offices[]=$dept;
                        elseif($dept->type==='ministries') $ministries[]=$dept;
                        else $depts[]=$dept;
                    }
                    ?>
                    <div class="row g-3">
                        <?php foreach([['Offices','building',$offices,'primary'],['Ministries','landmark',$ministries,'dark'],['Departments','sitemap',$depts,'warning']] as [$title,$icon,$list,$col]): ?>
                        <div class="col-md-4 <?php echo $title!=='Departments'?'border-end':''; ?>">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom text-<?php echo $col; ?>"><i class="fas fa-<?php echo $icon; ?> me-2"></i><?php echo $title; ?></h6>
                            <div class="d-flex flex-column gap-2" style="max-height:380px;overflow-y:auto;padding-right:6px;">
                                <?php if(empty($list)): ?><span class="text-muted small">No <?php echo strtolower($title); ?> defined</span>
                                <?php else: foreach($list as $dept): ?>
                                <div class="border rounded-3 p-3 dept-check-item d-flex align-items-start gap-2" style="cursor:pointer;transition:all .2s;">
                                    <input class="form-check-input dept-checkbox mt-1" type="checkbox" name="department_ids[]" value="<?php echo $dept->id; ?>" id="dept_<?php echo $dept->id; ?>" style="flex-shrink:0;margin-left:0;">
                                    <label class="form-check-label w-100" for="dept_<?php echo $dept->id; ?>" style="cursor:pointer;margin:0;line-height:1.4;"><span class="small fw-semibold"><?php echo htmlspecialchars($dept->name); ?></span></label>
                                </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="deptError" class="text-danger small mt-3 d-none"><i class="fas fa-exclamation-circle me-1"></i>Please select at least one department.</div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold" onclick="submitDispatch()"><i class="fas fa-paper-plane me-1"></i>Send Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white px-4 py-3" style="background:linear-gradient(135deg,#0284c7,#0369a1);">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0"><i class="fas fa-reply"></i><span>Department Reply — <span id="replyModalComplaintNo" class="text-warning"></span></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyUpdateForm" method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="p-3 mb-3 rounded-3 border bg-light d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size:.75rem;">Replying Department</small>
                            <span id="replyDeptName" class="fw-bold text-dark fs-6">Loading...</span>
                        </div>
                        <div class="text-sm-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size:.75rem;">Received Date &amp; Time</small>
                            <span id="replyDate" class="text-muted small fw-semibold"></span>
                            <span id="replyReplier" class="text-muted small"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Attached Document / File</label>
                        <div id="modalCurrentAttachment"><span class="text-muted small">Loading attachment...</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="modalReplyText" class="form-label fw-bold text-secondary small text-uppercase mb-1">Reply Message / Remarks <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3 border" id="modalReplyText" name="reply_text" rows="5" placeholder="Enter or update department reply remarks..." required style="resize:vertical;"></textarea>
                    </div>
                    <div class="p-3 rounded-3 border" style="background:#f8fafc;">
                        <label for="modalReplyAttachment" class="form-label fw-bold small text-primary mb-1 d-flex align-items-center"><i class="fas fa-paperclip me-2"></i>Update or Attach New File (Optional)</label>
                        <input type="file" class="form-control form-control-sm border" id="modalReplyAttachment" name="attachment" style="background:#fff;">
                        <div class="form-text small text-muted mt-1">Upload a PDF, document, or image to replace or add attachment to this reply.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-semibold shadow-sm"><i class="fas fa-save me-1"></i>Save &amp; Update Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.dept-check-item:has(.dept-checkbox:checked){ background:var(--primary-50)!important; border-color:var(--primary-color)!important; box-shadow:0 0 0 2px rgba(45,145,80,.18); }
.dept-check-item:hover{ background:var(--primary-50)!important; border-color:var(--primary-300)!important; }
</style>

<script>
/* ── PHP → JS data ───────────────────────────────── */
const trendLabels   = <?php echo json_encode($trend_labels); ?>;
const trendTotal    = <?php echo json_encode($trend_total); ?>;
const trendApproved = <?php echo json_encode($trend_approved); ?>;
const trendRejected = <?php echo json_encode($trend_rejected); ?>;
const trendPending  = <?php echo json_encode($trend_pending); ?>;

const doughnutLabels = <?php echo json_encode($doughnut_labels); ?>;
const doughnutData   = <?php echo json_encode($doughnut_values); ?>;

const catLabels = <?php echo json_encode($cat_labels); ?>;
const catVals   = <?php echo json_encode($cat_vals); ?>;

const deptLabels   = <?php echo json_encode($dept_labels); ?>;
const deptReplied  = <?php echo json_encode($dept_replied); ?>;
const deptApproved = <?php echo json_encode($dept_approved); ?>;
const deptPending  = <?php echo json_encode($dept_pending); ?>;
const deptRejected = <?php echo json_encode($dept_rejected); ?>;

const statusLabels = <?php echo json_encode($status_labels); ?>;
const statusVals   = <?php echo json_encode($status_vals); ?>;

/* ── Chart.js global defaults ───────────────────── */
Chart.defaults.font.family = "'Inter','Outfit',sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.plugins.legend.labels.padding   = 16;
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.pointStyle = 'circle';

/* ── Interactive Filter by Status ───────────────── */
function applyStatusFilter(statusLabel){
    if(!statusLabel) return;
    const url = new URL(window.location.href);
    const currentStatus = (url.searchParams.get('status') || '').trim();
    if(currentStatus.toLowerCase() === statusLabel.toLowerCase()){
        url.searchParams.delete('status');
    } else {
        url.searchParams.set('status', statusLabel);
    }
    url.hash = 'dashboardTabs';
    window.location.href = url.toString();
}

// Auto-switch to All System tab if My Submitted is empty when filtered
document.addEventListener('DOMContentLoaded', function(){
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('status')){
        const myCount = <?php echo count($data['user_complaints']); ?>;
        const allCount = <?php echo count($data['all_complaints']); ?>;
        if(myCount === 0 && allCount > 0){
            const allTabBtn = document.getElementById('all-tab');
            if(allTabBtn && typeof bootstrap !== 'undefined'){
                new bootstrap.Tab(allTabBtn).show();
            }
        }
    }
});

/* ── Trend Chart ─────────────────────────────────── */
let trendChart = null;
function buildTrendChart(type='bar'){
    const ctx = document.getElementById('trendChart');
    if(!ctx) return;
    if(trendChart) trendChart.destroy();
    trendChart = new Chart(ctx, {
        type: type,
        data: {
            labels: trendLabels,
            datasets: [
                { label:'Total',    data:trendTotal,    backgroundColor:'rgba(30,60,114,.7)',  borderColor:'#1e3c72', borderWidth:1.5, borderRadius:6, pointRadius:4, fill:false, tension:.4 },
                { label:'Approved', data:trendApproved, backgroundColor:'rgba(34,197,94,.7)', borderColor:'#22c55e', borderWidth:1.5, borderRadius:6, pointRadius:4, fill:false, tension:.4 },
                { label:'Pending',  data:trendPending,  backgroundColor:'rgba(245,158,11,.7)',borderColor:'#f59e0b', borderWidth:1.5, borderRadius:6, pointRadius:4, fill:false, tension:.4 },
                { label:'Rejected', data:trendRejected, backgroundColor:'rgba(239,68,68,.7)', borderColor:'#ef4444', borderWidth:1.5, borderRadius:6, pointRadius:4, fill:false, tension:.4 },
            ]
        },
        options:{
            responsive:true, maintainAspectRatio:true,
            interaction:{ mode:'nearest', intersect:true },
            plugins:{ legend:{ position:'top' }, tooltip:{ cornerRadius:10, callbacks:{ afterLabel: ctx => ctx.dataset.label !== 'Total' ? 'Click to filter by ' + ctx.dataset.label : '' } } },
            scales:{ y:{ beginAtZero:true, grid:{ color:'rgba(0,0,0,.05)' }, ticks:{ stepSize:1 } }, x:{ grid:{ display:false } } },
            onClick: (e, elements, chart) => {
                if (elements.length > 0) {
                    const el = elements[0];
                    const dataset = chart.data.datasets[el.datasetIndex];
                    if (dataset && dataset.label && dataset.label !== 'Total') {
                        applyStatusFilter(dataset.label);
                    }
                }
            },
            onHover: (e, elements) => {
                e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            }
        }
    });
}
function toggleTrendType(t){ buildTrendChart(t); }
if(document.getElementById('trendChart') && trendLabels.length>0) buildTrendChart('bar');

/* ── Doughnut Chart ──────────────────────────────── */
(function(){
    const ctx = document.getElementById('statusDoughnut');
    if(!ctx) return;
    new Chart(ctx,{
        type:'doughnut',
        data:{
            labels: doughnutLabels,
            datasets:[{
                data: doughnutData,
                backgroundColor:['#f59e0b','#22c55e','#ef4444','#3b82f6','#06b6d4'],
                borderWidth:2, borderColor:'#fff', hoverOffset:8
            }]
        },
        options:{
            responsive:true, maintainAspectRatio:true, cutout:'65%',
            plugins:{
                legend:{ display:false },
                tooltip:{ cornerRadius:10,
                    callbacks:{
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} (${Math.round(ctx.parsed/ctx.dataset.data.reduce((a,b)=>a+b,0)*100)}%)`,
                        afterLabel: () => 'Click to filter and view details'
                    }
                }
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const i = elements[0].index;
                    applyStatusFilter(doughnutLabels[i]);
                }
            },
            onHover: (e, elements) => {
                e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            }
        }
    });
})();

/* ── Category Horizontal Bar ─────────────────────── */
(function(){
    const ctx = document.getElementById('categoryChart');
    if(!ctx || catLabels.length===0) return;
    const palette = ['#2a5298','#22c55e','#f59e0b','#ef4444','#06b6d4','#8b5cf6','#ec4899','#14b8a6'];
    new Chart(ctx,{
        type:'bar',
        data:{
            labels: catLabels,
            datasets:[{
                label:'Complaints',
                data: catVals,
                backgroundColor: catLabels.map((_,i)=>palette[i%palette.length]+'cc'),
                borderColor:     catLabels.map((_,i)=>palette[i%palette.length]),
                borderWidth:1.5, borderRadius:8
            }]
        },
        options:{
            indexAxis:'y', responsive:true, maintainAspectRatio:true,
            plugins:{ legend:{ display:false }, tooltip:{ cornerRadius:10 } },
            scales:{ x:{ beginAtZero:true, grid:{ color:'rgba(0,0,0,.05)' }, ticks:{ stepSize:1 } }, y:{ grid:{ display:false } } }
        }
    });
})();

/* ── Department-wise Stacked Bar ─────────────────── */
(function(){
    const ctx = document.getElementById('deptChart');
    if(!ctx || deptLabels.length === 0) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: deptLabels,
            datasets: [
                {
                    label: 'Replied',
                    data: deptReplied,
                    backgroundColor: '#10b981',
                    borderRadius: 4
                },
                {
                    label: 'Approved / Sent',
                    data: deptApproved,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                },
                {
                    label: 'Pending Review',
                    data: deptPending,
                    backgroundColor: '#f59e0b',
                    borderRadius: 4
                },
                {
                    label: 'Rejected',
                    data: deptRejected,
                    backgroundColor: '#ef4444',
                    borderRadius: 4
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.05)' },
                    ticks: { stepSize: 1 }
                },
                y: {
                    stacked: true,
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 10, padding: 10 } },
                tooltip: { cornerRadius: 8, callbacks: { afterLabel: () => 'Click to filter by this status' } }
            },
            onClick: (e, elements, chart) => {
                if (elements.length > 0) {
                    const el = elements[0];
                    const dataset = chart.data.datasets[el.datasetIndex];
                    if (dataset && dataset.label) {
                        applyStatusFilter(dataset.label);
                    }
                }
            },
            onHover: (e, elements) => {
                e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            }
        }
    });
})();

/* ── Detailed Status-wise Chart ──────────────────── */
(function(){
    const ctx = document.getElementById('detailedStatusChart');
    if(!ctx || statusLabels.length === 0) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusVals,
                backgroundColor: [
                    '#94a3b8', // Draft
                    '#f59e0b', // Pending CC
                    '#0284c7', // Pending AO
                    '#6366f1', // Pending GS
                    '#22c55e', // Approved by GS
                    '#1e3c72', // Sent to Dept
                    '#0d9488', // Reply Received
                    '#ef4444'  // Rejected
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => {
                            const sum = ctx.dataset.data.reduce((a,b)=>a+b, 0);
                            const pct = sum > 0 ? Math.round((ctx.parsed / sum) * 100) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                        },
                        afterLabel: () => 'Click to filter and view details'
                    }
                }
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const i = elements[0].index;
                    applyStatusFilter(statusLabels[i]);
                }
            },
            onHover: (e, elements) => {
                e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            }
        }
    });
})();

/* ── Modal helpers ───────────────────────────────── */
function openReplyModal(complaintId, complaintNo){
    document.getElementById('replyModalComplaintNo').textContent = complaintNo;
    document.getElementById('replyUpdateForm').action = '<?php echo URLROOT; ?>/complaints/updateReply/' + complaintId;
    document.getElementById('modalReplyText').value = 'Loading...';
    document.getElementById('replyDeptName').textContent = 'Loading...';
    document.getElementById('replyDate').textContent = '';
    document.getElementById('replyReplier').textContent = '';
    document.getElementById('modalCurrentAttachment').innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2"></div> Loading...';
    const fi = document.getElementById('modalReplyAttachment');
    if(fi) fi.value='';
    new bootstrap.Modal(document.getElementById('replyModal')).show();
    fetch('<?php echo URLROOT; ?>/complaints/getReply/'+complaintId)
        .then(r=>r.json())
        .then(data=>{
            if(data.success&&data.reply){
                const r=data.reply;
                document.getElementById('modalReplyText').value=r.reply_text||'';
                document.getElementById('replyDeptName').textContent=r.department_name||'N/A';
                document.getElementById('replyDate').textContent=r.created_at?new Date(r.created_at).toLocaleString():'';
                document.getElementById('replyReplier').textContent=r.replier_name?' ('+r.replier_name+')':'';
                if(r.attachment_path){
                    const dlUrl='<?php echo URLROOT; ?>/'+r.attachment_path;
                    document.getElementById('modalCurrentAttachment').innerHTML=`<div class="alert alert-light border d-flex align-items-center justify-content-between p-2 mb-0 rounded-3"><div class="d-flex align-items-center text-truncate me-2"><i class="fas fa-paperclip text-primary me-2 fs-5"></i><span class="fw-semibold text-truncate small">${r.attachment_name||'Attachment'}</span></div><a href="${dlUrl}" target="_blank" download class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-shrink-0"><i class="fas fa-download me-1"></i>Download</a></div>`;
                } else {
                    document.getElementById('modalCurrentAttachment').innerHTML='<span class="text-muted small">No attachment uploaded yet.</span>';
                }
            } else {
                document.getElementById('modalReplyText').value='';
                document.getElementById('replyDeptName').textContent='Not specified';
                document.getElementById('modalCurrentAttachment').innerHTML='<span class="text-muted small">No reply details recorded yet.</span>';
            }
        }).catch(()=>{
            document.getElementById('modalReplyText').value='';
            document.getElementById('modalCurrentAttachment').innerHTML='<span class="text-danger small">Failed to load reply data.</span>';
        });
}

function openDispatchModal(complaintId, complaintNo){
    document.getElementById('modalComplaintNo').textContent = complaintNo;
    document.getElementById('dispatchForm').action = '<?php echo URLROOT; ?>/complaints/dispatch/'+complaintId;
    document.querySelectorAll('.dept-checkbox').forEach(cb=>cb.checked=false);
    document.getElementById('deptError').classList.add('d-none');
    const att=document.getElementById('dispatchAttachments'); if(att) att.value='';
    const s=document.getElementById('popupDeptSearch');
    if(s){ s.value=''; document.querySelectorAll('#dispatchModal .dept-check-item').forEach(i=>i.style.setProperty('display','flex','important')); }
    new bootstrap.Modal(document.getElementById('dispatchModal')).show();
}
function submitDispatch(){
    const checked=document.querySelectorAll('.dept-checkbox:checked');
    if(checked.length===0){ document.getElementById('deptError').classList.remove('d-none'); return; }
    document.getElementById('deptError').classList.add('d-none');
    document.getElementById('dispatchForm').submit();
}
function filterDepts(){
    const q=this.value.toLowerCase().trim();
    document.querySelectorAll('#dispatchModal .dept-check-item').forEach(item=>{
        const lbl=item.querySelector('.form-check-label');
        if(lbl) item.style.setProperty('display',lbl.textContent.toLowerCase().includes(q)?'flex':'none','important');
    });
}
(function bindSearch(){
    const s=document.getElementById('popupDeptSearch');
    if(s){s.removeEventListener('input',filterDepts);s.addEventListener('input',filterDepts);}
    else document.addEventListener('DOMContentLoaded',bindSearch);
})();
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
