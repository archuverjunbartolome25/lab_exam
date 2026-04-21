<script setup lang="ts">
interface Props {
    href: string;
    active?: boolean;
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <a
        :href="href"
        :class="[
            'inline-flex items-center gap-2 text-sm font-medium transition-colors hover:text-primary underline-offset-4 hover:underline',
            active && 'text-primary'
        ]"
    >
        <slot />
    </a>
</template>
