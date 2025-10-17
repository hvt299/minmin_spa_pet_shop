# Web Quản Lý Phòng Khám Thú Y

Đây là một hệ thống quản lý phòng khám thú y được xây dựng bằng **PHP**, **MySQL** và chạy trên môi trường **XAMPP**. Hệ thống giúp quản lý khách hàng, thú cưng, bác sĩ, hóa đơn, dịch vụ và các hồ sơ khám bệnh.

---

## 📌 Mục tiêu

- Quản lý thông tin **khách hàng** và **thú cưng**.
- Quản lý **bác sĩ** và các **dịch vụ**.
- Quản lý **hồ sơ khám bệnh** (Medical Records).
- Quản lý **chuồng thú cưng** và hóa đơn.
- Hỗ trợ hiển thị **thống kê doanh thu** và báo cáo.
- Tích hợp thông báo **toast** khi thao tác thành công hoặc thất bại.

---

## 💻 Công nghệ sử dụng

- **Ngôn ngữ**: PHP 8.x
- **Cơ sở dữ liệu**: MySQL (phpMyAdmin)
- **Server**: XAMPP (Apache + MySQL)
- **Front-end**: HTML5, CSS3, JavaScript, Font Awesome
- **Giao diện**: Responsive, thân thiện với người dùng

---

## 📂 Cấu trúc dự án

minmin_spa_pet_shop/
│
├─ admin/
│ ├─ assets/
│ │ ├─ css/
│ │ │ ├─ base.css
│ │ │ ├─ grid.css
│ │ │ ├─ main.css
│ │ │ └─ responsive.css
│ │ │
│ │ ├─ images/
│ │ │ ├─ sample-avatar.jpg
│ │ │ └─ default-avatar.jpg
│ │ │
│ │ └─ js/
│ │ │ └─ script.js
│ │
│ ├─ includes/
│ │ ├─ footer.php
│ │ ├─ header.php
│ │ └─ sidebar.php
│ │
│ ├─ pages/
│ │ ├─ customer
│ │ │ ├─ add_customer.php
│ │ │ ├─ customers.php
│ │ │ ├─ delete_customer.php
│ │ │ └─ edit_customer.php
│ │ │
│ │ ├─ doctor
│ │ │ ├─ add_doctor.php
│ │ │ ├─ delete_doctor.php
│ │ │ ├─ doctors.php
│ │ │ └─ edit_doctor.php
│ │ │
│ │ ├─ general_setting
│ │ │ └─ general_setting.php
│ │ │
│ │ ├─ invoice
│ │ │ ├─ add_invoice.php
│ │ │ ├─ delete_invoice.php
│ │ │ ├─ edit_invoice.php
│ │ │ └─ invoices.php
│ │ │
│ │ ├─ medical_record
│ │ │ ├─ add_medical_record.php
│ │ │ ├─ delete_medical_record.php
│ │ │ ├─ edit_medical_record.php
│ │ │ └─ medical_records.php
│ │ │
│ │ ├─ pet
│ │ │ ├─ add_pet.php
│ │ │ ├─ delete_pet.php
│ │ │ ├─ edit_pet.php
│ │ │ └─ pets.php
│ │ │
│ │ ├─ pet_enclosure
│ │ │ ├─ add_pet_enclosure.php
│ │ │ ├─ checkout_invoice.php
│ │ │ ├─ delete_pet_enclosure.php
│ │ │ ├─ edit_pet_enclosure.php
│ │ │ └─ pet_enclosures.php
│ │ │
│ │ ├─ printing_template
│ │ │ ├─ load_commit.php
│ │ │ ├─ load_invoice.php
│ │ │ └─ printing_template.php
│ │ │
│ │ ├─ service_type
│ │ │ ├─ add_service_type.php
│ │ │ ├─ delete_service_type.php
│ │ │ ├─ edit_service_type.php
│ │ │ └─ service_types.php
│ │ │
│ │ ├─ user
│ │ │ ├─ add_user.php
│ │ │ ├─ change_password.php
│ │ │ ├─ delete_user.php
│ │ │ ├─ edit_user.php
│ │ │ └─ users.php
│ │ │
│ │ └─ dashboard.php
│ │
│ ├─ sql/
│ │ ├─ data_original.sql
│ │ ├─ data_sample.sql
│ │ └─ table.sql
│ │
│ ├─ index.php
│ └─ init.php
│
├─ app/
│ ├─ check_login.php
│ ├─ customer_function.php
│ ├─ doctor_function.php
│ ├─ general_setting_function.php
│ ├─ invoice_function.php
│ ├─ logout.php
│ ├─ medical_record_function.php
│ ├─ pet_enclosure_function.php
│ ├─ pet_function.php
│ ├─ service_type_function.php
│ └─ user_function.php
│
├─ assets/
│ ├─ css/
│ │ ├─ base.css
│ │ ├─ grid.css
│ │ ├─ main.css
│ │ └─ responsive.css
│ │
│ ├─ images/
│ └─ js/
│ │ └─ script.js
│
├─ config/
│ └─ database.php/
│
├─ includes/
│ ├─ footer.php
│ └─ header.php
│
├─ index.php
└─ readme.md

---

## ⚙️ Cài đặt

1. **Cài đặt XAMPP**: Tải và cài đặt [XAMPP](https://www.apachefriends.org/index.html).  
2. **Khởi động Apache và MySQL**.  
3. **Tạo cơ sở dữ liệu**:  
   - Mở **phpMyAdmin** (`http://localhost/phpmyadmin`)  
   - Tạo database: `minmin_spa_pet_shop`  
   - Import file SQL mẫu (`table.sql`, `data_original.sql`) nếu có.  
4. **Cấu hình kết nối database**:  
   - Mở file `database.php`  
   - Cập nhật thông tin kết nối MySQL:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'minmin_spa_pet_shop');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```
5. **Đặt project vào thư mục htdocs**:
C:\xampp\htdocs\minmin_spa_pet_shop

6. **Truy cập ứng dụng**:  
Mở trình duyệt và truy cập `http://localhost/minmin_spa_pet_shop/`

---

## 📝 Tính năng chính

### Khách hàng
- Thêm, sửa, xóa, tìm kiếm khách hàng.
- Quản lý thông tin liên hệ, thẻ căn cước, địa chỉ, ghi chú.

### Thú cưng
- Thêm, sửa, xóa thú cưng.
- Quản lý thông tin tiêm chủng, dị ứng, đặc điểm, cân nặng, giới tính.

### Bác sĩ
- Quản lý thông tin bác sĩ, số điện thoại, thẻ căn cước, địa chỉ.

### Hồ sơ khám bệnh (Medical Records)
- Thêm, sửa, xóa hồ sơ khám bệnh.
- Quản lý Vaccine: tên vaccine, số lô, lịch tiêm tiếp theo.
- Liên kết khách hàng, thú cưng, bác sĩ, loại khám, kết quả khám.

### Chuồng thú cưng
- Check-in, check-out.
- Tính tiền lưu chuồng theo ngày.
- Tính phụ thu trễ giờ checkout.

### Hóa đơn
- Tạo tự động từ chuồng thú cưng.
- Thêm chi tiết dịch vụ, tính toán subtotal, tổng cộng, deposit.
- Quản lý hóa đơn: thêm, sửa, xóa.

### Dịch vụ
- Quản lý danh mục dịch vụ: thêm, sửa, xóa.
- Hỗ trợ dịch vụ phụ thu trễ giờ và lưu chuồng theo ngày.

### Người dùng
- Quản lý tài khoản: thêm, sửa, xóa, đổi mật khẩu.
- Upload ảnh đại diện.
- Phân quyền theo vai trò (Admin, Nhân viên).

### Giao diện
- Responsive, thân thiện với người dùng.
- Thông báo **toast** khi thao tác thành công hoặc thất bại.

---

## 🔒 Tài khoản mặc định

| Username | Password | Role          |
|----------|----------|---------------|
| admin    | Admin    | Quản trị viên |

> Bạn nên đổi mật khẩu sau khi cài đặt lần đầu.

---

## ⚠️ Ghi chú

- Đường dẫn và hằng số được khai báo trong `database.php`.  
- Tất cả thao tác CRUD đều có **xác nhận trước khi xóa**.  
- Hệ thống sử dụng `session` để kiểm tra đăng nhập.  
- Hệ thống hiện chưa áp dụng prepared statements, nên chỉ chạy trên môi trường **local**.

---

## 📞 Liên hệ

- **Người phát triển**: Hứa Viết Thái
- **Email**: huavietthai299@gmail.com
- **Github**: https://github.com/hvt299

---

## ✅ Hướng dẫn nâng cao

1. **Toast thông báo**:
- Khi thêm, sửa, xóa thành công: `?success=1`  
- Khi xóa thất bại: `?error=delete`  
- Toast hiển thị tự động bằng JS, góc dưới màn hình, tự ẩn sau 3 giây.

2. **Hóa đơn tự động**:
- Checkout chuồng -> tạo invoice.
- Tính số ngày lưu chuồng, phụ thu trễ giờ.
- Tự động thêm chi tiết dịch vụ vào `invoice_details`.

3. **Quản lý vaccine**:
- Khi loại hồ sơ là Vaccine, tự động thêm bản ghi vào bảng `vaccination_records`.
- Khi xóa hoặc thay đổi loại, cập nhật hoặc xóa bản ghi liên quan.

---