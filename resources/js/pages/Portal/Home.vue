<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import { useIntersectionObserver } from '@/composables/useIntersectionObserver';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps<{
    headline: Array<any>;
    latest: Array<any>;
    popular: Array<any>;
    categories: Array<any>;
    categoryArticles: Array<any>;
}>();

const { formatRelative, formatDate } = useRelativeTime();
const { observe } = useIntersectionObserver(0.06);

const animatedEls = ref<HTMLElement[]>([]);

const revealEl = (el: HTMLElement) => {
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    el.style.opacity = '1';
    el.style.transform = 'translateY(0)';
};

onMounted(() => {
    // Fallback: reveal all elements after 800ms in case IntersectionObserver doesn't fire
    const fallbackTimer = setTimeout(() => {
        animatedEls.value.forEach((el) => {
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }, 800);

    animatedEls.value.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        observe(el, (entry) => {
            if (entry.isIntersecting) {
                clearTimeout(fallbackTimer);
                setTimeout(() => revealEl(el, i), i * 60);
            }
        });
    });
});

const pushRef = (el: any) => {
    if (el) animatedEls.value.push(el as HTMLElement);
};
</script>

<template>
    <PortalLayout>
        <Head title="Portal Berita — Informasi Terkini" />

        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-4">

                <!-- ===== MAIN CONTENT (3/4) ===== -->
                <div class="lg:col-span-3 space-y-8">

                    <!-- HERO: 1 besar + 4 thumbnail kanan -->
                    <section :ref="pushRef">
                        <!-- Section header -->
                        <div class="mb-3 flex items-center gap-3 border-b-2 border-gray-900 pb-2">
                            <h2 class="text-lg font-black uppercase tracking-wide text-gray-900">Berita Utama</h2>
                            <span class="flex items-center gap-1.5 rounded-sm bg-red-600 px-2 py-0.5 text-xs font-bold text-white">
                                <span class="h-1.5 w-1.5 animate-ping rounded-full bg-white"></span>
                                LIVE
                            </span>
                        </div>

                        <div v-if="headline.length === 0" class="rounded-xl border border-dashed border-gray-300 py-16 text-center text-gray-400">
                            <svg class="mx-auto mb-3 h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Belum ada berita headline.
                        </div>

                        <div v-else class="grid grid-cols-3 gap-3">
                            <!-- Main big image (2/3 width) -->
                            <Link
                                v-if="headline[0]"
                                :href="route('articles.show', headline[0].slug)"
                                class="group relative col-span-2 overflow-hidden rounded-lg bg-gray-900"
                                style="min-height: 340px"
                            >
                                <img
                                    v-if="headline[0].featured_image"
                                    :src="`/storage/${headline[0].featured_image}`"
                                    :alt="headline[0].title"
                                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                                <div class="absolute inset-0 bg-red-900/0 transition-colors duration-300 group-hover:bg-red-900/10"></div>
                                <!-- Breaking news badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="rounded-sm bg-red-600 px-2 py-0.5 text-xs font-bold uppercase tracking-wide text-white">
                                        Breaking News
                                    </span>
                                </div>
                                <div class="absolute right-0 bottom-0 left-0 p-5">
                                    <span class="mb-1.5 inline-block rounded-sm bg-white/20 px-2 py-0.5 text-xs font-semibold text-white backdrop-blur-sm">
                                        {{ headline[0].category?.name ?? 'Berita' }}
                                    </span>
                                    <h3 class="mb-1.5 text-lg font-bold leading-snug text-white transition-colors group-hover:text-red-200 lg:text-xl">
                                        {{ headline[0].title }}
                                    </h3>
                                    <p class="line-clamp-2 text-sm text-gray-300">{{ headline[0].excerpt }}</p>
                                    <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ formatRelative(headline[0].published_at) }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ headline[0].view_count?.toLocaleString('id-ID') }}
                                        </span>
                                    </div>
                                </div>
                            </Link>

                            <!-- 4 thumbnail stack (1/3 width) -->
                            <div class="flex flex-col gap-2">
                                <Link
                                    v-for="item in headline.slice(1, 5)"
                                    :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="group relative flex-1 overflow-hidden rounded-lg bg-gray-800"
                                    style="min-height: 78px"
                                >
                                    <img
                                        v-if="item.featured_image"
                                        :src="`/storage/${item.featured_image}`"
                                        :alt="item.title"
                                        class="absolute inset-0 h-full w-full object-cover opacity-80 transition-all duration-300 group-hover:scale-105 group-hover:opacity-100"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                                    <div class="absolute right-0 bottom-0 left-0 p-2">
                                        <p class="line-clamp-2 text-xs font-semibold leading-tight text-white transition-colors group-hover:text-red-200">
                                            {{ item.title }}
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </section>

                    <!-- BERITA TERBARU section -->
                    <section :ref="pushRef">
                        <div class="mb-4 flex items-center justify-between border-b-2 border-gray-900 pb-2">
                            <h2 class="text-lg font-black uppercase tracking-wide text-gray-900">Berita Terbaru</h2>
                            <Link :href="route('archive.index')" class="flex items-center gap-1 rounded bg-red-600 px-3 py-1 text-xs font-bold text-white transition-colors hover:bg-red-700">
                                Selengkapnya
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>

                        <div v-if="latest.length === 0" class="rounded-xl border border-dashed border-gray-300 py-12 text-center text-gray-400">
                            Belum ada berita terbaru.
                        </div>

                        <!-- Grid: 3 kolom card atas + list bawah -->
                        <div v-else>
                            <!-- Top 3 as cards -->
                            <div class="mb-4 grid gap-4 sm:grid-cols-3">
                                <Link
                                    v-for="item in latest.slice(0, 3)"
                                    :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="group overflow-hidden rounded-lg border border-gray-200 bg-white transition-all duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:shadow-lg"
                                >
                                    <div class="relative h-40 overflow-hidden bg-gray-100">
                                        <img
                                            v-if="item.featured_image"
                                            :src="`/storage/${item.featured_image}`"
                                            :alt="item.title"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        />
                                        <div v-else class="flex h-full items-center justify-center">
                                            <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="absolute top-2 left-2 rounded-sm bg-red-600 px-1.5 py-0.5 text-xs font-bold text-white">
                                            {{ item.category?.name }}
                                        </span>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="line-clamp-2 text-sm font-bold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h3>
                                        <p class="mt-1.5 flex items-center gap-1 text-xs text-gray-400">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ formatDate(item.published_at) }}
                                        </p>
                                    </div>
                                </Link>
                            </div>

                            <!-- Rest as compact list -->
                            <div class="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                                <Link
                                    v-for="item in latest.slice(3)"
                                    :key="item.id"
                                    :href="route('articles.show', item.slug)"
                                    class="group flex items-start gap-3 p-3 transition-colors hover:bg-red-50"
                                >
                                    <div class="h-16 w-20 flex-shrink-0 overflow-hidden rounded bg-gray-100">
                                        <img
                                            v-if="item.featured_image"
                                            :src="`/storage/${item.featured_image}`"
                                            :alt="item.title"
                                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        />
                                        <div v-else class="flex h-full items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-semibold text-red-600">{{ item.category?.name }}</span>
                                        <h3 class="line-clamp-2 text-sm font-semibold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h3>
                                        <p class="mt-0.5 flex items-center gap-1 text-xs text-gray-400">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ formatRelative(item.published_at) }}
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </section>

                    <!-- KATEGORI sections (per category) -->
                    <section v-for="cat in categoryArticles" :key="cat.id" :ref="pushRef">
                        <div class="mb-4 flex items-center justify-between border-b-2 border-gray-900 pb-2">
                            <h2 class="text-lg font-black uppercase tracking-wide text-gray-900">{{ cat.name }}</h2>
                            <Link
                                :href="route('categories.show', cat.slug)"
                                class="flex items-center gap-1 rounded bg-red-600 px-3 py-1 text-xs font-bold text-white transition-colors hover:bg-red-700"
                            >
                                More+
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>

                        <div v-if="!cat.articles || cat.articles.length === 0" class="py-8 text-center text-sm text-gray-400 italic">
                            Belum ada artikel di kategori ini.
                        </div>

                        <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <Link
                                v-for="item in cat.articles"
                                :key="item.id"
                                :href="route('articles.show', item.slug)"
                                class="group overflow-hidden rounded-lg border border-gray-200 bg-white transition-all duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:shadow-lg"
                            >
                                <div class="relative h-36 overflow-hidden bg-gray-100">
                                    <img
                                        v-if="item.featured_image"
                                        :src="`/storage/${item.featured_image}`"
                                        :alt="item.title"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                    <div v-else class="flex h-full items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3 class="line-clamp-2 text-sm font-bold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h3>
                                    <p class="mt-1.5 text-xs text-gray-400">{{ formatRelative(item.published_at) }}</p>
                                </div>
                            </Link>
                        </div>
                    </section>

                </div>

                <!-- ===== SIDEBAR (1/4) ===== -->
                <aside class="space-y-6">

                    <!-- Search -->
                    <div :ref="pushRef">
                        <form :action="route('search.index')" method="get">
                            <div class="flex overflow-hidden rounded-lg border border-gray-300">
                                <input
                                    name="q"
                                    class="flex-1 px-3 py-2 text-sm focus:outline-none"
                                    placeholder="Pencarian..."
                                />
                                <button type="submit" class="flex items-center justify-center bg-gray-800 px-3 text-white transition-colors hover:bg-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Breaking / Latest sidebar list -->
                    <div :ref="pushRef">
                        <div class="mb-3 border-b-2 border-gray-900 pb-2">
                            <h3 class="text-sm font-black uppercase tracking-wide text-gray-900">Berita Terkini</h3>
                        </div>
                        <div v-if="latest.length === 0" class="py-6 text-center text-sm text-gray-400">Belum ada berita.</div>
                        <div class="space-y-0 divide-y divide-gray-100">
                            <div
                                v-for="item in latest.slice(0, 6)"
                                :key="item.id"
                                class="group py-3"
                            >
                                <Link :href="route('articles.show', item.slug)" class="block">
                                    <p class="line-clamp-2 text-sm font-semibold text-gray-800 transition-colors group-hover:text-red-600">
                                        {{ item.title }}
                                    </p>
                                    <div class="mt-1.5 flex items-center justify-between">
                                        <Link
                                            :href="route('articles.show', item.slug)"
                                            class="text-xs font-bold uppercase tracking-wide text-red-600 hover:underline"
                                        >
                                            Selengkapnya
                                        </Link>
                                        <span class="text-xs text-gray-400">{{ formatDate(item.published_at) }}</span>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Popular / Most Read -->
                    <div :ref="pushRef">
                        <div class="mb-3 flex items-center gap-3 border-b-2 border-gray-900 pb-2">
                            <h3 class="text-sm font-black uppercase tracking-wide text-gray-900">Popular</h3>
                            <span class="text-sm font-black uppercase tracking-wide text-red-600">Most Read</span>
                        </div>
                        <div v-if="popular.length === 0" class="py-6 text-center text-sm text-gray-400">Belum ada data.</div>
                        <div class="space-y-3">
                            <Link
                                v-for="(item, index) in popular.slice(0, 5)"
                                :key="item.id"
                                :href="route('articles.show', item.slug)"
                                class="group flex items-start gap-3 transition-colors"
                            >
                                <span
                                    class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded text-xs font-black"
                                    :class="index === 0 ? 'bg-red-600 text-white' : index < 3 ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600'"
                                >
                                    {{ index + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-semibold text-gray-800 transition-colors group-hover:text-red-600">{{ item.title }}</p>
                                    <p class="mt-0.5 flex items-center gap-1 text-xs text-gray-400">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ item.view_count?.toLocaleString('id-ID') }} views
                                    </p>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Kategori list -->
                    <div :ref="pushRef">
                        <div class="mb-3 border-b-2 border-gray-900 pb-2">
                            <h3 class="text-sm font-black uppercase tracking-wide text-gray-900">Kategori</h3>
                        </div>
                        <div class="space-y-1">
                            <Link
                                v-for="cat in categories"
                                :key="cat.id"
                                :href="route('categories.show', cat.slug)"
                                class="group flex items-center justify-between rounded px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-red-50 hover:text-red-600"
                            >
                                <span class="flex items-center gap-2">
                                    <svg class="h-3 w-3 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                    {{ cat.name }}
                                </span>
                            </Link>
                        </div>
                    </div>

                </aside>
            </div>
        </div>
    </PortalLayout>
</template>
