<div>
    <!-- Header dengan periode -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Ringkasan keuangan Anda</p>
            </div>
            
            <!-- Month Navigator -->
            <div class="mt-4 sm:mt-0 flex items-center space-x-2">
                <button wire:click="changeMonth('prev')" 
                        wire:loading.attr="disabled"
                        class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                
                <div class="text-center min-w-[120px]">
                    <div class="text-lg font-semibold text-gray-900">
                        <span wire:loading.remove wire:target="changeMonth">
                            {{ \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}
                        </span>
                        <span wire:loading wire:target="changeMonth" class="text-gray-400">
                            Loading...
                        </span>
                    </div>
                </div>
                
                <button wire:click="changeMonth('next')" 
                        wire:loading.attr="disabled"
                        class="p-2 text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <!-- Total Income -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.94" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pemasukan</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expense -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.511l-5.511-3.182" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pengeluaran</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $balance >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Saldo</dt>
                            <dd class="text-lg font-medium {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Tren 6 Bulan Terakhir
                </h3>
                
                @if(count($monthlyChart) > 0)
                    <div class="h-64">
                        <canvas id="monthlyChart" wire:ignore></canvas>
                    </div>
                @else
                    <div class="h-64 flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 7.996 21 8.625 21h6.75c.629 0 1.125-.504 1.125-1.125v-6.75c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v6.75C21 20.496 20.496 21 19.875 21H4.125C3.504 21 3 20.496 3 19.875v-6.75z" />
                            </svg>
                            <p class="mt-2">Belum ada data transaksi</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Transaksi Terbaru
                </h3>
                
                @if(count($recentTransactions) > 0)
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($recentTransactions as $index => $transaction)
                                <li>
                                    <div class="relative pb-8">
                                        @if($index !== count($recentTransactions) - 1)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full {{ $transaction['tipe'] === 'pemasukan' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        @if($transaction['tipe'] === 'pemasukan')
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                        @endif
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900 font-medium">{{ $transaction['deskripsi'] }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $transaction['category']['name'] ?? 'Tanpa Kategori' }} • 
                                                        {{ \Carbon\Carbon::parse($transaction['tgl_transaksi'])->format('d M Y') }}
                                                    </p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm">
                                                    <span class="font-medium {{ $transaction['tipe'] === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $transaction['tipe'] === 'pemasukan' ? '+' : '-' }}
                                                        Rp {{ number_format($transaction['nominal'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('transactions') }}" 
                           wire:navigate
                           class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Lihat Semua Transaksi
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi pertama Anda.</p>
                        <div class="mt-6">
                            <a href="{{ route('transactions') }}" 
                               wire:navigate
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Transaksi
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(count($categoryBreakdown) > 0)
        <!-- Category Breakdown -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Breakdown Kategori Bulan Ini
                </h3>
                
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemasukan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categoryBreakdown as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $category['name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                        @if($category['income'] > 0)
                                            Rp {{ number_format($category['income'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        @if($category['expense'] > 0)
                                            Rp {{ number_format($category['expense'], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $category['net'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $category['net'] >= 0 ? '+' : '' }}Rp {{ number_format($category['net'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $category['transactions'] }} transaksi
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let monthlyChartInstance = null;

    function initChart() {
        const ctx = document.getElementById('monthlyChart');
        if (!ctx) return;
        
        // Destroy existing chart
        if (monthlyChartInstance) {
            monthlyChartInstance.destroy();
            monthlyChartInstance = null;
        }
        
        const chartData = @json($monthlyChart);
        
        if (!chartData || chartData.length === 0) return;
        
        monthlyChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.month),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: chartData.map(item => item.income),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Pengeluaran',
                        data: chartData.map(item => item.expense),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
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
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Initialize chart when page loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initChart, 100);
    });

    // Listen for Livewire updates to refresh chart
    document.addEventListener('livewire:updated', function() {
        setTimeout(initChart, 100);
    });

    // Listen for month change event
    window.addEventListener('monthChanged', function() {
        setTimeout(initChart, 200);
    });
</script>
@endpush