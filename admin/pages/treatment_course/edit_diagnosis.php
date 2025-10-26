<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/diagnosis_function.php');

// Nếu không có id -> quay lại danh sách
if (!isset($_GET['id'])) {
    header("Location: treatment_sessions.php");
    exit;
}

$id = intval($_GET['id']);
$diagnosis = getDiagnosisById($id);

if (!$diagnosis) {
    header("Location: treatment_sessions.php?error=1&msg=" . urlencode("Không tìm thấy chẩn đoán!"));
    exit;
}

// Lấy session id để quay lại đúng đợt khám
$treatment_session_id = $diagnosis['treatment_session_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $diagnosis_name  = trim($_POST['diagnosis_name']);
    $diagnosis_type  = trim($_POST['diagnosis_type']);
    $clinical_tests  = !empty($_POST['clinical_tests']) ? trim($_POST['clinical_tests']) : null;
    $notes           = !empty($_POST['notes']) ? trim($_POST['notes']) : null;

    if (!empty($diagnosis_name)) {
        $result = updateDiagnosis($id, $diagnosis_name, $diagnosis_type, $clinical_tests, $notes);

        if ($result) {
            header("Location: diagnoses.php?treatment_session_id=$treatment_session_id&success=1&msg=" . urlencode("Cập nhật chẩn đoán thành công!"));
            exit;
        } else {
            header("Location: diagnoses.php?treatment_session_id=$treatment_session_id&error=1&msg=" . urlencode("Cập nhật chẩn đoán thất bại!"));
            exit;
        }
    } else {
        header("Location: diagnoses.php?treatment_session_id=$treatment_session_id&error=1&msg=" . urlencode("Vui lòng nhập tên chẩn đoán!"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa chẩn đoán - Spa Thú Cưng Min Min</title>
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
                <h2>Sửa chẩn đoán</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="diagnosis_name">Tên chẩn đoán <span class="required-field">*</span></label>
                        <input type="text" name="diagnosis_name" id="diagnosis_name"
                            value="<?= htmlspecialchars($diagnosis['diagnosis_name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="diagnosis_type">Loại chẩn đoán</label>
                        <select name="diagnosis_type" id="diagnosis_type" required>
                            <option value="1" <?= $diagnosis['diagnosis_type'] == '1' ? 'selected' : '' ?>>Chính</option>
                            <option value="0" <?= $diagnosis['diagnosis_type'] == '0' ? 'selected' : '' ?>>Phụ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="clinical_tests">Xét nghiệm lâm sàng</label>
                        <textarea name="clinical_tests" id="clinical_tests"><?= htmlspecialchars($diagnosis['clinical_tests']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea name="notes" id="notes"><?= htmlspecialchars($diagnosis['notes']) ?></textarea>
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