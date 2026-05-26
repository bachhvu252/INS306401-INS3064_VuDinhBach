<?php
session_start();

// Access Control: Chuyển hướng nếu chưa đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['user'];
$usersFile = 'users.json';
$users = json_decode(file_get_contents($usersFile), true);
$currentUser = $users[$username];
$msg = '';

// Tạo thư mục uploads nếu chưa có
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cập nhật Bio
    if (isset($_POST['bio'])) {
        $users[$username]['bio'] = $_POST['bio']; // Lưu dạng thô, sẽ escape lúc xuất ra
    }

    // Xử lý Upload Avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Kiểm tra định dạng (Chặn .exe, .pdf theo yêu cầu)
        $blockedExtensions = ['exe', 'pdf'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $blockedExtensions)) {
            $msg = "<p style='color:red;'>Lỗi: Không được phép tải lên file .exe hoặc .pdf!</p>";
        } elseif (in_array($fileExtension, $allowedExtensions)) {
            // Đặt lại tên file để tránh trùng lặp
            $newFileName = $username . '_' . time() . '.' . $fileExtension;
            $destPath = 'uploads/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $users[$username]['avatar'] = $destPath;
            }
        } else {
            $msg = "<p style='color:red;'>Chỉ chấp nhận file ảnh (jpg, png, gif).</p>";
        }
    }

    // Lưu lại thay đổi
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    $currentUser = $users[$username]; // Cập nhật lại data đang hiển thị
    if (!$msg) $msg = "<p style='color:green;'>Cập nhật Profile thành công!</p>";
}

// XSS Protection: Escape dữ liệu Bio trước khi hiển thị
$safeBio = htmlspecialchars($currentUser['bio'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Profile</title>
    <style>
        .profile-container {
            border: 1px solid #ccc;
            padding: 20px;
            width: 400px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <h2>Xin chào, <?php echo htmlspecialchars($username); ?>!</h2>

        <?php if ($currentUser['avatar']): ?>
            <img src="<?php echo htmlspecialchars($currentUser['avatar']); ?>" class="avatar" alt="Avatar">
        <?php else: ?>
            <div class="avatar" style="background:#ddd; display:inline-block;"></div>
        <?php endif; ?>

        <p><strong>Bio:</strong> <?php echo $safeBio; ?></p>

        <hr>
        <?php echo $msg; ?>

        <h3>Chỉnh sửa Profile</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <p>
                <label>Avatar mới:</label><br>
                <input type="file" name="avatar" accept="image/*">
            </p>
            <p>
                <label>Tiểu sử (Bio):</label><br>
                <textarea name="bio" rows="4" cols="40"><?php echo $safeBio; ?></textarea>
            </p>
            <button type="submit">Lưu thay đổi</button>
        </form>

        <br>
        <a href="logout.php"><button>Đăng Xuất</button></a>
    </div>
</body>

</html>