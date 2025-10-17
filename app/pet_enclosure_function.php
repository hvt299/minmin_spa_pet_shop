<?php
require_once(__DIR__ . '/../config/database.php');

function getAllPetEnclosures()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM pet_enclosures 
                            ORDER BY pet_enclosure_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetEnclosuresPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM pet_enclosures 
                            ORDER BY pet_enclosure_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPetEnclosure($customer_id, $pet_id, $enclosure_number, $check_in_date, $check_out_date, $daily_rate, $deposit, $emergency_limit, $note, $status)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO pet_enclosures (customer_id, pet_id, pet_enclosure_number, check_in_date, check_out_date, daily_rate, deposit, emergency_limit, pet_enclosure_note, pet_enclosure_status) 
                            VALUES (:customer_id, :pet_id, :enclosure_number, :check_in_date, :check_out_date, :daily_rate, :deposit, :emergency_limit, :note, :status)");
    return $stmt->execute([
        ':customer_id' => $customer_id,
        ':pet_id'    => $pet_id,
        ':enclosure_number'  => $enclosure_number,
        ':check_in_date'  => $check_in_date,
        ':check_out_date'     => $check_out_date,
        ':daily_rate' => $daily_rate,
        ':deposit'    => $deposit,
        ':emergency_limit'  => $emergency_limit,
        ':note'  => $note,
        ':status'     => $status
    ]);
}

function getPetEnclosureById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pet_enclosures WHERE pet_enclosure_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePetEnclosure($id, $customer_id, $pet_id, $enclosure_number, $check_in_date, $check_out_date, $daily_rate, $deposit, $emergency_limit, $note, $status)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE pet_enclosures 
                            SET customer_id = :customer_id, 
                                pet_id = :pet_id, 
                                pet_enclosure_number = :enclosure_number, 
                                check_in_date = :check_in_date, 
                                check_out_date = :check_out_date,
                                daily_rate = :daily_rate,
                                deposit = :deposit,
                                emergency_limit = :emergency_limit,
                                pet_enclosure_note = :note,
                                pet_enclosure_status = :status
                            WHERE pet_enclosure_id = :id");
    return $stmt->execute([
        ':customer_id' => $customer_id,
        ':pet_id'      => $pet_id,
        ':enclosure_number'   => $enclosure_number,
        ':check_in_date'        => $check_in_date,
        ':check_out_date'  => $check_out_date,
        ':daily_rate'     => $daily_rate,
        ':deposit'     => $deposit,
        ':emergency_limit'  => $emergency_limit,
        ':note'     => $note,
        ':status'     => $status,
        ':id'       => $id
    ]);
}

function updatePetEnclosureCheckOut($id, $check_out_date)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE pet_enclosures 
                            SET check_out_date = :check_out_date,
                                pet_enclosure_status = :status
                            WHERE pet_enclosure_id = :id");
    return $stmt->execute([
        ':check_out_date'  => $check_out_date,
        ':status'     => "Check Out",
        ':id'       => $id
    ]);
}

function deletePetEnclosure($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM pet_enclosures WHERE pet_enclosure_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getPetEnclosureCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM pet_enclosures");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

function getCheckinCheckoutStats($days = 7)
{
    global $conn;

    // Lấy dữ liệu check-in
    $stmt_in = $conn->prepare("
        SELECT DATE(check_in_date) AS visit_date, COUNT(*) AS count
        FROM pet_enclosures
        WHERE check_in_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
        GROUP BY DATE(check_in_date)
        ORDER BY visit_date ASC
    ");
    $stmt_in->execute([':days' => $days]);
    $checkins = $stmt_in->fetchAll(PDO::FETCH_KEY_PAIR); // [date => count]

    // Lấy dữ liệu check-out
    $stmt_out = $conn->prepare("
        SELECT DATE(check_out_date) AS visit_date, COUNT(*) AS count
        FROM pet_enclosures
        WHERE check_out_date IS NOT NULL 
          AND check_out_date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
        GROUP BY DATE(check_out_date)
        ORDER BY visit_date ASC
    ");
    $stmt_out->execute([':days' => $days]);
    $checkouts = $stmt_out->fetchAll(PDO::FETCH_KEY_PAIR); // [date => count]

    // Chuẩn hóa dữ liệu 7 ngày gần nhất
    $data = [];
    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i day"));
        $data[$date] = [
            'checkin'  => isset($checkins[$date]) ? (int)$checkins[$date] : 0,
            'checkout' => isset($checkouts[$date]) ? (int)$checkouts[$date] : 0,
        ];
    }

    return $data;
}

function getPetEnclosureCountByMonth($year, $month) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS count 
        FROM pet_enclosures 
        WHERE YEAR(check_in_date) = ? 
          AND MONTH(check_in_date) = ?
    ");
    $stmt->execute([$year, $month]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['count'] : 0;
}

function getPetEnclosureCountByDate($date) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM pet_enclosures WHERE DATE(check_in_date) = ?");
    $stmt->execute([$date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['count'] : 0;
}