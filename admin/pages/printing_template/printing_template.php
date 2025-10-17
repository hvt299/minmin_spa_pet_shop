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

// Lấy danh sách hóa đơn
$invoices = getAllInvoices();

$invoiceId = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;
$petEnclosureId = isset($_GET['pet_enclosure_id']) ? intval($_GET['pet_enclosure_id']) : 0;

$mappedInvoiceId = null;

if ($invoiceId > 0) {
  $mappedInvoiceId = $invoiceId;
} else if ($petEnclosureId > 0) {
  $row = getInvoiceByPetEnclosureId($petEnclosureId);
  $mappedInvoiceId = $row && isset($row['invoice_id']) ? $row['invoice_id'] : null;
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mẫu in hóa đơn - Spa Thú Cưng Min Min</title>
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
      <h1>Mẫu in hóa đơn</h1>

      <!-- Dropdown chọn hóa đơn -->
      <div class="filter-box">
        <select id="printInvoiceSelect">
          <?php foreach ($invoices as $inv): ?>
            <?php
            $customer = getCustomerById($inv['customer_id']);
            $pet = getPetById($inv['pet_id']);
            ?>
            <option value="<?= $inv['invoice_id'] ?>">
              <?= htmlspecialchars($pet['pet_name']) ?>
              (<?= htmlspecialchars($customer['customer_name']) ?>)
              - <?= date("Y-m-d H:i", strtotime($inv['invoice_date'])) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Nhóm nút -->
      <div class="actions action-buttons">
        <button class="btn-action btn-commit" id="btnRenderCommit" title="Xem Giấy cam kết"><i class="fas fa-code-commit"></i> Xem Giấy cam kết</button>
        <button class="btn-action btn-invoice" id="btnRenderInvoice" title="Xem Hóa đơn"><i class="fas fa-file-invoice"></i> Xem Hóa đơn</button>
        <button class="btn-action btn-print" id="btnPrintNow" title="In Trang này"><i class="fas fa-print"></i> In Trang này</button>
      </div>

      <!-- Vùng xem trước -->
      <div id="printArea" class="preview-box" style="display:none;">
        <!-- Nội dung sẽ được render ở JS -->
      </div>
    </main>
    <!-- Footer -->
    <?php include_once(ADMIN_INC . '/footer.php'); ?>
  </main>

  <script src="../../assets/js/script.js" defer></script>
  <script>
    const printArea = document.getElementById('printArea');

    document.getElementById('btnRenderCommit').addEventListener('click', function() {
      let invoiceId = document.getElementById('printInvoiceSelect').value;
      if (!invoiceId) {
        alert("Vui lòng chọn giấy cam kết!");
        return;
      }

      // Gọi AJAX lấy dữ liệu giấy cam kết
      fetch("load_commit.php?id=" + invoiceId)
        .then(res => res.text())
        .then(html => {
          const printArea = document.getElementById('printArea');
          printArea.style.display = 'block';
          printArea.innerHTML = html;
          printArea.scrollIntoView({
            behavior: 'smooth'
          });
        });
    });


    document.getElementById('btnRenderInvoice').addEventListener('click', function() {
      let invoiceId = document.getElementById('printInvoiceSelect').value;
      if (!invoiceId) {
        alert("Vui lòng chọn hóa đơn!");
        return;
      }

      // Gọi AJAX lấy dữ liệu
      fetch("load_invoice.php?id=" + invoiceId)
        .then(res => res.text())
        .then(html => {
          const printArea = document.getElementById('printArea');
          printArea.style.display = 'block';
          printArea.innerHTML = html;
          printArea.scrollIntoView({
            behavior: 'smooth'
          });
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
      const mappedInvoiceId = <?= json_encode($mappedInvoiceId) ?>;
      const petEnclosureId = <?= json_encode($petEnclosureId) ?>;
      const invoiceId = <?= json_encode($invoiceId) ?>;

      if (mappedInvoiceId) {
        const select = document.getElementById('printInvoiceSelect');
        if (select) select.value = mappedInvoiceId;

        let endpoint = "";

        // Nếu có pet_enclosure_id → ưu tiên xem giấy cam kết
        if (petEnclosureId > 0) {
          endpoint = "load_commit.php?id=" + mappedInvoiceId;
        }
        // Nếu có invoice_id → xem hóa đơn
        else if (invoiceId > 0) {
          endpoint = "load_invoice.php?id=" + mappedInvoiceId;
        }

        if (endpoint) {
          fetch(endpoint)
            .then(res => res.text())
            .then(html => {
              const printArea = document.getElementById('printArea');
              printArea.style.display = 'block';
              printArea.innerHTML = html;
              printArea.scrollIntoView({
                behavior: 'smooth'
              });
            })
            .catch(err => console.error('Lỗi tải dữ liệu:', err));
        }
      }
    });


    // document.getElementById('btnPrintNow').addEventListener('click', function() {
    //   var printContent = document.getElementById('printArea').innerHTML;
    //   if (!printContent.trim()) {
    //     alert("Chưa có nội dung hóa đơn để in!");
    //     return;
    //   }
    //   var printWindow = window.open('', '', 'width=900,height=650');
    //   printWindow.document.write('<html><head><title>In hóa đơn</title></head><body>');
    //   printWindow.document.write(printContent);
    //   printWindow.document.write('</body></html>');
    //   printWindow.document.close();
    //   printWindow.print();
    // });

    document.getElementById('btnPrintNow').addEventListener('click', function() {
      var printContent = document.getElementById('printArea').innerHTML;
      if (!printContent.trim()) {
        alert("Chưa có nội dung hóa đơn để in!");
        return;
      }
      window.print();
    });
  </script>
</body>

</html>