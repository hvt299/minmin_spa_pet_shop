<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/doctor_function.php');
require_once(APP_PATH . '/treatment_session_function.php');

$doctors = getAllDoctors();

// Nếu không có id -> quay lại danh sách
if (!isset($_GET['id'])) {
    header("Location: treatment_sessions.php");
    exit;
}

$id = intval($_GET['id']);
$treatment_session = getTreatmentSessionById($id);

if (!$treatment_session) {
    header("Location: treatment_sessions.php?error=1&msg=" . urlencode("Không tìm thấy lần khám!"));
    exit;
}

$treatment_course_id = $treatment_session['treatment_course_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $doctor_id         = trim($_POST['doctor_id']);
    $datetime          = trim($_POST['treatment_session_datetime']);
    $temperature       = trim($_POST['temperature']);
    $weight            = trim($_POST['weight']);
    $pulse_rate        = !empty($_POST['pulse_rate']) ? trim($_POST['pulse_rate']) : null;
    $respiratory_rate  = !empty($_POST['respiratory_rate']) ? trim($_POST['respiratory_rate']) : null;
    $overall_notes     = !empty($_POST['overall_notes']) ? trim($_POST['overall_notes']) : null;

    if (!empty($doctor_id) && !empty($datetime) && !empty($temperature) && !empty($weight)) {
        $result = updateTreatmentSession($id, $doctor_id, $datetime, $temperature, $weight, $pulse_rate, $respiratory_rate, $overall_notes);

        if ($result) {
            header("Location: treatment_sessions.php?treatment_course_id=$treatment_course_id&success=1&msg=" . urlencode("Cập nhật lần khám thành công!"));
            exit;
        } else {
            header("Location: treatment_sessions.php?treatment_course_id=$treatment_course_id&error=1&msg=" . urlencode("Cập nhật thất bại!"));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa lần khám - Spa Thú Cưng Min Min</title>
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
                <h2>Sửa lần khám</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="doctor_id">Bác sĩ <span class="required-field">*</span></label>
                        <select name="doctor_id" id="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?= $d['doctor_id'] ?>" <?= $d['doctor_id'] == $treatment_session['doctor_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['doctor_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="treatment_session_datetime">Ngày giờ khám <span class="required-field">*</span></label>
                        <input type="datetime-local" name="treatment_session_datetime" id="treatment_session_datetime"
                            value="<?= date('Y-m-d\TH:i', strtotime($treatment_session['treatment_session_datetime'])) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="temperature">Nhiệt độ (°C) <span class="required-field">*</span></label>
                        <input type="number" step="0.01" name="temperature" id="temperature" value="<?= $treatment_session['temperature'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="weight">Cân nặng (kg) <span class="required-field">*</span></label>
                        <input type="number" step="0.01" name="weight" id="weight" value="<?= $treatment_session['weight'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="pulse_rate">Mạch (HR)</label>
                        <input type="number" name="pulse_rate" id="pulse_rate" value="<?= $treatment_session['pulse_rate'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="respiratory_rate">Nhịp thở (RR)</label>
                        <input type="number" name="respiratory_rate" id="respiratory_rate" value="<?= $treatment_session['respiratory_rate'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="overall_notes">Ghi chú</label>
                        <textarea name="overall_notes" id="overall_notes"><?= htmlspecialchars($treatment_session['overall_notes']) ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="treatment_sessions.php?treatment_course_id=<?= $treatment_course_id ?>" class="btn btn-cancel">Hủy</a>
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