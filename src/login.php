<?php include('./header.php'); ?>

<?php
include('./database.php');

$error = "";

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'dashboard.php' : 'home.php'));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        try {
            // 1. Check for Admin first (Matches your Demo Credentials)
            if ($email === 'admin@university.edu' && $password === 'admin123') {
                $_SESSION['user_id'] = 0;
                $_SESSION['user_name'] = 'System Admin';
                $_SESSION['role'] = 'admin';
                header("Location: dashboard.php");
                exit();
            }

            // 2. Check Database for Student
            $stmt = $dbconnect->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set Session Variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['role'] = 'student';

                header("Location: home.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>




<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Application Management System</title>
</head>
<body class="bg-blue-50 font-sans text-gray-900 flex flex-col min-h-screen">
<main class="flex-grow flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        
        <div class="flex justify-center mb-6">
            <div class="bg-blue-600 rounded-full p-4 shadow-inner text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-extrabold text-center mb-1 text-gray-950">Welcome Back</h1>
        <p class="text-center text-sm text-gray-600 mb-8">Student Application Management System</p>

        <form action="#" method="POST" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" name="email" id="email" 
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 text-sm placeholder:text-gray-300"
                       placeholder="student@university.edu" required>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" id="password" 
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500 text-sm"
                       placeholder="••••••••" required>
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-3 rounded-xl transition duration-150 shadow-md hover:shadow-lg focus:ring-4 focus:ring-blue-300">
                    Sign In
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-gray-600">
            <p>Don't have an account? <a href="register.php" class="text-blue-600 font-medium hover:underline">Register here</a></p>
        </div>

        <div class="mt-10 bg-gray-50 border border-gray-100 p-5 rounded-xl space-y-2 text-xs text-gray-500">
            <p class="font-medium text-gray-700">Demo Credentials:</p>
            <p>Admin: admin@university.edu / admin123</p>
            <p>Student: any email / 6+ characters</p>
        </div>

    </div>
</main>

<div class="fixed bottom-3 right-3 p-1 bg-black text-white rounded-full text-lg cursor-help">?</div>
</body>
</html>