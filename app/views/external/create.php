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
                
                <form action="<?php echo URLROOT; ?>/externalcomplaint/create" method="POST">
                    
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
                            <label for="mobile" class="form-label">Contact Number (Mobile) *</label>
                            <input type="text" name="mobile" class="form-control" value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>" required>
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
                        <div class="form-group">
                            <label for="district" class="form-label">District *</label>
                            <select name="district" class="form-control" required disabled>
                                <option value="">Select District</option>
                                <!-- Populated by JS -->
                            </select>
                            <input type="hidden" id="selected_district" value="<?php echo isset($data['district']) ? htmlspecialchars($data['district']) : ''; ?>">
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
                            <label for="forward_department_id" class="form-label">Forward To Department *</label>
                            <select name="forward_department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php if(isset($data['departments'])): ?>
                                    <?php foreach($data['departments'] as $dept) : ?>
                                        <option value="<?php echo $dept->id; ?>" <?php echo (isset($data['forward_department_id']) && $data['forward_department_id'] == $dept->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept->name); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
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
        } else {
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
});
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
