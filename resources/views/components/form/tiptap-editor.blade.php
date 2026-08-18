@props([
    'name' => 'content',
    'id' => 'content',
    'value' => '',
    'placeholder' => 'Mulai menulis isi konten artikel lengkap di sini...',
    'required' => false,
])

<div 
    x-data="tiptapEditor({ 
        initialContent: {{ json_encode($value ?? '') }}, 
        placeholder: {{ json_encode($placeholder) }} 
    })" 
    class="space-y-2"
>
    <!-- Hidden textarea bound to form submission -->
    <textarea 
        name="{{ $name }}" 
        id="{{ $id }}" 
        x-ref="hiddenTextarea" 
        class="hidden"
        @if($required) required @endif
    >{{ $value ?? '' }}</textarea>

    <!-- Editor Container Card -->
    <div 
        class="w-full rounded-3xl bg-white border border-[#99cab7]/50 shadow-2xs overflow-hidden transition-all duration-200"
        :class="isFocused ? 'ring-4 ring-[#428e75]/20 border-[#31725e]' : 'hover:border-[#99cab7]'"
    >
        <!-- Toolbar Header -->
        <div class="p-2 sm:p-2.5 bg-[#f2f8f5]/80 border-b border-[#99cab7]/30 flex flex-wrap items-center gap-1 sm:gap-1.5 select-none">
            
            <!-- Format Selector -->
            <div class="flex items-center gap-0.5 bg-white p-1 rounded-xl border border-stone-200/80 shadow-2xs">
                <button 
                    type="button" 
                    @click="setHeading('paragraph')"
                    class="px-2 py-1 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                    :class="activeHeading === 'paragraph' ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Paragraf Biasa"
                >
                    P
                </button>
                <button 
                    type="button" 
                    @click="setHeading('h2')"
                    class="px-2 py-1 rounded-lg text-xs font-bold transition-colors cursor-pointer"
                    :class="activeHeading === 'h2' ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Judul Heading 2"
                >
                    H2
                </button>
                <button 
                    type="button" 
                    @click="setHeading('h3')"
                    class="px-2 py-1 rounded-lg text-xs font-bold transition-colors cursor-pointer"
                    :class="activeHeading === 'h3' ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Subjudul Heading 3"
                >
                    H3
                </button>
                <button 
                    type="button" 
                    @click="setHeading('h4')"
                    class="px-2 py-1 rounded-lg text-xs font-bold transition-colors cursor-pointer"
                    :class="activeHeading === 'h4' ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Section Heading 4"
                >
                    H4
                </button>
            </div>

            <div class="h-5 w-px bg-stone-300 mx-0.5 hidden sm:block"></div>

            <!-- Inline Styles Group -->
            <div class="flex items-center gap-0.5 bg-white p-1 rounded-xl border border-stone-200/80 shadow-2xs">
                <!-- Bold -->
                <button 
                    type="button" 
                    @click="toggleBold()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.bold ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Tebal (Ctrl+B)"
                >
                    <i data-lucide="bold" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Italic -->
                <button 
                    type="button" 
                    @click="toggleItalic()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.italic ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Miring (Ctrl+I)"
                >
                    <i data-lucide="italic" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Underline -->
                <button 
                    type="button" 
                    @click="toggleUnderline()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.underline ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Garis Bawah (Ctrl+U)"
                >
                    <i data-lucide="underline" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Strike -->
                <button 
                    type="button" 
                    @click="toggleStrike()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.strike ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Coretan (Strikethrough)"
                >
                    <i data-lucide="strikethrough" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Code Inline -->
                <button 
                    type="button" 
                    @click="toggleCode()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.code ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Inline Code"
                >
                    <i data-lucide="code" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <div class="h-5 w-px bg-stone-300 mx-0.5 hidden sm:block"></div>

            <!-- Lists & Blocks Group -->
            <div class="flex items-center gap-0.5 bg-white p-1 rounded-xl border border-stone-200/80 shadow-2xs">
                <!-- Bullet List -->
                <button 
                    type="button" 
                    @click="toggleBulletList()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.bulletList ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Daftar Poin (Bullet List)"
                >
                    <i data-lucide="list" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Ordered List -->
                <button 
                    type="button" 
                    @click="toggleOrderedList()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.orderedList ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Daftar Nomor (Numbered List)"
                >
                    <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Blockquote -->
                <button 
                    type="button" 
                    @click="toggleBlockquote()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.blockquote ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Kutipan (Blockquote)"
                >
                    <i data-lucide="quote" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Code Block -->
                <button 
                    type="button" 
                    @click="toggleCodeBlock()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.codeBlock ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Blok Kode (Code Block)"
                >
                    <i data-lucide="square-code" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Horizontal Rule -->
                <button 
                    type="button" 
                    @click="setHorizontalRule()"
                    class="p-1.5 rounded-lg text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35] transition-colors cursor-pointer"
                    title="Garis Pembatas (Horizontal Line)"
                >
                    <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <div class="h-5 w-px bg-stone-300 mx-0.5 hidden sm:block"></div>

            <!-- Links & Media Group -->
            <div class="flex items-center gap-0.5 bg-white p-1 rounded-xl border border-stone-200/80 shadow-2xs">
                <!-- Insert Link -->
                <button 
                    type="button" 
                    @click="setLink()"
                    class="p-1.5 rounded-lg transition-colors cursor-pointer"
                    :class="activeMarks.link ? 'bg-[#31725e] text-white' : 'text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35]'"
                    title="Sisipkan Link Tautan"
                >
                    <i data-lucide="link" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Remove Link (if active) -->
                <button 
                    type="button" 
                    x-show="activeMarks.link"
                    @click="unsetLink()"
                    class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors cursor-pointer"
                    title="Hapus Link Tautan"
                >
                    <i data-lucide="unlink" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Insert Image from Media Library -->
                <button 
                    type="button" 
                    @click="openImageModal()"
                    class="px-2 py-1 rounded-lg text-xs font-bold text-[#1d3e35] bg-[#e2f0ea] hover:bg-[#c5e1d5] inline-flex items-center gap-1 transition-colors cursor-pointer"
                    title="Sisipkan Gambar dari Media Library"
                >
                    <i data-lucide="image-plus" class="w-3.5 h-3.5 text-[#31725e]"></i>
                    <span class="hidden md:inline">Media</span>
                </button>

                <!-- Insert Image from URL -->
                <button 
                    type="button" 
                    @click="promptImageUrl()"
                    class="p-1.5 rounded-lg text-stone-600 hover:bg-[#e2f0ea] hover:text-[#1d3e35] transition-colors cursor-pointer"
                    title="Sisipkan Gambar via URL"
                >
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <!-- Right Spacer / Undo Redo & Clear -->
            <div class="ml-auto flex items-center gap-0.5 bg-white p-1 rounded-xl border border-stone-200/80 shadow-2xs">
                <!-- Clear Formatting -->
                <button 
                    type="button" 
                    @click="clearFormatting()"
                    class="p-1.5 rounded-lg text-stone-500 hover:bg-stone-100 hover:text-stone-800 transition-colors cursor-pointer"
                    title="Hapus Format"
                >
                    <i data-lucide="eraser" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Undo -->
                <button 
                    type="button" 
                    @click="undo()"
                    :disabled="!canUndo"
                    class="p-1.5 rounded-lg text-stone-500 hover:bg-[#e2f0ea] hover:text-[#1d3e35] disabled:opacity-30 disabled:cursor-not-allowed transition-colors cursor-pointer"
                    title="Urungkan (Undo)"
                >
                    <i data-lucide="undo-2" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Redo -->
                <button 
                    type="button" 
                    @click="redo()"
                    :disabled="!canRedo"
                    class="p-1.5 rounded-lg text-stone-500 hover:bg-[#e2f0ea] hover:text-[#1d3e35] disabled:opacity-30 disabled:cursor-not-allowed transition-colors cursor-pointer"
                    title="Ulangi (Redo)"
                >
                    <i data-lucide="redo-2" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>

        <!-- TipTap Editor Content Area -->
        <div 
            x-ref="editorElement" 
            class="bg-white min-h-[360px] cursor-text"
            @click="focusEditor()"
        ></div>
    </div>
</div>
