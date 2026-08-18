import './bootstrap';
import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';
import TomSelect from 'tom-select';

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';
import Underline from '@tiptap/extension-underline';

// Register Alpine plugins
Alpine.plugin(collapse);

// Attach globals to window
if (DataTable.use) {
    DataTable.use($);
}
window.$ = window.jQuery = $;
window.DataTable = DataTable;
window.TomSelect = TomSelect;
window.Alpine = Alpine;
window.Chart = Chart;
window.createIcons = createIcons;
window.icons = icons;

// Global Alpine sidebar store
Alpine.store('sidebar', {
    collapsed: false,
    mobileOpen: false,
    toggle() {
        this.collapsed = !this.collapsed;
    },
    toggleMobile() {
        this.mobileOpen = !this.mobileOpen;
    },
    closeMobile() {
        this.mobileOpen = false;
    }
});

// Alpine TipTap Editor Component Definition
Alpine.data('tiptapEditor', ({ initialContent = '', placeholder = 'Mulai menulis isi konten artikel lengkap di sini...' } = {}) => {
    let editorInstance = null;

    return {
        content: initialContent || '',
        isFocused: false,
        activeHeading: 'paragraph',
        activeMarks: {
            bold: false,
            italic: false,
            underline: false,
            strike: false,
            code: false,
            bulletList: false,
            orderedList: false,
            blockquote: false,
            codeBlock: false,
            link: false,
        },
        canUndo: false,
        canRedo: false,

        init() {
            const container = this.$refs.editorElement;
            if (!container) return;

            editorInstance = new Editor({
                element: container,
                extensions: [
                    StarterKit.configure({
                        heading: {
                            levels: [2, 3, 4],
                        },
                    }),
                    Underline,
                    Link.configure({
                        openOnClick: false,
                        HTMLAttributes: {
                            class: 'text-[#31725e] underline font-semibold hover:text-[#1d3e35]',
                            target: '_blank',
                            rel: 'noopener noreferrer',
                        },
                    }),
                    Image.configure({
                        HTMLAttributes: {
                            class: 'rounded-2xl max-w-full my-4 shadow-md',
                        },
                    }),
                    Placeholder.configure({
                        placeholder: placeholder,
                    }),
                ],
                content: this.content,
                editorProps: {
                    attributes: {
                        class: 'tiptap p-4 sm:p-6 outline-none focus:outline-none min-h-[360px] text-sm leading-relaxed text-[#1d3e35]',
                    },
                },
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                    if (this.$refs.hiddenTextarea) {
                        this.$refs.hiddenTextarea.value = this.content;
                        this.$refs.hiddenTextarea.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    this.updateState();
                },
                onSelectionUpdate: () => {
                    this.updateState();
                },
                onTransaction: () => {
                    this.updateState();
                },
                onFocus: () => {
                    this.isFocused = true;
                },
                onBlur: () => {
                    this.isFocused = false;
                },
            });

            // Set initial value to hidden textarea
            if (this.$refs.hiddenTextarea) {
                this.$refs.hiddenTextarea.value = this.content;
            }

            // Sync initial state
            this.updateState();

            // Listen for media picker event if target is tiptap
            window.addEventListener('media-selected', (event) => {
                if (event.detail && event.detail.targetField === 'tiptap_image' && event.detail.media) {
                    this.insertImage(event.detail.media.url, event.detail.media.title || '');
                }
            });

            this.$nextTick(() => {
                createIcons({ icons });
            });
        },

        updateState() {
            if (!editorInstance) return;

            if (editorInstance.isActive('heading', { level: 2 })) {
                this.activeHeading = 'h2';
            } else if (editorInstance.isActive('heading', { level: 3 })) {
                this.activeHeading = 'h3';
            } else if (editorInstance.isActive('heading', { level: 4 })) {
                this.activeHeading = 'h4';
            } else {
                this.activeHeading = 'paragraph';
            }

            this.activeMarks = {
                bold: editorInstance.isActive('bold'),
                italic: editorInstance.isActive('italic'),
                underline: editorInstance.isActive('underline'),
                strike: editorInstance.isActive('strike'),
                code: editorInstance.isActive('code'),
                bulletList: editorInstance.isActive('bulletList'),
                orderedList: editorInstance.isActive('orderedList'),
                blockquote: editorInstance.isActive('blockquote'),
                codeBlock: editorInstance.isActive('codeBlock'),
                link: editorInstance.isActive('link'),
            };

            this.canUndo = editorInstance.can().undo();
            this.canRedo = editorInstance.can().redo();
        },

        focusEditor() {
            if (editorInstance) {
                editorInstance.commands.focus();
            }
        },

        setHeading(type) {
            if (!editorInstance) return;
            if (type === 'paragraph') {
                editorInstance.chain().focus().setParagraph().run();
            } else if (type === 'h2') {
                editorInstance.chain().focus().toggleHeading({ level: 2 }).run();
            } else if (type === 'h3') {
                editorInstance.chain().focus().toggleHeading({ level: 3 }).run();
            } else if (type === 'h4') {
                editorInstance.chain().focus().toggleHeading({ level: 4 }).run();
            }
            this.updateState();
        },

        toggleBold() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleBold().run();
            this.updateState();
        },

        toggleItalic() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleItalic().run();
            this.updateState();
        },

        toggleUnderline() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleUnderline().run();
            this.updateState();
        },

        toggleStrike() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleStrike().run();
            this.updateState();
        },

        toggleCode() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleCode().run();
            this.updateState();
        },

        toggleBulletList() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleBulletList().run();
            this.updateState();
        },

        toggleOrderedList() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleOrderedList().run();
            this.updateState();
        },

        toggleBlockquote() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleBlockquote().run();
            this.updateState();
        },

        toggleCodeBlock() {
            if (!editorInstance) return;
            editorInstance.chain().focus().toggleCodeBlock().run();
            this.updateState();
        },

        setHorizontalRule() {
            if (!editorInstance) return;
            editorInstance.chain().focus().setHorizontalRule().run();
            this.updateState();
        },

        setLink() {
            if (!editorInstance) return;
            const previousUrl = editorInstance.getAttributes('link').href || '';
            const url = window.prompt('Masukkan URL tautan link (https://...):', previousUrl);

            if (url === null) return;

            if (url.trim() === '') {
                editorInstance.chain().focus().extendMarkRange('link').unsetLink().run();
            } else {
                editorInstance.chain().focus().extendMarkRange('link').setLink({ href: url.trim() }).run();
            }
            this.updateState();
        },

        unsetLink() {
            if (!editorInstance) return;
            editorInstance.chain().focus().extendMarkRange('link').unsetLink().run();
            this.updateState();
        },

        openImageModal() {
            window.dispatchEvent(new CustomEvent('open-media-picker', {
                detail: { targetField: 'tiptap_image' }
            }));
        },

        promptImageUrl() {
            const url = window.prompt('Masukkan URL Gambar (https://...):');
            if (url && url.trim()) {
                this.insertImage(url.trim());
            }
        },

        insertImage(url, alt = '') {
            if (!editorInstance || !url) return;
            editorInstance.chain().focus().setImage({ src: url, alt: alt }).run();
            this.updateState();
        },

        clearFormatting() {
            if (!editorInstance) return;
            editorInstance.chain().focus().unsetAllMarks().clearNodes().run();
            this.updateState();
        },

        undo() {
            if (!editorInstance) return;
            editorInstance.chain().focus().undo().run();
            this.updateState();
        },

        redo() {
            if (!editorInstance) return;
            editorInstance.chain().focus().redo().run();
            this.updateState();
        },

        destroy() {
            if (editorInstance) {
                editorInstance.destroy();
                editorInstance = null;
            }
        }
    };
});

// Start Alpine
Alpine.start();

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});

// Re-initialize after Alpine renders or dynamic updates
window.refreshIcons = () => {
    createIcons({ icons });
};
