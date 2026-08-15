<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Login' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        dark: '#1e293b',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            background: linear-gradient(135deg, #eef2f3 0%, #8e9eab 100%);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full blur-3xl mix-blend-multiply animate__animated animate__pulse animate__infinite animate__slower"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-300/30 rounded-full blur-3xl mix-blend-multiply animate__animated animate__pulse animate__infinite animate__slower" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-white/40 animate__animated animate__fadeInUp">
        <div class="bg-primary/90 p-8 text-center text-white backdrop-blur-md">
            <i class="fa-solid fa-building-user text-5xl mb-3 animate__animated animate__bounceIn" style="animation-delay: 0.5s;"></i>
            <h2 class="text-3xl font-bold tracking-tight">HRMS Portal</h2>
            <p class="text-sm text-indigo-100 mt-2 font-medium">Welcome back! Please sign in.</p>
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
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="email" name="email" id="email" class="pl-11 w-full px-4 py-3 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all focus:bg-white shadow-sm" placeholder="you@company.com" required>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="password" name="password" id="password" class="pl-11 pr-11 w-full px-4 py-3 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-all focus:bg-white shadow-sm" placeholder="••••••••" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer transition-colors">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="/payrollsystem/auth/forgot_password" class="font-medium text-primary hover:text-indigo-500 transition-colors">Forgot password?</a>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <button type="submit" id="submitBtn" class="relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-300 overflow-hidden group">
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                        <span class="relative flex items-center">Sign In <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i></span>
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
