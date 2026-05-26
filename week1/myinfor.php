<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Cá Nhân - Trịnh Như Long</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            /* Thay đổi hoàn toàn bảng màu sang Dark Theme */
            --bg-body: #0f172a; /* Màu nền tối thẫm */
            --card-bg: rgba(30, 41, 59, 0.7); /* Màu thẻ bán trong suốt */
            --text-main: #f1f5f9; /* Chữ trắng sáng */
            --text-secondary: #94a3b8; /* Chữ xám xanh */
            
            /* Gradient mới: Cyan tới Purple (Cyberpunk style) */
            --accent-gradient: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%);
            --glow-color: rgba(6, 182, 212, 0.5);
            
            --border-color: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: var(--bg-body);
            /* Thêm họa tiết nền mờ ảo */
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(6, 182, 212, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 40%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--text-main);
        }
        
        .container {
            background: var(--card-bg);
            backdrop-filter: blur(12px); /* Hiệu ứng kính mờ */
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 420px; /* Làm gọn chiều ngang hơn một chút */
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Thanh trang trí trên cùng */
        .top-bar {
            height: 6px;
            width: 100%;
            background: var(--accent-gradient);
        }
        
        .header {
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }
        
        .avatar-wrapper {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
        }

        .avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%; /* Hình tròn */
            background: linear-gradient(135deg, #1e293b, #0f172a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #22d3ee; /* Màu icon sáng neon */
            border: 2px solid rgba(34, 211, 238, 0.3);
            box-shadow: 0 0 20px var(--glow-color); /* Hiệu ứng phát sáng */
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(to right, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .header p {
            color: #22d3ee;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: rgba(34, 211, 238, 0.1);
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
        }
        
        .info-section {
            padding: 30px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 14px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.03); /* Nền item rất mờ */
            border: 1px solid transparent;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        /* Hiệu ứng hover đổi màu viền */
        .info-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(34, 211, 238, 0.3);
            transform: translateX(5px); /* Di chuyển nhẹ sang phải */
        }
        
        .icon {
            width: 38px;
            height: 38px;
            background: rgba(34, 211, 238, 0.1);
            color: #22d3ee;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 11px;
            color: var(--text-secondary);
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 15px;
            color: var(--text-main);
            font-weight: 500;
        }
        
        .current-time {
            text-align: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid var(--border-color);
        }

        .current-time .info-label {
            margin-bottom: 5px;
            opacity: 0.8;
        }
        
        .time-display {
            font-family: 'Courier New', monospace; /* Font kiểu kỹ thuật số */
            font-size: 18px;
            color: #e2e8f0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar"></div> <div class="header">
            <div class="avatar-wrapper">
                <div class="avatar">
                    <i class="fa-solid fa-user-astronaut"></i> </div>
            </div>
            <h1>Trịnh Như Long</h1>
            <p>Hồ sơ sinh viên</p>
        </div>
        
        <div class="info-section">
            <div class="info-item">
                <div class="icon"><i class="fa-solid fa-signature"></i></div>
                <div class="info-content">
                    <div class="info-label">Họ và Tên</div>
                    <div class="info-value">Trịnh Như Long</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="icon"><i class="fa-solid fa-cake-candles"></i></div>
                <div class="info-content">
                    <div class="info-label">Ngày Sinh</div>
                    <div class="info-value">04/03/2005</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-content">
                    <div class="info-label">Quê Quán</div>
                    <div class="info-value">Hà Nội, Việt Nam</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="icon"><i class="fa-solid fa-bolt"></i></div> <div class="info-content">
                    <div class="info-label">Sở Thích</div>
                    <div class="info-value">Bơi, Gym, Du lịch</div>
                </div>
            </div>
        </div>
        
        <div class="current-time">
            <div class="info-label">Real-time System</div>
            <div class="time-display" id="currentTime">--:--:--</div>
        </div>
    </div>
    
    <script>
        function updateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'numeric', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            let timeString = now.toLocaleDateString('vi-VN', options);
            document.getElementById('currentTime').textContent = timeString;
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>