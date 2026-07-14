<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">Management</div>
        
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
            
            <form action="<?php echo URLROOT; ?>/complaints/create" method="POST" enctype="multipart/form-data">
                
                <?php if(!empty($data['err'])) : ?>
                    <div class="alert alert-danger" style="margin-top: 15px;"><?php echo $data['err']; ?></div>
                <?php endif; ?>

                <h4 style="margin: 20px 0 15px; color: var(--primary-color);">Applicant Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="applicant_name" class="form-label">Applicant Name *</label>
                        <input type="text" name="applicant_name" id="applicant_name" class="form-control" value="<?php echo $data['applicant_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nic" class="form-label">NIC *</label>
                        <input type="text" name="nic" class="form-control" value="<?php echo $data['nic']; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobile" class="form-label">Contact Number (Mobile)</label>
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
                    <input type="text" name="subject" id="subject" class="form-control" value="<?php echo $data['subject']; ?>">
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
                        <label for="letter_type" class="form-label">Letter Type *</label>
                        <select name="letter_type" id="letter_type" class="form-control" required>
                            <option value="">Select Letter Type</option>
                            <option value="මහජන දින ලිපි" <?php echo (isset($data['letter_type']) && $data['letter_type'] == 'මහජන දින ලිපි') ? 'selected' : ''; ?>>මහජන දින ලිපි</option>
                            <option value="දෛනික ලිපි" <?php echo (isset($data['letter_type']) && $data['letter_type'] == 'දෛනික ලිපි') ? 'selected' : ''; ?>>දෛනික ලිපි</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="district" class="form-label">District *</label>
                        <select name="district" id="districtSelect" class="form-control" required>
                            <option value="">Select District</option>
                        </select>
                        <input type="hidden" id="userProvince" value="<?php echo htmlspecialchars($_SESSION['user_province'] ?? ''); ?>">
                        <input type="hidden" id="selectedDistrict" value="<?php echo htmlspecialchars($data['district'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-row">
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
                                <!-- Custom (typed) option – shown when no match found -->
                                <div id="deptCustomOption" class="custom-option d-none" data-custom="true" style="cursor: pointer; font-size: 0.9rem; border: 1px dashed var(--primary-color); background: var(--primary-50); color: var(--primary-color); margin: 0 0 4px;">
                                    <i class="fas fa-plus-circle" style="margin-right:6px;"></i><span id="deptCustomOptionText"></span>
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
                    <div class="form-group">
                        <label for="person" class="form-label">Forward To Person *</label>
                        
                        <div class="custom-person-select-wrapper" style="position: relative;">
                            <!-- Fake Select Trigger -->
                            <div id="customPersonSelectTrigger" class="form-control d-flex justify-content-between align-items-center" tabindex="0" style="cursor: pointer; min-height: 48px; position: relative;">
                                <span id="customPersonSelectText" class="text-muted">Select Person</span>
                                <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                            </div>
                            
                            <!-- Hidden Select Input for form submission -->
                            <input type="hidden" name="person" id="person" value="<?php echo $data['person']; ?>">
                            
                            <!-- Dropdown Menu -->
                            <div id="customPersonSelectDropdown" class="card shadow border-0 p-2 d-none" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; margin-top: 5px; max-height: 300px; display: flex; flex-direction: column; background: var(--panel-bg); border: 1px solid var(--panel-border) !important; border-radius: var(--radius-md) !important; box-shadow: var(--shadow-lg) !important;">
                                <!-- Search Box inside Dropdown -->
                                <div class="p-1 mb-2">
                                    <input type="text" id="personSearch" class="form-control form-control-sm" placeholder="🔍 Search person..." style="font-size: 0.85rem; padding: 6px 12px;">
                                </div>
                                <!-- Custom (typed) option – shown when no match found -->
                                <div id="personCustomOption" class="custom-person-option d-none" data-custom="true" style="cursor: pointer; font-size: 0.9rem; border: 1px dashed var(--primary-color); background: var(--primary-50); color: var(--primary-color); margin: 0 0 4px;">
                                    <i class="fas fa-plus-circle" style="margin-right:6px;"></i><span id="personCustomOptionText"></span>
                                </div>
                                <!-- Options List -->
                                <div id="customPersonSelectOptions" style="overflow-y: auto; max-height: 200px; display: flex; flex-direction: column; gap: 2px;">
                                    <div class="custom-person-option text-muted" data-value="" style="cursor: pointer; font-size: 0.9rem;">Select Person</div>
                                    <?php 
                                    $persons = [
                                        'ප්‍රධාන ලේකම්',
                                        'ලේකම්',
                                        'අධ්‍යක්ෂක',
                                        'කොමසාරිස්',
                                        'සභාපති',
                                        'නියෝජ්‍ය ප්‍රධාන ලේකම්',
                                        'ආණ්ඩුකාර ලේකම්'
                                    ];
                                    foreach($persons as $p) : ?>
                                        <div class="custom-person-option" data-value="<?php echo htmlspecialchars($p); ?>" style="cursor: pointer; font-size: 0.9rem;">
                                            <?php echo htmlspecialchars($p); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- Empty spacer group to align layout -->
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
                                <td><input type="text" name="detail_letter_no[]" id="firstDetailLetterNo" class="form-control"></td>
                                <td><input type="text" name="detail_name[]" id="firstDetailName" class="form-control"></td>
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

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">Attachments (Optional, multiple files allowed)</label>
                    <div class="advanced-dropzone" id="advancedDropzone">
                        <input type="file" name="attachments[]" id="attachments" class="d-none" style="display:none;" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <div class="dropzone-content">
                            <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                            <p style="margin-bottom: 10px; color: var(--text-secondary);">Drag & Drop files here or</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('attachments').click()">Browse Files</button>
                        </div>
                    </div>
                    <div id="filePreviewContainer" class="file-preview-container mt-3"></div>
                </div>
                
                <input type="hidden" name="direct_forward"   id="direct_forward"   value="">
                <input type="hidden" name="letter_intro"      id="letter_intro_hidden"  value="">
                <input type="hidden" name="letter_body"       id="letter_body_hidden"   value="">
                <input type="hidden" name="signatory_name"    id="signatory_name_hidden" value="">
                <input type="hidden" name="signatory_title"   id="signatory_title_hidden" value="">

                <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <button type="button" class="btn btn-primary" onclick="openLetterModal('')">
                        <i class="fas fa-eye me-1"></i> Preview &amp; Edit Letter — Submit to CC
                    </button>
                    <div style="width: 1px; height: 36px; background: var(--gray-200);"></div>
                    <button type="button" class="btn btn-warning" onclick="openLetterModal('ao')">
                        <i class="fas fa-user-tie me-1"></i> Preview &amp; Edit — Forward to AO
                    </button>
                    <button type="button" class="btn btn-info" onclick="openLetterModal('gs')">
                        <i class="fas fa-landmark me-1"></i> Preview &amp; Edit — Forward to GS
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- ═══════════════════════════════════════════════════════════════════
     LETTER PREVIEW & EDIT MODAL
     ═══════════════════════════════════════════════════════════════════ -->
<div id="letterModal" style="
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
    overflow-y: auto; padding: 20px;">
    <div style="
        background: var(--panel-bg); border-radius: var(--radius-lg);
        max-width: 860px; margin: 0 auto; box-shadow: var(--shadow-xl);
        overflow: hidden;">

        <!-- Modal Header -->
        <div style="
            background: var(--primary-color); color: white;
            padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4 style="margin:0; font-size:1.1rem;"><i class="fas fa-file-alt" style="margin-right:8px;"></i>Edit Letter Before Submission</h4>
                <p style="margin:4px 0 0; font-size:0.8rem; opacity:0.85;">Customise the letter content below. Changes apply to this complaint's letter only.</p>
            </div>
            <button type="button" onclick="closeLetterModal()" style="background:none;border:none;color:white;font-size:1.4rem;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <div style="padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

            <!-- LEFT: Editable Fields -->
            <div>
                <h5 style="color:var(--primary-color); margin: 0 0 16px; font-size:0.95rem; border-bottom: 1px solid var(--panel-border); padding-bottom:8px;">
                    <i class="fas fa-pen" style="margin-right:6px;"></i>Editable Sections
                </h5>

                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label" style="font-size:0.82rem; font-weight:600;">Opening Paragraph (paragraph 01)</label>
                    <textarea id="edit_letter_intro" class="form-control" rows="5" style="font-size:0.82rem; font-family:'Noto Sans Sinhala',sans-serif; line-height:1.7;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label" style="font-size:0.82rem; font-weight:600;">Body Paragraphs (paragraphs 02 &amp; 03)</label>
                    <textarea id="edit_letter_body" class="form-control" rows="8" style="font-size:0.82rem; font-family:'Noto Sans Sinhala',sans-serif; line-height:1.7;"></textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.82rem; font-weight:600;">Signatory Name</label>
                        <input type="text" id="edit_signatory_name" class="form-control" style="font-size:0.82rem;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size:0.82rem; font-weight:600;">Signatory Title</label>
                        <input type="text" id="edit_signatory_title" class="form-control" style="font-size:0.82rem;">
                    </div>
                </div>

                <button type="button" class="btn btn-secondary btn-sm" onclick="resetLetterDefaults()" style="margin-bottom:8px;">
                    <i class="fas fa-undo"></i> Reset to Defaults
                </button>
            </div>

            <!-- RIGHT: Live Preview -->
            <div>
                <h5 style="color:var(--primary-color); margin: 0 0 16px; font-size:0.95rem; border-bottom: 1px solid var(--panel-border); padding-bottom:8px;">
                    <i class="fas fa-eye" style="margin-right:6px;"></i>Live Preview
                </h5>
                <div id="letterPreviewPane" style="
                    border: 1px solid var(--panel-border); border-radius: var(--radius-md);
                    padding: 20px; background:#fff; color:#000;
                    font-family:'Noto Sans Sinhala',serif; font-size:11px; line-height:1.8;
                    max-height: 520px; overflow-y: auto;">
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 16px 24px; border-top: 1px solid var(--panel-border); display:flex; justify-content:flex-end; gap:10px; background: var(--bg-subtle);">
            <button type="button" class="btn btn-secondary" onclick="closeLetterModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" id="modalSubmitBtn" class="btn btn-primary" onclick="confirmAndSubmit()">
                <i class="fas fa-paper-plane"></i> <span id="modalSubmitLabel">Submit to CC</span>
            </button>
        </div>
    </div>
</div>


<style>
.custom-option, .custom-person-option {
    padding: 8px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--text-primary);
    transition: background 0.15s ease, color 0.15s ease;
}
.custom-option:hover, .custom-person-option:hover {
    background-color: var(--primary-50);
    color: var(--primary-color);
}
.custom-option.selected, .custom-person-option.selected {
    background-color: var(--primary-color) !important;
    color: white !important;
}
#customSelectTrigger:focus, #customPersonSelectTrigger:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3.5px rgba(45,145,80,0.14);
    outline: none;
}
.advanced-dropzone {
    border: 2px dashed var(--primary-color);
    border-radius: var(--radius-md);
    padding: 30px;
    text-align: center;
    background-color: var(--primary-50);
    transition: all 0.3s ease;
    cursor: pointer;
}
.advanced-dropzone.dragover {
    background-color: var(--primary-color);
    color: white;
}
.advanced-dropzone.dragover .dropzone-icon,
.advanced-dropzone.dragover p {
    color: white !important;
}
.dropzone-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}
.file-preview-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 15px;
    background: #fff;
    border: 1px solid var(--panel-border);
    border-radius: var(--radius-sm);
    margin-bottom: 10px;
}
.file-preview-info {
    display: flex;
    align-items: center;
    gap: 15px;
}
.file-icon {
    font-size: 1.5rem;
    color: var(--text-secondary);
}
.file-details h6 {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-primary);
}
.file-details small {
    color: var(--text-muted);
}
.btn-remove-file {
    color: #dc3545;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
}
.btn-remove-file:hover {
    color: #a71d2a;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Auto-fill first row "නම හා කාරණය" from Applicant Name + Subject ──────
    const applicantNameInput = document.getElementById('applicant_name');
    const subjectInput       = document.getElementById('subject');
    const firstDetailName    = document.getElementById('firstDetailName');

    let autoFillActive = true; // becomes false once user manually edits the cell

    function updateFirstDetail() {
        if (!autoFillActive) return;
        const name    = applicantNameInput ? applicantNameInput.value.trim() : '';
        const subject = subjectInput       ? subjectInput.value.trim()       : '';
        if (name || subject) {
            firstDetailName.value = name && subject ? name + ' - ' + subject
                                  : name || subject;
        }
    }

    if (applicantNameInput) applicantNameInput.addEventListener('input', updateFirstDetail);
    if (subjectInput)       subjectInput.addEventListener('input', updateFirstDetail);

    // If the user manually types in the cell, stop auto-syncing
    if (firstDetailName) {
        firstDetailName.addEventListener('input', function() {
            // Only mark as manual if the value no longer matches what auto-fill would produce
            const name    = applicantNameInput ? applicantNameInput.value.trim() : '';
            const subject = subjectInput       ? subjectInput.value.trim()       : '';
            const expected = name && subject ? name + ' - ' + subject : name || subject;
            if (this.value !== expected) {
                autoFillActive = false;
            }
        });
    }
    // ──────────────────────────────────────────────────────────────────────────

    const wrapper = document.querySelector('.custom-select-wrapper');
    if (wrapper) {
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
        
        // Filter options as you type + show "add new dept" option
        const deptCustomOption     = document.getElementById('deptCustomOption');
        const deptCustomOptionText = document.getElementById('deptCustomOptionText');
        // live options list (grows when new depts are added)
        let allDeptOptions = Array.from(optionsContainer.querySelectorAll('.custom-option'));

        function filterDeptOptions() {
            const query = searchInput.value.trim();
            const queryLower = query.toLowerCase();
            let anyVisible = false;

            allDeptOptions = Array.from(optionsContainer.querySelectorAll('.custom-option'));
            allDeptOptions.forEach(opt => {
                const val  = opt.getAttribute('data-value');
                const text = opt.textContent.toLowerCase();
                if (val === '' || text.includes(queryLower)) {
                    opt.style.display = 'block';
                    if (val !== '') anyVisible = true;
                } else {
                    opt.style.display = 'none';
                }
            });

            if (query.length > 0 && !anyVisible) {
                deptCustomOptionText.textContent = 'Add "' + query + '" as new department';
                deptCustomOption.classList.remove('d-none');
            } else {
                deptCustomOption.classList.add('d-none');
            }
        }

        searchInput.addEventListener('input', filterDeptOptions);

        // Click "Add new department" → AJAX insert then auto-select
        deptCustomOption.addEventListener('click', function(e) {
            e.stopPropagation();
            const newName = searchInput.value.trim();
            if (!newName) return;

            // Disable while saving
            deptCustomOption.style.opacity = '0.5';
            deptCustomOption.style.pointerEvents = 'none';
            deptCustomOptionText.textContent = 'Saving…';

            const formData = new FormData();
            formData.append('name', newName);

            fetch('<?php echo URLROOT; ?>/complaints/addDepartmentAjax', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Build and append a new option element
                    const newOpt = document.createElement('div');
                    newOpt.className = 'custom-option';
                    newOpt.setAttribute('data-value', res.id);
                    newOpt.style.cssText = 'cursor:pointer;font-size:0.9rem;';
                    newOpt.textContent = res.name;
                    optionsContainer.appendChild(newOpt);

                    // Select it
                    allDeptOptions = Array.from(optionsContainer.querySelectorAll('.custom-option'));
                    allDeptOptions.forEach(opt => opt.classList.remove('selected'));
                    newOpt.classList.add('selected');
                    hiddenInput.value = res.id;
                    selectText.textContent = res.name;
                    selectText.classList.remove('text-muted');

                    deptCustomOption.classList.add('d-none');
                    dropdown.classList.add('d-none');
                } else {
                    alert('Error: ' + (res.message || 'Could not add department'));
                }
            })
            .catch(() => alert('Network error. Please try again.'))
            .finally(() => {
                deptCustomOption.style.opacity = '';
                deptCustomOption.style.pointerEvents = '';
            });
        });
    }

    const personWrapper = document.querySelector('.custom-person-select-wrapper');
    if (personWrapper) {
        const trigger = document.getElementById('customPersonSelectTrigger');
        const dropdown = document.getElementById('customPersonSelectDropdown');
        const searchInput = document.getElementById('personSearch');
        const optionsContainer = document.getElementById('customPersonSelectOptions');
        const hiddenInput = document.getElementById('person');
        const selectText = document.getElementById('customPersonSelectText');
        const options = Array.from(optionsContainer.querySelectorAll('.custom-person-option'));
        
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
            const optionEl = e.target.closest('.custom-person-option');
            if (optionEl) {
                selectOption(optionEl);
            }
        });
        
        // Filter options as you type + show "use typed text" option
        const customOption = document.getElementById('personCustomOption');
        const customOptionText = document.getElementById('personCustomOptionText');

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            const queryLower = query.toLowerCase();
            let anyVisible = false;

            options.forEach(opt => {
                const val = opt.getAttribute('data-value');
                const text = opt.textContent.toLowerCase();
                if (val === "" || text.includes(queryLower)) {
                    opt.style.display = 'block';
                    if (val !== "") anyVisible = true;
                } else {
                    opt.style.display = 'none';
                }
            });

            // Show "Use typed text" option only when there is text and no list match
            if (query.length > 0 && !anyVisible) {
                customOptionText.textContent = 'Use "' + query + '" as person';
                customOption.classList.remove('d-none');
            } else {
                customOption.classList.add('d-none');
            }
        });

        // Click on custom option selects the typed text
        customOption.addEventListener('click', function(e) {
            e.stopPropagation();
            const typedValue = searchInput.value.trim();
            if (!typedValue) return;

            // Deselect all existing options
            options.forEach(opt => opt.classList.remove('selected'));
            customOption.classList.remove('selected'); // reset styling next open

            hiddenInput.value = typedValue;
            selectText.textContent = typedValue;
            selectText.classList.remove('text-muted');

            customOption.classList.add('d-none');
            dropdown.classList.add('d-none');
        });
    }

    // Populate District based on Province
    const districts = {
        'බස්නාහිර පළාත': ['කොළඹ', 'ගම්පහ', 'කළුතර'],
        'මධ්‍යම පළාත': ['මහනුවර', 'මාතලේ', 'නුවරඑළිය'],
        'දකුණු පළාත': ['ගාල්ල', 'මාතර', 'හම්බන්තොට'],
        'උතුරු පළාත': ['යාපනය', 'කිලිනොච්චි', 'මන්නාරම', 'වවුනියාව', 'මුලතිව්'],
        'නැගෙනහිර පළාත': ['මඩකලපුව', 'අම්පාර', 'ත්‍රිකුණාමලය'],
        'වයඹ පළාත': ['කුරුණෑගල', 'පුත්තලම'],
        'උතුරු මැද පළාත': ['අනුරාධපුරය', 'පොළොන්නරුව'],
        'ඌව පළාත': ['බදුල්ල', 'මොණරාගල'],
        'සබරගමුව පළාත': ['රත්නපුර', 'කෑගල්ල']
    };

    const userProvince = document.getElementById('userProvince').value;
    const selectedDistrict = document.getElementById('selectedDistrict').value;
    const districtSelect = document.getElementById('districtSelect');
    
    if (userProvince && districts[userProvince]) {
        districts[userProvince].forEach(function(district) {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            if (district === selectedDistrict) {
                option.selected = true;
            }
            districtSelect.appendChild(option);
        });
    } else if (!userProvince) {
        // If province is not found, we can leave it empty or show a message
        const option = document.createElement('option');
        option.value = "";
        option.textContent = "Province not configured";
        districtSelect.appendChild(option);
    }

    // Fetch and populate letter number when district changes
    const firstDetailLetterNo = document.getElementById('firstDetailLetterNo');
    
    function fetchComplaintNo() {
        const district = districtSelect.value;
        if (!district) {
            if (firstDetailLetterNo) firstDetailLetterNo.value = '';
            return;
        }
        
        const fd = new FormData();
        fd.append('district', district);
        
        fetch('<?php echo URLROOT; ?>/complaints/generateComplaintNoAjax', {
            method: 'POST',
            body: fd
        })
        .then(r => r.json())
        .then(res => {
            if (res.success && firstDetailLetterNo) {
                firstDetailLetterNo.value = res.complaint_no;
            }
        })
        .catch(err => console.error('Error fetching complaint no:', err));
    }

    districtSelect.addEventListener('change', fetchComplaintNo);
    
    // Also fetch on load if district is already selected
    if (districtSelect.value) {
        fetchComplaintNo();
    }

    // Advanced File Upload JS
    const dropzone = document.getElementById('advancedDropzone');
    const fileInput = document.getElementById('attachments');
    const previewContainer = document.getElementById('filePreviewContainer');
    let selectedFiles = [];

    if (dropzone && fileInput) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight dropzone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzone.classList.add('dragover');
        }

        function unhighlight(e) {
            dropzone.classList.remove('dragover');
        }

        // Handle dropped files
        dropzone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // Handle selected files
        fileInput.addEventListener('change', function(e) {
            handleFiles(this.files);
        });

        // Click on dropzone triggers file input (if not clicking the browse button)
        dropzone.addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                fileInput.click();
            }
        });

        function handleFiles(files) {
            const newFiles = Array.from(files);
            selectedFiles = [...selectedFiles, ...newFiles];
            updateFileInput();
            renderPreviews();
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        function renderPreviews() {
            previewContainer.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                
                // Determine icon based on file type
                let iconClass = 'fas fa-file';
                if (file.type.startsWith('image/')) iconClass = 'fas fa-file-image text-primary';
                else if (file.type === 'application/pdf') iconClass = 'fas fa-file-pdf text-danger';
                else if (file.type.includes('word')) iconClass = 'fas fa-file-word text-info';
                
                const previewItem = document.createElement('div');
                previewItem.className = 'file-preview-item';
                previewItem.innerHTML = `
                    <div class="file-preview-info">
                        <i class="${iconClass} file-icon"></i>
                        <div class="file-details">
                            <h6>${file.name}</h6>
                            <small>${sizeInMB} MB</small>
                        </div>
                    </div>
                    <button type="button" class="btn-remove-file" data-index="${index}" title="Remove file">
                        <i class="fas fa-times-circle"></i>
                    </button>
                `;
                previewContainer.appendChild(previewItem);
            });

            // Add event listeners to remove buttons
            const removeButtons = previewContainer.querySelectorAll('.btn-remove-file');
            removeButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const index = parseInt(this.getAttribute('data-index'));
                    removeFile(index);
                });
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreviews();
        }
    }
});

// ═══ Letter Modal JS ═══════════════════════════════════════════════════════

const BASE_DEFAULT_LETTER_INTRO = `ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් වී ඇති පහත සඳහන් අභියාචනය එතුමාගේ සටහන අනුව අවශ්‍ය ඉදිරි කටයුතු සඳහා මේ සමඟ ඔබ වෙත යොමු කරමි.`;

const BASE_DEFAULT_LETTER_BODY = `02. ඒ අනුව උක්ත ලිපියේ සඳහන් කරුණු සම්බන්ධයෙන් පරීක්ෂා කර බලා යොදා ඇති සටහන අනුව අවශ්‍ය කටයුතු සිදුකරන ලෙසත්, ඒ සම්බන්ධයෙන් ලිපිය ලැබූ දැනුවත් කිරීමට අවශ්‍ය කටයුතු සිදුකරන ලෙසත් කාරුණිකව දන්වා සිටිමි.

03. තවද මෙම ඉල්ලීම සම්බන්ධයෙන් ඔබ විසින් ගන්නා ලද ක්‍රියාමාර්ග පිළිබඳ වාර්තාවක් ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා මෙම ලිපිය ලැබී දින 14 ක් ඇතුළත මා වෙත යොමු කරන ලෙසත්, ඒ සම්බන්ධයෙන් අදාල අභියාචනාකරු දැනුවත් කරන ලෙසත් කාරුණිකව දන්වා සිටිමි. (ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කිරීම සඳහා පිළිතුරු ලිපි සකස්කර එවීමේදී අභියාචනයේ පිටපතක් (ඇමුණුම් රහිතව) අමුණා එවන ලෙසත්, ලිපියේ අංකය සඳහන් කොට එවන ලෙසත්, වැඩිදුරටත් කාරුණිකව දන්වා සිටිමි.)`;

function getDefaultLetterIntro() {
    const district = document.getElementById('districtSelect') ? document.getElementById('districtSelect').value : '';
    if (district === 'පොළොන්නරුව') {
        const today = new Date();
        const dateStr = today.getFullYear() + '.' + String(today.getMonth() + 1).padStart(2, '0') + '.' + String(today.getDate()).padStart(2, '0');
        return dateStr + ' දින ගරු ආණ්ඩුකාරතුමාගේ ප්‍රධානත්වයෙන් පැවති පොළොන්නරුව මහජන දිනයේ දී ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් කරන ලද පහත අභියාචනා එතුමාගේ සටහන අනුව අවශ්‍ය ඉදිරි කටයුතු සඳහා මේ සමඟ ඔබ වෙත එවමි.';
    }
    return BASE_DEFAULT_LETTER_INTRO;
}

function getDefaultLetterBody() {
    const district = document.getElementById('districtSelect') ? document.getElementById('districtSelect').value : '';
    if (district === 'පොළොන්නරුව') {
        return '02. අදාළ අභියාචනා මගින් දක්වා ඇති ඉල්ලීම් සම්බන්ධයෙන් ගත හැකි ක්‍රියා මාර්ගයන් පිළිබඳව අභියාචනා කරුවන් දැනුවත් කරන ලෙසත්, ඊට අදාළව ඔබ විසින් ගන්නා ලබන ක්‍රියා මාර්ගයන් පිළිබඳ තොරතුරු ගරු ආණ්ඩුකාරතුමා වෙත වාර්තා කිරීම සඳහා දින 14 ක් තුල මා වෙත දන්වා එවීමට අවශ්‍ය කටයුතු කරන ලෙසත් එතුමාගේ උපදෙස් පරිදි කාරුණිකව දන්වා සිටිමි.';
    }
    return BASE_DEFAULT_LETTER_BODY;
}

const DEFAULT_SIGNATORY_NAME  = 'නන්දන ගලබොඩ';
const DEFAULT_SIGNATORY_TITLE = 'ආණ්ඩුකාර ලේකම්,<br>උතුරු මැද පළාත';

let _modalForwardTarget = '';

function openLetterModal(target) {
    _modalForwardTarget = target;

    // Label on submit button
    const labels = { '': 'Submit to CC', 'ao': 'Forward to AO', 'gs': 'Forward to GS' };
    document.getElementById('modalSubmitLabel').textContent = labels[target] || 'Submit';

    // Populate editors — use previously saved hidden value if any, else defaults
    const introHidden = document.getElementById('letter_intro_hidden').value;
    const bodyHidden  = document.getElementById('letter_body_hidden').value;
    const nameHidden  = document.getElementById('signatory_name_hidden').value;
    const titleHidden = document.getElementById('signatory_title_hidden').value;

    const norm = str => (str||'').replace(/\s+/g, ' ').trim();
    
    let introToUse = introHidden;
    const isIntroDefault = !introHidden || norm(introHidden) === norm(BASE_DEFAULT_LETTER_INTRO) || introHidden.includes('දින ගරු ආණ්ඩුකාරතුමාගේ ප්‍රධානත්වයෙන් පැවති පොළොන්නරුව මහජන දිනයේ දී');
    if (isIntroDefault) introToUse = getDefaultLetterIntro();

    let bodyToUse = bodyHidden;
    const isBodyDefault = !bodyHidden || norm(bodyHidden) === norm(BASE_DEFAULT_LETTER_BODY) || bodyHidden.includes('අදාළ අභියාචනා මගින් දක්වා ඇති ඉල්ලීම් සම්බන්ධයෙන් ගත හැකි ක්‍රියා මාර්ගයන් පිළිබඳව');
    if (isBodyDefault) bodyToUse = getDefaultLetterBody();

    document.getElementById('edit_letter_intro').value    = introToUse;
    document.getElementById('edit_letter_body').value     = bodyToUse;
    document.getElementById('edit_signatory_name').value  = nameHidden   || DEFAULT_SIGNATORY_NAME;
    document.getElementById('edit_signatory_title').value = titleHidden  || DEFAULT_SIGNATORY_TITLE;

    renderLetterPreview();
    document.getElementById('letterModal').style.display = 'block';
    document.body.style.overflow = 'hidden';

    // Live update on each keystroke
    ['edit_letter_intro','edit_letter_body','edit_signatory_name','edit_signatory_title'].forEach(id => {
        document.getElementById(id).addEventListener('input', renderLetterPreview);
    });
}

function closeLetterModal() {
    document.getElementById('letterModal').style.display = 'none';
    document.body.style.overflow = '';
}

function resetLetterDefaults() {
    document.getElementById('edit_letter_intro').value    = getDefaultLetterIntro();
    document.getElementById('edit_letter_body').value     = getDefaultLetterBody();
    document.getElementById('edit_signatory_name').value  = DEFAULT_SIGNATORY_NAME;
    document.getElementById('edit_signatory_title').value = DEFAULT_SIGNATORY_TITLE;
    renderLetterPreview();
}

function nl2br(str) {
    return str.replace(/\n/g, '<br>');
}

function renderLetterPreview() {
    const intro    = document.getElementById('edit_letter_intro').value.trim();
    const body     = document.getElementById('edit_letter_body').value.trim();
    const sigName  = document.getElementById('edit_signatory_name').value.trim();
    const sigTitle = document.getElementById('edit_signatory_title').value.trim();

    // Gather form values for preview
    const applicantName = (document.getElementById('applicant_name') || {}).value || '[Applicant]';
    const complaintNo   = '[AUTO-GENERATED]';

    // Build detail rows from the Additional Details table
    const rows = document.querySelectorAll('#detailsTbody tr');
    let detailRowsHtml = '';
    let i = 1;
    rows.forEach(row => {
        const inputs = row.querySelectorAll('input[type=text]');
        if (inputs.length >= 2) {
            const letterNo = inputs[0].value || '';
            const name     = inputs[1].value || '';
            if (letterNo || name) {
                detailRowsHtml += `<tr>
                    <td style="border:1px solid #000;padding:5px;">${String(i).padStart(2,'0')}</td>
                    <td style="border:1px solid #000;padding:5px;">${letterNo}</td>
                    <td style="border:1px solid #000;padding:5px;">${name}</td>
                </tr>`;
                i++;
            }
        }
    });
    if (!detailRowsHtml) {
        detailRowsHtml = `<tr><td colspan="3" style="border:1px solid #000;padding:5px;color:#999;">— no details —</td></tr>`;
    }

    const personEl = document.getElementById('customPersonSelectText');
    const personText = personEl && !personEl.classList.contains('text-muted') ? personEl.textContent.trim() : '';
    const deptEl = document.getElementById('customSelectText');
    const deptText = deptEl && !deptEl.classList.contains('text-muted') ? deptEl.textContent.trim() : '[Department]';

    const preview = `
    <div style="font-family:'Noto Sans Sinhala',serif;font-size:11px;line-height:1.8;color:#000;">
        <div style="margin-bottom:16px;font-size:12px;">
            ${personText ? `<strong>${personText}</strong>,<br>` : ''}
            <strong>${deptText}</strong><br>
        </div>
        <div style="margin-bottom:16px;">
            <strong style="text-decoration:underline;">ගරු ආණ්ඩුකාරතුමා වෙත ඉදිරිපත් වී ඇති ලිපි</strong><br><br>
            ${nl2br(intro)}
        </div>
        <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
            <thead>
                <tr>
                    <th style="border:1px solid #000;padding:5px;width:10%;">අනු අංක</th>
                    <th style="border:1px solid #000;padding:5px;width:40%;">ලිපියේ අංකය</th>
                    <th style="border:1px solid #000;padding:5px;width:50%;">නම හා කාරණය</th>
                </tr>
            </thead>
            <tbody>${detailRowsHtml}</tbody>
        </table>
        <div style="margin-bottom:24px;">${nl2br(body)}</div>
        <div style="margin-top:20px;">
            <div style="height:40px;"></div>
            <strong>${nl2br(sigName)}</strong><br>
            ${nl2br(sigTitle)}
        </div>
    </div>`;

    document.getElementById('letterPreviewPane').innerHTML = preview;
}

function confirmAndSubmit() {
    // Copy editor values → hidden inputs
    document.getElementById('letter_intro_hidden').value    = document.getElementById('edit_letter_intro').value;
    document.getElementById('letter_body_hidden').value     = document.getElementById('edit_letter_body').value;
    document.getElementById('signatory_name_hidden').value  = document.getElementById('edit_signatory_name').value;
    document.getElementById('signatory_title_hidden').value = document.getElementById('edit_signatory_title').value;
    document.getElementById('direct_forward').value         = _modalForwardTarget;

    // Submit the main form
    document.querySelector('form[action*="/complaints/create"]').submit();
}

// Close modal on backdrop click
document.getElementById('letterModal').addEventListener('click', function(e) {
    if (e.target === this) closeLetterModal();
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
