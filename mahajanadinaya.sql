CREATE DATABASE IF NOT EXISTS mahajanadinaya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mahajanadinaya;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT NOT NULL
);

INSERT INTO roles (name, level) VALUES
('System Administrator', 100),
('Governor', 50),
('Governor Secretary (GS)', 40),
('Administrative Officer (AO)', 30),
('Chief Clerk (CC)', 20),
('Subject Officer', 10);

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO departments (name) VALUES
('පළාත් මාර්ග සංවර්ධන අධිකාරිය'),
('මාර්ග සංවර්ධන අධිකාරිය'),
('ජාතික ජලසම්පාදන හා ජලාපවහන මණ්ඩලය'),
('පළාත් ඉඩම් දෙපාර්තමේන්තුව'),
('ඉඩම් කොමසාරිස් ජනරාල් දෙපාර්තමේන්තුව'),
('ලංකා විදුලිබල මණ්ඩලය'),
('පළාත් අධ්‍යාපන දෙපාර්තමේන්තුව'),
('කලාප අධ්‍යාපන කාර්යාලය'),
('පළාත් සෞඛ්‍ය සේවා දෙපාර්තමේන්තුව'),
('දිස්ත්‍රික් මහ රෝහල'),
('පළාත් මගී ප්‍රවාහන අධිකාරිය'),
('පළාත් කෘෂිකර්ම දෙපාර්තමේන්තුව'),
('ගොවිජන සේවා දෙපාර්තමේන්තුව'),
('පළාත් පාලන දෙපාර්තමේන්තුව'),
('සමාජ සේවා දෙපාර්තමේන්තුව'),
('පොලිස් දෙපාර්තමේන්තුව'),
('ප්‍රාදේශීය ලේකම් කාර්යාලය'),
('දිස්ත්‍රික් ලේකම් කාර්යාලය');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    nic VARCHAR(20) NULL,
    role_id INT NOT NULL,
    department_id INT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Default Admin user (password: admin123)
INSERT INTO users (username, password, name, role_id) VALUES
('admin', '$2y$10$wE/.7.f8z8.7/00t8j/T..TzU/q/f1q8q/8g/58k/e/x/p/z/g/vG', 'System Administrator', 1);

CREATE TABLE IF NOT EXISTS complaint_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO complaint_categories (name) VALUES
('Road'), ('Water'), ('Land'), ('Electricity'), ('Education'), ('Health'), ('Transport'), ('Agriculture'), ('Others');

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_no VARCHAR(50) NOT NULL UNIQUE,
    date DATE NOT NULL,
    applicant_name VARCHAR(150) NOT NULL,
    nic VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(100) NULL,
    letter_no VARCHAR(100) NULL,
    subject VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    status VARCHAR(50) DEFAULT 'Draft',
    created_by INT NOT NULL,
    current_role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES complaint_categories(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (current_role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS complaint_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    letter_no VARCHAR(100) NOT NULL,
    name VARCHAR(150) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS workflow_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    from_role_id INT NOT NULL,
    to_role_id INT NULL,
    action VARCHAR(50) NOT NULL,
    remarks TEXT NULL,
    action_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
    FOREIGN KEY (from_role_id) REFERENCES roles(id),
    FOREIGN KEY (to_role_id) REFERENCES roles(id),
    FOREIGN KEY (action_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS generated_letters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    pdf_path VARCHAR(255) NOT NULL,
    generated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
    FOREIGN KEY (generated_by) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT
);

INSERT INTO settings (setting_key, setting_value) VALUES
('signature_image', ''),
('official_seal_image', '');
