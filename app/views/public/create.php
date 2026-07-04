<?php require APPROOT . '/views/layout/header.php'; ?>

<main class="container">
    <div style="display: flex; justify-content: center; margin-top: 30px; margin-bottom: 50px;">
        <div style="width: 100%; max-width: 800px;">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2>Submit a Complaint</h2>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;">Fill out the form below to register a new complaint.</p>
                    </div>
                </div>
                
                <form action="<?php echo URLROOT; ?>/publiccomplaint/create" method="POST">
                    
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

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
                    </div>

                    <h3 style="margin: 20px 0 15px; font-size: 1.1rem; color: var(--primary-color);">Complaint Details</h3>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control" value="<?php echo isset($data['subject']) ? $data['subject'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id" class="form-label">Category *</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php if(isset($data['categories'])): ?>
                                    <?php foreach($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category->id) ? 'selected' : ''; ?>><?php echo $category->name; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required readonly>
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

<?php require APPROOT . '/views/layout/footer.php'; ?>
