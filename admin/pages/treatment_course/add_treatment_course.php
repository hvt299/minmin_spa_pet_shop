<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/treatment_course_function.php');

$customers = getAllCustomers();
$pets = getAllPets();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id = trim($_POST['customer_id']);
    $pet_id      = trim($_POST['pet_id']);
    $start_date  = trim($_POST['start_date']);
    $end_date    = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    $status      = trim($_POST['status']);

    if (!empty($customer_id) && !empty($pet_id) && !empty($start_date)) {
        $result = addTreatmentCourse($customer_id, $pet_id, $start_date, $end_date, $status);

        if ($result) {
            header("Location: treatment_courses.php?success=1&msg=" . urlencode("Thêm đợt khám thành công!"));
            exit;
        } else {
            header("Location: treatment_courses.php?error=1&msg=" . urlencode("Thêm đợt khám thất bại!"));
            exit;
        }
    } else {
        header("Location: treatment_courses.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc!"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm đợt khám - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm đợt khám</h2>
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
                        <label for="pet_id">Thú cưng <span class="required-field">*</span></label>
                        <select name="pet_id" id="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Ngày bắt đầu <span class="required-field">*</span></label>
                        <input type="date" name="start_date" id="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="end_date">Ngày kết thúc</label>
                        <input type="date" name="end_date" id="end_date">
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái <span class="required-field">*</span></label>
                        <select name="status" id="status" required>
                            <option value="1">Đang điều trị</option>
                            <option value="0">Kết thúc</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="treatment_courses.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
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