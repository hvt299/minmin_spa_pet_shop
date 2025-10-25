<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/service_type_function.php');
// $serviceTypes = getAllServiceTypes();
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$serviceTypes = getServiceTypesPaginated($limit, $offset);
$totalServiceTypes = getServiceTypeCount();
$totalPages = ceil($totalServiceTypes / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách loại dịch vụ - Spa Thú Cưng Min Min</title>
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
            <h1>Danh sách loại dịch vụ</h1>

            <!-- Thanh tìm kiếm -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên dịch vụ hoặc mô tả...">
                <a href="add_service_type.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm loại dịch vụ</a>
            </div>

            <!-- Bảng danh sách -->
            <div class="table-responsive">
                <table class="admin-data-table" id="serviceTypeTable">
                    <thead>
                        <tr>
                            <th>Tên loại dịch vụ</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($serviceTypes): ?>
                            <?php foreach ($serviceTypes as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_service_type.php?id=<?php echo $row['service_type_id']; ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="delete_service_type.php?id=<?php echo $row['service_type_id']; ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa loại dịch vụ này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">Chưa có loại dịch vụ nào.</td>
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
        // Lọc tự động theo input
        document.getElementById("searchInput").addEventListener("keyup", function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#serviceTypeTable tbody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>