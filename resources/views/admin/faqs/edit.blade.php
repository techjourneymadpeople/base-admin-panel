<x-layouts.admin title="Edit FAQ: {{ $faq->question }}">
    <x-admin.breadcrumb 
        title="Edit FAQ" 
        :items="[
            'Konten' => '',
            'Kelola FAQ' => route('admin.faqs.index'),
            'Edit FAQ' => ''
        ]" 
    />

    <div class="space-y-6">
        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Top Action Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('admin.faqs.index') }}" 
                        class="p-2.5 rounded-2xl bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors"
                        title="Kembali ke Daftar FAQ"
                    >
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </a>
                    <div>
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Perbarui Pertanyaan</span>
                        <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">{{ $faq->question }}</h3>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.faqs.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full">
                <!-- ==================================================== -->
                <!-- LEFT COLUMN: QUESTION & ANSWER (Main Content)        -->
                <!-- ==================================================== -->
                <div class="md:col-span-8 space-y-6">
                    <x-admin.card 
                        title="Konten Pertanyaan & Jawaban" 
                        subtitle="Perbarui pertanyaan dan jawaban FAQ."
                    >
                        <div class="space-y-6">
                            <!-- Pertanyaan -->
                            <div class="space-y-2">
                                <label for="question" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Pertanyaan FAQ <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="question"
                                    id="question"
                                    placeholder="Masukkan pertanyaan FAQ..."
                                    value="{{ old('question', $faq->question) }}"
                                    class="w-full rounded-2xl p-4 text-base font-extrabold text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none shadow-2xs"
                                    required
                                />
                                @error('question')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jawaban FAQ -->
                            <div class="space-y-2">
                                <label for="answer" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Jawaban Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    name="answer"
                                    id="answer"
                                    rows="8"
                                    placeholder="Tuliskan jawaban yang lengkap, jelas, dan mudah dipahami..."
                                    class="w-full rounded-2xl p-4 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                                    required
                                >{{ old('answer', $faq->answer) }}</textarea>
                                @error('answer')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-admin.card>
                </div>

                <!-- ==================================================== -->
                <!-- RIGHT COLUMN: SIDEBAR (Category, Order, Status)      -->
                <!-- ==================================================== -->
                <div class="md:col-span-4 space-y-4">
                    <!-- Card Pengaturan FAQ -->
                    <x-admin.card 
                        title="Pengaturan FAQ" 
                        subtitle="Atur kategori topik, urutan tampil, dan status aktif."
                        icon="settings"
                    >
                        <div class="space-y-5">
                            <!-- Kategori (TomSelect with create support) -->
                            <div class="space-y-1.5">
                                <label for="category_select" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Kategori Topik
                                </label>
                                <select 
                                    name="category" 
                                    id="category_select" 
                                    class="w-full"
                                    placeholder="Pilih atau ketik kategori baru..."
                                >
                                    <option value="">-- Pilih / Ketik Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $faq->category) == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                    <option value="Umum" {{ old('category', $faq->category) == 'Umum' ? 'selected' : '' }}>Umum</option>
                                    <option value="Akun & Keamanan" {{ old('category', $faq->category) == 'Akun & Keamanan' ? 'selected' : '' }}>Akun & Keamanan</option>
                                    <option value="Layanan & Transaksi" {{ old('category', $faq->category) == 'Layanan & Transaksi' ? 'selected' : '' }}>Layanan & Transaksi</option>
                                </select>
                                <p class="text-[10px] text-stone-400">Pilih dari daftar atau ketik nama kategori baru lalu tekan Enter.</p>
                                @error('category')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Urutan Tampil -->
                            <div class="space-y-1.5">
                                <label for="order" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Urutan Tampil
                                </label>
                                <input
                                    type="number"
                                    name="order"
                                    id="order"
                                    min="0"
                                    value="{{ old('order', $faq->order) }}"
                                    class="w-full rounded-2xl p-3 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                                />
                                <p class="text-[10px] text-stone-400">Angka lebih kecil tampil lebih awal (0, 1, 2, dst).</p>
                                @error('order')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Aktif / Publikasi -->
                            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-[#1d3e35]">Status Aktif</h4>
                                    <p class="text-[11px] text-stone-400">Tampilkan FAQ ini di halaman publik</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        value="1" 
                                        {{ old('is_active', $faq->is_active ? '1' : '0') == '1' ? 'checked' : '' }} 
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                                </label>
                            </div>
                        </div>
                    </x-admin.card>
                </div>
            </div>
        </form>
    </div>

    <!-- Script TomSelect -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.TomSelect) {
                new window.TomSelect('#category_select', {
                    create: true,
                    persist: false,
                    placeholder: 'Pilih atau ketik kategori baru...',
                });
            }
        });
    </script>
</x-layouts.admin>
