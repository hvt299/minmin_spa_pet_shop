<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
if (!isset($_GET['id'])) {
    header("Location: customers.php");
    exit;
}

$customer_id = intval($_GET['id']);
$customer = getCustomerById($customer_id);

if (!$customer) {
    header("Location: customers.php?error=1&msg=" . urlencode("ID khách hàng không hợp lệ!"));
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $phone    = trim($_POST['phone']);
    $identity_card  = trim($_POST['identity_card']);
    $address  = trim($_POST['address']);
    $note     = trim($_POST['note']) ?: null;

    if (!empty($fullname) && !empty($phone) && !empty($identity_card) && !empty($address)) {
        if (updateCustomer($customer_id, $fullname, $phone, $identity_card, $address, $note)) {
            header("Location: customers.php?success=1&msg=" . urlencode("Cập nhật khách hàng thành công!"));
            exit;
        } else {
            header("Location: customers.php?error=1&msg=" . urlencode("Không thể cập nhật khách hàng. Vui lòng thử lại."));
            exit;
        }
    } else {
        header("Location: customers.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc."));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa khách hàng - Spa Thú Cưng Min Min</title>
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
                <h2>Chỉnh sửa khách hàng</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="fullname">Họ tên <span class="required-field">*</span></label>
                        <input type="text" id="fullname" name="fullname" placeholder="Nhập họ tên khách hàng" value="<?php echo htmlspecialchars($customer['customer_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại <span class="required-field">*</span></label>
                        <input type="number" id="phone" name="phone" placeholder="Nhập số điện thoại" value="<?php echo htmlspecialchars($customer['customer_phone_number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="identity_card">Thẻ căn cước <span class="required-field">*</span></label>
                        <input type="number" id="identity_card" name="identity_card" placeholder="Nhập thẻ căn cước" value="<?php echo htmlspecialchars($customer['customer_identity_card']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ <span class="required-field">*</span></label>
                        <input type="text" id="address" name="address" placeholder="Nhập địa chỉ khách hàng" value="<?php echo htmlspecialchars($customer['customer_address']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Ghi chú</label>
                        <textarea id="note" name="note" rows="3" placeholder="Nhập ghi chú..."><?php echo htmlspecialchars($customer['customer_note']); ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="customers.php" class="btn btn-cancel">Hủy</a>
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