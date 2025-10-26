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
require_once(APP_PATH . '/treatment_course_function.php');

// $treatment_courses = getAllTreatmentCourses();
$customers = getAllCustomers();
$pets = getAllPets();

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy dữ liệu phân trang
$treatment_courses = getTreatmentCoursesPaginated($limit, $offset);
$totalCourses = getTreatmentCourseCount();
$totalPages = ceil($totalCourses / $limit);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đợt khám & điều trị - Spa Thú Cưng Min Min</title>
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
            <h1>Đợt khám & điều trị</h1>

            <!-- Bộ lọc -->
            <div class="filter-box">
                <select id="customerFilter">
                    <option value="">-- Tất cả chủ nuôi --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['customer_id']; ?>">
                            <?php echo htmlspecialchars($c['customer_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select id="petFilter">
                    <option value="">-- Tất cả thú cưng --</option>
                    <?php foreach ($pets as $p): ?>
                        <option value="<?php echo $p['pet_id']; ?>">
                            <?php echo htmlspecialchars($p['pet_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="text" id="searchInput" placeholder="Tìm theo thú cưng, chủ nuôi, trạng thái...">
                <a href="add_treatment_course.php" class="btn btn-add"><i class="fas fa-plus"></i> Thêm đợt khám</a>
            </div>

            <div class="table-responsive">
                <table class="admin-data-table" id="courseTable">
                    <thead>
                        <tr>
                            <th>Mã đợt</th>
                            <th>Chủ nuôi</th>
                            <th>Thú cưng</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($treatment_courses): ?>
                            <?php foreach ($treatment_courses as $row): ?>
                                <tr data-customer="<?php echo $row['customer_id']; ?>" data-pet="<?php echo $row['pet_id']; ?>">
                                    <td><?php echo htmlspecialchars($row['treatment_course_id']); ?></td>
                                    <td><?php echo htmlspecialchars(getCustomerById($row['customer_id'])['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars(getPetById($row['pet_id'])['pet_name']); ?></td>
                                    <td>
                                        <?php
                                        echo !empty($row['start_date'])
                                            ? date('d-m-Y', strtotime($row['start_date']))
                                            : '';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo !empty($row['end_date'])
                                            ? date('d-m-Y', strtotime($row['end_date']))
                                            : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $row['status'] === '1'
                                            ? '<span class="status-active">Đang điều trị</span>'
                                            : '<span class="status-completed">Kết thúc</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="edit_treatment_course.php?id=<?= $row['treatment_course_id'] ?>"
                                                class="btn btn-icon btn-edit"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <?php if ($row['status'] == '1'): ?>
                                                <a href="complete_treatment_course.php?id=<?= $row['treatment_course_id'] ?>"
                                                    class="btn btn-icon btn-check"
                                                    title="Hoàn tất điều trị"
                                                    onclick="return confirm('Xác nhận hoàn tất đợt điều trị này?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>

                                            <a href="treatment_sessions.php?treatment_course_id=<?= $row['treatment_course_id'] ?>"
                                                class="btn btn-icon btn-steth"
                                                title="Quản lý lần khám">
                                                <i class="fa-solid fa-stethoscope"></i>
                                            </a>

                                            <a href="delete_treatment_course.php?id=<?= $row['treatment_course_id'] ?>"
                                                class="btn btn-icon btn-delete"
                                                title="Xóa"
                                                onclick="return confirm('Bạn có chắc muốn xóa đợt khám này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Chưa có đợt khám nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="page-link">&laquo; Trước</a>
                        <?php endif; ?>

                        <?php
                        $maxLinks = 5;
                        $start = max(1, $page - floor($maxLinks / 2));
                        $end = min($totalPages, $start + $maxLinks - 1);

                        if ($end - $start < $maxLinks - 1) {
                            $start = max(1, $end - $maxLinks + 1);
                        }

                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="page-link">Sau &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
        const customerFilter = document.getElementById("customerFilter");
        const petFilter = document.getElementById("petFilter");
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("#courseTable tbody tr");

        function filterCourses() {
            const customerId = customerFilter.value;
            const petId = petFilter.value;
            const keyword = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const matchCustomer = !customerId || row.getAttribute("data-customer") === customerId;
                const matchPet = !petId || row.getAttribute("data-pet") === petId;
                const text = row.innerText.toLowerCase();
                const matchSearch = text.includes(keyword);

                row.style.display = (matchCustomer && matchPet && matchSearch) ? "" : "none";
            });
        }

        customerFilter.addEventListener("change", filterCourses);
        petFilter.addEventListener("change", filterCourses);
        searchInput.addEventListener("keyup", filterCourses);
    </script>
</body>

</html>