<?php
session_start();
require_once(dirname(__DIR__) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/medical_record_function.php');
require_once(APP_PATH . '/pet_enclosure_function.php');
require_once(APP_PATH . '/invoice_function.php');

// ===== Helper format number =====
function formatNumberShort($num)
{
    if ($num >= 1000000000) {
        return round($num / 1000000000, 1) . 'B';
    } elseif ($num >= 1000000) {
        return round($num / 1000000, 1) . 'M';
    } elseif ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    } else {
        return $num;
    }
}

// Thiết lập múi giờ Việt Nam (GMT+7)
date_default_timezone_set('Asia/Ho_Chi_Minh');

$currentYear = date('Y');
$currentMonth = date('m');

// ===== Lấy dữ liệu =====
$customerCount = getCustomerCount();
$petCount = getPetCount();
$medicalRecordCount = getMedicalRecordCountByMonth($currentYear, $currentMonth);
$petEnclosureCount = getPetEnclosureCountByMonth($currentYear, $currentMonth);
$invoiceRevenue = getInvoiceRevenueByYear($currentYear);

$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

$medicalYesterday = getMedicalRecordCountByDate($yesterday);
$medicalToday = getMedicalRecordCountByDate($today);
if ($medicalYesterday == 0 && $medicalToday > 0) {
    $medicalPercentChange = 100;
} elseif ($medicalYesterday > 0) {
    $medicalPercentChange = (($medicalToday - $medicalYesterday) / $medicalYesterday) * 100;
} else {
    $medicalPercentChange = 0;
}

$enclosureYesterday = getPetEnclosureCountByDate($yesterday);
$enclosureToday = getPetEnclosureCountByDate($today);
if ($enclosureYesterday == 0 && $enclosureToday > 0) {
    $enclosurePercentChange = 100;
} elseif ($enclosureYesterday > 0) {
    $enclosurePercentChange = (($enclosureToday - $enclosureYesterday) / $enclosureYesterday) * 100;
} else {
    $enclosurePercentChange = 0;
}

$lastMonth = date('m', strtotime('-1 month'));
$lastMonthYear = date('Y', strtotime('-1 month'));
$lastMonthRevenue = getInvoiceRevenueByMonth($lastMonthYear, $lastMonth);
$thisMonthRevenue = getInvoiceRevenueByMonth($currentYear, $currentMonth);

if ($lastMonthRevenue == 0 && $thisMonthRevenue > 0) {
    $revenuePercentChange = 100;
} elseif ($lastMonthRevenue > 0) {
    $revenuePercentChange = (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
} else {
    $revenuePercentChange = 0;
}

$medicalRecordsData = getMedicalRecordsByDay(7);
$dates = array_keys($medicalRecordsData);
$counts = array_values($medicalRecordsData);

$checkinCheckoutData = getCheckinCheckoutStats(7);
$monthlyRevenueStats = getMonthlyRevenueStats();

// $revenueByService = getRevenueByServiceType();
$revenueByService = getRevenueByServiceTypeAndYear($currentYear);
$serviceNames = array_column($revenueByService, 'service_name');
$serviceRevenues = array_column($revenueByService, 'total_revenue');

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Spa Thú Cưng Min Min</title>
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/grid.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <?php include(__DIR__ . '/../includes/sidebar.php'); ?>

    <main class="main">
        <?php include(__DIR__ . '/../includes/header.php'); ?>

        <main class="content">
            <div class="dashboard__cards">
                <div class="card customer">
                    <i class="card__icon fa-solid fa-user"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($customerCount) ?></span>
                    </div>
                    <p class="card__title">Tổng khách hàng</p>
                </div>

                <div class="card pet">
                    <i class="card__icon fa-solid fa-paw"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($petCount) ?></span>
                    </div>
                    <p class="card__title">Tổng thú cưng</p>
                </div>

                <div class="card medical">
                    <i class="card__icon fa-solid fa-stethoscope"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($medicalRecordCount) ?></span>
                        <span class="card__percent <?= $medicalPercentChange >= 0 ? 'up' : 'down' ?>">
                            <i class="fa <?= $medicalPercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                            <?= number_format($medicalPercentChange, 1) . '%' ?>
                        </span>
                    </div>
                    <p class="card__title">Lượt khám</p>
                </div>

                <div class="card enclosure">
                    <i class="card__icon fa-solid fa-house"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($petEnclosureCount) ?></span>
                        <span class="card__percent <?= $enclosurePercentChange >= 0 ? 'up' : 'down' ?>">
                            <i class="fa <?= $enclosurePercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                            <?= number_format($enclosurePercentChange, 1) . '%' ?>
                        </span>
                    </div>
                    <p class="card__title">Lượt lưu chuồng</p>
                </div>

                <div class="card revenue">
                    <i class="card__icon fa-solid fa-dollar-sign"></i>
                    <div class="card__info">
                        <span class="card__value"><?= formatNumberShort($invoiceRevenue) ?></span>
                        <span class="card__percent <?= $revenuePercentChange >= 0 ? 'up' : 'down' ?>">
                            <i class="fa <?= $revenuePercentChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                            <?= number_format($revenuePercentChange, 1) . '%' ?>
                        </span>
                    </div>
                    <p class="card__title">Doanh thu</p>
                </div>
            </div>

            <!-- ===== Biểu đồ ===== -->
            <div class="dashboard__charts">
                <div class="chart-card">
                    <h3 class="chart-title">Lượt khám (7 ngày gần nhất)</h3>
                    <canvas id="medicalChart" height="200"></canvas>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Check-in / Check-out (7 ngày gần nhất)</h3>
                    <canvas id="checkinCheckoutChart" height="200"></canvas>
                </div>
            </div>

            <div class="dashboard__charts">
                <div class="chart-card">
                    <h3 class="chart-title">Doanh thu theo tháng (12 tháng)</h3>
                    <canvas id="revenueChart" height="300"></canvas>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">Tỷ trọng doanh thu theo loại dịch vụ</h3>
                    <canvas id="serviceRevenueChart" height="300"></canvas>
                </div>
            </div>
        </main>
        
        <?php include(__DIR__ . '/../includes/footer.php'); ?>
    </main>


    <script src="../assets/js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function updateChartTheme() {
            const isDark = document.body.classList.contains('dark-mode');
            const textColor = isDark ? '#e0e0e0' : '#333';
            const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
            const tooltipBg = isDark ? 'rgba(0,0,0,0.8)' : 'rgba(255,255,255,0.9)';

            const charts = Object.values(Chart.instances || {});
            charts.forEach(instance => {
                const chart = instance.chart ?? instance;
                if (!chart || !chart.options) return;

                // Force override màu (xoá config cũ, đặt lại hoàn toàn)
                if (chart.options.plugins?.legend?.labels) {
                    chart.options.plugins.legend.labels.color = textColor;
                }

                if (chart.options.plugins?.tooltip) {
                    chart.options.plugins.tooltip.titleColor = textColor;
                    chart.options.plugins.tooltip.bodyColor = textColor;
                    chart.options.plugins.tooltip.backgroundColor = tooltipBg;
                }

                if (chart.options.scales) {
                    for (let axis in chart.options.scales) {
                        if (!chart.options.scales[axis]) continue;
                        if (chart.options.scales[axis].ticks) {
                            chart.options.scales[axis].ticks.color = textColor;
                        }
                        if (chart.options.scales[axis].grid) {
                            chart.options.scales[axis].grid.color = gridColor;
                        }
                    }
                }

                // 🟢 Đây là điểm quan trọng: ép Chart.js re-render lại mọi thứ
                chart.options.color = textColor;
                chart.update('active');
            });
        }
    </script>
    <script>
        // ===== LƯỢT KHÁM (7 NGÀY GẦN NHẤT) =====
        const ctxMedical = document.getElementById('medicalChart').getContext('2d');

        // Gradient nền cho area chart (từ đỏ đậm sang trong suốt)
        const medicalGradient = ctxMedical.createLinearGradient(0, 0, 0, 300);
        medicalGradient.addColorStop(0, 'rgba(220, 53, 69, 0.6)');
        medicalGradient.addColorStop(1, 'rgba(220, 53, 69, 0)');

        const medicalData = <?= json_encode($counts); ?>;
        const medicalLabels = <?= json_encode($dates); ?>;

        new Chart(ctxMedical, {
            type: 'line', // Chart.js không có 'area' riêng, dùng line + fill
            data: {
                labels: medicalLabels,
                datasets: [{
                    label: 'Lượt khám',
                    data: medicalData,
                    backgroundColor: medicalGradient,
                    borderColor: '#dc3545',
                    borderWidth: 1.5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#dc3545'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true, // bật chú thích
                        position: 'top',
                        labels: {
                            color: '#333',
                            font: {
                                size: 13,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.7)',
                        titleFont: {
                            size: 13
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: (context) => `Lượt khám: ${context.formattedValue}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#555'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            stepSize: 2,
                            color: '#555'
                        }
                    }
                }
            }
        });

        // ===== CHECK-IN / CHECK-OUT =====
        const checkinCheckoutData = <?= json_encode($checkinCheckoutData) ?>;
        const dates2 = Object.keys(checkinCheckoutData);
        const checkin = Object.values(checkinCheckoutData).map(d => d.checkin);
        const checkout = Object.values(checkinCheckoutData).map(d => d.checkout);

        const ctxCheck = document.getElementById('checkinCheckoutChart').getContext('2d');
        const checkinGradient = ctxCheck.createLinearGradient(0, 0, 0, 400);
        checkinGradient.addColorStop(0, '#ffcd39');
        checkinGradient.addColorStop(1, '#fff3cd');
        const checkoutGradient = ctxCheck.createLinearGradient(0, 0, 0, 400);
        checkoutGradient.addColorStop(0, '#28a745');
        checkoutGradient.addColorStop(1, '#c8f7d2');

        new Chart(ctxCheck, {
            type: 'bar',
            data: {
                labels: dates2,
                datasets: [{
                        label: 'Check-in',
                        data: checkin,
                        backgroundColor: checkinGradient,
                        borderColor: '#ffc107',
                        borderWidth: 1
                    },
                    {
                        label: 'Check-out',
                        data: checkout,
                        backgroundColor: checkoutGradient,
                        borderColor: '#28a745',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });

        // ===== DOANH THU THEO THÁNG =====
        const monthlyRevenueData = <?= json_encode(array_values($monthlyRevenueStats)) ?>;
        const monthlyLabels = <?= json_encode(array_map(fn($m) => date('m/Y', strtotime($m . '-01')), array_keys($monthlyRevenueStats))) ?>;

        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 300);
        revenueGradient.addColorStop(0, 'rgba(17, 122, 139, 0.5)');
        revenueGradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: monthlyRevenueData,
                    borderColor: '#17a2b8',
                    backgroundColor: revenueGradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '10%',
                        ticks: {
                            callback: value => value.toLocaleString('vi-VN') + ' ₫'
                        }
                    }
                }
            }
        });
    </script>
    <script>
        const ctxService = document.getElementById('serviceRevenueChart').getContext('2d');

        // Tạo 12 gradient màu
        const colors = Array.from({
            length: 12
        }, (_, i) => {
            const grad = ctxService.createLinearGradient(0, 0, 300, 300);
            return grad;
        });

        // Bộ màu cơ bản cho 12 dịch vụ
        const baseColors = [
            ['#4e73df', '#a5b6f2'], // Xanh dương
            ['#1cc88a', '#a0f0c0'], // Xanh lá
            ['#36b9cc', '#a5e7ef'], // Xanh ngọc
            ['#f6c23e', '#fde4a5'], // Vàng
            ['#e74a3b', '#f4a9a2'], // Đỏ cam
            ['#8e44ad', '#d7bce7'], // Tím
            ['#fd7e14', '#ffd6a5'], // Cam
            ['#20c997', '#9ff2d0'], // Xanh mint
            ['#6610f2', '#b28df7'], // Tím đậm
            ['#6f42c1', '#cbb2f5'], // Tím nhạt
            ['#adb5bd', '#dee2e6'], // Xám
            ['#795548', '#d7ccc8'] // Nâu đất
        ];

        // Áp dụng màu gradient
        colors.forEach((g, i) => {
            g.addColorStop(0, baseColors[i % baseColors.length][0]);
            g.addColorStop(1, baseColors[i % baseColors.length][1]);
        });

        new Chart(ctxService, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($serviceNames) ?>,
                datasets: [{
                    data: <?= json_encode($serviceRevenues) ?>,
                    backgroundColor: colors,
                    borderWidth: 1,
                    borderColor: '#fff',
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                aspectRatio: 1.75,
                cutout: '75%',
                radius: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333',
                            font: {
                                size: 13,
                                weight: '500'
                            },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                return context.label + ': ' + value.toLocaleString('vi-VN') + ' ₫';
                            }
                        }
                    },
                }
            }
        });
    </script>

</body>

</html>