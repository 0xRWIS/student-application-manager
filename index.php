<?php
include('./src/database.php');


$query = "SELECT 
            d.id, 
            d.name, 
            d.description, 
            COUNT(a.id) as student_count 
          FROM departments d
          LEFT JOIN applications a ON d.id = a.department_id AND a.status = 'approved'
          GROUP BY d.id";

try {
    $departments = $dbconnect->query($query)->fetchAll();
    
    $totalStudents = $dbconnect->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    $totalDepts = count($departments);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assiut University | Excellence in Education</title>
    <link rel="icon" type="image/svg+xml" href="./src/assets/images/aun-logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    </style>
</head>
<body class="bg-white text-gray-900">

    <nav class="flex items-center justify-between px-12 py-6 bg-white">
        <div class="flex items-center gap-2">
            <div class="bg-indigo-600 p-2 rounded-lg text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
            </div>
            <span class="text-xl font-bold text-indigo-900 uppercase tracking-tight">Assiut University</span>
        </div>
        <div class="hidden md:flex gap-8 text-sm font-medium text-gray-500">
            <a href="#" class="hover:text-indigo-600">Home</a>
            <a href="#departments" class="hover:text-indigo-600">Departments</a>
            <a href="https://b.aun.edu.eg/main/explore" class="hover:text-indigo-600">About</a>
            <a href="#contact" class="hover:text-indigo-600">Contact</a>
        </div>
        <div class="flex gap-4 items-center">
            <a href="./src/login.php" class="text-sm font-semibold text-gray-700">Login</a>
            <a href="./src/register.php" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">Register</a>
        </div>
    </nav>

    <header class="relative px-12 py-20 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="md:w-1/2 space-y-6">
            <h1 class="text-6xl font-extrabold text-slate-900 leading-tight">
                Your Future Starts <br> <span class="white-text bg-clip-text hero-gradient">At Assiut</span>
            </h1>
            <p class="text-gray-500 text-lg max-w-lg leading-relaxed">
                Join one of Egypt's most prestigious universities. Choose from our world-class departments and embark on a journey of academic excellence and personal growth.
            </p>
            <div class="flex gap-4">
                <a href="./src/home.php" class="bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold shadow-xl shadow-indigo-100 hover:-translate-y-1 transition-all">Apply Now →</a>
                <a href="#departments" class="bg-white border border-gray-100 text-gray-600 px-8 py-4 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition-all">Explore Programs</a>
            </div>
        </div>
        <div class="md:w-1/2 relative">
            <div class="absolute -inset-4 bg-purple-100 rounded-3xl blur-2xl opacity-30 animate-pulse"></div>
            <img src="./src/assets/images/banner3.jpeg" alt="University Campus" class="relative rounded-3xl shadow-2xl z-10">
        </div>
    </header>

    <div class="flex justify-center gap-20 py-12">
        <div class="text-center">
            <span class="block text-3xl font-bold text-indigo-600"><?php echo number_format($totalStudents); ?>+</span>
            <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Students Enrolled</span>
        </div>
        <div class="text-center">
            <span class="block text-3xl font-bold text-indigo-600"><?php echo $totalDepts; ?>+</span>
            <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Academic Programs</span>
        </div>
    </div>

    <section id="departments" class="px-12 py-24 bg-slate-50/50">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-4">Available Departments</h2>
            <p class="text-gray-400 max-w-xl mx-auto italic">Explore our diverse range of departments and find the perfect path for your future</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($departments as $dept): ?>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($dept['name']); ?></h3>
                    <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                        <?php echo number_format($dept['student_count']); ?>+ Students
                    </span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-8">
                    <?php echo htmlspecialchars($dept['description'] ?? 'Shape the future and drive digital transformation with cutting-edge knowledge.'); ?>
                </p>
                <a href="./src/home.php" class="block w-full text-center py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-100 group-hover:bg-indigo-700 transition-colors">Apply Now →</a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="px-12 py-20">
        <div class="hero-gradient rounded-[40px] p-16 text-center text-white shadow-2xl shadow-purple-200">
            <h2 class="text-4xl font-black mb-6">Ready to Start Your Journey?</h2>
            <p class="text-purple-100 mb-10 max-w-2xl mx-auto">Join thousands of students who chose Assiut University to achieve their dreams. Your future awaits!</p>
            <div class="flex justify-center gap-4">
                <a href="./src/register.php" class="bg-white text-indigo-600 px-10 py-4 rounded-xl font-bold shadow-lg hover:scale-105 transition-transform">Register Today</a>
                <a href="#" class="border-2 border-white/30 text-white px-10 py-4 rounded-xl font-bold hover:bg-white/10 transition-all">Learn More</a>
            </div>
        </div>
    </div>

    <footer id="contact" class="bg-slate-900 text-gray-400 px-12 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-gray-800 pb-12 mb-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-white">
                    <div class="bg-indigo-500 p-1.5 rounded-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <span class="font-bold">Assiut University</span>
                </div>
                <p class="text-sm">Excellence in Education Since 1957</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Quick Links</h4>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="hover:text-white">About Us</a></li>
                    <li><a href="#" class="hover:text-white">Admissions</a></li>
                    <li><a href="#" class="hover:text-white">Research</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Departments</h4>
                <ul class="text-sm space-y-2">
                    <?php foreach(array_slice($departments, 0, 4) as $d): ?>
                        <li><a href="#" class="hover:text-white"><?php echo $d['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4">Contact</h4>
                <ul class="text-sm space-y-2">
                    <li>Assiut, Egypt</li>
                    <li>+20 88 2411 233</li>
                    <li>info@aun.edu.eg</li>
                </ul>
            </div>
        </div>
        <p class="text-center text-xs">© 2026 Assiut University. All rights reserved.</p>
    </footer>

</body>
</html>