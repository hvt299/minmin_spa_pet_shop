<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/medicine_function.php');

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$medicines = getMedicinesPaginated($limit, $offset);
$totalMedicines = getMedicineCount();
$totalPages = ceil($totalMedicines / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thuốc thú y - Spa Thú Cưng Min Min</title>
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
            <h1>Danh sách thuốc thú y</h1>

            <!-- Thanh tìm kiếm -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên thuốc hoặc đường tiêm truyền...">
                <a href="add_medicine.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm thuốc</a>
            </div>

            <!-- Bảng danh sách -->
            <div class="table-responsive">
                <table class="admin-data-table" id="medicineTable">
                    <thead>
                        <tr>
                            <th>Tên thuốc</th>
                            <th>Đường tiêm truyền</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($medicines): ?>
                            <?php foreach ($medicines as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                                    <td>
                                        <?php
                                        // Chuyển giá trị enum sang tiếng Việt dễ hiểu
                                        $routes = [
                                            'PO' => 'Đường uống (PO)',
                                            'IM' => 'Tiêm bắp (IM)',
                                            'IV' => 'Tiêm tĩnh mạch (IV)',
                                            'SC' => 'Tiêm dưới da (SC)'
                                        ];
                                        echo $routes[$row['medicine_route']];
                                        ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_medicine.php?id=<?php echo $row['medicine_id']; ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="delete_medicine.php?id=<?php echo $row['medicine_id']; ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa thuốc này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">Chưa có thuốc nào trong danh sách.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Phân trang -->
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
        // Lọc theo tên thuốc hoặc đường tiêm truyền
        document.getElementById("searchInput").addEventListener("keyup", function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#medicineTable tbody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>