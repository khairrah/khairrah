<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl overflow-hidden">
            <!-- Top - Tools Illustration -->
            <div class="flex items-center justify-center bg-white p-6 sm:p-8 h-48 sm:h-56">
                <svg class="w-full h-full" viewBox="0 0 500 300" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
                    <!-- Background circle -->
                    <circle cx="250" cy="150" r="120" fill="#d4e8e8" opacity="0.2"/>
                    
                    <!-- Crane -->
                    <line x1="250" y1="20" x2="250" y2="60" stroke="#2c3a48" stroke-width="3"/>
                    <polygon points="220,60 280,60 277,65 223,65" fill="#2c3a48"/>
                    <line x1="235" y1="65" x2="180" y2="85" stroke="#2c3a48" stroke-width="2"/>
                    <line x1="265" y1="65" x2="320" y2="85" stroke="#2c3a48" stroke-width="2"/>
                    <line x1="250" y1="65" x2="250" y2="110" stroke="#2c3a48" stroke-width="2"/>
                    <rect x="244" y="110" width="12" height="12" fill="#2c3a48"/>
                    <line x1="250" y1="122" x2="250" y2="145" stroke="#505050" stroke-width="2"/>
                    
                    <!-- Monitor/Screen -->
                    <rect x="130" y="70" width="130" height="85" fill="#2c3a48" rx="5" stroke="#1a1f28" stroke-width="2"/>
                    <rect x="137" y="77" width="116" height="71" fill="#b8e6e0" stroke="#2c3a48" stroke-width="1"/>
                    <circle cx="195" cy="112" r="25" fill="none" stroke="#2c3a48" stroke-width="1.5"/>
                    <line x1="195" y1="95" x2="195" y2="129" stroke="#2c3a48" stroke-width="1"/>
                    <line x1="178" y1="112" x2="212" y2="112" stroke="#2c3a48" stroke-width="1"/>
                    
                    <!-- Wrench -->
                    <g transform="translate(285, 95)">
                        <rect x="0" y="0" width="50" height="10" fill="#505050" rx="5"/>
                        <circle cx="10" cy="5" r="5" fill="#505050"/>
                        <ellipse cx="42" cy="5" rx="6" ry="8" fill="#505050"/>
                        <path d="M 46 -3 L 56 10 L 49 13 Z" fill="#505050"/>
                    </g>
                    
                    <!-- Screwdriver -->
                    <g transform="translate(265, 65)">
                        <rect x="0" y="0" width="40" height="6" fill="#c0c0c0" rx="3"/>
                        <rect x="36" y="-10" width="4" height="24" fill="#c0c0c0"/>
                        <polygon points="38,-10 42,-10 40,0" fill="#505050"/>
                    </g>
                    
                    <!-- Ladder -->
                    <g transform="translate(70, 140)">
                        <line x1="0" y1="0" x2="0" y2="70" stroke="#505050" stroke-width="2"/>
                        <line x1="15" y1="0" x2="15" y2="70" stroke="#505050" stroke-width="2"/>
                        <line x1="0" y1="10" x2="15" y2="10" stroke="#505050" stroke-width="1"/>
                        <line x1="0" y1="23" x2="15" y2="23" stroke="#505050" stroke-width="1"/>
                        <line x1="0" y1="36" x2="15" y2="36" stroke="#505050" stroke-width="1"/>
                        <line x1="0" y1="49" x2="15" y2="49" stroke="#505050" stroke-width="1"/>
                        <line x1="0" y1="62" x2="15" y2="62" stroke="#505050" stroke-width="1"/>
                    </g>
                    
                    <!-- Scaffolding -->
                    <rect x="65" y="135" width="50" height="80" fill="none" stroke="#2c3a48" stroke-width="1"/>
                    <line x1="70" y1="155" x2="115" y2="155" stroke="#2c3a48" stroke-width="1"/>
                    <line x1="70" y1="175" x2="115" y2="175" stroke="#2c3a48" stroke-width="1"/>
                    
                    <!-- Work table -->
                    <rect x="155" y="190" width="120" height="8" fill="#8b7355" stroke="#2c3a48" stroke-width="1"/>
                    <line x1="163" y1="198" x2="163" y2="210" stroke="#505050" stroke-width="1.5"/>
                    <line x1="267" y1="198" x2="267" y2="210" stroke="#505050" stroke-width="1.5"/>
                    
                    <!-- Tool boxes -->
                    <rect x="172" y="180" width="22" height="12" fill="#e67e22" stroke="#2c3a48" stroke-width="0.8"/>
                    <rect x="202" y="180" width="22" height="12" fill="#c0c0c0" stroke="#2c3a48" stroke-width="0.8"/>
                    <rect x="232" y="180" width="22" height="12" fill="#505050" stroke="#2c3a48" stroke-width="0.8"/>
                    
                    <!-- Warning sign -->
                    <g transform="translate(350, 155)">
                        <rect x="0" y="0" width="35" height="50" fill="white" stroke="#2c3a48" stroke-width="1"/>
                        <rect x="0" y="0" width="18" height="50" fill="#505050"/>
                        <rect x="18" y="0" width="17" height="50" fill="white"/>
                    </g>
                </svg>
            </div>

            <!-- Bottom - Form -->
            <div class="px-6 py-8 sm:px-12 sm:py-10">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 uppercase">LOGIN</h1>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email" class="w-full pl-14 pr-4 py-3 rounded-full bg-teal-500 text-white placeholder-white placeholder-opacity-80 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-0 text-base" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }">
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Password" class="w-full pl-14 pr-12 py-3 rounded-full bg-teal-500 text-white placeholder-white placeholder-opacity-80 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-0 text-base" />
                            
                            <!-- Eye Button -->
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white/70 hover:text-white transition focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center pt-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                            <span class="ms-2 text-sm text-gray-700">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition mt-8 text-lg">
                        LOGIN
                    </button>
                </form>

                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-700">
                        New User? 
                        <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-700 font-bold uppercase">Create a account</a>
                    </p>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a class="text-sm text-gray-600 hover:text-gray-900 transition" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
