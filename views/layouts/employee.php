<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Employee Portal' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6', // Blue-500
                        secondary: '#10b981', // Emerald-500
                        dark: '#1e293b',
                        darker: '#0f172a',
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-darker dark:text-gray-200 transition-colors duration-200 font-sans" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')">

    <!-- Global Loader -->
    <div id="global-loader" class="fixed inset-0 z-[100] bg-white dark:bg-darker flex items-center justify-center transition-opacity duration-500">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary"></div>
    </div>

    <!-- Topbar -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-dark dark:border-gray-700 h-16 transition-colors duration-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <!-- Sidebar Toggle (hidden on mobile, only desktop if needed, or remove completely) -->
                    <button @click="sidebarOpen = !sidebarOpen" type="button" class="hidden sm:inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="/payrollsystem/employee" class="flex ms-2 md:me-24 items-center">
                        <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap dark:text-white text-primary">
                            <i class="fa-solid fa-briefcase mr-2"></i> Employee Portal
                        </span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-2.5 transition-colors">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <div class="relative" x-data="{ notifOpen: false, unreadCount: 0, notifications: [] }" x-init="
                        fetch('/payrollsystem/notification/api?action=get')
                            .then(res => res.json())
                            .then(data => {
                                if(data.unread_count !== undefined) {
                                    unreadCount = data.unread_count;
                                    notifications = data.notifications;
                                }
                            });
                        setInterval(() => {
                            fetch('/payrollsystem/notification/api?action=get')
                                .then(res => res.json())
                                .then(data => {
                                    if(data.unread_count !== undefined) {
                                        unreadCount = data.unread_count;
                                        notifications = data.notifications;
                                    }
                                });
                        }, 30000);
                    ">
                        <button @click="notifOpen = !notifOpen" class="relative text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-2.5 transition-colors">
                            <i class="fa-solid fa-bell"></i>
                            <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-show="unreadCount > 0" x-text="unreadCount"></span>
                        </button>
                        <!-- Dropdown -->
                        <div x-show="notifOpen" @click.away="notifOpen = false" class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-20 dark:bg-gray-800 border dark:border-gray-700" x-cloak>
                            <div class="py-2">
                                <div class="px-4 py-2 border-b dark:border-gray-700 font-bold flex justify-between items-center">
                                    <span>Notifications</span>
                                    <button x-show="unreadCount > 0" @click="fetch('/payrollsystem/notification/api?action=read_all', {method: 'POST'}).then(() => { unreadCount = 0; notifications = []; })" class="text-xs text-primary hover:underline">Mark all read</button>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">No new notifications</div>
                                    </template>
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <a :href="'/payrollsystem' + notif.link" @click="fetch('/payrollsystem/notification/api?action=read', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id: notif.id})})" class="block px-4 py-3 border-b hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700 transition-colors">
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="notif.message"></p>
                                            <span class="text-xs text-primary" x-text="new Date(notif.created_at).toLocaleString()"></span>
                                        </a>
                                    </template>
                                </div>
                                <a href="/payrollsystem/notification" class="block text-center px-4 py-2 text-sm text-primary font-semibold hover:bg-gray-50 dark:hover:bg-gray-700">View All History</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold"><?= htmlspecialchars(strtoupper(substr($_SESSION['first_name'] ?? 'U', 0, 1))) ?></div>
                        </button>
                        <!-- Dropdown -->
                        <div x-show="userOpen" @click.away="userOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 dark:bg-gray-800 border dark:border-gray-700" x-cloak>
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-900 dark:text-white"><?= htmlspecialchars(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? 'User')) ?></p>
                                <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>
                            </div>
                            <ul class="py-1">
                                <li>
                                    <a href="/payrollsystem/employee/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">My Profile</a>
                                </li>
                                <li>
                                    <a href="/payrollsystem/auth/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-red-400">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Desktop Sidebar (Hidden on Mobile) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 hidden sm:block sm:translate-x-0 dark:bg-dark dark:border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-dark">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="/payrollsystem/employee" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-home w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary dark:group-hover:text-primary"></i>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/payrollsystem/employee/attendance" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-clock w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary dark:group-hover:text-primary"></i>
                        <span class="ms-3">My Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="/payrollsystem/employee/leaves" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-calendar-alt w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary dark:group-hover:text-primary"></i>
                        <span class="ms-3">Leave Requests</span>
                    </a>
                </li>
                <li>
                    <a href="/payrollsystem/employee/overtime" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-stopwatch w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-primary dark:group-hover:text-primary"></i>
                        <span class="ms-3">Overtime Requests</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Mobile Bottom Navigation -->
    <nav class="sm:hidden fixed bottom-0 w-full bg-white dark:bg-dark border-t border-gray-200 dark:border-gray-700 z-50 flex justify-between items-center px-6 py-2 pb-safe shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <a href="/payrollsystem/employee" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary group">
            <i class="fa-solid fa-home text-lg mb-1 group-hover:-translate-y-1 transition-transform"></i>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="/payrollsystem/employee/attendance" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary group">
            <i class="fa-solid fa-clock text-lg mb-1 group-hover:-translate-y-1 transition-transform"></i>
            <span class="text-[10px] font-medium">History</span>
        </a>
        <!-- Floating Action Button for Check In/Out or Add -->
        <a href="/payrollsystem/employee" class="relative -top-5 flex items-center justify-center w-14 h-14 bg-primary text-white rounded-full shadow-lg shadow-blue-500/50 hover:bg-blue-600 hover:scale-105 transition-all">
            <i class="fa-solid fa-fingerprint text-2xl"></i>
        </a>
        <a href="/payrollsystem/employee/leaves" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary group">
            <i class="fa-solid fa-calendar-alt text-lg mb-1 group-hover:-translate-y-1 transition-transform"></i>
            <span class="text-[10px] font-medium">Leaves</span>
        </a>
        <a href="/payrollsystem/employee/profile" class="flex flex-col items-center p-2 text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary group">
            <i class="fa-solid fa-user text-lg mb-1 group-hover:-translate-y-1 transition-transform"></i>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </nav>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64 mt-14 mb-20 sm:mb-4 min-h-screen">
        <?php if(isset($data['content'])) {
            require_once __DIR__ . '/../' . $data['content'] . '.php';
        } ?>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, duration: 800 });
        window.addEventListener('load', () => {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            }
        });
    </script>
</body>
</html>
