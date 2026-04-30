<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    category: any;
    articles: any;
}>();

const { formatRelative } = useRelativeTime();
</script>

<template>
    <PortalLayout>
        <Head :title="`Kategori: ${category.name}`" />

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="mb-3 flex items-center gap-2 text-sm text-gray-500">
                    <Link :href="route('home')" class="transition-colors hover:text-red-600">Beranda</Link>
                    <span>/</span>
                    <span class="text-gray-700">{{ category.name }}</span>
                </nav>
                <div class="flex items-center gap-3">
                    <span class="h-8 w-1.5 rounded-full bg-red-600"></span>
                    <h1 class="text-3xl font-bold text-gray-900">{{ category.name }}</h1>
                </div>
                <p v-if="category.description" class="mt-2 ml-5 text-gray-500">{{ category.description }}</p>
            </div>

            <div v-if="articles.data.length === 0" class="rounded-xl border border-dashed border-gray-300 py-20 text-center text-gray-400">
                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                Belum ada artikel dalam kategori ini.
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="item in articles.data"
                    :key="item.id"
                    :href="route('articles.show', item.slug)"
                    class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition-all hover:border-red-200 hover:shadow-lg"
                >
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img
                            v-if="item.featured_image"
                            :src="`/storage/${item.featured_image}`"
                            :alt="item.title"
                            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                        <div v-else class="flex h-full items-center justify-center">
                            <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-4">
                        <h2 class="mb-2 line-clamp-2 font-semibold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h2>
                        <p class="line-clamp-2 text-sm text-gray-500">{{ item.excerpt }}</p>
                        <p class="mt-3 text-xs text-gray-400">{{ formatRelative(item.published_at) }}</p>
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
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
                    v-html="link.label"
                />
            </div>
        </div>
    </PortalLayout>
</template>
