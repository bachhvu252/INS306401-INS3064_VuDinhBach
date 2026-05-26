<?php
// project_root/index.php
// Trang chủ điều hướng tới các module quản lý
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 0;
        }

        .description {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            text-align: center;
        }

        .menu-item:hover {
            background-color: #4CAF50;
            color: #fff;
            border-color: #4CAF50;
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
        }

        .menu-item h2 {
            margin: 10px 0 5px 0;
            font-size: 1.2rem;
        }

        .menu-item p {
            margin: 0;
            font-size: 0.9rem;
            color: #777;
        }

        .menu-item:hover p {
            color: #e0e0e0;
        }

        .icon {
            font-size: 2.5rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>🏫 Course Registration System</h1>
        <p class="description">Welcome to the admin dashboard. Please select a module below to get started.</p>

        <div class="menu-grid">
            <!-- Link tới module Sinh viên -->
            <a href="students/index.php" class="menu-item">
                <div class="icon">👨‍🎓</div>
                <h2>Student Management</h2>
                <p>Add, edit, or delete students</p>
            </a>

            <!-- Link tới module Khóa học -->
            <a href="courses/index.php" class="menu-item">
                <div class="icon">📚</div>
                <h2>Course Management</h2>
                <p>Add, edit, or delete courses</p>
            </a>

            <!-- Link tới module Đăng ký -->
            <a href="enrollments/index.php" class="menu-item">
                <div class="icon">📝</div>
                <h2>Enrollment Management</h2>
                <p>Enroll students into courses</p>
            </a>
        </div>
    </div>

</body>

</html>