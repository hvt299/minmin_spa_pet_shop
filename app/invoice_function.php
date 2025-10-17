<?php
require_once(__DIR__ . '/../config/database.php');

function getAllInvoices()
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM invoices 
                            ORDER BY invoice_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getInvoicesPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM invoices 
                            ORDER BY invoice_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addInvoice($customer_id, $pet_id, $enclosure_id, $invoice_date, $discount, $subtotal, $deposit, $total_amount)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO invoices 
        (customer_id, pet_id, pet_enclosure_id, invoice_date, discount, subtotal, deposit, total_amount) 
        VALUES (:customer_id, :pet_id, :enclosure_id, :invoice_date, :discount, :subtotal, :deposit, :total_amount)");

    $result = $stmt->execute([
        ':customer_id'      => $customer_id,
        ':pet_id'   => $pet_id,
        ':enclosure_id' => $enclosure_id,
        ':invoice_date' => $invoice_date,
        ':discount'        => $discount,
        ':subtotal'  => $subtotal,
        ':deposit'     => $deposit,
        ':total_amount'     => $total_amount
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function addInvoiceDetail($invoice_id, $service_type_id, $quantity, $unit_price, $total_price)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO invoice_details 
        (invoice_id, service_type_id, quantity, unit_price, total_price) 
        VALUES (:invoice_id, :service_type_id, :quantity, :unit_price, :total_price)");
    return $stmt->execute([
        ':invoice_id' => $invoice_id,
        ':service_type_id' => $service_type_id,
        ':quantity'  => $quantity,
        ':unit_price'  => $unit_price,
        ':total_price' => $total_price
    ]);
}

function getInvoiceById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM invoices WHERE invoice_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getInvoiceByPetEnclosureId($pet_enclosure_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM invoices WHERE pet_enclosure_id = :pet_enclosure_id");
    $stmt->execute([':pet_enclosure_id' => $pet_enclosure_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getInvoiceDetailsByInvoiceId($invoice_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM invoice_details WHERE invoice_id = :invoice_id");
    $stmt->execute([':invoice_id' => $invoice_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateInvoiceTotals($id, $discount, $subtotal, $total_amount)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE invoices 
                            SET discount = :discount,
                                subtotal = :subtotal, 
                                total_amount = :total_amount
                            WHERE invoice_id = :id");
    return $stmt->execute([
        ':discount' => $discount,
        ':subtotal' => $subtotal,
        ':total_amount' => $total_amount,
        ':id'       => $id
    ]);                        
}

function deleteInvoice($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM invoices WHERE invoice_id = :id");
    return $stmt->execute([':id' => $id]);
}

function deleteInvoiceDetailByInvoiceId($invoice_id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM invoice_details WHERE invoice_id = :invoice_id");
    return $stmt->execute([':invoice_id' => $invoice_id]);
}

function getInvoiceCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM invoices");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

function getInvoiceRevenue() {
    global $conn;
    $stmt = $conn->query("SELECT SUM(total_amount) as revenue FROM invoices");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['revenue'] ?? 0;
}

function getMonthlyRevenueStats($months = 12)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            DATE_FORMAT(invoice_date, '%Y-%m') AS month,
            SUM(total_amount) AS total_revenue
        FROM invoices
        WHERE invoice_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
        GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([':months' => $months]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chuẩn hóa dữ liệu đủ 12 tháng
    $data = [];
    for ($i = $months - 1; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i month"));
        $data[$month] = 0;
    }

    foreach ($rows as $row) {
        $month = $row['month'];
        if (isset($data[$month])) {
            $data[$month] = (float)$row['total_revenue'];
        }
    }

    return $data;
}

function getInvoiceRevenueByYear($year) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT SUM(total_amount) AS revenue 
        FROM invoices 
        WHERE YEAR(invoice_date) = ?
    ");
    $stmt->execute([$year]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (float)$row['revenue'] : 0;
}

function getInvoiceRevenueByMonth($year, $month) {
    global $conn;
    $stmt = $conn->prepare("SELECT SUM(total_amount) as revenue FROM invoices WHERE YEAR(invoice_date) = ? AND MONTH(invoice_date) = ?");
    $stmt->execute([$year, $month]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (float)$row['revenue'] : 0;
}

function getRevenueByServiceType() {
    global $conn;
    $stmt = $conn->query("
        SELECT st.service_name, 
               SUM(id.total_price) AS total_revenue
        FROM invoice_details id
        JOIN service_types st ON id.service_type_id = st.service_type_id
        JOIN invoices i ON id.invoice_id = i.invoice_id
        GROUP BY st.service_name
        ORDER BY total_revenue DESC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function getRevenueByServiceTypeAndYear($year) {
    global $conn;

    $sql = "
        SELECT 
            st.service_name, 
            SUM(id.total_price) AS total_revenue
        FROM invoice_details id
        JOIN service_types st ON id.service_type_id = st.service_type_id
        JOIN invoices i ON id.invoice_id = i.invoice_id
        WHERE YEAR(i.invoice_date) = :year
        GROUP BY st.service_name
        ORDER BY total_revenue DESC
    ";

    $stmt = $conn->prepare($sql);

    if ($year) {
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
