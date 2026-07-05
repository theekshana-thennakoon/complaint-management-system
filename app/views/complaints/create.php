<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Management</div>
        <a href="<?php echo URLROOT; ?>/complaints" class="sidebar-menu-item">
            <i class="fas fa-list"></i> All Complaints
        </a>
        <a href="<?php echo URLROOT; ?>/complaints/create" class="sidebar-menu-item active">
            <i class="fas fa-plus"></i> New Complaint
        </a>
        <a href="<?php echo URLROOT; ?>/complaints/sent" class="sidebar-menu-item">
            <i class="fas fa-paper-plane"></i> Sent to Departments
        </a>
        

    </aside>

    <main class="dashboard-content">
        <a href="<?php echo URLROOT; ?>/complaints" class="btn btn-secondary" style="margin-bottom: 20px;">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="card" style="max-width: 800px; overflow: visible;">
            <div class="card-header">
                <h3>Add New Complaint (Internal)</h3>
            </div>
            
            <form action="<?php echo URLROOT; ?>/complaints/create" method="POST">
                
                <?php if(!empty($data['err'])) : ?>
                    <div class="alert alert-danger" style="margin-top: 15px;"><?php echo $data['err']; ?></div>
                <?php endif; ?>

                <h4 style="margin: 20px 0 15px; color: var(--primary-color);">Applicant Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name" class="form-label">Applicant Name *</label>
                        <input type="text" name="applicant_name" class="form-control" value="<?php echo $data['applicant_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nic" class="form-label">NIC *</label>
                        <input type="text" name="nic" class="form-control" value="<?php echo $data['nic']; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile" class="form-label">Contact Number (Mobile) *</label>
                        <input type="text" name="mobile" class="form-control" value="<?php echo $data['mobile']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2"><?php echo $data['address']; ?></textarea>
                </div>

                <h4 style="margin: 20px 0 15px; color: var(--primary-color);">Complaint Details</h4>
                <div class="form-group">
                    <label for="subject" class="form-label">Subject *</label>
                    <input type="text" name="subject" class="form-control" value="<?php echo $data['subject']; ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category *</label>
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php foreach($data['categories'] as $category) : ?>
                                <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="forward_department_id" class="form-label">Forward To Department *</label>
                        
                        <div class="custom-select-wrapper" style="position: relative;">
                            <!-- Fake Select Trigger -->
                            <div id="customSelectTrigger" class="form-control d-flex justify-content-between align-items-center" tabindex="0" style="cursor: pointer; min-height: 48px; position: relative;">
                                <span id="customSelectText" class="text-muted">Select Department</span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                            </div>
                            
                            <!-- Hidden Select Input for form submission -->
                            <input type="hidden" name="forward_department_id" id="forward_department_id" value="<?php echo $data['forward_department_id']; ?>">
                            
                            <!-- Dropdown Menu -->
                            <div id="customSelectDropdown" class="card shadow border-0 p-2 d-none" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; margin-top: 5px; max-height: 300px; display: flex; flex-direction: column; background: var(--panel-bg); border: 1px solid var(--panel-border) !important; border-radius: var(--radius-md) !important; box-shadow: var(--shadow-lg) !important;">
                                <!-- Search Box inside Dropdown -->
                                <div class="p-1 mb-2">
                                    <input type="text" id="deptSearch" class="form-control form-control-sm" placeholder="🔍 Search department..." style="font-size: 0.85rem; padding: 6px 12px;">
                                </div>
                                <!-- Options List -->
                                <div id="customSelectOptions" style="overflow-y: auto; max-height: 200px; display: flex; flex-direction: column; gap: 2px;">
                                    <div class="custom-option text-muted" data-value="" style="cursor: pointer; font-size: 0.9rem;">Select Department</div>
                                    <?php foreach($data['departments'] as $department) : ?>
                                        <div class="custom-option" data-value="<?php echo $department->id; ?>" style="cursor: pointer; font-size: 0.9rem;">
                                            <?php echo htmlspecialchars($department->name); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                
                <h4 style="margin: 30px 0 15px; color: var(--primary-color);">Additional Details</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="detailsTable">
                        <thead>
                            <tr>
                                <th style="width: 10%;">අනු අංක</th>
                                <th style="width: 30%;">ලිපියේ අංකය</th>
                                <th style="width: 50%;">නම හා කාරණය</th>
                                <th style="width: 10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="detailsTbody">
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
                                <td><input type="text" name="detail_name[]" class="form-control"></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-secondary btn-sm" id="addRowBtn" style="margin-top: 10px;">
                        <i class="fas fa-plus"></i> Add Row
                    </button>
                </div>
                
                <input type="hidden" name="direct_forward" id="direct_forward" value="">

                <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <button type="submit" class="btn btn-primary" onclick="document.getElementById('direct_forward').value=''">
                        <i class="fas fa-save me-1"></i> Save &amp; Submit to CC
                    </button>
                    <div style="width: 1px; height: 36px; background: var(--gray-200);"></div>
                    <button type="submit" class="btn btn-warning" onclick="document.getElementById('direct_forward').value='ao'">
                        <i class="fas fa-user-tie me-1"></i> Forward Directly to AO
                    </button>
                    <button type="submit" class="btn btn-info" onclick="document.getElementById('direct_forward').value='gs'">
                        <i class="fas fa-landmark me-1"></i> Forward Directly to GS
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
.custom-option {
    padding: 8px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--text-primary);
    transition: background 0.15s ease, color 0.15s ease;
}
.custom-option:hover {
    background-color: var(--primary-50);
    color: var(--primary-color);
}
.custom-option.selected {
    background-color: var(--primary-color) !important;
    color: white !important;
}
#customSelectTrigger:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3.5px rgba(45,145,80,0.14);
    outline: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.custom-select-wrapper');
    if (!wrapper) return;
    
    const trigger = document.getElementById('customSelectTrigger');
    const dropdown = document.getElementById('customSelectDropdown');
    const searchInput = document.getElementById('deptSearch');
    const optionsContainer = document.getElementById('customSelectOptions');
    const hiddenInput = document.getElementById('forward_department_id');
    const selectText = document.getElementById('customSelectText');
    const options = Array.from(optionsContainer.querySelectorAll('.custom-option'));
    
    // Toggle dropdown
    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('d-none');
        if (!dropdown.classList.contains('d-none')) {
            searchInput.value = '';
            // reset options visibility
            options.forEach(opt => opt.style.display = 'block');
            searchInput.focus();
        }
    });
    
    // Stop propagation on dropdown clicks so it doesn't close when clicking search input
    dropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close dropdown on click outside
    document.addEventListener('click', function() {
        dropdown.classList.add('d-none');
    });

    // Handle trigger keyboard focusing & enter to open
    trigger.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
            e.preventDefault();
            dropdown.classList.remove('d-none');
            searchInput.focus();
        }
    });

    // Select option function
    function selectOption(optionEl) {
        options.forEach(opt => opt.classList.remove('selected'));
        optionEl.classList.add('selected');
        
        const val = optionEl.getAttribute('data-value');
        const text = optionEl.textContent.trim();
        
        hiddenInput.value = val;
        selectText.textContent = text;
        
        if (val === "") {
            selectText.classList.add('text-muted');
        } else {
            selectText.classList.remove('text-muted');
        }
        
        dropdown.classList.add('d-none');
    }

    // Set initial value
    const initialVal = hiddenInput.value;
    const matchedOpt = options.find(opt => opt.getAttribute('data-value') === initialVal);
    if (matchedOpt) {
        selectOption(matchedOpt);
    } else {
        const placeholderOpt = options.find(opt => opt.getAttribute('data-value') === "");
        if (placeholderOpt) selectOption(placeholderOpt);
    }
    
    // Bind click events to options
    optionsContainer.addEventListener('click', function(e) {
        const optionEl = e.target.closest('.custom-option');
        if (optionEl) {
            selectOption(optionEl);
        }
    });
    
    // Filter options as you type
    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase().trim();
        options.forEach(opt => {
            const val = opt.getAttribute('data-value');
            const text = opt.textContent.toLowerCase();
            if (val === "" || text.includes(query)) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        });
    });
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
