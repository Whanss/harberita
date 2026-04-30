<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    articles: any;
    periods: Array<any>;
    categories: Array<any>;
    filters: { period: string; category: string };
}>();

</script>

<template>
    <PortalLayout>
        <Head title="Arsip Berita" />

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="mb-3 flex items-center gap-2 text-sm text-gray-500">
                    <Link :href="route('home')" class="transition-colors hover:text-red-600">Beranda</Link>
                    <span>/</span>
                    <span class="text-gray-700">Arsip</span>
                </nav>
                <div class="flex items-center gap-3">
                    <span class="h-8 w-1.5 rounded-full bg-red-600"></span>
                    <h1 class="text-3xl font-bold text-gray-900">Arsip Berita</h1>
                </div>
            </div>

            <!-- Filters -->
            <form :action="route('archive.index')" method="get" class="mb-8 flex flex-wrap gap-3 rounded-xl border border-gray-200 bg-white p-4">
                <select name="period" class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-red-400 focus:outline-none">
                    <option value="">Semua Periode</option>
                    <option v-for="period in periods" :key="period.period" :value="period.period" :selected="filters.period === period.period">
                        {{ period.label }} ({{ period.count }})
                    </option>
                </select>
                <select name="category" class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-red-400 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    <option v-for="category in categories" :key="category.slug" :value="category.slug" :selected="filters.category === category.slug">
                        {{ category.name }}
                    </option>
                </select>
                <button class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700">Terapkan Filter</button>
            </form>

            <div v-if="articles.data.length === 0" class="rounded-xl border border-dashed border-gray-300 py-20 text-center text-gray-400">
                Tidak ada artikel untuk filter yang dipilih.
            </div>

            <div class="space-y-3">
                <Link
                    v-for="item in articles.data"
                    :key="item.id"
                    :href="route('articles.show', item.slug)"
                    class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 transition-all hover:border-red-200 hover:shadow-md"
                >
                    <div class="flex-shrink-0 text-center">
                        <p class="text-xs font-semibold text-red-600">{{ new Date(item.published_at).toLocaleDateString('id-ID', { month: 'short' }) }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ new Date(item.published_at).getDate() }}</p>
                        <p class="text-xs text-gray-400">{{ new Date(item.published_at).getFullYear() }}</p>
                    </div>
                    <div class="h-10 w-px bg-gray-200"></div>
                    <div class="min-w-0 flex-1">
                        <span class="mb-1 inline-block text-xs font-semibold text-red-600">{{ item.category?.name }}</span>
                        <h2 class="line-clamp-1 font-semibold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h2>
                        <p class="line-clamp-1 text-sm text-gray-500">{{ item.excerpt }}</p>
                    </div>
                </Link>
            </div>

            <div v-if="articles.links?.length > 3" class="mt-8 flex flex-wrap justify-center gap-2">
                <Link
                    v-for="link in articles.links"
                    :key="link.label"
                    :href="link.url || ''"
                    :class="[
                        'rounded-lg border px-4 py-2 text-sm font-medium transition-all',
                        link.active ? 'border-red-600 bg-red-600 text-white' : 'border-gray-200 bg-white text-gray-700 hover:border-red-300 hover:text-red-600',
                        !link.url ? 'pointer-events-none opacity-40' : '',
                    ]"
                >
                    <span v-html="link.label"></span>
                </Link>
            </div>
        </div>
    </PortalLayout>
</template>
