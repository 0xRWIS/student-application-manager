<?php 
// بدء الجلسة إذا لم تكن قد بدأت بعد
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('./database.php');

// حماية الصفحة: التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// التأكد من أن المستخدم الحالي هو مسؤول (Admin)
if ($_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

$success_msg = "";
$error_msg = "";

// معالجة طلب حذف جميع السجلات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_all_records') {
    try {
        // تنفيذ أمر مسح كافة البيانات من جدول التربية العسكرية
        $dbconnect->exec("DELETE FROM military_applications");
        $success_msg = "تم حذف جميع سجلات التربية العسكرية بنجاح.";
    } catch (PDOException $e) {
        $error_msg = "حدث خطأ أثناء محاولة حذف السجلات: " . $e->getMessage();
    }
}

try {
    // جلب كافة طلبات التربية العسكرية مرتبة من الأحدث للأقدم
    $stmt = $dbconnect->query("SELECT id, applicant_name, national_id, course_number, created_at FROM military_applications ORDER BY id DESC");
    $military_apps = $stmt->fetchAll();
    
    // حساب عدد السجلات الإجمالي
    $total_users = count($military_apps);
} catch (PDOException $e) {
    die("Error fetching military applications: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Military Applications</title>
    <link rel="icon" href="assets/images/aun-logo.png" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cairo:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen antialiased">

    <header class="flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="bg-amber-600 p-2.5 rounded-xl flex items-center justify-center shadow-lg shadow-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold text-gray-900 tracking-tight">Admin Dashboard</span>
                <span class="text-xs text-gray-400 font-medium">Military Training Service</span>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <a href="home.php" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 text-sm font-bold rounded-xl hover:bg-blue-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/xl" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Student Portal View
            </a>
                
            <a href="logout.php" class="flex items-center gap-2 text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span class="text-sm font-semibold">Logout</span>
            </a>
        </div>
    </header>

    <nav class="px-10 bg-white border-b border-gray-200">
        <div class="flex gap-10">
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            <a href="dashboard.php" class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-400 font-medium hover:text-gray-600 text-sm transition-all">Student Applications</a>
            <a href="dashboard-manager.php" class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-400 font-medium hover:text-gray-600 text-sm transition-all">Manage Programs</a>
            <a href="dashboard-student-manager.php" class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-400 font-medium hover:text-gray-600 text-sm transition-all">Students Manager</a>
            <a href="military-manager.php" class="flex items-center gap-2 py-4 border-b-2 border-amber-600 text-amber-600 font-bold text-sm transition-all">Military Registration</a>
        </div>
    </nav>

    <main class="px-10 py-8 max-w-7xl mx-auto">
        
        <?php if (!empty($success_msg)): ?>
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-semibold rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1-1 0 00-1.414-1.414L9 10.586 7.707 9.293a1-1 0 00-1.414 1.414l2 2a1-1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 text-sm font-semibold rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-sm text-gray-400 font-medium">Total Registered Users</span>
                    <h3 class="text-3xl font-extrabold text-gray-900"><?php echo $total_users; ?></h3>
                </div>
                <div class="bg-amber-50 p-3 rounded-xl text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-900 px-3 py-1 bg-amber-50 text-amber-800 rounded-lg inline-block uppercase tracking-wide border border-amber-100/50 self-start">
                            Military Training Management
                        </h2>
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-md border border-gray-200">
                            Count: <?php echo $total_users; ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($military_apps)): ?>
                        <form action="military-manager.php" method="POST" onsubmit="return confirm('تنبيه هام جداً! هل أنت متأكد تماماً من رغبتك في حذف جميع طلبات التربية العسكرية نهائياً من النظام؟ لا يمكن التراجع عن هذا الإجراء.');">
                            <input type="hidden" name="action" value="delete_all_records">
                            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-xs font-bold rounded-xl hover:bg-red-700 shadow-md shadow-red-100 active:scale-[0.98] transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete All Records
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="relative mb-8">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="militarySearchInput" placeholder="Search by name, national ID or course number..." 
                    class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-100 focus:border-amber-400 transition-all">
                </div>

                <div id="militaryContainer" class="space-y-0 border-t border-gray-50">
                    <?php if (!empty($military_apps)): ?>
                        <?php foreach ($military_apps as $app): ?>
                            <div class="military-row flex items-center justify-between py-6 border-b border-gray-50 hover:bg-slate-50/50 transition-colors px-2">
                                
                                <div class="space-y-1">
                                    <h3 class="student-name text-lg font-bold text-gray-900">
                                        <?php echo htmlspecialchars($app['applicant_name']); ?>
                                    </h3>
                                    
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <strong class="text-gray-500 font-medium">National ID:</strong> 
                                            <span class="student-id tracking-wider"><?php echo htmlspecialchars($app['national_id']); ?></span>
                                        </span>
                                        <span class="text-gray-200">•</span>
                                        <span>
                                            <strong class="text-gray-500 font-medium">Submitted:</strong> 
                                            <?php echo date('M d, Y - h:i A', strtotime($app['created_at'])); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="pt-2">
                                        <span class="course-tag px-3 py-1 bg-amber-50 text-amber-700 text-[11px] font-bold rounded-full border border-amber-100">
                                            <?php echo htmlspecialchars($app['course_number']); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <a href="delete-military.php?id=<?php echo $app['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this military registration record?');"
                                       class="flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-500 text-sm font-bold rounded-xl hover:bg-red-100 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete Record
                                    </a>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm text-gray-400 font-medium">No military training applications submitted yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

    <script>
        const searchInput = document.getElementById('militarySearchInput');
        const rows = document.querySelectorAll('.military-row');

        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase().trim();

            rows.forEach(row => {
                const name = row.querySelector('.student-name').textContent.toLowerCase();
                const nationalId = row.querySelector('.student-id').textContent.toLowerCase();
                const course = row.querySelector('.course-tag').textContent.toLowerCase();

                const matchesSearch = name.includes(searchText) || 
                                      nationalId.includes(searchText) || 
                                      course.includes(searchText);

                if (matchesSearch) {
                    row.style.display = "flex";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>