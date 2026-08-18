<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="container">
    <div style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 50px;">
        <div style="width: 100%; max-width: 850px;">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2>Check Complaint Status</h2>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Enter your Reference Number, NIC or Mobile Number below.</p>
                    </div>
                </div>
                
                <form action="<?php echo URLROOT; ?>/publiccomplaint/status" method="POST">
                    <div class="form-group">
                        <label for="complaint_no" class="form-label">Reference Number (e.g. COMP-XXXX)</label>
                        <input type="text" name="complaint_no" class="form-control" placeholder="Enter Reference Number (Optional if searching by NIC)" value="<?php echo isset($data['ref']) ? htmlspecialchars($data['ref']) : ''; ?>">
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <label for="nic_or_mobile" class="form-label">NIC or Mobile Number</label>
                        <input type="text" name="nic_or_mobile" class="form-control" placeholder="Enter your NIC or Mobile Number" value="<?php echo isset($data['nic_or_mobile']) ? htmlspecialchars($data['nic_or_mobile']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-search"></i> Check Status
                    </button>
                </form>

                <?php if(!empty($data['complaints'])): ?>
                    <hr style="border: 0; border-top: 1px solid var(--panel-border); margin: 30px 0;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                        <h3 style="margin: 0; color: var(--text-primary); display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-list-alt" style="color: var(--primary-color);"></i> 
                            Found Complaints (<span id="resultsCount"><?php echo count($data['complaints']); ?></span>)
                        </h3>

                        <?php if(count($data['complaints']) > 1): ?>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                            <input type="text" id="filterInput" class="form-control" placeholder="Search in results..." style="width: 220px; font-size: 0.9rem; padding: 6px 12px;">
                            <select id="statusFilterSelect" class="form-control" style="width: 140px; font-size: 0.9rem; padding: 6px 12px;">
                                <option value="">All Statuses</option>
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="in progress">In Progress</option>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div id="complaintsList">
                        <?php foreach($data['complaints'] as $complaint): ?>
                            <div class="card complaint-result-card" 
                                 data-status="<?php echo strtolower(htmlspecialchars($complaint->status)); ?>"
                                 style="margin-bottom: 25px; border: 1px solid var(--panel-border); padding: 20px; background: var(--card-bg, #ffffff); border-radius: 8px;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                                    <div>
                                        <h4 style="margin: 0 0 5px 0; color: var(--primary-color, #2d9150); font-weight: 700;">
                                            <?php echo htmlspecialchars($complaint->complaint_no); ?>
                                        </h4>
                                        <h5 style="margin: 0; color: var(--text-color, #333); font-weight: 600; font-size: 1.05rem;">
                                            <?php echo htmlspecialchars($complaint->subject); ?>
                                        </h5>
                                    </div>
                                    <div>
                                        <span class="badge status-<?php echo strtolower($complaint->status); ?>" style="font-size: 0.85rem; padding: 6px 12px; border-radius: 12px;">
                                            <?php echo htmlspecialchars($complaint->status); ?>
                                        </span>
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 0.9rem; background: var(--bg-hover, #f8fafc); padding: 12px 15px; border-radius: 6px; margin-bottom: 15px;">
                                    <p style="margin: 0;"><strong>Date Filed:</strong> <?php echo htmlspecialchars($complaint->date); ?></p>
                                    <?php if(!empty($complaint->category_name)): ?>
                                        <p style="margin: 0;"><strong>Category:</strong> <?php echo htmlspecialchars($complaint->category_name); ?></p>
                                    <?php endif; ?>
                                    <?php if(!empty($complaint->applicant_name)): ?>
                                        <p style="margin: 0;"><strong>Applicant:</strong> <?php echo htmlspecialchars($complaint->applicant_name); ?></p>
                                    <?php endif; ?>
                                    <?php if(!empty($complaint->department_name)): ?>
                                        <p style="margin: 0;"><strong>Department:</strong> <?php echo htmlspecialchars($complaint->department_name); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if(!empty($complaint->description)): ?>
                                    <div style="margin-bottom: 15px; font-size: 0.9rem;">
                                        <strong>Description:</strong>
                                        <p style="margin: 4px 0 0 0; color: var(--text-secondary, #555);"><?php echo nl2br(htmlspecialchars($complaint->description)); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($complaint->attachments)): ?>
                                    <div style="margin-bottom: 15px;">
                                        <strong style="font-size: 0.9rem;">Attachments:</strong>
                                        <ul style="list-style-type: none; padding-left: 0; margin: 5px 0 0 0;">
                                            <?php foreach($complaint->attachments as $attachment): ?>
                                                <li style="margin-bottom: 5px;">
                                                    <a href="<?php echo URLROOT; ?>/<?php echo $attachment->file_path; ?>" target="_blank" style="text-decoration: none; color: var(--primary-color, #2d9150); font-size: 0.9rem;">
                                                        <i class="fas fa-paperclip"></i> <?php echo htmlspecialchars($attachment->file_name); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <div class="timeline" style="margin-top: 15px; border-top: 1px dashed var(--panel-border); padding-top: 15px;">
                                    <div class="timeline-item">
                                        <div class="timeline-dot active"></div>
                                        <div class="timeline-content">
                                            <strong>Current Status: <?php echo htmlspecialchars($complaint->status); ?></strong>
                                            <div class="timeline-time">As of today</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="noResultsMsg" style="display: none; padding: 20px; text-align: center; color: var(--text-secondary);">
                        <i class="fas fa-search me-2"></i> No complaints match your filter criteria.
                    </div>

                    <?php if(count($data['complaints']) > 1): ?>
                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const filterInput = document.getElementById('filterInput');
                        const statusSelect = document.getElementById('statusFilterSelect');
                        const cards = document.querySelectorAll('.complaint-result-card');
                        const resultsCount = document.getElementById('resultsCount');
                        const noResultsMsg = document.getElementById('noResultsMsg');

                        function filterCards() {
                            const query = filterInput ? filterInput.value.toLowerCase().trim() : '';
                            const selectedStatus = statusSelect ? statusSelect.value.toLowerCase().trim() : '';
                            let visibleCount = 0;

                            cards.forEach(card => {
                                const cardText = card.textContent.toLowerCase();
                                const status = card.getAttribute('data-status') || '';

                                const matchesQuery = !query || cardText.includes(query);
                                const matchesStatus = !selectedStatus || status.includes(selectedStatus);

                                if (matchesQuery && matchesStatus) {
                                    card.style.display = 'block';
                                    visibleCount++;
                                } else {
                                    card.style.display = 'none';
                                }
                            });

                            if (resultsCount) {
                                resultsCount.textContent = visibleCount;
                            }

                            if (noResultsMsg) {
                                noResultsMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
                            }
                        }

                        if (filterInput) filterInput.addEventListener('input', filterCards);
                        if (statusSelect) statusSelect.addEventListener('change', filterCards);
                    });
                    </script>
                    <?php endif; ?>

                <?php elseif(!empty($data['err'])): ?>
                    <hr style="border: 0; border-top: 1px solid var(--panel-border); margin: 30px 0;">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($data['err']); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/layout/footer.php'; ?>
