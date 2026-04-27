<?php 
include('./header.php'); 
include('./database.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}

try {
    $stats = [
        'total'    => $dbconnect->query("SELECT COUNT(*) FROM applications")->fetchColumn() ?: 0,
        'pending'  => $dbconnect->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'")->fetchColumn() ?: 0,
        'approved' => $dbconnect->query("SELECT COUNT(*) FROM applications WHERE status = 'approved'")->fetchColumn() ?: 0,
        'rejected' => $dbconnect->query("SELECT COUNT(*) FROM applications WHERE status = 'rejected'")->fetchColumn() ?: 0,
    ];
} catch (PDOException $e) {
    $stats = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Application Management</title>
</head>
<body class="bg-slate-50 min-h-screen">

        <header class="flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="bg-purple-600 p-2.5 rounded-xl flex items-center justify-center shadow-lg shadow-purple-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold text-gray-900 tracking-tight">Admin Dashboard</span>
                    <span class="text-xs text-gray-400 font-medium">Application Management System</span>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <a href="home.php" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 text-sm font-bold rounded-xl hover:bg-blue-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Student Portal View
                </a>

                <a href="logout.php" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="text-sm font-medium">Logout</span>
                </a>
            </div>
        </header>

    <nav class="px-10 bg-white border-b border-gray-200">
        <div class="flex gap-10">
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            <a href="dashboard.php" class="flex items-center gap-2 py-4 border-b-2 <?php echo ($current_page == 'dashboard.php') ? 'border-purple-600 text-purple-600 font-semibold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'; ?> text-sm transition-all">
                Student Applications
            </a>
            <a href="dashboard-manager.php" class="flex items-center gap-2 py-4 border-b-2 <?php echo ($current_page == 'dashboard-manager.php') ? 'border-purple-600 text-purple-600 font-semibold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'; ?> text-sm transition-all">
                Manage Programs
            </a>
            <a href="dashboard-student-manager.php" class="flex items-center gap-2 py-4 border-b-2 <?php echo ($current_page == 'dashboard-student-manager.php') ? 'border-purple-600 text-purple-600 font-semibold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'; ?> text-sm transition-all">
                Students Manager
            </a>
        </div>
    </nav>

    <main class="px-10 py-8 space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-blue-500 bg-blue-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Applications</span>
                </div>
                <span class="text-4xl font-black text-gray-900"><?php echo $stats['total']; ?></span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-orange-400 bg-orange-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Pending Review</span>
                </div>
                <span class="text-4xl font-black text-gray-900"><?php echo $stats['pending']; ?></span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-green-500 bg-green-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Approved</span>
                </div>
                <span class="text-4xl font-black text-gray-900"><?php echo $stats['approved']; ?></span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Rejected</span>
                </div>
                <span class="text-4xl font-black text-gray-900"><?php echo $stats['rejected']; ?></span>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="flex items-center gap-3 mb-8">
                <div class="text-purple-600 bg-purple-50 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Students by Department</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <?php
                try {
                    $query = "SELECT d.name, COUNT(u.id) as total_students 
                            FROM departments d 
                            LEFT JOIN users u ON d.id = u.department_id AND u.role = 'user'
                            GROUP BY d.id ORDER BY d.name ASC";
                    $deptList = $dbconnect->query($query)->fetchAll();

                    if (!empty($deptList)):
                        foreach ($deptList as $dept): ?>
                            <div class="bg-purple-50/40 p-6 rounded-xl border border-purple-100/50 hover:bg-purple-50 transition-colors">
                                <p class="text-3xl font-black text-purple-600"><?php echo $dept['total_students']; ?></p>
                                <p class="text-sm text-gray-600 mt-2 font-semibold"><?php echo htmlspecialchars($dept['name']); ?></p>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <p class="text-gray-400 italic text-sm">No departments created yet.</p>
                    <?php endif;
                } catch (PDOException $e) { echo "<p class='text-red-500'>Error loading departments.</p>"; }
                ?>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Application Management</h2>
                
                <div class="flex flex-wrap gap-3" id="filter-buttons">
                    <button data-filter="all" class="filter-btn px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-100">
                        All (<?php echo $stats['total']; ?>)
                    </button>
                    <button data-filter="pending" class="filter-btn px-5 py-2.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        Pending (<?php echo $stats['pending']; ?>)
                    </button>
                    <button data-filter="approved" class="filter-btn px-5 py-2.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        Approved (<?php echo $stats['approved']; ?>)
                    </button>
                    <button data-filter="rejected" class="filter-btn px-5 py-2.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        Rejected (<?php echo $stats['rejected']; ?>)
                    </button>
                </div>
            </div>

            <div id="applications-container" class="divide-y divide-gray-50">
    <?php
    try {
        $query = "SELECT a.*, d.name as dept_name, u.email 
                  FROM applications a 
                  JOIN departments d ON a.department_id = d.id 
                  JOIN users u ON a.user_id = u.id 
                  ORDER BY a.created_at DESC";
        $all_apps = $dbconnect->query($query)->fetchAll();

        if (!empty($all_apps)):
            foreach ($all_apps as $app): 
                $status = strtolower($app['status']);
                $statusColor = ($status == 'approved') ? 'text-green-600 bg-green-50' : (($status == 'rejected') ? 'text-red-600 bg-red-50' : 'text-amber-600 bg-amber-50');
            ?>
            <div class="app-row p-6 hover:bg-gray-50/50 transition-colors flex items-center justify-between" data-status="<?php echo $status; ?>">
                <div class="flex items-center gap-6">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($app['applicant_name']); ?></span>
                        <span class="text-xs text-gray-400"><?php echo htmlspecialchars($app['email']); ?></span>
                    </div>
                    <div class="h-8 w-px bg-gray-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">القسم</span>
                        <span class="text-xs font-semibold text-gray-600"><?php echo htmlspecialchars($app['dept_name']); ?></span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?php echo $statusColor; ?>">
                        <?php echo $status; ?>
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="view-applications.php?id=<?php echo $app['id']; ?>" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="عرض التفاصيل">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>

                    <?php if ($status === 'pending'): ?>
                        <a href="update-status.php?id=<?php echo $app['id']; ?>&action=approved" class="px-4 py-2 bg-green-500 text-white text-xs font-bold rounded-lg hover:bg-green-600 shadow-sm transition-all">قبول</a>
                        <a href="update-status.php?id=<?php echo $app['id']; ?>&action=rejected" class="px-4 py-2 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 shadow-sm transition-all">رفض</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div id="empty-state" class="p-20 text-center flex flex-col items-center justify-center">
                <p class="text-gray-400 font-medium italic">لا توجد سجلات حالياً.</p>
            </div>
        <?php endif;
    } catch (PDOException $e) { echo "Error: " . $e->getMessage(); }
    ?>
</div>
        </div>
    </main>

    <div class="fixed bottom-6 right-6">
        <button class="bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-900 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </button>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.app-row');
        const emptyState = document.getElementById('empty-state');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                buttons.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
                    btn.classList.add('bg-gray-50', 'text-gray-500');
                });
                this.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
                this.classList.remove('bg-gray-50', 'text-gray-500');

                let visibleCount = 0;
                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    if (filter === 'all' || status === filter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                emptyState.style.display = (visibleCount === 0) ? 'flex' : 'none';
            });
        });
    });
    </script>
</body>
</html>