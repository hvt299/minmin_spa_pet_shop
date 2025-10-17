<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/doctor_function.php');
require_once(APP_PATH . '/medical_record_function.php');

// $records = getAllMedicalRecords();
$customers = getAllCustomers();
$pets = getAllPets();
$doctors = getAllDoctors();

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$records = getMedicalRecordsPaginated($limit, $offset);
$totalRecords = getMedicalRecordCount();
$totalPages = ceil($totalRecords / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử khám & điều trị - Spa Thú Cưng Min Min</title>
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
            <h1>Lịch sử khám & điều trị</h1>

            <!-- Bộ lọc -->
            <div class="filter-box">
                <select id="customerFilter">
                    <option value="">-- Tất cả chủ nuôi --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['customer_id']; ?>">
                            <?php echo htmlspecialchars($c['customer_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select id="petFilter">
                    <option value="">-- Tất cả thú cưng --</option>
                    <?php foreach ($pets as $p): ?>
                        <option value="<?php echo $p['pet_id']; ?>">
                            <?php echo htmlspecialchars($p['pet_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="text" id="searchInput" placeholder="Tìm theo thú cưng, loại, bác sĩ, vaccine...">
                <a href="add_medical_record.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm lịch sử</a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="recordTable">
                    <thead>
                        <tr>
                            <th>Ngày khám</th>
                            <th>Tên thú</th>
                            <th>Chủ nuôi</th>
                            <th>Loại</th>
                            <th>Tóm tắt</th>
                            <th>Bác sĩ</th>
                            <th>Vaccine</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($records): ?>
                            <?php foreach ($records as $row): ?>
                                <tr data-customer="<?php echo $row['customer_id']; ?>" data-pet="<?php echo $row['pet_id']; ?>">
                                    <td>
                                        <?php
                                        echo !empty($row['medical_record_visit_date'])
                                            ? date('d-m-Y', strtotime($row['medical_record_visit_date']))
                                            : '';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars(getPetById($row['pet_id'])['pet_name']); ?></td>
                                    <td><?php echo htmlspecialchars(getCustomerById($row['customer_id'])['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['medical_record_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['medical_record_summary']); ?></td>
                                    <td><?php echo htmlspecialchars(getDoctorById($row['doctor_id'])['doctor_name']); ?></td>
                                    <td>
                                        <?php
                                        if ($row['medical_record_type'] === 'Vaccine') {
                                            $vacc = getVaccinationByMedicalId($row['medical_record_id']);
                                            if ($vacc) {
                                                echo htmlspecialchars($vacc['vaccine_name']);
                                                if (!empty($vacc['next_injection_date']) && $vacc['next_injection_date'] !== '0000-00-00') {
                                                    $formattedDate = date("d/m/Y", strtotime($vacc['next_injection_date']));
                                                    echo " (hẹn: " . htmlspecialchars($formattedDate) . ")";
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_medical_record.php?id=<?php echo $row['medical_record_id']; ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="delete_medical_record.php?id=<?php echo $row['medical_record_id']; ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa hồ sơ này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Chưa có hồ sơ nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="page-link">&laquo; Trước</a>
                        <?php endif; ?>

                        <?php
                        $maxLinks = 5; // số lượng nút trang hiển thị
                        $start = max(1, $page - floor($maxLinks / 2));
                        $end = min($totalPages, $start + $maxLinks - 1);

                        if ($end - $start < $maxLinks - 1) {
                            $start = max(1, $end - $maxLinks + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="page-link">Sau &raquo;</a>
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
        const customerFilter = document.getElementById("customerFilter");
        const petFilter = document.getElementById("petFilter");
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#recordTable tbody tr");

        function filterRecords() {
            const customerId = customerFilter.value;
            const petId = petFilter.value;
            const keyword = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const matchCustomer = !customerId || row.getAttribute("data-customer") === customerId;
                const matchPet = !petId || row.getAttribute("data-pet") === petId;
                const text = row.innerText.toLowerCase();
                const matchSearch = text.includes(keyword);

                row.style.display = (matchCustomer && matchPet && matchSearch) ? "" : "none";
            });
        }

        customerFilter.addEventListener("change", filterRecords);
        petFilter.addEventListener("change", filterRecords);
        searchInput.addEventListener("keyup", filterRecords);
    </script>
</body>

</html>