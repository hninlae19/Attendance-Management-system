<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#4f46e5', dark: '#1e293b' } } }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>body { background: linear-gradient(135deg, #eef2f3 0%, #8e9eab 100%); }</style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden">
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary/20 rounded-full blur-3xl mix-blend-multiply animate__animated animate__pulse animate__infinite animate__slower"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-300/30 rounded-full blur-3xl mix-blend-multiply animate__animated animate__pulse animate__infinite animate__slower" style="animation-delay: 2s;"></div>

    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-white/40 animate__animated animate__fadeInUp">
        <div class="bg-primary/90 p-8 text-center text-white backdrop-blur-md">
            <i class="fa-solid fa-key text-5xl mb-3 animate__animated animate__bounceIn" style="animation-delay: 0.5s;"></i>
            <h2 class="text-3xl font-bold tracking-tight">Forgot Password</h2>
            <p class="text-sm text-indigo-100 mt-2 font-medium">Enter your email to request a reset.</p>
        </div>
        
        <div class="p-8">
            <?php if(isset($data['error'])): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-200 flex items-center animate__animated animate__shakeX">
                    <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
                    <span class="font-medium"><?= htmlspecialchars($data['error']) ?></span>
                </div>
            <?php endif; ?>
            <?php if(isset($data['success'])): ?>
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm mb-6 border border-emerald-200 flex items-center">
                    <i class="fa-solid fa-circle-check mr-3 text-lg"></i>
                    <span class="font-medium"><?= htmlspecialchars($data['success']) ?></span>
                </div>
            <?php endif; ?>

            <form action="/payrollsystem/auth/forgot_password_submit" method="POST" class="space-y-6">
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

                <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <button type="submit" class="relative w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-300 overflow-hidden group">
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                        <span class="relative flex items-center">Request Password Reset <i class="fa-solid fa-paper-plane ml-2"></i></span>
                    </button>
                </div>
                
                <div class="text-center mt-4 animate__animated animate__fadeIn" style="animation-delay: 0.5s;">
                    <a href="/payrollsystem/auth/login" class="text-sm font-medium text-primary hover:text-indigo-500 transition-colors">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
