<x-layouts.admin title="Detail Artikel: {{ $article->title }}">
    <x-admin.breadcrumb 
        title="Detail & Analisis SEO Artikel" 
        :items="[
            'Article SEO' => '',
            'Article' => route('admin.articles.index'),
            'Detail Artikel' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        deleteModalOpen: false,
    }">
        <!-- Top Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-xs">
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('admin.articles.index') }}" 
                    class="p-2.5 rounded-2xl bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors"
                    title="Kembali ke Daftar Artikel"
                >
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Preview & Analisis SEO</span>
                    <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">{{ $article->title }}</h3>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('admin.articles.edit', $article->id) }}" 
                    class="px-4 py-2 rounded-2xl bg-[#31725e] text-white hover:bg-[#295c4d] font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#31725e]/15 transition-all"
                >
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    <span>Edit Artikel</span>
                </a>

                <button 
                    type="button" 
                    @click="deleteModalOpen = true"
                    class="px-4 py-2 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 font-bold text-xs inline-flex items-center gap-1.5 transition-colors"
                >
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Article Content Preview -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Article Header & Content -->
                <x-admin.card>
                    <div class="space-y-6">
                        <!-- Thumbnail Header Image -->
                        @if($article->getThumbnail())
                            <div class="w-full aspect-21/9 rounded-2xl overflow-hidden shadow-xs border border-stone-200">
                                <img 
                                    src="{{ $article->getThumbnail() }}" 
                                    alt="{{ $article->title }}" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        @endif

                        <!-- Meta Info Row -->
                        <div class="flex flex-wrap items-center gap-3 text-xs text-stone-500">
                            @if($article->category)
                                <span class="inline-flex items-center gap-1 font-bold text-[#295c4d] bg-[#f2f8f5] px-2.5 py-1 rounded-lg border border-[#99cab7]/40">
                                    <i data-lucide="folder" class="w-3.5 h-3.5 text-[#31725e]"></i>
                                    {{ $article->category->name }}
                                </span>
                            @endif

                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                {{ $article->author->name ?? 'Sistem' }}
                            </span>

                            <span>&bull;</span>

                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ $article->published_at ? $article->published_at->translatedFormat('d F Y, H:i') : 'Draft' }}
                            </span>

                            <span>&bull;</span>

                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                {{ number_format($article->views_count) }} kali dibaca
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl font-extrabold text-[#1d3e35] leading-tight">
                            {{ $article->title }}
                        </h1>

                        <!-- Slug URL Badge -->
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-[#f2f8f5] border border-[#99cab7]/30 font-mono text-xs text-[#295c4d]">
                            <i data-lucide="link" class="w-3.5 h-3.5 text-[#31725e] shrink-0"></i>
                            <span class="truncate">{{ url('/articles/' . $article->slug) }}</span>
                        </div>

                        <!-- Excerpt -->
                        @if($article->excerpt)
                            <div class="p-4 rounded-2xl bg-stone-50 border-l-4 border-[#31725e] text-sm text-stone-700 italic leading-relaxed">
                                {{ $article->excerpt }}
                            </div>
                        @endif

                        <!-- Main Content -->
                        <div class="prose max-w-none text-[#1d3e35] text-sm leading-relaxed pt-2 border-t border-stone-100">
                            {!! $article->content !!}
                        </div>

                        <!-- Tags -->
                        @if($article->tags->isNotEmpty())
                            <div class="pt-4 border-t border-stone-100 flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold text-stone-400 uppercase tracking-wider">Tags:</span>
                                @foreach($article->tags as $tag)
                                    <span class="px-2.5 py-1 rounded-lg bg-stone-100 text-stone-700 text-xs font-semibold hover:bg-stone-200 transition-colors">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </x-admin.card>
            </div>

            <!-- Right Column: SEO Analysis & Metadata -->
            <div class="space-y-6">
                <!-- SEO Score Card -->
                <x-admin.card 
                    title="Analisis Kualitas SEO" 
                    subtitle="Pemeriksaan indikator SEO On-Page."
                >
                    <div class="space-y-4">
                        <!-- Check 1: Slug -->
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-[#f2f8f5] border border-[#99cab7]/30">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
                            <div class="text-xs">
                                <span class="font-bold text-[#1d3e35]">Clean SEO Slug</span>
                                <p class="text-stone-500 font-mono text-[11px] mt-0.5">/{{ $article->slug }}</p>
                            </div>
                        </div>

                        <!-- Check 2: Meta Title -->
                        @php
                            $titleLength = strlen($article->meta_title ?: $article->title);
                            $titleOk = $titleLength >= 30 && $titleLength <= 65;
                        @endphp
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $titleOk ? 'bg-[#f2f8f5] border-[#99cab7]/30' : 'bg-amber-50/70 border-amber-200' }} border">
                            <i data-lucide="{{ $titleOk ? 'check-circle-2' : 'alert-circle' }}" class="w-4 h-4 {{ $titleOk ? 'text-emerald-600' : 'text-amber-600' }} shrink-0 mt-0.5"></i>
                            <div class="text-xs">
                                <span class="font-bold text-[#1d3e35]">Panjang Meta Title</span>
                                <p class="text-stone-500 text-[11px] mt-0.5">{{ $titleLength }} karakter (Disarankan 30-65)</p>
                            </div>
                        </div>

                        <!-- Check 3: Meta Description -->
                        @php
                            $descLength = strlen($article->meta_description ?: ($article->excerpt ?? ''));
                            $descOk = $descLength >= 70 && $descLength <= 165;
                        @endphp
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $descOk ? 'bg-[#f2f8f5] border-[#99cab7]/30' : 'bg-amber-50/70 border-amber-200' }} border">
                            <i data-lucide="{{ $descOk ? 'check-circle-2' : 'alert-circle' }}" class="w-4 h-4 {{ $descOk ? 'text-emerald-600' : 'text-amber-600' }} shrink-0 mt-0.5"></i>
                            <div class="text-xs">
                                <span class="font-bold text-[#1d3e35]">Meta Description</span>
                                <p class="text-stone-500 text-[11px] mt-0.5">{{ $descLength }} karakter (Disarankan 70-160)</p>
                            </div>
                        </div>

                        <!-- Check 4: Thumbnail Image -->
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $article->getThumbnail() ? 'bg-[#f2f8f5] border-[#99cab7]/30' : 'bg-amber-50/70 border-amber-200' }} border">
                            <i data-lucide="{{ $article->getThumbnail() ? 'check-circle-2' : 'alert-circle' }}" class="w-4 h-4 {{ $article->getThumbnail() ? 'text-emerald-600' : 'text-amber-600' }} shrink-0 mt-0.5"></i>
                            <div class="text-xs">
                                <span class="font-bold text-[#1d3e35]">Featured Thumbnail (OG Image)</span>
                                <p class="text-stone-500 text-[11px] mt-0.5">{{ $article->getThumbnail() ? 'Tersedia dari Media Library' : 'Belum ditentukan' }}</p>
                            </div>
                        </div>

                        <!-- Check 5: Sitemap Inclusion -->
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ $article->status === 'published' ? 'bg-[#f2f8f5] border-[#99cab7]/30' : 'bg-stone-50 border-stone-200' }} border">
                            <i data-lucide="{{ $article->status === 'published' ? 'network' : 'clock' }}" class="w-4 h-4 {{ $article->status === 'published' ? 'text-emerald-600' : 'text-stone-400' }} shrink-0 mt-0.5"></i>
                            <div class="text-xs">
                                <span class="font-bold text-[#1d3e35]">Status Sitemap.xml</span>
                                <p class="text-stone-500 text-[11px] mt-0.5">
                                    {{ $article->status === 'published' ? 'Terdaftar di public/sitemap.xml' : 'Tidak terdaftar (Status Draft/Arsip)' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </x-admin.card>

                <!-- Social Card Snippet Preview (OpenGraph) -->
                <x-admin.card 
                    title="Pratinjau Media Sosial" 
                    subtitle="Tampilan saat tautan artikel dibagikan."
                >
                    <div class="rounded-2xl border border-stone-200 overflow-hidden bg-white shadow-2xs">
                        @if($article->getThumbnail())
                            <img src="{{ $article->getThumbnail() }}" alt="Social Preview" class="w-full aspect-16/9 object-cover" />
                        @else
                            <div class="w-full aspect-16/9 bg-stone-100 flex items-center justify-center text-stone-400">
                                <i data-lucide="image" class="w-8 h-8"></i>
                            </div>
                        @endif
                        <div class="p-3.5 space-y-1">
                            <span class="text-[10px] text-stone-400 font-mono uppercase">{{ parse_url(url('/'), PHP_URL_HOST) }}</span>
                            <h5 class="text-xs font-bold text-[#1d3e35] line-clamp-1">{{ $article->meta_title ?: $article->title }}</h5>
                            <p class="text-[11px] text-stone-500 line-clamp-2">{{ $article->meta_description ?: ($article->excerpt ?: 'Baca selengkapnya artikel ini di situs kami.') }}</p>
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus Artikel -->
        <template x-teleport="body">
            <div 
                x-show="deleteModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="deleteModalOpen = false"
                    class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/30"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-stone-900">Hapus Artikel</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus artikel <strong class="text-red-600">{{ $article->title }}</strong>?
                    </p>

                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                        @csrf
                        @method('DELETE')

                        <button 
                            type="button" 
                            @click="deleteModalOpen = false"
                            class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            class="px-4 py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-red-600/20 transition-all"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            <span>Hapus Artikel</span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-layouts.admin>
