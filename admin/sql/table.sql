CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    password VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    fullname VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    avatar VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    role ENUM('admin', 'staff') CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL DEFAULT 'staff',
    create_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE service_types (
    service_type_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    description TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL
);

CREATE TABLE general_settings (
    setting_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    clinic_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    clinic_address_1 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    clinic_address_2 VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    phone_number_1 VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    phone_number_2 VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    representative_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    checkout_hour TIME DEFAULT '18:00:00',
    overtime_fee_per_hour INT(11) DEFAULT 0,
    default_daily_rate INT(11) DEFAULT 0,
    signing_place VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL
);

CREATE TABLE customers (
    customer_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    customer_phone_number VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    customer_identity_card VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    customer_address VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    customer_note TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL
);

CREATE TABLE doctors (
    doctor_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    doctor_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    doctor_phone_number VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    doctor_identity_card VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    doctor_address VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    doctor_note TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL
);

CREATE TABLE pets (
    pet_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    pet_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    pet_species VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    pet_gender ENUM('0', '1') CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL COMMENT '0: cái, 1: đực',
    pet_dob DATE DEFAULT NULL,
    pet_weight DECIMAL(10,2) DEFAULT NULL,
    pet_sterilization ENUM('0', '1') CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL COMMENT '0: chưa triệt sản, 1: đã triệt sản',
    pet_characteristic TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    pet_drug_allergy TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE TABLE medical_records (
    medical_record_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    pet_id INT(11) NOT NULL,
    doctor_id INT(11) NOT NULL,
    medical_record_type ENUM('Khám', 'Điều trị', 'Vaccine') CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    medical_record_visit_date DATE NOT NULL,
    medical_record_summary TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    medical_record_details TEXT CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id) ON DELETE CASCADE
);

CREATE TABLE vaccination_records (
    medical_record_id INT(11) NOT NULL,
    vaccine_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
    batch_number VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci DEFAULT NULL,
    next_injection_date DATE DEFAULT NULL,
    PRIMARY KEY (medical_record_id, vaccine_name),
    FOREIGN KEY (medical_record_id) REFERENCES medical_records(medical_record_id) ON DELETE CASCADE
);

CREATE TABLE pet_enclosures (
    pet_enclosure_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    pet_id INT(11) NOT NULL,
    pet_enclosure_number INT(11) NOT NULL,
    check_in_date DATETIME NOT NULL,
    check_out_date DATETIME,
    daily_rate INT(11) NOT NULL,
    deposit INT(11) DEFAULT 0,
    emergency_limit INT(11) DEFAULT 0,
    pet_enclosure_note TEXT,
    pet_enclosure_status ENUM('Check In', 'Check Out') NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE
);

CREATE TABLE invoices (
    invoice_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    pet_id INT(11) NOT NULL,
    pet_enclosure_id INT(11) NOT NULL,
    invoice_date DATETIME NOT NULL,
    discount INT(11) DEFAULT 0,
    subtotal INT(11) NOT NULL,
    deposit INT(11) DEFAULT 0,
    total_amount INT(11) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(pet_id) ON DELETE CASCADE,
    FOREIGN KEY (pet_enclosure_id) REFERENCES pet_enclosures(pet_enclosure_id) ON DELETE CASCADE
);

CREATE TABLE invoice_details (
    detail_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT(11) NOT NULL,
    service_type_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    unit_price INT(11) NOT NULL,
    total_price INT(11) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE,
    FOREIGN KEY (service_type_id) REFERENCES service_types(service_type_id) ON DELETE CASCADE
);