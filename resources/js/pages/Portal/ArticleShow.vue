<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import { useReadingProgress } from '@/composables/useIntersectionObserver';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps<{
    article: any;
    shareUrl: string;
    related?: any[];
}>();

const page = usePage();
const currentUser = computed(() => (page.props.auth as any)?.reader);
const { formatRelative, formatDate } = useRelativeTime();
const readingProgress = useReadingProgress();

// ── Comment forms ──────────────────────────────────────────────
const form = useForm({ content: '' });
const copySuccess = ref(false);
const commentSent = ref(false);
const editingCommentId = ref<number | null>(null);
const editForm = useForm({ content: '' });

const startEdit = (comment: any) => {
    editingCommentId.value = comment.id;
    editForm.content = comment.content;
};
const cancelEdit = () => {
    editingCommentId.value = null;
    editForm.reset();
};
const submitEdit = (commentId: number) => {
    editForm.put(route('comments.update', commentId), {
        preserveScroll: true,
        onSuccess: () => { editingCommentId.value = null; editForm.reset(); },
    });
};
const deleteComment = (commentId: number) => {
    if (!confirm('Hapus komentar ini?')) return;
    useForm({}).delete(route('comments.destroy', commentId), { preserveScroll: true });
};

// ── Share ──────────────────────────────────────────────────────
const encodedShareUrl = computed(() => encodeURIComponent(props.shareUrl));
const encodedTitle = computed(() => encodeURIComponent(props.article.title));
const shareLinks = computed(() => ({
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedShareUrl.value}`,
    x: `https://twitter.com/intent/tweet?url=${encodedShareUrl.value}&text=${encodedTitle.value}`,
    whatsapp: `https://wa.me/?text=${encodedTitle.value}%20${encodedShareUrl.value}`,
    telegram: `https://t.me/share/url?url=${encodedShareUrl.value}&text=${encodedTitle.value}`,
}));

const submitComment = () => {
    form.post(route('articles.comments.store', props.article.slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            commentSent.value = true;
            setTimeout(() => { commentSent.value = false; }, 5000);
        },
    });
};
const copyLink = async () => {
    await navigator.clipboard.writeText(props.shareUrl);
    copySuccess.value = true;
    setTimeout(() => { copySuccess.value = false; }, 3000);
};

// ── Video embed ────────────────────────────────────────────────
const getVideoEmbed = (url: string): string | null => {
    if (!url) return null;
    const ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
    if (ytMatch) return `https://www.youtube.com/embed/${ytMatch[1]}`;
    const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
    if (vimeoMatch) return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
    return null;
};

// ── Reading time estimate ──────────────────────────────────────
const readingTime = computed(() => {
    const text = String(props.article.content ?? '').replace(/<[^>]*>/g, '');
    const words = text.trim().split(/\s+/).length;
    return Math.max(1, Math.ceil(words / 200));
});


</script>

<template>
    <PortalLayout>
        <Head :title="article.title" />

        <!-- Reading progress bar -->
        <div class="fixed top-0 left-0 z-[60] h-[3px] bg-gradient-to-r from-brand-600 to-brand-400 transition-all duration-75 shadow-brand" :style="{ width: `${readingProgress}%` }"></div>

        <!-- ── PAGE WRAPPER (light bg) ── -->
        <div class="min-h-screen bg-ink-50">

            <!-- ── ARTICLE HEADER ── -->
            <div class="bg-white pt-8 pb-0 border-b border-ink-100">
                <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

                    <!-- Breadcrumb -->
                    <nav class="mb-5 flex items-center gap-1.5 text-xs text-ink-400" aria-label="Breadcrumb">
                        <Link :href="route('home')" class="transition-colors hover:text-brand-600">Beranda</Link>
                        <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <Link v-if="article.category" :href="route('categories.show', article.category.slug)" class="transition-colors hover:text-brand-600">
                            {{ article.category.name }}
                        </Link>
                        <svg v-if="article.category" class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="line-clamp-1 text-ink-500">{{ article.title }}</span>
                    </nav>

                    <!-- Category badge -->
                    <Link v-if="article.category" :href="route('categories.show', article.category.slug)">
                        <span class="mb-4 inline-flex items-center gap-1.5 rounded-sm bg-brand-600 px-3 py-1 text-xs font-black uppercase tracking-widest text-white shadow-brand transition-all hover:bg-brand-700">
                            {{ article.category.name }}
                        </span>
                    </Link>

                    <!-- Title -->
                    <h1 class="mb-4 font-headline text-3xl font-black leading-tight text-ink-950 sm:text-4xl lg:text-5xl" style="letter-spacing: -0.02em;">{{ article.title }}</h1>

                    <!-- Excerpt -->
                    <p v-if="article.excerpt" class="mb-6 text-lg leading-relaxed text-ink-600 border-l-[3px] border-brand-600 pl-5 italic">{{ article.excerpt }}</p>

                    <!-- Meta bar -->
                    <div class="mb-8 flex flex-wrap items-center gap-x-5 gap-y-2 border-y border-ink-200 py-3.5 text-sm text-ink-500">
                        <!-- Author -->
                        <Link v-if="article.journalist" :href="route('journalists.show', article.journalist.slug)" class="group flex items-center gap-2.5 transition-colors hover:text-brand-600">
                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand-100 ring-2 ring-brand-200">
                                <img v-if="article.journalist.photo_path" :src="`/storage/${article.journalist.photo_path}`" :alt="article.journalist.name" class="h-full w-full object-cover" />
                                <span v-else class="text-xs font-black text-brand-600">{{ article.journalist.name?.charAt(0) }}</span>
                            </div>
                            <span class="font-semibold text-ink-700 group-hover:text-brand-600">{{ article.journalist.name }}</span>
                        </Link>
                        <!-- Date -->
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ formatDate(article.published_at) }}
                        </span>
                        <!-- Reading time -->
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ readingTime }} menit baca
                        </span>
                        <!-- Views -->
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ article.view_count?.toLocaleString('id-ID') }} views
                        </span>
                    </div>

                    <!-- Share buttons — TOP -->
                    
                </div>
            </div>

            <!-- ── MAIN CONTENT AREA ── -->
            <div class="mx-auto max-w-[1280px] px-4 pb-16 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_340px]">

                    <!-- ── LEFT: Article body ── -->
                    <article>
                        <!-- Featured image 16:9 -->
                        <div v-if="article.featured_image" class="mb-8 overflow-hidden rounded-2xl shadow-md">
                            <div class="relative aspect-video bg-ink-100">
                                <img :src="`/storage/${article.featured_image}`" :alt="article.title"
                                    class="absolute inset-0 h-full w-full object-cover" loading="eager" />
                            </div>
                        </div>

                        <!-- Video divider -->
                        <div v-if="article.featured_image && article.video_url && getVideoEmbed(article.video_url)" class="mb-6 flex items-center gap-3">
                            <div class="h-px flex-1 bg-ink-200"></div>
                            <span class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-ink-400">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                Video
                            </span>
                            <div class="h-px flex-1 bg-ink-200"></div>
                        </div>

                        <!-- Video embed -->
                        <div v-if="article.video_url && getVideoEmbed(article.video_url)" class="mb-8 overflow-hidden rounded-2xl shadow-md">
                            <div class="relative aspect-video bg-ink-100">
                                <iframe :src="getVideoEmbed(article.video_url)!" class="absolute inset-0 h-full w-full"
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>

                        <!-- Article content -->
                        <div class="article-prose" v-html="article.content"></div>

                        <!-- Share buttons BOTTOM -->
                        <div class="mt-10 rounded-xl border border-ink-200 bg-ink-50 p-5">
                            <h3 class="mb-4 text-xs font-black uppercase tracking-widest text-ink-400">Bagikan Artikel Ini</h3>
                            <div class="flex flex-wrap gap-2">
                            <a :href="shareLinks.facebook" target="_blank" rel="noopener"
                                class="article-share-btn bg-[#1877f2] hover:bg-[#1565d8]">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                            <a :href="shareLinks.x" target="_blank" rel="noopener"
                                class="article-share-btn bg-zinc-800 hover:bg-zinc-700">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                X
                            </a>
                            <a :href="shareLinks.whatsapp" target="_blank" rel="noopener"
                                class="article-share-btn bg-[#25d366] hover:bg-[#1ebe5d]">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                            <a :href="shareLinks.telegram" target="_blank" rel="noopener"
                                class="article-share-btn bg-[#229ed9] hover:bg-[#1a8fc4]">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                Telegram
                            </a>
                            <button type="button" @click="copyLink"
                                class="article-share-btn"
                                :class="copySuccess ? 'bg-green-600 hover:bg-green-700' : 'bg-zinc-700 hover:bg-zinc-600'">
                                <svg v-if="!copySuccess" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <svg v-else class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ copySuccess ? 'Tersalin!' : 'Salin Link' }}
                            </button>
                            </div>
                        </div>

                        <!-- Journalist card -->
                        <div v-if="article.journalist" class="mt-8 flex items-start gap-5 rounded-xl border border-ink-200 bg-white p-5 shadow-sm">
                            <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-full ring-2 ring-brand-200">
                                <img v-if="article.journalist.photo_path" :src="`/storage/${article.journalist.photo_path}`" :alt="article.journalist.name" class="h-full w-full object-cover" />
                                <div v-else class="flex h-full w-full items-center justify-center bg-brand-100 text-2xl font-black text-brand-600">
                                    {{ article.journalist.name?.charAt(0) }}
                                </div>
                            </div>
                            <div class="min-w-0">
                                <p class="mb-0.5 text-xs font-black uppercase tracking-widest text-brand-600">Ditulis oleh</p>
                                <Link :href="route('journalists.show', article.journalist.slug)" class="text-lg font-black text-ink-900 transition-colors hover:text-brand-600">
                                    {{ article.journalist.name }}
                                </Link>
                                <p v-if="article.journalist.position" class="text-sm text-ink-500">{{ article.journalist.position }}</p>
                                <p v-if="article.journalist.bio" class="mt-2 text-sm leading-relaxed text-ink-600 line-clamp-2">{{ article.journalist.bio }}</p>
                            </div>
                        </div>

                        <!-- Comments -->
                        <section class="mt-10">
                            <div class="mb-6 flex items-center gap-3 border-b border-ink-200 pb-4">
                                <span class="h-6 w-1 rounded-full bg-brand-600"></span>
                                <h2 class="text-xl font-black text-ink-900">Komentar <span class="text-ink-400">({{ article.comments?.length ?? 0 }})</span></h2>
                            </div>

                            <!-- Comment form -->
                            <div v-if="currentUser" class="mb-6 rounded-xl border border-ink-200 bg-white p-5 shadow-sm">
                                <p class="mb-3 text-sm text-ink-600">Komentar sebagai <span class="font-bold text-brand-600">{{ currentUser.name }}</span></p>
                                <form @submit.prevent="submitComment">
                                    <textarea v-model="form.content" rows="4" placeholder="Tulis komentar Anda (5-1000 karakter)..."
                                        class="w-full rounded-lg border border-ink-200 px-4 py-3 text-sm text-ink-800 placeholder-ink-400 transition-all focus:border-brand-400 focus:ring-2 focus:ring-brand-100 focus:outline-none resize-none" />
                                    <p v-if="form.errors.content" class="mt-1 text-sm text-brand-600">{{ form.errors.content }}</p>
                                    <button type="submit" :disabled="form.processing"
                                        class="mt-3 rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-brand-700 hover:shadow-brand disabled:opacity-50 active:scale-95">
                                        {{ form.processing ? 'Mengirim...' : 'Kirim Komentar' }}
                                    </button>
                                    <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0">
                                        <p v-if="commentSent" class="mt-2 text-sm font-semibold text-green-600">✓ Komentar berhasil dikirim.</p>
                                    </Transition>
                                </form>
                            </div>
                            <div v-else class="mb-6 rounded-xl border border-dashed border-ink-300 p-5 text-center text-sm text-ink-500">
                                <Link :href="route('login')" class="font-bold text-brand-600 hover:underline">Login</Link> untuk memberikan komentar.
                            </div>

                            <!-- Comment list -->
                            <div v-if="!article.comments || article.comments.length === 0"
                                class="rounded-xl border border-dashed border-ink-200 py-12 text-center text-sm text-ink-400">
                                Belum ada komentar. Jadilah yang pertama!
                            </div>
                            <div class="space-y-3">
                                <div v-for="comment in article.comments" :key="comment.id"
                                    class="flex gap-3 rounded-xl border border-ink-200 bg-white p-4 transition-colors hover:border-ink-300">
                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-ink-100 text-sm font-black text-ink-600">
                                        {{ comment.reader?.name?.charAt(0) ?? '?' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="text-sm font-bold text-ink-900">{{ comment.reader?.name ?? 'Anonim' }}</p>
                                            <div v-if="currentUser && currentUser.id === comment.reader_id" class="flex items-center gap-1">
                                                <button @click="startEdit(comment)" class="rounded px-2 py-0.5 text-xs text-ink-400 hover:bg-ink-100 hover:text-blue-600 transition-colors">Edit</button>
                                                <button @click="deleteComment(comment.id)" class="rounded px-2 py-0.5 text-xs text-ink-400 hover:bg-ink-100 hover:text-brand-600 transition-colors">Hapus</button>
                                            </div>
                                        </div>
                                        <div v-if="editingCommentId === comment.id" class="mt-2">
                                            <textarea v-model="editForm.content" rows="3"
                                                class="w-full rounded-lg border border-ink-200 px-3 py-2 text-sm text-ink-800 focus:border-brand-400 focus:ring-1 focus:ring-brand-100 focus:outline-none resize-none" />
                                            <p v-if="editForm.errors.content" class="mt-1 text-xs text-brand-600">{{ editForm.errors.content }}</p>
                                            <div class="mt-2 flex gap-2">
                                                <button @click="submitEdit(comment.id)" :disabled="editForm.processing"
                                                    class="rounded-lg bg-brand-600 px-3 py-1 text-xs font-bold text-white hover:bg-brand-700 disabled:opacity-50">Simpan</button>
                                                <button @click="cancelEdit"
                                                    class="rounded-lg border border-ink-200 px-3 py-1 text-xs text-ink-600 hover:bg-ink-50">Batal</button>
                                            </div>
                                        </div>
                                        <p v-else class="mt-1 text-sm leading-relaxed text-ink-700">{{ comment.content }}</p>
                                        <p class="mt-1.5 text-xs text-ink-400">{{ formatRelative(comment.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </article>

                    <!-- ── RIGHT: Sidebar ── -->
                    <aside class="space-y-5 lg:sticky lg:top-[72px] lg:self-start">

                        <!-- Berita Terbaru (same category, max 3) -->
                        <div v-if="related && related.length > 0" class="overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm">
                            <div class="flex items-center gap-2 border-b border-ink-100 bg-ink-950 px-4 py-3">
                                <span class="h-4 w-1 rounded-full bg-brand-500"></span>
                                <h3 class="text-xs font-black uppercase tracking-widest text-white">Berita Terbaru</h3>
                                <Link v-if="article.category" :href="route('categories.show', article.category.slug)"
                                    class="ml-auto text-xs font-semibold text-brand-400 transition-colors hover:text-brand-300">
                                    Lihat semua →
                                </Link>
                            </div>
                            <div class="divide-y divide-ink-100">
                                <Link v-for="item in related.slice(0, 3)" :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="group flex items-start gap-3 p-4 transition-colors hover:bg-ink-50">
                                    <div class="h-16 w-20 flex-shrink-0 overflow-hidden rounded-lg bg-ink-100">
                                        <img v-if="item.featured_image"
                                            :src="`/storage/${item.featured_image}`"
                                            :alt="item.title"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                        <div v-else class="flex h-full w-full items-center justify-center">
                                            <svg class="h-5 w-5 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="line-clamp-2 text-xs font-semibold leading-snug text-ink-800 transition-colors group-hover:text-brand-600">{{ item.title }}</p>
                                        <p class="mt-1.5 text-xs text-ink-400">{{ formatRelative(item.published_at) }}</p>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm">
                            <div class="flex items-center gap-2 border-b border-ink-100 bg-ink-950 px-4 py-3">
                                <span class="h-4 w-1 rounded-full bg-brand-500"></span>
                                <h3 class="text-xs font-black uppercase tracking-widest text-white">Kategori</h3>
                            </div>
                            <div class="flex flex-wrap gap-2 p-4">
                                <Link v-for="cat in ($page.props.categories as any[])" :key="cat.id"
                                    :href="route('categories.show', cat.slug)"
                                    class="rounded-full border border-ink-200 px-3 py-1 text-xs font-medium text-ink-600 transition-all hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600">
                                    {{ cat.name }}
                                </Link>
                            </div>
                        </div>

                    </aside>
                </div>

                <!-- ── RECOMMENDATION GRID (bottom) ── -->
                <div v-if="related && related.length > 0" class="mt-16 border-t border-ink-200 pt-12">
                    <div class="mb-8 flex items-center gap-3">
                        <span class="h-6 w-1 rounded-full bg-brand-600"></span>
                        <h2 class="text-xl font-black text-ink-900">Rekomendasi Artikel</h2>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <Link v-for="item in related" :key="item.id" :href="route('articles.show', item.slug)"
                            class="group overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-card-hover">
                            <div class="relative aspect-video overflow-hidden bg-ink-100">
                                <img v-if="item.featured_image" :src="`/storage/${item.featured_image}`" :alt="item.title" loading="lazy"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                <div v-else class="flex h-full w-full items-center justify-center">
                                    <svg class="h-10 w-10 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span v-if="item.category" class="absolute left-3 top-3 cat-badge">{{ item.category.name }}</span>
                            </div>
                            <div class="p-4">
                                <h3 class="line-clamp-2 font-sans text-sm font-bold leading-snug text-ink-900 transition-colors group-hover:text-brand-600">{{ item.title }}</h3>
                                <p v-if="item.excerpt" class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-ink-500">{{ item.excerpt }}</p>
                                <div class="mt-3 flex items-center justify-between text-xs text-ink-400">
                                    <span>{{ formatRelative(item.published_at) }}</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ item.view_count?.toLocaleString('id-ID') }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </PortalLayout>
</template>
