<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register - EduGlass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <style>
        /* Keep all the existing CSS styles */
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

        .loading-spinner {
            display: inline-block; width: 20px; height: 20px;
            border: 2px solid rgba(0,0,0,0.15);
            border-radius: 50%;
            border-top-color: rgba(0,0,0,0.5);
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .org-option {
            transition: all 0.3s ease;
        }
        .org-option.selected {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.4);
        }

        /* Responsive grid for additional fields */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-animated text-slate-800 font-sans relative overflow-x-hidden">
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4 py-8">
        <div class="w-full max-w-2xl">
            <div class="glass-card rounded-3xl p-8">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/60 flex items-center justify-center shadow-2xl border border-white/70">
                        <i data-feather="user-plus" class="text-blue-600 w-10 h-10"></i>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl lg:text-4xl font-bold text-center mb-2">Create Account</h1>
                <p class="text-center text-slate-600 text-lg mb-8">Join your learning community</p>

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
                <form id="registerForm" class="space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- REMOVED: Hidden role field -->

                    <!-- Personal Information Grid -->
                    <div class="form-grid">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="user" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="John Doe"
                                    required
                                    autocomplete="name"
                                />
                            </div>
                            <div id="nameError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Username *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="at-sign" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="johndoe"
                                    required
                                    autocomplete="username"
                                />
                            </div>
                            <div id="usernameError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>
                    </div>

                    <!-- Contact Information Grid -->
                    <div class="form-grid">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="mail" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="you@example.com"
                                    required
                                    autocomplete="email"
                                />
                            </div>
                            <div id="emailError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>

                        <!-- IC Number -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">IC Number *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="credit-card" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="text"
                                    id="icno"
                                    name="icno"
                                    value="{{ old('icno') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="e.g., 900101-01-1234"
                                    required
                                    maxlength="20"
                                />
                            </div>
                            <div id="icnoError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>
                    </div>

                    <!-- Phone and Address Grid -->
                    <div class="form-grid">
                        <!-- Telephone Number -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="phone" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="tel"
                                    id="telno"
                                    name="telno"
                                    value="{{ old('telno') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="e.g., 012-3456789"
                                    required
                                    maxlength="15"
                                />
                            </div>
                            <div id="telnoError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">State *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="map-pin" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="text"
                                    id="state"
                                    name="state"
                                    value="{{ old('state') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="e.g., Kuala Lumpur"
                                    required
                                    maxlength="100"
                                />
                            </div>
                            <div id="stateError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>
                    </div>

                    <!-- Address Grid -->
                    <div>
                        <!-- Address -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Address *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="home" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <textarea
                                    id="address"
                                    name="address"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="Full address"
                                    rows="2"
                                    required
                                    maxlength="500"
                                >{{ old('address') }}</textarea>
                            </div>
                            <div id="addressError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>

                        <!-- Postcode -->
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Postcode *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="hash" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="text"
                                    id="postcode"
                                    name="postcode"
                                    value="{{ old('postcode') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="e.g., 50000"
                                    required
                                    maxlength="10"
                                />
                            </div>
                            <div id="postcodeError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>
                    </div>

                    <!-- Password Grid -->
                    <div class="form-grid">
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="lock" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="glass-input w-full pl-12 pr-12 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i data-feather="eye" class="text-slate-500 w-5 h-5 hover:text-slate-700 transition-colors"></i>
                                </button>
                            </div>
                            <div id="passwordError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="lock" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>
                            <div id="passwordConfirmationError" class="text-red-600 text-xs mt-2 hidden"></div>
                        </div>
                    </div>

                    <!-- Organization Option -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Join Organization (Optional)</label>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <label class="org-option glass-button p-4 rounded-xl text-center cursor-pointer transition-all" id="skipOrgOption">
                                <input type="radio" name="organization_option" value="skip" class="hidden" checked>
                                <i data-feather="user" class="w-6 h-6 mx-auto mb-2 text-slate-600"></i>
                                <span class="font-semibold text-slate-800">Skip</span>
                            </label>
                            <label class="org-option glass-button p-4 rounded-xl text-center cursor-pointer transition-all" id="joinOrgOption">
                                <input type="radio" name="organization_option" value="join" class="hidden">
                                <i data-feather="users" class="w-6 h-6 mx-auto mb-2 text-slate-600"></i>
                                <span class="font-semibold text-slate-800">Join</span>
                            </label>
                        </div>

                        <!-- Organization Code -->
                        <div id="joinOrgSection" class="hidden">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Organization Code</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-feather="hash" class="text-slate-500 w-5 h-5"></i>
                                </div>
                                <input
                                    type="text"
                                    id="organization_code"
                                    name="organization_code"
                                    value="{{ old('organization_code') }}"
                                    class="glass-input w-full pl-12 pr-4 py-3 rounded-xl focus:outline-none placeholder-slate-500 text-slate-800"
                                    placeholder="Enter 6-digit code"
                                    maxlength="6"
                                />
                            </div>
                            <div id="organizationCodeError" class="text-red-600 text-xs mt-2 hidden"></div>
                            <p class="text-xs text-slate-600 mt-2">Get this code from your organization admin</p>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            class="h-5 w-5 rounded border-slate-300 bg-white/70 text-blue-600 focus:ring-blue-400 mt-1"
                            required
                        />
                        <label for="terms" class="ml-3 block text-sm text-slate-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">Terms of Service</a> and <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">Privacy Policy</a>
                        </label>
                    </div>
                    <div id="termsError" class="text-red-600 text-xs mt-2 hidden"></div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-blue-500/90 hover:bg-blue-600 text-white py-4 px-4 rounded-xl transition-all duration-200 shadow-lg font-semibold text-lg flex items-center justify-center"
                    >
                        <span id="submitText">Create Account</span>
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
                        id="githubRegister"
                        class="glass-button flex items-center justify-center py-4 px-4 rounded-xl hover:scale-[1.01]"
                    >
                        <i data-feather="github" class="w-6 h-6 mr-3"></i>
                        <span class="font-semibold text-slate-800">Continue with GitHub</span>
                    </button>
                </div>

                <!-- Sign In -->
                <div class="text-center mt-8">
                    <p class="text-sm text-slate-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">Sign in</a>
                    </p>
                </div>
            </div>

            <!-- Demo Info -->
            <div class="glass-card rounded-2xl p-4 mt-6 text-center">
                <p class="text-sm text-slate-600 mb-2">Demo Information</p>
                <div class="text-xs text-slate-600 space-y-1">
                    <div>Skip organization to start independently</div>
                    <div>Or join with code: <span class="text-slate-800">DEMO123</span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        const registerForm = document.getElementById('registerForm');
        const nameInput = document.getElementById('name');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const icnoInput = document.getElementById('icno');
        const telnoInput = document.getElementById('telno');
        const addressInput = document.getElementById('address');
        const postcodeInput = document.getElementById('postcode');
        const stateInput = document.getElementById('state');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const termsCheckbox = document.getElementById('terms');

        // Organization elements
        const skipOrgOption = document.getElementById('skipOrgOption');
        const joinOrgOption = document.getElementById('joinOrgOption');
        const joinOrgSection = document.getElementById('joinOrgSection');
        const organizationCodeInput = document.getElementById('organization_code');

        // Organization option handling
        skipOrgOption.addEventListener('click', function() {
            document.querySelector('input[name="organization_option"][value="skip"]').checked = true;
            joinOrgSection.classList.add('hidden');
            skipOrgOption.classList.add('selected', 'bg-blue-50', 'border-blue-200');
            joinOrgOption.classList.remove('selected', 'bg-blue-50', 'border-blue-200');
        });

        joinOrgOption.addEventListener('click', function() {
            document.querySelector('input[name="organization_option"][value="join"]').checked = true;
            joinOrgSection.classList.remove('hidden');
            joinOrgOption.classList.add('selected', 'bg-blue-50', 'border-blue-200');
            skipOrgOption.classList.remove('selected', 'bg-blue-50', 'border-blue-200');
        });

        // Initialize organization option
        skipOrgOption.click();

        // Toggle Password
        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.setAttribute('data-feather', type === 'password' ? 'eye' : 'eye-off');
            feather.replace();
        });

        // Helper functions
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            return false;
        }

        function clearErrors() {
            document.querySelectorAll('[id$="Error"]').forEach(error => {
                error.textContent = '';
                error.classList.add('hidden');
            });
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidPassword(p) {
            return p.length >= 6;
        }

        function isValidOrganizationCode(code) {
            return code.length === 6 && /^[A-Z0-9]+$/.test(code);
        }

        function isValidICNo(icno) {
            // Malaysian IC format: XXXXXX-XX-XXXX or XXXXXXXXXXXX
            const cleaned = icno.replace(/[^\d]/g, '');
            return cleaned.length >= 12;
        }

        function isValidPostcode(postcode) {
            return /^\d{4,6}$/.test(postcode.replace(/\D/g, ''));
        }

        // Auto-format inputs
        if (icnoInput) {
            icnoInput.addEventListener('input', function() {
                let value = this.value.replace(/[^\d-]/g, '');
                if (value.length > 6 && !value.includes('-')) {
                    value = value.slice(0, 6) + '-' + value.slice(6);
                }
                if (value.length > 9 && value.split('-').length < 3) {
                    value = value.slice(0, 9) + '-' + value.slice(9);
                }
                this.value = value.slice(0, 14); // Max length for IC
            });
        }

        if (telnoInput) {
            telnoInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d-+]/g, '');
            });
        }

        if (postcodeInput) {
            postcodeInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        }

        if (organizationCodeInput) {
            organizationCodeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        }

        // Client-side validation
        registerForm.addEventListener('submit', function (e) {
            clearErrors();

            const name = nameInput.value.trim();
            const username = usernameInput.value.trim();
            const email = emailInput.value.trim();
            const icno = icnoInput.value.trim();
            const telno = telnoInput.value.trim();
            const address = addressInput.value.trim();
            const postcode = postcodeInput.value.trim();
            const state = stateInput.value.trim();
            const password = passwordInput.value;
            const passwordConfirmation = passwordConfirmationInput.value;
            const organizationOption = document.querySelector('input[name="organization_option"]:checked')?.value;
            const organizationCode = organizationCodeInput?.value.trim();
            const terms = termsCheckbox.checked;

            let ok = true;

            // Name validation
            if (!name) {
                showError('nameError', 'Full name is required');
                ok = false;
            }

            // Username validation
            if (!username) {
                showError('usernameError', 'Username is required');
                ok = false;
            }

            // Email validation
            if (!email) {
                showError('emailError', 'Email is required');
                ok = false;
            } else if (!isValidEmail(email)) {
                showError('emailError', 'Please enter a valid email address');
                ok = false;
            }

            // IC Number validation
            if (!icno) {
                showError('icnoError', 'IC number is required');
                ok = false;
            } else if (!isValidICNo(icno)) {
                showError('icnoError', 'Please enter a valid IC number (minimum 12 digits)');
                ok = false;
            }

            // Telephone validation
            if (!telno) {
                showError('telnoError', 'Phone number is required');
                ok = false;
            }

            // Address validation
            if (!address) {
                showError('addressError', 'Address is required');
                ok = false;
            }

            // Postcode validation
            if (!postcode) {
                showError('postcodeError', 'Postcode is required');
                ok = false;
            } else if (!isValidPostcode(postcode)) {
                showError('postcodeError', 'Please enter a valid postcode (4-6 digits)');
                ok = false;
            }

            // State validation
            if (!state) {
                showError('stateError', 'State is required');
                ok = false;
            }

            // Password validation
            if (!password) {
                showError('passwordError', 'Password is required');
                ok = false;
            } else if (!isValidPassword(password)) {
                showError('passwordError', 'Password must be at least 6 characters');
                ok = false;
            }

            // Password confirmation
            if (password !== passwordConfirmation) {
                showError('passwordConfirmationError', 'Passwords do not match');
                ok = false;
            }

            // Organization validation
            if (organizationOption === 'join') {
                if (!organizationCode) {
                    showError('organizationCodeError', 'Organization code is required when joining');
                    ok = false;
                } else if (!isValidOrganizationCode(organizationCode)) {
                    showError('organizationCodeError', 'Organization code must be 6 characters (letters and numbers)');
                    ok = false;
                }
            }

            // Terms validation
            if (!terms) {
                showError('termsError', 'You must agree to the terms and conditions');
                ok = false;
            }

            if (!ok) {
                e.preventDefault();
                return;
            }

            // Show loading state
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            const submitBtn = document.getElementById('submitBtn');

            submitText.textContent = 'Creating Account...';
            submitSpinner.classList.remove('hidden');
            submitBtn.disabled = true;
        });

        // GitHub registration placeholder
        document.getElementById('githubRegister').addEventListener('click', function () {
            alert('GitHub authentication is not configured yet.');
        });
    </script>
</body>
</html>
