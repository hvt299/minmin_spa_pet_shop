<?php
// Đường dẫn vật lý (trên máy chủ) (Gọi file PHP (require, include))
define('ADMIN_ROOT', dirname(__FILE__)); // ví dụ: /var/www/html/project/admin
define('APP_PATH', dirname(__DIR__) . '/app');
define('ADMIN_INC', ADMIN_ROOT . '/includes');

// Đường dẫn URL (cho trình duyệt) (Tạo link cho trình duyệt (href, src, header("Location:")))
define('BASE_URL', '/minmin_spa_pet_shop'); // ví dụ: http://localhost/myproject
define('ADMIN_URL', BASE_URL . '/admin');
define('APP_URL', BASE_URL . '/app');
?>
<link rel="icon" type="image/x-icon" href="<?= ADMIN_URL ?>/assets/images/logo-shop.jpg">