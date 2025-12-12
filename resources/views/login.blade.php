<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - EduGlass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <style>
        /* Reuse the Search page's softer glass + gradient */
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .glass-input {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.6);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow:
                0 4px 20px rgba(59, 130, 246, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .glass-button {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }
        .glass-button:hover {
            background: rgba(255, 255, 255, 0.4);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-1px);
            box-shadow:
                0 6px 25px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .bg-gradient-animated {
            background: linear-gradient(-45deg, #e0f2fe, #f0f9ff, #f0fdf4, #fef7cd);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .floating-shapes {
            position: fixed; inset: 0;
            width: 100%; height: 100%;
            z-index: -1; overflow: hidden;
        }
        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(59,130,246,0.1), rgba(139,92,246,0.1));
            filter: blur(60px);
            animation: float 20s infinite linear;
        }
        .shape:nth-child(1) { width: 300px; height: 300px; top: 10%; left: 10%; animation-duration: 25s; }
        .shape:nth-child(2) { width: 400px; height: 400px; top: 60%; right: 10%; animation-duration: 30s; animation-delay: -5s; }
        .shape:nth-child(3) { width: 250px; height: 250px; bottom: 20%; left: 20%; animation-duration: 20s; animation-delay: -10s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25%      { transform: translate(100px, 50px) rotate(90deg); }
            50%      { transform: translate(50px, 100px) rotate(180deg); }
            75%      { transform: translate(-50px, 50px) rotate(270deg); }
        }

        /* Small utility for spinner */
        .loading-spinner {
            display: inline-block; width: 20px; height: 20px;
            border: 2px solid rgba(0,0,0,0.15);
            border-radius: 50%;
            border-top-color: rgba(0,0,0,0.5);
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body class="min-h-screen bg-gradient-animated text-slate-800 font-sans relative overflow-x-hidden">
    <!-- Floating Background Shapes (same vibe as Search) -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Softer Glass Login Card -->
            <div class="glass-card rounded-3xl p-8">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/60 flex items-center justify-center shadow-2xl border border-white/70">
                        <i data-feather="book-open" class="text-blue-600 w-10 h-10"></i>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl lg:text-4xl font-bold text-center mb-2">Welcome Back</h1>
                <p class="text-center text-slate-600 text-lg mb-8">Sign in to your learning portal</p>

                <!-- Error/Success -->
                <div id="messageContainer" class="mb-4">
                    @if($errors->any())
                        <div class="glass-card bg-red-500/20 border-red-400/30 rounded-xl p-4">
                            <div class="flex items-center text-red-700">
                                <i data-feather="alert-triangle" class="w-5 h-5 mr-3"></i>
                                <span class="text-sm">{{ $errors->first() }}</span>
                            </div>
                        </div>
                    @endif
                    @if(session('status'))
                        <div class="glass-card bg-green-500/20 border-green-400/30 rounded-xl p-4">
                            <div class="flex items-center text-green-700">
                                <i data-feather="check-circle" class="w-5 h-5 mr-3"></i>
                                <span class="text-sm">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="glass-card bg-green-500/20 border-green-400/30 rounded-xl p-4">
                            <div class="flex items-center text-green-700">
                                <i data-feather="check-circle" class="w-5 h-5 mr-3"></i>
                                <span class="text-sm">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Form -->
                <form id="loginForm" class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-feather="mail" class="text-slate-500 w-5 h-5"></i>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="glass-input w-full pl-12 pr-4 py-4 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                placeholder="you@example.com"
                                required
                                autocomplete="email"
                            />
                        </div>
                        <div id="emailError" class="text-red-600 text-xs mt-2 hidden"></div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-semibold text-slate-700">Password</label>
                            <!-- Removed Forgot Password link since route doesn't exist -->
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-feather="lock" class="text-slate-500 w-5 h-5"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="glass-input w-full pl-12 pr-12 py-4 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            />
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <i data-feather="eye" class="text-slate-500 w-5 h-5 hover:text-slate-700 transition-colors"></i>
                            </button>
                        </div>
                        <div id="passwordError" class="text-red-600 text-xs mt-2 hidden"></div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="h-5 w-5 rounded border-slate-300 bg-white/70 text-blue-600 focus:ring-blue-400"
                            {{ old('remember') ? 'checked' : '' }}
                        />
                        <label for="remember" class="ml-3 block text-sm text-slate-700">Remember me for 30 days</label>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-blue-500/90 hover:bg-blue-600 text-white py-4 px-4 rounded-xl transition-all duration-200 shadow-lg font-semibold text-lg flex items-center justify-center"
                    >
                        <span id="submitText">Sign In</span>
                        <div id="submitSpinner" class="loading-spinner ml-3 hidden"></div>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/50"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-4 bg-transparent text-sm text-slate-600">or continue with</span>
                    </div>
                </div>

                <!-- Social -->
                <div class="grid grid-cols-1 gap-3">
                    <button
                        type="button"
                        id="githubLogin"
                        class="glass-button flex items-center justify-center py-4 px-4 rounded-xl hover:scale-[1.01]"
                    >
                        <i data-feather="github" class="w-6 h-6 mr-3"></i>
                        <span class="font-semibold text-slate-800">Continue with GitHub</span>
                    </button>
                </div>

                <!-- Sign Up -->
                <div class="text-center mt-8">
                    <p class="text-sm text-slate-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">Sign up</a>
                    </p>
                </div>
            </div>

            <!-- Demo Credentials -->
            <div class="glass-card rounded-2xl p-4 mt-6 text-center">
                <p class="text-sm text-slate-600 mb-2">Demo Credentials</p>
                <div class="text-xs text-slate-600 space-y-1">
                    <div>Email: <span class="text-slate-800">demo@eduglass.com</span></div>
                    <div>Password: <span class="text-slate-800">demo123</span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const githubLoginBtn = document.getElementById('githubLogin');
        const messageContainer = document.getElementById('messageContainer');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');

        // Toggle Password
        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.setAttribute('data-feather', type === 'password' ? 'eye' : 'eye-off');
            feather.replace();
        });

        // Messages (softer colors)
        function showMessage(message, type = 'error') {
            const styles = type === 'error'
                ? { bg: 'bg-red-500/20', border: 'border-red-400/30', text: 'text-red-700', icon: 'alert-triangle' }
                : { bg: 'bg-green-500/20', border: 'border-green-400/30', text: 'text-green-700', icon: 'check-circle' };

            messageContainer.innerHTML = `
                <div class="glass-card ${styles.bg} ${styles.border} rounded-xl p-4">
                    <div class="flex items-center ${styles.text}">
                        <i data-feather="${styles.icon}" class="w-5 h-5 mr-3"></i>
                        <span class="text-sm">${message}</span>
                    </div>
                </div>
            `;
            feather.replace();
            if (type === 'success') setTimeout(() => messageContainer.innerHTML = '', 5000);
        }

        function clearErrors() {
            emailError.textContent = '';
            emailError.classList.add('hidden');
            passwordError.textContent = '';
            passwordError.classList.add('hidden');
        }

        function isValidEmail(email){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
        function isValidPassword(p){ return p.length >= 6; }

        // Client-side validation before form submission
        loginForm.addEventListener('submit', function (e) {
            clearErrors();

            const email = emailInput.value.trim();
            const password = passwordInput.value;

            let ok = true;
            if (!email) {
                emailError.textContent = 'Email is required';
                emailError.classList.remove('hidden');
                ok = false;
            }
            else if (!isValidEmail(email)) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.classList.remove('hidden');
                ok = false;
            }

            if (!password) {
                passwordError.textContent = 'Password is required';
                passwordError.classList.remove('hidden');
                ok = false;
            }
            else if (!isValidPassword(password)) {
                passwordError.textContent = 'Password must be at least 6 characters';
                passwordError.classList.remove('hidden');
                ok = false;
            }

            if (!ok) {
                e.preventDefault();
                return;
            }

            // If validation passes, show loading state but let the form submit normally
            submitText.textContent = 'Signing In...';
            submitSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        githubLoginBtn.addEventListener('click', function () {
            showMessage('GitHub authentication is not configured yet.', 'error');
        });

        // Auto-fill demo credentials for testing
        document.addEventListener('DOMContentLoaded', function () {
            // Only auto-fill if no existing values and we're in development
            if (!emailInput.value && !passwordInput.value) {
                emailInput.value = 'demo@eduglass.com';
                passwordInput.value = 'demo123';
            }
        });
    </script>
</body>
</html>
