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
                        primary: '#7c3aed',
                        secondary: '#06b6d4',
                        dark: '#0f0a1e',
                        darker: '#070512',
                        surface: '#160e29',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            background: #070512;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .gradient-text {
            background: linear-gradient(135deg, #c4b5fd 0%, #a78bfa 50%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden text-gray-100 p-4">

    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full blur-3xl mix-blend-screen animate__animated animate__pulse animate__infinite animate__slower"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-secondary/20 rounded-full blur-3xl mix-blend-screen animate__animated animate__pulse animate__infinite animate__slower" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md bg-surface/90 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-violet-500/30 animate__animated animate__fadeInUp">
        <div class="bg-gradient-to-b from-violet-900/60 to-surface/80 p-8 text-center border-b border-violet-800/30">
            <!-- 3D Brand Badge -->
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl overflow-hidden shadow-xl border border-violet-400/40 p-1 bg-darker/80 animate__animated animate__bounceIn">
                <img src="/payrollsystem/assets/img/system_brand_badge.jpg" alt="Logo" class="w-full h-full object-cover rounded-xl">
            </div>
            <h2 class="text-2xl font-extrabold tracking-tight gradient-text font-outfit">Attendance & Payroll</h2>
            <p class="text-xs uppercase tracking-[0.2em] text-cyan-300 font-bold mt-0.5">Management System</p>
            <p class="text-xs text-gray-400 mt-2 font-medium">Please sign in to access your portal</p>
        </div>
        
        <div class="p-8">
            <?php if(isset($data['error'])): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-200 flex items-center animate__animated animate__shakeX">
                    <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
                    <span class="font-medium"><?= htmlspecialchars($data['error']) ?></span>
                </div>
            <?php endif; ?>

            <form action="/payrollsystem/auth/login" method="POST" class="space-y-6" id="loginForm">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

                <div class="animate__animated animate__fadeInLeft" style="animation-delay: 0.2s;">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-500 group-focus-within:text-violet-400 transition-colors"></i>
                        </div>
                        <input type="email" name="email" id="email" class="pl-11 w-full px-4 py-3 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 sm:text-sm transition-all shadow-inner placeholder-gray-500" placeholder="you@company.com" required>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-violet-300 mb-1.5">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-500 group-focus-within:text-violet-400 transition-colors"></i>
                        </div>
                        <input type="password" name="password" id="password" class="pl-11 pr-11 w-full px-4 py-3 bg-darker/60 border border-violet-700/30 text-white rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-400 sm:text-sm transition-all shadow-inner placeholder-gray-500" placeholder="••••••••" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-violet-300 focus:outline-none transition-colors">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 bg-darker border-violet-700/40 text-violet-600 focus:ring-violet-500 rounded cursor-pointer transition-colors">
                        <label for="remember-me" class="ml-2 block text-gray-400 cursor-pointer">
                            Remember me
                        </label>
                    </div>
                    <div>
                        <a href="/payrollsystem/auth/forgot_password" class="font-bold text-cyan-400 hover:text-cyan-300 transition-colors">Forgot password?</a>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <button type="submit" id="submitBtn" class="relative w-full flex justify-center py-3.5 px-4 rounded-xl shadow-xl shadow-violet-600/30 text-sm font-extrabold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] overflow-hidden group">
                        <span class="relative flex items-center gap-2">
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
