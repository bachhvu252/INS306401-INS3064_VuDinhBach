<?php
$input = [1, 2, 3, 4, 5];
$reversed = [];

// Chạy vòng lặp từ cuối mảng về đầu
for ($i = count($input) - 1; $i >= 0; $i--) {
    $reversed[] = $input[$i];
}

// In kết quả
echo "[" . implode(", ", $reversed) . "]";
?>