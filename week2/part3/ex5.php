<?php
// Gán giá trị mặc định cho biến $c là '$'
function fmt(float $amt, string $c = '$'): string {
    return $c . number_format($amt, 2);
}

// Chạy thử
echo fmt(50); // Kết quả: $50.00
?>