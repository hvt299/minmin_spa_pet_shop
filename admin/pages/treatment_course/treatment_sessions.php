<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/treatment_session_function.php');
require_once(APP_PATH . '/doctor_function.php');
require_once(APP_PATH . '/treatment_course_function.php');

$treatment_course_id = isset($_GET['treatment_course_id']) ? intval($_GET['treatment_course_id']) : 0;
$treatment_course = getTreatmentCourseById($treatment_course_id);
if (!$treatment_course) {
    die("Đợt điều trị không tồn tại.");
}

$doctors = getAllDoctors();

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$treatment_sessions = getTreatmentSessionsByTreatmentCourseIdPaginated($treatment_course_id, $limit, $offset);
$totalSessions = getTreatmentSessionsByTreatmentCourseIdCount($treatment_course_id);
$totalPages = ceil($totalSessions / $limit);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lần khám - Đợt <?= htmlspecialchars($treatment_course_id) ?> | Spa Thú Cưng Min Min</title>
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
            <h1>Lần khám - Đợt điều trị #<?= htmlspecialchars($treatment_course_id) ?></h1>

            <div class="back-box">
                <a href="treatment_courses.php" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại đợt khám
                </a>
            </div>

            <!-- Bộ lọc -->
            <div class="filter-box">
                <select id="doctorFilter">
                    <option value="">-- Tất cả bác sĩ --</option>
                    <?php foreach ($doctors as $d): ?>
                        <option value="<?= $d['doctor_id']; ?>">
                            <?= htmlspecialchars($d['doctor_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="text" id="searchInput" placeholder="Tìm theo bác sĩ, ngày khám, ghi chú...">
                <a href="add_treatment_session.php?treatment_course_id=<?= $treatment_course_id ?>" class="btn btn-add">
                    <i class="fas fa-plus"></i> Thêm lần khám
                </a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="sessionTable">
                    <thead>
                        <tr>
                            <th>Mã lần khám</th>
                            <th>Bác sĩ</th>
                            <th>Ngày khám</th>
                            <th>Nhiệt độ (°C)</th>
                            <th>Cân nặng (kg)</th>
                            <th>Mạch (HR)</th>
                            <th>Nhịp thở (RR)</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($treatment_sessions): ?>
                            <?php foreach ($treatment_sessions as $s): ?>
                                <?php
                                $doctor = getDoctorById($s['doctor_id']);
                                $doctor_name = is_array($doctor) && isset($doctor['doctor_name'])
                                    ? $doctor['doctor_name']
                                    : 'Không xác định';
                                ?>
                                <tr data-doctor="<?= $s['doctor_id']; ?>">
                                    <td><?= htmlspecialchars($s['treatment_session_id']); ?></td>
                                    <td><?= htmlspecialchars($doctor_name); ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($s['treatment_session_datetime'])); ?></td>
                                    <td><?= htmlspecialchars($s['temperature']); ?></td>
                                    <td><?= htmlspecialchars($s['weight']); ?></td>
                                    <td><?= htmlspecialchars($s['pulse_rate'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($s['respiratory_rate'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($s['overall_notes'] ?? ''); ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_treatment_session.php?id=<?= $s['treatment_session_id']; ?>&treatment_course_id=<?= $treatment_course_id ?>"
                                                class="btn btn-icon btn-edit"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="diagnoses.php?treatment_session_id=<?= $s['treatment_session_id']; ?>"
                                                class="btn btn-icon btn-diagnose"
                                                title="Chẩn đoán">
                                                <i class="fas fa-diagnoses"></i>
                                            </a>

                                            <a href="prescriptions.php?treatment_session_id=<?= $s['treatment_session_id']; ?>"
                                                class="btn btn-icon btn-prescription"
                                                title="Đơn thuốc">
                                                <i class="fas fa-prescription-bottle"></i>
                                            </a>

                                            <a href="delete_treatment_session.php?id=<?= $s['treatment_session_id']; ?>&treatment_course_id=<?= $treatment_course_id ?>"
                                                class="btn btn-icon btn-delete"
                                                title="Xóa"
                                                onclick="return confirm('Bạn có chắc muốn xóa lần khám này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Chưa có lần khám nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?treatment_course_id=<?= $treatment_course_id ?>&page=<?= $page - 1 ?>" class="page-link">&laquo; Trước</a>
                        <?php endif; ?>

                        <?php
                        $maxLinks = 5;
                        $start = max(1, $page - floor($maxLinks / 2));
                        $end = min($totalPages, $start + $maxLinks - 1);

                        if ($end - $start < $maxLinks - 1) {
                            $start = max(1, $end - $maxLinks + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?treatment_course_id=<?= $treatment_course_id ?>&page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?treatment_course_id=<?= $treatment_course_id ?>&page=<?= $page + 1 ?>" class="page-link">Sau &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
        const doctorFilter = document.getElementById("doctorFilter");
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#sessionTable tbody tr");

        function filterSessions() {
            const doctorId = doctorFilter.value;
            const keyword = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const matchDoctor = !doctorId || row.getAttribute("data-doctor") === doctorId;
                const text = row.innerText.toLowerCase();
                const matchSearch = text.includes(keyword);
                row.style.display = (matchDoctor && matchSearch) ? "" : "none";
            });
        }

        doctorFilter.addEventListener("change", filterSessions);
        searchInput.addEventListener("keyup", filterSessions);
    </script>
</body>

</html>