<?php include('./header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - My Applications</title>
</head>
<body class="bg-white min-h-screen">

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
                <span class="text-xs text-gray-500 font-medium">test</span>
            </div>
        </div>

        <a href="#" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="text-sm">Logout</span>
        </a>
    </header>

    <nav class="px-10 border-b border-gray-200">
        <div class="flex gap-10">
            <a href="home.php" class="flex items-center gap-2 py-4 text-gray-500 font-medium text-sm hover:text-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Available Applications
            </a>
            
            <button class="flex items-center gap-2 py-4 border-b-2 border-blue-600 text-blue-600 font-semibold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                My Applications
            </button>
        </div>
    </nav>

    <main class="px-10 py-12">
        <h2 class="text-2xl font-bold text-gray-900">My Applications</h2>
        <p class="text-gray-500 mt-1">Track the status of your submitted applications</p>

        <div class="mt-10 flex flex-col items-center justify-center border border-gray-100 rounded-xl bg-white p-20 text-center shadow-sm">
            <div class="mb-5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Applications Yet</h3>
            <p class="text-gray-500 max-w-md leading-relaxed">
                You haven't submitted any applications yet. Check out the available applications to get started.
            </p>
        </div>
    </main>

</body>
</html>