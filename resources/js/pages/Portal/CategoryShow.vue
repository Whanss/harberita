<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    category: any;
    articles: any;
}>();

const { formatRelative, formatDate } = useRelativeTime();
</script>

<template>
    <PortalLayout>
        <Head :title="`${category.name} — Hasberita.id`" />

        <!-- ── CATEGORY HERO BANNER ── -->
        <div class="bg-ink-950 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-4 flex items-center gap-2 text-xs text-ink-500">
                    <Link :href="route('home')" class="transition-colors hover:text-white">Beranda</Link>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-ink-300">{{ category.name }}</span>
                </nav>

                <div class="flex items-end justify-between">
                    <div>
                        <div class="mb-2 flex items-center gap-3">
                            <span class="rounded-sm bg-brand-600 px-2.5 py-1 text-xs font-black uppercase tracking-widest text-white">Kategori</span>
                        </div>
                        <h1 class="font-headline text-4xl font-black text-white lg:text-5xl">{{ category.name }}</h1>
                        <p v-if="category.description" class="mt-2 max-w-xl text-sm text-ink-400">{{ category.description }}</p>
                    </div>
                    <div class="hidden text-right md:block">
                        <p class="text-xs text-ink-500">Total artikel</p>
                        <p class="font-headline text-3xl font-black text-white">{{ articles.total ?? articles.data?.length }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── CONTENT ── -->
        <div class="bg-ink-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Empty state -->
                <div v-if="articles.data.length === 0" class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-ink-300 py-24 text-center">
                    <svg class="mb-4 h-14 w-14 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-base font-semibold text-ink-500">Belum ada artikel dalam kategori ini.</p>
                    <Link :href="route('home')" class="mt-4 btn-brand">Kembali ke Beranda</Link>
                </div>

                <div v-else>
                    <!-- Featured article (first one, large) -->
                    <Link
                        :href="route('articles.show', articles.data[0].slug)"
                        class="group mb-8 flex overflow-hidden rounded-2xl bg-white shadow-sm transition-all hover:shadow-lg"
                    >
                        <div class="relative h-64 w-2/5 flex-shrink-0 overflow-hidden bg-ink-100 md:h-72">
                            <img
                                v-if="articles.data[0].featured_image"
                                :src="`/storage/${articles.data[0].featured_image}`"
                                :alt="articles.data[0].title"
                                loading="eager"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                            <div v-else class="flex h-full items-center justify-center">
                                <svg class="h-16 w-16 text-ink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col justify-center p-6 lg:p-8">
                            <span class="mb-3 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-brand-600">
                                <span class="h-1 w-4 rounded-full bg-brand-600"></span>
                                Artikel Terbaru
                            </span>
                            <h2 class="mb-3 font-headline text-xl font-black leading-snug text-ink-900 transition-colors group-hover:text-brand-600 lg:text-2xl">
                                {{ articles.data[0].title }}
                            </h2>
                            <p v-if="articles.data[0].excerpt" class="mb-4 line-clamp-2 text-sm text-ink-500">{{ articles.data[0].excerpt }}</p>
                            <div class="flex items-center gap-4 text-xs text-ink-400">
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
                                    {{ articles.data[0].view_count?.toLocaleString('id-ID') }}
                                </span>
                            </div>
                            <div class="mt-5">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-600 px-4 py-1.5 text-xs font-bold text-white transition-all group-hover:bg-brand-700 group-hover:shadow-brand">
                                    Baca Selengkapnya
                                    <svg class="h-3 w-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </Link>

                    <!-- Section label -->
                    <div class="mb-5 flex items-center gap-3">
                        <div class="h-5 w-1 rounded-full bg-brand-600"></div>
                        <h2 class="font-sans text-sm font-black uppercase tracking-widest text-ink-700">Semua Artikel</h2>
                        <div class="flex-1 border-t border-ink-200"></div>
                    </div>

                    <!-- Grid: rest of articles -->
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="item in articles.data.slice(1)"
                            :key="item.id"
                            :href="route('articles.show', item.slug)"
                            class="group overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm transition-all hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-lg"
                        >
                            <div class="relative h-48 overflow-hidden bg-ink-100">
                                <img
                                    v-if="item.featured_image"
                                    :src="`/storage/${item.featured_image}`"
                                    :alt="item.title"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div v-else class="flex h-full items-center justify-center">
                                    <svg class="h-12 w-12 text-ink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <!-- View count badge -->
                                <div class="absolute bottom-2 right-2 flex items-center gap-1 rounded-full bg-black/60 px-2 py-0.5 text-xs text-white backdrop-blur-sm">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ item.view_count?.toLocaleString('id-ID') }}
                                </div>
                            </div>
                            <div class="p-4">
                                <h2 class="mb-2 line-clamp-2 font-sans text-sm font-bold leading-snug text-ink-900 transition-colors group-hover:text-brand-600">{{ item.title }}</h2>
                                <p v-if="item.excerpt" class="mb-3 line-clamp-2 text-xs text-ink-500">{{ item.excerpt }}</p>
                                <div class="flex items-center justify-between text-xs text-ink-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ formatRelative(item.published_at) }}
                                    </span>
                                    <span v-if="item.journalist" class="truncate max-w-[120px]">{{ item.journalist.name }}</span>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Pagination -->
                    <div v-if="articles.links?.length > 3" class="mt-10 flex flex-wrap justify-center gap-2">
                        <Link
                            v-for="link in articles.links"
                            :key="link.label"
                            :href="link.url || ''"
                            :class="[
                                'rounded-lg border px-4 py-2 text-sm font-medium transition-all',
                                link.active
                                    ? 'border-brand-600 bg-brand-600 text-white shadow-brand'
                                    : 'border-ink-200 bg-white text-ink-700 hover:border-brand-300 hover:text-brand-600',
                                !link.url ? 'pointer-events-none opacity-40' : '',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>

    </PortalLayout>
</template>
