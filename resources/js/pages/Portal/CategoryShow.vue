<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    category: any;
    articles: any;
    currentSort: string;
    totalArticles: number;
}>();

const { formatRelative } = useRelativeTime();

const sortOptions = [
    { key: 'latest',  label: 'Terbaru' },
    { key: 'popular', label: 'Terpopuler' },
    { key: 'week',    label: 'Minggu Ini' },
    { key: 'month',   label: 'Bulan Ini' },
];

const activeSort = ref(props.currentSort ?? 'latest');

const setSort = (key: string) => {
    activeSort.value = key;
    router.get(route('categories.show', props.category.slug), { sort: key }, {
        preserveScroll: false,
        preserveState: false,
    });
};
</script>

<template>
    <PortalLayout>
        <Head :title="`${category.name} — Hasberita.id`" />

        <!-- ══════════════════════════════════════
             HERO SECTION
        ══════════════════════════════════════ -->
        <div class="relative overflow-hidden bg-ink-950">
            <!-- Subtle grid pattern overlay -->
            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 40px 40px;">
            </div>
            <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-brand-600/10 blur-3xl"></div>

            <div class="relative mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-4 flex items-center gap-2 text-xs text-ink-600">
                    <Link :href="route('home')" class="transition-colors hover:text-ink-300">Beranda</Link>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-ink-400">{{ category.name }}</span>
                </nav>

                <div class="flex items-center justify-between gap-4">
                    <!-- Left: title + desc -->
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-sm bg-brand-600 px-2 py-0.5 text-xs font-black uppercase tracking-widest text-white">Kategori</span>
                        </div>
                        <h1 class="font-headline text-3xl font-black leading-tight text-white lg:text-4xl">{{ category.name }}</h1>
                        <p class="mt-1.5 max-w-lg text-sm text-ink-500">
                            {{ category.description || `Berita terkini seputar ${category.name.toLowerCase()}.` }}
                        </p>
                    </div>

                    <!-- Right: total artikel -->
                    <div class="hidden flex-shrink-0 text-right md:block">
                        <p class="text-xs font-semibold uppercase tracking-widest text-ink-600">Total Artikel</p>
                        <p class="font-headline text-4xl font-black text-white">{{ totalArticles }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════
             CONTENT
        ══════════════════════════════════════ -->
        <div class="bg-ink-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Empty state -->
                <div v-if="articles.data.length === 0"
                     class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-ink-300 py-24 text-center">
                    <svg class="mb-4 h-14 w-14 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <p class="text-base font-semibold text-ink-500">Belum ada artikel dalam kategori ini.</p>
                    <button @click="setSort('latest')" class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-brand-600 px-5 py-2 text-sm font-bold text-white hover:bg-brand-700">
                        Lihat Semua Artikel
                    </button>
                </div>

                <template v-else>
                    <!-- ── FEATURED ARTICLE (hanya kalau ada lebih dari 1) ── -->
                    <Link
                        v-if="articles.data.length > 1"
                        :href="route('articles.show', articles.data[0].slug)"
                        class="group mb-8 flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition-all hover:shadow-xl md:flex-row"
                    >
                        <!-- Image -->
                        <div class="relative aspect-video w-full flex-shrink-0 overflow-hidden bg-ink-100 md:aspect-auto md:h-auto md:w-5/12">
                            <img
                                v-if="articles.data[0].featured_image"
                                :src="`/storage/${articles.data[0].featured_image}`"
                                :alt="articles.data[0].title"
                                loading="eager"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div v-else class="flex h-full min-h-[240px] items-center justify-center bg-gradient-to-br from-ink-100 to-ink-200">
                                <svg class="h-20 w-20 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <!-- Featured badge -->
                            <div class="absolute left-4 top-4">
                                <span class="rounded-sm bg-brand-600 px-2.5 py-1 text-xs font-black uppercase tracking-widest text-white shadow-brand">
                                    Artikel Terbaru
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex flex-1 flex-col justify-center p-6 lg:p-10">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="h-0.5 w-8 rounded-full bg-brand-600"></span>
                                <span class="text-xs font-bold uppercase tracking-widest text-brand-600">{{ category.name }}</span>
                            </div>
                            <h2 class="mb-4 font-headline text-2xl font-black leading-tight text-ink-900 transition-colors group-hover:text-brand-600 lg:text-3xl">
                                {{ articles.data[0].title }}
                            </h2>
                            <p v-if="articles.data[0].excerpt" class="mb-5 line-clamp-3 text-sm leading-relaxed text-ink-500">
                                {{ articles.data[0].excerpt }}
                            </p>
                            <div class="mb-6 flex flex-wrap items-center gap-4 text-xs text-ink-400">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ formatRelative(articles.data[0].published_at) }}
                                </span>
                                <span v-if="articles.data[0].journalist" class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ articles.data[0].journalist.name }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ articles.data[0].view_count?.toLocaleString('id-ID') }} views
                                </span>
                            </div>
                            <div>
                                <span class="inline-flex items-center gap-2 rounded-full bg-ink-900 px-5 py-2 text-sm font-bold text-white transition-all group-hover:bg-brand-600 group-hover:shadow-brand">
                                    Baca Selengkapnya
                                    <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </Link>

                    <!-- ── FILTER BAR ── -->
                    <div class="mb-6 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-5 w-1 rounded-full bg-brand-600"></div>
                            <h2 class="font-sans text-sm font-black uppercase tracking-widest text-ink-700">Semua Artikel</h2>
                        </div>
                        <!-- Sort tabs -->
                        <div class="flex items-center gap-1 rounded-xl border border-ink-200 bg-white p-1 shadow-sm">
                            <button
                                v-for="opt in sortOptions"
                                :key="opt.key"
                                @click="setSort(opt.key)"
                                :class="[
                                    'rounded-lg px-3 py-1.5 text-xs font-bold transition-all',
                                    activeSort === opt.key
                                        ? 'bg-ink-900 text-white shadow-sm'
                                        : 'text-ink-500 hover:bg-ink-50 hover:text-ink-800'
                                ]"
                            >
                                {{ opt.label }}
                            </button>
                        </div>
                    </div>

                    <!-- ── ARTICLE GRID ── -->
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="item in (articles.data.length > 1 ? articles.data.slice(1) : articles.data)"
                            :key="item.id"
                            :href="route('articles.show', item.slug)"
                            class="group overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl"
                        >
                            <!-- Image -->
                            <div class="relative aspect-video overflow-hidden bg-ink-100">
                                <img
                                    v-if="item.featured_image"
                                    :src="`/storage/${item.featured_image}`"
                                    :alt="item.title"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                />
                                <div v-else class="flex h-full items-center justify-center bg-gradient-to-br from-ink-100 to-ink-200">
                                    <svg class="h-10 w-10 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <!-- View count -->
                                <div class="absolute bottom-2 right-2 flex items-center gap-1 rounded-full bg-black/60 px-2 py-0.5 text-xs text-white/90 backdrop-blur-sm">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ item.view_count?.toLocaleString('id-ID') }}
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-4">
                                <div class="mb-2 flex items-center gap-2">
                                    <span class="rounded-sm bg-brand-50 px-1.5 py-0.5 text-xs font-bold text-brand-700">{{ category.name }}</span>
                                </div>
                                <h3 class="mb-2 line-clamp-2 font-sans text-sm font-bold leading-snug text-ink-900 transition-colors group-hover:text-brand-600">
                                    {{ item.title }}
                                </h3>
                                <p v-if="item.excerpt" class="mb-3 line-clamp-2 text-xs leading-relaxed text-ink-500">
                                    {{ item.excerpt }}
                                </p>
                                <div class="flex items-center justify-between border-t border-ink-100 pt-3 text-xs text-ink-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ formatRelative(item.published_at) }}
                                    </span>
                                    <span v-if="item.journalist" class="max-w-[120px] truncate font-medium text-ink-500">
                                        {{ item.journalist.name }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- ── PAGINATION ── -->
                    <div v-if="articles.links?.length > 3" class="mt-10 flex flex-wrap justify-center gap-2">
                        <template v-for="link in articles.links" :key="link.label">
                            <span
                                v-if="!link.url"
                                class="rounded-lg border px-4 py-2 text-sm font-medium pointer-events-none opacity-40 border-ink-200 bg-white text-ink-700"
                                v-html="link.label"
                            />
                            <Link
                                v-else
                                :href="link.url"
                                :class="[
                                    'rounded-lg border px-4 py-2 text-sm font-medium transition-all',
                                    link.active
                                        ? 'border-brand-600 bg-brand-600 text-white shadow-brand'
                                        : 'border-ink-200 bg-white text-ink-700 hover:border-brand-300 hover:text-brand-600',
                                ]"
                            ><span v-html="link.label" /></Link>
                        </template>
                    </div>
                </template>
            </div>
        </div>

    </PortalLayout>
</template>
