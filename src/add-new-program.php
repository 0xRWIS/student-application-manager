<?php include('./header.php'); ?>
<?php include('./database.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Program</title>
</head>
<body class="bg-slate-600 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        
        <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-slate-800">Add New Program</h2>
            <a href="dashboard-manager.php" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        <form action="process-add-program.php" method="POST" class="p-8 space-y-6">
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                <input type="text" name="department" required 
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-300"
                    placeholder="Enter department name">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-300 resize-none"
                    placeholder="Describe the program details..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deadline</label>
                <div class="relative">
                    <input type="date" name="deadline" required 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-gray-600">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Requirements</label>
                <div id="requirements-container" class="space-y-3">
                    <input type="text" name="requirements[]" 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-300"
                        placeholder="Enter requirement">
                </div>
                
                <button type="button" onclick="addRequirementField()" 
                    class="mt-3 flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg hover:bg-blue-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Add Requirement
                </button>
            </div>

            <div class="flex justify-end items-center gap-4 pt-6 border-t border-gray-50">
                <a href="dashboard-manager.php" 
                    class="px-6 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 active:scale-[0.98] transition-all text-sm">
                    Add Program
                </button>
            </div>
        </form>
    </div>

    <script>
        function addRequirementField() {
            const container = document.getElementById('requirements-container');
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'requirements[]';
            newInput.className = 'w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-gray-300';
            newInput.placeholder = 'Enter requirement';
            container.appendChild(newInput);
        }
    </script>

</body>
</html>