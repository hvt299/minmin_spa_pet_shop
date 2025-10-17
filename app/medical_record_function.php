<?php
require_once(__DIR__ . '/../config/database.php');

function getAllMedicalRecords()
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM medical_records 
                            ORDER BY medical_record_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMedicalRecordsPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM medical_records
                            ORDER BY medical_record_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addMedicalRecord($customer_id, $pet_id, $doctor_id, $type, $visit_date, $summary, $details)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO medical_records 
        (customer_id, pet_id, doctor_id, medical_record_type, medical_record_visit_date, medical_record_summary, medical_record_details) 
        VALUES (:customer_id, :pet_id, :doctor_id, :type, :visit_date, :summary, :details)");

    $result = $stmt->execute([
        ':customer_id' => $customer_id,
        ':pet_id'      => $pet_id,
        ':doctor_id'   => $doctor_id,
        ':type'        => $type,
        ':visit_date'  => $visit_date,
        ':summary'     => $summary,
        ':details'     => $details
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function addVaccinationRecord($medical_id, $vaccine_name, $batch_number, $next_injection_date)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO vaccination_records (medical_record_id, vaccine_name, batch_number, next_injection_date) 
                            VALUES (:medical_id, :vaccine_name, :batch_number, :next_injection_date)");
    return $stmt->execute([
        ':medical_id' => $medical_id,
        ':vaccine_name'    => $vaccine_name,
        ':batch_number'  => $batch_number,
        ':next_injection_date'  => $next_injection_date
    ]);
}

function getMedicalRecordById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM medical_records WHERE medical_record_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getVaccinationByMedicalId($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM vaccination_records WHERE medical_record_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateMedicalRecord($id, $customer_id, $pet_id, $doctor_id, $type, $visit_date, $summary, $details)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE medical_records 
                            SET customer_id = :customer_id, 
                                pet_id = :pet_id, 
                                doctor_id = :doctor_id, 
                                medical_record_type = :type, 
                                medical_record_visit_date = :visit_date,
                                medical_record_summary = :summary,
                                medical_record_details = :details
                            WHERE medical_record_id = :id");
    return $stmt->execute([
        ':customer_id' => $customer_id,
        ':pet_id'      => $pet_id,
        ':doctor_id'   => $doctor_id,
        ':type'        => $type,
        ':visit_date'  => $visit_date,
        ':summary'     => $summary,
        ':details'     => $details,
        ':id'       => $id
    ]);
}

function updateVaccinationRecord($medical_id, $vaccine_name, $batch_number, $next_injection_date)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE vaccination_records 
                            SET vaccine_name = :vaccine_name, 
                                batch_number = :batch_number, 
                                next_injection_date = :next_injection_date
                            WHERE medical_record_id = :medical_id");
    return $stmt->execute([
        ':vaccine_name'    => $vaccine_name,
        ':batch_number'  => $batch_number,
        ':next_injection_date'  => $next_injection_date,
        ':medical_id' => $medical_id,
    ]);
}

function deleteMedicalRecord($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM medical_records WHERE medical_record_id = :id");
    return $stmt->execute([':id' => $id]);
}

function deleteVaccinationRecordByMedicalId($medical_id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM vaccination_records WHERE medical_record_id = :medical_id");
    return $stmt->execute([':medical_id' => $medical_id]);
}

function getMedicalRecordCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM medical_records");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

function getMedicalRecordsByDay($days = 7)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            DATE(medical_record_visit_date) AS visit_date, 
            COUNT(*) AS total
        FROM medical_records
        WHERE medical_record_visit_date BETWEEN DATE_SUB(NOW(), INTERVAL :days DAY) AND NOW()
        GROUP BY DATE(medical_record_visit_date)
        ORDER BY visit_date ASC
    ");
    $stmt->execute([':days' => (int)$days]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Đảm bảo đủ các ngày
    $data = [];
    for ($i = $days - 1; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $data[$date] = 0;
    }

    foreach ($rows as $row) {
        $data[$row['visit_date']] = (int)$row['total'];
    }

    return $data;
}

function getMedicalRecordCountByMonth($year, $month) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS count 
        FROM medical_records 
        WHERE YEAR(medical_record_visit_date) = ? 
          AND MONTH(medical_record_visit_date) = ?
    ");
    $stmt->execute([$year, $month]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['count'] : 0;
}

function getMedicalRecordCountByDate($date) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM medical_records WHERE medical_record_visit_date = ?");
    $stmt->execute([$date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['count'] : 0;
}