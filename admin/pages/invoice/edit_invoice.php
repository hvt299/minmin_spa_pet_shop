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
require_once(APP_PATH . '/service_type_function.php');

$customers = getAllCustomers();
$pets = getAllPets();
$services = getAllServiceTypes();

// Nếu không có id thì quay về danh sách
if (!isset($_GET['id'])) {
    header("Location: invoices.php");
    exit;
}

$invoice_id = intval($_GET['id']);
$invoice = getInvoiceById($invoice_id);
$invoice_details = getInvoiceDetailsByInvoiceId($invoice_id);

if (!$invoice) {
    header("Location: invoices.php?error=1&msg=" . urlencode("ID hóa đơn không hợp lệ!"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $discount = intval($_POST['discount'] ?? 0);
    $deposit  = intval($_POST['deposit'] ?? 0);

    $detail_services = $_POST['service_type_id'] ?? [];
    $detail_qty      = $_POST['quantity'] ?? [];
    $detail_price    = $_POST['unit_price'] ?? [];

    if (empty($detail_services)) {
        header("Location: invoices.php?error=1&msg=" . urlencode("Vui lòng nhập ít nhất 1 dịch vụ!"));
        exit;
    }

    $details = [];
    $subtotal = 0;

    foreach ($detail_services as $i => $service_id) {
        $service_id = intval($service_id);
        $qty        = intval($detail_qty[$i] ?? 0);
        $price      = intval($detail_price[$i] ?? 0);

        if ($service_id <= 0 || $qty <= 0 || $price < 0) {
            header("Location: invoices.php?error=1&msg=" . urlencode("Dữ liệu chi tiết đơn hàng không hợp lệ!"));
            exit;
        }

        $line_total = $qty * $price;
        $subtotal  += $line_total;

        $details[] = [
            'service_type_id' => $service_id,
            'quantity'        => $qty,
            'unit_price'      => $price,
            'total_price'     => $line_total,
        ];
    }

    $total_amount = $subtotal - $discount - $deposit;

    // Cập nhật invoices
    updateInvoiceTotals($invoice_id, $discount, $subtotal, $total_amount);

    // Cập nhật invoice_details
    deleteInvoiceDetailByInvoiceId($invoice_id);

    foreach ($details as $d) {
        addInvoiceDetail($invoice_id, $d['service_type_id'], $d['quantity'], $d['unit_price'], $d['total_price']);
    }

    header("Location: invoices.php?success=1&msg=" . urlencode("Cập nhật hóa đơn thành công!"));
    exit;
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa hóa đơn - Spa Thú Cưng Min Min</title>
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
            <div class="form-container">
                <h2>Sửa hóa đơn</h2>
                <form method="post">
                    <h3 class="title-description">Chi tiết đơn hàng</h3>
                    <div class="table-responsive">
                        <table class="detail-table" id="detailTable">
                            <thead>
                                <tr>
                                    <th>Dịch vụ</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá (₫)</th>
                                    <th>Thành tiền (₫)</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoice_details as $d): ?>
                                    <tr>
                                        <td>
                                            <select name="service_type_id[]">
                                                <?php foreach ($services as $s): ?>
                                                    <option value="<?= $s['service_type_id'] ?>" <?= $s['service_type_id'] == $d['service_type_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($s['service_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="quantity[]" value="<?= $d['quantity'] ?>" min="1"></td>
                                        <td><input type="number" name="unit_price[]" value="<?= $d['unit_price'] ?>" min="0"></td>
                                        <td class="line-total"><?= number_format($d['quantity'] * $d['unit_price'], 0, ",", ".") ?></td>
                                        <td><button type="button" class="btn btn-delete" onclick="removeRow(this)">Xóa</button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-add-option" onclick="addRow()">+ Thêm dịch vụ</button>

                    <hr style="margin-top: 15px;">

                    <div class="totals">
                        <div class="form-group">
                            <label for="subtotal">Tạm tính (₫)</label>
                            <input type="text" id="subtotal" readonly>
                        </div>
                        <div class="form-group">
                            <label for="deposit">Đặt cọc (₫)</label>
                            <input type="number" name="deposit" id="deposit"
                                value="<?= $invoice['deposit'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="discount">Giảm giá (₫)</label>
                            <input type="number" name="discount" id="discount" value="<?= $invoice['discount'] ?>" min="0">
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Tổng thanh toán (₫)</label>
                            <input type="text" id="total_amount" readonly>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="invoices.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
        function calcTotals() {
            let subtotal = 0;
            document.querySelectorAll("#detailTable tbody tr").forEach(row => {
                const qty = parseInt(row.querySelector("input[name='quantity[]']").value) || 0;
                const price = parseInt(row.querySelector("input[name='unit_price[]']").value) || 0;
                const total = qty * price;
                row.querySelector(".line-total").textContent = total.toLocaleString();
                subtotal += total;
            });

            const discount = parseInt(document.getElementById("discount").value) || 0;
            const deposit = parseInt(document.getElementById("deposit").value) || 0;

            const totalAmount = subtotal - discount - deposit;

            document.getElementById("subtotal").value = subtotal.toLocaleString();
            document.getElementById("total_amount").value = totalAmount.toLocaleString();
        }

        function addRow() {
            const tableBody = document.querySelector("#detailTable tbody");
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <select name="service_type_id[]">
                        <?php foreach ($services as $s): ?>
                            <option value="<?= $s['service_type_id'] ?>"><?= htmlspecialchars($s['service_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="quantity[]" value="1" min="1"></td>
                <td><input type="number" name="unit_price[]" value="0" min="0"></td>
                <td class="line-total">0</td>
                <td><button type="button" class="btn btn-delete" onclick="removeRow(this)">Xóa</button></td>
            `;
            tableBody.appendChild(row);
        }

        function removeRow(btn) {
            btn.closest("tr").remove();
            calcTotals();
        }

        document.addEventListener("input", function(e) {
            if (e.target.name === "quantity[]" || e.target.name === "unit_price[]" || e.target.id === "discount") {
                calcTotals();
            }
        });

        // Tính toán lần đầu
        calcTotals();
    </script>
</body>

</html>