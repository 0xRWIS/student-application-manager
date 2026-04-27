<?php 
include('./header.php'); 
include('./database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $stmt = $dbconnect->query("SELECT * FROM departments ORDER BY id DESC");
    $programs = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">

    <header class="flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100">
    <div class="flex items-center gap-4">
        <div class="bg-blue-600 p-2.5 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-xl font-bold text-gray-900 tracking-tight">Student Portal</span>
            <span class="text-xs text-gray-500 font-medium">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        </div>
    </div>

            <div class="flex items-center gap-6">
                <?php 
                if (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'admin'): 
                ?>
                    <a href="dashboard.php" class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-xl hover:bg-purple-700 transition-all shadow-lg shadow-purple-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Admin Dashboard
                    </a>
                <?php endif; ?>

                <a href="./logout.php" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="text-sm font-medium">Logout</span>
                </a>
            </div>
    </div>
    </header>

    <nav class="px-10 bg-white border-b border-gray-200">
        <div class="flex gap-10">
            <button class="flex items-center gap-2 py-4 border-b-2 border-blue-600 text-blue-600 font-semibold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Available Applications
            </button>
            <a href="my-applications.php" class="flex items-center gap-2 py-4 text-gray-500 font-medium text-sm hover:text-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                My Applications
            </a>
        </div>
    </nav>

    <main class="px-10 py-12">
        <h2 class="text-2xl font-bold text-gray-900">Available Applications</h2>
        <p class="text-gray-500 mt-1 mb-10">Browse and apply to available programs</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($programs as $program): ?>
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full relative overflow-hidden">
        
        <div class="flex justify-between items-start mb-6">
            <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            
            <?php if ($program['status'] === 'open'): ?>
                <span class="px-3 py-1 bg-green-50 text-green-600 text-[11px] font-bold rounded-full border border-green-100 uppercase tracking-wider">
                    Open
                </span>
            <?php else: ?>
                <span class="px-3 py-1 bg-red-50 text-red-600 text-[11px] font-bold rounded-full border border-red-100 uppercase tracking-wider">
                    Closed
                </span>
            <?php endif; ?>
        </div>

        <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($program['name']); ?></h3>
        <p class="text-sm text-gray-500 mb-5 leading-relaxed flex-grow">
            <?php echo htmlspecialchars($program['description']); ?>
        </p>

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 font-medium">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            Deadline: <?php echo date('n/j/Y', strtotime($program['deadline'])); ?>
        </div>

        <div class="mb-8">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Requirements:</p>
            <ul class="space-y-2">
                <?php
                $stmtReq = $dbconnect->prepare("SELECT requirement FROM department_requirements WHERE department_id = ? LIMIT 3");
                $stmtReq->execute([$program['id']]);
                $reqs = $stmtReq->fetchAll();
                
                foreach ($reqs as $r): ?>
                    <li class="text-sm text-gray-600 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                        <?php echo htmlspecialchars($r['requirement']); ?>
                    </li>
                <?php endforeach; ?>
                
                <?php if (count($reqs) >= 3): ?>
                    <li class="text-xs text-gray-400 font-medium mt-1">+ more</li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if ($program['status'] === 'open'): ?>
            <a href="application.php?id=<?php echo $program['id']; ?>" 
            class="w-full py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all text-center shadow-lg shadow-blue-100 active:scale-[0.98]">
                Apply Now
            </a>
        <?php else: ?>
            <button disabled 
            class="w-full py-3.5 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed text-center border border-gray-200 shadow-none">
                Closed
            </button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
        </div>
    </main>

</body>
</html>