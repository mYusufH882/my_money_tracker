<div class="p-2 sm:p-2">
    <!-- Header - RESPONSIVE -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Manajemen Kategori</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-600">Kelola kategori untuk transaksi Anda</p>
            </div>
            <div class="w-full sm:w-auto">
                <button 
                    wire:click="openCreateModal"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 sm:py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="sm:hidden">Tambah Kategori Baru</span>
                    <span class="hidden sm:inline">Tambah Kategori</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Kategori</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Digunakan</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $categoriesWithTransactions }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Belum Digunakan</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $categoriesWithoutTransactions }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter - RESPONSIVE -->
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow mb-6">
        <div class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4">
            <!-- Search Input -->
            <div class="flex-1 sm:max-w-md">
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:hidden">Cari Kategori</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        placeholder="Cari kategori..."
                        class="block w-full pl-9 sm:pl-10 pr-3 py-2.5 sm:py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Sort Buttons -->
            <div class="flex gap-2 sm:gap-3">
                <div class="text-xs sm:text-sm text-gray-500 self-center hidden sm:block">Urutkan:</div>
                
                <!-- Mobile: Dropdown for sorting -->
                <div class="sm:hidden flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select wire:change="sortBy($event.target.value.split(',')[0])" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="name,{{ $sortDirection }}" {{ $sortBy === 'name' ? 'selected' : '' }}>
                            Nama {{ $sortBy === 'name' ? ($sortDirection === 'asc' ? '(A-Z)' : '(Z-A)') : '' }}
                        </option>
                        <option value="transactions_count,{{ $sortDirection }}" {{ $sortBy === 'transactions_count' ? 'selected' : '' }}>
                            Transaksi {{ $sortBy === 'transactions_count' ? ($sortDirection === 'asc' ? '(Rendah-Tinggi)' : '(Tinggi-Rendah)') : '' }}
                        </option>
                    </select>
                </div>

                <!-- Desktop: Button for sorting -->
                <div class="hidden sm:flex sm:gap-2">
                    <button 
                        wire:click="sortBy('name')"
                        class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center gap-2 text-sm {{ $sortBy === 'name' ? 'bg-blue-50 border-blue-300 text-blue-700' : '' }}">
                        Nama
                        @if ($sortBy === 'name')
                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        @endif
                    </button>
                    <button 
                        wire:click="sortBy('transactions_count')"
                        class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center gap-2 text-sm {{ $sortBy === 'transactions_count' ? 'bg-blue-50 border-blue-300 text-blue-700' : '' }}">
                        Transaksi
                        @if ($sortBy === 'transactions_count')
                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories List - RESPONSIVE -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($categories->count() > 0)
            
            <!-- Mobile: Card Layout (Hidden on Desktop) -->
            <div class="block sm:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($categories as $category)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <!-- Category Icon -->
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    
                                    <!-- Category Name -->
                                    <div>
                                        <h3 class="font-medium text-gray-900 text-sm">{{ $category->name }}</h3>
                                    </div>
                                </div>
                                
                                <!-- Transaction Count Badge -->
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->transactions_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $category->transactions_count }} transaksi
                                    </span>
                                </div>
                            </div>

                            <!-- Card Meta Info -->
                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Dibuat {{ $category->created_at->format('d M Y') }}
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <button 
                                    wire:click="viewDetails({{ $category->id }})"
                                    class="flex items-center px-3 py-1.5 text-xs text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </button>
                                
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="openEditModal({{ $category->id }})"
                                        class="flex items-center px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="confirmDelete({{ $category->id }})"
                                        class="flex items-center px-3 py-1.5 text-xs {{ $category->transactions_count > 0 ? 'text-gray-400' : 'text-red-600 hover:bg-red-50' }} rounded-md transition-colors {{ $category->transactions_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        title="{{ $category->transactions_count > 0 ? 'Tidak dapat dihapus (masih memiliki transaksi)' : 'Hapus' }}"
                                        {{ $category->transactions_count > 0 ? 'disabled' : '' }}>
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Desktop: Table Layout (Hidden on Mobile) -->
            <div class="hidden sm:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Kategori
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Transaksi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dibuat
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $category->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->transactions_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $category->transactions_count }} transaksi
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $category->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button 
                                                wire:click="viewDetails({{ $category->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                wire:click="openEditModal({{ $category->id }})"
                                                class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button 
                                                wire:click="confirmDelete({{ $category->id }})"
                                                class="text-red-600 hover:text-red-800 {{ $category->transactions_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                title="{{ $category->transactions_count > 0 ? 'Tidak dapat dihapus (masih memiliki transaksi)' : 'Hapus' }}"
                                                {{ $category->transactions_count > 0 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        @else
            <!-- Empty State -->
            <div class="text-center py-12 px-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada kategori ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($search)
                        Coba ubah kata kunci pencarian Anda
                    @else
                        Mulai dengan menambahkan kategori baru
                    @endif
                </p>
                @if (!$search)
                    <div class="mt-6">
                        <button 
                            wire:click="openCreateModal"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Kategori
                        </button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="bg-white px-4 py-3 sm:px-6 border-t border-gray-200">
                <!-- Mobile Pagination -->
                <div class="flex justify-between items-center sm:hidden">
                    <div class="text-sm text-gray-700">
                        <span class="font-medium">{{ $categories->firstItem() }}</span>
                        -
                        <span class="font-medium">{{ $categories->lastItem() }}</span>
                        dari
                        <span class="font-medium">{{ $categories->total() }}</span>
                    </div>
                    <div class="flex space-x-2">
                        @if($categories->onFirstPage())
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded">Prev</span>
                        @else
                            <button wire:click="previousPage" class="px-3 py-1 text-sm text-blue-600 bg-blue-50 rounded hover:bg-blue-100">
                                Prev
                            </button>
                        @endif

                        @if($categories->hasMorePages())
                            <button wire:click="nextPage" class="px-3 py-1 text-sm text-blue-600 bg-blue-50 rounded hover:bg-blue-100">
                                Next
                            </button>
                        @else
                            <span class="px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded">Next</span>
                        @endif
                    </div>
                </div>

                <!-- Desktop Pagination -->
                <div class="hidden sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan
                            <span class="font-medium">{{ $categories->firstItem() }}</span>
                            sampai
                            <span class="font-medium">{{ $categories->lastItem() }}</span>
                            dari
                            <span class="font-medium">{{ $categories->total() }}</span>
                            kategori
                        </p>
                    </div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $isEditing ? 'Edit Kategori' : 'Tambah Kategori' }}
                    </h3>
                    <form wire:submit="save">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kategori
                            </label>
                            <input 
                                wire:model="name"
                                type="text" 
                                id="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan nama kategori"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-3">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md transition-colors duration-200">
                                Batal
                            </button>
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200">
                                {{ $isEditing ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Konfirmasi Hapus
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex justify-center gap-3">
                        <button 
                            wire:click="cancelDelete"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md transition-colors duration-200">
                            Batal
                        </button>
                        <button 
                            wire:click="deleteCategory"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category Details Modal -->
    @if ($selectedCategory)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        Detail Kategori: {{ $selectedCategory->name }}
                    </h3>
                    <button 
                        wire:click="closeDetails"
                        class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $categoryStats['total_transactions'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Transaksi</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($categoryStats['total_income'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-sm text-green-600">Total Pemasukan</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ number_format($categoryStats['total_expense'] ?? 0, 0, ',', '.') }}</div>
                        <div class="text-sm text-red-600">Total Pengeluaran</div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                @if (($categoryStats['recent_transactions'] ?? collect())->count() > 0)
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Transaksi Terbaru</h4>
                        <div class="bg-gray-50 rounded-lg max-h-64 overflow-y-auto">
                            @foreach ($categoryStats['recent_transactions'] as $transaction)
                                <div class="flex justify-between items-center p-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $transaction->deskripsi }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->tgl_transaksi->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}{{ number_format($transaction->nominal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Belum ada transaksi untuk kategori ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>