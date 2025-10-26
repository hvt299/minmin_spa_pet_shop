<?php
require_once(__DIR__ . '/../config/database.php');

function getDiagnosesByTreatmentSessionId($treatment_session_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM diagnoses WHERE treatment_session_id = :treatment_session_id");
    $stmt->execute([':treatment_session_id' => $treatment_session_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getDiagnosesByTreatmentSessionIdPaginated($treatment_session_id, $limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT *
        FROM diagnoses
        WHERE treatment_session_id = :treatment_session_id
        ORDER BY diagnosis_id DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':treatment_session_id', (int)$treatment_session_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDiagnosisById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM diagnoses WHERE diagnosis_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addDiagnosis($treatment_session_id, $diagnosis_name, $diagnosis_type, $clinical_tests, $notes)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO diagnoses (treatment_session_id, diagnosis_name, diagnosis_type, clinical_tests, notes) 
        VALUES (:treatment_session_id, :diagnosis_name, :diagnosis_type, :clinical_tests, :notes)");

    $result = $stmt->execute([
        ':treatment_session_id'     => $treatment_session_id,
        ':diagnosis_name'           => $diagnosis_name,
        ':diagnosis_type'           => $diagnosis_type,
        ':clinical_tests'           => $clinical_tests,
        ':notes'                    => $notes
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updateDiagnosis($id, $diagnosis_name, $diagnosis_type, $clinical_tests, $notes)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE diagnoses 
                            SET diagnosis_name = :diagnosis_name, 
                                diagnosis_type = :diagnosis_type,
                                clinical_tests = :clinical_tests,
                                notes = :notes
                            WHERE diagnosis_id = :id");
    return $stmt->execute([
        ':diagnosis_name'           => $diagnosis_name,
        ':diagnosis_type'           => $diagnosis_type,
        ':clinical_tests'           => $clinical_tests,
        ':notes'                    => $notes,
        ':id'                       => $id
    ]);
}

function deleteDiagnosis($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM diagnoses WHERE diagnosis_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getDiagnosesByTreatmentSessionIdCount($treatment_session_id)
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM diagnoses
        WHERE treatment_session_id = :treatment_session_id
    ");
    $stmt->bindValue(':treatment_session_id', (int)$treatment_session_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}