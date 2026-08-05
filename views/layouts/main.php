<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'HRMS Admin Dashboard' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5', // Indigo-600
                        secondary: '#10b981', // Emerald-500
                        dark: '#1e293b', // Slate-800
                        darker: '#0f172a', // Slate-900
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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Custom CSS -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Glassmorphism */
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .glass-header {
            background: rgba(30, 41, 59, 0.85);
        }
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
        .animate-\\[shrink_5s_linear_forwards\\] {
            animation: shrink 5s linear forwards;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-darker dark:text-gray-200 transition-colors duration-200 font-sans" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')">

    <!-- Global Loader -->
    <div id="global-loader" class="fixed inset-0 z-[100] bg-white dark:bg-darker flex items-center justify-center transition-opacity duration-500">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary"></div>
    </div>

    <!-- Topbar -->
    <nav class="fixed top-0 z-50 w-full glass-header border-b border-gray-200 dark:border-gray-700 h-16 transition-colors duration-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button @click="sidebarOpen = !sidebarOpen" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="/payrollsystem/admin" class="flex ms-2 md:me-24 items-center">
                        <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap dark:text-white text-primary flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-blue-500 rounded-lg flex items-center justify-center mr-2 shadow-lg shadow-primary/30">
                                <i class="fa-solid fa-building-user text-white text-sm"></i>
                            </div>
                            HRMS Admin
                        </span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-2 w-10 h-10 flex items-center justify-center transition-colors">
                        <i class="fa-solid text-lg" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon'"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <div class="relative" x-data="{ 
                            notifOpen: false, 
                            unreadCount: 0, 
                            notifications: [],
                            toastNotif: null,
                            toastTimeout: null,
                            playChime() {
                                try {
                                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                                    const ctx = new AudioContext();
                                    const osc = ctx.createOscillator();
                                    const gain = ctx.createGain();
                                    osc.connect(gain);
                                    gain.connect(ctx.destination);
                                    osc.type = 'sine';
                                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                                    osc.frequency.exponentialRampToValueAtTime(440, ctx.currentTime + 0.1);
                                    gain.gain.setValueAtTime(0.5, ctx.currentTime);
                                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                                    osc.start(ctx.currentTime);
                                    osc.stop(ctx.currentTime + 0.3);
                                } catch(e) {}
                            },
                            showToast(notif) {
                                this.toastNotif = notif;
                                if(this.toastTimeout) clearTimeout(this.toastTimeout);
                                this.toastTimeout = setTimeout(() => { this.toastNotif = null; }, 5000);
                            }
                         }" 
                         x-init="
                            const fetchNotifs = () => fetch('/payrollsystem/notification/api?action=get').then(res => res.json()).then(data => {
                                if(data.unread_count !== undefined) {
                                    if(data.unread_count > unreadCount && data.notifications.length > 0) {
                                        playChime();
                                        // Find the newest notification to show as a toast
                                        const newNotif = data.notifications[0];
                                        showToast(newNotif);
                                    }
                                    unreadCount = data.unread_count;
                                    notifications = data.notifications;
                                }
                            });
                            fetchNotifs();
                            setInterval(fetchNotifs, 10000); // Polling every 10s for better responsiveness
                            window.addEventListener('notifications-read', fetchNotifs);
                         ">
                        <button @click="notifOpen = !notifOpen" class="relative text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-2 w-10 h-10 flex items-center justify-center transition-colors group">
                            <i class="fa-solid fa-bell text-lg group-hover:animate-swing"></i>
                            <span class="absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ring-2 ring-white dark:ring-gray-800" x-show="unreadCount > 0" x-text="unreadCount"></span>
                        </button>
                        
                        <!-- Toast Popup (Bottom Right) -->
                        <template x-teleport="body">
                            <div x-show="toastNotif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10" class="fixed bottom-5 right-5 z-[100] w-80 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden cursor-pointer hover:shadow-primary/20 transition-all" @click="window.location.href='/payrollsystem/notification'; toastNotif=null" x-cloak>
                                <div class="p-4 flex gap-3 relative">
                                    <button @click.stop="toastNotif = null" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"><i class="fa-solid fa-xmark"></i></button>
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg bg-primary shadow-lg shadow-primary/30">
                                            <i class="fa-solid fa-bell animate-swing"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white pr-4" x-text="toastNotif?.title || 'New Notification'"></h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mt-1 leading-snug" x-text="toastNotif?.message"></p>
                                    </div>
                                </div>
                                <div class="h-1 bg-gray-100 dark:bg-gray-700 w-full">
                                    <div class="h-full bg-primary animate-[shrink_5s_linear_forwards]"></div>
                                </div>
                            </div>
                        </template>

                        <!-- Dropdown -->
                        <div x-show="notifOpen" @click.away="notifOpen = false" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl overflow-hidden z-20 dark:bg-gray-800 border dark:border-gray-700 transform origin-top-right transition-all" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
                            <div class="py-2">
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                                    <span class="font-bold text-gray-900 dark:text-white">Notifications</span>
                                    <button x-show="unreadCount > 0" @click="fetch('/payrollsystem/notification/api?action=read_all', {method: 'POST'}).then(() => { unreadCount = 0; notifications.forEach(n => n.is_read = 1); })" class="text-xs text-primary hover:text-blue-600 font-medium transition-colors">Mark all read</button>
                                </div>
                                <div class="max-h-72 overflow-y-auto">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 flex flex-col items-center">
                                            <i class="fa-regular fa-bell-slash text-3xl mb-2 opacity-50"></i>
                                            <span class="text-sm">No recent notifications</span>
                                        </div>
                                    </template>
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <a :href="'/payrollsystem/notification'" class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 dark:hover:bg-gray-700/50 dark:border-gray-700/50 transition-colors relative" :class="notif.is_read == 0 ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : ''">
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs shadow-sm"
                                                        :class="{
                                                            'bg-blue-500': notif.type === 'attendance',
                                                            'bg-emerald-500': notif.type === 'leave',
                                                            'bg-orange-500': notif.type === 'overtime',
                                                            'bg-cyan-500': notif.type === 'payroll',
                                                            'bg-red-500': notif.type === 'error',
                                                            'bg-gray-500': !['attendance','leave','overtime','payroll','error'].includes(notif.type)
                                                        }">
                                                        <i class="fa-solid" :class="{
                                                            'fa-clock-rotate-left': notif.type === 'attendance',
                                                            'fa-plane': notif.type === 'leave',
                                                            'fa-bolt': notif.type === 'overtime',
                                                            'fa-money-check-dollar': notif.type === 'payroll',
                                                            'fa-triangle-exclamation': notif.type === 'error',
                                                            'fa-bell': !['attendance','leave','overtime','payroll','error'].includes(notif.type)
                                                        }"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold truncate pr-4 text-gray-900 dark:text-white" x-text="notif.title || 'System Notification'"></p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-1 mt-0.5" x-text="notif.message"></p>
                                                    <p class="text-[10px] text-primary font-medium mt-1" x-text="new Date(notif.created_at).toLocaleString([], {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'})"></p>
                                                </div>
                                                <div class="absolute right-4 top-4" x-show="notif.is_read == 0">
                                                    <div class="w-2 h-2 bg-primary rounded-full shadow-[0_0_8px_rgba(79,70,229,0.5)]"></div>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                                <a href="/payrollsystem/notification" class="block text-center px-4 py-3 text-sm text-primary font-bold hover:bg-gray-50 dark:hover:bg-gray-700/50 bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 transition-colors">
                                    View All History <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold"><?= htmlspecialchars(strtoupper(substr($_SESSION['email'] ?? 'A', 0, 1))) ?></div>
                        </button>
                        <!-- Dropdown -->
                        <div x-show="userOpen" @click.away="userOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 dark:bg-gray-800 border dark:border-gray-700" x-cloak>
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-900 dark:text-white">System Admin</p>
                                <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>
                            </div>
                            <ul class="py-1">
                                <li>
                                    <a href="/payrollsystem/admin/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
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

    <!-- Sidebar -->
    <?php
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $isActive = function($path) use ($currentPath) {
        return strpos($currentPath, $path) !== false;
    };
    ?>
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform duration-300 bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-darker dark:border-gray-800" aria-label="Sidebar">
        <div class="h-full px-4 pb-4 overflow-y-auto">
            <ul class="space-y-2 font-medium text-sm">
                
                <!-- DASHBOARD -->
                <li>
                    <a href="/payrollsystem/admin" class="flex items-center p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $currentPath == '/payrollsystem/admin' ? 'bg-primary/10 text-primary border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-chart-pie w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $currentPath == '/payrollsystem/admin' ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold relative z-10">Dashboard</span>
                    </a>
                </li>

                <!-- EMPLOYEE MANAGEMENT -->
                <li x-data="{ open: <?= $isActive('/employees') || $isActive('/departments') || $isActive('/positions') ? 'true' : 'false' ?> }">
                    <button @click="open = !open" class="flex items-center w-full p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/employees') || $isActive('/departments') || $isActive('/positions') ? 'text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-users w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/employees') || $isActive('/departments') || $isActive('/positions') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold text-left whitespace-nowrap flex-1">Employee Mgmt</span>
                        <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : 'text-gray-400'"></i>
                    </button>
                    <ul x-show="open" x-collapse.duration.300ms class="py-2 space-y-1">
                        <li><a href="/payrollsystem/admin/employees" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/employees') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Employees</a></li>
                        <li><a href="/payrollsystem/admin/departments" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/departments') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Departments</a></li>
                        <li><a href="/payrollsystem/admin/positions" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/positions') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Positions</a></li>
                    </ul>
                </li>

                <!-- ATTENDANCE MANAGEMENT -->
                <li x-data="{ open: <?= $isActive('/attendance') ? 'true' : 'false' ?> }">
                    <button @click="open = !open" class="flex items-center w-full p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/attendance') ? 'text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-clock-rotate-left w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/attendance') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold text-left whitespace-nowrap flex-1">Attendance Mgmt</span>
                        <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : 'text-gray-400'"></i>
                    </button>
                    <ul x-show="open" x-collapse.duration.300ms class="py-2 space-y-1">
                        <li><a href="/payrollsystem/admin/attendance?view=daily" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/attendance') && (isset($_GET['view']) && $_GET['view'] == 'daily' || !isset($_GET['view']) && !isset($_GET['tab'])) ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Daily Attendance</a></li>
                        <li><a href="/payrollsystem/admin/attendance?view=weekly" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= isset($_GET['view']) && $_GET['view'] == 'weekly' ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Weekly Attendance</a></li>
                        <li><a href="/payrollsystem/admin/attendance?view=monthly" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= isset($_GET['view']) && $_GET['view'] == 'monthly' ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Monthly Attendance</a></li>
                        <li><a href="/payrollsystem/admin/attendance?tab=corrections" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= isset($_GET['tab']) && $_GET['tab'] == 'corrections' ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Corrections</a></li>
                    </ul>
                </li>

                <!-- LEAVE MANAGEMENT -->
                <li x-data="{ open: <?= $isActive('/leave') ? 'true' : 'false' ?> }">
                    <button @click="open = !open" class="flex items-center w-full p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/leave') ? 'text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-calendar-minus w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/leave') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold text-left whitespace-nowrap flex-1">Leave Mgmt</span>
                        <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : 'text-gray-400'"></i>
                    </button>
                    <ul x-show="open" x-collapse.duration.300ms class="py-2 space-y-1">
                        <li><a href="/payrollsystem/admin/leaves" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/leaves') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Leave Requests</a></li>
                        <li><a href="/payrollsystem/admin/leave_types" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/leave_types') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Leave Types</a></li>
                    </ul>
                </li>

                <!-- OVERTIME MANAGEMENT -->
                <li x-data="{ open: <?= $isActive('/overtime') ? 'true' : 'false' ?> }">
                    <button @click="open = !open" class="flex items-center w-full p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/overtime') ? 'text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-business-time w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/overtime') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold text-left whitespace-nowrap flex-1">Overtime Mgmt</span>
                        <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : 'text-gray-400'"></i>
                    </button>
                    <ul x-show="open" x-collapse.duration.300ms class="py-2 space-y-1">
                        <li><a href="/payrollsystem/admin/overtime" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/overtime') && !$isActive('/overtime_assignments') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">OT Requests</a></li>
                        <li><a href="/payrollsystem/admin/overtime_assignments" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/overtime_assignments') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">OT Assignments</a></li>
                    </ul>
                </li>

                <!-- PAYROLL MANAGEMENT -->
                <li x-data="{ open: <?= $isActive('/payroll') || $isActive('/bonus') || $isActive('/deduction') ? 'true' : 'false' ?> }">
                    <button @click="open = !open" class="flex items-center w-full p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/payroll') || $isActive('/bonus') || $isActive('/deduction') ? 'text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-file-invoice-dollar w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/payroll') || $isActive('/bonus') || $isActive('/deduction') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold text-left whitespace-nowrap flex-1">Payroll Mgmt</span>
                        <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : 'text-gray-400'"></i>
                    </button>
                    <ul x-show="open" x-collapse.duration.300ms class="py-2 space-y-1">
                        <li><a href="/payrollsystem/admin/payroll" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/payroll') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Payroll List</a></li>
                        <li><a href="/payrollsystem/admin/bonuses" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/bonuses') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Bonuses</a></li>
                        <li><a href="/payrollsystem/admin/deductions" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/deductions') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Deductions</a></li>
                    </ul>
                </li>
                
                <!-- REPORTS -->
                <li x-data="{ open: <?= $isActive('/report') ? 'true' : 'false' ?> }">
                    <button @click="open = !open" class="flex items-center w-full p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/report') ? 'text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-chart-line w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/report') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold text-left whitespace-nowrap flex-1">Reports</span>
                        <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="open ? 'rotate-180 text-primary' : 'text-gray-400'"></i>
                    </button>
                    <ul x-show="open" x-collapse.duration.300ms class="py-2 space-y-1">
                        <li><a href="/payrollsystem/admin/reports_attendance" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/reports_attendance') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Attendance Reports</a></li>
                        <li><a href="/payrollsystem/admin/reports_payroll" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/reports_payroll') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Payroll Reports</a></li>
                        <li><a href="/payrollsystem/admin/reports_leave" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/reports_leave') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">Leave Reports</a></li>
                        <li><a href="/payrollsystem/admin/reports_ot" class="flex items-center p-2 pl-12 text-sm rounded-lg transition-colors <?= $isActive('/reports_ot') ? 'text-primary font-bold bg-primary/5' : 'text-gray-600 dark:text-gray-400 hover:text-primary hover:bg-gray-50 dark:hover:bg-gray-800' ?>">OT Reports</a></li>
                    </ul>
                </li>
                
                <!-- NOTIFICATIONS -->
                <li>
                    <a href="/payrollsystem/admin/notifications" class="flex items-center p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/notifications') ? 'bg-primary/10 text-primary border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-bell w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/notifications') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold relative z-10">Notifications</span>
                    </a>
                </li>
                
                <!-- SETTINGS -->
                <li>
                    <a href="/payrollsystem/admin/settings" class="flex items-center p-3 rounded-xl transition-all duration-300 group relative overflow-hidden <?= $isActive('/settings') ? 'bg-primary/10 text-primary border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border-l-4 border-transparent hover:border-gray-300 dark:hover:border-gray-600' ?>">
                        <i class="fa-solid fa-gear w-6 h-6 flex items-center justify-center transition-all duration-300 <?= $isActive('/settings') ? 'text-primary scale-110 drop-shadow-[0_0_8px_rgba(79,70,229,0.5)]' : 'text-gray-400 group-hover:text-primary' ?>"></i>
                        <span class="ms-3 font-semibold relative z-10">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64 mt-14">
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
