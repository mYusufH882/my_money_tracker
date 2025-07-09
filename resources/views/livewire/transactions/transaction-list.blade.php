<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Transaksi</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola semua transaksi keuangan Anda</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button 
                    wire:click="addTransaction"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Transaksi
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
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari deskripsi..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Type Filter -->
            <div>
                <select wire:model.live="filterType" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <select wire:model.live="filterCategory" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <select wire:model.live="filterMonth" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                    @endfor
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <select wire:model.live="filterYear" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <!-- Clear Filters -->
        <div class="mt-4 flex items-center justify-between">
            <button 
                wire:click="clearFilters" 
                class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Bersihkan Filter
            </button>
            
            <!-- Filter Summary -->
            <div class="text-sm text-gray-500">
                @php
                    $activeFilters = collect([
                        $search ? 'Search' : null,
                        $filterType ? 'Tipe' : null,
                        $filterCategory ? 'Kategori' : null,
                        $filterMonth ? 'Bulan' : null,
                    ])->filter()->count();
                @endphp
                
                @if($activeFilters > 0)
                    {{ $activeFilters }} filter aktif
                @endif
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($transactions->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Type Indicator -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="w-5 h-5 {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($transaction->tipe === 'pemasukan')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            @endif
                                        </svg>
                                    </div>
                                </div>

                                <!-- Transaction Info -->
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->deskripsi }}</p>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($transaction->tipe) }}
                                        </span>
                                    </div>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <span>{{ $transaction->category->name ?? 'Tanpa Kategori' }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $transaction->tgl_transaksi->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount & Actions -->
                            <div class="flex items-center space-x-4">
                                <!-- Amount -->
                                <div class="text-right">
                                    <p class="text-sm font-medium {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}
                                        Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <button 
                                        wire:click="editTransaction({{ $transaction->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        wire:click="deleteTransaction({{ $transaction->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus transaksi ini?"
                                        class="text-red-600 hover:text-red-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search || $filterType || $filterCategory || $filterMonth)
                        Tidak ada transaksi yang sesuai dengan filter.
                    @else
                        Mulai dengan menambahkan transaksi pertama Anda.
                    @endif
                </p>
                <div class="mt-6">
                    <button 
                        wire:click="addTransaction"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Transaksi
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>