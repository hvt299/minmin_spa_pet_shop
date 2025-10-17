<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/invoice_function.php');

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$invoices = getInvoicesPaginated($limit, $offset);
$totalInvoices = getInvoiceCount();
$totalPages = ceil($totalInvoices / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hóa đơn - Spa Thú Cưng Min Min</title>
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
            <h1>Danh sách hóa đơn</h1>

            <!-- Thanh tìm kiếm -->
            <div class="filter-box">
                <input type="text" id="searchInput" placeholder="Tìm theo mã hóa đơn, khách hàng, thú cưng...">
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="invoiceTable">
                    <thead>
                        <tr>
                            <th>Mã hóa đơn</th>
                            <th>Ngày</th>
                            <th>Khách hàng</th>
                            <th>Thú cưng</th>
                            <th>Tổng tiền (₫)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($invoices): ?>
                            <?php foreach ($invoices as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['invoice_id']); ?></td>
                                    <td>
                                        <?= (!empty($row['invoice_date'])
                                            && $row['invoice_date'] !== '0000-00-00'
                                            && $row['invoice_date'] !== '0000-00-00 00:00:00')
                                            ? date("d-m-Y H:i", strtotime($row['invoice_date']))
                                            : '' ?>
                                    </td>
                                    <td><?= htmlspecialchars(getCustomerById($row['customer_id'])['customer_name']); ?></td>
                                    <td><?= htmlspecialchars(getPetById($row['pet_id'])['pet_name']); ?></td>
                                    <td><?= number_format($row['total_amount'], 0, ",", ".") ?> ₫</td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_invoice.php?id=<?= $row['invoice_id'] ?>" class="btn btn-icon btn-edit" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                            <a href="<?= ADMIN_URL ?>/pages/printing_template/printing_template.php?invoice_id=<?= rawurlencode($row['invoice_id']) ?>" class="btn btn-icon btn-print" title="In hóa đơn"><i class="fas fa-print"></i></a>
                                            <a href="delete_invoice.php?id=<?= $row['invoice_id'] ?>" class="btn btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa hóa đơn này?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Chưa có dữ liệu hóa đơn.</td>
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
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#invoiceTable tbody tr");

        function filterRecords() {
            const keyword = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        }

        searchInput.addEventListener("keyup", filterRecords);
    </script>
</body>

</html>