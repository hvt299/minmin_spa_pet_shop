<?php
require_once(dirname(__DIR__) . '/init.php');
$currentPage = $_SERVER['REQUEST_URI'];

function isActive($path)
{
    global $currentPage;
    return str_contains($currentPage, $path);
}
?>
<aside class="sidebar" id="sidebar">
    <a href="<?= BASE_URL ?>/admin/pages/dashboard.php" class="sidebar__logo">
        <div class="sidebar__logo-icon"><i class="fa-solid fa-paw"></i></div>
        <div class="sidebar__logo-text">Min Min Spa Pet Shop</div>
    </a>
    <nav class="sidebar__menu">
        <div class="sidebar__item <?= isActive('/admin/pages/dashboard.php') ? 'sidebar__item--active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/pages/dashboard.php" class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="sidebar__text">Tổng quan</span>
            </a>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/user/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-circle-user"></i></span>
                <span class="sidebar__text">Người dùng</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/user/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/user/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/user/users.php">Danh sách người dùng</a>
                <a href="<?= BASE_URL ?>/admin/pages/user/add_user.php">Thêm người dùng</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/customer/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-users"></i></span>
                <span class="sidebar__text">Khách hàng</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/customer/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/customer/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/customer/customers.php">Danh sách khách hàng</a>
                <a href="<?= BASE_URL ?>/admin/pages/customer/add_customer.php">Thêm khách hàng</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/doctor/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-user-doctor"></i></span>
                <span class="sidebar__text">Bác sĩ</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/doctor/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/doctor/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/doctor/doctors.php">Danh sách bác sĩ</a>
                <a href="<?= BASE_URL ?>/admin/pages/doctor/add_doctor.php">Thêm bác sĩ</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/pet/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-dog"></i></span>
                <span class="sidebar__text">Thú cưng</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/pet/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/pet/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/pet/pets.php">Danh sách thú cưng</a>
                <a href="<?= BASE_URL ?>/admin/pages/pet/add_pet.php">Thêm thú cưng</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/medical_record/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-stethoscope"></i></span>
                <span class="sidebar__text">Khám bệnh</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/medical_record/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/medical_record/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/medical_record/medical_records.php">Lịch sử khám</a>
                <a href="<?= BASE_URL ?>/admin/pages/medical_record/add_medical_record.php">Tạo phiếu khám</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/pet_enclosure/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-spa"></i></span>
                <span class="sidebar__text">Lưu chuồng</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/pet_enclosure/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/pet_enclosure/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/pet_enclosure/pet_enclosures.php">Danh sách chuồng</a>
                <a href="<?= BASE_URL ?>/admin/pages/pet_enclosure/add_pet_enclosure.php">Thêm chuồng thú</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/service_type/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-hands-holding-circle"></i></span>
                <span class="sidebar__text">Dịch vụ</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/service_type/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/service_type/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/service_type/service_types.php">Danh sách dịch vụ</a>
                <a href="<?= BASE_URL ?>/admin/pages/service_type/add_service_type.php">Thêm dịch vụ</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/medicine/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-prescription-bottle-medical"></i></span>
                <span class="sidebar__text">Kho thuốc</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/medicine/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/medicine/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/medicine/medicines.php">Danh sách thuốc</a>
                <a href="<?= BASE_URL ?>/admin/pages/medicine/add_medicine.php">Thêm thuốc</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/vaccine/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-syringe"></i></span>
                <span class="sidebar__text">Vaccine</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/vaccine/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/vaccine/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/vaccine/vaccines.php">Danh sách vaccine</a>
                <a href="<?= BASE_URL ?>/admin/pages/vaccine/add_vaccine.php">Thêm vaccine</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item sidebar__item--has-submenu <?= isActive('/admin/pages/pet_vaccination/') ? 'sidebar__item--active' : '' ?>">
            <div class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-shield-virus"></i></span>
                <span class="sidebar__text">Lịch sử tiêm vaccine</span>
                <span class="sidebar__arrow"><i class="fa-solid <?= isActive('/admin/pages/pet_vaccination/') ? 'fa-chevron-down' : 'fa-chevron-left' ?>"></i></span>
            </div>
            <div class="sidebar__submenu" style="<?= isActive('/admin/pages/pet_vaccination/') ? 'display: flex;' : '' ?>">
                <a href="<?= BASE_URL ?>/admin/pages/pet_vaccination/pet_vaccinations.php">Danh sách tiêm vaccine</a>
                <a href="<?= BASE_URL ?>/admin/pages/pet_vaccination/add_pet_vaccination.php">Thêm lượt tiêm vaccine</a>
            </div>
            <div class="sidebar__submenu-popup"></div>
        </div>

        <div class="sidebar__item <?= isActive('/admin/pages/invoice/') ? 'sidebar__item--active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/pages/invoice/invoices.php" class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-file-invoice"></i></span>
                <span class="sidebar__text">Hóa đơn</span>
            </a>
        </div>

        <div class="sidebar__item <?= isActive('/admin/pages/printing_template/') ? 'sidebar__item--active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/pages/printing_template/printing_template.php" class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-print"></i></span>
                <span class="sidebar__text">Mẫu in</span>
            </a>
        </div>

        <div class="sidebar__item <?= isActive('/admin/pages/general_setting/') ? 'sidebar__item--active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/pages/general_setting/general_setting.php" class="sidebar__link">
                <span class="sidebar__icon"><i class="fa-solid fa-gear"></i></span>
                <span class="sidebar__text">Cài đặt</span>
            </a>
        </div>
    </nav>
</aside>