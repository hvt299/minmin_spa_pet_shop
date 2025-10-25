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
require_once(APP_PATH . '/vaccine_function.php');
require_once(APP_PATH . '/pet_vaccination_function.php');

$customers = getAllCustomers();
$pets = getAllPets();
$doctors = getAllDoctors();
$vaccines = getAllVaccines();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $vaccine_id            = trim($_POST['vaccine_id']);
    $customer_id           = trim($_POST['customer_id']);
    $pet_id                = trim($_POST['pet_id']);
    $doctor_id             = trim($_POST['doctor_id']);
    $vaccination_date      = trim($_POST['vaccination_date']);
    $next_vaccination_date = $_POST['next_vaccination_date'] ?? null;
    $notes                 = $_POST['notes'] ?? null;

    if (!empty($vaccine_id) && !empty($customer_id) && !empty($pet_id) && !empty($doctor_id) && !empty($vaccination_date)) {
        if (addPetVaccination($vaccine_id, $customer_id, $pet_id, $doctor_id, $vaccination_date, $next_vaccination_date, $notes)) {
            header("Location: pet_vaccinations.php?success=1&msg=" . urlencode("Thêm lịch tiêm vaccine thành công!"));
            exit;
        } else {
            header("Location: pet_vaccinations.php?error=1&msg=" . urlencode("Thêm lịch tiêm vaccine thất bại!"));
            exit;
        }
    } else {
        header("Location: pet_vaccinations.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc!"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm lịch tiêm vaccine - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm lịch tiêm vaccine</h2>

                <form action="" method="post">
                    <!-- Chủ nuôi -->
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

                    <!-- Thú cưng -->
                    <div class="form-group">
                        <label for="pet_id">Tên thú cưng <span class="required-field">*</span></label>
                        <select name="pet_id" id="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                        </select>
                    </div>

                    <!-- Vaccine -->
                    <div class="form-group">
                        <label for="vaccine_id">Vaccine <span class="required-field">*</span></label>
                        <select name="vaccine_id" id="vaccine_id" required>
                            <option value="">-- Chọn vaccine --</option>
                            <?php foreach ($vaccines as $v): ?>
                                <option value="<?php echo $v['vaccine_id']; ?>">
                                    <?php echo htmlspecialchars($v['vaccine_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Bác sĩ -->
                    <div class="form-group">
                        <label for="doctor_id">Bác sĩ tiêm <span class="required-field">*</span></label>
                        <select name="doctor_id" id="doctor_id" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?php echo $d['doctor_id']; ?>">
                                    <?php echo htmlspecialchars($d['doctor_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Ngày tiêm -->
                    <div class="form-group">
                        <label for="vaccination_date">Ngày tiêm <span class="required-field">*</span></label>
                        <input type="date" name="vaccination_date" id="vaccination_date" required>
                    </div>

                    <!-- Ngày tiêm tiếp theo -->
                    <div class="form-group">
                        <label for="next_vaccination_date">Ngày tiêm tiếp theo</label>
                        <input type="date" name="next_vaccination_date" id="next_vaccination_date">
                    </div>

                    <!-- Ghi chú -->
                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea name="notes" id="notes" rows="3"></textarea>
                    </div>

                    <!-- Nút hành động -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="pet_vaccinations.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
        // Lọc thú cưng theo chủ nuôi
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