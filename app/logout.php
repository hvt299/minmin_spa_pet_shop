<?php
session_start();
session_destroy();
require_once(dirname(__DIR__) . '/admin/init.php');
header("Location: " . ADMIN_URL . "/index.php");
exit;
