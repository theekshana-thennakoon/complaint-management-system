<?php require APPROOT . '/views/layout/header.php'; ?>

<style>
/* Hide header for external page */
header.main-header { display: none !important; }
</style>
<main class="container">
    <div style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 50px;">
        <div style="width: 100%; max-width: 800px;">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2>Submit an External Complaint</h2>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Fill out the form below to register a new external complaint.</p>
                    </div>
                </div>
                
                <form action="<?php echo URLROOT; ?>/externalcomplaint/create" method="POST" enctype="multipart/form-data">
                    
                    <?php if(!empty($data['err'])) : ?>
                        <div class="alert alert-danger" style="margin-top: 15px; margin-bottom: 15px;"><?php echo $data['err']; ?></div>
                    <?php endif; ?>

                    <h3 style="margin: 20px 0 15px; font-size: 1.1rem; color: var(--primary-color);">Applicant Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="applicant_name" class="form-label">Applicant Name *</label>
                            <input type="text" name="applicant_name" class="form-control" value="<?php echo isset($data['applicant_name']) ? $data['applicant_name'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nic" class="form-label">NIC *</label>
                            <input type="text" name="nic" class="form-control" value="<?php echo isset($data['nic']) ? $data['nic'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile" class="form-label">Contact Number (Mobile)</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="province" class="form-label">Province *</label>
                            <select name="province" class="form-control" required>
                                <option value="">Select Province</option>
                                <?php
                                $provinces = [
                                    'බස්නාහිර පළාත' => 'බස්නාහිර පළාත',
                                    'මධ්‍යම පළාත' => 'මධ්‍යම පළාත',
                                    'දකුණු පළාත' => 'දකුණු පළාත',
                                    'උතුරු පළාත' => 'උතුරු පළාත',
                                    'නැගෙනහිර පළාත' => 'නැගෙනහිර පළාත',
                                    'වයඹ පළාත' => 'වයඹ පළාත',
                                    'උතුරු මැද පළාත' => 'උතුරු මැද පළාත',
                                    'ඌව පළාත' => 'ඌව පළාත',
                                    'සබරගමුව පළාත' => 'සබරගමුව පළාත'
                                ];
                                foreach($provinces as $val => $label) {
                                    $selected = (isset($data['province']) && $data['province'] == $val) ? 'selected' : '';
                                    echo "<option value=\"$val\" $selected>$label</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
                    </div>

                    <h3 style="margin: 20px 0 15px; font-size: 1.1rem; color: var(--primary-color);">Complaint Details</h3>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="reason" class="form-label">Reason *</label>
                        <textarea name="reason" class="form-control" rows="4" required><?php echo isset($data['reason']) ? $data['reason'] : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id" class="form-label">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                <?php if(isset($data['categories'])): ?>
                                    <?php foreach($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                            <select name="district" class="form-control" required disabled>
                                <option value="">Select District</option>
                                <!-- Populated by JS -->
                            </select>
                            <input type="hidden" id="selected_district" value="<?php echo isset($data['district']) ? htmlspecialchars($data['district']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row" style="margin-top: 15px;">
                        <div class="form-group">
                            <label for="forward_department_id" class="form-label">Forward To Department *</label>

                            <div class="custom-dept-select-wrapper" style="position: relative;">
                                <!-- Fake Select Trigger -->
                                <div id="customDeptSelectTrigger" class="form-control d-flex justify-content-between align-items-center" tabindex="0" style="cursor: pointer; min-height: 48px; position: relative;">
                                    <span id="customDeptSelectText" class="text-muted">Select Department</span>
                                    <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                                </div>

                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="forward_department_id" id="forward_department_id"
                                    value="<?php echo isset($data['forward_department_id']) ? htmlspecialchars($data['forward_department_id']) : ''; ?>">

                                <!-- Dropdown Menu -->
                                <div id="customDeptSelectDropdown" class="card shadow border-0 p-2 d-none" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; margin-top: 5px; max-height: 300px; display: flex; flex-direction: column; background: var(--panel-bg); border: 1px solid var(--panel-border) !important; border-radius: var(--radius-md) !important; box-shadow: var(--shadow-lg) !important;">
                                    <!-- Search Box -->
                                    <div class="p-1 mb-2">
                                        <input type="text" id="extDeptSearch" class="form-control form-control-sm" placeholder="🔍 Search department..." style="font-size: 0.85rem; padding: 6px 12px;">
                                    </div>
                                    <!-- Custom (typed) option – shown when no match found -->
                                    <div id="extDeptCustomOption" class="custom-dept-option d-none" data-custom="true" style="cursor: pointer; font-size: 0.9rem; border: 1px dashed var(--primary-color); background: var(--primary-50); color: var(--primary-color); margin: 0 0 4px;">
                                        <i class="fas fa-plus-circle" style="margin-right:6px;"></i><span id="extDeptCustomOptionText"></span>
                                    </div>
                                    <!-- Options List -->
                                    <div id="customDeptSelectOptions" style="overflow-y: auto; max-height: 200px; display: flex; flex-direction: column; gap: 2px;">
                                        <div class="custom-dept-option text-muted" data-value="" style="cursor: pointer; font-size: 0.9rem;">Select Department</div>
                                        <?php if(isset($data['departments'])): ?>
                                            <?php foreach($data['departments'] as $dept) : ?>
                                                <div class="custom-dept-option" data-value="<?php echo $dept->id; ?>" style="cursor: pointer; font-size: 0.9rem;">
                                                    <?php echo htmlspecialchars($dept->name); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
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

                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="person" id="person" value="<?php echo isset($data['person']) ? htmlspecialchars($data['person']) : ''; ?>">

                                <!-- Dropdown Menu -->
                                <div id="customPersonSelectDropdown" class="card shadow border-0 p-2 d-none" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; margin-top: 5px; max-height: 300px; display: flex; flex-direction: column; background: var(--panel-bg); border: 1px solid var(--panel-border) !important; border-radius: var(--radius-md) !important; box-shadow: var(--shadow-lg) !important;">
                                    <!-- Search Box -->
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
                            <!-- spacer -->
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 15px;">
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
                    
                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Match Select2 to custom form-control styles without changing the design */
.select2-container .select2-selection--single {
    height: auto !important;
    padding: 11px 15px !important;
    border-radius: var(--radius-md) !important;
    border: 1.5px solid var(--input-border) !important;
    background: var(--input-bg) !important;
    color: var(--text-primary) !important;
    font-size: 0.9rem !important;
    font-family: 'Inter', sans-serif !important;
    display: flex;
    align-items: center;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text-primary) !important;
    line-height: 1.5 !important;
    padding-left: 0 !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    right: 15px !important;
}
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3.5px rgba(45,145,80,0.14) !important;
}
.select2-dropdown {
    border-radius: var(--radius-md) !important;
    border: 1px solid var(--input-border) !important;
    box-shadow: var(--shadow-md) !important;
}
.select2-search__field {
    border-radius: var(--radius-sm) !important;
    border: 1px solid var(--input-border) !important;
    padding: 8px 12px !important;
}
.select2-search__field:focus {
    outline: none !important;
    border-color: var(--primary-color) !important;
    box-shadow: none !important;
}
.select2-results__option--highlighted[aria-selected] {
    background-color: var(--primary-50) !important;
    color: var(--primary-color) !important;
}
.select2-results__option {
    padding: 8px 12px !important;
}
.custom-dept-option {
    padding: 8px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--text-primary);
    transition: background 0.15s ease, color 0.15s ease;
}
.custom-dept-option:hover {
    background-color: var(--primary-50);
    color: var(--primary-color);
}
.custom-dept-option.selected {
    background-color: var(--primary-color) !important;
    color: white !important;
}
#customDeptSelectTrigger:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3.5px rgba(45,145,80,0.14);
    outline: none;
}
.custom-person-option {
    padding: 8px 12px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--text-primary);
    transition: background 0.15s ease, color 0.15s ease;
}
.custom-person-option:hover {
    background-color: var(--primary-50);
    color: var(--primary-color);
}
.custom-person-option.selected {
    background-color: var(--primary-color) !important;
    color: white !important;
}
#customPersonSelectTrigger:focus {
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

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('select[name="province"]').select2({
        placeholder: 'Select Province',
        allowClear: true,
        width: '100%'
    });
    
    $('select[name="district"]').select2({
        placeholder: 'Select District',
        allowClear: true,
        width: '100%'
    });
    
    $('select[name="forward_department_id"]').select2({
        placeholder: 'Select Department',
        allowClear: true,
        width: '100%'
    });

    // ── Department custom dropdown with AJAX insert ─────────────────────────
    const deptWrapper = document.querySelector('.custom-dept-select-wrapper');
    if (deptWrapper) {
        const dTrigger    = document.getElementById('customDeptSelectTrigger');
        const dDropdown   = document.getElementById('customDeptSelectDropdown');
        const dSearch     = document.getElementById('extDeptSearch');
        const dOptsContainer = document.getElementById('customDeptSelectOptions');
        const dHidden     = document.getElementById('forward_department_id');
        const dSelectText = document.getElementById('customDeptSelectText');
        const dCustomOpt  = document.getElementById('extDeptCustomOption');
        const dCustomText = document.getElementById('extDeptCustomOptionText');

        dTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            dDropdown.classList.toggle('d-none');
            if (!dDropdown.classList.contains('d-none')) {
                dSearch.value = '';
                Array.from(dOptsContainer.querySelectorAll('.custom-dept-option')).forEach(o => o.style.display = 'block');
                dCustomOpt.classList.add('d-none');
                dSearch.focus();
            }
        });
        dDropdown.addEventListener('click', e => e.stopPropagation());
        document.addEventListener('click', () => dDropdown.classList.add('d-none'));
        dTrigger.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                e.preventDefault(); dDropdown.classList.remove('d-none'); dSearch.focus();
            }
        });

        function dSelectOption(optEl) {
            Array.from(dOptsContainer.querySelectorAll('.custom-dept-option')).forEach(o => o.classList.remove('selected'));
            optEl.classList.add('selected');
            const val = optEl.getAttribute('data-value');
            dHidden.value = val;
            dSelectText.textContent = optEl.textContent.trim();
            dSelectText.classList.toggle('text-muted', val === '');
            dDropdown.classList.add('d-none');
        }

        // Set initial value
        const dInitVal = dHidden.value;
        const dMatchedOpt = Array.from(dOptsContainer.querySelectorAll('.custom-dept-option')).find(o => o.getAttribute('data-value') == dInitVal);
        if (dMatchedOpt) dSelectOption(dMatchedOpt);
        else {
            const dPlaceholder = Array.from(dOptsContainer.querySelectorAll('.custom-dept-option')).find(o => o.getAttribute('data-value') === '');
            if (dPlaceholder) dSelectOption(dPlaceholder);
        }

        dOptsContainer.addEventListener('click', function(e) {
            const optEl = e.target.closest('.custom-dept-option');
            if (optEl) dSelectOption(optEl);
        });

        // Filter + show "add new dept" option
        dSearch.addEventListener('input', function() {
            const query = dSearch.value.trim();
            const queryLower = query.toLowerCase();
            let anyVisible = false;
            Array.from(dOptsContainer.querySelectorAll('.custom-dept-option')).forEach(opt => {
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
                dCustomText.textContent = 'Add "' + query + '" as new department';
                dCustomOpt.classList.remove('d-none');
            } else {
                dCustomOpt.classList.add('d-none');
            }
        });

        // Click "Add new department" → AJAX insert then auto-select
        dCustomOpt.addEventListener('click', function(e) {
            e.stopPropagation();
            const newName = dSearch.value.trim();
            if (!newName) return;
            dCustomOpt.style.opacity = '0.5';
            dCustomOpt.style.pointerEvents = 'none';
            dCustomText.textContent = 'Saving…';

            const fd = new FormData();
            fd.append('name', newName);
            fetch('<?php echo URLROOT; ?>/externalcomplaint/addDepartmentAjax', {
                method: 'POST', body: fd
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    const newOpt = document.createElement('div');
                    newOpt.className = 'custom-dept-option';
                    newOpt.setAttribute('data-value', res.id);
                    newOpt.style.cssText = 'cursor:pointer;font-size:0.9rem;';
                    newOpt.textContent = res.name;
                    dOptsContainer.appendChild(newOpt);
                    Array.from(dOptsContainer.querySelectorAll('.custom-dept-option')).forEach(o => o.classList.remove('selected'));
                    newOpt.classList.add('selected');
                    dHidden.value = res.id;
                    dSelectText.textContent = res.name;
                    dSelectText.classList.remove('text-muted');
                    dCustomOpt.classList.add('d-none');
                    dDropdown.classList.add('d-none');
                } else {
                    alert('Error: ' + (res.message || 'Could not add department'));
                }
            })
            .catch(() => alert('Network error. Please try again.'))
            .finally(() => { dCustomOpt.style.opacity = ''; dCustomOpt.style.pointerEvents = ''; });
        });
    }

    const districts = <?php 
        $districts_map = [
            'බස්නාහිර පළාත' => ['කොළඹ', 'ගම්පහ', 'කළුතර'],
            'මධ්‍යම පළාත' => ['මහනුවර', 'මාතලේ', 'නුවරඑළිය'],
            'දකුණු පළාත' => ['ගාල්ල', 'මාතර', 'හම්බන්තොට'],
            'උතුරු පළාත' => ['යාපනය', 'කිලිනොච්චි', 'මන්නාරම', 'වවුනියාව', 'මුලතිව්'],
            'නැගෙනහිර පළාත' => ['මඩකලපුව', 'අම්පාර', 'ත්‍රිකුණාමලය'],
            'වයඹ පළාත' => ['කුරුණෑගල', 'පුත්තලම'],
            'උතුරු මැද පළාත' => ['අනුරාධපුරය', 'පොළොන්නරුව'],
            'ඌව පළාත' => ['බදුල්ල', 'මොණරාගල'],
            'සබරගමුව පළාත' => ['රත්නපුර', 'කෑගල්ල']
        ];
        echo json_encode($districts_map, JSON_UNESCAPED_UNICODE); 
    ?>;

    function populateDistricts(province, selectedDistrict = '') {
        const districtSelect = $('select[name="district"]');
        districtSelect.empty().append('<option value="">Select District</option>');
        
        if (province && districts[province]) {
            districts[province].forEach(function(district) {
                const selected = (district === selectedDistrict) ? 'selected' : '';
                districtSelect.append('<option value="'+district+'" '+selected+'>'+district+'</option>');
            });
            districtSelect.prop('disabled', false);
        } else if (!province) {
            districtSelect.prop('disabled', true);
        }
        districtSelect.trigger('change.select2'); // Refresh Select2
    }

    $('select[name="province"]').on('change', function() {
        populateDistricts($(this).val());
    });

    // Populate on load if province is selected (for form validation errors)
    const initProvince = $('select[name="province"]').val();
    const initDistrict = $('#selected_district').val();
    if (initProvince) {
        populateDistricts(initProvince, initDistrict);
    }

    // ── Person custom dropdown ──────────────────────────────────────────────
    const personWrapper = document.querySelector('.custom-person-select-wrapper');
    if (personWrapper) {
        const trigger    = document.getElementById('customPersonSelectTrigger');
        const dropdown   = document.getElementById('customPersonSelectDropdown');
        const searchInput = document.getElementById('personSearch');
        const optionsContainer = document.getElementById('customPersonSelectOptions');
        const hiddenInput = document.getElementById('person');
        const selectText  = document.getElementById('customPersonSelectText');
        const customOption     = document.getElementById('personCustomOption');
        const customOptionText = document.getElementById('personCustomOptionText');
        const options = Array.from(optionsContainer.querySelectorAll('.custom-person-option'));

        // Toggle dropdown
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('d-none');
            if (!dropdown.classList.contains('d-none')) {
                searchInput.value = '';
                options.forEach(opt => opt.style.display = 'block');
                customOption.classList.add('d-none');
                searchInput.focus();
            }
        });

        dropdown.addEventListener('click', function(e) { e.stopPropagation(); });
        document.addEventListener('click', function() { dropdown.classList.add('d-none'); });

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
            const val  = optionEl.getAttribute('data-value');
            const text = optionEl.textContent.trim();
            hiddenInput.value = val;
            selectText.textContent = text;
            selectText.classList.toggle('text-muted', val === '');
            dropdown.classList.add('d-none');
        }

        // Set initial value
        const initialVal = hiddenInput.value;
        const matchedOpt = options.find(opt => opt.getAttribute('data-value') === initialVal);
        if (matchedOpt) selectOption(matchedOpt);
        else {
            const placeholder = options.find(opt => opt.getAttribute('data-value') === '');
            if (placeholder) selectOption(placeholder);
        }

        optionsContainer.addEventListener('click', function(e) {
            const optionEl = e.target.closest('.custom-person-option');
            if (optionEl) selectOption(optionEl);
        });

        // Filter + show custom option
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            const queryLower = query.toLowerCase();
            let anyVisible = false;

            options.forEach(opt => {
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
                customOptionText.textContent = 'Use "' + query + '" as person';
                customOption.classList.remove('d-none');
            } else {
                customOption.classList.add('d-none');
            }
        });

        // Click custom option
        customOption.addEventListener('click', function(e) {
            e.stopPropagation();
            const typedValue = searchInput.value.trim();
            if (!typedValue) return;
            options.forEach(opt => opt.classList.remove('selected'));
            hiddenInput.value = typedValue;
            selectText.textContent = typedValue;
            selectText.classList.remove('text-muted');
            customOption.classList.add('d-none');
            dropdown.classList.add('d-none');
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
