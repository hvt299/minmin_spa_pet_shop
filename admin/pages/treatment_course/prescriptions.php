<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/prescription_function.php');
require_once(APP_PATH . '/medicine_function.php');
require_once(APP_PATH . '/treatment_session_function.php');

$treatment_session_id = isset($_GET['treatment_session_id']) ? intval($_GET['treatment_session_id']) : 0;
$treatment_session = getTreatmentSessionById($treatment_session_id);
if (!$treatment_session) {
    die("Lần khám không tồn tại.");
}

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$prescriptions = getPrescriptionsByTreatmentSessionIdPaginated($treatment_session_id, $limit, $offset);
$totalPrescriptions = getPrescriptionsByTreatmentSessionIdCount($treatment_session_id);
$totalPages = ceil($totalPrescriptions / $limit);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn thuốc - Lần khám #<?= htmlspecialchars($treatment_session_id) ?> | Spa Thú Cưng Min Min</title>
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
            <h1>Đơn thuốc - Lần khám #<?= htmlspecialchars($treatment_session_id) ?></h1>

            <div class="back-box">
                <a href="treatment_sessions.php?treatment_course_id=<?= htmlspecialchars($treatment_session['treatment_course_id']) ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại lần khám
                </a>
            </div>

            <div class="filter-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên thuốc, loại, tình trạng...">
                <a href="add_prescription.php?treatment_session_id=<?= $treatment_session_id ?>" class="btn btn-add">
                    <i class="fas fa-plus"></i> Thêm đơn thuốc
                </a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="prescriptionTable">
                    <thead>
                        <tr>
                            <th>Mã đơn thuốc</th>
                            <th>Tên thuốc</th>
                            <th>Hình thức</th>
                            <th>Liều lượng</th>
                            <th>Đơn vị</th>
                            <th>Tần suất</th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($prescriptions): ?>
                            <?php foreach ($prescriptions as $p): ?>
                                <?php $medicine = getMedicineById($p['medicine_id']); ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['prescription_id']); ?></td>
                                    <td><?= htmlspecialchars($medicine['medicine_name'] ?? 'Không xác định'); ?></td>
                                    <td><?= htmlspecialchars($p['treatment_type']); ?></td>
                                    <td><?= htmlspecialchars($p['dosage']); ?></td>
                                    <td><?= htmlspecialchars($p['unit']); ?></td>
                                    <td><?= htmlspecialchars($p['frequency'] ?? '-'); ?></td>
                                    <td><?= $p['status'] == '1' ? 'Đang thực hiện' : 'Đã làm'; ?></td>
                                    <td><?= htmlspecialchars($p['notes'] ?? ''); ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_prescription.php?id=<?= $p['prescription_id']; ?>&treatment_session_id=<?= $treatment_session_id ?>"
                                                class="btn btn-icon btn-edit"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_prescription.php?id=<?= $p['prescription_id']; ?>&treatment_session_id=<?= $treatment_session_id ?>"
                                                class="btn btn-icon btn-delete"
                                                title="Xóa"
                                                onclick="return confirm('Bạn có chắc muốn xóa đơn thuốc này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Chưa có đơn thuốc nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?treatment_session_id=<?= $treatment_session_id ?>&page=<?= $page - 1 ?>" class="page-link">&laquo; Trước</a>
                        <?php endif; ?>

                        <?php
                        $maxLinks = 5;
                        $start = max(1, $page - floor($maxLinks / 2));
                        $end = min($totalPages, $start + $maxLinks - 1);

                        if ($end - $start < $maxLinks - 1) {
                            $start = max(1, $end - $maxLinks + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?treatment_session_id=<?= $treatment_session_id ?>&page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?treatment_session_id=<?= $treatment_session_id ?>&page=<?= $page + 1 ?>" class="page-link">Sau &raquo;</a>
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
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#prescriptionTable tbody tr");

        function filterPrescriptions() {
            const keyword = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        }

        searchInput.addEventListener("keyup", filterPrescriptions);
    </script>
</body>

</html>