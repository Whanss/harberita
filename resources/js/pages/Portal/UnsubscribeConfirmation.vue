<script setup lang="ts">
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <PortalLayout>
        <Head title="Berhenti Berlangganan" />

        <div class="flex min-h-[60vh] items-center justify-center px-4 py-16">
            <div class="max-w-md text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <h1 class="mb-3 text-2xl font-bold text-gray-900">Anda telah berhenti berlangganan</h1>
                <p class="mb-8 text-gray-500">Anda tidak akan menerima notifikasi email berita terbaru dari kami. Anda dapat berlangganan kembali kapan saja.</p>
                <Link
                    :href="route('home')"
                    class="inline-block rounded-lg bg-red-600 px-6 py-3 font-semibold text-white transition-colors hover:bg-red-700"
                >
                    Kembali ke Beranda
                </Link>
            </div>
        </div>
    </PortalLayout>
</template>
