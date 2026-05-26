<?php
session_start();
$error = '';
$usersFile = 'users.json';

// Khởi tạo biến đếm số lần sai nếu chưa có
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['login_attempts'] >= 3) {
        $error = "Tài khoản tạm khóa do nhập sai mật khẩu 3 lần. Vui lòng thử lại sau!";
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
            // Đăng nhập thành công, reset số lần sai
            $_SESSION['user'] = $username;
            $_SESSION['login_attempts'] = 0;
            header('Location: profile.php');
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $error = "Sai thông tin đăng nhập! (Thử nghiệm thất bại: " . $_SESSION['login_attempts'] . "/3)";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Đăng Nhập</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <p>Username: <input type="text" name="username" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <button type="submit">Đăng Nhập</button>
    </form>
    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</body>

</html>