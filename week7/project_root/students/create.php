<?php
// students/create.php
require_once __DIR__ . '/../classes/Database.php';

$errors = [];
$name   = '';
$email  = '';
$phone  = ''; // Biến cho số điện thoại

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '') $errors['name'] = 'Vui lòng nhập họ tên.';
    
    if ($email === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    if (empty($errors)) {
        try {
            $db = Database::getInstance();
            $existing = $db->fetch('SELECT id FROM students WHERE email = ?', [$email]);

            if ($existing) {
                $errors['email'] = 'Email này đã tồn tại trong hệ thống.';
            } else {
                $db->insert('students', [
                    'name'  => $name,
                    'email' => $email,
                    'phone' => $phone // Thêm data phone
                ]);
                header('Location: index.php?success=1');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'Lỗi hệ thống! Hãy kiểm tra log để xem chi tiết.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 40px 20px; color: #333; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 16px; text-decoration: none;}
        .btn-save { background: #4CAF50; }
        .btn-cancel { background: #f44336; margin-left: 10px; }
        .error-text { color: red; font-size: 14px; margin-top: 5px; display: block; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="container">
    <h1>Thêm sinh viên mới</h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert-error"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Họ tên:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
            <?php if (!empty($errors['name'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (!empty($errors['email'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['email']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="Ví dụ: 0987654321">
        </div>

        <button type="submit" class="btn btn-save">Lưu sinh viên</button>
        <a href="index.php" class="btn btn-cancel">Hủy</a>
    </form>
</div>
</body>
</html>