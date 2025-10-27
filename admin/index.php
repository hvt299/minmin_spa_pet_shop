<?php
session_start();

// Nếu đã đăng nhập thì chuyển hướng sang dashboard.php
if (isset($_SESSION['username']) && !isset($_SESSION['success'])) {
    header("Location: ./pages/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Spa Thú Cưng Min Min</title>
    <link rel="icon" type="image/x-icon" href="assets/images/logo-shop.jpg">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/grid.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="body__login">
    <section class="login">
        <div class="login__icon">
            <i class="fa-solid fa-key"></i>
        </div>
        <h2 class="login__title">Spa Thú Cưng Min Min</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="login__alert login__alert--error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="login__alert login__alert--success">
                <i class="fa-solid fa-circle-check"></i>
                <?php echo $_SESSION['success']; ?>
            </div>
        <?php endif; ?>

        <form class="login__form" action="../app/check_login.php" method="POST">
            <div class="login__group">
                <label for="username" class="login__label">Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="login__input" required>
            </div>

            <div class="login__group">
                <label for="password" class="login__label">Mật khẩu</label>
                <input type="password" id="password" name="password" class="login__input" required>
            </div>

            <button type="submit" class="login__button">Đăng nhập</button>
        </form>
    </section>

    <!-- ===== TỰ ĐỘNG CHUYỂN HƯỚNG SAU 2 GIÂY ===== -->
    <?php if (isset($_SESSION['success'])): ?>
        <script>
            setTimeout(() => {
                window.location.href = "./pages/dashboard.php";
            }, 2000); // delay 2 giây
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
</body>

</html>