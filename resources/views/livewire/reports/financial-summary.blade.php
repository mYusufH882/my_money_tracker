<div class="p-2 sm:p-2">
    <!-- Header - RESPONSIVE -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-600">Ringkasan dan analisis keuangan Anda</p>
            </div>
            
            <!-- Export Buttons - Mobile: Full width, Desktop: Inline -->
            <div class="w-full sm:w-auto">
                <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                    <button 
                        wire:click="exportExcel"
                        class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2.5 sm:py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel
                    </button>
                    <button 
                        wire:click="exportPdf"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2.5 sm:py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 text-xs sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Info Messages -->
    @if (session()->has('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            {{ session('info') }}
        </div>
    @endif

    <!-- Filters - RESPONSIVE -->
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow mb-4 sm:mb-6">
        <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-1 md:grid-cols-4 sm:gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select wire:model.live="selectedMonth" class="w-full p-2.5 sm:p-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($months as $month)
                        <option value="{{ $month['value'] }}">{{ $month['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select wire:model.live="selectedYear" class="w-full p-2.5 sm:p-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select wire:model.live="selectedCategory" class="w-full p-2.5 sm:p-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button 
                    wire:click="resetFilters"
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 sm:py-2 text-sm rounded-md transition-colors duration-200">
                    <span class="sm:hidden">Bersihkan Filter</span>
                    <span class="hidden sm:inline">Reset Filter</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards - Already responsive -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <!-- Total Income -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Pemasukan</p>
                    <p class="text-lg sm:text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Expense -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Pengeluaran</p>
                    <p class="text-lg sm:text-2xl font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 {{ $balance >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Saldo</p>
                    <p class="text-lg sm:text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - RESPONSIVE -->
    <div class="space-y-6 lg:space-y-0 lg:grid lg:grid-cols-2 lg:gap-6 mb-4 sm:mb-6">
        <!-- Monthly Trend Chart -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Tren 6 Bulan Terakhir</h3>
            <div class="h-48 sm:h-64" wire:ignore>
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Breakdown per Kategori</h3>
            <div class="max-h-48 sm:max-h-64 overflow-y-auto">
                <div class="space-y-3 sm:space-y-4">
                    @forelse($categoryBreakdown as $category)
                        <!-- Mobile: Stacked layout -->
                        <div class="sm:hidden p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-medium text-gray-900 text-sm">{{ $category['name'] }}</div>
                                <div class="text-right">
                                    <div class="font-medium text-sm {{ $category['net'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $category['net'] >= 0 ? '+' : '' }}{{ number_format($category['net'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>{{ $category['transactions'] }} transaksi</div>
                                <div class="flex justify-between">
                                    <span class="text-green-600">Masuk: +{{ number_format($category['income'], 0, ',', '.') }}</span>
                                    <span class="text-red-600">Keluar: -{{ number_format($category['expense'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop: Horizontal layout -->
                        <div class="hidden sm:flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-medium text-gray-900">{{ $category['name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $category['transactions'] }} transaksi</div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium {{ $category['net'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $category['net'] >= 0 ? '+' : '' }}{{ number_format($category['net'], 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="text-green-600">+{{ number_format($category['income'], 0, ',', '.') }}</span> / 
                                    <span class="text-red-600">-{{ number_format($category['expense'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-sm">Tidak ada data untuk periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions - RESPONSIVE -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
        </div>
        
        @if($recentTransactions->count() > 0)
            <!-- Mobile: Card Layout -->
            <div class="sm:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($recentTransactions as $transaction)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <!-- Type indicator -->
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="w-3 h-3 {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($transaction->tipe === 'pemasukan')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 text-sm">{{ $transaction->deskripsi }}</div>
                                        <div class="text-xs text-gray-500">{{ $transaction->tgl_transaksi->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="font-medium text-sm {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $transaction->category->name ?? 'Tanpa Kategori' }}
                                </span>
                                <span class="capitalize {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->tipe }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Desktop: Table Layout -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->tgl_transaksi->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $transaction->deskripsi }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->category->name ?? 'Tanpa Kategori' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->tipe === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($transaction->tipe) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $transaction->tipe === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->tipe === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12 px-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500">Tidak ada transaksi untuk periode yang dipilih.</p>
            </div>
        @endif
    </div>

    <!-- Loading Modal -->
    <div 
        x-data="{ showLoading: false, loadingMessage: '' }"
        x-on:show-loading.window="showLoading = true; loadingMessage = $event.detail"
        x-show="showLoading"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
        style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Memproses Export</h3>
                <p class="text-sm text-gray-500 mb-4" x-text="loadingMessage"></p>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full animate-pulse" style="width: 75%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Mohon tunggu sebentar...</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    let monthlyChart = null;

    function createChart() {
        // Generate 6 months data if not available from backend
        const monthlyData = @json($monthlyData ?? []);
        
        // Fallback data if empty
        const chartData = monthlyData.length > 0 ? monthlyData : [
            {label: 'Jan', income: 0, expense: 0},
            {label: 'Feb', income: 0, expense: 0},
            {label: 'Mar', income: 0, expense: 0},
            {label: 'Apr', income: 0, expense: 0},
            {label: 'May', income: 0, expense: 0},
            {label: 'Jun', income: 0, expense: 0}
        ];
        
        const ctx = document.getElementById('monthlyChart');
        if (ctx) {
            if (monthlyChart) {
                monthlyChart.destroy();
            }

            monthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.label || item.month),
                    datasets: [{
                        label: 'Pemasukan',
                        data: chartData.map(item => item.income || 0),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        pointRadius: 4
                    }, {
                        label: 'Pengeluaran',
                        data: chartData.map(item => item.expense || 0),
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.3,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', createChart);
    document.addEventListener('livewire:updated', () => setTimeout(createChart, 100));
</script>