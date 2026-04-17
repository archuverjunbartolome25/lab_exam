<script setup lang="ts">
// =====================================================
// PART 5: DATA FLOW (LANDONG)
// =====================================================
// Implement:
// One-way data flow (parent → child) 
// Controlled input (search user)

import { ref, computed, watch } from 'vue';
import { Search, X } from 'lucide-vue-next';

// Props - One-way data flow from parent to child
interface Props {
    modelValue: string;           // Current value from parent
    placeholder?: string;         // Optional placeholder
    disabled?: boolean;          // Optional disabled state
    debounceMs?: number;         // Optional debounce delay
    showClearButton?: boolean;   // Optional clear button
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Search...',
    disabled: false,
    debounceMs: 300,
    showClearButton: true
});

// Emits - Child communicates back to parent
interface Emits {
    'update:modelValue': [value: string];  // Emit value changes to parent
    'search': [query: string];            // Emit search event
    'clear': [];                         // Emit clear event
}

const emit = defineEmits<Emits>();

// Local state for controlled input
const localValue = ref(props.modelValue);
const isFocused = ref(false);

// Computed properties
const hasValue = computed(() => localValue.value.trim().length > 0);
const showClear = computed(() => props.showClearButton && hasValue.value && isFocused.value);

// Watch for prop changes (one-way data flow: parent → child)
watch(() => props.modelValue, (newValue) => {
    localValue.value = newValue;
});

// Debounced search - Controlled input pattern
let debounceTimer: number | null = null;
const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const value = target.value;
    
    // Update local state immediately for responsive UI
    localValue.value = value;
    
    // Clear existing timer
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }
    
    // Set new timer for debounced search
    debounceTimer = setTimeout(() => {
        // Emit to parent (two-way binding simulation)
        emit('update:modelValue', value);
        emit('search', value);
    }, props.debounceMs);
};

const handleClear = () => {
    localValue.value = '';
    emit('update:modelValue', '');
    emit('clear');
    
    // Focus the input after clearing
    const input = document.querySelector('input') as HTMLInputElement;
    if (input) {
        input.focus();
    }
};

const handleFocus = () => {
    isFocused.value = true;
};

const handleBlur = () => {
    isFocused.value = false;
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        handleClear();
    }
};
</script>

<template>
    <div class="relative">
        <!-- Search Icon -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search 
                class="h-5 w-5 text-gray-400 transition-colors"
                :class="{
                    'text-orange-500': isFocused,
                    'text-gray-400': !isFocused
                }"
            />
        </div>
        
        <!-- Input Field - Controlled Component -->
        <input
            :value="localValue"
            @input="handleInput"
            @focus="handleFocus"
            @blur="handleBlur"
            @keydown="handleKeydown"
            :placeholder="placeholder"
            :disabled="disabled"
            class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
            :class="{
                'ring-2 ring-orange-500 border-orange-500': isFocused,
                'bg-gray-100 dark:bg-gray-800 cursor-not-allowed opacity-50': disabled
            }"
        />
        
        <!-- Clear Button -->
        <button
            v-if="showClear"
            @click="handleClear"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            :class="{
                'opacity-0 invisible': !showClear,
                'opacity-100 visible': showClear
            }"
        >
            <X class="h-4 w-4" />
        </button>
    </div>
</template>

<style scoped>
/* Smooth transitions for clear button */
.opacity-0 {
    opacity: 0;
    transform: scale(0.8);
}

.opacity-100 {
    opacity: 1;
    transform: scale(1);
}

.invisible {
    visibility: hidden;
}

.visible {
    visibility: visible;
}
</style>
