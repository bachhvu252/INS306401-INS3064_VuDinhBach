<?php
// Dấu ? trước kiểu dữ liệu (int) cho phép nhận giá trị null
function isAdult(?int $age): bool {
    if ($age === null) {
        return false;
    }
    return $age >= 18;
}

// Chạy thử
var_dump(isAdult(null)); // Kết quả: bool(false)
?>