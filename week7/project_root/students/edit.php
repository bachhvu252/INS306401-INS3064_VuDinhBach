<?php
// students/edit.php
require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header('Location: index.php'); exit; }

$db = Database::getInstance();
try {
    $student = $db->fetch('SELECT * FROM students WHERE id = ?', [$id]);
    if (!$student) { header('Location: index.php'); exit; }
} catch (Exception $e) {
    die('Không lấy được dữ liệu sinh viên.');
}

$errors = [];
$name  = $student['name'];
$email = $student['email'];
$phone = $student['phone'] ?? '';

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
            $existing = $db->fetch('SELECT id FROM students WHERE email = ? AND id <> ?', [$email, $id]);
            if ($existing) {
                $errors['email'] = 'Email đã thuộc về sinh viên khác.';
            } else {
                $db->update('students', [
                    'name'  => $name,
                    'email' => $email,
                    'phone' => $phone
                ], 'id = ?', [$id]);
                header('Location: index.php?updated=1');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'Có lỗi khi cập nhật, vui lòng thử lại.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sửa sinh viên</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 40px 20px; color: #333; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #2196F3; border-bottom: 2px solid #2196F3; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 16px; text-decoration: none;}
        .btn-save { background: #2196F3; }
        .btn-cancel { background: #f44336; margin-left: 10px; }
        .error-text { color: red; font-size: 14px; margin-top: 5px; display: block; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="container">
    <h1>Sửa thông tin sinh viên</h1>

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
            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
        </div>

        <button type="submit" class="btn btn-save">Cập nhật</button>
        <a href="index.php" class="btn btn-cancel">Hủy</a>
    </form>
</div>
</body>
</html>