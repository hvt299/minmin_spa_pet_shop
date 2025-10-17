<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/doctor_function.php');

if (!isset($_GET['id'])) {
    header("Location: doctors.php");
    exit;
}

$doctor_id = intval($_GET['id']);
$doctor = getDoctorById($doctor_id);

if (!$doctor) {
    header("Location: doctors.php?error=1&msg=" . urlencode("ID bác sĩ không hợp lệ!"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $phone    = trim($_POST['phone']);
    $identity_card  = trim($_POST['identity_card']);
    $address  = trim($_POST['address']);
    $note     = trim($_POST['note']) ?: null;

    if (!empty($fullname) && !empty($phone) && !empty($identity_card) && !empty($address)) {
        if (updateDoctor($doctor_id, $fullname, $phone, $identity_card, $address, $note)) {
            header("Location: doctors.php?success=1&msg=" . urlencode("Cập nhật bác sĩ thành công!"));
            exit;
        } else {
            header("Location: doctors.php?error=1&msg=" . urlencode("Cập nhật bác sĩ thất bại!"));
            exit;
        }
    } else {
        header("Location: doctors.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc."));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa bác sĩ - Spa Thú Cưng Min Min</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/grid.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <?php include_once(ADMIN_INC . '/sidebar.php'); ?>

    <main class="main">
        <!-- Header -->
        <?php include_once(ADMIN_INC . '/header.php'); ?>
        <!-- Main content -->
        <main class="content">
            <div class="form-container">
                <h2>Chỉnh sửa thông tin bác sĩ</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="fullname">Họ tên <span class="required-field">*</span></label>
                        <input type="text" id="fullname" name="fullname" placeholder="Nhập họ tên bác sĩ" value="<?php echo htmlspecialchars($doctor['doctor_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="required-field">*</span></label>
                        <input type="text" id="phone" name="phone" maxlength="11" placeholder="Nhập số điện thoại" value="<?php echo htmlspecialchars($doctor['doctor_phone_number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="identity_card">Thẻ căn cước <span class="required-field">*</span></label>
                        <input type="text" id="identity_card" name="identity_card" maxlength="12" placeholder="Nhập số CCCD" value="<?php echo htmlspecialchars($doctor['doctor_identity_card']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ <span class="required-field">*</span></label>
                        <input type="text" id="address" name="address" placeholder="Nhập địa chỉ bác sĩ" value="<?php echo htmlspecialchars($doctor['doctor_address']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" rows="3" placeholder="Nhập ghi chú..."><?php echo htmlspecialchars($doctor['doctor_note']); ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="doctors.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>
</body>

</html>