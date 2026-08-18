<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Login' ?> — Attendance & Payroll Management System</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        primary: '#6366f1',
                        secondary: '#0ea5e9',
                        dark: '#0f172a',
                        darker: '#f1f5f9',
                        surface: '#ffffff',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #ede9fe 50%, #e0f2fe 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }
        .gradient-text-light {
            background: linear-gradient(135deg, #4338ca 0%, #6366f1 50%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-gold {
            background: linear-gradient(135deg, #fef08a 0%, #fde047 50%, #67e8f9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden text-slate-900 p-4">

    <!-- Decorative background glow orbs -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl mix-blend-multiply animate__animated animate__pulse animate__infinite animate__slower"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl mix-blend-multiply animate__animated animate__pulse animate__infinite animate__slower" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-indigo-100/80 animate__animated animate__fadeInUp">
        
        <!-- Header Banner with Vibrant Hero Gradient -->
        <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-cyan-500 p-8 text-center text-white relative overflow-hidden shadow-lg">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            
            <!-- 3D Brand Badge -->
            <div class="w-20 h-20 mx-auto mb-3 rounded-2xl overflow-hidden shadow-xl border-2 border-white/40 p-1 bg-white/20 backdrop-blur-md animate__animated animate__bounceIn">
                <img src="/payrollsystem/assets/img/system_brand_badge.jpg" alt="Logo" class="w-full h-full object-cover rounded-xl shadow-inner">
            </div>
            
            <h2 class="text-2xl font-extrabold tracking-tight font-outfit text-white drop-shadow-sm">Attendance & <span class="gradient-gold font-extrabold">Payroll</span></h2>
            <p class="text-[11px] uppercase tracking-[0.22em] text-cyan-200 font-extrabold mt-0.5">Management System</p>
            <p class="text-xs text-indigo-100 mt-2 font-medium">Please sign in to access your portal</p>
        </div>
        
        <div class="p-8">
            <?php if(isset($data['error'])): ?>
                <div class="bg-rose-50 text-rose-700 p-3.5 rounded-2xl text-xs font-semibold mb-6 border border-rose-200 flex items-center gap-2.5 animate__animated animate__shakeX shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-base text-rose-500"></i>
                    <span><?= htmlspecialchars($data['error']) ?></span>
                </div>
            <?php endif; ?>

            <form action="/payrollsystem/auth/login" method="POST" class="space-y-5" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                <div class="animate__animated animate__fadeInLeft" style="animation-delay: 0.1s;">
                    <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-slate-400 group-focus-within:text-indigo-600 transition-colors text-xs"></i>
                        </div>
                        <input type="email" name="email" id="email" class="pl-10 w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs font-medium transition-all shadow-sm placeholder-slate-400" placeholder="you@company.com" required>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                    <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 group-focus-within:text-indigo-600 transition-colors text-xs"></i>
                        </div>
                        <input type="password" name="password" id="password" class="pl-10 pr-11 w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-xs font-medium transition-all shadow-sm placeholder-slate-400" placeholder="••••••••" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                            <i class="fa-solid fa-eye text-xs" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs animate__animated animate__fadeInUp pt-1" style="animation-delay: 0.3s;">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 bg-slate-100 border-slate-300 text-indigo-600 focus:ring-indigo-500 rounded cursor-pointer transition-colors">
                        <label for="remember-me" class="ml-2 block text-slate-600 font-medium cursor-pointer text-xs">
                            Remember me
                        </label>
                    </div>
                    <div>
                        <a href="/payrollsystem/auth/forgot_password" class="font-bold text-indigo-600 hover:text-indigo-700 transition-colors text-xs">Forgot password?</a>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInUp pt-2" style="animation-delay: 0.4s;">
                    <button type="submit" id="submitBtn" class="relative w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/25 text-xs font-extrabold text-white bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] overflow-hidden group">
                        <span class="relative flex items-center gap-2 tracking-wider uppercase">
                            <span>SIGN IN TO WORKSPACE</span>
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<span class="relative flex items-center"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Signing in...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>
