<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Dashboard' ?> — Attendance & Payroll Management System</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        primary:   '#6366f1', // indigo-500
                        'primary-light': '#818cf8', // indigo-400
                        'primary-dark':  '#4f46e5', // indigo-600
                        secondary: '#0ea5e9', // sky-500
                        accent:    '#f43f5e', // rose-500
                        surface:   '#ffffff', // light card bg
                        'surface-dark': '#1e293b', // dark card bg (slate-800)
                    },
                    keyframes: {
                        float:    { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        floatSlow: { '0%,100%': { transform: 'translateY(0) scale(1)' }, '50%': { transform: 'translateY(-6px) scale(1.02)' } },
                        shimmer:  { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        pulse2:   { '0%,100%': { opacity: 1 }, '50%': { opacity: 0.5 } },
                        pulseGlow: { '0%,100%': { filter: 'drop-shadow(0 0 15px rgba(99,102,241,0.4))' }, '50%': { filter: 'drop-shadow(0 0 25px rgba(14,165,233,0.5))' } },
                        slideIn:  { '0%': { transform: 'translateX(-100%)', opacity: 0 }, '100%': { transform: 'translateX(0)', opacity: 1 } },
                        fadeUp:   { '0%': { transform: 'translateY(20px)',  opacity: 0 }, '100%': { transform: 'translateY(0)',  opacity: 1 } },
                        spinSlow: { '0%': { transform: 'rotate(0deg)' }, '100%': { transform: 'rotate(360deg)' } },
                        spinReverse: { '0%': { transform: 'rotate(360deg)' }, '100%': { transform: 'rotate(0deg)' } },
                        bounceSoft: { '0%,100%': { transform: 'scale(1)' }, '50%': { transform: 'scale(1.05)' } },
                        loaderProgress: { '0%': { width: '0%' }, '50%': { width: '70%' }, '100%': { width: '100%' } }
                    },
                    animation: {
                        'float':      'float 3s ease-in-out infinite',
                        'float-slow': 'floatSlow 4s ease-in-out infinite',
                        'shimmer':    'shimmer 2.5s linear infinite',
                        'slide-in':   'slideIn 0.4s ease-out',
                        'fade-up':    'fadeUp 0.5s ease-out',
                        'spin-slow':  'spinSlow 10s linear infinite',
                        'spin-reverse': 'spinReverse 14s linear infinite',
                        'bounce-soft':'bounceSoft 2s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
                        'loader-bar': 'loaderProgress 1.2s ease-in-out forwards',
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
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* === SCROLLBAR === */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* === TOPBAR GLASS === */
        .topbar-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid #e2e8f0;
        }
        .dark .topbar-glass {
            background: rgba(15, 23, 42, 0.92);
            border-bottom: 1px solid rgba(51, 65, 85, 0.8);
        }

        /* === SIDEBAR === */
        .sidebar-glass {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .dark .sidebar-glass {
            background: #0f172a;
            border-right: 1px solid rgba(51, 65, 85, 0.8);
        }

        /* === GLOW EFFECTS === */
        .glow-violet { box-shadow: 0 0 25px rgba(99, 102, 241, 0.35); }
        .glow-violet-sm { box-shadow: 0 0 12px rgba(99, 102, 241, 0.25); }
        .glow-cyan { box-shadow: 0 0 25px rgba(14, 165, 233, 0.35); }
        .glow-amber { box-shadow: 0 0 25px rgba(245, 158, 11, 0.3); }

        /* === CARD GLASS === */
        .card-glass {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
        }
        .card-glass:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }
        .dark .card-glass {
            background: #1e293b;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        .dark .card-glass:hover {
            border-color: #475569;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }

        /* === NAV ITEM === */
        .nav-item {
            position: relative;
            transition: all 0.2s ease-in-out;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3.5px;
            background: linear-gradient(to bottom, #6366f1, #4f46e5);
            border-radius: 0 4px 4px 0;
            opacity: 0;
            transition: all 0.2s ease;
        }
        .nav-item.active::before { opacity: 1; }
        .nav-item.active { 
            background: rgba(99, 102, 241, 0.08);
            color: #4338ca;
            font-weight: 700;
        }
        .dark .nav-item.active {
            background: rgba(99, 102, 241, 0.18);
            color: #ffffff;
        }

        /* === GRADIENT TEXT === */
        .gradient-text {
            background: linear-gradient(135deg, #4338ca 0%, #6366f1 45%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }
        .dark .gradient-text {
            background: linear-gradient(135deg, #a5b4fc 0%, #818cf8 45%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* === HERO BANNER PERFECTION === */
        .bg-gradient-to-r.from-indigo-600 {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 50%, #0ea5e9 100%) !important;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.35);
        }
        .bg-gradient-to-r.from-indigo-600 h1,
        .bg-gradient-to-r.from-indigo-600 h2 {
            color: #ffffff !important;
            text-shadow: 0 2px 8px rgba(15, 23, 42, 0.25);
        }
        .bg-gradient-to-r.from-indigo-600 p {
            color: #f1f5f9 !important;
        }
        .bg-gradient-to-r.from-indigo-600 .gradient-text {
            background: linear-gradient(135deg, #fef08a 0%, #fde047 50%, #67e8f9 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            font-weight: 900;
        }

        /* === ORBS === */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }
        .dark .orb { opacity: 0.25; }
        .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%); top: -100px; right: -100px; }
        .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(14,165,233,0.15) 0%, transparent 70%); bottom: -60px; left: -60px; }
        .orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(244,63,94,0.1) 0%, transparent 70%); top: 40%; left: 35%; }

        /* === NOTIFICATION DOT PULSE === */
        .notif-pulse { animation: pulse2 1.5s ease-in-out infinite; }

        /* === LOGO SPIN RING === */
        .logo-ring {
            position: absolute;
            inset: -2px;
            border-radius: 14px;
            background: conic-gradient(from 0deg, #6366f1, #0ea5e9, #f59e0b, #6366f1);
            animation: spinSlow 6s linear infinite;
            z-index: -1;
        }

        /* === MOBILE OVERLAY === */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 39;
        }
        .sidebar-overlay.show { display: block; }
    </style>
</head>
<body
    x-data="{
        sidebarOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }"
    x-init="
        if (darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    "
    class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen selection:bg-indigo-500 selection:text-white"
>

<!-- Background Ambient Lighting -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<!-- ============ ADVANCED ANIMATED LOADER ============ -->
<div id="global-loader" class="fixed inset-0 z-[100] bg-slate-50 dark:bg-slate-900 flex flex-col items-center justify-center transition-all duration-700">
    <div class="relative flex flex-col items-center">
        <!-- 3D Brand Badge in Rotating Rings -->
        <div class="relative w-28 h-28 flex items-center justify-center mb-6">
            <!-- Glowing Orbit Rings -->
            <div class="absolute inset-0 rounded-full border-2 border-violet-500/20 animate-spin-slow"></div>
            <div class="absolute -inset-3 rounded-full border-2 border-dashed border-cyan-400/30 animate-spin-reverse"></div>
            <div class="absolute inset-0 rounded-3xl bg-gradient-to-tr from-violet-600 to-cyan-500 blur-xl opacity-50 animate-pulse-glow"></div>
            
            <!-- Logo Image -->
            <div class="relative w-24 h-24 rounded-2xl overflow-hidden shadow-2xl border border-violet-400/40 bg-surface/90 p-1 animate-float">
                <img src="/payrollsystem/assets/img/system_brand_badge.jpg" 
                     alt="Attendance & Payroll Management System Logo" 
                     class="w-full h-full object-cover rounded-xl shadow-inner">
            </div>
        </div>

        <!-- System Title and Loading Status -->
        <div class="text-center space-y-2">
            <h1 class="text-xl md:text-2xl font-extrabold tracking-tight gradient-text font-outfit">
                Attendance & Payroll
            </h1>
            <p class="text-xs uppercase tracking-[0.25em] text-cyan-400/90 font-bold">
                Management System
            </p>
            
            <!-- Animated Progress Bar -->
            <div class="w-48 h-1.5 bg-gray-200 rounded-full overflow-hidden mx-auto mt-4 border border-gray-300 relative">
                <div class="h-full bg-gradient-to-r from-violet-500 via-cyan-400 to-amber-400 animate-loader-bar rounded-full"></div>
            </div>
            <p class="text-[11px] text-gray-600 dark:text-gray-400 font-medium tracking-wider pt-1 animate-pulse">Initializing workspace...</p>
        </div>
    </div>
</div>

<!-- ============ TOPBAR ============ -->
<nav class="fixed top-0 z-50 w-full topbar-glass h-16 shadow-lg shadow-black/20">
    <div class="h-full px-4 lg:px-6 flex items-center justify-between">

        <!-- Left: Brand Logo & Title -->
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="sm:hidden text-gray-600 dark:text-gray-400 hover:text-violet-400 w-9 h-9 flex items-center justify-center rounded-xl hover:bg-violet-500/10 transition-all border border-transparent hover:border-violet-500/20">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <a href="<?= ($_SESSION['role'] ?? '') === 'Employee' ? '/payrollsystem/employee' : '/payrollsystem/admin' ?>" class="flex items-center gap-3 group">
                <div class="relative w-10 h-10 flex-shrink-0">
                    <div class="logo-ring rounded-2xl opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-violet-400/40 bg-surface flex items-center justify-center relative z-10 shadow-lg group-hover:scale-105 transition-transform">
                        <img src="/payrollsystem/assets/img/system_brand_badge.jpg" 
                             alt="APMS Logo" 
                             class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="hidden sm:block">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-base lg:text-lg gradient-text font-outfit leading-tight tracking-tight">Attendance & Payroll</span>
                        <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded-md bg-gradient-to-r from-violet-600/30 to-cyan-500/30 text-violet-300 border border-violet-400/30 shadow-sm">APMS</span>
                    </div>
                    <div class="text-[10px] text-cyan-400/90 font-semibold tracking-wider uppercase -mt-0.5 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Management System • <?= htmlspecialchars($_SESSION['role'] ?? 'Portal') ?></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Right: Notifications + User Profile -->
        <div class="flex items-center gap-2.5">

            <!-- Dark Mode Toggle -->
            <button @click="toggleDarkMode()"
                    class="relative w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all shadow-sm">
                <!-- Moon Icon for Light Mode -->
                <i x-show="!darkMode" class="fa-solid fa-moon text-sm text-indigo-600"></i>
                <!-- Sun Icon for Dark Mode -->
                <i x-show="darkMode" class="fa-solid fa-sun text-sm text-amber-400" x-cloak></i>
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
                        if(data.new_count > 0 && data.notifications.length > 0) {
                            const newNotif = data.notifications.find(n => n.is_new) || data.notifications[0];
                            playChime();
                            showToast(newNotif);
                        }
                        unreadCount = data.unread_count;
                        notifications = data.notifications;
                    }
                });
                fetchNotifs();
                setInterval(fetchNotifs, 10000);
                window.addEventListener('notifications-read', fetchNotifs);
            ">
                <button @click="notifOpen = !notifOpen"
                        class="relative w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all shadow-sm">
                    <i class="fa-solid fa-bell text-sm"></i>
                    <span x-show="unreadCount > 0" x-text="unreadCount"
                          class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-extrabold min-w-[18px] h-[18px] flex items-center justify-center rounded-full px-1 shadow-md notif-pulse"></span>
                </button>

                <!-- Toast -->
                <template x-teleport="body">
                    <div x-show="toastNotif"
                         @click="if(toastNotif && toastNotif.link && toastNotif.link !== '#') window.location.href = '/payrollsystem' + toastNotif.link"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
                         class="fixed bottom-5 right-5 z-[100] w-84 card-glass rounded-2xl overflow-hidden cursor-pointer border border-indigo-200 dark:border-slate-700 hover:scale-105 transition-all shadow-2xl"
                         x-cloak>
                        <div class="p-4 flex gap-3.5 relative bg-white dark:bg-slate-800">
                            <button @click.stop="toastNotif=null" class="absolute top-3 right-3 text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xs"></i></button>
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-sky-500 flex items-center justify-center flex-shrink-0 text-white shadow-md">
                                <i class="fa-solid fa-bell text-sm animate-bounce-soft"></i>
                            </div>
                            <div class="flex-1 pr-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-sky-400" x-text="toastNotif?.title || 'System Alert'"></p>
                                <p class="text-xs text-slate-700 dark:text-slate-300 mt-0.5 line-clamp-2" x-text="toastNotif?.message"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Notifications Dropdown Menu -->
                <div x-show="notifOpen" @click.away="notifOpen=false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute right-0 top-12 w-84 bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-2xl z-50 border border-slate-200 dark:border-slate-700"
                     x-cloak>
                    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <span class="font-bold text-slate-800 dark:text-slate-100 text-xs uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-bolt text-indigo-500"></i> Active Alerts
                        </span>
                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400 bg-slate-200 dark:bg-slate-800 px-2 py-0.5 rounded-full" x-text="notifications.length + ' Live'"></span>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/60">
                        <template x-if="notifications.length === 0">
                            <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-2 text-indigo-500">
                                    <i class="fa-solid fa-circle-check text-lg"></i>
                                </div>
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">All caught up!</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">No pending alerts at this time.</p>
                            </div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <a :href="'/payrollsystem' + notif.link"
                               class="flex items-start gap-3.5 p-3.5 hover:bg-indigo-50 dark:hover:bg-slate-700/50 transition-colors group relative">
                                <div class="w-8 h-8 rounded-xl flex-shrink-0 flex items-center justify-center text-white text-xs mt-0.5 shadow-sm"
                                     :class="{
                                         'bg-indigo-600': notif.type === 'attendance' || notif.type === 'info',
                                         'bg-emerald-600': notif.type === 'success' || notif.type === 'leave',
                                         'bg-amber-500': notif.type === 'warning' || notif.type === 'overtime',
                                         'bg-rose-500':    notif.type === 'error',
                                     }">
                                    <i class="fa-solid" :class="{
                                        'fa-clock-rotate-left': notif.type === 'attendance',
                                        'fa-calendar-check':    notif.type === 'leave',
                                        'fa-bolt':              notif.type === 'overtime',
                                        'fa-money-bill-wave':   notif.type === 'payroll' || notif.type === 'warning',
                                        'fa-triangle-exclamation': notif.type === 'error',
                                        'fa-bell':              !['attendance','leave','overtime','error'].includes(notif.type)
                                    }"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" x-text="notif.title || 'Notification'"></p>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2 mt-0.5" x-text="notif.message"></p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[9px] text-slate-400 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all mt-2"></i>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- User Avatar & Profile Pill -->
            <div class="relative" x-data="{ userOpen: false }">
                <button @click="userOpen = !userOpen"
                        class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all group shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-sky-500 flex items-center justify-center font-extrabold text-white text-xs shadow-sm group-hover:scale-105 transition-transform">
                        <?= htmlspecialchars(strtoupper(substr($_SESSION['Email'] ?? ($_SESSION['first_name'] ?? 'U'), 0, 1))) ?>
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                            <?= htmlspecialchars($_SESSION['first_name'] ?? ($_SESSION['role'] ?? 'User')) ?>
                        </div>
                        <div class="text-[10px] text-indigo-600 dark:text-sky-400 font-semibold leading-none mt-0.5 capitalize"><?= htmlspecialchars($_SESSION['role'] ?? 'Staff') ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-sky-400 transition-colors ml-1"></i>
                </button>
                <div x-show="userOpen" @click.away="userOpen=false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute right-0 top-12 w-56 bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-2xl z-50 border border-slate-200 dark:border-slate-700"
                     x-cloak>
                    <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Authenticated As</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mt-0.5"><?= htmlspecialchars($_SESSION['Email'] ?? '') ?></p>
                        <span class="inline-block mt-1 text-[9px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-700"><?= htmlspecialchars($_SESSION['role'] ?? 'User') ?></span>
                    </div>
                    <div class="p-2 space-y-1">
                        <?php if (($_SESSION['role'] ?? '') === 'Employee'): ?>
                        <a href="/payrollsystem/employee/profile" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all">
                            <i class="fa-solid fa-user text-indigo-500 w-4 text-center"></i> My Profile
                        </a>
                        <a href="/payrollsystem/employee/salary_history" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-emerald-600 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all">
                            <i class="fa-solid fa-file-invoice-dollar text-emerald-500 w-4 text-center"></i> My Salary Slip
                        </a>
                        <?php endif; ?>
                        <a href="/payrollsystem/auth/logout" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-all">
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
    <div class="h-full py-4 px-3.5 overflow-y-auto flex flex-col justify-between">

        <div>
            <!-- Navigation Links -->
            <nav class="space-y-1">
            <?php
            if (($_SESSION['role'] ?? '') === 'Employee') {
                $navSections = [
                    [
                        'label' => 'Main Portal',
                        'items' => [
                            ['href' => '/payrollsystem/employee', 'icon' => 'fa-chart-pie', 'label' => 'Dashboard', 'exact' => true],
                            ['href' => '/payrollsystem/employee/attendance', 'icon' => 'fa-clock-rotate-left', 'label' => 'My Attendance', 'match' => '/attendance'],
                            ['href' => '/payrollsystem/employee/salary_history', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Salary History', 'match' => '/salary_history'],
                        ]
                    ],
                    [
                        'label' => 'Requests & OT',
                        'items' => [
                            ['href' => '/payrollsystem/employee/leaves', 'icon' => 'fa-calendar-minus', 'label' => 'My Leaves', 'match' => '/leaves'],
                            ['href' => '/payrollsystem/employee/overtime', 'icon' => 'fa-clipboard-list', 'label' => 'Overtime Assign', 'match' => '/overtime'],
                        ]
                    ],
                    [
                        'label' => 'Guidelines & Policies',
                        'items' => [
                            ['href' => '/payrollsystem/employee/rules', 'icon' => 'fa-book-bookmark', 'label' => 'Rules & Policies', 'match' => '/rules'],
                        ]
                    ]
                ];
            } else {
                $navSections = [
                    [
                        'label' => 'Main Console',
                        'items' => [
                            ['href' => '/payrollsystem/admin', 'icon' => 'fa-chart-pie', 'label' => 'Dashboard', 'exact' => true],
                            ['href' => '/payrollsystem/admin/rules', 'icon' => 'fa-book-bookmark', 'label' => 'Rules & Policies', 'match' => '/rules'],
                        ]
                    ],
                    [
                        'label' => 'Organization Management',
                        'items' => [
                           
                            ['href' => '/payrollsystem/admin/departments','icon' => 'fa-sitemap', 'label' => 'Departments', 'match' => '/departments'],
                            ['href' => '/payrollsystem/admin/positions',  'icon' => 'fa-id-badge','label' => 'Positions',   'match' => '/positions'],
                             ['href' => '/payrollsystem/admin/employees', 'icon' => 'fa-users', 'label' => 'Employees Directory', 'match' => '/employees'],
                        ]
                    ],
                    [
                        'label' => 'Attendance Management',
                        'items' => [
                            ['href' => '/payrollsystem/admin/attendance', 'icon' => 'fa-clock-rotate-left', 'label' => 'Attendance Logs', 'match' => '/attendance'],
                        ]
                    ],
                    [
                        'label' => 'Leaves Management',
                        'items' => [
                            ['href' => '/payrollsystem/admin/leave_types', 'icon' => 'fa-list-check',       'label' => 'Leave Types', 'match' => '/leave_types'],
                            ['href' => '/payrollsystem/admin/leaves',      'icon' => 'fa-calendar-minus',  'label' => 'Leave Requests', 'match' => '/leaves'],
                            
                        ]
                    ],
                    [
                        'label' => 'Overtime Operations',
                        'items' => [
                            ['href' => '/payrollsystem/admin/overtime_assignments','icon' => 'fa-clipboard-list','label' => 'OT Assignments','match' => '/overtime_assignments'],
                        ]
                    ],
                    [
                        'label' => 'Payroll Management',
                        'items' => [
                             ['href' => '/payrollsystem/admin/bonuses',     'icon' => 'fa-gift',               'label' => 'Bonuses', 'match' => '/bonuses'],
                            ['href' => '/payrollsystem/admin/payroll',     'icon' => 'fa-file-invoice-dollar','label' => 'Calculate Salary',   'match' => '/payroll'],
                           
                        ]
                    ],
                    [
                        'label' => 'Security & Access',
                        'items' => [
                            ['href' => '/payrollsystem/admin/password_resets', 'icon' => 'fa-key', 'label' => 'Password Resets', 'match' => '/password_resets'],
                        ]
                    ]
                ];
            }
            foreach ($navSections as $section):
            ?>
                <div class="pt-3 pb-1">
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-[0.15em] text-slate-400 dark:text-slate-400 mb-1.5 flex items-center gap-1.5">
                        <span class="w-1 h-1 rounded-full bg-indigo-500"></span>
                        <span><?= $section['label'] ?></span>
                    </p>
                    <?php foreach ($section['items'] as $item):
                        $active = !empty($item['exact'])
                            ? ($currentPath === $item['href'])
                            : (!empty($item['match']) && strpos($currentPath . ($_SERVER['QUERY_STRING'] ?? ''), ltrim($item['match'], '/')) !== false);
                    ?>
                    <a href="<?= $item['href'] ?>"
                       class="nav-item <?= $active ? 'active' : '' ?> flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group
                              <?= $active ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-white border border-indigo-200 dark:border-indigo-700/50 shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/60 border border-transparent' ?>">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200
                                    <?= $active ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:bg-indigo-50 dark:group-hover:bg-slate-700' ?>">
                            <i class="fa-solid <?= $item['icon'] ?> text-xs"></i>
                        </div>
                        <span class="tracking-wide"><?= $item['label'] ?></span>
                        <?php if ($active): ?>
                        <div class="ml-auto w-2 h-2 rounded-full bg-indigo-500 dark:bg-sky-400 shadow-sm"></div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            </nav>
        </div>

        <!-- Bottom Sign Out -->
        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800">
            <a href="/payrollsystem/auth/logout"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 border border-transparent hover:border-rose-200 dark:hover:border-rose-900 transition-all duration-200 group">
                <div class="w-7 h-7 rounded-lg bg-rose-100 dark:bg-rose-950/60 flex items-center justify-center text-rose-600 dark:text-rose-400 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                </div>
                <span>Sign Out</span>
            </a>
        </div>
    </div>
</aside>

<!-- ============ MAIN CONTENT ============ -->
<main class="sm:ml-64 pt-16 min-h-screen relative">
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

<!-- Global Flash Message Component -->
<?php 
$flashMessage = null;
$flashType = 'info';
$flashTitle = 'Notification';

if (isset($_SESSION['flash_success'])) {
    $flashMessage = $_SESSION['flash_success'];
    $flashType = 'success';
    $flashTitle = 'Success';
    unset($_SESSION['flash_success']);
} elseif (isset($_SESSION['flash_error'])) {
    $flashMessage = $_SESSION['flash_error'];
    $flashType = 'error';
    $flashTitle = 'Error';
    unset($_SESSION['flash_error']);
} elseif (isset($_SESSION['flash_notification'])) {
    $flashMessage = $_SESSION['flash_notification'];
    $flashType = $_SESSION['flash_type'] ?? 'info';
    $flashTitle = $_SESSION['flash_title'] ?? 'Notification';
    unset($_SESSION['flash_notification']);
    unset($_SESSION['flash_title']);
    unset($_SESSION['flash_type']);
}
?>

<?php if ($flashMessage): ?>
    <div id="flash-toast-notification" class="fixed top-5 right-5 z-[200] w-84 bg-white dark:bg-slate-800 rounded-2xl overflow-hidden cursor-pointer border border-slate-200 dark:border-slate-700 shadow-2xl transition-all duration-300 transform translate-x-full opacity-0" style="transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);">
        <div class="p-4 flex gap-3.5 relative bg-white dark:bg-slate-800">
            <button onclick="closeFlashToast()" class="absolute top-3 right-3 text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xs"></i></button>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?= $flashType === 'error' ? 'from-rose-500 to-red-600' : ($flashType === 'success' ? 'from-emerald-500 to-teal-600' : 'from-indigo-500 to-sky-500') ?> flex items-center justify-center flex-shrink-0 shadow-sm text-white">
                <i class="fa-solid <?= $flashType === 'error' ? 'fa-triangle-exclamation' : ($flashType === 'success' ? 'fa-check' : 'fa-bell') ?> text-sm"></i>
            </div>
            <div class="flex-1 pr-4">
                <p class="text-xs font-bold uppercase tracking-wider <?= $flashType === 'error' ? 'text-rose-600 dark:text-rose-400' : ($flashType === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 'text-indigo-600 dark:text-sky-400') ?>"><?= htmlspecialchars($flashTitle) ?></p>
                <p class="text-xs text-slate-700 dark:text-slate-300 mt-0.5"><?= htmlspecialchars($flashMessage) ?></p>
            </div>
        </div>
    </div>
    <script>
        function closeFlashToast() {
            const toast = document.getElementById('flash-toast-notification');
            if (toast) {
                toast.style.transform = 'translateX(full)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }
        
        window.addEventListener('load', () => {
            const toast = document.getElementById('flash-toast-notification');
            if (toast) {
                // Play notification chime
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
                
                // Animate in
                setTimeout(() => {
                    toast.style.transform = 'translateX(0)';
                    toast.style.opacity = '1';
                }, 100);
                
                // Auto dismiss
                setTimeout(closeFlashToast, 5000);
            }
        });
    </script>
<?php endif; ?>

</body>
</html>
