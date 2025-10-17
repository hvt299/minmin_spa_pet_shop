-- === USERS ===
INSERT INTO users (username, password, fullname, avatar, role, create_at) VALUES
('admin', 'e3afed0047b08059d0fada10f400c1e5', 'Quản trị viên', NULL, 'admin', NOW());

-- === SERVICE TYPES ===
INSERT INTO service_types (service_name, description) VALUES
('Lưu chuồng theo ngày', 'Dịch vụ lưu trú cho thú cưng theo ngày.'),
('Phụ thu trễ giờ', 'Phí phụ thu cho khách đến đón muộn.'),
('Đồ gửi kèm', 'Nhận giữ đồ dùng cá nhân của thú cưng.');

-- === GENERAL SETTINGS ===
INSERT INTO general_settings (
    clinic_name, clinic_address_1, clinic_address_2,
    phone_number_1, phone_number_2,
    representative_name, checkout_hour,
    overtime_fee_per_hour, default_daily_rate,
    signing_place
) VALUES (
    'Phòng Khám Thú Y Min Min - Spa Thú Cưng Min Min',
    '163/5 Võ Văn Kiệt, P7, TP. Bạc Liêu',
    '',
    '0794796166',
    '0799970111',
    'Phan Anh Tuấn',
    '18:00:00',
    20000,
    60000,
    'Bạc Liêu'
);