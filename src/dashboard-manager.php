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
    
    $stmt = $dbconnect->query("SELECT * FROM departments ORDER BY id DESC");
    $programs = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching programs: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Programs</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        
            <?php if ($current_page == 'dashboard-student-manager.php'): ?>
            <div class="flex items-center gap-2 py-4 border-b-2 border-purple-600 text-purple-600 font-semibold text-sm cursor-default">
                Students Manager
            </div>
        <?php else: ?>
            <a href="dashboard-student-manager.php" class="flex items-center gap-2 py-4 border-b-2 border-transparent text-gray-400 font-medium text-sm hover:text-gray-600 transition-all">
                Students Manager
            </a>
        <?php endif; ?>
    </div>
</nav>

    <main class="px-10 py-8">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-xl font-bold text-gray-900 mb-2">Program Availability Control</h2>
                <p class="text-sm text-gray-500">Open or close programs for student applications. Closed programs will still be visible but students cannot apply.</p>
            </div>

            <div class="p-8 space-y-6">
                
                <?php if (empty($programs)): ?>
                    <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-xl">
                        <p class="text-gray-400">No programs available. Add your first one below.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($programs as $program): ?>
                        <div class="flex items-center justify-between border border-gray-100 rounded-xl p-6 hover:bg-slate-50/30 transition-colors">
                            <div class="space-y-4 w-2/3">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($program['name']); ?></h3>
                                        
                                        <?php if ($program['status'] === 'open'): ?>
                                            <span class="px-2.5 py-0.5 bg-green-50 text-green-600 text-[10px] font-bold rounded-full flex items-center gap-1 uppercase tracking-wide">
                                                <span class="w-1 h-1 bg-green-600 rounded-full"></span> Open
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-full flex items-center gap-1 uppercase tracking-wide">
                                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span> Closed
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm text-gray-500 line-clamp-1"><?php echo htmlspecialchars($program['description']); ?></p>
                                </div>

                                <div class="flex gap-6 text-sm text-gray-400">
                                    <p><span class="font-medium">Department:</span> <?php echo htmlspecialchars($program['name']); ?></p>
                                    <p><span class="font-medium">Deadline:</span> <?php echo date('M d, Y', strtotime($program['deadline'])); ?></p>
                                </div>

                                <?php
                                $stmtReq = $dbconnect->prepare("SELECT requirement FROM department_requirements WHERE department_id = ?");
                                $stmtReq->execute([$program['id']]);
                                $requirements = $stmtReq->fetchAll();
                                ?>
                                
                                <?php if (!empty($requirements)): ?>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($requirements as $req): ?>
                                            <span class="px-2 py-1 bg-slate-50 text-slate-500 rounded text-[10px] border border-slate-100">
                                                • <?php echo htmlspecialchars($req['requirement']); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex gap-3">
                                <?php if ($program['status'] === 'open'): ?>
                                    <a href="toggle-status.php?id=<?php echo $program['id']; ?>&current_status=open" 
                                    class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Close Program
                                    </a>
                                <?php else: ?>
                                    <a href="toggle-status.php?id=<?php echo $program['id']; ?>&current_status=close" 
                                    class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-600 text-sm font-semibold rounded-lg hover:bg-green-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Open Program
                                    </a>
                                <?php endif; ?>

                                <a href="delete-program.php?id=<?php echo $program['id']; ?>" 
                                    onclick="return confirm('Are you sure you want to delete this program and all its requirements?');"
                                    class="flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-500 text-sm font-semibold rounded-lg hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            
                <div class="pt-4">
                    <a href="add-new-program.php" class="inline-flex items-center gap-4 px-7 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Add New Program
                    </a>
                </div>
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