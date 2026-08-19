<x-layouts.admin title="Detail Masukan: {{ $feedback->name }}">
    <x-admin.breadcrumb 
        title="Detail Masukan" 
        :items="[
            'Konten' => '',
            'Saran & Masukan' => route('admin.feedbacks.index'),
            $feedback->name => ''
        ]" 
    />

    <!-- Flash Messages Notification -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="p-4 rounded-2xl bg-[#e2f0ea] border border-[#99cab7] text-[#1d3e35] flex items-center justify-between shadow-xs mb-6 transition-all duration-300"
        >
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-[#31725e]"></i>
                <span class="text-xs font-bold">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-stone-400 hover:text-stone-700">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div 
            class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 flex items-center justify-between shadow-xs mb-6"
        >
            <div class="flex items-center gap-3">
                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                <span class="text-xs font-bold">{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Top Action Bar -->
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <a 
                href="{{ route('admin.feedbacks.index') }}" 
                class="px-4 py-2 rounded-2xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-2 shadow-2xs transition-all"
            >
                <i data-lucide="arrow-left" class="w-4 h-4 text-stone-400"></i>
                <span>Kembali ke Daftar</span>
            </a>

            <div class="flex items-center gap-2">
                @if($feedback->isResolved())
                    <span class="px-3.5 py-1.5 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-300 font-bold text-xs inline-flex items-center gap-1.5 shadow-2xs">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Selesai (Read-Only)</span>
                    </span>
                @else
                    @if($isSupportOrSuperAdmin)
                        <a 
                            href="{{ route('admin.feedbacks.edit', $feedback->id) }}" 
                            class="px-4 py-2 rounded-2xl bg-[#1d3e35] text-white hover:bg-[#31725e] font-bold text-xs inline-flex items-center gap-2 shadow-md transition-all"
                        >
                            <i data-lucide="pencil" class="w-4 h-4 text-[#cca06e]"></i>
                            <span>Tindak Lanjut & Edit</span>
                        </a>
                    @endif
                @endif

                @if($isSupportOrSuperAdmin)
                    <button 
                        type="button" 
                        onclick="confirmDelete()"
                        class="px-4 py-2 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 font-bold text-xs inline-flex items-center gap-2 transition-all cursor-pointer"
                    >
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <span>Hapus</span>
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Main Message Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs space-y-6">
                    <!-- Sender Profile Card Header -->
                    <div class="flex items-start justify-between gap-4 pb-6 border-b border-stone-100 flex-wrap">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1d3e35] to-[#31725e] flex items-center justify-center text-white font-black text-lg shadow-md shrink-0">
                                {{ strtoupper(substr($feedback->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#1d3e35]">{{ $feedback->name }}</h3>
                                <p class="text-xs text-stone-500 font-medium">{{ $feedback->email }}</p>
                                @if($feedback->phone)
                                    <p class="text-xs text-stone-500 font-mono mt-0.5 flex items-center gap-1">
                                        <i data-lucide="phone" class="w-3 h-3 text-stone-400"></i>
                                        <span>{{ $feedback->phone }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Direct Email Reply -->
                            <a 
                                href="mailto:{{ $feedback->email }}?subject=Tanggapan:%20{{ urlencode($feedback->subject ?? 'Saran & Masukan') }}" 
                                class="px-3 py-1.5 rounded-xl border border-stone-200 bg-stone-50 hover:bg-stone-100 text-stone-700 text-xs font-bold inline-flex items-center gap-1.5 transition-colors"
                            >
                                <i data-lucide="mail" class="w-3.5 h-3.5 text-stone-500"></i>
                                <span>Kirim Email</span>
                            </a>
                            <!-- Direct WhatsApp if phone available -->
                            @if($feedback->phone)
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $feedback->phone);
                                    if (str_starts_with($cleanPhone, '0')) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <a 
                                    href="https://wa.me/{{ $cleanPhone }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold inline-flex items-center gap-1.5 transition-colors"
                                >
                                    <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                    <span>WhatsApp</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Subject & Meta Badges -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            @php
                                $typeInfo = $feedback->getTypeInfo();
                                $statusInfo = $feedback->getStatusInfo();
                            @endphp
                            <!-- Type Badge -->
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border border-{{ $typeInfo['color'] }}-200 bg-{{ $typeInfo['color'] }}-50 text-{{ $typeInfo['color'] }}-700">
                                <i data-lucide="{{ $typeInfo['icon'] }}" class="w-3 h-3"></i>
                                <span>{{ $typeInfo['label'] }}</span>
                            </span>

                            <!-- Status Badge -->
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border border-{{ $statusInfo['color'] }}-200 bg-{{ $statusInfo['color'] }}-50 text-{{ $statusInfo['color'] }}-700">
                                <i data-lucide="{{ $statusInfo['icon'] }}" class="w-3 h-3"></i>
                                <span>{{ $statusInfo['label'] }}</span>
                            </span>

                            @if($feedback->is_starred)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border border-amber-200 bg-amber-50 text-amber-700">
                                    ★ Prioritas
                                </span>
                            @endif

                            @if($feedback->rating)
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50/50 border border-amber-200 text-amber-600 ml-auto">
                                    <span>Skor Kepuasan:</span>
                                    <span class="text-amber-500 font-black">{{ $feedback->rating }}/5</span>
                                    <span>(
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $feedback->rating ? 'text-amber-400' : 'text-stone-300' }}">★</span>
                                        @endfor
                                    )</span>
                                </div>
                            @endif
                        </div>

                        @if($feedback->subject)
                            <h2 class="text-lg font-black text-[#1d3e35] pt-2">{{ $feedback->subject }}</h2>
                        @endif
                    </div>

                    <!-- Full Message Content -->
                    <div class="p-5 rounded-2xl bg-[#f4f8f6] border border-[#99cab7]/25 text-stone-800 text-sm leading-relaxed whitespace-pre-line font-medium">
                        {{ $feedback->message }}
                    </div>

                    <!-- Meta Timestamps -->
                    <div class="text-xs text-stone-400 pt-2 flex items-center justify-between border-t border-stone-100 flex-wrap gap-2">
                        <span>Diterima pada: <strong class="text-stone-600">{{ $feedback->created_at->translatedFormat('d F Y, H:i') }} WIB</strong></span>
                        @if($feedback->replied_at)
                            <span class="text-emerald-700 font-semibold">Ditindaklanjuti pada: {{ $feedback->replied_at->translatedFormat('d F Y, H:i') }} WIB</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Follow-up Panel -->
            <div class="space-y-6">
                @if($feedback->isResolved())
                    <!-- Panel Selesai (Read-Only Archive) -->
                    <div class="p-6 rounded-3xl bg-emerald-50/70 border border-emerald-200 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 text-emerald-800">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-emerald-900">Status: Selesai Ditutup</h4>
                                <span class="text-[10px] text-emerald-700 font-semibold">Arsip Read-Only</span>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2 border-t border-emerald-200/80 text-xs">
                            <div>
                                <span class="block text-[10px] text-emerald-700 font-bold uppercase">Tanggal Penyelesaian</span>
                                <span class="font-extrabold text-emerald-900">
                                    {{ $feedback->replied_at ? $feedback->replied_at->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                                </span>
                            </div>

                            <div class="pt-2">
                                <span class="block text-[10px] text-emerald-700 font-bold uppercase mb-1">Catatan Tindak Lanjut / Solusi</span>
                                <div class="p-3.5 rounded-xl bg-white border border-emerald-200 text-stone-700 leading-relaxed whitespace-pre-line text-xs font-normal">
                                    {{ $feedback->admin_notes ?: 'Tidak ada catatan khusus yang dicantumkan.' }}
                                </div>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-emerald-100/60 text-[11px] text-emerald-800 font-medium flex items-center gap-2">
                            <i data-lucide="lock" class="w-4 h-4 shrink-0 text-emerald-600"></i>
                            <span>Pesan ini telah diselesaikan dan dikunci untuk menjaga integritas riwayat layanan.</span>
                        </div>
                    </div>
                @else
                    @if($isSupportOrSuperAdmin)
                        <!-- Panel Tindak Lanjut untuk Super Admin & Support -->
                        <div class="p-6 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs space-y-5">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                                    <i data-lucide="check-square" class="w-4 h-4"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-[#1d3e35]">Tindak Lanjut & Status</h4>
                            </div>

                            <form action="{{ route('admin.feedbacks.update', $feedback->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <!-- Hidden fields to preserve original submission -->
                                <input type="hidden" name="name" value="{{ $feedback->name }}">
                                <input type="hidden" name="email" value="{{ $feedback->email }}">
                                <input type="hidden" name="phone" value="{{ $feedback->phone }}">
                                <input type="hidden" name="subject" value="{{ $feedback->subject }}">
                                <input type="hidden" name="type" value="{{ $feedback->type }}">
                                <input type="hidden" name="message" value="{{ $feedback->message }}">
                                <input type="hidden" name="rating" value="{{ $feedback->rating }}">

                                <!-- Status Selection -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1">Ubah Status</label>
                                    <select name="status" class="w-full rounded-xl p-2.5 text-xs font-bold border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none">
                                        @foreach($statuses as $key => $meta)
                                            <option value="{{ $key }}" {{ old('status', $feedback->status) === $key ? 'selected' : '' }}>
                                                {{ $meta['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Prioritas Bintang Checkbox -->
                                <div class="flex items-center gap-2 py-1">
                                    <input 
                                        type="checkbox" 
                                        id="is_starred" 
                                        name="is_starred" 
                                        value="1" 
                                        {{ old('is_starred', $feedback->is_starred) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded text-[#31725e] focus:ring-[#31725e] border-stone-300"
                                    >
                                    <label for="is_starred" class="text-xs font-bold text-stone-700 cursor-pointer">
                                        Tandai Sebagai Prioritas (★)
                                    </label>
                                </div>

                                <!-- Internal Admin Notes -->
                                <div>
                                    <label class="block text-xs font-bold text-stone-700 mb-1">Catatan Tindak Lanjut / Solusi</label>
                                    <textarea 
                                        name="admin_notes" 
                                        rows="4" 
                                        placeholder="Tuliskan catatan tindak lanjut, langkah penanganan, atau ringkasan solusi yang diberikan..."
                                        class="w-full rounded-xl p-2.5 text-xs border border-stone-200 bg-white text-stone-800 focus:border-[#31725e] outline-none"
                                    >{{ old('admin_notes', $feedback->admin_notes) }}</textarea>
                                    <span class="text-[10px] text-stone-400">Catatan disimpan untuk riwayat penanganan pelanggan.</span>
                                </div>

                                <button 
                                    type="submit" 
                                    class="w-full py-2.5 rounded-2xl bg-[#1d3e35] text-white hover:bg-[#31725e] font-bold text-xs shadow-md shadow-[#1d3e35]/15 transition-all cursor-pointer"
                                >
                                    Simpan Tindak Lanjut
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Panel untuk Role Non-Super Admin / Non-Support -->
                        <div class="p-6 rounded-3xl bg-white border border-stone-200 shadow-2xs space-y-4">
                            <div class="flex items-center gap-2 text-stone-800">
                                <i data-lucide="shield-alert" class="w-4 h-4 text-stone-400"></i>
                                <h4 class="text-xs font-bold">Otoritas Tindak Lanjut</h4>
                            </div>
                            <p class="text-xs text-stone-500 leading-relaxed">
                                Pengubahan status dan pencatatan tindak lanjut komplain/masukan hanya dapat dilakukan oleh staf <strong>Super Admin</strong> dan <strong>Support</strong>.
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if($isSupportOrSuperAdmin)
        <!-- Hidden Form for Delete -->
        <form id="deleteForm" action="{{ route('admin.feedbacks.destroy', $feedback->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <script>
            function confirmDelete() {
                if (confirm('Apakah Anda yakin ingin menghapus pesan masukan ini secara permanen?')) {
                    document.getElementById('deleteForm').submit();
                }
            }
        </script>
    @endif
</x-layouts.admin>
