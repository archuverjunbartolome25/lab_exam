<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div v-if="title" class="mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ title }}</h1>
                <p v-if="description" class="mt-2 text-gray-600 dark:text-gray-400">{{ description }}</p>
            </div>

            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
type Props = {
    title?: string;
    description?: string;
};

defineProps<Props>();
</script>