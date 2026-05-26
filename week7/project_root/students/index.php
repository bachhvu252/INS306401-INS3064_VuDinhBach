<?php
// students/index.php
require_once __DIR__ . '/../classes/Database.php';
$db = Database::getInstance();

$students = $db->fetchAll('SELECT * FROM students ORDER BY created_at DESC');

$successMessage = '';
$errorMessage = '';

if (isset($_GET['success'])) $successMessage = 'Thêm sinh viên thành công!';
elseif (isset($_GET['updated'])) $successMessage = 'Cập nhật sinh viên thành công!';
elseif (isset($_GET['deleted'])) $successMessage = 'Xóa sinh viên thành công!';
elseif (isset($_GET['error'])) $errorMessage = 'Có lỗi xảy ra, không thể thực hiện thao tác!';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 40px 20px; color: #333; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; margin-bottom: 20px; }
        h1 { color: #4CAF50; margin: 0; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; color: #fff; border: none; cursor: pointer; display: inline-block; }
        .btn-add { background: #4CAF50; }
        .btn-add:hover { background: #45a049; }
        .btn-edit { background: #2196F3; font-size: 13px; padding: 5px 10px; }
        .btn-delete { background: #f44336; font-size: 13px; padding: 5px 10px; }
        .btn-back { background: #607d8b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>👨‍🎓 Quản lý Sinh viên</h1>
        <div>
            <a href="../index.php" class="btn btn-back">⬅ Quay lại Trang chủ</a>
            <a href="create.php" class="btn btn-add">+ Thêm sinh viên</a>
        </div>
    </div>

    <!-- Thông báo rõ ràng theo yêu cầu bài tập -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="alert alert-error">❌ <?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><strong><?= htmlspecialchars($student['name']) ?></strong></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['phone'] ?? 'Chưa cập nhật') ?></td>
                <td><?= $student['created_at'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-edit">Sửa</a>
                    <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>