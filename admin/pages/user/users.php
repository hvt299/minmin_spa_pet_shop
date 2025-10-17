<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/user_function.php');
// $users = getAllUsers();
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$users = getUsersPaginated($limit, $offset);
$totalUsers = getUserCount();
$totalPages = ceil($totalUsers / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách người dùng - Spa Thú Cưng Min Min</title>
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
            <h1>Danh sách người dùng</h1>

            <!-- Thanh tìm kiếm -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm theo tên đăng nhập hoặc họ tên...">
                <a href="add_user.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm người dùng</a>
            </div>

            <?php if (isset($_GET['deleted'])): ?>
                <p style="color: green; font-weight: 600;">Xóa người dùng thành công!</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'delete'): ?>
                <p style="color: red; font-weight: 600;">Xóa người dùng thất bại!</p>
            <?php endif; ?>

            <!-- Bảng danh sách -->
            <div class="table-responsive">
                <table class="admin-data-table" id="userTable">
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users): ?>
                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td>
                                        <?php
                                        // Hiển thị vai trò thân thiện hơn
                                        switch ($row['role']) {
                                            case 'admin':
                                                echo '<span style="color: #dc3545; font-weight: 600;">Quản trị viên</span>';
                                                break;
                                            case 'staff':
                                                echo '<span style="color: #007bff; font-weight: 600;">Nhân viên</span>';
                                                break;
                                            // default:
                                            //     echo '<span style="color: #6c757d;">Khác</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= (!empty($row['create_at']) && $row['create_at'] !== '0000-00-00' && $row['create_at'] !== '0000-00-00 00:00:00') ? date("d-m-Y H:i", strtotime($row['create_at'])) : '' ?></td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="change_password.php?id=<?php echo $row['id']; ?>" class="btn btn-icon btn-password" title="Đổi mật khẩu"><i class="fas fa-key"></i></a>
                                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Chưa có người dùng nào.</td>
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
            const rows = document.querySelectorAll("#userTable tbody tr");

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>

</html>