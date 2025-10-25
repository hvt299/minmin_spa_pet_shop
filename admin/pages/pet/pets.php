<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');

// Lấy danh sách tất cả thú cưng
// $pets = getAllPets();

// Lấy danh sách khách hàng để hiện ở dropdown
$customers = getAllCustomers();

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$pets = getPetsPaginated($limit, $offset);
$totalPets = getPetCount();
$totalPages = ceil($totalPets / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thú cưng - Spa Thú Cưng Min Min</title>
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
            <h1>Danh sách thú cưng</h1>

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
                <input type="text" id="searchInput" placeholder="Tìm theo tên thú cưng, loài, giới tính...">
                <a href="add_pet.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm thú cưng</a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="petTable">
                    <thead>
                        <tr>
                            <th>Tên thú</th>
                            <th>Chủ nuôi</th>
                            <th>Loài/Giống</th>
                            <th>Giới tính</th>
                            <th>Cân nặng (kg)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pets): ?>
                            <?php foreach ($pets as $row): ?>
                                <tr data-customer="<?php echo $row['customer_id']; ?>">
                                    <td><?php echo htmlspecialchars($row['pet_name']); ?></td>
                                    <td><?php echo htmlspecialchars(getCustomerById($row['customer_id'])['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pet_species']); ?></td>
                                    <td><?php echo empty($row['pet_gender']) && $row['pet_gender'] !== "0" ? 'Không rõ' : ($row['pet_gender'] == 0 ? 'Đực' : 'Cái'); ?></td>
                                    <td><?php echo $row['pet_weight'] > 0 ? htmlspecialchars($row['pet_weight']) : "Không rõ" ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_pet.php?id=<?php echo $row['pet_id']; ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="delete_pet.php?id=<?php echo $row['pet_id']; ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa thú cưng này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Chưa có thú cưng nào.</td>
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
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#petTable tbody tr");

        function filterPets() {
            const customerId = customerFilter.value;
            const keyword = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const matchCustomer = !customerId || row.getAttribute("data-customer") === customerId;
                const text = row.innerText.toLowerCase();
                const matchSearch = text.includes(keyword);

                row.style.display = (matchCustomer && matchSearch) ? "" : "none";
            });
        }

        customerFilter.addEventListener("change", filterPets);
        searchInput.addEventListener("keyup", filterPets);
    </script>
</body>

</html>