<?php
require_once(dirname(__DIR__) . '/init.php');
require_once(APP_PATH . '/general_setting_function.php');
$settings = getGeneralSettings();
?>
<footer class="footer">
    <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($settings['clinic_name'] ?? 'Phòng khám thú y') ?>. All Rights Reserved.</p>
</footer>