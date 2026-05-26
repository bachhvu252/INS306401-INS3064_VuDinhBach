<?php
include 'utils.php'; // Gọi thư viện vừa tạo

// Mảng chứa các kịch bản kiểm thử (Test Cases)
$tests = [
    // Test sanitize
    [
        'function' => 'sanitize',
        'input' => ' <script>alert("Hi")</script> ',
        'expected' => '&lt;script&gt;alert(&quot;Hi&quot;)&lt;/script&gt;',
        'desc' => 'Remove spaces & convert HTML chars'
    ],
    // Test validateEmail
    [
        'function' => 'validateEmail',
        'input' => 'user@example.com',
        'expected' => true,
        'desc' => 'Valid Email format'
    ],
    [
        'function' => 'validateEmail',
        'input' => 'invalid-email',
        'expected' => false,
        'desc' => 'Invalid Email format'
    ],
    // Test validateLength
    [
        'function' => 'validateLength',
        'input' => ['hello', 3, 10], // Input kèm tham số min, max
        'expected' => true,
        'desc' => '"hello" length between 3 and 10'
    ],
    [
        'function' => 'validateLength',
        'input' => ['hi', 5, 10],
        'expected' => false,
        'desc' => '"hi" length is too short (< 5)'
    ],
    // Test validatePassword
    [
        'function' => 'validatePassword',
        'input' => 'Pass123!',
        'expected' => true,
        'desc' => 'Password > 8 chars & has special char (!)'
    ],
    [
        'function' => 'validatePassword',
        'input' => 'password123',
        'expected' => false,
        'desc' => 'Password missing special char'
    ],
    [
        'function' => 'validatePassword',
        'input' => 'Short!',
        'expected' => false,
        'desc' => 'Password too short (< 8 chars)'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Validation Test Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-color: #e4e9f7;
            --card-bg: #ffffff;
            --success: #2ecc71;
            --fail: #e74c3c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);
            min-height: 100vh;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 30px;
            overflow: hidden;
        }

        h1 {
            text-align: center;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            text-align: left;
            padding: 15px;
            background: #f7f9fc;
            color: #667eea;
            font-weight: 600;
            border-bottom: 2px solid #eee;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            color: #333;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 500;
            font-size: 12px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .pass {
            background-color: var(--success);
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
        }

        .fail {
            background-color: var(--fail);
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
        }

        code {
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 4px;
            font-family: monospace;
            color: #d63384;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Validation Library Tests</h1>
        <table>
            <thead>
                <tr>
                    <th>Function</th>
                    <th>Description</th>
                    <th>Input</th>
                    <th>Result</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tests as $test): ?>
                    <?php
                    // Gọi hàm động dựa trên tên hàm
                    $func = $test['function'];
                    $input = $test['input'];

                    // Xử lý trường hợp input là mảng (cho hàm validateLength)
                    if ($func == 'validateLength' && is_array($input)) {
                        $actual = $func($input[0], $input[1], $input[2]);
                        $inputDisplay = '"' . $input[0] . '" (' . $input[1] . '-' . $input[2] . ')';
                    } else {
                        $actual = $func($input);
                        $inputDisplay = htmlspecialchars($input);
                    }

                    // So sánh kết quả thực tế với kỳ vọng
                    $passed = ($actual === $test['expected']);
                    $statusClass = $passed ? 'pass' : 'fail';
                    $statusText = $passed ? 'PASS' : 'FAIL';

                    // Hiển thị true/false dưới dạng text
                    $actualText = $actual === true ? 'true' : ($actual === false ? 'false' : $actual);
                    ?>
                    <tr>
                        <td><code><?php echo $func; ?></code></td>
                        <td><?php echo $test['desc']; ?></td>
                        <td><code><?php echo $inputDisplay; ?></code></td>
                        <td><?php echo htmlspecialchars($actualText); ?></td>
                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>