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
                
                <form action="<?php echo URLROOT; ?>/publiccomplaint/create" method="POST" enctype="multipart/form-data">
                    
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

<style>
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
