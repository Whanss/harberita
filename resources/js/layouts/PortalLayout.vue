<script setup lang="ts">
import { useScrolled } from '@/composables/useIntersectionObserver';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const categories = computed(() => (page.props.categories as any[]) ?? []);
const currentUser = computed(() => (page.props.auth as any)?.reader ?? null);
const mobileMenuOpen = ref(false);
const moreMenuOpen = ref(false);
const scrolled = useScrolled(20);

const MAX_VISIBLE_CATS = 7;
const visibleCategories = computed(() => categories.value.slice(0, MAX_VISIBLE_CATS));
const hiddenCategories = computed(() => categories.value.slice(MAX_VISIBLE_CATS));
const hasMoreCategories = computed(() => hiddenCategories.value.length > 0);

const currentUrl = computed(() => page.url);
const isHome = computed(() => currentUrl.value === '/' || currentUrl.value.startsWith('/?'));
const isArchive = computed(() => currentUrl.value.startsWith('/arsip'));
const activeCategorySlug = computed(() => {
    const match = currentUrl.value.match(/^\/kategori\/([^/?]+)/);
    return match ? match[1] : null;
});

// Close more menu on outside click
const moreMenuRef = ref<HTMLElement | null>(null);
const handleOutsideClick = (e: MouseEvent) => {
    if (moreMenuRef.value && !moreMenuRef.value.contains(e.target as Node)) {
        moreMenuOpen.value = false;
    }
};
onMounted(() => document.addEventListener('click', handleOutsideClick));
onUnmounted(() => document.removeEventListener('click', handleOutsideClick));

const toggleMore = () => {
    moreMenuOpen.value = !moreMenuOpen.value;
};

const subscriptionForm = useForm({ email: '' });
const submitSubscription = () => {
    subscriptionForm.post(route('subscriptions.store'), {
        preserveScroll: true,
        onSuccess: () => subscriptionForm.reset(),
    });
};

const logout = () => { router.post(route('logout')); };
const currentYear = new Date().getFullYear();
</script>

<template>
    <div class="min-h-screen bg-ink-50 font-body text-ink-900 antialiased">

        <!-- ── TOP BAR ── -->
        <div class="border-b border-ink-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-1.5">
                    <span class="hidden text-xs text-ink-400 md:block">
                        {{ new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) }}
                    </span>
                    <div class="flex items-center gap-2">
                        <a href="#" class="flex h-6 w-6 items-center justify-center rounded text-ink-400 transition-colors hover:bg-[#1877f2] hover:text-white" title="Facebook">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="flex h-6 w-6 items-center justify-center rounded text-ink-400 transition-colors hover:bg-ink-900 hover:text-white" title="X">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="flex h-6 w-6 items-center justify-center rounded text-ink-400 transition-colors hover:bg-[#e4405f] hover:text-white" title="Instagram">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <div class="mx-1 h-3 w-px bg-ink-200"></div>
                        <Link :href="route('contact.index')" class="text-xs text-ink-400 transition-colors hover:text-brand-600">Kontak</Link>
                        <div class="mx-1 h-3 w-px bg-ink-200"></div>
                        <template v-if="currentUser">
                            <div class="flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white">{{ currentUser.name?.charAt(0).toUpperCase() }}</span>
                                <span class="text-xs text-ink-600">{{ currentUser.name }}</span>
                                <button @click="logout" class="text-xs text-ink-400 hover:text-brand-600" title="Keluar">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                </button>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="rounded-full bg-brand-600 px-3 py-0.5 text-xs font-bold text-white shadow-sm transition-all hover:bg-brand-700 hover:shadow-brand">Masuk</Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── HEADER: Logo + ticker + search ── -->
        <div class="bg-white py-4 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-6">
                    <!-- Logo -->
                    <Link :href="route('home')" class="group flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col leading-none">
                                <div class="flex items-baseline gap-0.5">
                                    <span class="font-headline text-2xl font-black tracking-tight text-ink-950 transition-colors group-hover:text-ink-700">HAS</span>
                                    <span class="font-headline text-2xl font-black tracking-tight text-brand-600 transition-colors group-hover:text-brand-700">BERITA</span>
                                    <span class="font-headline text-2xl font-black tracking-tight text-ink-950 transition-colors group-hover:text-ink-700">.id</span>
                                </div>
                                <span class="text-[9px] font-semibold uppercase tracking-[0.2em] text-ink-400">Informasi Terkini &amp; Terpercaya</span>
                            </div>
                        </div>
                    </Link>

                    <!-- Ticker inline -->
                    <div class="hidden flex-1 items-center gap-2 overflow-hidden lg:flex">
                        <div class="overflow-hidden flex-1">
                            <p class="animate-marquee whitespace-nowrap text-xs text-ink-500">
                                Hasberita.id &nbsp;—&nbsp; Informasi Terkini, Akurat, dan Berimbang &nbsp;•&nbsp; Dapatkan berita terbaru setiap saat &nbsp;•&nbsp; Tetap update bersama Hasberita.id &nbsp;•&nbsp;
                            </p>
                        </div>
                    </div>

                    <!-- Search pill -->
                    <form :action="route('search.index')" method="get" class="hidden w-56 md:block">
                        <div class="flex overflow-hidden rounded-full border border-ink-200 bg-ink-50 transition-all focus-within:border-brand-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-brand-100">
                            <input name="q" class="flex-1 bg-transparent px-4 py-2 text-sm focus:outline-none" placeholder="Cari berita..." />
                            <button type="submit" class="flex items-center justify-center px-3 text-ink-400 transition-colors hover:text-brand-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
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
                        class="relative flex items-center gap-1.5 px-3.5 py-3 text-xs font-black uppercase tracking-widest transition-colors hover:bg-ink-800/50 hover:text-white"
                        :class="isHome ? 'text-white' : 'text-ink-400'"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                        <span v-if="isHome" class="absolute bottom-0 left-0 right-0 h-[3px] bg-brand-500"></span>
                    </Link>

                    <!-- Desktop: visible categories -->
                    <div class="hidden items-center md:flex min-w-0">
                        <Link
                            v-for="cat in visibleCategories"
                            :key="cat.id"
                            :href="route('categories.show', cat.slug)"
                            class="relative flex-shrink-0 px-3.5 py-3 text-xs font-bold uppercase tracking-wider transition-all hover:bg-ink-800/50 hover:text-white"
                            :class="activeCategorySlug === cat.slug ? 'text-white' : 'text-ink-400'"
                        >
                            {{ cat.name }}
                            <span v-if="activeCategorySlug === cat.slug" class="absolute bottom-0 left-0 right-0 h-[3px] bg-brand-500"></span>
                        </Link>

                        <!-- More button + mega dropdown -->
                        <div v-if="hasMoreCategories" ref="moreMenuRef" class="relative flex-shrink-0">
                            <button
                                @click.stop="toggleMore"
                                class="relative flex items-center gap-1.5 border-l border-ink-700 px-3.5 py-3 text-xs font-bold uppercase tracking-wider transition-all hover:bg-ink-800/50 hover:text-white"
                                :class="moreMenuOpen || hiddenCategories.some(c => c.slug === activeCategorySlug) ? 'text-white bg-ink-800' : 'text-ink-400'"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                More
                                <svg class="h-3 w-3 transition-transform duration-200" :class="moreMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                                <span v-if="hiddenCategories.some(c => c.slug === activeCategorySlug)" class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-500"></span>
                            </button>

                            <!-- Mega dropdown panel -->
                            <Transition
                                enter-active-class="transition duration-200 ease-out"
                                enter-from-class="opacity-0 -translate-y-1"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 -translate-y-1"
                            >
                                <div
                                    v-if="moreMenuOpen"
                                    class="absolute top-full left-0 z-50 mt-px min-w-[480px] rounded-b-lg border border-ink-700 bg-ink-900 p-4 shadow-2xl"
                                >
                                    <p class="mb-3 text-xs font-black uppercase tracking-widest text-ink-500">Kategori Lainnya</p>
                                    <div class="grid grid-cols-3 gap-1">
                                        <Link
                                            v-for="cat in hiddenCategories"
                                            :key="cat.id"
                                            :href="route('categories.show', cat.slug)"
                                            class="flex items-center gap-2 rounded px-3 py-2 text-sm font-medium transition-colors hover:bg-ink-800 hover:text-white"
                                            :class="activeCategorySlug === cat.slug ? 'text-white bg-ink-800' : 'text-ink-300'"
                                            @click="moreMenuOpen = false"
                                        >
                                            <svg class="h-3 w-3 flex-shrink-0 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                            </svg>
                                            {{ cat.name }}
                                        </Link>
                                    </div>
                                </div>
                            </Transition>
                        </div>
                    </div>

                    <!-- Right: Archive (desktop) + hamburger mobile -->
                    <div class="ml-auto flex items-center">
                        <!-- Archive — desktop only -->
                        <Link
                            :href="route('archive.index')"
                            class="relative hidden items-center gap-1.5 px-3.5 py-3 text-xs font-bold uppercase tracking-wider transition-colors hover:bg-ink-800/50 hover:text-white md:flex"
                            :class="isArchive ? 'text-white' : 'text-ink-400'"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Arsip
                            <span v-if="isArchive" class="absolute bottom-0 left-0 right-0 h-[3px] bg-brand-500"></span>
                        </Link>

                        <!-- Mobile hamburger — di navbar hitam, style sama seperti Home -->
                        <button
                            class="relative flex items-center gap-1.5 border-l border-ink-700 px-4 py-3 text-xs font-black uppercase tracking-widest transition-colors hover:bg-ink-800 hover:text-white md:hidden"
                            :class="mobileMenuOpen ? 'text-white bg-ink-800' : 'text-ink-400'"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                        >
                            <svg v-if="!mobileMenuOpen" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu — full width panel di bawah navbar -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="mobileMenuOpen" class="border-t border-ink-700 bg-ink-900 md:hidden">
                    <!-- Header kategori -->
                    <div class="px-4 pt-3 pb-1">
                        <p class="text-xs font-black uppercase tracking-widest text-ink-500">Kategori</p>
                    </div>
                    <!-- Grid kategori -->
                    <div class="grid grid-cols-2 gap-px bg-ink-800 border-t border-ink-700 mt-2">
                        <Link
                            v-for="cat in categories"
                            :key="cat.id"
                            :href="route('categories.show', cat.slug)"
                            class="relative flex items-center gap-2 bg-ink-900 px-4 py-2.5 text-xs font-bold uppercase tracking-wider transition-colors hover:bg-ink-800 hover:text-white"
                            :class="activeCategorySlug === cat.slug ? 'text-white bg-ink-800' : 'text-ink-400'"
                            @click="mobileMenuOpen = false"
                        >
                            <svg class="h-2.5 w-2.5 flex-shrink-0" :class="activeCategorySlug === cat.slug ? 'text-brand-500' : 'text-ink-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                            {{ cat.name }}
                        </Link>
                    </div>
                    <!-- Bottom links -->
                    <div class="flex border-t border-ink-700">
                        <Link
                            :href="route('archive.index')"
                            class="relative flex flex-1 items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-bold uppercase tracking-wider transition-colors hover:bg-ink-800 hover:text-white"
                            :class="isArchive ? 'text-white bg-ink-800' : 'text-ink-400'"
                            @click="mobileMenuOpen = false"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Arsip
                        </Link>
                        <div class="w-px bg-ink-700"></div>
                        <Link
                            :href="route('contact.index')"
                            class="flex flex-1 items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-ink-400 transition-colors hover:bg-ink-800 hover:text-white"
                            @click="mobileMenuOpen = false"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Kontak
                        </Link>
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

                <!-- Sudah subscribe -->
                <div v-if="(page.props as any).isSubscribed" class="inline-flex items-center gap-2 rounded-full bg-green-500/20 px-6 py-3 text-sm font-semibold text-green-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Kamu sudah berlangganan newsletter kami!
                </div>

                <!-- Sudah login tapi belum subscribe -->
                <form v-else-if="currentUser" class="flex justify-center" @submit.prevent="submitSubscription">
                    <button
                        type="submit"
                        :disabled="subscriptionForm.processing"
                        class="group flex items-center justify-center gap-2 rounded bg-brand-600 px-8 py-2.5 text-sm font-bold text-white transition-all hover:bg-brand-700 hover:shadow-brand disabled:opacity-60"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ subscriptionForm.processing ? 'Memproses...' : 'Langganan Gratis' }}
                    </button>
                </form>

                <!-- Belum login: form email -->
                <template v-else>
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
                    <p class="mt-3 text-xs text-ink-500">
                        Sudah punya akun?
                        <Link :href="route('login')" class="font-semibold text-brand-400 hover:underline">Masuk</Link>
                        untuk langganan 1 klik.
                    </p>
                </template>

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
                            <span class="font-headline text-lg font-black text-white">HAS</span>
                            <span class="font-headline text-lg font-black text-brand-500">BERITA</span>
                            <span class="font-headline text-lg font-black text-white">.id</span>
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
                        <ul class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-sm">
                            <li v-for="cat in categories" :key="cat.id">
                                <Link :href="route('categories.show', cat.slug)" class="flex items-center gap-2 transition-colors hover:text-brand-400">
                                    <svg class="h-2.5 w-2.5 flex-shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="mt-8 border-t border-ink-800 pt-6 flex items-center justify-between text-xs text-ink-600">
                    <p>&copy; {{ currentYear }} Hasberita.id. Seluruh hak cipta dilindungi.</p>
                    <a
                        href="https://github.com/Whanss"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center gap-1.5 text-ink-500 transition-colors hover:text-white"
                        title="GitHub Developer"
                    >
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                        </svg>
                        Whanss
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>
