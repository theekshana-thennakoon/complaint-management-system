<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div style="width: 100%; max-width: 550px;">
        <div class="card">
            <div class="card-header" style="justify-content: center; border-bottom: none;">
                <div style="text-align: center;">
                    <i class="fas fa-user-plus" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                    <h2>Register Account</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Create a new officer account.</p>
                </div>
            </div>
            
            <div style="padding: 0 10px;">
                <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name'] ?? ''; ?>" placeholder="Enter full name">
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['name_err'] ?? ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="nic" class="form-label">NIC</label>
                        <input type="text" name="nic" class="form-control <?php echo (!empty($data['nic_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['nic'] ?? ''; ?>" placeholder="Enter NIC number">
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['nic_err'] ?? ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="province" class="form-label">Province</label>
                        
                        <div class="dropdown w-100">
                            <button class="form-control text-start d-flex justify-content-between align-items-center <?php echo (!empty($data['province_err'])) ? 'is-invalid' : ''; ?>" type="button" id="provinceDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #fff;">
                                <span><?php echo (isset($data['province']) && !empty($data['province'])) ? htmlspecialchars($data['province']) : 'Select Province'; ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu w-100 p-2 shadow-sm border-0" aria-labelledby="provinceDropdownBtn">
                                <li>
                                    <input type="text" class="form-control form-control-sm mb-2 rounded-pill px-3" id="provinceSearchInput" placeholder="🔍 Search province...">
                                </li>
                                <div style="max-height: 200px; overflow-y: auto;" id="provinceListContainer">
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="බස්නාහිර පළාත" data-name="බස්නාහිර පළාත (Western)">බස්නාහිර පළාත (Western)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="මධ්‍යම පළාත" data-name="මධ්‍යම පළාත (Central)">මධ්‍යම පළාත (Central)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="දකුණු පළාත" data-name="දකුණු පළාත (Southern)">දකුණු පළාත (Southern)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="උතුරු පළාත" data-name="උතුරු පළාත (Northern)">උතුරු පළාත (Northern)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="නැගෙනහිර පළාත" data-name="නැගෙනහිර පළාත (Eastern)">නැගෙනහිර පළාත (Eastern)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="වයඹ පළාත" data-name="වයඹ පළාත (North Western)">වයඹ පළාත (North Western)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="උතුරු මැද පළාත" data-name="උතුරු මැද පළාත (North Central)">උතුරු මැද පළාත (North Central)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="ඌව පළාත" data-name="ඌව පළාත (Uva)">ඌව පළාත (Uva)</a></li>
                                    <li><a class="dropdown-item province-item rounded-2" href="#" data-value="සබරගමුව පළාත" data-name="සබරගමුව පළාත (Sabaragamuwa)">සබරගමුව පළාත (Sabaragamuwa)</a></li>
                                </div>
                            </ul>
                            <input type="hidden" name="province" id="province_hidden" value="<?php echo isset($data['province']) ? $data['province'] : ''; ?>">
                        </div>
                        
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['province_err'] ?? ''; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>" placeholder="Choose a username">
                        <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['username_err']; ?></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Create password">
                            <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['password_err']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Confirm password">
                            <span style="color: #dc3545; font-size: 0.8rem;"><?php echo $data['confirm_password_err']; ?></span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-check-circle"></i> Register
                        </button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px; border-top: 1px solid var(--panel-border); padding-top: 20px;">
                    <a href="<?php echo URLROOT; ?>/auth/login" style="font-size: 0.9rem;">Already have an account? Login here.</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSearchInput = document.getElementById('provinceSearchInput');
    const provinceItems = document.querySelectorAll('.province-item');
    const provinceDropdownBtn = document.getElementById('provinceDropdownBtn');
    const provinceDropdownBtnText = provinceDropdownBtn.querySelector('span');
    const provinceHiddenInput = document.getElementById('province_hidden');

    // Set initial selected value if exists
    if (provinceHiddenInput && provinceHiddenInput.value) {
        const selectedItem = document.querySelector(`.province-item[data-value="${provinceHiddenInput.value}"]`);
        if (selectedItem) {
            provinceDropdownBtnText.textContent = selectedItem.getAttribute('data-name');
        }
    }

    if(provinceSearchInput) {
        provinceSearchInput.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        provinceSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            provinceItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase();
                if (name.includes(query)) {
                    item.parentElement.style.display = 'block';
                } else {
                    item.parentElement.style.display = 'none';
                }
            });
        });
    }

    provinceItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const val = this.getAttribute('data-value');
            const name = this.getAttribute('data-name');
            
            provinceHiddenInput.value = val;
            provinceDropdownBtnText.textContent = name;
            
            // Clear search when selected
            provinceSearchInput.value = '';
            provinceItems.forEach(i => i.parentElement.style.display = 'block');
            
            // Remove invalid class if present
            provinceDropdownBtn.classList.remove('is-invalid');
        });
    });
});
</script>
