<?php
require_once(__DIR__ . '/../config/database.php');

function getAllServiceTypes()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM service_types 
                            ORDER BY service_type_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getServiceTypesPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM service_types 
                            ORDER BY service_type_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getServiceTypeById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM service_types WHERE service_type_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getServiceTypeByName($name)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM service_types WHERE service_name = :name");
    $stmt->execute([':name' => $name]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addServiceType($name, $description)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO service_types (service_name, description) 
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

function updateServiceType($id, $name, $description)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE service_types 
                            SET service_name = :name, 
                                description = :description
                            WHERE service_type_id = :id");
    return $stmt->execute([
        ':name' => $name,
        ':description'    => $description,
        ':id'       => $id
    ]);
}

function deleteServiceType($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM service_types WHERE service_type_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getServiceTypeCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM service_types");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}