<?php include('./header.php'); ?>
<?php include('./database.php'); ?>

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

        <a href="logout.php" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="text-sm font-medium">Logout</span>
        </a>
    </header>

    <nav class="px-10 bg-white border-b border-gray-200">
    <div class="flex gap-10">
        <?php 
            $current_page = basename($_SERVER['PHP_SELF']); 
        ?>

        <?php if ($current_page == 'dashboard.php'): ?>
            <div class="flex items-center gap-2 py-4 border-b-2 border-purple-600 text-purple-600 font-semibold text-sm cursor-default">
                Student Applications
            </div>
        <?php else: ?>
            <a href="dashboard.php" class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-400 font-medium text-sm hover:text-gray-600 transition-all">
                Student Applications
            </a>
        <?php endif; ?>

        <?php if ($current_page == 'dashboard-manager.php'): ?>
            <div class="flex items-center gap-2 py-4 border-b-2 border-purple-600 text-purple-600 font-semibold text-sm cursor-default">
                Manage Programs
            </div>
        <?php else: ?>
            <a href="dashboard-manager.php" class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-400 font-medium text-sm hover:text-gray-600 transition-all">
                Manage Programs
            </a>
        <?php endif; ?>
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
                <span class="text-4xl font-black text-gray-900">0</span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-orange-400 bg-orange-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Pending Review</span>
                </div>
                <span class="text-4xl font-black text-gray-900">0</span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-green-500 bg-green-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Approved</span>
                </div>
                <span class="text-4xl font-black text-gray-900">0</span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                <div class="flex flex-col gap-2">
                    <div class="text-red-500 bg-red-50 w-10 h-10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Rejected</span>
                </div>
                <span class="text-4xl font-black text-gray-900">0</span>
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
        // 1. Fetch department names from the database
        try {
            $stmtDept = $dbconnect->query("SELECT name FROM departments ORDER BY name ASC");
            $deptList = $stmtDept->fetchAll();

            if (!empty($deptList)):
                foreach ($deptList as $dept): ?>
                    <div class="bg-purple-50/40 p-6 rounded-xl border border-purple-100/50 hover:bg-purple-50 transition-colors">
                        <p class="text-3xl font-black text-purple-600">0</p>
                        <p class="text-sm text-gray-600 mt-2 font-semibold">
                            <?php echo htmlspecialchars($dept['name']); ?>
                        </p>
                    </div>
                <?php endforeach;
            else: ?>
                <p class="text-gray-400 italic text-sm">No departments created yet.</p>
            <?php endif;

        } catch (PDOException $e) {
            echo "<p class='text-red-500 text-xs'>Error: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</div>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Application Management</h2>
                
                <div class="flex flex-wrap gap-3">
                    <button class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-100">
                        All (0)
                    </button>
                    <button class="px-5 py-2.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        Pending (0)
                    </button>
                    <button class="px-5 py-2.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        Approved (0)
                    </button>
                    <button class="px-5 py-2.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                        Rejected (0)
                    </button>
                </div>
            </div>

            <div class="p-20 text-center flex flex-col items-center justify-center">
                <div class="bg-gray-50 p-6 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                </div>
                <p class="text-gray-400 font-medium italic">No application records found.</p>
            </div>
        </div>

    </main>

    <div class="fixed bottom-6 right-6">
        <button class="bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-900 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </button>
    </div>

</body>
</html>