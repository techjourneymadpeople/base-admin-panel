<x-layouts.admin title="Catat Saran & Masukan Baru">
    <x-admin.breadcrumb 
        title="Catat Masukan Baru" 
        :items="[
            'Konten' => '',
            'Saran & Masukan' => route('admin.feedbacks.index'),
            'Tambah Baru' => ''
        ]" 
    />

    <form action="{{ route('admin.feedbacks.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Top Action Bar -->
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <a 
                href="{{ route('admin.feedbacks.index') }}" 
                class="px-4 py-2 rounded-2xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-2 shadow-2xs transition-all"
            >
                <i data-lucide="arrow-left" class="w-4 h-4 text-stone-400"></i>
                <span>Kembali ke Daftar</span>
            </a>

            <button 
                type="submit" 
                class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
            >
                <i data-lucide="check" class="w-4 h-4 text-[#cca06e]"></i>
                <span>Simpan Masukan</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Sender & Content Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-stone-100">
                        <div class="w-8 h-8 rounded-xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <h4 class="text-sm font-extrabold text-[#1d3e35]">Informasi Pengirim</h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Pengirim -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Nama Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                placeholder="Contoh: Budi Santoso"
                                required 
                                class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                            >
                            @error('name')
                                <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Pengirim -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Email Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                placeholder="budi@example.com"
                                required 
                                class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                            >
                            @error('email')
                                <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon / WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Nomor WhatsApp / Telepon
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                placeholder="081234567890"
                                class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                            >
                            @error('phone')
                                <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Masukan -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Jenis Masukan <span class="text-red-500">*</span>
                            </label>
                            <select name="type" required class="w-full rounded-xl p-2.5 text-xs font-bold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                                @foreach($types as $key => $meta)
                                    <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                        {{ $meta['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Subjek / Topik -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Subjek / Topik Masukan
                        </label>
                        <input 
                            type="text" 
                            name="subject" 
                            value="{{ old('subject') }}" 
                            placeholder="Contoh: Saran Penambahan Fitur Pembayaran QRIS"
                            class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                        >
                        @error('subject')
                            <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Isi Pesan Masukan -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Isi Pesan Masukan Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="message" 
                            rows="6" 
                            required 
                            placeholder="Tuliskan aspirasi, kritik, atau saran perbaikan secara detail..."
                            class="w-full rounded-xl p-3 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Status & Tindak Lanjut -->
            <div class="space-y-6">
                <div class="p-6 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-stone-100">
                        <div class="w-8 h-8 rounded-xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                            <i data-lucide="settings-2" class="w-4 h-4"></i>
                        </div>
                        <h4 class="text-sm font-extrabold text-[#1d3e35]">Pengaturan & Status</h4>
                    </div>

                    <!-- Status Tindak Lanjut -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Status Awal <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required class="w-full rounded-xl p-2.5 text-xs font-bold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                            @foreach($statuses as $key => $meta)
                                <option value="{{ $key }}" {{ old('status', 'unread') === $key ? 'selected' : '' }}>
                                    {{ $meta['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rating Skor Kepuasan (Opsional) -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Skor Kepuasan Layanan (1 - 5)
                        </label>
                        <select name="rating" class="w-full rounded-xl p-2.5 text-xs font-semibold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                            <option value="">Tidak ada skor (Opsional)</option>
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>★★★★★ (5 - Sangat Puas)</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>★★★★☆ (4 - Puas)</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>★★★☆☆ (3 - Cukup)</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ (2 - Kurang)</option>
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ (1 - Sangat Kecewa)</option>
                        </select>
                    </div>

                    <!-- Prioritas Bintang -->
                    <div class="flex items-center gap-2 py-1">
                        <input 
                            type="checkbox" 
                            id="is_starred" 
                            name="is_starred" 
                            value="1" 
                            {{ old('is_starred') ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-[#31725e] focus:ring-[#31725e] border-stone-300"
                        >
                        <label for="is_starred" class="text-xs font-bold text-stone-700 cursor-pointer">
                            Tandai Sebagai Prioritas (★)
                        </label>
                    </div>

                    <!-- Catatan Internal Admin -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Catatan Internal / Solusi</label>
                        <textarea 
                            name="admin_notes" 
                            rows="4" 
                            placeholder="Catatan tindak lanjut internal staf/admin..."
                            class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                        >{{ old('admin_notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
