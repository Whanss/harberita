<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import { useReadingProgress } from '@/composables/useIntersectionObserver';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps<{
    article: any;
    shareUrl: string;
}>();

const page = usePage();
const currentUser = computed(() => (page.props.auth as any)?.reader);
const { formatRelative, formatDate } = useRelativeTime();
const readingProgress = useReadingProgress();

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
        onSuccess: () => {
            editingCommentId.value = null;
            editForm.reset();
        },
    });
};

const deleteComment = (commentId: number) => {
    if (!confirm('Hapus komentar ini?')) return;
    useForm({}).delete(route('comments.destroy', commentId), {
        preserveScroll: true,
    });
};

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

const getVideoEmbed = (url: string): string | null => {
    if (!url) return null;
    const ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
    if (ytMatch) return `https://www.youtube.com/embed/${ytMatch[1]}`;
    const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
    if (vimeoMatch) return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
    return null;
};
</script>

<template>
    <PortalLayout>
        <Head :title="article.title" />

        <!-- Reading progress bar -->
        <div class="fixed top-0 left-0 z-[60] h-1 bg-red-600 transition-all duration-100" :style="{ width: `${readingProgress}%` }"></div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Main article -->
                <article class="lg:col-span-2">
                    <!-- Breadcrumb -->
                    <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500">
                        <Link :href="route('home')" class="transition-colors hover:text-red-600">Beranda</Link>
                        <span>/</span>
                        <Link v-if="article.category" :href="route('categories.show', article.category.slug)" class="transition-colors hover:text-red-600">
                            {{ article.category.name }}
                        </Link>
                        <span>/</span>
                        <span class="line-clamp-1 text-gray-700">{{ article.title }}</span>
                    </nav>

                    <!-- Category badge -->
                    <Link v-if="article.category" :href="route('categories.show', article.category.slug)">
                        <span class="mb-3 inline-block rounded bg-red-600 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
                            {{ article.category.name }}
                        </span>
                    </Link>

                    <!-- Title -->
                    <h1 class="mb-3 font-headline text-3xl font-black leading-tight text-ink-950 lg:text-4xl">{{ article.title }}</h1>

                    <!-- Excerpt -->
                    <p v-if="article.excerpt" class="mb-4 font-serif text-lg italic text-ink-600 leading-relaxed border-l-4 border-brand-600 pl-4">{{ article.excerpt }}</p>

                    <!-- Meta -->
                    <div class="mb-6 flex flex-wrap items-center gap-4 border-y border-ink-200 py-3 text-sm text-ink-500">
                        <Link v-if="article.journalist" :href="route('journalists.show', article.journalist.slug)" class="flex items-center gap-2 font-medium text-ink-700 transition-colors hover:text-brand-600">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-600">
                                {{ article.journalist.name?.charAt(0) }}
                            </div>
                            {{ article.journalist.name }}
                        </Link>
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ formatDate(article.published_at) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ article.view_count }} kali dilihat
                        </span>
                    </div>

                    <!-- Featured image -->
                    <div v-if="article.featured_image" class="mb-6 overflow-hidden rounded-xl">
                        <img :src="`/storage/${article.featured_image}`" :alt="article.title" class="w-full object-cover" />
                    </div>

                    <!-- Divider antara gambar dan video -->
                    <div v-if="article.featured_image && article.video_url && getVideoEmbed(article.video_url)" class="mb-6 flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            Video
                        </span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <!-- Video embed -->
                    <div v-if="article.video_url && getVideoEmbed(article.video_url)" class="mb-6 overflow-hidden rounded-xl">
                        <div class="relative aspect-video">
                            <iframe
                                :src="getVideoEmbed(article.video_url)!"
                                class="absolute inset-0 h-full w-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="article-prose" v-html="article.content"></div>

                    <!-- Share buttons -->
                    <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-5">
                        <h3 class="mb-3 font-semibold text-gray-900">Bagikan Berita Ini</h3>
                        <div class="flex flex-wrap gap-2">
                            <a :href="shareLinks.facebook" target="_blank" rel="noopener"
                                class="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-all hover:-translate-y-0.5 hover:shadow-md active:scale-95">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                            <a :href="shareLinks.x" target="_blank" rel="noopener"
                                class="flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition-all hover:-translate-y-0.5 hover:shadow-md active:scale-95">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                Twitter/X
                            </a>
                            <a :href="shareLinks.whatsapp" target="_blank" rel="noopener"
                                class="flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition-all hover:-translate-y-0.5 hover:shadow-md active:scale-95">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                            <a :href="shareLinks.telegram" target="_blank" rel="noopener"
                                class="flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white transition-all hover:-translate-y-0.5 hover:shadow-md active:scale-95">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                Telegram
                            </a>
                            <button
                                type="button"
                                class="flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-medium transition-all active:scale-95"
                                :class="copySuccess ? 'border-green-400 bg-green-50 text-green-700' : 'border-gray-300 bg-white text-gray-700 hover:border-red-300 hover:text-red-600'"
                                @click="copyLink"
                            >
                                <svg v-if="!copySuccess" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ copySuccess ? 'Tersalin!' : 'Salin Tautan' }}
                            </button>
                        </div>
                    </div>

                    <!-- Journalist card -->
                    <div v-if="article.journalist" class="mt-6 flex items-start gap-4 rounded-xl border border-gray-200 bg-white p-5">
                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-xl font-bold text-red-600">
                            {{ article.journalist.name?.charAt(0) }}
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-red-600">Penulis</p>
                            <Link :href="route('journalists.show', article.journalist.slug)" class="font-bold text-gray-900 transition-colors hover:text-red-600">
                                {{ article.journalist.name }}
                            </Link>
                            <p v-if="article.journalist.position" class="text-sm text-gray-500">{{ article.journalist.position }}</p>
                            <p v-if="article.journalist.bio" class="mt-1 text-sm text-gray-600 line-clamp-2">{{ article.journalist.bio }}</p>
                        </div>
                    </div>

                    <!-- Comments -->
                    <section class="mt-8">
                        <div class="mb-4 flex items-center gap-3">
                            <span class="h-6 w-1.5 rounded-full bg-red-600"></span>
                            <h2 class="text-xl font-bold text-gray-900">Komentar ({{ article.comments?.length ?? 0 }})</h2>
                        </div>

                        <!-- Comment form -->
                        <div v-if="currentUser" class="mb-6 rounded-xl border border-gray-200 bg-white p-5">
                            <p class="mb-3 text-sm font-medium text-gray-700">Komentar sebagai <span class="text-red-600">{{ currentUser.name }}</span></p>
                            <form @submit.prevent="submitComment">
                                <textarea
                                    v-model="form.content"
                                    class="w-full rounded-lg border border-gray-200 px-4 py-3 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                                    rows="4"
                                    placeholder="Tulis komentar Anda (5-1000 karakter)..."
                                />
                                <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">{{ form.errors.content }}</p>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="mt-3 rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700 disabled:opacity-60"
                                >
                                    {{ form.processing ? 'Mengirim...' : 'Kirim Komentar' }}
                                </button>
                                <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0">
                                    <p v-if="commentSent" class="mt-2 text-sm font-semibold text-green-600">
                                        ✓ Komentar berhasil dikirim.
                                    </p>
                                </Transition>
                            </form>
                        </div>
                        <div v-else class="mb-6 rounded-xl border border-dashed border-gray-300 p-5 text-center text-sm text-gray-500">
                            <Link :href="route('login')" class="font-semibold text-red-600 hover:underline">Login</Link> untuk memberikan komentar.
                        </div>

                        <!-- Comment list -->
                        <div v-if="!article.comments || article.comments.length === 0" class="rounded-xl border border-dashed border-gray-300 py-10 text-center text-sm text-gray-400">
                            Belum ada komentar. Jadilah yang pertama!
                        </div>
                        <div class="space-y-4">
                            <div v-for="comment in article.comments" :key="comment.id" class="flex gap-3 rounded-xl border border-gray-200 bg-white p-4">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-bold text-gray-600">
                                    {{ comment.reader?.name?.charAt(0) ?? '?' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-gray-900">{{ comment.reader?.name ?? 'Anonim' }}</p>
                                        <!-- Edit/Delete buttons — hanya untuk pemilik komentar -->
                                        <div v-if="currentUser && currentUser.id === comment.reader_id" class="flex items-center gap-1">
                                            <button
                                                @click="startEdit(comment)"
                                                class="rounded px-2 py-0.5 text-xs text-gray-400 hover:bg-gray-100 hover:text-blue-600 transition-colors"
                                            >Edit</button>
                                            <button
                                                @click="deleteComment(comment.id)"
                                                class="rounded px-2 py-0.5 text-xs text-gray-400 hover:bg-gray-100 hover:text-red-600 transition-colors"
                                            >Hapus</button>
                                        </div>
                                    </div>

                                    <!-- Mode edit -->
                                    <div v-if="editingCommentId === comment.id" class="mt-2">
                                        <textarea
                                            v-model="editForm.content"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                                            rows="3"
                                        />
                                        <p v-if="editForm.errors.content" class="mt-1 text-xs text-red-600">{{ editForm.errors.content }}</p>
                                        <div class="mt-2 flex gap-2">
                                            <button
                                                @click="submitEdit(comment.id)"
                                                :disabled="editForm.processing"
                                                class="rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                                            >Simpan</button>
                                            <button
                                                @click="cancelEdit"
                                                class="rounded border border-gray-200 px-3 py-1 text-xs text-gray-600 hover:bg-gray-50"
                                            >Batal</button>
                                        </div>
                                    </div>

                                    <!-- Mode tampil -->
                                    <p v-else class="mt-1 text-sm text-gray-700">{{ comment.content }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ formatRelative(comment.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </article>

                <!-- Sidebar -->
                <aside class="space-y-6">
                    <div class="rounded-xl border border-gray-200 bg-white p-5">
                        <div class="mb-3 flex items-center gap-2">
                            <span class="h-5 w-1 rounded-full bg-red-600"></span>
                            <h3 class="font-bold text-gray-900">Jelajahi Kategori</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                v-for="cat in ($page.props.categories as any[])"
                                :key="cat.id"
                                :href="route('categories.show', cat.slug)"
                                class="rounded-full border border-gray-200 px-3 py-1 text-xs font-medium text-gray-600 transition-all hover:border-red-400 hover:text-red-600"
                            >
                                {{ cat.name }}
                            </Link>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </PortalLayout>
</template>
