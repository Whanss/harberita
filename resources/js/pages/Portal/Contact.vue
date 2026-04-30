<script setup lang="ts">
import PortalLayout from '@/layouts/PortalLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submit = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <PortalLayout>
        <Head title="Kontak Redaksi" />

        <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <div class="mb-2 flex justify-center">
                    <span class="h-1.5 w-12 rounded-full bg-red-600"></span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Kontak Redaksi</h1>
                <p class="mt-2 text-gray-500">Hubungi kami untuk pertanyaan, koreksi, atau pengiriman berita.</p>
            </div>

            <div class="grid gap-8 lg:grid-cols-5">
                <!-- Contact info -->
                <div class="space-y-4 lg:col-span-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-6">
                        <h2 class="mb-4 font-bold text-gray-900">Informasi Redaksi</h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Nama Redaksi</p>
                                    <p class="font-medium text-gray-900">Portal Berita</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Alamat</p>
                                    <p class="font-medium text-gray-900">Jl. Redaksi No. 1, Jakarta</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Telepon</p>
                                    <p class="font-medium text-gray-900">021-12345678</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Email</p>
                                    <p class="font-medium text-gray-900">redaksi@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact form -->
                <div class="lg:col-span-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-6">
                        <h2 class="mb-5 font-bold text-gray-900">Kirim Pesan</h2>

                        <div v-if="form.wasSuccessful" class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm font-medium text-green-700">
                            ✓ Pesan Anda berhasil dikirim. Kami akan segera merespons.
                        </div>

                        <form class="space-y-4" @submit.prevent="submit">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Nama <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                                        placeholder="Nama lengkap"
                                    />
                                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                                        placeholder="email@contoh.com"
                                    />
                                    <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Subjek <span class="text-red-500">*</span></label>
                                <input
                                    v-model="form.subject"
                                    type="text"
                                    class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                                    placeholder="Subjek pesan"
                                />
                                <p v-if="form.errors.subject" class="mt-1 text-xs text-red-600">{{ form.errors.subject }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Pesan <span class="text-red-500">*</span></label>
                                <textarea
                                    v-model="form.message"
                                    rows="6"
                                    class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-400 focus:outline-none"
                                    placeholder="Tulis pesan Anda..."
                                />
                                <p v-if="form.errors.message" class="mt-1 text-xs text-red-600">{{ form.errors.message }}</p>
                            </div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full rounded-lg bg-red-600 py-3 font-semibold text-white transition-colors hover:bg-red-700 disabled:opacity-60"
                            >
                                {{ form.processing ? 'Mengirim...' : 'Kirim Pesan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </PortalLayout>
</template>
