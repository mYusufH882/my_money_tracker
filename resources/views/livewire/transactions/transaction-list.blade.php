<div>
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Daftar Transaksi</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">Kelola semua transaksi keuangan Anda</p>
            </div>
            <div class="w-full sm:w-auto">
                <button 
                    wire:click="addTransaction"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="sm:hidden">Tambah Transaksi Baru</span>
                    <span class="hidden sm:inline">Tambah Transaksi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success/Info Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('info'))
        <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            {{ session('info') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow mb-6">
        <!-- Mobile: Stack filters vertically -->
        <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-1 md:grid-cols-6 sm:gap-4">
            
            <!-- Search - Full width on mobile, 2 cols on desktop -->
            <div class="sm:col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:hidden">Cari Transaksi</label>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari deskripsi..."
                    class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:hidden">Tipe</label>
                <select wire:model.live="filterType" class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:hidden">Kategori</label>
                <select wire:model.live="filterCategory" class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:hidden">Bulan</label>
                <select wire:model.live="filterMonth" class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:hidden">Tahun</label>
                <select wire:model.live="filterYear" class="w-full p-3 sm:p-4 text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Clear Filters Button - Full width on mobile -->
            <div class="flex items-end">
                <button 
                    wire:click="clearFilters"
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 sm:py-2 text-sm sm:text-base rounded-md transition-colors duration-200">
                    <span class="sm:hidden">Bersihkan Filter</span>
                    <span class="hidden sm:inline">Reset Filter</span>
                </button>
            </div>
        </div>

        <!-- Active Filters Indicator - Better mobile display -->
        <div class="mt-4 flex flex-wrap items-center gap-2">
            @php
                $activeFilters = collect([
                    $search ? 'Search' : null,
                    $filterType ? 'Tipe' : null,
                    $filterCategory ? 'Kategori' : null,
                    $filterMonth ? 'Bulan' : null,
                ])->filter()->count();
            @endphp
            
            @if($activeFilters > 0)
                <div class="flex items-center text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                    </svg>
                    {{ $activeFilters }} filter aktif
                </div>
            @endif
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($transactions->count() > 0)   
            <!-- Mobile: Card Layout (Hidden on Desktop) -->
            <div class="block sm:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($transactions as $index => $transaction)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <!-- Transaction Number -->
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        #{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                                    </span>

                                    <!-- Type Indicator -->
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="w-4 h-4 {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($transaction->tipe === 'pemasukan')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Amount -->
                                <div class="text-right">
                                    <div class="text-lg font-bold {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="space-y-2">
                                <!-- Description -->
                                <div>
                                    <p class="font-medium text-gray-900 text-sm leading-relaxed">{{ $transaction->deskripsi }}</p>
                                </div>
                                
                                <!-- Meta Info -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($transaction->tgl_transaksi)->format('d M Y') }}
                                    </span>
                                    
                                    @if($transaction->category)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $transaction->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-2 mt-3 pt-3 border-t border-gray-100">
                                <button 
                                    wire:click="editTransaction({{ $transaction->id }})"
                                    class="flex items-center px-3 py-1.5 text-xs text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <button 
                                    wire:click="deleteTransaction({{ $transaction->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus transaksi ini?"
                                    class="flex items-center px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Desktop: List Layout (Hidden on Mobile) -->
            <div class="hidden sm:block">
                <ul class="divide-y divide-gray-200">
                    @foreach($transactions as $index => $transaction)
                        <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <!-- Number -->
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Type Indicator -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                            <svg class="w-5 h-5 {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($transaction->tipe === 'pemasukan')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                                @endif
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Transaction Info -->
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-gray-900">{{ $transaction->deskripsi }}</p>
                                            @if($transaction->category)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $transaction->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($transaction->tgl_transaksi)->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount and Actions -->
                                <div class="flex items-center space-x-4">
                                    <!-- Amount -->
                                    <div class="text-right">
                                        <p class="text-lg font-semibold {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="editTransaction({{ $transaction->id }})"
                                            class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="deleteTransaction({{ $transaction->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus transaksi ini?"
                                            class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>   
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi pertama Anda.</p>
                <div class="mt-6">
                    <button 
                        wire:click="addTransaction"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Transaksi
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Include Transaction Form Modal -->
    <livewire:transactions.transaction-form />
</div>