<?php 
// 1. تضمين الملفات الأساسية
include('./header.php'); 
include('./database.php');

// التأكد من تسجيل دخول المستخدم
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role']; // جلب الرتبة من الجلسة
$app_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$app_id) {
    // توجيه مختلف بناءً على الرتبة في حال عدم وجود ID
    header("Location: " . ($user_role === 'admin' ? 'dashboard.php' : 'my-applications.php'));
    exit();
}

try {
    // إذا كان مسؤولاً، نجلب الطلب بدون قيد user_id، أما الطالب فنقيده بـ id الخاص به
    if ($user_role === 'admin') {
        $stmt = $dbconnect->prepare("
            SELECT a.*, d.name as department_name 
            FROM applications a 
            INNER JOIN departments d ON a.department_id = d.id 
            WHERE a.id = ?
        ");
        $stmt->execute([$app_id]);
    } else {
        $stmt = $dbconnect->prepare("
            SELECT a.*, d.name as department_name 
            FROM applications a 
            INNER JOIN departments d ON a.department_id = d.id 
            WHERE a.id = ? AND a.user_id = ?
        ");
        $stmt->execute([$app_id, $_SESSION['user_id']]);
    }
    
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        die("<div class='p-10 text-center text-red-500 font-bold'>عذراً، لم يتم العثور على الطلب أو لا تملك صلاحية الوصول إليه.</div>");
    }
} catch (PDOException $e) {
    die("خطأ في قاعدة البيانات: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب - <?php echo htmlspecialchars($app['department_name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-20">

    <main class="max-w-5xl mx-auto mt-10 px-6">
        
        <div class="bg-white p-8 rounded-t-3xl border-x border-t border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">القسم المختار</span>
                <h1 class="text-3xl font-black text-gray-900"><?php echo htmlspecialchars($app['department_name']); ?></h1>
                <p class="text-sm text-gray-400 mt-1">رقم مرجع الطلب: #<?php echo $app['id']; ?></p>
            </div>
            
            <div class="flex flex-col items-start md:items-end">
                <span class="text-xs font-bold text-gray-400 mb-1 uppercase">حالة الطلب</span>
                <?php 
                    $status = strtolower($app['status']); 
                    $statusTranslations = ['pending' => 'قيد الانتظار', 'approved' => 'مقبول', 'rejected' => 'مرفوض'];
                    $statusColor = ($status == 'approved') ? 'bg-green-100 text-green-700' : (($status == 'rejected') ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700');
                ?>
                <div class="px-6 py-2 rounded-full font-black text-sm <?php echo $statusColor; ?>">
                    <?php echo $statusTranslations[$status] ?? $status; ?>
                </div>
            </div>
        </div>

        <div class="bg-white p-10 rounded-b-3xl border border-gray-100 shadow-sm space-y-12">
            
            <section>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-4">
                    المعلومات الشخصية <span class="h-px bg-gray-100 flex-grow"></span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">اسم مقدم الطلب</label>
                        <p class="text-gray-900 font-semibold border-b border-gray-50 pb-1"><?php echo htmlspecialchars($app['applicant_name']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">الجنسية</label>
                        <p class="text-gray-900 font-semibold border-b border-gray-50 pb-1"><?php echo htmlspecialchars($app['nationality']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">رقم الهاتف</label>
                        <p class="text-gray-900 font-semibold border-b border-gray-50 pb-1"><?php echo htmlspecialchars($app['mobile_phone']); ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-400">عنوان السكن</label>
                        <p class="text-gray-900 font-semibold border-b border-gray-50 pb-1"><?php echo htmlspecialchars($app['residence_address']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">الرقم القومي</label>
                        <p class="text-gray-900 font-semibold border-b border-gray-50 pb-1"><?php echo htmlspecialchars($app['national_id']); ?></p>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-4">
                    الخلفية الأكاديمية <span class="h-px bg-gray-100 flex-grow"></span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">المجموع الكلي</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['total_score']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">رقم الجلوس</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['seat_number']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">سنة التخرج</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['graduation_year']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">اسم المدرسة</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['high_school_name']); ?></p>
                    </div>
                </div>
            </section>

            <section class="bg-gray-50 p-8 rounded-2xl border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">بيانات ولي الأمر</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">اسم ولي الأمر</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['guardian_name']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">الوظيفة</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['guardian_occupation']); ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400">هاتف ولي الأمر</label>
                        <p class="text-gray-900 font-semibold"><?php echo htmlspecialchars($app['guardian_mobile']); ?></p>
                    </div>
                </div>
            </section>

            <div class="pt-8 border-t border-gray-100 flex justify-between items-center">
    <p class="text-[11px] text-gray-400 italic">تم تقديم الطلب في: <?php echo date('Y/m/d', strtotime($app['created_at'])); ?></p>
    <div class="flex gap-4">
        <button onclick="window.print()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition-all">
            طباعة الطلب
        </button>
        
        <?php if ($user_role === 'admin'): ?>
            <a href="dashboard.php" class="px-6 py-2.5 bg-purple-600 text-white text-sm font-bold rounded-xl hover:bg-purple-700 transition-all shadow-md shadow-purple-100">
                العودة للوحة التحكم
            </a>
        <?php else: ?>
            <a href="home.php" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-all">
                العودة للرئيسية
            </a>
        <?php endif; ?>
    </div>
</div>
        </div>
    </main>

</body>
</html>