<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container-fluid mt-4 px-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-secondary mb-4">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            
            <?php if($data['complaint']->status == 'Rejected by CC' && $data['reject_log']): ?>
            <div class="alert alert-danger shadow-sm border-danger border-start border-5 mb-4 rounded">
                <h5 class="alert-heading text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Rejected by Chief Clerk</h5>
                <p class="mb-0 mt-2"><strong>Reason for Rejection:</strong> <?php echo htmlspecialchars($data['reject_log']->remarks); ?></p>
                <hr>
                <p class="mb-0 small text-danger">Please correct the information below based on the remarks and resubmit.</p>
            </div>
            <?php endif; ?>

            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient bg-primary text-white py-3 rounded-top-4">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> <?php echo ($data['complaint']->status == 'Draft') ? 'Edit & Submit Complaint' : 'Edit & Resubmit Complaint'; ?></h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="<?php echo URLROOT; ?>/complaints/edit/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">
                        
                        <?php if(!empty($data['err'])) : ?>
                            <div class="alert alert-danger"><?php echo $data['err']; ?></div>
                        <?php endif; ?>

                        <h5 class="text-primary mb-3 border-bottom pb-2">Applicant Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="applicant_name" class="form-label fw-bold">Applicant Name *</label>
                                <input type="text" name="applicant_name" class="form-control" value="<?php echo htmlspecialchars($data['applicant_name']); ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="nic" class="form-label fw-bold">NIC *</label>
                                <input type="text" name="nic" class="form-control" value="<?php echo htmlspecialchars($data['nic']); ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="mobile" class="form-label fw-bold">Contact Number (Mobile) *</label>
                                <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($data['mobile']); ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($data['address']); ?></textarea>
                        </div>

                        <h5 class="text-primary mb-3 border-bottom pb-2">Complaint Details</h5>
                        <div class="form-group mb-3">
                            <label for="subject" class="form-label fw-bold">Subject *</label>
                            <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($data['subject']); ?>" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="category_id" class="form-label fw-bold">Category *</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="forward_department_id" class="form-label fw-bold">Forward To Department *</label>
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
                                        <div class="p-1 mb-2">
                                            <input type="text" id="deptSearch" class="form-control form-control-sm" placeholder="🔍 Search department..." style="font-size: 0.85rem; padding: 6px 12px;">
                                        </div>
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

                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-3">
                                <label for="person" class="form-label fw-bold">Forward To Person *</label>
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
                                        <div class="p-1 mb-2">
                                            <input type="text" id="personSearch" class="form-control form-control-sm" placeholder="🔍 Search person..." style="font-size: 0.85rem; padding: 6px 12px;">
                                        </div>
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
                            <div class="col-md-6 mb-3"></div>
                        </div>
                        

                        
                        <h5 class="text-primary mb-3 border-bottom pb-2">Additional Details</h5>
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
                                    <?php if(!empty($data['details'])): ?>
                                        <?php $i = 1; foreach($data['details'] as $detail): ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><input type="text" name="detail_letter_no[]" class="form-control" value="<?php echo htmlspecialchars($detail->letter_no); ?>"></td>
                                                <td><input type="text" name="detail_name[]" class="form-control" value="<?php echo htmlspecialchars($detail->name); ?>"></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php $i++; endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
                                            <td><input type="text" name="detail_name[]" class="form-control"></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-secondary btn-sm" id="addRowBtn" style="margin-bottom: 20px;">
                                <i class="fas fa-plus"></i> Add Row
                            </button>
                        </div>
                        
                        <?php if(!empty($data['attachments'])): ?>
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2">Existing Attachments</h5>
                            <div class="row g-2 mt-2">
                                <?php foreach($data['attachments'] as $attachment): ?>
                                <div class="col-md-6">
                                    <div class="border rounded p-2 d-flex align-items-center">
                                        <i class="fas fa-paperclip text-muted me-2"></i>
                                        <a href="<?php echo URLROOT; ?>/<?php echo $attachment->file_path; ?>" target="_blank" class="text-decoration-none text-truncate" style="max-width: 90%;">
                                            <?php echo htmlspecialchars($attachment->file_name); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Add New Attachments (Optional, multiple files allowed)</label>
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
                        <input type="hidden" name="direct_forward" id="direct_forward" value="">

                        <div class="mt-4" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary" onclick="document.getElementById('direct_forward').value=''">
                                <i class="fas fa-paper-plane me-1"></i> <?php echo ($data['complaint']->status == 'Draft') ? 'Submit to CC' : 'Resubmit to CC'; ?>
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
            </div>
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
    let rowCount = document.getElementById('detailsTbody').children.length;
    document.getElementById('addRowBtn').addEventListener('click', function() {
        rowCount++;
        const tbody = document.getElementById('detailsTbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${rowCount}</td>
            <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
            <td><input type="text" name="detail_name[]" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('detailsTable').addEventListener('click', function(e) {
        if(e.target.closest('.remove-row')) {
            const tr = e.target.closest('tr');
            if(document.getElementById('detailsTbody').children.length > 1) {
                tr.remove();
                const rows = document.getElementById('detailsTbody').querySelectorAll('tr');
                let count = 0;
                rows.forEach(row => {
                    count++;
                    row.cells[0].innerText = count;
                });
                rowCount = count;
            } else {
                tr.querySelector('input[name="detail_letter_no[]"]').value = '';
                tr.querySelector('input[name="detail_name[]"]').value = '';
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Department Dropdown
        const wrapper = document.querySelector('.custom-select-wrapper');
        if (wrapper) {
            const trigger = document.getElementById('customSelectTrigger');
            const dropdown = document.getElementById('customSelectDropdown');
            const searchInput = document.getElementById('deptSearch');
            const optionsContainer = document.getElementById('customSelectOptions');
            const hiddenInput = document.getElementById('forward_department_id');
            const selectText = document.getElementById('customSelectText');
            const options = Array.from(optionsContainer.querySelectorAll('.custom-option'));
            
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('d-none');
                if (!dropdown.classList.contains('d-none')) {
                    searchInput.value = '';
                    options.forEach(opt => opt.style.display = 'block');
                    searchInput.focus();
                }
            });
            
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            document.addEventListener('click', function() {
                dropdown.classList.add('d-none');
            });
            
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    dropdown.classList.remove('d-none');
                    searchInput.focus();
                }
            });

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

            const initialVal = hiddenInput.value;
            const matchedOpt = options.find(opt => opt.getAttribute('data-value') === initialVal);
            if (matchedOpt) {
                selectOption(matchedOpt);
            } else {
                const placeholderOpt = options.find(opt => opt.getAttribute('data-value') === "");
                if (placeholderOpt) selectOption(placeholderOpt);
            }
            
            optionsContainer.addEventListener('click', function(e) {
                const optionEl = e.target.closest('.custom-option');
                if (optionEl) {
                    selectOption(optionEl);
                }
            });
            
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
        }

        // Person Dropdown
        const personWrapper = document.querySelector('.custom-person-select-wrapper');
        if (personWrapper) {
            const trigger = document.getElementById('customPersonSelectTrigger');
            const dropdown = document.getElementById('customPersonSelectDropdown');
            const searchInput = document.getElementById('personSearch');
            const optionsContainer = document.getElementById('customPersonSelectOptions');
            const hiddenInput = document.getElementById('person');
            const selectText = document.getElementById('customPersonSelectText');
            const options = Array.from(optionsContainer.querySelectorAll('.custom-person-option'));
            
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('d-none');
                if (!dropdown.classList.contains('d-none')) {
                    searchInput.value = '';
                    options.forEach(opt => opt.style.display = 'block');
                    searchInput.focus();
                }
            });
            
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            document.addEventListener('click', function() {
                dropdown.classList.add('d-none');
            });
            
            trigger.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    dropdown.classList.remove('d-none');
                    searchInput.focus();
                }
            });

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

            const initialVal = hiddenInput.value;
            const matchedOpt = options.find(opt => opt.getAttribute('data-value') === initialVal);
            if (matchedOpt) {
                selectOption(matchedOpt);
            } else {
                const placeholderOpt = options.find(opt => opt.getAttribute('data-value') === "");
                if (placeholderOpt) selectOption(placeholderOpt);
            }
            
            optionsContainer.addEventListener('click', function(e) {
                const optionEl = e.target.closest('.custom-person-option');
                if (optionEl) {
                    selectOption(optionEl);
                }
            });
            
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
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
