<script setup lang="ts">
import { useScrolled } from '@/composables/useIntersectionObserver';
import { computed, ref } from 'vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const categories = computed(() => (page.props.categories as any[]) ?? []);
const currentUser = computed(() => (page.props.auth as any)?.reader ?? null);
const mobileMenuOpen = ref(false);
const scrolled = useScrolled(20);

const currentUrl = computed(() => page.url);

const isHome = computed(() => currentUrl.value === '/' || currentUrl.value.startsWith('/?'));
const isArchive = computed(() => currentUrl.value.startsWith('/arsip'));
const activeCategorySlug = computed(() => {
    const match = currentUrl.value.match(/^\/kategori\/([^/?]+)/);
    return match ? match[1] : null;
});

const subscriptionForm = useForm({ email: '' });
const submitSubscription = () => {
    subscriptionForm.post(route('subscriptions.store'), {
        preserveScroll: true,
        onSuccess: () => subscriptionForm.reset(),
    });
};

const logout = () => {
    router.post(route('logout'));
};

const currentYear = new Date().getFullYear();
</script>

<template>
    <div class="min-h-screen bg-ink-50 font-body text-ink-900 antialiased">

        <!-- Ticker bar -->
        <div class="overflow-hidden bg-brand-gradient py-1.5">
            <div class="flex items-center gap-3 px-4">
                <span class="flex-shrink-0 rounded-sm bg-white px-2 py-0.5 text-xs font-black uppercase tracking-wide text-brand-700">
                    Terkini
                </span>
                <div class="overflow-hidden flex-1">
                    <p class="animate-marquee whitespace-nowrap text-xs font-medium text-white/90">
                        📰 Portal Berita Terpercaya &nbsp;•&nbsp; Informasi Terkini, Akurat, dan Berimbang &nbsp;•&nbsp; Selamat datang di Portal Berita &nbsp;•&nbsp; Dapatkan berita terbaru setiap saat
                    </p>
                </div>
            </div>
        </div>

        <!-- Header: Logo + search + social -->
        <div class="border-b border-ink-200 bg-white py-3">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <!-- Logo -->
                    <Link :href="route('home')" class="group flex-shrink-0">
                        <div class="flex flex-col leading-none">
                            <span class="font-sans text-xs font-semibold uppercase tracking-widest text-ink-400">Situs</span>
                            <div class="flex items-baseline gap-1">
                                <span class="font-headline text-2xl font-black text-ink-900 transition-colors group-hover:text-ink-700">PORTAL</span>
                                <span class="font-headline text-2xl font-black text-brand-600 transition-colors group-hover:text-brand-700">BERITA</span>
                            </div>
                        </div>
                    </Link>

                    <!-- Search -->
                    <form :action="route('search.index')" method="get" class="hidden flex-1 max-w-sm md:flex">
                        <div class="flex w-full overflow-hidden rounded border border-ink-300 transition-all focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-100">
                            <input
                                name="q"
                                class="flex-1 px-3 py-2 text-sm focus:outline-none"
                                placeholder="Pencarian berita..."
                            />
                            <button type="submit" class="flex items-center justify-center bg-ink-800 px-3 text-white transition-colors hover:bg-brand-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Social + links -->
                    <div class="hidden items-center gap-3 md:flex">
                        <a href="#" class="flex h-7 w-7 items-center justify-center rounded bg-[#1877f2] text-white transition-opacity hover:opacity-80" title="Facebook">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="flex h-7 w-7 items-center justify-center rounded bg-ink-900 text-white transition-opacity hover:opacity-80" title="Twitter/X">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="flex h-7 w-7 items-center justify-center rounded bg-[#e4405f] text-white transition-opacity hover:opacity-80" title="Instagram">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <div class="h-5 w-px bg-ink-200"></div>
                        <Link :href="route('contact.index')" class="text-xs font-medium text-ink-500 transition-colors hover:text-brand-600">Kontak</Link>

                        <!-- User auth area -->
                        <div class="h-5 w-px bg-ink-200"></div>
                        <template v-if="currentUser">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-700">
                                    {{ currentUser.name?.charAt(0).toUpperCase() }}
                                </span>
                                <span class="text-xs font-medium text-ink-700">{{ currentUser.name }}</span>
                                <button
                                    @click="logout"
                                    class="text-xs font-medium text-ink-400 transition-colors hover:text-red-600"
                                    title="Logout"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="rounded bg-brand-600 px-3 py-1 text-xs font-bold text-white transition-colors hover:bg-brand-700">
                                Masuk
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile menu button -->
                    <button class="rounded p-1.5 text-ink-600 transition-colors hover:bg-ink-100 md:hidden" @click="mobileMenuOpen = !mobileMenuOpen">
                        <svg v-if="!mobileMenuOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Category navbar (black bar) -->
        <nav
            class="sticky top-0 z-50 border-b border-ink-700 bg-ink-950 transition-shadow duration-300"
            :class="scrolled ? 'shadow-lg' : ''"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <!-- Home link -->
                    <Link
                        :href="route('home')"
                        class="relative flex items-center gap-1.5 border-r border-ink-700 px-4 py-3 text-xs font-black uppercase tracking-widest transition-colors hover:text-white"
                        :class="isHome ? 'text-white' : 'text-ink-400 hover:bg-ink-800'"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                        <!-- Active indicator -->
                        <span v-if="isHome" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500"></span>
                    </Link>

                    <!-- Category links -->
                    <div class="hidden items-center md:flex">
                        <Link
                            v-for="cat in categories"
                            :key="cat.id"
                            :href="route('categories.show', cat.slug)"
                            class="relative px-4 py-3 text-xs font-bold uppercase tracking-wider transition-all hover:text-white"
                            :class="activeCategorySlug === cat.slug ? 'text-white' : 'text-ink-400 hover:bg-ink-800'"
                        >
                            {{ cat.name }}
                            <!-- Active indicator -->
                            <span v-if="activeCategorySlug === cat.slug" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500"></span>
                        </Link>
                    </div>

                    <!-- Right: Archive -->
                    <div class="ml-auto hidden items-center gap-0 md:flex">
                        <Link
                            :href="route('archive.index')"
                            class="relative flex items-center gap-1.5 px-4 py-3 text-xs font-bold uppercase tracking-wider transition-colors hover:text-white"
                            :class="isArchive ? 'text-white' : 'text-ink-400 hover:bg-ink-800'"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Arsip
                            <!-- Active indicator -->
                            <span v-if="isArchive" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500"></span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="-translate-y-2 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="-translate-y-2 opacity-0"
            >
                <div v-if="mobileMenuOpen" class="border-t border-ink-700 bg-ink-900 md:hidden">
                    <div class="grid grid-cols-2 gap-px bg-ink-700 p-px">
                        <Link
                            v-for="cat in categories"
                            :key="cat.id"
                            :href="route('categories.show', cat.slug)"
                            class="relative bg-ink-900 px-4 py-2.5 text-xs font-bold uppercase tracking-wider transition-colors hover:bg-ink-800 hover:text-white"
                            :class="activeCategorySlug === cat.slug ? 'text-white' : 'text-ink-400'"
                            @click="mobileMenuOpen = false"
                        >
                            {{ cat.name }}
                            <span v-if="activeCategorySlug === cat.slug" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500"></span>
                        </Link>
                    </div>
                    <div class="flex gap-px bg-ink-700 p-px">
                        <Link
                            :href="route('archive.index')"
                            class="relative flex flex-1 items-center justify-center gap-1.5 bg-ink-900 px-4 py-2.5 text-center text-xs font-bold uppercase tracking-wider transition-colors hover:bg-ink-800 hover:text-white"
                            :class="isArchive ? 'text-white' : 'text-ink-400'"
                            @click="mobileMenuOpen = false"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Arsip
                            <span v-if="isArchive" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500"></span>
                        </Link>
                        <Link :href="route('contact.index')" class="flex-1 bg-ink-900 px-4 py-2.5 text-center text-xs font-bold uppercase tracking-wider text-ink-400 transition-colors hover:bg-ink-800 hover:text-white" @click="mobileMenuOpen = false">Kontak</Link>
                    </div>
                </div>
            </Transition>
        </nav>

        <!-- Page content -->
        <main>
            <slot />
        </main>

        <!-- Newsletter banner -->
        <section class="bg-dark-gradient py-12">
            <div class="mx-auto max-w-3xl px-4 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-brand-600/30">
                    <svg class="h-6 w-6 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="mb-1 font-headline text-2xl font-black text-white">Jangan Lewatkan Berita Terbaru</h2>
                <p class="mb-6 text-sm text-ink-400">Daftarkan email Anda dan dapatkan notifikasi berita terkini langsung di inbox.</p>
                <form class="flex flex-col gap-3 sm:flex-row sm:justify-center" @submit.prevent="submitSubscription">
                    <input
                        v-model="subscriptionForm.email"
                        type="email"
                        placeholder="Masukkan alamat email..."
                        class="w-full rounded border border-ink-700 bg-ink-800 px-4 py-2.5 text-sm text-white placeholder-ink-500 transition-all focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none sm:w-72"
                    />
                    <button
                        type="submit"
                        :disabled="subscriptionForm.processing"
                        class="group flex items-center justify-center gap-2 rounded bg-brand-600 px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-brand-700 hover:shadow-brand disabled:opacity-60"
                    >
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        {{ subscriptionForm.processing ? 'Memproses...' : 'Langganan' }}
                    </button>
                </form>
                <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0">
                    <p v-if="subscriptionForm.errors.email" class="mt-2 text-xs text-brand-400">{{ subscriptionForm.errors.email }}</p>
                    <p v-else-if="subscriptionForm.recentlySuccessful" class="mt-2 text-xs font-semibold text-green-400">✓ Berhasil! Cek email Anda untuk konfirmasi.</p>
                </Transition>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-ink-950 py-10 text-ink-400">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 md:grid-cols-3">
                    <div>
                        <div class="mb-3 flex items-baseline gap-1">
                            <span class="font-headline text-lg font-black text-white">PORTAL</span>
                            <span class="font-headline text-lg font-black text-brand-500">BERITA</span>
                        </div>
                        <p class="text-sm leading-relaxed">Sumber informasi terpercaya dengan liputan berita terkini, akurat, dan berimbang untuk masyarakat Indonesia.</p>
                        <div class="mt-4 flex gap-2">
                            <a href="#" class="flex h-7 w-7 items-center justify-center rounded bg-ink-800 text-ink-400 transition-all hover:bg-brand-600 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="flex h-7 w-7 items-center justify-center rounded bg-ink-800 text-ink-400 transition-all hover:bg-brand-600 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="#" class="flex h-7 w-7 items-center justify-center rounded bg-ink-800 text-ink-400 transition-all hover:bg-brand-600 hover:text-white">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h3 class="mb-3 font-sans text-xs font-black uppercase tracking-widest text-white">Kategori</h3>
                        <ul class="space-y-1.5 text-sm">
                            <li v-for="cat in categories.slice(0, 6)" :key="cat.id">
                                <Link :href="route('categories.show', cat.slug)" class="flex items-center gap-2 transition-colors hover:text-brand-400">
                                    <svg class="h-2.5 w-2.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                    </svg>
                                    {{ cat.name }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="mb-3 font-sans text-xs font-black uppercase tracking-widest text-white">Tautan</h3>
                        <ul class="space-y-1.5 text-sm">
                            <li><Link :href="route('home')" class="flex items-center gap-2 transition-colors hover:text-brand-400"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>Beranda</Link></li>
                            <li><Link :href="route('archive.index')" class="flex items-center gap-2 transition-colors hover:text-brand-400"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>Arsip Berita</Link></li>
                            <li><Link :href="route('contact.index')" class="flex items-center gap-2 transition-colors hover:text-brand-400"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>Kontak Redaksi</Link></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-ink-800 pt-6 text-center text-xs">
                    &copy; {{ currentYear }} Portal Berita. Seluruh hak cipta dilindungi.
                </div>
            </div>
        </footer>
    </div>
</template>
