<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 relative">
        <div class="absolute inset-0 flex justify-between items-center pointer-events-none">
            <div class="w-32 h-32 bg-blue-300 rounded-full opacity-30 blur-xl ml-16"></div>
            <div class="w-24 h-24 bg-blue-200 rounded-full opacity-30 blur-xl mr-16"></div>
        </div>
        <div class="w-full max-w-md z-10">
            <div class="bg-white rounded-2xl shadow-2xl px-8 py-10">
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-extrabold text-blue-700 mb-2">Masuk Akun</h1>
                    <p class="text-gray-500">Buat kamu yang sudah terdaftar, silakan masuk ke akunmu.</p>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <x-label for="email" :value="__('Alamat E-mail')" class="text-gray-700 font-semibold" />
                        <x-input id="email" class="block mt-1 w-full border border-blue-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" type="email" name="email" :value="old('email')" required autofocus placeholder="Alamat E-mail" />
                    </div>
                    <div>
                        <x-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                        <x-input id="password" class="block mt-1 w-full border border-blue-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
                    </div>
                    {{-- Captcha bisa ditambahkan di sini jika diperlukan --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" name="remember">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">Lupa password?</a>
                    </div>
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg text-lg">Masuk</button>
                </form>
                <div class="mt-6 text-center">
                    <span class="text-sm text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftarkan Dirimu</a></span>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
