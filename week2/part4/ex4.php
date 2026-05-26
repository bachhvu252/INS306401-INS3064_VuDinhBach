<?php
$scores = [85, 92, 78, 60, 95, 88, 72];

function processScores(array $input): string {
    $count = count($input);
    $sum = array_sum($input);
    $avg = $sum / $count;
    $max = max($input);
    $min = min($input);
    
    $topPerformers = [];
    foreach ($input as $score) {
        if ($score > $avg) {
            $topPerformers[] = $score;
        }
    }

    return "Avg: " . round($avg) . ", Top: [" . implode(", ", $topPerformers) . "]";
}

// Chạy thử
echo processScores($scores);
?>