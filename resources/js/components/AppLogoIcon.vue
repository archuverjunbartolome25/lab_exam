<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img
        src="/public/ccs-logo.png"
        alt="CCS Logo"
        :class="className"
        v-bind="$attrs"
    />
</template>
