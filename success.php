<?php
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>تم الحجز بنجاح</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --success-green: #22c55e; --dark-blue: #2c3e50; }
        body { 
            font-family: 'Cairo', sans-serif; 
            background-color: #ffffff; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            overflow: hidden;
        }
        .success-container { text-align: center; padding: 30px; width: 100%; max-width: 450px; }

        /* --- أنيميشن الدائرة وعلامة الصح البرمجي --- */
        .check-container {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 35px;
        }

        .svg-check {
            width: 110px;
            display: block;
            margin: 0 auto;
        }

        .path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 0;
        }
        .path.circle {
            -webkit-animation: dash 1s ease-in-out;
            animation: dash 1s ease-in-out;
        }
        .path.check {
            stroke-dashoffset: -100;
            -webkit-animation: dash-check 1s 0.4s ease-in-out forwards;
            animation: dash-check 1s 0.4s ease-in-out forwards;
        }

        @keyframes dash {
            0% { stroke-dashoffset: 1000; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes dash-check {
            0% { stroke-dashoffset: -100; }
            100% { stroke-dashoffset: 900; }
        }

        /* --- النصوص المعدلة حسب طلبك --- */
        h2 { color: var(--success-green); font-size: 28px; font-weight: 800; margin: 0 0 15px 0; }
        p { color: #4b5563; font-size: 17px; line-height: 1.8; margin: 0; font-weight: 600; padding: 0 15px; }

        .btn-done {
            margin-top: 45px;
            display: inline-block;
            padding: 15px 50px;
            background-color: var(--dark-blue);
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 17px;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(44, 62, 80, 0.2);
        }
        .btn-done:active { transform: scale(0.98); }
    </style>
</head>
<body>

<div class="success-container">
    <div class="check-container">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" class="svg-check">
            <circle class="path circle" fill="none" stroke="#22c55e" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
            <polyline class="path check" fill="none" stroke="#22c55e" stroke-width="10" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
        </svg>
    </div>

    <h2>تم الحجز بنجاح</h2>
    <p>ستصلك رسالة عبر الجوال أو البريد الإلكتروني المسجل لدى شركة سلامة.</p>

    <a href="index.php" class="btn-done">إغلاق</a>
</div>

</body>
</html>