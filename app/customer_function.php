<?php
require_once(__DIR__ . '/../config/database.php');

function getAllCustomers()
{
    global $conn;
    $stmt = $conn->prepare("SELECT customer_id, customer_name, customer_phone_number, customer_identity_card, customer_address, customer_note 
                            FROM customers 
                            ORDER BY customer_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCustomersPaginated($limit, $offset)
{
    global $conn;
    $stmt = $conn->prepare("SELECT customer_id, customer_name, customer_phone_number, customer_identity_card, customer_address, customer_note 
                            FROM customers 
                            ORDER BY customer_id DESC 
                            LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addCustomer($fullname, $phone, $identity_card, $address, $note)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO customers (customer_name, customer_phone_number, customer_identity_card, customer_address, customer_note) 
                            VALUES (:fullname, :phone, :identity_card, :address, :note)");
    return $stmt->execute([
        ':fullname' => $fullname,
        ':phone'    => $phone,
        ':identity_card'  => $identity_card,
        ':address'  => $address,
        ':note'     => $note
    ]);
}

function getCustomerById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateCustomer($id, $fullname, $phone, $identity_card, $address, $note)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE customers 
                            SET customer_name = :fullname, 
                                customer_phone_number = :phone, 
                                customer_identity_card = :identity_card, 
                                customer_address = :address, 
                                customer_note = :note
                            WHERE customer_id = :id");
    return $stmt->execute([
        ':fullname' => $fullname,
        ':phone'    => $phone,
        ':identity_card'  => $identity_card,
        ':address'  => $address,
        ':note'     => $note,
        ':id'       => $id
    ]);
}

function deleteCustomer($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = :id");
    return $stmt->execute([':id' => $id]);
}

function getCustomerCount() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) as total FROM customers");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}