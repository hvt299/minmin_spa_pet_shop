<?php
require_once(__DIR__ . '/../config/database.php');

function getPrescriptionsByTreatmentSessionId($treatment_session_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM prescriptions WHERE treatment_session_id = :treatment_session_id");
    $stmt->execute([':treatment_session_id' => $treatment_session_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPrescriptionsByTreatmentSessionIdPaginated($treatment_session_id, $limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT *
        FROM prescriptions
        WHERE treatment_session_id = :treatment_session_id
        ORDER BY treatment_session_id DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':treatment_session_id', (int)$treatment_session_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPrescriptionById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM prescriptions WHERE prescription_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addPrescription($treatment_session_id, $medicine_id, $treatment_type, $dosage, $unit, $frequency, $status, $notes)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO prescriptions (treatment_session_id, medicine_id, treatment_type, dosage, unit, frequency, status, notes) 
        VALUES (:treatment_session_id, :medicine_id, :treatment_type, :dosage, :unit, :frequency, :status, :notes)");

    $result = $stmt->execute([
        ':treatment_session_id' => $treatment_session_id,
        ':medicine_id'          => $medicine_id,
        ':treatment_type'       => $treatment_type,
        ':dosage'               => $dosage,
        ':unit'                 => $unit,
        ':frequency'            => $frequency,
        ':status'               => $status,
        ':notes'                => $notes
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updatePrescription($id, $medicine_id, $treatment_type, $dosage, $unit, $frequency, $status, $notes)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE prescriptions 
                            SET medicine_id = :medicine_id,
                                treatment_type = :treatment_type, 
                                dosage = :dosage,
                                unit = :unit, 
                                frequency = :frequency,
                                status = :status,
                                notes = :notes
                            WHERE prescription_id = :id");
    return $stmt->execute([
        ':medicine_id'          => $medicine_id,
        ':treatment_type'       => $treatment_type,
        ':dosage'               => $dosage,
        ':unit'                 => $unit,
        ':frequency'            => $frequency,
        ':status'               => $status,
        ':notes'                => $notes,
        ':id'                   => $id
    ]);
}

function deletePrescription($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM prescriptions WHERE prescription_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getPrescriptionsByTreatmentSessionIdCount($treatment_session_id)
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM prescriptions
        WHERE treatment_session_id = :treatment_session_id
    ");
    $stmt->bindValue(':treatment_session_id', (int)$treatment_session_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}