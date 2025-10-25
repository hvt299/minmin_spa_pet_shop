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
require_once(APP_PATH . '/vaccine_function.php');
require_once(APP_PATH . '/pet_vaccination_function.php');

// Lấy dữ liệu
$customers = getAllCustomers();
$pets = getAllPets();
$doctors = getAllDoctors();
$vaccines = getAllVaccines();

// Phân trang
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$vaccinations = getPetVaccinationsPaginated($limit, $offset);
$totalVaccinations = getPetVaccinationCount();
$totalPages = ceil($totalVaccinations / $limit);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử tiêm vaccine - Spa Thú Cưng Min Min</title>
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
            <h1>Lịch sử tiêm vaccine</h1>

            <!-- Bộ lọc -->
            <div class="filter-box">
                <select id="customerFilter">
                    <option value="">-- Tất cả chủ nuôi --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['customer_id'] ?>"><?= htmlspecialchars($c['customer_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="petFilter">
                    <option value="">-- Tất cả thú cưng --</option>
                    <?php foreach ($pets as $p): ?>
                        <option value="<?= $p['pet_id'] ?>"><?= htmlspecialchars($p['pet_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Bộ lọc theo thời gian tiêm tiếp theo -->
                <select id="dateFilter">
                    <option value="">-- Tất cả --</option>
                    <option value="7">Trong 7 ngày tới</option>
                    <option value="14">Trong 14 ngày tới</option>
                    <option value="30">Trong 30 ngày tới</option>
                    <option value="overdue">Đã quá hạn</option>
                </select>

                <input type="text" id="searchInput" placeholder="Tìm theo vaccine, bác sĩ...">
                <a href="add_pet_vaccination.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm lịch tiêm</a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="vaccinationTable">
                    <thead>
                        <tr>
                            <th>Chủ nuôi</th>
                            <th>Thú cưng</th>
                            <th>Vaccine</th>
                            <th>Bác sĩ tiêm</th>
                            <th>Ngày tiêm</th>
                            <th>Ngày tiêm tiếp theo</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($vaccinations): ?>
                            <?php foreach ($vaccinations as $row): ?>
                                <?php
                                $nextDate = $row['next_vaccination_date'];
                                $badgeHTML = '';

                                if ($nextDate) {
                                    $today = new DateTime();
                                    $next = new DateTime($nextDate);
                                    $diffDays = (int)$next->diff($today)->format('%r%a'); // có dấu âm/dương
                                    $daysRemaining = abs($diffDays);

                                    if ($diffDays < -7) {
                                        // Còn hơn 7 ngày
                                        $badgeHTML = '<span class="badge badge-green">Còn ' . $daysRemaining . ' ngày</span>';
                                    } elseif ($diffDays < 0) {
                                        // Trong vòng 7 ngày tới
                                        if ($daysRemaining === 0) {
                                            $badgeHTML = '<span class="badge badge-yellow">Hôm nay</span>';
                                        } else {
                                            $badgeHTML = '<span class="badge badge-yellow">Còn ' . $daysRemaining . ' ngày</span>';
                                        }
                                    } elseif ($diffDays === 0) {
                                        $badgeHTML = '<span class="badge badge-yellow">Hôm nay</span>';
                                    } else {
                                        // Đã trễ hạn
                                        $badgeHTML = '<span class="badge badge-red">Trễ ' . $daysRemaining . ' ngày</span>';
                                    }
                                }
                                ?>
                                <tr data-customer="<?= $row['customer_id'] ?>" data-pet="<?= $row['pet_id'] ?>" data-next="<?= $row['next_vaccination_date'] ?>">
                                    <td><?= htmlspecialchars(getCustomerById($row['customer_id'])['customer_name']); ?></td>
                                    <td><?= htmlspecialchars(getPetById($row['pet_id'])['pet_name']); ?></td>
                                    <td><?= htmlspecialchars(getVaccineById($row['vaccine_id'])['vaccine_name']); ?></td>
                                    <td><?= htmlspecialchars(getDoctorById($row['doctor_id'])['doctor_name']); ?></td>
                                    <td><?= date("d-m-Y", strtotime($row['vaccination_date'])) ?></td>
                                    <td>
                                        <?php if ($nextDate): ?>
                                            <?= date("d-m-Y", strtotime($nextDate)) ?><br>
                                            <?= $badgeHTML ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['notes'] ?? '') ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_pet_vaccination.php?id=<?= $row['pet_vaccination_id'] ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="delete_pet_vaccination.php?id=<?= $row['pet_vaccination_id'] ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa lịch tiêm này không?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Chưa có dữ liệu lịch tiêm vaccine.</td>
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
                        $maxLinks = 5;
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
        const dateFilter = document.getElementById("dateFilter");
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#vaccinationTable tbody tr");

        function filterRecords() {
            const customerId = customerFilter.value;
            const petId = petFilter.value;
            const keyword = searchInput.value.toLowerCase();
            const dateOption = dateFilter.value;
            const today = new Date();

            rows.forEach(row => {
                const matchCustomer = !customerId || row.getAttribute("data-customer") === customerId;
                const matchPet = !petId || row.getAttribute("data-pet") === petId;
                const text = row.innerText.toLowerCase();
                const matchSearch = text.includes(keyword);

                let matchDate = true;
                const nextDateStr = row.getAttribute("data-next");

                if (dateOption) {
                    if (!nextDateStr || nextDateStr === "null") {
                        // Không có ngày tiêm tiếp theo → không hiển thị ở bất kỳ bộ lọc nào trừ “Tất cả”
                        matchDate = false;
                    } else {
                        const nextDate = new Date(nextDateStr);
                        const diffDays = Math.ceil((nextDate - today) / (1000 * 60 * 60 * 24));

                        if (dateOption === "overdue") {
                            matchDate = diffDays < 0;
                        } else {
                            matchDate = diffDays >= 0 && diffDays <= parseInt(dateOption);
                        }
                    }
                }

                row.style.display = (matchCustomer && matchPet && matchSearch && matchDate) ? "" : "none";
            });
        }

        customerFilter.addEventListener("change", filterRecords);
        petFilter.addEventListener("change", filterRecords);
        dateFilter.addEventListener("change", filterRecords);
        searchInput.addEventListener("keyup", filterRecords);
    </script>
</body>

</html>