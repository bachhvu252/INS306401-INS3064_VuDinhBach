<?php
session_start();
$error = '';
$success = '';
$usersFile = 'users.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Lỗi: Mật khẩu xác nhận không khớp!";
    } else {
        // Đọc dữ liệu cũ
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

        if (isset($users[$username])) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            // Lưu user mới với mật khẩu được hash
            $users[$username] = [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'bio' => '',
                'avatar' => ''
            ];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            $success = "Đăng ký thành công! Bạn có thể <a href='login.php'>đăng nhập</a> ngay.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Registration</title>
</head>

<body>
    <h2>Đăng Ký Tài Khoản</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="POST" action="">
        <p>Username: <input type="text" name="username" required></p>
        <p>Email: <input type="email" name="email" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <p>Confirm Password: <input type="password" name="confirm_password" required></p>
        <button type="submit">Đăng Ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</body>

</html>