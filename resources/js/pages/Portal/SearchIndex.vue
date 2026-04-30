<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    q: string;
    results: any;
}>();

const { formatRelative } = useRelativeTime();
</script>

<template>
    <PortalLayout>
        <Head :title="`Pencarian: ${q}`" />

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="mb-3 flex items-center gap-2 text-sm text-gray-500">
                    <Link :href="route('home')" class="transition-colors hover:text-red-600">Beranda</Link>
                    <span>/</span>
                    <span class="text-gray-700">Pencarian</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">
                    Hasil pencarian: <span class="text-red-600">"{{ q }}"</span>
                </h1>
                <p class="mt-1 text-sm text-gray-500">Ditemukan {{ results.total }} artikel</p>
            </div>

            <!-- Search form -->
            <form :action="route('search.index')" method="get" class="mb-8 flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        name="q"
                        :value="q"
                        class="w-full rounded-lg border border-gray-200 py-2.5 pr-4 pl-10 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                        placeholder="Cari berita..."
                    />
                </div>
                <button class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-red-700">Cari</button>
            </form>

            <div v-if="results.data.length === 0" class="rounded-xl border border-dashed border-gray-300 py-20 text-center text-gray-400">
                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Tidak ditemukan hasil untuk kata kunci <strong>"{{ q }}"</strong>.
            </div>

            <div class="space-y-4">
                <Link
                    v-for="item in results.data"
                    :key="item.id"
                    :href="route('articles.show', item.slug)"
                    class="group flex gap-4 rounded-xl border border-gray-200 bg-white p-4 transition-all hover:border-red-200 hover:shadow-md"
                >
                    <div v-if="item.featured_image" class="h-24 w-32 flex-shrink-0 overflow-hidden rounded-lg">
                        <img :src="`/storage/${item.featured_image}`" :alt="item.title" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="mb-1 inline-block text-xs font-semibold text-red-600">{{ item.category?.name }}</span>
                        <h2 class="line-clamp-2 font-semibold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h2>
                        <p class="mt-1 line-clamp-2 text-sm text-gray-500">{{ item.excerpt }}</p>
                        <p class="mt-2 text-xs text-gray-400">{{ formatRelative(item.published_at) }}</p>
                    </div>
                </Link>
            </div>

            <div v-if="results.links?.length > 3" class="mt-8 flex flex-wrap justify-center gap-2">
                <Link
                    v-for="link in results.links"
                    :key="link.label"
                    :href="link.url || ''"
                    :class="[
                        'rounded-lg border px-4 py-2 text-sm font-medium transition-all',
                        link.active ? 'border-red-600 bg-red-600 text-white' : 'border-gray-200 bg-white text-gray-700 hover:border-red-300 hover:text-red-600',
                        !link.url ? 'pointer-events-none opacity-40' : '',
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </PortalLayout>
</template>
