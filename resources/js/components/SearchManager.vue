<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import ControlledSearch from './ControlledSearch.vue';

// Parent component state - single source of truth
const searchQuery = ref('');
const searchHistory = ref<string[]>([]);
const isSearching = ref(false);
const searchResults = ref<string[]>([]);

// Mock data for demonstration
const mockData = [
    'John Doe - Computer Science',
    'Jane Smith - Information Technology', 
    'Robert Johnson - Computer Science',
    'Maria Garcia - Information Technology',
    'David Wilson - Computer Science',
    'Sarah Brown - Information Technology'
];

// One-way data flow: parent → child
// Child receives searchQuery via props
const handleSearch = async (query: string) => {
    console.log('Parent received search query:', query);
    
    if (!query.trim()) {
        searchResults.value = [];
        return;
    }
    
    isSearching.value = true;
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Filter results based on query
    searchResults.value = mockData.filter(item => 
        item.toLowerCase().includes(query.toLowerCase())
    );
    
    isSearching.value = false;
};

const handleClear = () => {
    console.log('Parent received clear event');
    searchQuery.value = '';
    searchResults.value = [];
};

const handleSearchSelect = (result: string) => {
    searchQuery.value = result;
    searchResults.value = [];
    
    // Add to search history
    if (!searchHistory.value.includes(result)) {
        searchHistory.value.unshift(result);
        if (searchHistory.value.length > 5) {
            searchHistory.value.pop();
        }
    }
};

const clearHistory = () => {
    searchHistory.value = [];
};

// Computed properties
const hasResults = computed(() => searchResults.value.length > 0);
const hasHistory = computed(() => searchHistory.value.length > 0);

// Watch search query for logging (demonstrating one-way flow)
watch(searchQuery, (newQuery, oldQuery) => {
    console.log('Search query changed:', { old: oldQuery, new: newQuery });
});
</script>

<template>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            One-Way Data Flow Demo
        </h2>
        
        <!-- Parent State Display -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Parent State (Single Source of Truth)
            </h3>
            <div class="space-y-1 text-sm">
                <p><span class="font-medium">Current Query:</span> "{{ searchQuery }}"</p>
                <p><span class="font-medium">Is Searching:</span> {{ isSearching }}</p>
                <p><span class="font-medium">Results Count:</span> {{ searchResults.length }}</p>
            </div>
        </div>
        
        <!-- Controlled Search Component -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Controlled Search Input
            </label>
            <!-- One-way data flow: searchQuery flows from parent to child -->
            <ControlledSearch
                v-model="searchQuery"
                placeholder="Search users..."
                @search="handleSearch"
                @clear="handleClear"
                :debounce-ms="500"
            />
        </div>
        
        <!-- Search Results -->
        <div v-if="isSearching" class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-orange-500"></div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Searching...</p>
        </div>
        
        <div v-else-if="hasResults" class="space-y-2">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Search Results
            </h3>
            <div 
                v-for="result in searchResults" 
                :key="result"
                @click="handleSearchSelect(result)"
                class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors"
            >
                {{ result }}
            </div>
        </div>
        
        <!-- Search History -->
        <div v-if="hasHistory" class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Recent Searches
                </h3>
                <button 
                    @click="clearHistory"
                    class="text-xs text-orange-600 hover:text-orange-800 dark:text-orange-400"
                >
                    Clear
                </button>
            </div>
            <div class="flex flex-wrap gap-2">
                <span 
                    v-for="item in searchHistory" 
                    :key="item"
                    @click="handleSearchSelect(item)"
                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer transition-colors"
                >
                    {{ item }}
                </span>
            </div>
        </div>
        
        <!-- Data Flow Diagram -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">
                Data Flow Pattern
            </h3>
            <div class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                <p>1. Parent holds <code>searchQuery</code> state</p>
                <p>2. Parent passes <code>searchQuery</code> to child via props ↓</p>
                <p>3. Child emits events back to parent ↑</p>
                <p>4. Parent updates state and re-renders child</p>
            </div>
        </div>
    </div>
</template>
