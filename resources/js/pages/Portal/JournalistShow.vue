<script setup lang="ts">
import { useRelativeTime } from '@/composables/useRelativeTime';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    journalist: any;
    articles: any;
}>();

const { formatRelative } = useRelativeTime();
</script>

<template>
    <PortalLayout>
        <Head :title="`${journalist.name} — Jurnalis`" />

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Journalist profile card -->
            <div class="mb-10 overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <div class="h-24 bg-gradient-to-r from-red-600 to-red-800"></div>
                <div class="px-6 pb-6">
                    <div class="-mt-10 mb-4 flex items-end gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full border-4 border-white bg-red-100 text-3xl font-bold text-red-600 shadow-md">
                            {{ journalist.name?.charAt(0) }}
                        </div>
                        <div class="pb-1">
                            <h1 class="text-2xl font-bold text-gray-900">{{ journalist.name }}</h1>
                            <p v-if="journalist.position" class="text-sm font-medium text-red-600">{{ journalist.position }}</p>
                        </div>
                    </div>
                    <p v-if="journalist.bio" class="max-w-2xl text-gray-600 leading-relaxed">{{ journalist.bio }}</p>
                </div>
            </div>

            <!-- Articles -->
            <div class="mb-4 flex items-center gap-3">
                <span class="h-6 w-1.5 rounded-full bg-red-600"></span>
                <h2 class="text-xl font-bold text-gray-900">Artikel oleh {{ journalist.name }}</h2>
                <span class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-600">{{ articles.total }}</span>
            </div>

            <div v-if="articles.data.length === 0" class="rounded-xl border border-dashed border-gray-300 py-16 text-center text-gray-400">
                Belum ada artikel dari jurnalis ini.
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="item in articles.data"
                    :key="item.id"
                    :href="route('articles.show', item.slug)"
                    class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition-all hover:border-red-200 hover:shadow-lg"
                >
                    <div class="h-44 overflow-hidden bg-gray-100">
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
                    </div>
                    <div class="p-4">
                        <span class="mb-1 inline-block text-xs font-semibold text-red-600">{{ item.category?.name }}</span>
                        <h3 class="line-clamp-2 font-semibold text-gray-900 transition-colors group-hover:text-red-600">{{ item.title }}</h3>
                        <p class="mt-2 text-xs text-gray-400">{{ formatRelative(item.published_at) }}</p>
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
                    v-html="link.label"
                />
            </div>
        </div>
    </PortalLayout>
</template>
