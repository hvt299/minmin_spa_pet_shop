<?php
require_once(__DIR__ . '/../config/database.php');

function getAllPets()
{
    global $conn;
    $stmt = $conn->prepare("SELECT pet_id, customer_id, pet_name, pet_species, pet_gender, pet_dob, pet_weight, pet_sterilization, pet_characteristic, pet_drug_allergy 
                            FROM pets 
                            ORDER BY pet_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPetsPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT pet_id, customer_id, pet_name, pet_species, pet_gender, pet_dob, pet_weight, pet_sterilization, pet_characteristic, pet_drug_allergy
                            FROM pets 
                            ORDER BY pet_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPet($customer_id, $name, $species, $gender, $dob, $weight, $sterilization, $characteristic, $allergy)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO pets (customer_id, pet_name, pet_species, pet_gender, pet_dob, pet_weight, pet_sterilization, pet_characteristic, pet_drug_allergy) 
                            VALUES (:customer_id, :name, :species, :gender, :dob, :weight, :sterilization, :characteristic, :allergy)");
    return $stmt->execute([
        ':customer_id' => $customer_id,
        ':name' => $name,
        ':species'    => $species,
        ':gender'  => $gender,
        ':dob'  => $dob,
        ':weight'     => $weight,
        ':sterilization'     => $sterilization,
        ':characteristic' => $characteristic,
        ':allergy'     => $allergy
    ]);
}

function getPetById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pets WHERE pet_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePet($id, $customer_id, $name, $species, $gender, $dob, $weight, $sterilization, $characteristic, $allergy)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE pets 
                            SET customer_id = :customer_id, 
                                pet_name = :name, 
                                pet_species = :species, 
                                pet_gender = :gender, 
                                pet_dob = :dob,
                                pet_weight = :weight,
                                pet_sterilization = :sterilization,
                                pet_characteristic = :characteristic,
                                pet_drug_allergy = :allergy
                            WHERE pet_id = :id");
    return $stmt->execute([
        ':customer_id' => $customer_id,
        ':name' => $name,
        ':species'    => $species,
        ':gender'  => $gender,
        ':dob'  => $dob,
        ':weight'     => $weight,
        ':sterilization' => $sterilization,
        ':characteristic' => $characteristic,
        ':allergy'     => $allergy,
        ':id'       => $id
    ]);
}

function deletePet($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM pets WHERE pet_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getPetCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM pets");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}