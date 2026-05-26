<?php
function isPrime(int $n): bool {
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) return false;
    }
    return true;
}

$primes = [];
for ($i = 1; $i <= 100; $i++) {
    if (isPrime($i)) {
        $primes[] = $i;
    }
}

echo implode(", ", $primes);
?>