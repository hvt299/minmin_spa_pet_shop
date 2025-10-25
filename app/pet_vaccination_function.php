<?php
require_once(__DIR__ . '/../config/database.php');

function getAllPetVaccinations()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM pet_vaccinations 
                            ORDER BY pet_vaccination_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetVaccinationsPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM pet_vaccinations 
                            ORDER BY pet_vaccination_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetVaccinationById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pet_vaccinations WHERE pet_vaccination_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addPetVaccination($vaccine_id, $customer_id, $pet_id, $doctor_id, $vaccination_date, $next_vaccination_date, $notes)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO pet_vaccinations (vaccine_id, customer_id, pet_id, doctor_id, vaccination_date, next_vaccination_date, notes) 
        VALUES (:vaccine_id, :customer_id, :pet_id, :doctor_id, :vaccination_date, :next_vaccination_date, :notes)");

    $result = $stmt->execute([
        ':vaccine_id'               => $vaccine_id,
        ':customer_id'              => $customer_id,
        ':pet_id'                   => $pet_id,
        ':doctor_id'                => $doctor_id,
        ':vaccination_date'         => $vaccination_date,
        ':next_vaccination_date'    => $next_vaccination_date,
        ':notes'                    => $notes
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updatePetVaccination($id, $vaccine_id, $customer_id, $pet_id, $doctor_id, $vaccination_date, $next_vaccination_date, $notes)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE pet_vaccinations 
                            SET vaccine_id = :vaccine_id, 
                                customer_id = :customer_id,
                                pet_id = :pet_id, 
                                doctor_id = :doctor_id,
                                vaccination_date = :vaccination_date, 
                                next_vaccination_date = :next_vaccination_date,
                                notes = :notes
                            WHERE pet_vaccination_id = :id");
    return $stmt->execute([
        ':vaccine_id'               => $vaccine_id,
        ':customer_id'              => $customer_id,
        ':pet_id'                   => $pet_id,
        ':doctor_id'                => $doctor_id,
        ':vaccination_date'         => $vaccination_date,
        ':next_vaccination_date'    => $next_vaccination_date,
        ':notes'                    => $notes,
        ':id'                       => $id
    ]);
}

function deletePetVaccination($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM pet_vaccinations WHERE pet_vaccination_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getPetVaccinationCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM pet_vaccinations");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}