<template>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Two-Factor Authentication</h2>
                <button
                    @click="$emit('close')"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6L6 18z" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    const emit = defineEmits<{
        close: [];
    }>();
</script>
