<div class="p-6">
    <!-- Month Navigation -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">{{ Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <button 
                wire:click="changeMonth('prev')"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
                class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            
            <div wire:loading.remove wire:target="changeMonth" class="text-center text-sm text-gray-500">
                {{ Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
            </div>
            
            <div wire:loading wire:target="changeMonth" class="text-center text-sm text-blue-600 animate-pulse">
                Loading...
            </div>
            
            <button 
                wire:click="changeMonth('next')"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
                class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Loading State -->
        <div wire:loading wire:target="changeMonth" class="col-span-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @for($i = 0; $i < 3; $i++)
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="animate-pulse">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-lg"></div>
                                <div class="ml-4 flex-1">
                                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                                    <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Actual Content -->
        <div wire:loading.remove wire:target="changeMonth" class="col-span-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $balance >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Saldo</p>
                            <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions (Full Width) -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Transaksi Terbaru - {{ Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction['tipe'] === 'pemasukan' ? 'bg-green-100' : 'bg-red-100' }}">
                                <svg class="w-5 h-5 {{ $transaction['tipe'] === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($transaction['tipe'] === 'pemasukan')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $transaction['deskripsi'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ Carbon\Carbon::parse($transaction['tgl_transaksi'])->format('d/m/Y') }} • 
                                    {{ $transaction['category']['name'] ?? 'Tanpa Kategori' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold {{ $transaction['tipe'] === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction['tipe'] === 'pemasukan' ? '+' : '-' }}{{ number_format($transaction['nominal'], 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-500 capitalize">{{ $transaction['tipe'] }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-lg font-medium text-gray-900 mb-2">Belum ada transaksi</p>
                        <p class="text-gray-500">Transaksi untuk {{ Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }} akan ditampilkan di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart & Daily Breakdown (Side by Side) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tren 6 Bulan Terakhir</h3>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="monthlyChart" wire:ignore></canvas>
                </div>
            </div>
        </div>

        <!-- Daily Breakdown -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Breakdown Hari Ini</h3>
                <p class="text-sm text-gray-500">{{ Carbon\Carbon::today()->format('d F Y') }}</p>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-64 overflow-y-auto">
                    @forelse($dailyBreakdown as $breakdown)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-medium text-gray-900">{{ $breakdown['category_name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $breakdown['total_transactions'] }} transaksi</div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium {{ $breakdown['net'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $breakdown['net'] >= 0 ? '+' : '' }}{{ number_format($breakdown['net'], 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($breakdown['income'] > 0)
                                        <span class="text-green-600">+{{ number_format($breakdown['income'], 0, ',', '.') }}</span>
                                    @endif
                                    @if($breakdown['expense'] > 0)
                                        @if($breakdown['income'] > 0) / @endif
                                        <span class="text-red-600">-{{ number_format($breakdown['expense'], 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-900 mb-2">Belum ada riwayat transaksi</p>
                            <p class="text-gray-500">Transaksi hari ini akan ditampilkan di sini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Chart.js Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        let monthlyChart = null;

        function createChart() {
            const monthlyData = @js($monthlyChart);
            
            const ctx = document.getElementById('monthlyChart');
            if (ctx) {
                if (monthlyChart) {
                    monthlyChart.destroy();
                }

                monthlyChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(d => d.month),
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: monthlyData.map(d => d.income),
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.1,
                                fill: true
                            },
                            {
                                label: 'Pengeluaran',
                                data: monthlyData.map(d => d.expense),
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.1,
                                fill: true
                            }
                        ]
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
                            },
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            createChart();
        });

        // Safe Livewire listeners
        function initLivewireListeners() {
            if (typeof Livewire !== 'undefined') {
                Livewire.on('monthChanged', () => {
                    setTimeout(() => {
                        createChart();
                    }, 100);
                });
            } else {
                setTimeout(initLivewireListeners, 100);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initLivewireListeners();
        });
    </script>
@endpush