-- INSERT DỮ LIỆU MẪU BAN ĐẦU
-- === USERS ===
INSERT INTO users (username, password, fullname, avatar, role, create_at) VALUES
('admin', 'e3afed0047b08059d0fada10f400c1e5', 'Quản trị viên', NULL, 'admin', NOW()),
('staff1', 'e3afed0047b08059d0fada10f400c1e5', 'Nguyễn Văn A', NULL, 'staff', NOW()),
('staff2', 'e3afed0047b08059d0fada10f400c1e5', 'Trần Thị B', NULL, 'staff', NOW()),
('staff3', 'e3afed0047b08059d0fada10f400c1e5', 'Lê Quốc Cường', NULL, 'staff', NOW()),
('staff4', 'e3afed0047b08059d0fada10f400c1e5', 'Phạm Thanh Dũng', NULL, 'staff', NOW()),
('staff5', 'e3afed0047b08059d0fada10f400c1e5', 'Võ Hồng Nhung', NULL, 'staff', NOW()),
('staff6', 'e3afed0047b08059d0fada10f400c1e5', 'Trần Hữu Phước', NULL, 'staff', NOW()),
('staff7', 'e3afed0047b08059d0fada10f400c1e5', 'Đỗ Thị Hoa', NULL, 'staff', NOW()),
('staff8', 'e3afed0047b08059d0fada10f400c1e5', 'Nguyễn Hồng Nam', NULL, 'staff', NOW()),
('staff9', 'e3afed0047b08059d0fada10f400c1e5', 'Phan Văn Đạt', NULL, 'staff', NOW());

-- === SERVICE TYPES ===
INSERT INTO service_types (service_name, description) VALUES
('Lưu chuồng theo ngày', 'Dịch vụ lưu trú cho thú cưng theo ngày.'),
('Phụ thu trễ giờ', 'Phí phụ thu cho khách đến đón muộn.'),
('Đồ gửi kèm', 'Nhận giữ đồ dùng cá nhân của thú cưng.'),
('Tắm thú cưng', 'Tắm rửa, sấy khô, chăm sóc lông da.'),
('Cắt tỉa lông', 'Cắt tỉa lông tạo kiểu theo yêu cầu.'),
('Khám sức khỏe định kỳ', 'Khám tổng quát định kỳ cho thú cưng.'),
('Tiêm phòng vắc xin', 'Phòng ngừa bệnh truyền nhiễm.'),
('Vệ sinh tai', 'Làm sạch tai ngừa viêm.'),
('Vệ sinh răng miệng', 'Làm sạch răng, khử mùi hôi.'),
('Massage thú cưng', 'Massage thư giãn cho thú cưng.'),
('Huấn luyện cơ bản', 'Dạy ngồi, nằm, bắt tay.'),
('Thức ăn', 'Cung cấp thức ăn phù hợp.'),
('Cắt móng', 'Cắt móng chân an toàn.'),
('Tắm dưỡng lông', 'Dưỡng mềm và suôn mượt lông.'),
('Tẩy giun', 'Tẩy giun định kỳ.'),
('Khám sau điều trị', 'Theo dõi thú sau quá trình điều trị.'),
('Phẫu thuật nhỏ', 'Tiểu phẫu, triệt sản.'),
('Chụp X-quang', 'Kiểm tra xương khớp.'),
('Siêu âm', 'Kiểm tra cơ quan nội tạng.'),
('Tư vấn dinh dưỡng', 'Tư vấn chế độ ăn phù hợp.');

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

-- === CUSTOMERS ===
INSERT INTO customers (customer_name, customer_phone_number, customer_identity_card, customer_address, customer_note)
VALUES
('Nguyễn Văn A', '0905123456', '012345678901', '123 Lê Lợi, Q1, TP.HCM', 'Khách hàng thân thiết.'),
('Trần Thị B', '0906789123', '098765432109', '45 Nguyễn Trãi, Q5, TP.HCM', 'Mèo bị dị ứng.'),
('Phạm Quốc C', '0912345678', '074812569874', '88 CMT8, Q10, TP.HCM', NULL),
('Lê Minh D', '0934567890', '011122233344', '12 Phan Đăng Lưu, Bình Thạnh', 'Gọi trước khi đón.'),
('Võ Thị E', '0987654321', '066655544433', '200 Võ Văn Kiệt, Q6', NULL),
('Nguyễn Thị F', '0901234987', '055667788990', '7A Nguyễn Văn Linh, Q7', 'Thường gửi cuối tuần.'),
('Phan Văn G', '0911789456', '011112223334', '34 Tôn Đức Thắng, Q1', NULL),
('Trần Quỳnh H', '0923456678', '066777888999', '10 Nguyễn Huệ, Q1', 'Khách quen.'),
('Đỗ Đức I', '0932345671', '099887766554', '56 Nguyễn Oanh, Gò Vấp', NULL),
('Vũ Minh K', '0945671234', '088776655443', '123 Huỳnh Tấn Phát, Q7', NULL),
('Lâm Thị L', '0909090909', '011223344556', '99 Lê Văn Sỹ, Q3', 'Ưa sạch sẽ.'),
('Phan Hữu M', '0966668888', '055667788990', '11 Nguyễn Văn Trỗi, Phú Nhuận', NULL),
('Võ Mỹ N', '0977888999', '022334455667', '200 Điện Biên Phủ, Q3', NULL),
('Bùi Văn O', '0939988776', '033445566778', '8A Cách Mạng Tháng 8, Q10', NULL),
('Nguyễn Thanh P', '0988776655', '044556677889', '22 Pasteur, Q1', NULL),
('Trần Gia Q', '0999888777', '055667788991', '12 Nguyễn Kiệm, Gò Vấp', NULL),
('Phạm Quỳnh R', '0908777666', '066778899001', '35 Trần Não, Q2', 'Khách VIP.'),
('Ngô Hồng S', '0935566778', '077889900112', '14 Võ Văn Tần, Q3', NULL),
('Lê Ngọc T', '0912333444', '088990011223', '44 Nguyễn Thông, Q3', NULL),
('Hoàng Thị U', '0983445566', '099001122334', '66 Lý Thường Kiệt, Q10', 'Có mèo lông dài.');

-- === DOCTORS ===
INSERT INTO doctors (doctor_name, doctor_phone_number, doctor_identity_card, doctor_address, doctor_note)
VALUES
('BS. Nguyễn Hữu Tài', '0909345678', '011223344556', '72 Lê Văn Sỹ, Q3', 'Da liễu.'),
('BS. Lê Hồng Mai', '0911456789', '055667788990', '22 Pasteur, Q1', 'Tiêm phòng.'),
('BS. Trần Văn Hùng', '0939988776', '022334455667', '99 Cống Quỳnh, Q1', 'Phẫu thuật.'),
('BS. Phạm Thu Trang', '0977888999', '044556677889', '12 Nguyễn Kiệm, Gò Vấp', 'Theo dõi sau điều trị.'),
('BS. Võ Minh Tâm', '0966543210', '099887766554', '8 Trần Não, Q2', 'Chẩn đoán hình ảnh.'),
('BS. Trần Hoài An', '0911234567', '088776655443', '9 Nguyễn Hữu Cảnh, Bình Thạnh', 'Khám tổng quát.'),
('BS. Nguyễn Đăng Khoa', '0905678912', '077889900112', '11 Phạm Văn Đồng, Thủ Đức', NULL),
('BS. Phạm Hương Ly', '0935556677', '066778899001', '22 Điện Biên Phủ, Q3', 'Chuyên dinh dưỡng.'),
('BS. Lâm Hữu Dũng', '0988776655', '055667788991', '55 Nguyễn Văn Cừ, Q5', NULL),
('BS. Nguyễn Thị Lệ', '0977665544', '044556677880', '33 Cách Mạng Tháng 8, Q10', NULL);

-- === PETS (20 pets, mỗi khách 1–2 thú cưng)
INSERT INTO pets (customer_id, pet_name, pet_species, pet_gender, pet_dob, pet_weight, pet_sterilization, pet_characteristic, pet_drug_allergy)
VALUES
(1, 'Bim Bim', 'Chó Poodle', 1, '2020-03-15', 6.5, 1, 'Hiếu động.', NULL),
(1, 'Lulu', 'Chó Poodle', 0, '2021-06-20', 5.2, 0, 'Kỵ tiếng ồn.', NULL),
(2, 'Miu Miu', 'Mèo Anh lông ngắn', 0, '2019-08-10', 4.0, 1, 'Lười vận động.', 'Penicillin'),
(3, 'Lucky', 'Chó Corgi', 1, '2022-02-01', 8.3, 0, 'Thân thiện.', NULL),
(4, 'Toto', 'Chó Shiba Inu', 1, '2020-11-11', 9.1, 1, 'Trung thành.', NULL),
(5, 'Momo', 'Mèo Ba Tư', 0, '2021-09-09', 3.8, 0, 'Dễ chăm sóc.', NULL),
(6, 'Coco', 'Chó Pug', 1, '2019-05-12', 7.2, 0, 'Thích ăn.', NULL),
(7, 'Bé Bông', 'Mèo Ragdoll', 0, '2020-07-30', 4.8, 1, 'Hiền lành.', NULL),
(8, 'Ken', 'Chó Husky', 1, '2018-12-22', 18.5, 1, 'Sôi nổi.', NULL),
(9, 'Na Na', 'Mèo Xiêm', 0, '2022-04-10', 3.2, 0, 'Thích leo trèo.', NULL),
(10, 'Milo', 'Chó Golden', 1, '2021-02-02', 25.0, 0, 'Thân thiện.', NULL),
(11, 'Misa', 'Mèo Ba Tư', 0, '2021-06-09', 4.2, 0, 'Thích tắm.', NULL),
(12, 'Ben', 'Chó Cocker', 1, '2019-07-17', 10.5, 1, 'Ngoan.', NULL),
(13, 'Tom', 'Mèo Munchkin', 1, '2020-01-01', 3.9, 0, 'Ngắn chân.', NULL),
(14, 'Neko', 'Mèo Nhật', 0, '2022-03-12', 3.3, 0, 'Tò mò.', NULL),
(15, 'Simba', 'Chó Becgie', 1, '2019-09-09', 22.0, 1, 'Canh gác tốt.', NULL),
(16, 'Snow', 'Chó Samoyed', 1, '2020-10-10', 23.5, 1, 'Rất thân thiện.', NULL),
(17, 'Lucy', 'Mèo Sphynx', 0, '2021-08-01', 3.0, 0, 'Không có lông.', NULL),
(18, 'Zin', 'Chó Pomeranian', 1, '2023-01-05', 2.8, 0, 'Nhỏ, nhanh nhẹn.', NULL),
(19, 'Daisy', 'Chó Chihuahua', 0, '2022-12-25', 2.5, 0, 'Hơi sợ người lạ.', NULL);

-- === MEDICAL RECORDS ===
INSERT INTO medical_records 
(customer_id, pet_id, doctor_id, medical_record_type, medical_record_visit_date, medical_record_summary, medical_record_details)
VALUES
(1, 1, 1, 'Khám', '2025-09-20', 'Khám tổng quát định kỳ cho chó Poodle Bim Bim.', 'Sức khỏe ổn định, không phát hiện bất thường.'),
(1, 2, 2, 'Vaccine', '2025-10-02', 'Tiêm phòng dại cho Lulu.', 'Tiêm 1 mũi Vacxin Rabisin, theo dõi 30 phút sau tiêm, phản ứng tốt.'),
(2, 3, 3, 'Điều trị', '2025-09-28', 'Điều trị viêm da cho Miu Miu.', 'Bôi thuốc kháng sinh ngoài da trong 7 ngày, tái khám ngày 05/10.'),
(3, 4, 4, 'Khám', '2025-10-10', 'Khám lần đầu cho Lucky.', 'Cún khỏe mạnh, đề nghị tiêm phòng vắc xin tổng hợp.'),
(4, 5, 5, 'Điều trị', '2025-10-05', 'Điều trị viêm tai giữa cho Toto.', 'Làm sạch tai, kê thuốc nhỏ tai và kháng sinh uống 5 ngày.'),
(5, 6, 2, 'Vaccine', '2025-09-15', 'Tiêm phòng 4 bệnh cho Momo.', 'Đã tiêm Nobivac DHPPi, theo dõi không phản ứng phụ.'),
(6, 7, 3, 'Khám', '2025-08-20', 'Khám sức khỏe định kỳ cho mèo Bé Bông.', 'Ổn định, cân nặng 3.5kg, mắt hơi đỏ nhẹ.'),
(7, 8, 2, 'Vaccine', '2025-08-22', 'Tiêm phòng 6 bệnh cho chó Ken.', 'Tiêm Nobivac 6, theo dõi tốt.'),
(8, 9, 5, 'Điều trị', '2025-08-25', 'Điều trị tiêu hóa cho chó Na Na.', 'Kê thuốc men tiêu hóa 5 ngày, ăn cháo loãng.'),
(9, 10, 4, 'Khám', '2025-09-01', 'Khám tổng quát mèo Milo.', 'Sức khỏe tốt, chưa cần tiêm phòng.'),
(10, 11, 3, 'Điều trị', '2025-09-05', 'Viêm da nhẹ ở tai phải.', 'Bôi thuốc hằng ngày, kiểm tra lại sau 1 tuần.'),
(11, 12, 1, 'Khám', '2025-09-07', 'Khám lần đầu cho mèo Misa.', 'Đề xuất tiêm phòng dại.'),
(12, 13, 4, 'Vaccine', '2025-09-10', 'Tiêm phòng dại cho mèo Ben.', 'Tiêm Rabisin, theo dõi tốt.'),
(13, 14, 5, 'Điều trị', '2025-09-14', 'Điều trị nấm ngoài da cho chó Ben.', 'Tắm bằng dung dịch đặc trị, 3 lần/tuần.'),
(14, 15, 2, 'Khám', '2025-09-18', 'Khám kiểm tra lại sau điều trị.', 'Tình trạng da đã cải thiện 90%.'),
(15, 16, 3, 'Vaccine', '2025-09-20', 'Tiêm phòng 4 bệnh cho Lucky nhỏ.', 'Không phản ứng phụ.'),
(16, 17, 1, 'Điều trị', '2025-09-25', 'Điều trị ve rận.', 'Xịt thuốc Frontline, khuyên tắm thuốc 2 lần/tuần.'),
(17, 18, 5, 'Khám', '2025-09-28', 'Khám sức khỏe tổng quát.', 'Ổn định, cân nặng đạt chuẩn.'),
(18, 19, 2, 'Điều trị', '2025-10-02', 'Điều trị cảm cúm.', 'Cho uống vitamin C và kháng sinh 3 ngày.'),
(19, 20, 4, 'Khám', '2025-10-05', 'Khám lần 2 kiểm tra sức khỏe.', 'Không phát hiện vấn đề.'),
(20, 3, 3, 'Vaccine', '2025-10-10', 'Tiêm vaccine 6 bệnh cho Miu Miu.', 'Hoàn tất, theo dõi tốt.');

-- === VACCINATION RECORDS ===
INSERT INTO vaccination_records 
(medical_record_id, vaccine_name, batch_number, next_injection_date)
VALUES
(2, 'Rabisin (Phòng dại)', 'RBS-2025-01', '2026-10-02'),
(6, 'Nobivac DHPPi (Phòng 4 bệnh)', 'NBV-2025-07', '2026-09-15'),
(7, 'Nobivac 6 (Phòng 6 bệnh)', 'NBV-2025-08', '2026-08-22'),
(12, 'Rabisin (Phòng dại)', 'RBS-2025-09', '2026-09-10'),
(15, 'Nobivac DHPPi', 'NBV-2025-09', '2026-09-20'),
(20, 'Nobivac 6', 'NBV-2025-10', '2026-10-10'),
(3, 'Rabisin (Phòng dại)', 'RBS-2025-07', '2026-07-25'),
(8, 'Felocell 4 (Mèo)', 'FLC-2025-08', '2026-08-25'),
(9, 'Nobivac DHP', 'NBV-2025-09', '2026-09-18'),
(10, 'Nobivac 4 bệnh', 'NBV-2025-09B', '2026-09-25'),
(11, 'Rabisin', 'RBS-2025-09B', '2026-09-30'),
(13, 'Nobivac 6', 'NBV-2025-09C', '2026-09-28'),
(14, 'Felocell CVR', 'FLC-2025-09', '2026-09-18'),
(16, 'Nobivac DHPPi', 'NBV-2025-09D', '2026-09-25'),
(17, 'Felocell 4', 'FLC-2025-10', '2026-10-02'),
(18, 'Rabisin', 'RBS-2025-10', '2026-10-05'),
(19, 'Nobivac 6', 'NBV-2025-10A', '2026-10-09');

-- === PET ENCLOSURES ===
INSERT INTO pet_enclosures 
(customer_id, pet_id, pet_enclosure_number, check_in_date, check_out_date, daily_rate, deposit, emergency_limit, pet_enclosure_note, pet_enclosure_status)
VALUES
(1, 1, 101, '2025-09-18 09:00:00', '2025-09-20 10:00:00', 300000, 50000, 0, 'Giữ 2 ngày, thú cưng ngoan.', 'Check Out'),
(2, 3, 102, '2025-09-28 08:30:00', '2025-10-01 09:00:00', 350000, 100000, 0, 'Ở chuồng VIP, có camera theo dõi.', 'Check Out'),
(3, 4, 103, '2025-10-10 10:00:00', NULL, 400000, 100000, 0, 'Cún lần đầu gửi, cần quan sát.', 'Check In'),
(4, 5, 104, '2025-10-05 14:00:00', '2025-10-09 09:00:00', 380000, 80000, 0, 'Thú cưng dễ sợ tiếng ồn.', 'Check Out'),
(5, 6, 105, '2025-10-12 09:00:00', NULL, 350000, 100000, 0, 'Chó Momo đang điều trị, theo dõi thêm.', 'Check In'),
(6, 7, 106, '2025-08-20 08:00:00', '2025-08-22 10:00:00', 250000, 50000, 0, 'Giữ 2 ngày.', 'Check Out'),
(7, 8, 107, '2025-08-22 09:00:00', '2025-08-24 11:00:00', 300000, 70000, 0, 'Rất thân thiện.', 'Check Out'),
(8, 9, 108, '2025-08-25 08:30:00', '2025-08-27 09:00:00', 280000, 50000, 0, 'Có theo dõi camera.', 'Check Out'),
(9, 10, 109, '2025-09-01 09:00:00', '2025-09-03 09:00:00', 320000, 80000, 0, 'Ở phòng lạnh.', 'Check Out'),
(10, 11, 110, '2025-09-05 10:00:00', '2025-09-07 10:00:00', 350000, 100000, 0, 'Cún nhỏ, dễ sợ tiếng ồn.', 'Check Out'),
(11, 12, 111, '2025-09-07 09:00:00', NULL, 400000, 100000, 0, 'Đang điều trị ve.', 'Check In'),
(12, 13, 112, '2025-09-10 08:00:00', '2025-09-13 09:00:00', 370000, 90000, 0, 'Rất ngoan.', 'Check Out'),
(13, 14, 113, '2025-09-14 10:00:00', '2025-09-17 09:00:00', 380000, 80000, 0, 'Thú cưng mới đến lần đầu.', 'Check Out'),
(14, 15, 114, '2025-09-18 09:00:00', '2025-09-20 09:00:00', 350000, 70000, 0, 'Không thích tiếng máy hút bụi.', 'Check Out'),
(15, 16, 115, '2025-09-20 09:30:00', NULL, 400000, 100000, 0, 'Đang ở 3 ngày.', 'Check In'),
(16, 17, 116, '2025-09-25 08:00:00', '2025-09-27 09:00:00', 370000, 80000, 0, 'Vui vẻ.', 'Check Out'),
(17, 18, 117, '2025-09-28 08:30:00', '2025-09-30 09:00:00', 390000, 90000, 0, 'Thú cưng hiền.', 'Check Out'),
(18, 19, 118, '2025-10-02 08:00:00', '2025-10-04 09:00:00', 400000, 100000, 0, 'Rất dễ thương.', 'Check Out'),
(19, 20, 119, '2025-10-05 10:00:00', NULL, 420000, 100000, 0, 'Cún đang điều trị nhẹ.', 'Check In'),
(20, 3, 120, '2025-10-10 09:00:00', NULL, 350000, 100000, 0, 'Ở chuồng VIP.', 'Check In');

-- === INVOICES ===
INSERT INTO invoices 
(customer_id, pet_id, pet_enclosure_id, invoice_date, discount, subtotal, deposit, total_amount)
VALUES
(1, 1, 1, '2025-09-20 11:00:00', 0, 600000, 50000, 550000),
(2, 3, 2, '2025-10-01 10:30:00', 0, 1050000, 100000, 950000),
(3, 4, 3, '2025-10-11 17:00:00', 0, 4000000, 100000, 3900000),
(4, 5, 4, '2025-10-09 09:30:00', 50000, 1520000, 80000, 1390000),
(5, 6, 5, '2025-10-13 11:00:00', 0, 24000000, 100000, 23900000),
(6, 7, 6, '2025-08-22 11:00:00', 0, 500000, 50000, 450000),
(7, 8, 7, '2025-08-24 11:30:00', 0, 600000, 70000, 530000),
(8, 9, 8, '2025-08-27 10:00:00', 0, 560000, 50000, 510000),
(9, 10, 9, '2025-09-03 09:30:00', 0, 640000, 80000, 560000),
(10, 11, 10, '2025-09-07 09:45:00', 0, 700000, 100000, 600000),
(12, 13, 12, '2025-09-13 09:15:00', 0, 1100000, 90000, 1010000),
(13, 14, 13, '2025-09-17 10:30:00', 0, 980000, 80000, 900000),
(14, 15, 14, '2025-09-20 09:40:00', 0, 850000, 70000, 780000),
(15, 16, 15, '2025-09-22 08:50:00', 0, 400000, 100000, 300000),
(16, 17, 16, '2025-09-27 09:10:00', 0, 1200000, 80000, 1120000),
(17, 18, 17, '2025-09-30 09:00:00', 0, 950000, 90000, 860000),
(18, 19, 18, '2025-10-04 09:00:00', 0, 1150000, 100000, 1050000),
(19, 20, 19, '2025-10-06 09:00:00', 0, 1400000, 100000, 1300000),
(20, 3, 20, '2025-10-10 11:00:00', 0, 1550000, 100000, 1450000),
(11, 12, 11, '2025-09-10 10:00:00', 0, 800000, 100000, 700000);

-- === INVOICE DETAILS ===
INSERT INTO invoice_details 
(invoice_id, service_type_id, quantity, unit_price, total_price)
VALUES
(1, 1, 2, 300000, 600000),
(2, 2, 3, 350000, 1050000),
(3, 3, 1, 4000000, 4000000),
(4, 4, 2, 760000, 1520000),
(5, 5, 1, 24000000, 24000000),
(6, 1, 2, 250000, 500000),
(7, 2, 3, 200000, 600000),
(8, 3, 2, 280000, 560000),
(9, 1, 2, 320000, 640000),
(10, 4, 2, 350000, 700000),
(11, 5, 3, 300000, 900000),
(12, 2, 2, 490000, 980000),
(13, 4, 2, 425000, 850000),
(14, 3, 1, 400000, 400000),
(15, 5, 3, 400000, 1200000),
(16, 4, 2, 475000, 950000),
(17, 2, 3, 383000, 1150000),
(18, 3, 2, 700000, 1400000),
(19, 5, 3, 516000, 1550000),
(20, 1, 2, 400000, 800000);