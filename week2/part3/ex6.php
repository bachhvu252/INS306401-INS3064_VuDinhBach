<?php
/**
 * Một "Pure function" không gây ra hiệu ứng lề (như echo) 
 * và không phụ thuộc vào biến toàn cục.
 */
function add(int $a, int $b): int {
    return $a + $b;
}

// Chạy thử
echo add(5, 10);
?>