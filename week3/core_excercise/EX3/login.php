<?php
// 1. Khởi tạo biến
$message = "";
$messageType = ""; // 'success' hoặc 'error'
$attempts = 0;

// 2. Xử lý khi người dùng bấm nút Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy số lần thử từ form gửi lên (nếu có), nếu không thì bằng 0
    if (isset($_POST['attempts'])) {
        $attempts = (int)$_POST['attempts'];
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 3. Kiểm tra tài khoản (Hardcode credentials theo đề bài)
    // User: admin, Pass: 123456
    if ($username === 'admin' && $password === '123456') {
        $message = "Login Successful";
        $messageType = "success";
        // Reset số lần thử nếu đăng nhập thành công
        $attempts = 0;
    } else {
        // Nếu sai, tăng biến đếm số lần thử
        $attempts++;
        $message = "Invalid Credentials. Failed Attempts: " . $attempts;
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Giữ nguyên bảng màu Soft UI bạn thích */
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --button-gradient: linear-gradient(to right, #667eea, #764ba2);
            --bg-color: #e4e9f7;
            --card-bg: #ffffff;
            --text-color: #333;
            --input-bg: #f7f9fc;
            --input-focus-border: #667eea;
            --shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 30px;
            box-shadow: var(--shadow);
            max-width: 400px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        h2 {
            text-align: center;
            color: #4a4a4a;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 28px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid transparent;
            border-radius: 50px;
            background: var(--input-bg);
            font-size: 14px;
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            background: #fff;
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        button {
            background: var(--button-gradient);
            color: white;
            padding: 15px 0;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.2);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(118, 75, 162, 0.3);
            filter: brightness(1.1);
        }

        /* Style cho thông báo kết quả */
        .alert {
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>Welcome Back</h2>

        <?php if ($message): ?>
            <div class="alert <?php echo ($messageType == 'success') ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off">
            <input type="hidden" name="attempts" value="<?php echo $attempts; ?>">

            <div class="form-group">
                <input type="text" name="username" placeholder="Username (admin)" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password (123456)" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

</body>

</html>