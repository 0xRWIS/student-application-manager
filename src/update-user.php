<?php
include('./database.php');
include('./header.php');

// 1. التحقق من وجود معرف المستخدم وجلب البيانات
$user_id = isset($_GET['id']) ? $_GET['id'] : null;
$user = null;
$app = null;

if ($user_id) {
    // جلب البيانات الأساسية من جدول users
    $stmtUser = $dbconnect->prepare("SELECT * FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch();

    // جلب البيانات التفصيلية من جدول applications
    $stmtApp = $dbconnect->prepare("SELECT * FROM applications WHERE user_id = ?");
    $stmtApp->execute([$user_id]);
    $app = $stmtApp->fetch();
}

// إذا لم يوجد المستخدم نعود للوحة التحكم
if (!$user) {
    header("Location: dashboard-student-manager.php");
    exit();
}

// جلب قائمة الأقسام للقائمة المنسدلة
$depts = $dbconnect->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

// 2. منطق تحديث البيانات عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_changes'])) {
    try {
        $dbconnect->beginTransaction();

        // منطق الـ NULL: إذا كانت القيمة فارغة تصبح NULL حقيقي في PHP
        $dept_id_value = !empty($_POST['department_id']) ? $_POST['department_id'] : null;

        // التحديث الأول: جدول users (هنا نركز على تحويل القسم لـ NULL)
        $upUser = $dbconnect->prepare("UPDATE users SET full_name = ?, phone = ?, department_id = ? WHERE id = ?");
        $upUser->execute([
            $_POST['applicant_name'], 
            $_POST['mobile_phone'], 
            $dept_id_value, // سيتم إدخال NULL هنا إذا اخترت "لم يتم تحديد قسم"
            $user_id
        ]);

        // التحديث الثاني: جدول applications (تم حذف department_id من هنا بناءً على طلبك لتجنب أخطاء الربط)
        $upApp = $dbconnect->prepare("UPDATE applications SET 
            applicant_name = ?, nationality = ?, religion = ?, residence_address = ?, 
            home_phone = ?, mobile_phone = ?, guardian_name = ?, guardian_occupation = ?, 
            guardian_address = ?, guardian_mobile = ?, birth_date = ?, age = ?, 
            national_id = ?, passport_number = ?, id_issue_place = ?, id_issue_date = ?, 
            military_service_card_number = ?, previous_certificate = ?, graduation_year = ?, 
            total_score = ?, grade = ?, seat_number = ?, first_foreign_language = ?, 
            second_foreign_language = ?, high_school_name = ?, educational_region = ?, 
            governorate = ?, student_signature = ?
            WHERE user_id = ?");

        $upApp->execute([
            $_POST['applicant_name'], $_POST['nationality'], $_POST['religion'], $_POST['residence_address'],
            $_POST['home_phone'], $_POST['mobile_phone'], $_POST['guardian_name'], $_POST['guardian_occupation'],
            $_POST['guardian_address'], $_POST['guardian_mobile'], $_POST['birth_date'], $_POST['age'],
            $_POST['national_id'], $_POST['passport_number'], $_POST['id_issue_place'], $_POST['id_issue_date'],
            $_POST['military_service_card_number'], $_POST['previous_certificate'], $_POST['graduation_year'],
            $_POST['total_score'], $_POST['grade'], $_POST['seat_number'], $_POST['first_foreign_language'],
            $_POST['second_foreign_language'], $_POST['high_school_name'], $_POST['educational_region'],
            $_POST['governorate'], $_POST['student_signature'], 
            $user_id
        ]);

        $dbconnect->commit();
        echo "<script>alert('تم تحديث البيانات بنجاح وتغيير القسم في جدول المستخدمين'); window.location.href='update-user.php?id=$user_id';</script>";
    } catch (Exception $e) {
        $dbconnect->rollBack();
        $error = "حدث خطأ أثناء التحديث: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث بيانات الطالب - الإدارة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen pb-20">

    <main class="max-w-5xl mx-auto mt-10 px-6">
        <div class="bg-white p-8 rounded-t-3xl border-x border-t border-gray-100 shadow-sm">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-black text-gray-900">تعديل الملف الشامل: <?php echo htmlspecialchars($user['full_name']); ?></h1>
                <a href="dashboard-student-manager.php" class="text-blue-600 font-bold hover:underline">العودة للوحة التحكم</a>
            </div>
        </div>

        <form method="POST" class="bg-white p-8 rounded-b-3xl border border-gray-100 shadow-sm space-y-10">
            
            <?php if(isset($error)): ?>
                <div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-100"><?php echo $error; ?></div>
            <?php endif; ?>

            <section>
                <h3 class="text-lg font-bold text-blue-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span> التحكم في القسم والبيانات الأساسية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">اسم مقدم الطلب</label>
                        <input type="text" name="applicant_name" value="<?php echo htmlspecialchars($app['applicant_name'] ?? $user['full_name']); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">رقم المحمول</label>
                        <input type="text" name="mobile_phone" value="<?php echo htmlspecialchars($app['mobile_phone'] ?? $user['phone']); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700">القسم الدراسي</label>
                        <select name="department_id" class="w-full p-3 bg-blue-50 border border-blue-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-bold text-blue-800">
                            <option value="">لم يتم تحديد قسم</option>
                            <?php foreach($depts as $d): ?>
                                <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == ($user['department_id'] ?? '')) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </section>

            <section>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">الجنسية</label>
                        <input type="text" name="nationality" value="<?php echo htmlspecialchars($app['nationality'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">الديانة</label>
                        <input type="text" name="religion" value="<?php echo htmlspecialchars($app['religion'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">تليفون المنزل</label>
                        <input type="text" name="home_phone" value="<?php echo htmlspecialchars($app['home_phone'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-gray-500">عنوان الإقامة الحالي</label>
                        <input type="text" name="residence_address" value="<?php echo htmlspecialchars($app['residence_address'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>
            </section>

            <section class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-purple-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-purple-600 rounded-full"></span> بيانات ولي الأمر
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">اسم ولي الأمر</label>
                        <input type="text" name="guardian_name" value="<?php echo htmlspecialchars($app['guardian_name'] ?? ''); ?>" class="w-full p-3 bg-white border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">مهنة ولي الأمر</label>
                        <input type="text" name="guardian_occupation" value="<?php echo htmlspecialchars($app['guardian_occupation'] ?? ''); ?>" class="w-full p-3 bg-white border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">عنوان ولي الأمر</label>
                        <input type="text" name="guardian_address" value="<?php echo htmlspecialchars($app['guardian_address'] ?? ''); ?>" class="w-full p-3 bg-white border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">موبايل ولي الأمر</label>
                        <input type="text" name="guardian_mobile" value="<?php echo htmlspecialchars($app['guardian_mobile'] ?? ''); ?>" class="w-full p-3 bg-white border border-gray-200 rounded-xl">
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-lg font-bold text-blue-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span> بيانات الميلاد والهوية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">تاريخ الميلاد</label>
                        <input type="date" name="birth_date" value="<?php echo $app['birth_date'] ?? ''; ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">العمر</label>
                        <input type="number" name="age" value="<?php echo $app['age'] ?? ''; ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">الرقم القومي</label>
                        <input type="text" name="national_id" value="<?php echo htmlspecialchars($app['national_id'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">رقم جواز السفر</label>
                        <input type="text" name="passport_number" value="<?php echo htmlspecialchars($app['passport_number'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">مكان الصدور</label>
                        <input type="text" name="id_issue_place" value="<?php echo htmlspecialchars($app['id_issue_place'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">تاريخ الصدور</label>
                        <input type="date" name="id_issue_date" value="<?php echo $app['id_issue_date'] ?? ''; ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-gray-500">رقم الخدمة العسكرية (جند)</label>
                        <input type="text" name="military_service_card_number" value="<?php echo htmlspecialchars($app['military_service_card_number'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-lg font-bold text-blue-600 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span> المؤهلات والشهادات
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">الشهادة السابقة</label>
                        <input type="text" name="previous_certificate" value="<?php echo htmlspecialchars($app['previous_certificate'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">سنة التخرج</label>
                        <input type="number" name="graduation_year" value="<?php echo $app['graduation_year'] ?? ''; ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">المجموع</label>
                        <input type="number" step="0.01" name="total_score" value="<?php echo $app['total_score'] ?? ''; ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">التقدير</label>
                        <input type="text" name="grade" value="<?php echo htmlspecialchars($app['grade'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">رقم الجلوس</label>
                        <input type="text" name="seat_number" value="<?php echo htmlspecialchars($app['seat_number'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">اللغة الأولى</label>
                        <input type="text" name="first_foreign_language" value="<?php echo htmlspecialchars($app['first_foreign_language'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">اللغة الثانية</label>
                        <input type="text" name="second_foreign_language" value="<?php echo htmlspecialchars($app['second_foreign_language'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">المدرسة</label>
                        <input type="text" name="high_school_name" value="<?php echo htmlspecialchars($app['high_school_name'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">الإدارة التعليمية</label>
                        <input type="text" name="educational_region" value="<?php echo htmlspecialchars($app['educational_region'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500">المحافظة</label>
                        <input type="text" name="governorate" value="<?php echo htmlspecialchars($app['governorate'] ?? ''); ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>
            </section>

            <div class="pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 w-full md:w-1/3">
                    <label class="text-xs font-bold text-gray-500 uppercase">توقيع الطالب الرقمي</label>
                    <input type="text" name="student_signature" value="<?php echo htmlspecialchars($app['student_signature'] ?? ''); ?>" class="w-full p-3 border-b-2 border-gray-200 focus:border-blue-600 outline-none transition-all placeholder:italic" placeholder="الاسم الثلاثي">
                </div>

                <div class="flex gap-4 w-full md:w-auto">
                    <button type="submit" name="save_changes" class="flex-1 md:px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-100">
                        حفظ التغييرات النهائية
                    </button>
                    <a href="dashboard-student-manager.php" class="px-10 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all text-center">
                        إلغاء
                    </a>
                </div>
            </div>
        </form>
    </main>
</body>
</html>