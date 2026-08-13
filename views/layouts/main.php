<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'HRMS Admin Dashboard' ?> — PayrollPro</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary:   '#7c3aed', // violet-700
                        'primary-light': '#8b5cf6', // violet-500
                        'primary-dark':  '#5b21b6', // violet-800
                        secondary: '#06b6d4', // cyan-500
                        dark:      '#0f0a1e',
                        darker:    '#070512',
                        surface:   '#1a1030',
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    },
                    keyframes: {
                        float:    { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        shimmer:  { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        pulse2:   { '0%,100%': { opacity: 1 },                 '50%':  { opacity: 0.6 } },
                        slideIn:  { '0%': { transform: 'translateX(-100%)', opacity: 0 }, '100%': { transform: 'translateX(0)', opacity: 1 } },
                        fadeUp:   { '0%': { transform: 'translateY(20px)',  opacity: 0 }, '100%': { transform: 'translateY(0)',  opacity: 1 } },
                        spinSlow: { '0%': { transform: 'rotate(0deg)' }, '100%': { transform: 'rotate(360deg)' } },
                        bounceSoft: { '0%,100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.05)' } },
                        shrink:   { 'from': { width: '100%' }, 'to': { width: '0%' } },
                    },
                    animation: {
                        'float':      'float 3s ease-in-out infinite',
                        'float-slow': 'float 5s ease-in-out infinite',
                        'shimmer':    'shimmer 3s linear infinite',
                        'slide-in':   'slideIn 0.4s ease-out',
                        'fade-up':    'fadeUp 0.5s ease-out',
                        'spin-slow':  'spinSlow 8s linear infinite',
                        'bounce-soft':'bounceSoft 2s ease-in-out infinite',
                        'shrink-bar': 'shrink 5s linear forwards',
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #070512;
        }

        /* === SCROLLBAR === */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #5b21b6; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #7c3aed; }

        /* === TOPBAR GLASS === */
        .topbar-glass {
            background: rgba(7, 5, 18, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(124, 58, 237, 0.2);
        }

        /* === SIDEBAR === */
        .sidebar-glass {
            background: rgba(15, 10, 30, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(124, 58, 237, 0.15);
        }

        /* === GLOW === */
        .glow-violet { box-shadow: 0 0 20px rgba(124, 58, 237, 0.35); }
        .glow-violet-sm { box-shadow: 0 0 10px rgba(124, 58, 237, 0.25); }
        .text-glow { text-shadow: 0 0 12px rgba(167, 139, 250, 0.6); }

        /* === CARD GLASS === */
        .card-glass {
            background: rgba(26, 16, 48, 0.75);
            border: 1px solid rgba(124, 58, 237, 0.2);
            backdrop-filter: blur(12px);
        }
        .card-glass:hover {
            border-color: rgba(124, 58, 237, 0.5);
            box-shadow: 0 0 24px rgba(124, 58, 237, 0.15);
        }

        /* === NAV ITEM === */
        .nav-item {
            position: relative;
            transition: all 0.25s ease;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #7c3aed;
            border-radius: 0 3px 3px 0;
            opacity: 0;
            transition: all 0.25s ease;
        }
        .nav-item.active::before { opacity: 1; }
        .nav-item.active { background: rgba(124, 58, 237, 0.12); }

        /* === GRADIENT TEXT === */
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa, #c4b5fd, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* === ORBS === */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        .orb-1 { width: 400px; height: 400px; background: rgba(124,58,237,0.12); top: -100px; right: -100px; }
        .orb-2 { width: 300px; height: 300px; background: rgba(6,182,212,0.07); bottom: 0; left: 0; }

        /* === SHIMMER GRADIENT === */
        .shimmer-bg {
            background: linear-gradient(90deg, rgba(124,58,237,0) 0%, rgba(167,139,250,0.15) 50%, rgba(124,58,237,0) 100%);
            background-size: 200% 100%;
        }

        /* === STAT CARD === */
        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-4px); }
        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.03), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        /* === PROGRESS BAR === */
        .progress-fill { transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); }

        /* === BADGE === */
        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }

        /* === NOTIFICATION DOT PULSE === */
        .notif-pulse { animation: pulse2 1.5s ease-in-out infinite; }

        /* === LOGO SPIN RING === */
        .logo-ring {
            position: absolute;
            inset: -3px;
            border-radius: 12px;
            background: conic-gradient(from 0deg, #7c3aed, #06b6d4, #a78bfa, #7c3aed);
            animation: spinSlow 4s linear infinite;
            z-index: -1;
        }

        /* === SHRINK BAR (toast) === */
        @keyframes shrinkBar { from { width: 100%; } to { width: 0; } }
        .animate-shrink-bar { animation: shrinkBar 5s linear forwards; }

        /* === MOBILE OVERLAY === */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 39;
        }
        .sidebar-overlay.show { display: block; }
    </style>
</head>
<body
    x-data="{
        sidebarOpen: false,
        darkMode: true
    }"
    x-init="
        darkMode = true;
        document.documentElement.classList.add('dark');
    "
    class="bg-darker text-gray-100 min-h-screen"
>

<!-- Background Orbs -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>

<!-- Global Loader -->
<div id="global-loader" class="fixed inset-0 z-[100] bg-darker flex flex-col items-center justify-center transition-opacity duration-700">
    <div class="relative">
        <div class="w-16 h-16 rounded-full border-2 border-violet-900 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full border-t-2 border-violet-400 animate-spin"></div>
            <i class="fa-solid fa-building-user text-violet-400 text-xl relative z-10"></i>
        </div>
    </div>
    <p class="mt-4 text-violet-300 text-sm font-semibold tracking-widest uppercase animate-pulse">Loading PayrollPro...</p>
</div>

<!-- ============ TOPBAR ============ -->
<nav class="fixed top-0 z-50 w-full topbar-glass h-16">
    <div class="h-full px-4 lg:px-6 flex items-center justify-between">

        <!-- Left: Logo + Hamburger -->
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="sm:hidden text-gray-400 hover:text-violet-400 w-9 h-9 flex items-center justify-center rounded-lg hover:bg-violet-500/10 transition-all">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <a href="/payrollsystem/admin" class="flex items-center gap-3 group">
                <div class="relative w-9 h-9">
                    <div class="logo-ring rounded-xl opacity-70 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-9 h-9 bg-gradient-to-br from-violet-600 to-purple-900 rounded-xl flex items-center justify-center relative z-10">
                        <i class="fa-solid fa-building-user text-white text-sm"></i>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="font-extrabold text-lg gradient-text">PayrollPro</span>
                    <div class="text-[10px] text-violet-400/70 font-medium tracking-widest uppercase -mt-1">Admin Panel</div>
                </div>
            </a>
        </div>

        <!-- Right: Notifications + User -->
        <div class="flex items-center gap-2">

            <!-- Notifications -->
            <div class="relative" x-data="{
                notifOpen: false,
                unreadCount: 0,
                notifications: [],
                toastNotif: null,
                toastTimeout: null,
                playChime() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain); gain.connect(ctx.destination);
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, ctx.currentTime);
                        osc.frequency.exponentialRampToValueAtTime(440, ctx.currentTime + 0.1);
                        gain.gain.setValueAtTime(0.5, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                        osc.start(ctx.currentTime); osc.stop(ctx.currentTime + 0.3);
                    } catch(e) {}
                },
                showToast(notif) {
                    this.toastNotif = notif;
                    if(this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastNotif = null; }, 5000);
                }
            }"
            x-init="
                const fetchNotifs = () => fetch('/payrollsystem/notification/api?action=get').then(r=>r.json()).then(data=>{
                    if(data.unread_count !== undefined) {
                        if(data.unread_count > unreadCount && data.notifications.length > 0) { playChime(); showToast(data.notifications[0]); }
                        unreadCount = data.unread_count;
                        notifications = data.notifications;
                    }
                });
                fetchNotifs();
                setInterval(fetchNotifs, 10000);
                window.addEventListener('notifications-read', fetchNotifs);
            ">
                <button @click="notifOpen = !notifOpen"
                        class="relative w-9 h-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-violet-400 hover:bg-violet-500/10 transition-all">
                    <i class="fa-solid fa-bell text-base"></i>
                    <span x-show="unreadCount > 0" x-text="unreadCount"
                          class="absolute -top-0.5 -right-0.5 bg-violet-500 text-white text-[9px] font-bold min-w-[16px] h-4 flex items-center justify-center rounded-full px-1 notif-pulse"></span>
                </button>

                <!-- Toast -->
                <template x-teleport="body">
                    <div x-show="toastNotif"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-10"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-10"
                         class="fixed bottom-5 right-5 z-[100] w-80 card-glass rounded-2xl overflow-hidden cursor-pointer hover:border-violet-400/50 transition-all"
                         @click="window.location.href='/payrollsystem/notification'; toastNotif=null"
                         x-cloak>
                        <div class="p-4 flex gap-3 relative">
                            <button @click.stop="toastNotif=null" class="absolute top-3 right-3 text-gray-500 hover:text-gray-300 transition-colors"><i class="fa-solid fa-xmark text-xs"></i></button>
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-purple-800 flex items-center justify-center flex-shrink-0 glow-violet-sm">
                                <i class="fa-solid fa-bell text-white text-sm animate-bounce-soft"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white pr-4" x-text="toastNotif?.title || 'New Notification'"></p>
                                <p class="text-xs text-gray-400 line-clamp-2 mt-0.5" x-text="toastNotif?.message"></p>
                            </div>
                        </div>
                        <div class="h-0.5 bg-violet-900 w-full"><div class="h-full bg-gradient-to-r from-violet-500 to-cyan-500 animate-shrink-bar"></div></div>
                    </div>
                </template>

                <!-- Dropdown -->
                <div x-show="notifOpen" @click.away="notifOpen=false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute right-0 top-12 w-80 card-glass rounded-2xl overflow-hidden shadow-2xl z-50"
                     x-cloak>
                    <div class="px-4 py-3 border-b border-violet-900/50 flex items-center justify-between">
                        <span class="font-bold text-white text-sm">Notifications</span>
                        <button x-show="unreadCount > 0"
                                @click="fetch('/payrollsystem/notification/api?action=read_all',{method:'POST'}).then(()=>{ unreadCount=0; notifications.forEach(n=>n.is_read=1); })"
                                class="text-[11px] text-violet-400 hover:text-violet-300 font-semibold transition-colors">Mark all read</button>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="py-10 text-center">
                                <i class="fa-regular fa-bell-slash text-3xl text-violet-900 mb-2"></i>
                                <p class="text-xs text-gray-500">No notifications yet</p>
                            </div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <a :href="'/payrollsystem' + notif.link"
                               @click="fetch('/payrollsystem/notification/api?action=read',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id:notif.id})})"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-violet-500/8 border-b border-violet-900/30 transition-colors relative"
                               :class="notif.is_read == 0 ? 'bg-violet-500/5' : ''">
                                <div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center text-white text-xs mt-0.5"
                                     :class="{
                                         'bg-blue-500/80':    notif.type === 'attendance',
                                         'bg-emerald-500/80': notif.type === 'leave',
                                         'bg-orange-500/80':  notif.type === 'overtime',
                                         'bg-violet-500/80':  notif.type === 'payroll',
                                         'bg-red-500/80':     notif.type === 'error',
                                         'bg-gray-500/80':    !['attendance','leave','overtime','payroll','error'].includes(notif.type)
                                     }">
                                    <i class="fa-solid" :class="{
                                        'fa-clock-rotate-left': notif.type==='attendance',
                                        'fa-plane':             notif.type==='leave',
                                        'fa-bolt':              notif.type==='overtime',
                                        'fa-money-check-dollar':notif.type==='payroll',
                                        'fa-triangle-exclamation': notif.type==='error',
                                        'fa-bell':              !['attendance','leave','overtime','payroll','error'].includes(notif.type)
                                    }"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white truncate" x-text="notif.title || 'System Notification'"></p>
                                    <p class="text-[11px] text-gray-400 line-clamp-1 mt-0.5" x-text="notif.message"></p>
                                    <p class="text-[10px] text-violet-400 mt-1" x-text="new Date(notif.created_at).toLocaleString([],{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'})"></p>
                                </div>
                                <div x-show="notif.is_read == 0" class="absolute right-4 top-4 w-2 h-2 bg-violet-400 rounded-full notif-pulse"></div>
                            </a>
                        </template>
                    </div>
                    <a href="/payrollsystem/notification"
                       class="block text-center py-3 text-xs text-violet-400 font-bold hover:text-violet-300 border-t border-violet-900/50 hover:bg-violet-500/5 transition-colors">
                        View All <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- User Avatar -->
            <div class="relative" x-data="{ userOpen: false }">
                <button @click="userOpen = !userOpen"
                        class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-violet-500/10 transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center font-bold text-white text-sm glow-violet-sm">
                        <?= htmlspecialchars(strtoupper(substr($_SESSION['email'] ?? 'A', 0, 1))) ?>
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-bold text-white leading-none">Admin</div>
                        <div class="text-[10px] text-gray-500 leading-none mt-0.5 truncate max-w-[100px]"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-500 group-hover:text-violet-400 transition-colors"></i>
                </button>
                <div x-show="userOpen" @click.away="userOpen=false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 top-12 w-52 card-glass rounded-2xl overflow-hidden shadow-2xl z-50"
                     x-cloak>
                    <div class="px-4 py-3 border-b border-violet-900/50">
                        <p class="text-xs text-gray-400">Signed in as</p>
                        <p class="text-sm font-bold text-white truncate"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>
                    </div>
                    <div class="p-2">
                        <a href="/payrollsystem/admin/settings" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-violet-500/10 hover:text-violet-300 transition-colors">
                            <i class="fa-solid fa-gear w-4 text-center text-violet-500"></i> Settings
                        </a>
                        <a href="/payrollsystem/auth/logout" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- ============ SIDEBAR ============ -->
<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isActive = function($path) use ($currentPath) {
    return strpos($currentPath, $path) !== false;
};
?>

<!-- Mobile overlay -->
<div class="sidebar-overlay" :class="sidebarOpen ? 'show' : ''" @click="sidebarOpen=false"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform duration-300 sm:translate-x-0 sidebar-glass"
       aria-label="Sidebar">
    <div class="h-full py-4 px-3 overflow-y-auto flex flex-col">

        <!-- Admin Profile Card -->
        <div class="mx-1 mb-5 p-3 rounded-2xl bg-gradient-to-br from-violet-900/40 to-purple-900/20 border border-violet-700/20 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center font-extrabold text-white text-base flex-shrink-0">
                <?= htmlspecialchars(strtoupper(substr($_SESSION['email'] ?? 'A', 0, 1))) ?>
            </div>
            <div class="min-w-0">
                <div class="text-sm font-bold text-white">System Admin</div>
                <div class="text-[10px] text-violet-400/80 truncate"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
            </div>
        </div>

        <nav class="flex-1 space-y-0.5">

            <?php
            $navSections = [
                [
                    'label' => 'Main',
                    'items' => [
                        ['href' => '/payrollsystem/admin', 'icon' => 'fa-chart-pie', 'label' => 'Dashboard', 'exact' => true],
                    ]
                ],
                [
                    'label' => 'Management',
                    'items' => [
                        ['href' => '/payrollsystem/admin/employees', 'icon' => 'fa-users', 'label' => 'Employees',    'match' => '/employees'],
                        ['href' => '/payrollsystem/admin/departments','icon' => 'fa-sitemap', 'label' => 'Departments', 'match' => '/departments'],
                        ['href' => '/payrollsystem/admin/positions',  'icon' => 'fa-id-badge','label' => 'Positions',   'match' => '/positions'],
                    ]
                ],
                [
                    'label' => 'Attendance',
                    'items' => [
                        ['href' => '/payrollsystem/admin/attendance', 'icon' => 'fa-clock-rotate-left', 'label' => 'Attendance', 'match' => '/attendance'],
                        ['href' => '/payrollsystem/admin/attendance?tab=corrections', 'icon' => 'fa-file-pen', 'label' => 'Corrections', 'match' => 'corrections'],
                    ]
                ],
                [
                    'label' => 'Leave & OT',
                    'items' => [
                        ['href' => '/payrollsystem/admin/leaves',      'icon' => 'fa-calendar-minus',  'label' => 'Leave Requests', 'match' => '/leaves'],
                        ['href' => '/payrollsystem/admin/leave_types', 'icon' => 'fa-list-check',       'label' => 'Leave Types',    'match' => '/leave_types'],
                        ['href' => '/payrollsystem/admin/overtime',    'icon' => 'fa-bolt',              'label' => 'OT Requests',    'match' => '/overtime'],
                        ['href' => '/payrollsystem/admin/overtime_assignments','icon' => 'fa-clipboard-list','label' => 'OT Assignments','match' => '/overtime_assignments'],
                    ]
                ],
                [
                    'label' => 'Payroll',
                    'items' => [
                        ['href' => '/payrollsystem/admin/payroll',     'icon' => 'fa-file-invoice-dollar','label' => 'Payroll List',   'match' => '/payroll'],
                        ['href' => '/payrollsystem/admin/bonuses',     'icon' => 'fa-gift',               'label' => 'Bonuses',        'match' => '/bonuses'],
                        ['href' => '/payrollsystem/admin/deductions',  'icon' => 'fa-scissors',           'label' => 'Deductions',     'match' => '/deductions'],
                        ['href' => '/payrollsystem/admin/holidays',    'icon' => 'fa-umbrella-beach',     'label' => 'Holidays',       'match' => '/holidays'],
                    ]
                ],
                [
                    'label' => 'System',
                    'items' => [
                        ['href' => '/payrollsystem/notification',      'icon' => 'fa-bell',        'label' => 'Notifications',  'match' => '/notification'],
                        ['href' => '/payrollsystem/admin/settings',    'icon' => 'fa-gear',        'label' => 'Settings',       'match' => '/settings'],
                    ]
                ]
            ];
            foreach ($navSections as $section):
            ?>
                <div class="pt-3 pb-1">
                    <p class="px-3 text-[9px] font-bold uppercase tracking-[0.12em] text-violet-600/60 mb-1"><?= $section['label'] ?></p>
                    <?php foreach ($section['items'] as $item):
                        $active = !empty($item['exact'])
                            ? ($currentPath === $item['href'])
                            : (!empty($item['match']) && strpos($currentPath . ($_SERVER['QUERY_STRING'] ?? ''), ltrim($item['match'], '/')) !== false);
                    ?>
                    <a href="<?= $item['href'] ?>"
                       class="nav-item <?= $active ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group
                              <?= $active ? 'text-violet-300' : 'text-gray-400 hover:text-violet-300 hover:bg-violet-500/8' ?>">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200
                                    <?= $active ? 'bg-violet-500/25 text-violet-300' : 'text-gray-500 group-hover:text-violet-400' ?>">
                            <i class="fa-solid <?= $item['icon'] ?> text-xs"></i>
                        </div>
                        <span><?= $item['label'] ?></span>
                        <?php if ($active): ?>
                        <div class="ml-auto w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>

        <!-- Bottom Sign Out -->
        <div class="mt-4 pt-4 border-t border-violet-900/40">
            <a href="/payrollsystem/auth/logout"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-400/80 hover:text-red-400 hover:bg-red-500/8 transition-all">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                </div>
                Sign Out
            </a>
        </div>
    </div>
</aside>

<!-- ============ MAIN CONTENT ============ -->
<main class="sm:ml-64 pt-16 min-h-screen relative z-10">
    <div class="p-5 md:p-7">
        <?php if(isset($data['content'])) {
            require_once __DIR__ . '/../' . $data['content'] . '.php';
        } ?>
    </div>
</main>

<!-- AOS + Scripts -->
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ once: true, duration: 700, offset: 60 });

    window.addEventListener('load', () => {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 700);
        }
    });
</script>

<!-- Pseudo-Cron -->
<script>
    fetch('/payrollsystem/cron/run?token=cron_secret_12345').catch(() => {});
</script>

</body>
</html>
