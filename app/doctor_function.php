<?php
require_once(__DIR__ . '/../config/database.php');

function getAllDoctors()
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM doctors 
                            ORDER BY doctor_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDoctorsPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM doctors 
                            ORDER BY doctor_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addDoctor($fullname, $phone, $identity_card, $address, $note)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO doctors (doctor_name, doctor_phone_number, doctor_identity_card, doctor_address, doctor_note) 
                            VALUES (:fullname, :phone, :identity_card, :address, :note)");
    return $stmt->execute([
        ':fullname' => $fullname,
        ':phone'    => $phone,
        ':identity_card'  => $identity_card,
        ':address'  => $address,
        ':note'     => $note
    ]);
}

function getDoctorById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateDoctor($id, $fullname, $phone, $identity_card, $address, $note)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE doctors 
                            SET doctor_name = :fullname, 
                                doctor_phone_number = :phone, 
                                doctor_identity_card = :identity_card, 
                                doctor_address = :address, 
                                doctor_note = :note
                            WHERE doctor_id = :id");
    return $stmt->execute([
        ':fullname' => $fullname,
        ':phone'    => $phone,
        ':identity_card'  => $identity_card,
        ':address'  => $address,
        ':note'     => $note,
        ':id'       => $id
    ]);
}

function deleteDoctor($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getDoctorCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM doctors");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}