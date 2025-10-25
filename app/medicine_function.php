<?php
require_once(__DIR__ . '/../config/database.php');

function getAllMedicines()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM medicines
                            ORDER BY medicine_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMedicinesPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM medicines
                            ORDER BY medicine_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMedicineById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM medicines WHERE medicine_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addMedicine($name, $route)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO medicines (medicine_name, medicine_route) 
        VALUES (:name, :route)");

    $result = $stmt->execute([
        ':name'     => $name,
        ':route'    => $route
    ]);

    if ($result) {
        return $conn->lastInsertId();
    }
    return false;
}

function updateMedicine($id, $name, $route)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE medicines 
                            SET medicine_name = :name, 
                                medicine_route = :route
                            WHERE medicine_id = :id");
    return $stmt->execute([
        ':name'     => $name,
        ':route'    => $route,
        ':id'       => $id
    ]);
}

function deleteMedicine($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM medicines WHERE medicine_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getMedicineCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM medicines");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}