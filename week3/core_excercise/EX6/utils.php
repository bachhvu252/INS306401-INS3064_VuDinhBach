<?php
// 1. Hàm làm sạch dữ liệu
function sanitize($data)
{
    $data = trim($data);             // Xóa khoảng trắng thừa
    $data = stripslashes($data);     // Bỏ dấu gạch chéo
    $data = htmlspecialchars($data); // Chuyển ký tự đặc biệt thành thực thể HTML
    return $data;
}

// 2. Hàm kiểm tra Email
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 3. Hàm kiểm tra độ dài chuỗi (trong khoảng min, max)
function validateLength($str, $min, $max)
{
    $len = strlen($str);
    return $len >= $min && $len <= $max;
}

// 4. Hàm kiểm tra mật khẩu (Độ dài & Ký tự đặc biệt)
// Yêu cầu: Tối thiểu 8 ký tự và có ít nhất 1 ký tự đặc biệt
function validatePassword($pass)
{
    // Kiểm tra độ dài < 8
    if (strlen($pass) < 8) {
        return false;
    }
    // Kiểm tra ký tự đặc biệt (non-word characters) bằng Regex
    if (!preg_match('/[\W_]/', $pass)) {
        return false;
    }
    return true;
}
