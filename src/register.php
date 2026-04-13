
<?php 
// 1. DATABASE & LOGIC FIRST
include('./database.php'); 

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'dashboard.php' : 'home.php'));
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = $_POST['password'];

    if (!empty($full_name) && !empty($email) && !empty($password)) {
        try {
            // UPDATED: Changed $pdo to $dbconnect to match your database.php
            $checkEmail = $dbconnect->prepare("SELECT email FROM users WHERE email = ?");
            $checkEmail->execute([$email]);

            if ($checkEmail->rowCount() > 0) {
                $message = "Error: This email is already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // UPDATED: Changed $pdo to $dbconnect here as well
                $sql = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)";
                $stmt = $dbconnect->prepare($sql);
                
                if ($stmt->execute([$full_name, $email, $phone, $hashed_password])) {
                    header("Location: login.php?signup=success");
                    exit();
                }
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 font-sans text-gray-900 flex flex-col min-h-screen">
    
    <?php include('./header.php'); ?>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            
            <div class="flex justify-center mb-6">
                <div class="bg-green-600 rounded-full p-4 shadow-inner text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5a3 3 0 11-6 0 3 3 0 016 0zM12 12a3 3 0 00-3-3H9.75M12 12v3.75m0 0h3.75M12 15.75H8.25m3.75-10.5h1.5M4.5 19.5v-1.5a1.5 1.5 0 011.5-1.5h12a1.5 1.5 0 011.5 1.5v1.5m-15 0a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5m-15 0H2.25M6 15.75H4.5" />
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-extrabold text-center mb-1 text-gray-950">Create Account</h1>
            <p class="text-center text-sm text-gray-600 mb-6">Join our student application system</p>

            <?php if (!empty($message)): ?>
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-bold rounded">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" id="name" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-300 focus:border-green-500 text-sm"
                           placeholder="John Doe" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-300 focus:border-green-500 text-sm"
                           placeholder="student@university.edu" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 text-sm font-medium border-r border-gray-200 pr-3">
                            +20
                        </span>
                        <input type="tel" name="phone" required 
                            class="w-full pl-16 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"
                            placeholder="1012345678">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" id="password" 
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-300 focus:border-green-500 text-sm"
                           placeholder="••••••••" required>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full flex justify-center bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-3 rounded-xl transition duration-150 shadow-md">
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center text-sm text-gray-600">
                <p>Already have an account? <a href="login.php" class="text-green-600 font-medium hover:underline">Sign in here</a></p>
            </div>
        </div>
    </main>

    <div class="fixed bottom-3 right-3 p-1 bg-black text-white rounded-full text-lg cursor-help">?</div>
</body>
</html>