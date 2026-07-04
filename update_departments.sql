ALTER DATABASE mahajanadinaya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE roles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE departments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE complaint_categories CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE complaints CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE complaint_details CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE attachments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE workflow_logs CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE generated_letters CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE notifications CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE audit_logs CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE settings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

SET NAMES utf8mb4;

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
