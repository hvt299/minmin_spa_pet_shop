<?php
require_once(__DIR__ . '/../config/database.php');

function getGeneralSettings()
{
    global $conn;
    $stmt = $conn->prepare("SELECT *
                            FROM general_settings 
                            ORDER BY setting_id ASC
                            LIMIT 1");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateGeneralSettings($id, $clinic_name, $clinic_address_1, $clinic_address_2, $phone_number_1, $phone_number_2, $representative_name, $checkout_hour, $overtime_fee_per_hour, $default_daily_rate, $signing_place)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE general_settings 
                            SET clinic_name = :clinic_name,
                                clinic_address_1 = :clinic_address_1,
                                clinic_address_2 = :clinic_address_2,
                                phone_number_1 = :phone_number_1,
                                phone_number_2 = :phone_number_2,
                                representative_name = :representative_name,
                                checkout_hour = :checkout_hour,
                                overtime_fee_per_hour = :overtime_fee_per_hour,
                                default_daily_rate = :default_daily_rate,
                                signing_place = :signing_place
                            WHERE setting_id = :id");

    return $stmt->execute([
        ':clinic_name'           => $clinic_name,
        ':clinic_address_1'      => $clinic_address_1,
        ':clinic_address_2'      => $clinic_address_2,
        ':phone_number_1'        => $phone_number_1,
        ':phone_number_2'        => $phone_number_2,
        ':representative_name'   => $representative_name,
        ':checkout_hour'         => $checkout_hour,
        ':overtime_fee_per_hour' => $overtime_fee_per_hour,
        ':default_daily_rate'    => $default_daily_rate,
        ':signing_place'         => $signing_place,
        ':id'                    => $id
    ]);
}
