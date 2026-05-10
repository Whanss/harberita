<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    headline: Array<any>;
    latest: Array<any>;
    popular: Array<any>;
    categories: Array<any>;
    categoryArticles: Array<any>;
}>();

const { formatRelative, formatDate } = useRelativeTime();

const catColors = [
    'bg-blue-100 text-blue-700',
    'bg-green-100 text-green-700',
    'bg-purple-100 text-purple-700',
    'bg-orange-100 text-orange-700',
    'bg-pink-100 text-pink-700',
    'bg-teal-100 text-teal-700',
];
</script>

<template>
    <PortalLayout>
        <Head title="Hasberita.id — Informasi Terkini" />
        <!-- REDESIGNED -->

        <!-- ── HERO SECTION ── -->
        <div class="bg-ink-950 pb-10 pt-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Section label -->
                <div class="mb-4 flex items-center gap-3">
                    <div class="h-5 w-1 rounded-full bg-brand-500"></div>
                    <span class="text-xs font-black uppercase tracking-widest text-ink-400">Berita Utama</span>
                    <span class="flex items-center gap-1.5 rounded-sm bg-brand-600/20 px-2 py-0.5 text-xs font-bold text-brand-400">
                        <span class="h-1.5 w-1.5 animate-ping rounded-full bg-brand-400"></span>
                        LIVE
                    </span>
                </div>

                <!-- Empty state -->
                <div v-if="headline.length === 0" class="flex h-64 items-center justify-center rounded-xl border border-ink-800 text-ink-600">
                    <div class="text-center">
                        <svg class="mx-auto mb-2 h-10 w-10 text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <p class="text-sm">Belum ada berita headline.</p>
                    </div>
                </div>

                <!-- Hero grid -->
                <div v-else class="grid grid-cols-12 gap-3">

                    <!-- Main hero (large) -->
                    <Link
                        v-if="headline[0]"
                        :href="route('articles.show', headline[0].slug)"
                        class="group relative col-span-12 overflow-hidden rounded-xl bg-ink-900 lg:col-span-7"
                        style="min-height: 480px"
                    >
                        <img
                            v-if="headline[0].featured_image"
                            :src="`/storage/${headline[0].featured_image}`"
                            :alt="headline[0].title"
                            loading="eager"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <!-- Placeholder if no image -->
                        <div v-else class="absolute inset-0 bg-gradient-to-br from-ink-800 to-ink-950 flex items-center justify-center">
                            <svg class="h-16 w-16 text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/98 via-black/60 to-black/10"></div>
                        <!-- Breaking badge -->
                        <div class="absolute left-4 top-4">
                            <span class="rounded-sm bg-brand-600 px-2.5 py-1 text-xs font-black uppercase tracking-widest text-white shadow-brand">
                                Breaking News
                            </span>
                        </div>
                        <!-- Content -->
                        <div class="absolute inset-x-0 bottom-0 p-6">
                            <span class="mb-2 inline-block rounded-sm bg-white/15 px-2 py-0.5 text-xs font-bold uppercase tracking-wide text-white backdrop-blur-sm">
                                {{ headline[0].category?.name ?? 'Berita' }}
                            </span>
                            <h2 class="mb-2 font-headline text-3xl font-black leading-tight text-white transition-colors group-hover:text-brand-200 lg:text-4xl">
                                {{ headline[0].title }}
                            </h2>
                            <p v-if="headline[0].excerpt" class="mb-3 line-clamp-2 text-sm text-white/70">{{ headline[0].excerpt }}</p>
                            <p v-if="headline[0].journalist?.name" class="mb-2 text-xs text-white/50">
                                Oleh {{ headline[0].journalist.name }}
                            </p>
                            <div class="flex items-center gap-4 text-xs text-white/50">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ formatRelative(headline[0].published_at) }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ headline[0].view_count?.toLocaleString('id-ID') }}
                                </span>
                            </div>
                        </div>
                    </Link>

                    <!-- Secondary headlines stack -->
                    <div class="col-span-12 flex flex-col gap-3 lg:col-span-5">
                        <Link
                            v-for="(item, idx) in headline.slice(1, 5)"
                            :key="item.id"
                            :href="route('articles.show', item.slug)"
                            class="group relative flex flex-1 overflow-hidden rounded-xl bg-ink-900"
                            style="min-height: 108px"
                        >
                            <img
                                v-if="item.featured_image"
                                :src="`/storage/${item.featured_image}`"
                                :alt="item.title"
                                loading="lazy"
                                class="absolute inset-0 h-full w-full object-cover opacity-70 transition-all duration-500 group-hover:scale-105 group-hover:opacity-90"
                            />
                            <div v-else class="absolute inset-0 bg-gradient-to-r from-ink-800 to-ink-900"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                            <!-- Number badge -->
                            <span class="absolute left-0 top-0 bg-brand-600 px-1.5 py-0.5 text-xs font-black text-white">{{ idx + 2 }}</span>
                            <div class="relative flex flex-col justify-center p-4">
                                <span class="mb-1 text-xs font-bold uppercase tracking-wide text-brand-400">{{ item.category?.name }}</span>
                                <h3 class="line-clamp-2 font-sans text-sm font-bold leading-snug text-white transition-colors group-hover:text-brand-200">
                                    {{ item.title }}
                                </h3>
                                <span class="mt-1.5 text-xs text-white/40">{{ formatRelative(item.published_at) }}</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── MAIN CONTENT ── -->
        <div class="bg-ink-50 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 lg:grid-cols-4">

                    <!-- ── LEFT: Main feed ── -->
                    <div class="lg:col-span-3 space-y-12">

                        <!-- BERITA TERBARU -->
                        <section>
                            <div class="mb-6 flex items-center justify-between border-b-[3px] border-brand-600 pb-2">
                                <h2 class="font-sans text-xl font-black uppercase tracking-wide text-ink-900">Berita Terbaru</h2>
                                <Link :href="route('archive.index')" class="btn-brand">
                                    Selengkapnya
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </Link>
                            </div>

                            <div v-if="latest.length === 0" class="rounded-xl border border-dashed border-ink-300 py-12 text-center text-sm text-ink-400">
                                Belum ada berita terbaru.
                            </div>

                            <div v-else>
                                <!-- Top 3 cards -->
                                <div class="mb-4 grid gap-4 sm:grid-cols-3">
                                    <Link
                                        v-for="item in latest.slice(0, 3)"
                                        :key="item.id"
                                        :href="route('articles.show', item.slug)"
                                        class="article-card group"
                                    >
                                        <div class="relative h-48 overflow-hidden bg-ink-100">
                                            <img v-if="item.featured_image" :src="`/storage/${item.featured_image}`" :alt="item.title" loading="lazy" class="article-card-img" />
                                            <div v-else class="flex h-full items-center justify-center bg-ink-100">
                                                <svg class="h-10 w-10 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <span class="absolute left-2 top-2 cat-badge">{{ item.category?.name }}</span>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="line-clamp-2 font-sans text-sm font-bold leading-snug text-ink-900 transition-colors group-hover:text-brand-600">{{ item.title }}</h3>
                                            <p v-if="item.excerpt" class="mt-1.5 line-clamp-3 text-xs text-ink-500 leading-relaxed">{{ item.excerpt }}</p>
                                            <p class="mt-2 flex items-center gap-1.5 text-xs text-ink-400">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ formatRelative(item.published_at) }}
                                            </p>
                                        </div>
                                    </Link>
                                </div>

                                <!-- List rows -->
                                <div class="divide-y divide-ink-100 overflow-hidden rounded-xl border border-ink-200 bg-white">
                                    <Link
                                        v-for="item in latest.slice(3)"
                                        :key="item.id"
                                        :href="route('articles.show', item.slug)"
                                        class="group flex items-start gap-4 p-4 transition-colors hover:bg-ink-50"
                                    >
                                        <div class="h-16 w-24 flex-shrink-0 overflow-hidden rounded-lg bg-ink-100">
                                            <img v-if="item.featured_image" :src="`/storage/${item.featured_image}`" :alt="item.title" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                            <div v-else class="flex h-full items-center justify-center"><svg class="h-6 w-6 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-bold uppercase tracking-wide text-brand-600">{{ item.category?.name }}</span>
                                            <h3 class="mt-0.5 line-clamp-2 font-sans text-sm font-semibold text-ink-900 transition-colors group-hover:text-brand-600">{{ item.title }}</h3>
                                            <p class="mt-1.5 flex items-center gap-1 text-xs text-ink-400">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ formatRelative(item.published_at) }}
                                            </p>
                                        </div>
                                    </Link>
                                </div>
                            </div>
                        </section>

                        <!-- KATEGORI SECTIONS -->
                        <section v-for="cat in categoryArticles" :key="cat.id">
                            <div class="mb-6 flex items-center justify-between border-b-[3px] border-brand-600 pb-2">
                                <h2 class="font-sans text-xl font-black uppercase tracking-wide text-ink-900">{{ cat.name }}</h2>
                                <Link :href="route('categories.show', cat.slug)" class="btn-brand">
                                    More+
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </Link>
                            </div>

                            <div v-if="!cat.articles || cat.articles.length === 0" class="rounded-xl border border-dashed border-ink-200 py-8 text-center text-sm text-ink-400 italic">
                                Belum ada artikel di kategori ini.
                            </div>

                            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <Link
                                    v-for="item in cat.articles"
                                    :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="article-card group"
                                >
                                    <div class="relative h-40 overflow-hidden bg-ink-100">
                                        <img v-if="item.featured_image" :src="`/storage/${item.featured_image}`" :alt="item.title" loading="lazy" class="article-card-img" />
                                        <div v-else class="flex h-full items-center justify-center bg-ink-100">
                                            <svg class="h-8 w-8 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="line-clamp-2 font-sans text-sm font-bold text-ink-900 transition-colors group-hover:text-brand-600">{{ item.title }}</h3>
                                        <p class="mt-1.5 text-xs text-ink-400">{{ formatRelative(item.published_at) }}</p>
                                    </div>
                                </Link>
                            </div>
                        </section>

                    </div>

                    <!-- ── RIGHT: Sidebar ── -->
                    <aside class="space-y-6">

                        <!-- Search -->
                        <form :action="route('search.index')" method="get">
                            <div class="flex overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm transition-all focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100">
                                <input name="q" class="flex-1 bg-transparent px-4 py-2.5 text-sm focus:outline-none" placeholder="Pencarian..." />
                                <button type="submit" class="flex items-center justify-center bg-ink-900 px-4 text-white transition-colors hover:bg-brand-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </div>
                        </form>

                        <!-- Berita Terkini -->
                        <div class="overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm">
                            <div class="flex items-center gap-2 border-b border-ink-100 border-l-[3px] border-l-brand-500 bg-ink-950 px-4 py-3">
                                <h3 class="font-sans text-xs font-black uppercase tracking-widest text-white">Berita Terkini</h3>
                            </div>
                            <div v-if="latest.length === 0" class="py-8 text-center text-sm text-ink-400">Belum ada berita.</div>
                            <div class="divide-y divide-ink-100">
                                <Link
                                    v-for="item in latest.slice(0, 6)"
                                    :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="group flex items-start gap-3 p-3.5 transition-colors hover:bg-ink-50"
                                >
                                    <div class="h-16 w-20 flex-shrink-0 overflow-hidden rounded-lg bg-ink-100">
                                        <img v-if="item.featured_image" :src="`/storage/${item.featured_image}`" :alt="item.title" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                        <div v-else class="flex h-full items-center justify-center"><svg class="h-5 w-5 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="line-clamp-2 text-xs font-semibold leading-snug text-ink-800 transition-colors group-hover:text-brand-600">{{ item.title }}</p>
                                        <div class="mt-1.5 flex items-center justify-between">
                                            <span class="text-xs font-bold text-brand-600 hover:underline">Selengkapnya</span>
                                            <span class="text-xs text-ink-400">{{ formatDate(item.published_at) }}</span>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Most Read -->
                        <div class="overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm">
                            <div class="flex items-center gap-2 border-b border-ink-100 border-l-[3px] border-l-brand-500 bg-ink-950 px-4 py-3">
                                <h3 class="font-sans text-xs font-black uppercase tracking-widest text-white">Popular</h3>
                                <span class="ml-1 text-xs font-black uppercase tracking-widest text-brand-400">Most Read</span>
                            </div>
                            <div v-if="popular.length === 0" class="py-8 text-center text-sm text-ink-400">Belum ada data.</div>
                            <div class="divide-y divide-ink-100">
                                <Link
                                    v-for="(item, index) in popular.slice(0, 5)"
                                    :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="group flex items-start gap-3 p-3 transition-colors hover:bg-ink-50"
                                >
                                    <span
                                        class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg text-xs font-black"
                                        :class="index === 0 ? 'bg-brand-600 text-white' : index < 3 ? 'bg-ink-900 text-white' : 'bg-ink-100 text-ink-500'"
                                    >{{ index + 1 }}</span>
                                    <div class="min-w-0">
                                        <p class="line-clamp-2 text-xs font-semibold text-ink-800 transition-colors group-hover:text-brand-600">{{ item.title }}</p>
                                        <p class="mt-1 flex items-center gap-1 text-xs text-ink-400">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ item.view_count?.toLocaleString('id-ID') }} views
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>

                        <!-- Newsletter CTA inline -->
                        <div class="rounded-xl bg-ink-950 p-5 text-center">
                            <div class="mb-2 text-2xl">📬</div>
                            <h4 class="mb-1 font-sans text-sm font-black text-white">Newsletter Harian</h4>
                            <p class="mb-3 text-xs text-ink-400">Berita terpilih langsung ke inbox kamu.</p>
                            <Link :href="route('login')" class="block w-full rounded-lg bg-brand-600 py-2 text-xs font-bold text-white transition-colors hover:bg-brand-700">
                                Langganan Gratis
                            </Link>
                        </div>

                        <!-- Kategori list -->
                        <div class="overflow-hidden rounded-xl border border-ink-200 bg-white shadow-sm">
                            <div class="flex items-center gap-2 border-b border-ink-100 border-l-[3px] border-l-brand-500 bg-ink-950 px-4 py-3">
                                <h3 class="font-sans text-xs font-black uppercase tracking-widest text-white">Kategori</h3>
                            </div>
                            <div class="p-2">
                                <Link
                                    v-for="(cat, index) in categories"
                                    :key="cat.id"
                                    :href="route('categories.show', cat.slug)"
                                    class="group flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-ink-700 transition-all hover:bg-brand-50 hover:text-brand-700"
                                >
                                    <div class="flex items-center gap-2.5">
                                        <svg class="h-3 w-3 flex-shrink-0 text-brand-500 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        {{ cat.name }}
                                    </div>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-bold" :class="catColors[index % catColors.length]">
                                        {{ cat.articles_count ?? '' }}
                                    </span>
                                </Link>
                            </div>
                        </div>

                    </aside>
                </div>
            </div>
        </div>

    </PortalLayout>
</template>
