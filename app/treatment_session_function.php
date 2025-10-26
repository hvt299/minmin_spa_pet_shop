<?php
require_once(__DIR__ . '/../config/database.php');

function getTreatmentSessionsByTreatmentCourseId($treatment_course_id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM treatment_sessions WHERE treatment_course_id = :treatment_course_id");
    $stmt->execute([':treatment_course_id' => $treatment_course_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getTreatmentSessionsByTreatmentCourseIdPaginated($treatment_course_id, $limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT *
        FROM treatment_sessions
        WHERE treatment_course_id = :treatment_course_id
        ORDER BY treatment_session_id DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':treatment_course_id', (int)$treatment_course_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTreatmentSessionById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM treatment_sessions WHERE treatment_session_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addTreatmentSession($treatment_course_id, $doctor_id, $datetime, $temperature, $weight, $pulse_rate, $respiratory_rate, $overall_notes)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO treatment_sessions (treatment_course_id, doctor_id, treatment_session_datetime, temperature, weight, pulse_rate, respiratory_rate, overall_notes) 
        VALUES (:treatment_course_id, :doctor_id, :datetime, :temperature, :weight, :pulse_rate, :respiratory_rate, :overall_notes)");

    $result = $stmt->execute([
        ':treatment_course_id'        => $treatment_course_id,
        ':doctor_id'        => $doctor_id,
        ':datetime'         => $datetime,
        ':temperature'      => $temperature,
        ':weight'           => $weight,
        ':pulse_rate'       => $pulse_rate,
        ':respiratory_rate' => $respiratory_rate,
        ':overall_notes'    => $overall_notes
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updateTreatmentSession($id, $doctor_id, $datetime, $temperature, $weight, $pulse_rate, $respiratory_rate, $overall_notes)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE treatment_sessions 
                            SET doctor_id = :doctor_id,
                                treatment_session_datetime = :datetime, 
                                temperature = :temperature,
                                weight = :weight, 
                                pulse_rate = :pulse_rate,
                                respiratory_rate = :respiratory_rate,
                                overall_notes = :overall_notes
                            WHERE treatment_session_id = :id");
    return $stmt->execute([
        ':doctor_id'        => $doctor_id,
        ':datetime'         => $datetime,
        ':temperature'      => $temperature,
        ':weight'           => $weight,
        ':pulse_rate'       => $pulse_rate,
        ':respiratory_rate' => $respiratory_rate,
        ':overall_notes'    => $overall_notes,
        ':id'               => $id
    ]);
}

function deleteTreatmentSession($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM treatment_sessions WHERE treatment_session_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getTreatmentSessionsByTreatmentCourseIdCount($treatment_course_id)
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM treatment_sessions
        WHERE treatment_course_id = :treatment_course_id
    ");
    $stmt->bindValue(':treatment_course_id', (int)$treatment_course_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}