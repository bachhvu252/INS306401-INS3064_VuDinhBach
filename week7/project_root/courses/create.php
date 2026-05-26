<?php
// courses/create.php
require_once __DIR__ . '/../classes/Database.php';

$errors = [];
$title = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validate theo yêu cầu bài tập: Không rỗng và dài >= 3 ký tự
    if ($title === '') {
        $errors['title'] = 'Vui lòng nhập tên khóa học.';
    } elseif (mb_strlen($title) < 3) {
        $errors['title'] = 'Tên khóa học phải có ít nhất 3 ký tự.';
    }

    if (empty($errors)) {
        try {
            $db = Database::getInstance();
            $db->insert('courses', [
                'title' => $title,
                'description' => $description
            ]);
            header('Location: index.php?success=1');
            exit;
        } catch (Exception $e) {
            $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm khóa học</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 40px 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #4CAF50; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; font-size: 16px; text-decoration: none;}
        .btn-save { background: #4CAF50; }
        .btn-cancel { background: #f44336; margin-left: 10px; }
        .error-text { color: red; font-size: 14px; margin-top: 5px; display: block; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="container">
    <h1>Thêm khóa học mới</h1>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert-error"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Tên khóa học:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" placeholder="Nhập tên khóa học (tối thiểu 3 ký tự)">
            <?php if (!empty($errors['title'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['title']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Mô tả (Không bắt buộc):</label>
            <textarea name="description" rows="5" placeholder="Mô tả chi tiết khóa học..."><?= htmlspecialchars($description) ?></textarea>
        </div>

        <button type="submit" class="btn btn-save">Lưu khóa học</button>
        <a href="index.php" class="btn btn-cancel">Hủy</a>
    </form>
</div>
</body>
</html>