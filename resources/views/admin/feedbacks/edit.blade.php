<x-layouts.admin title="Tindak Lanjut & Edit Masukan: {{ $feedback->name }}">
    <x-admin.breadcrumb 
        title="Tindak Lanjut & Edit Masukan" 
        :items="[
            'Konten' => '',
            'Saran & Masukan' => route('admin.feedbacks.index'),
            $feedback->name => route('admin.feedbacks.show', $feedback->id),
            'Tindak Lanjut' => ''
        ]" 
    />

    <form action="{{ route('admin.feedbacks.update', $feedback->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Top Action Bar -->
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <a 
                href="{{ route('admin.feedbacks.show', $feedback->id) }}" 
                class="px-4 py-2 rounded-2xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-2 shadow-2xs transition-all"
            >
                <i data-lucide="arrow-left" class="w-4 h-4 text-stone-400"></i>
                <span>Kembali ke Detail</span>
            </a>

            <button 
                type="submit" 
                class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
            >
                <i data-lucide="check" class="w-4 h-4 text-[#cca06e]"></i>
                <span>Simpan Perubahan & Tindak Lanjut</span>
            </button>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-stone-100">
                    <div class="w-10 h-10 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-[#1d3e35]">Formulir Tindak Lanjut & Status Masukan</h4>
                        <p class="text-xs text-stone-400">Kelola status penyelesaian dan catatan tindak lanjut solusi kepada pengirim.</p>
                    </div>
                </div>

                <!-- Status & Prioritas (Section Utama) -->
                <div class="p-5 rounded-2xl bg-[#f2f8f5]/80 border border-[#99cab7]/40 space-y-4">
                    <h5 class="text-xs font-extrabold text-[#1d3e35] uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-[#31725e]"></i>
                        <span>Status & Alur Penanganan</span>
                    </h5>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">
                                Status Penanganan <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required class="w-full rounded-xl p-2.5 text-xs font-extrabold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                                @foreach($statuses as $key => $meta)
                                    <option value="{{ $key }}" {{ old('status', $feedback->status) === $key ? 'selected' : '' }}>
                                        {{ $meta['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prioritas Bintang -->
                        <div class="flex items-center gap-2 pt-6">
                            <input 
                                type="checkbox" 
                                id="is_starred" 
                                name="is_starred" 
                                value="1" 
                                {{ old('is_starred', $feedback->is_starred) ? 'checked' : '' }}
                                class="w-4 h-4 rounded text-[#31725e] focus:ring-[#31725e] border-stone-300"
                            >
                            <label for="is_starred" class="text-xs font-bold text-stone-700 cursor-pointer">
                                Tandai Sebagai Prioritas Penting (★)
                            </label>
                        </div>
                    </div>

                    <!-- Catatan Tindak Lanjut -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Catatan Tindak Lanjut / Solusi (Admin Notes)</label>
                        <textarea 
                            name="admin_notes" 
                            rows="3" 
                            placeholder="Catatan penanganan, nomor tiket, atau solusi yang telah diberikan kepada pengirim..."
                            class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                        >{{ old('admin_notes', $feedback->admin_notes) }}</textarea>
                    </div>
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
                            value="{{ old('name', $feedback->name) }}" 
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
                            value="{{ old('email', $feedback->email) }}" 
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
                            value="{{ old('phone', $feedback->phone) }}" 
                            class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                        >
                        @error('phone')
                            <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Masukan (Hanya 2 Jenis) -->
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Kategori Masukan <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required class="w-full rounded-xl p-2.5 text-xs font-bold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                            @foreach($types as $key => $meta)
                                <option value="{{ $key }}" {{ old('type', $feedback->type) === $key ? 'selected' : '' }}>
                                    {{ $meta['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Skor Kepuasan Layanan -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-stone-700 mb-1">
                            Skor Kepuasan Layanan (1 - 5 Bintang)
                        </label>
                        <select name="rating" class="w-full rounded-xl p-2.5 text-xs font-semibold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                            <option value="">Tidak ada skor (Opsional)</option>
                            <option value="5" {{ old('rating', $feedback->rating) == 5 ? 'selected' : '' }}>★★★★★ (5 - Sangat Puas)</option>
                            <option value="4" {{ old('rating', $feedback->rating) == 4 ? 'selected' : '' }}>★★★★☆ (4 - Puas)</option>
                            <option value="3" {{ old('rating', $feedback->rating) == 3 ? 'selected' : '' }}>★★★☆☆ (3 - Cukup)</option>
                            <option value="2" {{ old('rating', $feedback->rating) == 2 ? 'selected' : '' }}>★★☆☆☆ (2 - Kurang)</option>
                            <option value="1" {{ old('rating', $feedback->rating) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1 - Sangat Kecewa)</option>
                        </select>
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
                        value="{{ old('subject', $feedback->subject) }}" 
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
                        class="w-full rounded-xl p-3 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                    >{{ old('message', $feedback->message) }}</textarea>
                    @error('message')
                        <p class="text-[11px] text-red-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
