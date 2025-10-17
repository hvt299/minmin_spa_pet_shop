<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/user_function.php');

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = intval($_GET['id']);
$user = getUserById($user_id);

if (!$user) {
    header("Location: users.php?error=1&msg=" . urlencode("ID người dùng không hợp lệ!"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $avatarPath = $user['avatar']; // giữ ảnh cũ nếu không upload mới

    if (!empty($_FILES['avatar']['name'])) {
        $uploadDir = dirname(__DIR__, 2) . '/assets/images/';
        $fileName = time() . '_' . basename($_FILES['avatar']['name']);
        $targetFile = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile);
            $avatarPath = 'assets/images/' . $fileName;
        }
    }

    $role = $_POST['role'] ?? '';

    if (!empty($username) && !empty($fullname)) {
        if (updateUser($user_id, $username, $fullname, $avatarPath, $role)) {
            header("Location: users.php?success=2&msg=" . urlencode("Cập nhật người dùng thành công!"));
            exit;
        } else {
            header("Location: users.php?error=1&msg=" . urlencode("Cập nhật người dùng thất bại."));
            exit;
        }
    } else {
        header("Location: users.php?error=1&msg=" . urlencode("Tên đăng nhập và họ tên không được để trống."));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa người dùng - Spa Thú Cưng Min Min</title>
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
                <h2>Chỉnh sửa thông tin người dùng</h2>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập <span class="required-field">*</span></label>
                        <input type="text" id="username" name="username"
                            placeholder="Nhập tên đăng nhập"
                            value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fullname">Họ và tên <span class="required-field">*</span></label>
                        <input type="text" id="fullname" name="fullname"
                            placeholder="Nhập họ tên đầy đủ"
                            value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="avatar">Ảnh đại diện</label>
                        <img id="avatarPreview" class="avatar-preview"
                            src="<?= ADMIN_URL ?>/<?= htmlspecialchars($user['avatar'] ?: 'assets/images/default-avatar.jpg') ?>"
                            alt="Avatar Preview">
                        <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="form-group">
                        <label for="role">Vai trò <span class="required-field">*</span></label>
                        <select name="role" id="role" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="staff" <?= ($user['role'] === 'staff') ? 'selected' : ''; ?>>Nhân viên</option>
                            <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : ''; ?>>Quản trị viên</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="create_at">Ngày tạo</label>
                        <input type="text" id="create_at" name="create_at"
                            value="<?php echo htmlspecialchars($user['create_at']); ?>" readonly>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="users.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('avatarPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>