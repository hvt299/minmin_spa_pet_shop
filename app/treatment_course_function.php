<?php
require_once(__DIR__ . '/../config/database.php');

function getAllTreatmentCourses()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM treatment_courses 
                            ORDER BY treatment_course_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTreatmentCoursesPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM treatment_courses 
                            ORDER BY treatment_course_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTreatmentCourseById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM treatment_courses WHERE treatment_course_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addTreatmentCourse($customer_id, $pet_id, $start_date, $end_date, $status)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO treatment_courses (customer_id, pet_id, start_date, end_date, status) 
        VALUES (:customer_id, :pet_id, :start_date, :end_date, :status)");

    $result = $stmt->execute([
        ':customer_id'      => $customer_id,
        ':pet_id'           => $pet_id,
        ':start_date'       => $start_date,
        ':end_date'         => $end_date,
        ':status'           => $status
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updateTreatmentCourse($id, $customer_id, $pet_id, $start_date, $end_date, $status)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE treatment_courses 
                            SET customer_id = :customer_id,
                                pet_id = :pet_id, 
                                start_date = :start_date,
                                end_date = :end_date, 
                                status = :status
                            WHERE treatment_course_id = :id");
    return $stmt->execute([
        ':customer_id'      => $customer_id,
        ':pet_id'           => $pet_id,
        ':start_date'       => $start_date,
        ':end_date'         => $end_date,
        ':status'           => $status,
        ':id'               => $id
    ]);
}

function updateTreatmentCourseStatus($id, $end_date)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE treatment_courses 
                            SET end_date = :end_date,
                                status = :status
                            WHERE treatment_course_id = :id");
    return $stmt->execute([
        ':end_date'   => $end_date,
        ':status'     => 0,
        ':id'         => $id
    ]);
}

function deleteTreatmentCourse($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM treatment_courses WHERE treatment_course_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getTreatmentCourseCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM treatment_courses");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}