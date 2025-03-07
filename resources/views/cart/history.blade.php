<x-cart-layout>
    <div x-data="{ loginOpen: false }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-2xl font-bold mb-6">Your Cart</h2>

                        @if(count($items) > 0)
                            <div class="space-y-4">
                                @foreach($items as $item)
                                    <div class="flex items-center justify-between border-b pb-4">
                                        <div class="flex items-center gap-4">
                                            @if($item['image'])
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                            @endif
                                            <div>
                                                <h3 class="font-semibold">{{ $item['name'] }}</h3>
                                                <p class="text-gray-600">Quantity: {{ $item['quantity'] }}</p>
                                                <p class="text-[#006D5B]">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                            </div>
                                        </div>
                                        <button @click="removeFromCart({{ $item['id'] }})" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach

                                <div class="mt-6 flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold">Total: ₦{{ number_format(collect($items)->sum(function($item) { 
                                            return $item['price'] * $item['quantity'];
                                        }), 2) }}</p>
                                    </div>
                                    @auth
                                        <form action="{{ route('checkout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-[#006D5B] text-white px-6 py-2 rounded-lg hover:bg-[#005c4d]">
                                                Proceed to Checkout
                                            </button>
                                        </form>
                                    @else
                                        <button @click="loginOpen = true" class="bg-[#006D5B] text-white px-6 py-2 rounded-lg hover:bg-[#005c4d]">
                                            Login to Checkout
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <p class="text-gray-500 mb-4">Your cart is empty</p>
                                <a href="/" class="text-[#006D5B] hover:underline">Continue Shopping</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Modal -->
        <div x-show="loginOpen" 
             class="fixed inset-0 z-50 overflow-y-auto" 
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
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6">
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
                    
                    <form method="POST" action="{{ route('login') }}">
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
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full bg-[#006D5B] text-white px-4 py-2 rounded-lg hover:bg-[#005c4d] transition-colors">
                                {{ __('Log in') }}
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
    </div>

    <script>
    function removeFromCart(id) {
        fetch(`/cart/remove/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        });
    }
    </script>
</x-cart-layout> 