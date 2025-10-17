<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/doctor_function.php');
require_once(APP_PATH . '/medical_record_function.php');

$customers = getAllCustomers();
$pets = getAllPets();
$doctors = getAllDoctors();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id   = trim($_POST['customer_id']);
    $pet_id        = trim($_POST['pet_id']);
    $doctor_id     = trim($_POST['doctor_id']);
    $type          = trim($_POST['medical_record_type']);
    $visit_date    = trim($_POST['medical_record_visit_date']);
    $summary       = trim($_POST['medical_record_summary']);
    $details       = trim($_POST['medical_record_details']);

    $vaccine_name  = $_POST['vaccine_name'] ?? null;
    $batch_number  = $_POST['batch_number'] ?? null;
    $next_injection_date = $_POST['next_injection_date'] ?? null;

    if (!empty($customer_id) && !empty($pet_id) && !empty($doctor_id) && !empty($type) && !empty($visit_date)) {

        $medical_id = addMedicalRecord($customer_id, $pet_id, $doctor_id, $type, $visit_date, $summary, $details);

        if ($medical_id) {
            if ($type === "Vaccine" && !empty($vaccine_name)) {
                addVaccinationRecord($medical_id, $vaccine_name, $batch_number, $next_injection_date);
            }

            header("Location: medical_records.php?success=1&msg=" . urlencode("Thêm hồ sơ khám bệnh thành công!"));
            exit;
        } else {
            header("Location: medical_records.php?error=1&msg=" . urlencode("Thêm hồ sơ thất bại!"));
            exit;
        }
    } else {
        header("Location: medical_records.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc!"));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm hồ sơ khám bệnh - Spa Thú Cưng Min Min</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/grid.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <?php include_once(ADMIN_INC . '/sidebar.php'); ?>

    <main class="main">
        <!-- Header -->
        <?php include_once(ADMIN_INC . '/header.php'); ?>
        <!-- Main content -->
        <main class="content">
            <div class="form-container">
                <h2>Thêm hồ sơ khám bệnh</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="customer_id">Chủ nuôi <span class="required-field">*</span></label>
                        <select name="customer_id" id="customer_id" required>
                            <option value="">-- Chọn chủ nuôi --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?php echo $c['customer_id']; ?>">
                                    <?php echo htmlspecialchars($c['customer_name']) . " - " . $c['customer_phone_number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet_id">Tên thú cưng <span class="required-field">*</span></label>
                        <select name="pet_id" id="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Bác sĩ <span class="required-field">*</span></label>
                        <select name="doctor_id" id="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['doctor_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medical_record_type">Loại <span class="required-field">*</span></label>
                        <select name="medical_record_type" id="medical_record_type" required>
                            <option value="Khám">Khám</option>
                            <option value="Điều trị">Điều trị</option>
                            <option value="Vaccine">Vaccine</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medical_record_visit_date">Ngày khám <span class="required-field">*</span></label>
                        <input type="date" name="medical_record_visit_date" id="medical_record_visit_date" required>
                    </div>

                    <div class="form-group">
                        <label for="medical_record_summary">Tóm tắt</label>
                        <input type="text" name="medical_record_summary" id="medical_record_summary">
                    </div>

                    <div class="form-group">
                        <label for="medical_record_details">Chi tiết</label>
                        <textarea name="medical_record_details" id="medical_record_details" rows="3"></textarea>
                    </div>

                    <!-- Vaccine fields -->
                    <div class="vaccine-fields">
                        <div class="form-group">
                            <label for="vaccine_name">Tên vaccine <span class="required-field">*</span></label>
                            <input type="text" name="vaccine_name" id="vaccine_name">
                        </div>
                        <div class="form-group">
                            <label for="batch_number">Số lô</label>
                            <input type="text" name="batch_number" id="batch_number">
                        </div>
                        <div class="form-group">
                            <label for="next_injection_date">Hẹn mũi tiêm kế tiếp</label>
                            <input type="date" name="next_injection_date" id="next_injection_date">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="medical_records.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
        const typeSelect = document.getElementById("medical_record_type");
        const vaccineFields = document.querySelector(".vaccine-fields");
        const vaccineNameInput = document.getElementById("vaccine_name");

        function toggleVaccineFields() {
            if (typeSelect.value === "Vaccine") {
                vaccineFields.style.display = "block";
                vaccineNameInput.setAttribute("required", "required"); // bắt buộc khi Vaccine
            } else {
                vaccineFields.style.display = "none";
                vaccineNameInput.removeAttribute("required"); // bỏ bắt buộc khi không phải Vaccine
            }
        }

        typeSelect.addEventListener("change", toggleVaccineFields);
        toggleVaccineFields(); // chạy 1 lần khi load

        const pets = <?php echo json_encode($pets); ?>;
        const customerSelect = document.getElementById("customer_id");
        const petSelect = document.getElementById("pet_id");

        customerSelect.addEventListener("change", function() {
            const customerId = this.value;
            petSelect.innerHTML = "<option value=''>-- Chọn thú cưng --</option>";

            pets.filter(p => p.customer_id == customerId)
                .forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.pet_id;
                    opt.textContent = p.pet_name;
                    petSelect.appendChild(opt);
                });
        });
    </script>

</body>

</html>