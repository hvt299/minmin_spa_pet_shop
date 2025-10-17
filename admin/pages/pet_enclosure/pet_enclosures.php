<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/pet_enclosure_function.php');

// $enclosures = getAllPetEnclosures();
$customers = getAllCustomers();
$pets = getAllPets();

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$enclosures = getPetEnclosuresPaginated($limit, $offset);
$totalEnclosures = getPetEnclosureCount();
$totalPages = ceil($totalEnclosures / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý chuồng thú - Spa Thú Cưng Min Min</title>
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
            <h1>Danh sách chuồng thú</h1>

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
                <input type="text" id="searchInput" placeholder="Tìm theo số chuồng, trạng thái...">
                <a href="add_pet_enclosure.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm chuồng thú</a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="enclosureTable">
                    <thead>
                        <tr>
                            <th>Chủ nuôi</th>
                            <th>Tên thú</th>
                            <th>Số chuồng</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Đơn giá/ngày (₫)</th>
                            <th>Tiền cọc (₫)</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($enclosures): ?>
                            <?php foreach ($enclosures as $row): ?>
                                <tr data-customer="<?= $row['customer_id'] ?>" data-pet="<?= $row['pet_id'] ?>">
                                    <td><?= htmlspecialchars(getCustomerById($row['customer_id'])['customer_name']); ?></td>
                                    <td><?= htmlspecialchars(getPetById($row['pet_id'])['pet_name']); ?></td>
                                    <td><?= htmlspecialchars($row['pet_enclosure_number']); ?></td>
                                    <td><?= (!empty($row['check_in_date']) && $row['check_in_date'] !== '0000-00-00' && $row['check_in_date'] !== '0000-00-00 00:00:00') ? date("d-m-Y H:i", strtotime($row['check_in_date'])) : '' ?></td>
                                    <td><?= (!empty($row['check_out_date']) && $row['check_out_date'] !== '0000-00-00' && $row['check_out_date'] !== '0000-00-00 00:00:00') ? date("d-m-Y H:i", strtotime($row['check_out_date'])) : '' ?></td>
                                    <td><?= number_format($row['daily_rate'], 0, ",", ".") ?> ₫</td>
                                    <td><?= number_format($row['deposit'], 0, ",", ".") ?> ₫</td>
                                    <td><?= htmlspecialchars($row['pet_enclosure_status']); ?></td>
                                    <td>
                                        <div class="actions">
                                            <?php if ($row['pet_enclosure_status'] === 'Check In'): ?>
                                                <a href="edit_pet_enclosure.php?id=<?= $row['pet_enclosure_id'] ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                                <a href="checkout_invoice.php?id=<?= $row['pet_enclosure_id'] ?>" class="btn btn-icon btn-checkout" title="Check-out & Tạo hóa đơn"><i class="fas fa-check"></i></a>
                                                <a href="delete_pet_enclosure.php?id=<?= $row['pet_enclosure_id'] ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash-alt"></i></a>
                                            <?php elseif ($row['pet_enclosure_status'] === 'Check Out'): ?>
                                                <a href="<?= ADMIN_URL ?>/pages/printing_template/printing_template.php?pet_enclosure_id=<?= rawurlencode($row['pet_enclosure_id']) ?>" class="btn btn-icon btn-print" title="In giấy cam kết"><i class="fas fa-print"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Chưa có dữ liệu chuồng thú.</td>
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
        const rows = document.querySelectorAll("#enclosureTable tbody tr");

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