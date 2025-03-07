<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Foodie') }}</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.places_api_key') }}&libraries=places"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    </head>
    <body class="bg-white" x-data="{ loginOpen: false, registerOpen: false }">
        <script>
            function showNotification(message, type = 'success') {
                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    className: "custom-toast",
                    style: {
                        background: type === 'success' ? "#006D5B" : "#EF4444",
                        padding: "8px 16px",
                        "font-size": "12px",
                        "min-width": "200px",
                        "max-width": "300px"
                    }
                }).showToast();
            }
        </script>

        @if(session('success'))
            <script>
                showNotification("{{ session('success') }}", 'success');
            </script>
                        @endif

        @if(session('error'))
            <script>
                showNotification("{{ session('error') }}", 'error');
            </script>
            @endif

        <!-- Hero Section with Navigation -->
        <div class="relative h-[400px]">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="{{ asset('images/food.jpg') }}" 
                     alt="Delicious Food" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/20"></div>
            </div>

            <!-- Navigation -->
            <nav class="relative z-50 pt-4 mb-8">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="/" class="text-2xl font-bold text-white hover:text-white/90 transition-colors" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5), 0 0 10px rgba(0,0,0,0.4)">Foodie</a>
                        </div>
                        <div class="flex items-center gap-8">
                            <div class="relative group">
                                <a href="{{ route('cart.history') }}" class="flex items-center gap-2 p-2 text-white hover:text-[#006D5B]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    @if($cartCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-green-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="flex items-center gap-4">
                                @auth
                                    <div class="flex items-center gap-6">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-white">{{ Auth::user()->name }}</span>
                                        </div>
                                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-white bg-black hover:bg-gray-900 px-8 py-2.5 rounded-[20px] transition-colors">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <button @click="loginOpen = true" class="text-white bg-black hover:bg-gray-900 px-8 py-2.5 rounded-[20px] transition-colors">
                                        Login
                                    </button>
                                    <button @click="registerOpen = true" class="text-white bg-[#006D5B] hover:bg-[#005c4d] px-8 py-2.5 rounded-[20px] transition-colors">
                                        Sign up
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Content -->
            <div class="relative max-w-3xl mx-auto px-4 h-full flex flex-col justify-center">
                <h1 class="text-4xl font-bold text-white mb-8" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5), 0 0 10px rgba(0,0,0,0.4)">Order delivery near you</h1>
                <div class="max-w-xl">
                    <form action="{{ route('search.restaurants') }}" method="GET" class="flex gap-2">
                        <div class="flex-1">
                            <input type="text" 
                                   id="addressInput"
                                   name="address"
                                   placeholder="Enter delivery address" 
                                   required
                                   class="w-full px-4 py-3 text-gray-900 rounded-lg bg-white border border-gray-300 focus:ring-1 focus:ring-black focus:border-black">
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                        </div>
                        <button type="submit" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors whitespace-nowrap">
                            Find Food
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Business Registration Section -->
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Restaurant Section -->
                <div class="relative overflow-hidden rounded-xl group">
                    <img src="{{ asset('images/business.jpg') }}" 
                         alt="Restaurant Business" 
                         class="w-full h-[300px] object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-6">
                        <h3 class="text-2xl font-bold text-white mb-2">Partner with Foodie</h3>
                        <p class="text-white/90 mb-4">Grow your business by reaching more customers. Join thousands of restaurants already delivering with us.</p>
                        <a href="#" class="inline-flex items-center text-white hover:text-[#006D5B] transition-colors">
                            Learn More
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                    </div>
                </div>

                <!-- Delivery Section -->
                <div class="relative overflow-hidden rounded-xl group">
                    <img src="{{ asset('images/delivery.png') }}" 
                         alt="Delivery Partner" 
                         class="w-full h-[300px] object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-6">
                        <h3 class="text-2xl font-bold text-white mb-2">Become a Delivery Partner</h3>
                        <p class="text-white/90 mb-4">Turn your spare time into earnings. Enjoy flexible hours and competitive pay as a delivery partner.</p>
                        <a href="#" class="inline-flex items-center text-white hover:text-[#006D5B] transition-colors">
                            Start Earning
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-50 border-t">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div>
                    <h4 class="font-semibold mb-6 text-lg">Areas near me</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <a href="/area/alakia" class="text-[#006D5B] hover:text-gray-600 transition-colors">Alakia</a>
                        <a href="/area/airport" class="text-[#006D5B] hover:text-gray-600 transition-colors">Airport</a>
                        <a href="/area/akobo" class="text-[#006D5B] hover:text-gray-600 transition-colors">Akobo</a>
                        <a href="/area/basorun" class="text-[#006D5B] hover:text-gray-600 transition-colors">Basorun</a>
                        <a href="/area/bodija" class="text-[#006D5B] hover:text-gray-600 transition-colors">Bodija</a>
                        <a href="/area/mokola" class="text-[#006D5B] hover:text-gray-600 transition-colors">Mokola</a>
                        <a href="/area/orogun" class="text-[#006D5B] hover:text-gray-600 transition-colors">Orogun</a>
                        <a href="/area/sango" class="text-[#006D5B] hover:text-gray-600 transition-colors">Sango</a>
                        <a href="/area/eleyele" class="text-[#006D5B] hover:text-gray-600 transition-colors">Eleyele</a>
                        <a href="/area/ologuneru" class="text-[#006D5B] hover:text-gray-600 transition-colors">Ologuneru</a>
                        <a href="/area/moniya" class="text-[#006D5B] hover:text-gray-600 transition-colors">Moniya</a>
                        <a href="/area/poly-ibadan" class="text-[#006D5B] hover:text-gray-600 transition-colors">Poly-ibadan</a>
                        <a href="/area/agodi-gate" class="text-[#006D5B] hover:text-gray-600 transition-colors">Agodi-gate</a>
                        <a href="/area/university-of-ibadan" class="text-[#006D5B] hover:text-gray-600 transition-colors">University of Ibadan</a>
                        <a href="/area/uch" class="text-[#006D5B] hover:text-gray-600 transition-colors">UCH</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Login Modal -->
        <div x-show="loginOpen" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-data="{ isSubmitting: false }"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
           <div class="flex items-center justify-center min-h-screen px-4">
               <!-- Backdrop -->
               <div class="fixed inset-0 bg-black/50" @click="loginOpen = false"></div>
               
               <!-- Modal -->
               <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">
                   
                   <!-- Close button -->
                   <button @click="loginOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                       <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                       </svg>
                   </button>

                   <!-- Login Form -->
                   <div class="text-center mb-8">
                       <h2 class="text-2xl font-bold text-[#006D5B]">Welcome back</h2>
                   </div>
                   
                   <form method="POST" 
                       action="{{ route('login') }}" 
                       @submit.prevent="
                           isSubmitting = true;
                           $el.submit();
                           $nextTick(() => {
                               if ($el.querySelector('.text-red-500')) {
                                   showNotification('Invalid credentials', 'error');
                                   isSubmitting = false;
                               }
                           });"
                   >
                       @csrf
                       <div>
                           <x-input-label for="email" :value="__('Email')" />
                           <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                           <x-input-error :messages="$errors->get('email')" class="mt-2" />
                       </div>

                       <div class="mt-4">
                           <x-input-label for="password" :value="__('Password')" />
                           <x-password-input id="password" name="password" required autocomplete="current-password" />
                           <x-input-error :messages="$errors->get('password')" class="mt-2" />
                       </div>

                       <div class="mt-4 flex items-center justify-between">
                           <label class="inline-flex items-center">
                               <input type="checkbox" class="rounded border-gray-300 text-[#006D5B]" name="remember">
                               <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                           </label>
                           @if (Route::has('password.request'))
                               <a class="text-sm text-[#006D5B] hover:underline" href="{{ route('password.request') }}">
                                   {{ __('Forgot password?') }}
                               </a>
        @endif
                       </div>

                       <div class="mt-6">
                           <button 
                               type="submit" 
                               class="w-full bg-[#006D5B] text-white px-4 py-2 rounded-lg hover:bg-[#005c4d] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                               :disabled="isSubmitting"
                           >
                               <span x-show="!isSubmitting">{{ __('Log in') }}</span>
                               <span x-show="isSubmitting">Logging in...</span>
                           </button>
                       </div>
                   </form>

                   <div class="mt-6">
                       <div class="relative">
                           <div class="absolute inset-0 flex items-center">
                               <div class="w-full border-t border-gray-300"></div>
                           </div>
                           <div class="relative flex justify-center text-sm">
                               <span class="px-2 bg-white text-gray-500">Or continue with</span>
                           </div>
                       </div>

                       <a href="{{ route('google.login') }}" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                           <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                               <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                               <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                               <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                               <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                           </svg>
                           Sign in with Google
                       </a>
                   </div>
               </div>
           </div>
       </div>

       <!-- Register Modal -->
       <div x-show="registerOpen" 
            class="fixed inset-0 z-50 overflow-y-auto" 
            x-data="{ 
                isSubmitting: false,
                password: '',
                passwordConfirm: '',
                get passwordsMatch() {
                    return this.password === this.passwordConfirm;
                }
            }"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            style="display: none;">
           <div class="flex items-center justify-center min-h-screen px-4">
               <!-- Backdrop -->
               <div class="fixed inset-0 bg-black/50" @click="registerOpen = false"></div>
               
               <!-- Modal -->
               <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">
                   
                   <!-- Close button -->
                   <button @click="registerOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                       <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                       </svg>
                   </button>

                   <!-- Register Form -->
                   <div class="text-center mb-8">
                       <h2 class="text-2xl font-bold text-[#006D5B]">Create an account</h2>
                   </div>
                   
                   <form method="POST" 
                       action="{{ route('register') }}"
                       @submit.prevent="
                           isSubmitting = true;
                           $el.submit();
                           $nextTick(() => {
                               if ($el.querySelector('.text-red-500')) {
                                   showNotification('Please check your input', 'error');
                                   isSubmitting = false;
                               }
                           });"
                   >
                       @csrf
                       <div>
                           <x-input-label for="name" :value="__('Name')" />
                           <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                           <x-input-error :messages="$errors->get('name')" class="mt-2" />
                       </div>

                       <div class="mt-4">
                           <x-input-label for="email" :value="__('Email')" />
                           <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                           <x-input-error :messages="$errors->get('email')" class="mt-2" />
                       </div>

                       <div class="mt-4">
                           <x-input-label for="password" :value="__('Password')" />
                           <x-password-input 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               x-model="password"
                           />
                           <x-input-error :messages="$errors->get('password')" class="mt-2" />
                       </div>

                       <div class="mt-4">
                           <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                           <x-password-input 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               x-model="passwordConfirm"
                           />
                           <p 
                               x-show="password && passwordConfirm && !passwordsMatch" 
                               class="text-red-500 text-sm mt-1"
                           >
                               Passwords do not match
                           </p>
                           <p 
                               x-show="password && passwordConfirm && passwordsMatch" 
                               class="text-green-500 text-sm mt-1"
                           >
                               Passwords match
                           </p>
                       </div>

                       <div class="mt-6">
                           <button 
                               type="submit" 
                               class="w-full bg-[#006D5B] text-white px-4 py-2 rounded-lg hover:bg-[#005c4d] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                               :disabled="(!passwordsMatch && password && passwordConfirm) || isSubmitting"
                           >
                               <span x-show="!isSubmitting">{{ __('Register') }}</span>
                               <span x-show="isSubmitting">Creating account...</span>
                           </button>
                       </div>
                   </form>

                   <div class="mt-6">
                       <div class="relative">
                           <div class="absolute inset-0 flex items-center">
                               <div class="w-full border-t border-gray-300"></div>
                           </div>
                           <div class="relative flex justify-center text-sm">
                               <span class="px-2 bg-white text-gray-500">Or continue with</span>
                           </div>
                       </div>

                       <a href="{{ route('google.login') }}" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                           <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                               <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                               <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                               <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                               <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                           </svg>
                           Sign up with Google
                       </a>
                   </div>
               </div>
           </div>
       </div>
    </body>
</html>
