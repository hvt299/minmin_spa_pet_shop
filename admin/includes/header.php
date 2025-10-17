<?php
require_once(dirname(__DIR__) . '/init.php');
require_once(APP_PATH . '/user_function.php');
require_once(APP_PATH . '/general_setting_function.php');
$settings = getGeneralSettings();
?>
<header class="topbar">
    <div class="topbar__left">
        <button class="topbar__toggle" id="toggleSidebar"><i class="fas fa-bars"></i></button>
        <div class="topbar__clinic-info">
            <div class="clinic-name"><?= htmlspecialchars($settings['clinic_name'] ?? 'Phòng khám thú y') ?></div>
            <div class="clinic-details">
                <?= htmlspecialchars($settings['clinic_address_1'] ?? '') ?>
                <?= isset($settings['clinic_address_2']) && $settings['clinic_address_2'] ? ', ' . htmlspecialchars($settings['clinic_address_2']) : '' ?>
                <?= isset($settings['phone_number_1']) && $settings['phone_number_1'] ? ' | ' . htmlspecialchars($settings['phone_number_1']) : '' ?>
                <?= isset($settings['phone_number_2']) && $settings['phone_number_2'] ? ' - ' . htmlspecialchars($settings['phone_number_2']) : '' ?>
            </div>
        </div>
    </div>
    <input type="text" id="topbar__search" name="topbar__search" class="topbar__search" placeholder="Tìm kiếm..." />
    <div class="topbar__actions">
        <button id="themeToggle" class="theme-toggle" title="Chuyển giao diện">
            <i class="fa-solid fa-moon"></i>
        </button>
        <div class="topbar__user">
            <div class="topbar__user-info">
                <img src="<?= ADMIN_URL ?>/<?= htmlspecialchars(getUserByUsername($_SESSION['username'])['avatar'] ?: 'assets/images/default-avatar.jpg') ?>" alt="avatar" class="topbar__avatar" />
                <span class="topbar__username"><?php echo $_SESSION['fullname']; ?></span>
            </div>
            <div class="topbar__dropdown">
                <a href="<?= ADMIN_URL ?>/pages/user/edit_user.php?id=<?= getUserByUsername($_SESSION['username'])["id"]; ?>" class="topbar__myaccount">
                    <i class="fa-solid fa-circle-user"></i> Thông tin tài khoản
                </a>
                <a href="<?= ADMIN_URL ?>/pages/user/change_password.php?id=<?= getUserByUsername($_SESSION['username'])["id"]; ?>" class="topbar__changepassword">
                    <i class="fa-solid fas fa-key"></i> Đổi mật khẩu
                </a>
                <a href="<?= APP_URL ?>/logout.php" class="topbar__logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
</header>