<?php
class DashboardController extends Controller {
    public function __construct(){
        if(!isLoggedIn()){
            redirect('auth');
        }
        $this->complaintModel = $this->model('Complaint');
    }

    public function index(){
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        $month = isset($_GET['month']) ? trim($_GET['month']) : ($date ? '' : date('Y-m'));
        $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
        $status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

        $user_complaints = $this->complaintModel->getComplaintsByUserId($_SESSION['user_id'], $month, $category_id, $date, $status_filter);
        $all_complaints = $this->complaintModel->getComplaints($month, $category_id, $date, $status_filter);
        $external_complaints = $this->complaintModel->getExternalComplaints($month, $category_id, $date, $status_filter);

        $status_summary = $this->complaintModel->getStatusSummary($_SESSION['user_id'], $month, $category_id, $date);
        $all_status_summary = $this->complaintModel->getStatusSummary(null, $month, $category_id, $date);

        // Category breakdown directly from current dataset for perfect filter sync
        $category_counts = [];
        foreach($all_complaints as $c) {
            $catName = !empty($c->category_name) ? $c->category_name : 'General / Other';
            $category_counts[$catName] = ($category_counts[$catName] ?? 0) + 1;
        }
        arsort($category_counts);

        // Department-wise breakdown & status distribution
        $department_counts = [];
        $dept_status_breakdown = [];
        foreach($all_complaints as $c) {
            $deptName = !empty($c->department_name) ? $c->department_name : 'General / Direct';
            if(!isset($dept_status_breakdown[$deptName])) {
                $dept_status_breakdown[$deptName] = [
                    'total' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'replied' => 0,
                    'rejected' => 0
                ];
            }
            $dept_status_breakdown[$deptName]['total']++;
            $stLower = strtolower($c->status ?? '');
            if(strpos($stLower, 'reply received') !== false || ($c->status ?? '') === 'Replied') {
                $dept_status_breakdown[$deptName]['replied']++;
            } elseif(strpos($stLower, 'approve') !== false || strpos($stLower, 'department') !== false) {
                $dept_status_breakdown[$deptName]['approved']++;
            } elseif(strpos($stLower, 'reject') !== false) {
                $dept_status_breakdown[$deptName]['rejected']++;
            } else {
                $dept_status_breakdown[$deptName]['pending']++;
            }
            $department_counts[$deptName] = ($department_counts[$deptName] ?? 0) + 1;
        }
        arsort($department_counts);
        uasort($dept_status_breakdown, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Detailed Status-wise distribution
        $detailed_status_counts = [
            'Draft' => 0,
            'Pending CC' => 0,
            'Pending AO' => 0,
            'Pending GS' => 0,
            'Approved by GS' => 0,
            'Sent to Dept' => 0,
            'Reply Received' => 0,
            'Rejected' => 0
        ];
        foreach($all_complaints as $c) {
            $st = $c->status ?? '';
            if($st === 'Draft') {
                $detailed_status_counts['Draft']++;
            } elseif(strpos($st, 'Pending CC') !== false) {
                $detailed_status_counts['Pending CC']++;
            } elseif(strpos($st, 'Approved by CC') !== false) {
                $detailed_status_counts['Pending AO']++;
            } elseif(strpos($st, 'Approved by AO') !== false) {
                $detailed_status_counts['Pending GS']++;
            } elseif($st === 'Approved by GS') {
                $detailed_status_counts['Approved by GS']++;
            } elseif($st === 'Sent to Department' || strpos(strtolower($st), 'sent to dept') !== false) {
                $detailed_status_counts['Sent to Dept']++;
            } elseif(strpos(strtolower($st), 'reply received') !== false || $st === 'Replied') {
                $detailed_status_counts['Reply Received']++;
            } elseif(strpos(strtolower($st), 'reject') !== false) {
                $detailed_status_counts['Rejected']++;
            } else {
                $detailed_status_counts['Draft']++;
            }
        }

        // Monthly trends from dataset
        $trend_data = [];
        foreach($all_complaints as $c) {
            $dateStr = !empty($c->date) ? $c->date : (!empty($c->created_at) ? $c->created_at : null);
            $m = $dateStr ? date('M Y', strtotime($dateStr)) : 'Unknown';
            $m_sort = $dateStr ? date('Y-m', strtotime($dateStr)) : '0000-00';
            if(!isset($trend_data[$m_sort])) {
                $trend_data[$m_sort] = [
                    'label' => $m,
                    'total' => 0,
                    'approved' => 0,
                    'pending' => 0,
                    'rejected' => 0
                ];
            }
            $trend_data[$m_sort]['total']++;
            $statusLower = strtolower($c->status ?? '');
            if(strpos($statusLower, 'approve') !== false || strpos($statusLower, 'department') !== false) {
                $trend_data[$m_sort]['approved']++;
            } elseif(strpos($statusLower, 'reject') !== false) {
                $trend_data[$m_sort]['rejected']++;
            } else {
                $trend_data[$m_sort]['pending']++;
            }
        }
        ksort($trend_data);

        // Rates
        $totalAll = $all_status_summary['total'] ?? 0;
        $approvedAll = $all_status_summary['approved_total'] ?? 0;
        $dispatchedAll = $all_status_summary['dispatched'] ?? 0;
        $repliesAll = $all_status_summary['reply_received'] ?? 0;
        $resolution_rate = $totalAll > 0 ? round(($approvedAll / $totalAll) * 100, 1) : 0;
        $reply_rate = $dispatchedAll > 0 ? round(($repliesAll / $dispatchedAll) * 100, 1) : 0;

        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'total'    => $status_summary['total'],
                'pending'  => $status_summary['pending_total'],
                'approved' => $status_summary['approved_total'],
                'rejected' => $status_summary['rejected_total'],
                'sent'     => $status_summary['dispatched'],
                'replied'  => $status_summary['reply_received'] ?? 0
            ],
            'status_summary' => $status_summary,
            'all_status_summary' => $all_status_summary,
            'category_counts' => $category_counts,
            'department_counts' => $department_counts,
            'dept_status_breakdown' => $dept_status_breakdown,
            'detailed_status_counts' => $detailed_status_counts,
            'trend_data' => array_values($trend_data),
            'resolution_rate' => $resolution_rate,
            'reply_rate' => $reply_rate,
            'user_complaints' => $user_complaints,
            'departments' => $this->complaintModel->getDepartments(),
            'month' => $month,
            'date' => $date,
            'status_filter' => $status_filter,
            'category_id' => $category_id,
            'categories' => $this->complaintModel->getCategories(),
            'all_complaints' => $all_complaints,
            'external_complaints' => $external_complaints
        ];
        $this->view('dashboard/index', $data);
    }
}
