<div>
    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:transition>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-100 bg-opacity-50 transition-opacity" wire:click="closeModal"></div>
            
            <!-- Modal Content -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto transform transition-all">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ $isEdit ? 'Edit Transaksi' : 'Tambah Transaksi' }}
                            </h3>
                            <button 
                                wire:click="closeModal" 
                                type="button" 
                                class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="px-6 py-6">
                        <form wire:submit.prevent="save" class="space-y-5">
                            <!-- Tanggal -->
                            <div>
                                <label for="tgl_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Transaksi
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
                                    Deskripsi
                                </label>
                                <input 
                                    wire:model="deskripsi" 
                                    type="text" 
                                    id="deskripsi"
                                    placeholder="Contoh: Gaji bulanan, Belanja groceries"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('deskripsi') border-red-500 @enderror">
                                @error('deskripsi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipe -->
                            <div>
                                <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipe Transaksi
                                </label>
                                <select 
                                    wire:model="tipe" 
                                    id="tipe"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('tipe') border-red-500 @enderror">
                                    <option value="">Pilih Tipe</option>
                                    <option value="pemasukan">💰 Pemasukan</option>
                                    <option value="pengeluaran">💸 Pengeluaran</option>
                                </select>
                                @error('tipe')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori
                                </label>
                                <select 
                                    wire:model="kategori_id" 
                                    id="kategori_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('kategori_id') border-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nominal -->
                            <div>
                                <label for="nominal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nominal
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                        <span class="text-gray-500 font-medium">Rp</span>
                                    </div>
                                    <input 
                                        wire:model="nominal" 
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
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
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
                                    {{ $isEdit ? 'Update' : 'Simpan' }}
                                </span>
                                <span wire:loading wire:target="save" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>