<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/diagnosis_function.php');
require_once(APP_PATH . '/treatment_session_function.php');

$treatment_session_id = isset($_GET['treatment_session_id']) ? intval($_GET['treatment_session_id']) : 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $treatment_session_id = trim($_POST['treatment_session_id']);
    $diagnosis_name       = trim($_POST['diagnosis_name']);
    $diagnosis_type       = trim($_POST['diagnosis_type']);
    $clinical_tests       = !empty($_POST['clinical_tests']) ? trim($_POST['clinical_tests']) : null;
    $notes                = !empty($_POST['notes']) ? trim($_POST['notes']) : null;

    if (!empty($treatment_session_id) && !empty($diagnosis_name) && isset($diagnosis_type)) {
        $result = addDiagnosis($treatment_session_id, $diagnosis_name, $diagnosis_type, $clinical_tests, $notes);

        if ($result) {
            header("Location: diagnoses.php?treatment_session_id=$treatment_session_id&success=1&msg=" . urlencode("Thêm chẩn đoán thành công!"));
            exit;
        } else {
            header("Location: diagnoses.php?treatment_session_id=$treatment_session_id&error=1&msg=" . urlencode("Thêm chẩn đoán thất bại!"));
            exit;
        }
    } else {
        header("Location: diagnoses.php?treatment_session_id=$treatment_session_id&error=1&msg=" . urlencode("Vui lòng nhập đầy đủ thông tin bắt buộc!"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm chẩn đoán - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm chẩn đoán</h2>
                <form action="" method="post">
                    <input type="hidden" name="treatment_session_id" value="<?= htmlspecialchars($treatment_session_id) ?>">

                    <div class="form-group">
                        <label for="diagnosis_name">Tên chẩn đoán <span class="required-field">*</span></label>
                        <input type="text" name="diagnosis_name" id="diagnosis_name" required>
                    </div>

                    <div class="form-group">
                        <label for="diagnosis_type">Loại chẩn đoán <span class="required-field">*</span></label>
                        <select name="diagnosis_type" id="diagnosis_type" required>
                            <option value="">-- Chọn loại --</option>
                            <option value="1">Chính</option>
                            <option value="0">Phụ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="clinical_tests">Xét nghiệm lâm sàng</label>
                        <textarea name="clinical_tests" id="clinical_tests"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea name="notes" id="notes"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="diagnoses.php?treatment_session_id=<?= $treatment_session_id ?>" class="btn btn-cancel">Hủy</a>
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