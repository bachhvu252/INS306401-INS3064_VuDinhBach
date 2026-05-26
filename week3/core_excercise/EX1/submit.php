<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submission Result</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #e4e9f7;
            background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .result-card {
            background: #fff;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
        }

        h2 {
            color: #667eea;
            text-align: center;
            margin-top: 0;
        }

        /* Style cho List theo yêu cầu Structured HTML list */
        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: #f7f9fc;
            margin-bottom: 12px;
            padding: 15px;
            border-radius: 15px;
            border-left: 5px solid #667eea;
            color: #333;
            font-size: 14px;
        }

        strong {
            color: #764ba2;
            margin-right: 5px;
        }

        .error-msg {
            color: #ff4757;
            font-weight: bold;
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .error-desc {
            text-align: center;
            color: #666;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: white;
            background: linear-gradient(to right, #667eea, #764ba2);
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.2);
        }
    </style>
</head>

<body>
    <div class="result-card">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu an toàn
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $message = isset($_POST['message']) ? trim($_POST['message']) : '';

            // 1. Kiểm tra rỗng (Tasks & Acceptance Criteria)
            // Nếu thiếu Name, Email, Phone hoặc Message thì báo lỗi
            if (empty($name) || empty($email) || empty($phone) || empty($message)) {
                echo "<div class='error-msg'>Missing Data</div>";
                echo "<div class='error-desc'>Please fill in all required fields.</div>";
            } else {
                // 2. Hiển thị danh sách (Acceptance Criteria: structured HTML list)
                echo "<h2>Submission Received</h2>";
                echo "<ul>";
                echo "<li><strong>Name:</strong> " . htmlspecialchars($name) . "</li>";
                echo "<li><strong>Email:</strong> " . htmlspecialchars($email) . "</li>";
                echo "<li><strong>Phone:</strong> " . htmlspecialchars($phone) . "</li>";
                echo "<li><strong>Message:</strong> " . htmlspecialchars($message) . "</li>";
                echo "</ul>";
            }
        } else {
            echo "<div class='error-desc'>No form data submitted.</div>";
        }
        ?>

        <a href="contact.html" class="back-btn">Go Back</a>
    </div>
</body>

</html>