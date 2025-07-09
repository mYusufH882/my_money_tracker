<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="login" class="space-y-6">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
            <input 
                wire:model="email" 
                type="email" 
                id="email"
                class="mt-1 block w-full p-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                placeholder="Masukkan email Anda"
                autocomplete="email">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
            <input 
                wire:model="password" 
                type="password" 
                id="password"
                class="mt-1 block w-full p-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
                placeholder="Masukkan password Anda"
                autocomplete="current-password">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="remember" type="checkbox" id="remember" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
            <label for="remember" class="ml-2 text-sm text-gray-900">Ingat saya</label>
        </div>

        <!-- Submit -->
        <button 
            type="submit" 
            class="w-full p-4 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            wire:loading.attr="disabled">
            
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Loading...</span>
        </button>

        <!-- Register Link -->
        <div class="text-center">
            <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                Belum punya akun? Daftar di sini
            </a>
        </div>
    </form>
</div>