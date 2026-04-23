<?php
include('./database.php');
include('./header.php');

// 1. Get the user ID from the URL
$user_id = isset($_GET['id']) ? $_GET['id'] : null;
$user = null;

if ($user_id) {
    // Fetch current user data from the database
    $stmt = $dbconnect->prepare("SELECT * FROM users WHERE id = ? AND role = 'user'");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}

if (!$user) {
    header("Location: dashboard-student-manager.php");
    exit();
}

$depts = $dbconnect->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dept_id = $_POST['department_id']; 
    try {
        $updateStmt = $dbconnect->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, department_id = ? WHERE id = ?");
        
        if ($updateStmt->execute([$full_name, $email, $phone, $dept_id, $user_id])) {
            // Redirect with success status
            header("Location: dashboard-student-manager.php?status=updated");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Update failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - <?php echo htmlspecialchars($user['full_name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <h2 class="text-2xl font-bold text-slate-800">Edit Student Profile</h2>
            <p class="text-slate-500 text-sm mt-1">Update information and manage department assignment.</p>
        </div>

        <form method="POST" class="p-8 space-y-6">
            
            <?php if(isset($error)): ?>
                <div class="p-4 bg-red-50 text-red-600 rounded-xl text-sm font-medium border border-red-100">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-purple-50 focus:border-purple-400 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-purple-50 focus:border-purple-400 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-purple-50 focus:border-purple-400 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Assigned Department</label>
                    <div class="relative">
                        <select name="department_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-purple-50 focus:border-purple-400 outline-none transition-all appearance-none bg-white cursor-pointer">
                            
                            <option value="">-- Choose Department --</option>
                            
                            <?php foreach ($depts as $d): ?>
                                <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == $user['department_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d['name']); ?>
                                </option>
                            <?php endforeach; ?>
                            
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-50">
                <a href="dashboard-student-manager.php" 
                    class="px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-100 transition-all">
                    Cancel
                </a>
                
                <button type="submit" name="update_user" 
                        class="px-8 py-3 bg-purple-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-purple-100 hover:bg-purple-700 hover:-translate-y-0.5 transition-all">
                    Save Changes
                </button>
            </div>

        </form>
    </div>

</body>
</html>