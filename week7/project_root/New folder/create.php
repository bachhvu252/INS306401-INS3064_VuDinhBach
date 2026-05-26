<?php
// enrollments/create.php
require_once __DIR__ . '/../classes/Database.php';
$db = Database::getInstance();

$students = $db->fetchAll('SELECT id, name, email FROM students ORDER BY name');
$courses  = $db->fetchAll('SELECT id, title FROM courses ORDER BY title');

$errors = [];
$student_id = 0;
$course_id  = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) ($_POST['student_id'] ?? 0);
    $course_id  = (int) ($_POST['course_id']  ?? 0);

    if ($student_id <= 0) $errors['student_id'] = 'Vui lòng chọn sinh viên.';
    if ($course_id <= 0) $errors['course_id'] = 'Vui lòng chọn khóa học.';

    if (empty($errors)) {
        try {
            $exists = $db->fetch('SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?', [$student_id, $course_id]);
            if ($exists) {
                $errors['general'] = 'Sinh viên này đã đăng ký khóa học này rồi.';
            } else {
                $db->insert('enrollments', [
                    'student_id' => $student_id,
                    'course_id'  => $course_id,
                ]);
                header('Location: index.php?success=1');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'Lỗi hệ thống, vui lòng thử lại sau.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thêm đăng ký học</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 40px 20px;
            color: #333;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-size: 16px;
            text-decoration: none;
        }

        .btn-save {
            background: #4CAF50;
        }

        .btn-cancel {
            background: #f44336;
            margin-left: 10px;
        }

        .error-text {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Ghi danh khóa học</h1>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert-error"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Chọn Sinh viên:</label>
                <select name="student_id">
                    <option value="0">-- Vui lòng chọn --</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($s['id'] == $student_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['student_id'])): ?>
                    <span class="error-text"><?= htmlspecialchars($errors['student_id']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Chọn Khóa học:</label>
                <select name="course_id">
                    <option value="0">-- Vui lòng chọn --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($c['id'] == $course_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['course_id'])): ?>
                    <span class="error-text"><?= htmlspecialchars($errors['course_id']) ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-save">Lưu đăng ký</button>
            <a href="index.php" class="btn btn-cancel">Hủy</a>
        </form>
    </div>
</body>

</html>