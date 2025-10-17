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

// Nếu không có id thì quay về danh sách
if (!isset($_GET['id'])) {
    header("Location: medical_records.php");
    exit;
}

$medical_id = $_GET['id'];
$medical = getMedicalRecordById($medical_id);
if (!$medical) {
    header("Location: medical_records.php?error=1&msg=" . urlencode("ID hồ sơ khám không hợp lệ!"));
    exit;
}

// Nếu có bản ghi vaccine thì lấy thêm
$vaccination = null;
if ($medical['medical_record_type'] === "Vaccine") {
    $vaccination = getVaccinationByMedicalId($medical_id);
}

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

        $updated = updateMedicalRecord($medical_id, $customer_id, $pet_id, $doctor_id, $type, $visit_date, $summary, $details);

        if ($updated) {
            if ($type === "Vaccine" && !empty($vaccine_name)) {
                if ($vaccination) {
                    updateVaccinationRecord($medical_id, $vaccine_name, $batch_number, $next_injection_date);
                } else {
                    addVaccinationRecord($medical_id, $vaccine_name, $batch_number, $next_injection_date);
                }
            } else {
                if ($vaccination) {
                    deleteVaccinationRecordByMedicalId($medical_id);
                }
            }

            header("Location: medical_records.php?success=1&msg=" . urlencode("Cập nhật hồ sơ thành công!"));
            exit;
        } else {
            header("Location: medical_records.php?error=1&msg=" . urlencode("Cập nhật hồ sơ thất bại!"));
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
    <title>Sửa hồ sơ khám bệnh - Spa Thú Cưng Min Min</title>
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
                <h2>Sửa hồ sơ khám bệnh</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="customer_id">Chủ nuôi <span class="required-field">*</span></label>
                        <select name="customer_id" id="customer_id" required>
                            <option value="">-- Chọn chủ nuôi --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['customer_id'] ?>"
                                    <?= $c['customer_id'] == $medical['customer_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['customer_name']) . " - " . $c['customer_phone_number'] ?>
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
                                <option value="<?= $d['doctor_id'] ?>"
                                    <?= $d['doctor_id'] == $medical['doctor_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d['doctor_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medical_record_type">Loại <span class="required-field">*</span></label>
                        <select name="medical_record_type" id="medical_record_type" required>
                            <option value="Khám" <?= $medical['medical_record_type'] == "Khám" ? 'selected' : '' ?>>Khám</option>
                            <option value="Điều trị" <?= $medical['medical_record_type'] == "Điều trị" ? 'selected' : '' ?>>Điều trị</option>
                            <option value="Vaccine" <?= $medical['medical_record_type'] == "Vaccine" ? 'selected' : '' ?>>Vaccine</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medical_record_visit_date">Ngày khám <span class="required-field">*</span></label>
                        <input type="date" name="medical_record_visit_date" id="medical_record_visit_date"
                            value="<?= $medical['medical_record_visit_date'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="medical_record_summary">Tóm tắt</label>
                        <input type="text" name="medical_record_summary" id="medical_record_summary"
                            value="<?= htmlspecialchars($medical['medical_record_summary']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="medical_record_details">Chi tiết</label>
                        <textarea name="medical_record_details" id="medical_record_details" rows="3"><?= htmlspecialchars($medical['medical_record_details']) ?></textarea>
                    </div>

                    <!-- Vaccine fields -->
                    <div class="vaccine-fields">
                        <div class="form-group">
                            <label for="vaccine_name">Tên vaccine <span class="required-field">*</span></label>
                            <input type="text" name="vaccine_name" id="vaccine_name"
                                value="<?= $vaccination['vaccine_name'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="batch_number">Số lô</label>
                            <input type="text" name="batch_number" id="batch_number"
                                value="<?= $vaccination['batch_number'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="next_injection_date">Hẹn mũi tiêm kế tiếp</label>
                            <input type="date" name="next_injection_date" id="next_injection_date"
                                value="<?= $vaccination['next_injection_date'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
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
                vaccineNameInput.setAttribute("required", "required");
            } else {
                vaccineFields.style.display = "none";
                vaccineNameInput.removeAttribute("required");
            }
        }

        typeSelect.addEventListener("change", toggleVaccineFields);
        toggleVaccineFields();

        const pets = <?php echo json_encode($pets); ?>;
        const customerSelect = document.getElementById("customer_id");
        const petSelect = document.getElementById("pet_id");
        const medicalPetId = <?= isset($medical['pet_id']) ? (int)$medical['pet_id'] : 'null' ?>;

        function loadPets(customerId) {
            petSelect.innerHTML = "<option value=''>-- Chọn thú cưng --</option>";

            pets.filter(p => p.customer_id == customerId).forEach(p => {
                const opt = document.createElement("option");
                opt.value = p.pet_id;
                opt.textContent = p.pet_name;

                if (medicalPetId && p.pet_id == medicalPetId) {
                    opt.selected = true; // tự chọn đúng thú cưng khi edit
                }

                petSelect.appendChild(opt);
            });
        }

        // Khi thay đổi chủ nuôi → lọc lại thú cưng
        customerSelect.addEventListener("change", function() {
            loadPets(this.value);
        });

        // Nếu đang edit, tự động load đúng thú cưng ban đầu
        <?php if (!empty($medical['customer_id'])): ?>
            loadPets(<?= (int)$medical['customer_id'] ?>);
        <?php endif; ?>
    </script>
</body>

</html>