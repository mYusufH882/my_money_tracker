<div>
    <form wire:submit.prevent="register" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-900">Nama Lengkap</label>
            <input 
                wire:model="name" 
                type="text" 
                id="name"
                class="mt-1 block w-full p-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                placeholder="Masukkan nama lengkap">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
            <input 
                wire:model="email" 
                type="email" 
                id="email"
                class="mt-1 block w-full p-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                placeholder="Masukkan email Anda">
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
                placeholder="Minimal 8 karakter">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-900">Konfirmasi Password</label>
            <input 
                wire:model="password_confirmation" 
                type="password" 
                id="password_confirmation"
                class="mt-1 block w-full p-4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Ulangi password Anda">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button 
            type="submit" 
            class="w-full p-4 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            wire:loading.attr="disabled">
            
            <span wire:loading.remove>Daftar</span>
            <span wire:loading>Loading...</span>
        </button>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                Sudah punya akun? Masuk di sini
            </a>
        </div>
    </form>
</div>