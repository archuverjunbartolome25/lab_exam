<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Filter, X, Calendar, Users, BookOpen, Award } from 'lucide-vue-next';

// Props interface
interface Props {
    modelValue: {
        search: string;
        course: string;
        year: string;
        status: string;
        dateRange: string;
        sortBy: string;
        sortOrder: 'asc' | 'desc';
    };
    courses: string[];
    years: string[];
}

const props = defineProps<Props>();

// Emits interface
interface Emits {
    'update:modelValue': [value: Props['modelValue']];
    'filter': [filters: Props['modelValue']];
    'clear': [];
}

const emit = defineEmits<Emits>();

// Local state
const isExpanded = ref(false);
const localFilters = ref({ ...props.modelValue });

// Computed properties
const hasActiveFilters = computed(() => {
    return localFilters.value.search ||
           localFilters.value.course !== 'all' ||
           localFilters.value.year !== 'all' ||
           localFilters.value.status !== 'all' ||
           localFilters.value.dateRange !== 'all';
});

const activeFilterCount = computed(() => {
    let count = 0;
    if (localFilters.value.search) count++;
    if (localFilters.value.course !== 'all') count++;
    if (localFilters.value.year !== 'all') count++;
    if (localFilters.value.status !== 'all') count++;
    if (localFilters.value.dateRange !== 'all') count++;
    return count;
});

// Watch for prop changes
watch(() => props.modelValue, (newValue) => {
    localFilters.value = { ...newValue };
});

// Methods
const updateFilter = (key: keyof Props['modelValue'], value: any) => {
    (localFilters.value as any)[key] = value;
    emitChanges();
};

const emitChanges = () => {
    emit('update:modelValue', { ...localFilters.value });
    emit('filter', { ...localFilters.value });
};

const clearAllFilters = () => {
    localFilters.value = {
        search: '',
        course: 'all',
        year: 'all',
        status: 'all',
        dateRange: 'all',
        sortBy: 'name',
        sortOrder: 'asc'
    };
    emitChanges();
    emit('clear');
};

const clearFilter = (key: keyof Props['modelValue']) => {
    if (key === 'search') {
        localFilters.value[key] = '';
    } else if (key === 'sortOrder') {
        localFilters.value[key] = 'asc';
    } else {
        localFilters.value[key] = 'all';
    }
    emitChanges();
};

const toggleSort = (field: string) => {
    if (localFilters.value.sortBy === field) {
        localFilters.value.sortOrder = localFilters.value.sortOrder === 'asc' ? 'desc' : 'asc';
    } else {
        localFilters.value.sortBy = field;
        localFilters.value.sortOrder = 'asc';
    }
    emitChanges();
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Filter Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <Filter class="h-5 w-5 text-gray-500" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Advanced Filters
                    </h3>
                    <span v-if="hasActiveFilters" class="px-2 py-1 bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300 text-xs font-semibold rounded-full">
                        {{ activeFilterCount }} Active
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        v-if="hasActiveFilters"
                        @click="clearAllFilters"
                        class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                    >
                        Clear All
                    </button>
                    <button
                        @click="isExpanded = !isExpanded"
                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg"
                    >
                        <component :is="isExpanded ? X : Filter" class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Content -->
        <div v-if="isExpanded" class="p-4 space-y-4">
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Search Users
                </label>
                <input
                    :value="localFilters.search"
                    @input="updateFilter('search', ($event.target as HTMLInputElement).value)"
                    type="text"
                    placeholder="Search by name, email, or ID..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                />
            </div>

            <!-- Filter Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Course Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <BookOpen class="inline h-4 w-4 mr-1" />
                        Course
                    </label>
                    <select
                        :value="localFilters.course"
                        @change="updateFilter('course', ($event.target as HTMLSelectElement).value)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Courses</option>
                        <option v-for="course in courses" :key="course" :value="course">
                            {{ course }}
                        </option>
                    </select>
                </div>

                <!-- Year Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <Calendar class="inline h-4 w-4 mr-1" />
                        Year Level
                    </label>
                    <select
                        :value="localFilters.year"
                        @change="updateFilter('year', ($event.target as HTMLSelectElement).value)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Years</option>
                        <option v-for="year in years" :key="year" :value="year">
                            {{ year }}
                        </option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <Users class="inline h-4 w-4 mr-1" />
                        Status
                    </label>
                    <select
                        :value="localFilters.status"
                        @change="updateFilter('status', ($event.target as HTMLSelectElement).value)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                        <option value="graduated">Graduated</option>
                    </select>
                </div>
            </div>

            <!-- Sort Options -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Sort By</h4>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="field in ['name', 'email', 'course', 'year', 'enrolledDate']"
                        :key="field"
                        @click="toggleSort(field)"
                        class="px-3 py-2 text-sm rounded-lg border transition-colors"
                        :class="[
                            localFilters.sortBy === field
                                ? 'border-orange-500 bg-orange-50 text-orange-700 dark:bg-orange-900/20 dark:text-orange-300'
                                : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-gray-400 dark:hover:border-gray-500'
                        ]"
                    >
                        {{ field.charAt(0).toUpperCase() + field.slice(1) }}
                        <span v-if="localFilters.sortBy === field" class="ml-1">
                            {{ localFilters.sortOrder === 'asc' ? '↑' : '↓' }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Active Filters Display -->
            <div v-if="hasActiveFilters" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Active Filters</h4>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-if="localFilters.search"
                        class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
                    >
                        search: {{ localFilters.search }}
                        <button
                            @click="clearFilter('search')"
                            class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        >
                            <X class="h-3 w-3" />
                        </button>
                    </span>
                    <span
                        v-if="localFilters.course && localFilters.course !== 'all'"
                        class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
                    >
                        course: {{ localFilters.course }}
                        <button
                            @click="clearFilter('course')"
                            class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        >
                            <X class="h-3 w-3" />
                        </button>
                    </span>
                    <span
                        v-if="localFilters.year && localFilters.year !== 'all'"
                        class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
                    >
                        year: {{ localFilters.year }}
                        <button
                            @click="clearFilter('year')"
                            class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        >
                            <X class="h-3 w-3" />
                        </button>
                    </span>
                    <span
                        v-if="localFilters.status && localFilters.status !== 'all'"
                        class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
                    >
                        status: {{ localFilters.status }}
                        <button
                            @click="clearFilter('status')"
                            class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        >
                            <X class="h-3 w-3" />
                        </button>
                    </span>
                    <span
                        v-if="localFilters.dateRange && localFilters.dateRange !== 'all'"
                        class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm"
                    >
                        dateRange: {{ localFilters.dateRange }}
                        <button
                            @click="clearFilter('dateRange')"
                            class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        >
                            <X class="h-3 w-3" />
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
