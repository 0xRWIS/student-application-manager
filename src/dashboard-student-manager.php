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

$depts = $dbconnect->query("SELECT name FROM departments ORDER BY name ASC")->fetchAll();

$students = $dbconnect->query("SELECT id, full_name, email, department_id FROM users WHERE role = 'user' ORDER BY full_name ASC")->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Management</title>
</head>
<body class="bg-slate-50 min-h-screen">

    <header class="flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100">
        <div class="flex items-center gap-4">
            <div class="bg-purple-600 p-2.5 rounded-xl flex items-center justify-center shadow-lg shadow-purple-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
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
            <a href="dashboard.php" class="flex items-center gap-2 py-4 border-b-2 <?php echo ($current_page == 'dashboard.php') ? 'border-purple-600 text-purple-600 font-semibold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'; ?> text-sm transition-all">Student Applications</a>
            <a href="dashboard-manager.php" class="flex items-center gap-2 py-4 border-b-2 <?php echo ($current_page == 'dashboard-manager.php') ? 'border-purple-600 text-purple-600 font-semibold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'; ?> text-sm transition-all">Manage Programs</a>
            <a href="dashboard-student-manager.php" class="flex items-center gap-2 py-4 border-b-2 <?php echo ($current_page == 'dashboard-student-manager.php') ? 'border-purple-600 text-purple-600 font-semibold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'; ?> text-sm transition-all">Students Manager</a>
        </div>
    </nav>

    <main class="px-10 py-8">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 px-1 bg-cyan-50 inline-block uppercase tracking-wide">Student Management</h2>

                <div class="relative mb-8">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" id="searchInput" placeholder="Search students by name or email..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-100 focus:border-purple-400 transition-all">
                </div>

                <div class="flex flex-wrap gap-3 mb-10" id="filterButtons">
                    <button data-dept="all" class="filter-btn px-6 py-2 bg-purple-600 text-white text-sm font-bold rounded-xl shadow-md shadow-purple-100 transition-all">All</button>
                    <?php foreach ($depts as $dept): ?>
                        <button data-dept="<?php echo htmlspecialchars($dept['name']); ?>" class="filter-btn px-5 py-2 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-all border border-transparent hover:border-gray-200">
                            <?php echo htmlspecialchars($dept['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div id="studentContainer" class="space-y-0 border-t border-gray-50">
                    <?php if ($students): ?>
                        <?php foreach ($students as $student): ?>
                            <div class="student-row flex items-center justify-between py-8 border-b border-gray-50 hover:bg-slate-50/50 transition-colors px-2">
                                <div class="space-y-2">
                                    <h3 class="student-name text-lg font-bold text-gray-900"><?php echo htmlspecialchars($student['full_name']); ?></h3>
                                    <p class="text-sm text-gray-400"><?php echo htmlspecialchars($student['email']); ?></p>
                                    
                                    <div class="flex gap-2 pt-1 student-depts">
                                        <?php
                                        if (!empty($student['department_id'])) {
                                            $stmtDept = $dbconnect->prepare("SELECT name FROM departments WHERE id = ?");
                                            $stmtDept->execute([$student['department_id']]);
                                            $deptInfo = $stmtDept->fetch();
                                            
                                            if ($deptInfo): ?>
                                                <span class="dept-tag px-3 py-1 bg-purple-50 text-purple-500 text-[10px] font-bold rounded-full uppercase">
                                                    <?php echo htmlspecialchars($deptInfo['name']); ?>
                                                </span>
                                            <?php endif;
                                        } else { ?>
                                            <span class="text-[10px] text-gray-300 italic uppercase">No Programs</span>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <a href="update-user.php?id=<?php echo $student['id']; ?>" 
                                        class="flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-600 text-sm font-bold rounded-xl hover:bg-blue-100 transition-all">
                                        Update
                                    </a>

                                    <a href="delete-user.php?id=<?php echo $student['id']; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this student?');"
                                    class="flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-500 text-sm font-bold rounded-xl hover:bg-red-100 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.student-row');
        let currentFilter = 'all';

        function updateDisplay() {
            const searchText = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const name = row.querySelector('.student-name').textContent.toLowerCase();
                // Get the text from the department tag
                const deptTag = row.querySelector('.dept-tag');
                const studentDeptName = deptTag ? deptTag.textContent.trim().toUpperCase() : "";
                
                const matchesSearch = name.includes(searchText);
                const matchesFilter = (currentFilter === 'all' || studentDeptName === currentFilter.toUpperCase());

                if (matchesSearch && matchesFilter) {
                    row.style.display = "flex";
                } else {
                    row.style.display = "none";
                }
            });
        }

        searchInput.addEventListener('keyup', updateDisplay);

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-purple-600', 'text-white', 'shadow-md', 'shadow-purple-100');
                    b.classList.add('bg-gray-50', 'text-gray-500');
                });
                this.classList.remove('bg-gray-50', 'text-gray-500');
                this.classList.add('bg-purple-600', 'text-white', 'shadow-md', 'shadow-purple-100');

                currentFilter = this.getAttribute('data-dept');
                updateDisplay();
            });
        });
    </script>
</body>
</html>