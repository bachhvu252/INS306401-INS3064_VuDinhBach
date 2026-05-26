<?php
// courses/edit.php
require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header('Location: index.php'); exit; }

$db = Database::getInstance();
try {
    $course = $db->fetch('SELECT * FROM courses WHERE id = ?', [$id]);
    if (!$course) { header('Location: index.php'); exit; }
} catch (Exception $e) {
    die('Không lấy được dữ liệu khóa học.');
}

$errors = [];
$title = $course['title'];
$description = $course['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors['title'] = 'Vui lòng nhập tên khóa học.';
    } elseif (mb_strlen($title) < 3) {
        $errors['title'] = 'Tên khóa học phải có ít nhất 3 ký tự.';
    }

    if (empty($errors)) {
        try {
            $db->update('courses', [
                'title' => $title,
                'description' => $description
            ], 'id = ?', [$id]);
            header('Location: index.php?updated=1');
            exit;
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
    <title>Sửa khóa học</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 40px 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #2196F3; border-bottom: 2px solid #2196F3; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 16px; text-decoration: none;}
        .btn-save { background: #2196F3; }
        .btn-cancel { background: #f44336; margin-left: 10px; }
        .error-text { color: red; font-size: 14px; margin-top: 5px; display: block; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="container">
    <h1>Sửa thông tin khóa học</h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert-error"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Tên khóa học:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">
            <?php if (!empty($errors['title'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['title']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Mô tả:</label>
            <textarea name="description" rows="5"><?= htmlspecialchars($description) ?></textarea>
        </div>

        <button type="submit" class="btn btn-save">Cập nhật</button>
        <a href="index.php" class="btn btn-cancel">Hủy</a>
    </form>
</div>
</body>
</html>