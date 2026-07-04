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
        
        <div class="sidebar-heading">Reports</div>
        <a href="#" class="sidebar-menu-item">
            <i class="fas fa-chart-bar"></i> Statistics
        </a>
    </aside>

    <main class="dashboard-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="<?php echo URLROOT; ?>/complaints" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <?php 
                $badge_class = 'status-pending';
                if($data['complaint']->status == 'Approved') $badge_class = 'status-resolved';
                if($data['complaint']->status == 'Rejected') $badge_class = 'status-rejected';
                if($data['complaint']->status == 'Sent to CC') $badge_class = 'status-info-requested';
            ?>
            <div>
                <strong>Status:</strong> <span class="badge <?php echo $badge_class; ?>"><?php echo $data['complaint']->status; ?></span>
            </div>
        </div>

        <?php if($data['complaint']->status == 'Rejected by CC' && !empty($data['reject_log'])): ?>
            <div class="alert alert-danger shadow-sm border-danger border-start border-5 mb-4 rounded d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="alert-heading text-danger fw-bold mb-1"><i class="fas fa-exclamation-triangle"></i> Rejected by Chief Clerk</h5>
                    <p class="mb-0"><strong>Reason:</strong> <?php echo htmlspecialchars($data['reject_log']->remarks); ?></p>
                </div>
                <?php if ($data['complaint']->created_by == $_SESSION['user_id']): ?>
                <div>
                    <a href="<?php echo URLROOT; ?>/complaints/edit/<?php echo $data['complaint']->id; ?>" class="btn btn-danger">
                        <i class="fas fa-edit"></i> Edit & Resubmit
                    </a>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['rejections'])): ?>
            <div class="alert alert-warning mb-4 shadow-sm border-warning border-start border-5 rounded">
                <h6 class="alert-heading text-warning-emphasis fw-bold mb-2"><i class="fas fa-history"></i> Previous Rejection History</h6>
                <ul class="mb-0 small">
                    <?php foreach($data['rejections'] as $rej): ?>
                        <li><strong><?php echo date('Y-m-d h:i A', strtotime($rej->created_at)); ?>:</strong> <?php echo htmlspecialchars($rej->remarks); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3>Complaint Details: <?php echo $data['complaint']->complaint_no; ?></h3>
                <div>
                    <a href="<?php echo URLROOT; ?>/complaints/generate_pdf/<?php echo $data['complaint']->id; ?>" class="btn btn-accent btn-sm" target="_blank">
                        <i class="fas fa-file-pdf"></i> Generate PDF Letter
                    </a>
                </div>
            </div>

            <div class="form-row" style="margin-bottom: 30px;">
                <div style="flex: 1;">
                    <h4 style="color: var(--primary-color); margin-bottom: 10px;">Applicant Information</h4>
                    <table class="data-table">
                        <tr>
                            <th width="30%">Name</th>
                            <td><?php echo $data['complaint']->applicant_name; ?></td>
                        </tr>
                        <tr>
                            <th>NIC</th>
                            <td><?php echo $data['complaint']->nic; ?></td>
                        </tr>
                        <tr>
                            <th>Contact</th>
                            <td><?php echo $data['complaint']->mobile; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo nl2br($data['complaint']->address); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div style="flex: 1;">
                    <h4 style="color: var(--primary-color); margin-bottom: 10px;">Complaint Details</h4>
                    <table class="data-table">
                        <tr>
                            <th width="30%">Subject</th>
                            <td><?php echo $data['complaint']->subject; ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo $data['complaint']->category_name; ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo $data['complaint']->date; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <h4 style="color: var(--primary-color); margin-bottom: 10px;">Description</h4>
                <div style="background: var(--input-bg); border: 1px solid var(--panel-border); padding: 15px; border-radius: 8px;">
                    <?php echo nl2br($data['complaint']->description); ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Dynamic Letter Data</h3>
            </div>
            
            <form action="<?php echo URLROOT; ?>/complaints/add_detail/<?php echo $data['complaint']->id; ?>" method="POST" style="margin-bottom: 20px;">
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <input type="text" name="letter_no" class="form-control" placeholder="Letter Number" required>
                    </div>
                    <div class="form-group" style="flex: 2;">
                        <input type="text" name="subject" class="form-control" placeholder="Name & Subject" required>
                    </div>
                    <div class="form-group" style="width: 100px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Add Row</button>
                    </div>
                </div>
            </form>

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="30%">Letter Number</th>
                        <th width="45%">Name & Subject</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['details'])): ?>
                        <?php $i = 1; foreach($data['details'] as $detail): ?>
                            <tr>
                                <td><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $detail->letter_no; ?></td>
                                <td><?php echo !empty($detail->name) ? $detail->name : $detail->subject; ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/complaints/delete_detail/<?php echo $detail->id; ?>/<?php echo $data['complaint']->id; ?>" method="POST" style="display:inline;">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php $i++; endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No dynamic rows added yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
