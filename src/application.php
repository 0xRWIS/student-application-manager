<?php 
include('./header.php'); 
include('./database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$dept_id = isset($_GET['id']) ? $_GET['id'] : null;
$program_name = "طلب التحاق";

if ($dept_id) {
    $stmt = $dbconnect->prepare("SELECT name FROM departments WHERE id = ?");
    $stmt->execute([$dept_id]);
    $program = $stmt->fetch();
    if ($program) $program_name = $program['name'];
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم طلب التحاق</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-20">

    <main class="max-w-5xl mx-auto mt-10 px-6">
        <div class="bg-white p-8 rounded-t-3xl border-x border-t border-gray-100 shadow-sm">
            <h1 class="text-2xl font-black text-gray-900 mb-2">استمارة التحاق: <?php echo htmlspecialchars($program_name); ?></h1>
            <p class="text-sm text-gray-400 font-medium italic">يرجى التأكد من مطابقة البيانات لجدول قاعدة البيانات `applications`</p>
        </div>

        <form action="process_application.php" method="POST" class="bg-white p-8 rounded-b-3xl border border-gray-100 shadow-sm space-y-10">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <input type="hidden" name="department_id" value="<?php echo $dept_id; ?>">

            <section>
                <h3 class="text-lg font-bold text-blue-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span> البيانات الشخصية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">اسم مقدم الطلب</label>
                        <input type="text" name="applicant_name" value="<?php echo $_SESSION['user_name']; ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">الجنسية</label>
                        <input type="text" name="nationality" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">الديانة</label>
                        <input type="text" name="religion" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">عنوان الإقامة الحالي</label>
                        <input type="text" name="residence_address" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">تليفون المنزل</label>
                        <input type="text" name="home_phone" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">رقم المحمول</label>
                        <input type="text" name="mobile_phone" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                    </div>
                </div>
            </section>

            <section class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-purple-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-purple-600 rounded-full"></span> بيانات ولي الأمر
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">اسم ولي الأمر</label>
                        <input type="text" name="guardian_name" class="w-full p-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">مهنة ولي الأمر</label>
                        <input type="text" name="guardian_occupation" class="w-full p-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">عنوان ولي الأمر</label>
                        <input type="text" name="guardian_address" class="w-full p-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">موبايل ولي الأمر</label>
                        <input type="text" name="guardian_mobile" class="w-full p-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-lg font-bold text-blue-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span> بيانات الميلاد والهوية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">تاريخ الميلاد</label>
                        <input type="date" name="birth_date" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">العمر (عند التقديم)</label>
                        <input type="number" name="age" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">الرقم القومي</label>
                        <input type="text" name="national_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">رقم جواز السفر</label>
                        <input type="text" name="passport_number" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">مكان صدور الهوية</label>
                        <input type="text" name="id_issue_place" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">تاريخ صدور الهوية</label>
                        <input type="date" name="id_issue_date" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">رقم بطاقة الخدمة الوطنية والعسكرية</label>
                        <input type="text" name="military_service_card_number" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-lg font-bold text-blue-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span> المؤهلات الدراسية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">الشهادة السابقة</label>
                        <input type="text" name="previous_certificate" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">سنة التخرج</label>
                        <input type="number" name="graduation_year" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">المجموع الكلي</label>
                        <input type="number" step="0.01" name="total_score" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">التقدير (Grade)</label>
                        <input type="text" name="grade" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">رقم الجلوس</label>
                        <input type="text" name="seat_number" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">اللغة الأجنبية الأولى</label>
                        <input type="text" name="first_foreign_language" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">اللغة الأجنبية الثانية</label>
                        <input type="text" name="second_foreign_language" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">اسم المدرسة الثانوية</label>
                        <input type="text" name="high_school_name" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">الإدارة التعليمية</label>
                        <input type="text" name="educational_region" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">المحافظة</label>
                        <input type="text" name="governorate" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>
            </section>

            <div class="pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 w-full md:w-1/3">
                    <label class="text-xs font-bold text-gray-500 uppercase">توقيع الطالب</label>
                    <input type="text" name="student_signature" class="w-full p-3 border-b-2 border-gray-200 outline-none focus:border-blue-600 transition-all placeholder:italic" placeholder="اكتب اسمك الثلاثي للتوقيع">
                </div>

                <div class="flex gap-4 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-100">
                        تأكيد وإرسال الطلب
                    </button>
                    <a href="home.php" class="px-10 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all text-center">
                        إلغاء
                    </a>
                </div>
            </div>
        </form>
    </main>

</body>
</html>