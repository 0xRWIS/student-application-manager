<?php
session_start();
include('database.php');

// حماية الصفحة: التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// محاولة جلب الاسم والرقم القومي تلقائياً من آخر تقديم أكاديمي للطالب لتسهيل التجربة
$preFillName = $_SESSION['full_name'] ?? '';
$preFillNationalId = '';

try {
    $stmtCheck = $dbconnect->prepare("SELECT applicant_name, national_id FROM applications WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    $stmtCheck->execute([$user_id]);
    $existingApp = $stmtCheck->fetch();
    
    if ($existingApp) {
        if (!empty($existingApp['applicant_name'])) $preFillName = $existingApp['applicant_name'];
        $preFillNationalId = $existingApp['national_id'];
    }
} catch (PDOException $e) {
    // يمكن تجاهل الخطأ هنا والاستمرار بالحقول فارغة
}

// معالجة إرسال النموذج (Form Submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_name = trim($_POST['applicant_name']);
    $national_id = trim($_POST['national_id']);
    $course_number = trim($_POST['course_number']);

    if (empty($applicant_name) || empty($national_id) || empty($course_number)) {
        $error_msg = "جميع الحقول مطلوبة لإتمام التسجيل.";
    } elseif (strlen($national_id) < 14) {
        $error_msg = "الرجاء إدخال رقم قومي صحيح مكون من 14 رقماً.";
    } else {
        try {
            // إدخال الطلب في جدول التربية العسكرية
            $stmtInsert = $dbconnect->prepare("INSERT INTO military_applications (user_id, applicant_name, national_id, course_number, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmtInsert->execute([$user_id, $applicant_name, $national_id, $course_number]);
            
            $success_msg = "تم تسجيل طلبك في دورة التربية العسكرية رقم ($course_number) بنجاح!";
        } catch (PDOException $e) {
            $error_msg = "حدث خطأ أثناء حفظ البيانات: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل في دورة التربية العسكرية</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col justify-between">

    <nav class="flex items-center justify-between px-10 py-4 bg-white border-b border-gray-100 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="bg-amber-600 p-2 rounded-xl text-white shadow-md shadow-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span class="text-lg font-bold text-gray-900">إدارة التربية العسكرية | جامعة أسيوط</span>
        </div>
        <a href="home.php" class="text-sm font-semibold text-amber-700 hover:text-amber-800 transition-colors">العودة للبوابة ←</a>
    </nav>

    <main class="flex-grow flex items-center justify-center p-6 my-8">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xl shadow-slate-100/70 w-full max-w-xl">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl font-black text-slate-900 mb-2">استمارة التسجيل الإلكتروني</h1>
                <p class="text-sm text-gray-400">يرجى التأكد من صحة البيانات القومية المرفقة قبل الإرسال</p>
            </div>

            <?php if (!empty($success_msg)): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-2xl flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1-1 0 00-1.414-1.414L9 10.586 7.707 9.293a1-1 0 00-1.414 1.414l2 2a1-1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 text-sm font-bold rounded-2xl flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="military-application.php" method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">اسم الطالب رباعي كما هو بالبطاقة</label>
                    <input type="text" name="applicant_name" value="<?php echo htmlspecialchars($preFillName); ?>" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الرقم القومي (14 رقماً)</label>
                    <input type="text" name="national_id" maxlength="14" placeholder="2990101XXXXXXXX" value="<?php echo htmlspecialchars($preFillNationalId); ?>" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm tracking-wider focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">رقم دورة التربية العسكرية المتاحة</label>
                    <select name="course_number" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all bg-white">
                        <option value="">-- اختر رقم الدورة التدريبية --</option>
                        <option value="الدورة 45 (يونيو / يوليو)">الدورة 45 (يونيو / يوليو)</option>
                        <option value="الدورة 46 (أغسطس / سبتمبر)">الدورة 46 (أغسطس / سبتمبر)</option>
                        <option value="الدورة 47 (أكتوبر / نوفمبر)">الدورة 47 (أكتوبر / نوفمبر)</option>
                    </select>
                </div>

                <button type="submit" 
                class="w-full py-4 bg-amber-600 text-white font-bold rounded-xl text-sm hover:bg-amber-700 shadow-lg shadow-amber-100 active:scale-[0.99] transition-all">
                    تأكيد وإرسال طلب الالتحاق
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-4 text-center text-xs text-gray-400">
        جميع الحقوق محفوظة © 2026 جامعة أسيوط.
    </footer>

</body>
</html>