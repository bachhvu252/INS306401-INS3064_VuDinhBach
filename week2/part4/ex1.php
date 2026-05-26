<?php
function calculateBMI(float $kg, float $m): string {
    $bmi = $kg / ($m * $m);
    $category = "";

    if ($bmi < 18.5) {
        $category = "Under";
    } elseif ($bmi <= 24.9) {
        $category = "Normal";
    } else {
        $category = "Over";
    }

    // Làm tròn 1 chữ số thập phân
    $bmiFormatted = number_format($bmi, 1);
    return "BMI: $bmiFormatted ($category)";
}

// Chạy thử
echo calculateBMI(70, 1.75); 
?>