import { onMounted, onUnmounted, ref, type Ref } from 'vue';

export function useIntersectionObserver(threshold = 0.1) {
    const callbackMap = new Map<HTMLElement, (entry: IntersectionObserverEntry) => void>();
    const observer = ref<IntersectionObserver | null>(null);

    const getObserver = () => {
        if (!observer.value) {
            observer.value = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        const cb = callbackMap.get(entry.target as HTMLElement);
                        if (cb) cb(entry);
                    });
                },
                { threshold },
            );
        }
        return observer.value;
    };

    const observe = (el: HTMLElement, callback: (entry: IntersectionObserverEntry) => void) => {
        callbackMap.set(el, callback);
        getObserver().observe(el);
    };

    const unobserve = (el: HTMLElement) => {
        callbackMap.delete(el);
        observer.value?.unobserve(el);
    };

    onUnmounted(() => {
        observer.value?.disconnect();
        callbackMap.clear();
    });

    return { observe, unobserve };
}

export function useReadingProgress(): Ref<number> {
    const progress = ref(0);

    const update = () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        progress.value = docHeight > 0 ? Math.min(100, (scrollTop / docHeight) * 100) : 0;
    };

    onMounted(() => window.addEventListener('scroll', update, { passive: true }));
    onUnmounted(() => window.removeEventListener('scroll', update));

    return progress;
}

export function useScrolled(offset = 10): Ref<boolean> {
    const scrolled = ref(false);

    const update = () => {
        scrolled.value = window.scrollY > offset;
    };

    onMounted(() => window.addEventListener('scroll', update, { passive: true }));
    onUnmounted(() => window.removeEventListener('scroll', update));

    return scrolled;
}
