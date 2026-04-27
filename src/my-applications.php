<?php 
include('./header.php'); 
include('./database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    // Removed d.deadline from the SELECT statement
    $stmt = $dbconnect->prepare("
        SELECT a.*, d.name as department_name, d.description as department_desc 
        FROM applications a 
        JOIN departments d ON a.department_id = d.id 
        WHERE a.user_id = ? 
        ORDER BY a.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $my_applications = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - My Applications</title>
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
                <span class="text-xs text-gray-500 font-medium"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
        </div>
        <a href="./logout.php" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <span class="text-sm">Logout</span>
        </a>
    </header>

    <nav class="px-10 bg-white border-b border-gray-200">
        <div class="flex gap-10">
            <a href="home.php" class="flex items-center gap-2 py-4 text-gray-500 font-medium text-sm hover:text-gray-700">Available Applications</a>
            <button class="flex items-center gap-2 py-4 border-b-2 border-blue-600 text-blue-600 font-semibold text-sm">My Applications</button>
        </div>
    </nav>

    <main class="px-10 py-12">
        <h2 class="text-2xl font-bold text-gray-900">My Applications</h2>
        <p class="text-gray-500 mt-1">Track the status of your submitted applications</p>

        <div class="mt-10 space-y-4">
            <?php if (empty($my_applications)): ?>
                <div class="flex flex-col items-center justify-center border border-gray-100 rounded-xl bg-white p-20 text-center shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Applications Yet</h3>
                    <p class="text-gray-500 max-w-md">You haven't submitted any applications yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($my_applications as $app): ?>
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex-grow">
            <div class="flex items-center gap-3 mb-2">
                <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($app['department_name']); ?></h3>
                
                <?php 
                    $status = strtolower($app['status']);
                    $statusClasses = [
                        'pending'  => 'bg-amber-50 text-amber-600 border-amber-100',
                        'approved' => 'bg-green-50 text-green-600 border-green-100',
                        'rejected' => 'bg-red-50 text-red-600 border-red-100'
                    ];
                    $currentClass = $statusClasses[$status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                ?>
                <span class="px-3 py-1 text-[11px] font-bold rounded-full border uppercase tracking-wider <?php echo $currentClass; ?>">
                    <?php echo $status; ?>
                </span>
            </div>
            
            <div class="flex flex-wrap gap-6 text-sm">
                <div class="flex items-center gap-2 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Submitted: <?php echo date('M d, Y', strtotime($app['created_at'])); ?>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="view-applications.php?id=<?php echo $app['id']; ?>" 
                class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all text-center">
                    View Details
                </a>
        </div>
    </div>
<?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>