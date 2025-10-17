<?php
require_once(__DIR__ . '/../config/database.php');

function getAllUsers()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM users 
                            ORDER BY create_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * 
                            FROM users 
                            ORDER BY create_at DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addUser($username, $hashedPassword, $fullname, $avatar, $role)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, avatar, role) 
                            VALUES (:username, :password, :fullname, :avatar, :role)");
    return $stmt->execute([
        ':username' => $username,
        ':password'    => $hashedPassword,
        ':fullname'  => $fullname,
        ':avatar' => $avatar,
        ':role' => $role
    ]);
}

function getUserById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserByUsername($username)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUser($id, $username, $fullname, $avatar, $role)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users 
                            SET username = :username, 
                                fullname = :fullname,
                                avatar = :avatar,
                                role = :role
                            WHERE id = :id");
    return $stmt->execute([
        ':username' => $username,
        ':fullname'    => $fullname,
        ':avatar' => $avatar,
        ':role' => $role,
        ':id'       => $id
    ]);
}

function updateUserPassword($id, $hashedPassword)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users 
                            SET password = :password
                            WHERE id = :id");
    return $stmt->execute([
        ':password' => $hashedPassword,
        ':id'       => $id
    ]);
}

function deleteUser($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

function getUserCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}