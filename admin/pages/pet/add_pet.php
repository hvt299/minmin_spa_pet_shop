<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');

// Lấy danh sách khách hàng để hiển thị trong dropdown
$customers = getAllCustomers();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id = intval($_POST['customer_id']);
    $name        = trim($_POST['name']);
    $species     = trim($_POST['species']) ?: null;
    $gender      = intval($_POST['gender']);
    $dob         = trim($_POST['dob']) ?: null;
    $weight      = ($_POST['weight'] !== '' ? floatval($_POST['weight']) : null);
    $sterilization = $_POST['sterilization'] !== '' ? intval($_POST['sterilization']) : null;
    $characteristic = trim($_POST['characteristic']) ?: null;
    $allergy     = trim($_POST['allergy']) ?: null;

    if (!empty($customer_id) && !empty($name) && ($gender === 0 || $gender === 1)) {
        if (addPet($customer_id, $name, $species, $gender, $dob, $weight, $sterilization, $characteristic, $allergy)) {
            header("Location: pets.php?success=1&msg=" . urlencode("Thêm thú cưng thành công!"));
            exit;
        } else {
            header("Location: pets.php?error=1&msg=" . urlencode("Thêm thú cưng thất bại!"));
            exit;
        }
    } else {
        header("Location: pets.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc!"));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thú cưng - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm thú cưng mới</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="customer_id">Chủ nuôi <span class="required-field">*</span></label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn chủ nuôi --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?php echo $c['customer_id']; ?>">
                                    <?php echo htmlspecialchars($c['customer_name']) . " - " . $c['customer_phone_number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Tên thú <span class="required-field">*</span></label>
                        <input type="text" id="name" name="name" placeholder="Nhập tên thú cưng" required>
                    </div>
                    <div class="form-group">
                        <label for="species">Loài/Giống</label>
                        <input type="text" id="species" name="species" placeholder="Ví dụ: Chó Poodle, Mèo Anh lông ngắn...">
                    </div>
                    <div class="form-group">
                        <label for="gender">Giới tính <span class="required-field">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="0">Đực</option>
                            <option value="1">Cái</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dob">Ngày sinh (ước tính)</label>
                        <input type="date" id="dob" name="dob">
                    </div>
                    <div class="form-group">
                        <label for="weight">Cân nặng (kg)</label>
                        <input type="number" step="0.01" id="weight" name="weight" placeholder="Ví dụ: 5.25">
                    </div>
                    <div class="form-group">
                        <label for="sterilization">Đã triệt sản</label>
                        <select id="sterilization" name="sterilization">
                            <option value="">-- Chọn lựa --</option>
                            <option value="0">Chưa</option>
                            <option value="1">Rồi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="characteristic">Đặc điểm</label>
                        <textarea id="characteristic" name="characteristic" rows="3" placeholder="Nhập các đặc điểm nhận dạng..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="allergy">Dị ứng thuốc</label>
                        <textarea id="allergy" name="allergy" rows="3" placeholder="Nhập các loại thuốc bị dị ứng..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="pets.php" class="btn btn-cancel">Hủy</a>
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
