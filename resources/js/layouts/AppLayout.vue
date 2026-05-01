<script setup lang="ts">
import PortalLayout from '@/layouts/PortalLayout.vue';
import { type BreadcrumbItem } from '@/types';

defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <PortalLayout>
        <slot />
    </PortalLayout>
</template>
