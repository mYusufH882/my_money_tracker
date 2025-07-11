<div>
    @if($showModal)
        <!-- Full Screen Backdrop - Covers everything including sidebar -->
        <div class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm transition-all duration-300 ease-in-out z-[9999]" wire:click="closeModal"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-[10000] overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all duration-300 ease-in-out sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-white">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $isEdit ? 'Edit Transaksi' : 'Tambah Transaksi' }}
                            </h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Current Balance Info -->
                    <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-700 font-medium">Saldo Saat Ini:</span>
                            <span class="text-blue-900 font-semibold">{{ $this->formattedCurrentBalance }}</span>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="px-6 py-6 bg-white">
                        <form class="space-y-5">
                            <!-- Tanggal Transaksi -->
                            <div>
                                <label for="tgl_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    wire:model="tgl_transaksi" 
                                    type="date" 
                                    id="tgl_transaksi"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('tgl_transaksi') border-red-500 @enderror">
                                @error('tgl_transaksi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    wire:model="deskripsi" 
                                    type="text" 
                                    id="deskripsi"
                                    placeholder="Contoh: Beli makan siang"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('deskripsi') border-red-500 @enderror">
                                @error('deskripsi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipe Transaksi -->
                            <div>
                                <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe Transaksi <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    wire:model.live="tipe" 
                                    id="tipe"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('tipe') border-red-500 @enderror">
                                    <option value="">Pilih tipe transaksi</option>
                                    <option value="pemasukan">💰 Pemasukan</option>
                                    <option value="pengeluaran">💸 Pengeluaran</option>
                                </select>
                                @error('tipe')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nominal -->
                            <div>
                                <label for="nominal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nominal <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm font-medium">Rp</span>
                                    </div>
                                    <input 
                                        wire:model.live="nominal" 
                                        type="number" 
                                        step="0.01"
                                        min="0"
                                        id="nominal"
                                        placeholder="50000"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('nominal') border-red-500 @enderror">
                                </div>
                                @error('nominal')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Balance Preview -->
                            @if($showBalancePreview)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Preview Saldo:</span>
                                        <span class="text-sm font-semibold {{ $previewBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $this->formattedPreviewBalance }}
                                        </span>
                                    </div>
                                    
                                    @if($balanceWarning)
                                        <div class="flex items-center mt-2">
                                            <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm text-yellow-700">{{ $balanceWarning }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Kategori -->
                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    wire:model="kategori_id" 
                                    id="kategori_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('kategori_id') border-red-500 @enderror">
                                    <option value="">Pilih kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex gap-3 justify-end">
                            <button 
                                wire:click="closeModal" 
                                type="button" 
                                class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-200 transition-colors">
                                Batal
                            </button>
                            <button 
                                wire:click="save" 
                                type="button" 
                                wire:loading.attr="disabled"
                                class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <span wire:loading.remove wire:target="save">
                                    {{ $isEdit ? 'Update Transaksi' : 'Simpan Transaksi' }}
                                </span>
                                <span wire:loading wire:target="save" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional CSS to ensure body scroll is disabled -->
        <style>
            body { overflow: hidden !important; }
        </style>

        <!-- JavaScript for body scroll control -->
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('disable-body-scroll', () => {
                    document.body.style.overflow = 'hidden';
                    document.documentElement.style.overflow = 'hidden';
                });
                
                Livewire.on('enable-body-scroll', () => {
                    document.body.style.overflow = '';
                    document.documentElement.style.overflow = '';
                });
            });
        </script>
    @endif
</div>