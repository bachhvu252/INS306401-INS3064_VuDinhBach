<?php
// Khởi tạo biến
$name = $email = $phone = $message = "";
$errors = [];
$isSuccess = false;

// Kiểm tra xem người dùng có đang gửi form không (Refactor Exercise 1 logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lấy dữ liệu và làm sạch
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate dữ liệu (Kiểm tra rỗng)
    if (empty($name)) $errors['name'] = "Name is required";
    if (empty($email)) $errors['email'] = "Email is required";
    if (empty($phone)) $errors['phone'] = "Phone is required";
    if (empty($message)) $errors['message'] = "Message is required";

    // Nếu không có lỗi thì đánh dấu là thành công
    if (empty($errors)) {
        $isSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Self-Processing Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Giữ nguyên bảng màu Soft UI của bạn */
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --button-gradient: linear-gradient(to right, #667eea, #764ba2);
            --bg-color: #e4e9f7;
            --card-bg: #ffffff;
            --text-color: #333;
            --input-bg: #f7f9fc;
            --input-focus-border: #667eea;
            --shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --error-color: #ff4757;
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

        .card-container {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 30px;
            box-shadow: var(--shadow);
            max-width: 450px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        h1 {
            text-align: center;
            color: #4a4a4a;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 28px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input,
        textarea {
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

        textarea {
            border-radius: 20px;
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
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

        .error-msg {
            color: var(--error-color);
            font-size: 12px;
            margin-left: 15px;
            margin-top: 5px;
            display: block;
        }

        /* Style riêng cho màn hình Thank You */
        .thank-you-message {
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        .check-icon {
            font-size: 60px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="card-container">
        <?php if ($isSuccess): ?>
            <div class="thank-you-message">
                <div class="check-icon">✔</div>
                <h1>Thank You!</h1>
                <p>Your message has been sent successfully.</p>
                <p style="color: #666; font-size: 14px;">We will contact you shortly.</p>
                <a href="contact_self.php" style="display:inline-block; margin-top:20px; text-decoration:none; color:#667eea; font-weight:600;">Send another message</a>
            </div>

        <?php else: ?>
            <h1>Contact Us</h1>

            <form method="POST" action="contact_self.php" autocomplete="off">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Full Name" value="<?php echo $name; ?>">
                    <?php if (isset($errors['name'])) echo "<span class='error-msg'>" . $errors['name'] . "</span>"; ?>
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>">
                    <?php if (isset($errors['email'])) echo "<span class='error-msg'>" . $errors['email'] . "</span>"; ?>
                </div>

                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Phone Number" value="<?php echo $phone; ?>">
                    <?php if (isset($errors['phone'])) echo "<span class='error-msg'>" . $errors['phone'] . "</span>"; ?>
                </div>

                <div class="form-group">
                    <textarea name="message" placeholder="Type your message here..."><?php echo $message; ?></textarea>
                    <?php if (isset($errors['message'])) echo "<span class='error-msg'>" . $errors['message'] . "</span>"; ?>
                </div>

                <button type="submit">Send Message</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>