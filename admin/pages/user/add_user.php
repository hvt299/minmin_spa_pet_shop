<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/user_function.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $avatarPath = null;

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

    if ($username === '' || $password === '' || $fullname === '') {
        header("Location: users.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ các trường bắt buộc."));
        exit;
    }

    $hashedPassword = md5($password);
    if (addUser($username, $hashedPassword, $fullname, $avatarPath, $role)) {
        header("Location: users.php?success=1&msg=" . urlencode("Thêm người dùng thành công!"));
        exit;
    } else {
        header("Location: users.php?error=1&msg=" . urlencode("Thêm người dùng thất bại — có thể tên đăng nhập đã tồn tại."));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm người dùng - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm người dùng mới</h2>

                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập <span class="required-field">*</span></label>
                        <input type="text" id="username" name="username" required placeholder="Nhập tên đăng nhập">
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu <span class="required-field">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu">
                    </div>

                    <div class="form-group">
                        <label for="fullname">Họ tên đầy đủ <span class="required-field">*</span></label>
                        <input type="text" id="fullname" name="fullname" required placeholder="Nhập họ và tên">
                    </div>

                    <div class="form-group">
                        <label for="avatar">Ảnh đại diện</label>
                        <img id="avatarPreview" class="avatar-preview" src="<?= ADMIN_URL ?>/assets/images/default-avatar.jpg" alt="Avatar Preview">
                        <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewImage(event)">
                    </div>

                    <div class="form-group">
                        <label for="role">Vai trò <span class="required-field">*</span></label>
                        <select name="role" id="role" required>
                            <option value="">-- Chọn vai trò --</option>
                            <option value="staff">Nhân viên</option>
                            <option value="admin">Quản trị viên</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
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