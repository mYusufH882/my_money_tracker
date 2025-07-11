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

    <!-- Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('info'))
        <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            {{ session('info') }}
        </div>
    @endif

    <!-- Balance Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Current Balance -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 {{ $currentBalance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Saat Ini</p>
                    <p class="text-lg font-bold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $this->formattedCurrentBalance }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Income -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pemasukan</p>
                    <p class="text-lg font-bold text-blue-600">{{ $this->formattedTotalIncome }}</p>
                </div>
            </div>
        </div>

        <!-- Total Expense -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengeluaran</p>
                    <p class="text-lg font-bold text-orange-600">{{ $this->formattedTotalExpense }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction Count -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                    <p class="text-lg font-bold text-purple-600">{{ number_format($transactionCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Transaksi</label>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    id="search"
                    placeholder="Cari deskripsi..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Type Filter -->
            <div>
                <label for="filterType" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select wire:model.live="filterType" id="filterType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="filterCategory" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select wire:model.live="filterCategory" id="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label for="filterMonth" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select wire:model.live="filterMonth" id="filterMonth" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                    @endfor
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <label for="filterYear" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select wire:model.live="filterYear" id="filterYear" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <!-- Clear Filters Button -->
        <div class="mt-4 flex justify-end">
            <button 
                wire:click="clearFilters"
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Bersihkan Filter
            </button>
        </div>
    </div>

    <!-- Filtered Summary (if filters applied) -->
    @if($search || $filterType || $filterCategory || $filterMonth)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-blue-900">Hasil Filter</h3>
                    <div class="mt-2 grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700">Pemasukan: </span>
                            <span class="font-semibold text-green-600">{{ $this->formattedFilteredIncome }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700">Pengeluaran: </span>
                            <span class="font-semibold text-orange-600">{{ $this->formattedFilteredExpense }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700">Net: </span>
                            <span class="font-semibold {{ $this->filteredNet >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $this->formattedFilteredNet }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Transactions Display -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($transactions->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->tgl_transaksi->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate">{{ $transaction->deskripsi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $transaction->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaction->tipe === 'pemasukan' ? '💰 Pemasukan' : '💸 Pengeluaran' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}{{ $transaction->formatted_nominal }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button 
                                            wire:click="editTransaction({{ $transaction->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="deleteTransaction({{ $transaction->id }})"
                                            wire:confirm="Yakin ingin menghapus transaksi ini?"
                                            class="text-red-600 hover:text-red-900 transition-colors">
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

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4 p-4 text-xs">
                @foreach($transactions as $transaction)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Transaction Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <svg class="w-5 h-5 {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($transaction->tipe === 'pemasukan')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $transaction->tipe === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}{{ $transaction->formatted_nominal }}
                                </p>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="space-y-2">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $transaction->deskripsi }}</h3>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center space-x-4">
                                    <span>{{ $transaction->tgl_transaksi->format('d M Y') }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $transaction->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex items-center justify-end space-x-3">
                            <button 
                                wire:click="editTransaction({{ $transaction->id }})"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <button 
                                wire:click="deleteTransaction({{ $transaction->id }})"
                                wire:confirm="Yakin ingin menghapus transaksi ini?"
                                class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search || $filterType || $filterCategory || $filterMonth)
                        Tidak ada transaksi yang sesuai dengan filter yang dipilih.
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

    <!-- Include Transaction Form Modal -->
    <livewire:transactions.transaction-form />
</div>