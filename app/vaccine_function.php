<?php
require_once(__DIR__ . '/../config/database.php');

function getAllVaccines()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM vaccines 
                            ORDER BY vaccine_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVaccinesPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM vaccines 
                            ORDER BY vaccine_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getVaccineById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM vaccines WHERE vaccine_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addVaccine($name, $description)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO vaccines (vaccine_name, description) 
        VALUES (:name, :description)");

    $result = $stmt->execute([
        ':name'     => $name,
        ':description'     => $description
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updateVaccine($id, $name, $description)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE vaccines 
                            SET vaccine_name = :name, 
                                description = :description
                            WHERE vaccine_id = :id");
    return $stmt->execute([
        ':name' => $name,
        ':description'    => $description,
        ':id'       => $id
    ]);
}

function deleteVaccine($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM vaccines WHERE vaccine_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getVaccineCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM vaccines");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}