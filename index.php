<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truy cập Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #f4f6fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .access {
            background: #ffffff;
            border: 1px solid #e5e9f2;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            width: 350px;
            padding: 50px 40px;
            text-align: center;
        }

        .access__icon {
            font-size: 60px;
            color: #2a5bd7;
            margin-bottom: 25px;
        }

        .access__title {
            font-size: 22px;
            color: #2a2a2a;
            font-weight: 600;
            margin-bottom: 35px;
            letter-spacing: 0.5px;
        }

        .access__button {
            display: inline-block;
            background: #2a5bd7;
            color: #fff;
            border: none;
            padding: 12px 0;
            width: 100%;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .access__button:hover {
            background: #234ec0;
            box-shadow: 0 2px 8px rgba(42, 91, 215, 0.3);
        }

        @media (max-width: 400px) {
            .access {
                width: 90%;
                padding: 40px 25px;
            }
        }
    </style>
</head>

<body>
    <section class="access">
        <div class="access__icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2 class="access__title">Truy cập khu vực quản trị</h2>

        <!-- Nút chuyển hướng đến thư mục admin -->
        <a href="admin/" class="access__button">
            <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập Admin
        </a>
    </section>
</body>

</html>