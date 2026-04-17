<script setup lang="ts">
// =====================================================
// PART 3: PROPS VS STATE (BARON)
// =====================================================
// Users Page:
// Store user list in state 
// Pass user data to child components using props 
// Example Flow:
// Parent (Users Page) → Child (User Card)

import { Head } from '@inertiajs/vue3';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { ref, reactive, computed, onMounted } from 'vue';
import { Filter } from 'lucide-vue-next';
import UserCard from '@/components/UserCard.vue';
import ControlledSearch from '@/components/ControlledSearch.vue';
import SearchManager from '@/components/SearchManager.vue';

// TypeScript interface for User
interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    course: string;
    year: string;
    status: string;
    enrolledDate: string;
}

// State management - reactive data (Single Source of Truth)
const state = reactive({
    // User list stored in parent component state
    users: [
        {
            id: 1,
            name: 'John Doe',
            email: 'john.doe@pnc.edu.ph',
            phone: '0912-345-6789',
            course: 'Computer Science',
            year: '3rd Year',
            status: 'active',
            enrolledDate: '2022-06-15',
        },
        {
            id: 2,
            name: 'Jane Smith',
            email: 'jane.smith@pnc.edu.ph',
            phone: '0913-456-7890',
            course: 'Information Technology',
            year: '2nd Year',
            status: 'active',
            enrolledDate: '2023-06-15',
        },
        {
            id: 3,
            name: 'Robert Johnson',
            email: 'robert.johnson@pnc.edu.ph',
            phone: '0914-567-8901',
            course: 'Computer Science',
            year: '4th Year',
            status: 'active',
            enrolledDate: '2021-06-15',
        },
        {
            id: 4,
            name: 'Maria Garcia',
            email: 'maria.garcia@pnc.edu.ph',
            phone: '0915-678-9012',
            course: 'Information Technology',
            year: '1st Year',
            status: 'inactive',
            enrolledDate: '2024-06-15',
        },
    ] as User[],
    searchQuery: '',
    selectedCourse: 'all',
    selectedYear: 'all',
    isLoading: false
});

// Local state for filters (one-way data flow to child components)
const searchQuery = ref('');
const selectedCourse = ref('all');
const selectedYear = ref('all');
const showDataFlowDemo = ref(false);

// Computed property for filtered users
const filteredUsers = computed(() => {
    return state.users.filter(user => {
        const matchesSearch = user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesCourse = selectedCourse.value === 'all' || user.course === selectedCourse.value;
        const matchesYear = selectedYear.value === 'all' || user.year === selectedYear.value;
        
        return matchesSearch && matchesCourse && matchesYear;
    });
});

// Methods for state management
const loadUsers = async () => {
    state.isLoading = true;
    try {
        // In a real app, this would be an API call
        // const response = await fetch('/api/users');
        // state.users = await response.json();
        
        // For now, using the mock data in state
        await new Promise(resolve => setTimeout(resolve, 500)); // Simulate API delay
    } catch (error) {
        console.error('Failed to load users:', error);
    } finally {
        state.isLoading = false;
    }
};

// Event handlers for child component interactions (one-way data flow)
const handleSearchQuery = (query: string) => {
    // Parent receives data from child via props
    searchQuery.value = query;
    console.log('Parent received search query from child:', query);
};

const handleEditUser = (user: User) => {
    console.log('Edit user:', user);
    // Navigate to edit page or open modal
    // Example: window.location.href = `/faculty/users/${user.id}/edit`;
};

const handleDeleteUser = (user: User) => {
    console.log('Delete user:', user);
    // Show confirmation dialog and delete user
    if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        // Update state to remove user
        const index = state.users.findIndex(u => u.id === user.id);
        if (index > -1) {
            state.users.splice(index, 1);
        }
    }
};

const filterUsers = () => {
    // This is handled by computed property
    console.log('Filtering users with:', {
        search: searchQuery.value,
        course: selectedCourse.value,
        year: selectedYear.value
    });
};

// Lifecycle hook
onMounted(() => {
    loadUsers();
    filterUsers();
});
</script>

<template>
    <Head title="Users" />
    
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage student accounts and information</p>
        </div>

        <!-- Filters with Controlled Input -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Controlled Search Input -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Users (Controlled Input)
                    </label>
                    <!-- One-way data flow: searchQuery flows from parent to child -->
                    <ControlledSearch
                        v-model="searchQuery"
                        placeholder="Search users by name or email..."
                        @search="handleSearchQuery"
                        :debounce-ms="300"
                    />
                </div>
                <div class="flex gap-4 items-end">
                    <select
                        v-model="selectedCourse"
                        @change="filterUsers"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Courses</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                    </select>
                    <select
                        v-model="selectedYear"
                        @change="filterUsers"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Years</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Data Flow Demo Toggle -->
        <div class="mb-6">
            <button
                @click="showDataFlowDemo = !showDataFlowDemo"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
            >
                {{ showDataFlowDemo ? 'Hide' : 'Show' }} Data Flow Demo
            </button>
        </div>

        <!-- Data Flow Demo Component -->
        <SearchManager v-if="showDataFlowDemo" class="mb-6" />

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Loading State -->
            <div v-if="state.isLoading" class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">Loading users...</span>
            </div>
            
            <!-- Users Table -->
            <div v-else class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Course & Year
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Enrolled
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- UserCard child component receiving user data via props -->
                    <!-- Parent (Users Page) → Child (User Card) -->
                    <UserCard 
                        v-for="user in filteredUsers" 
                        :key="user.id" 
                        :user="user"
                        :on-edit="handleEditUser"
                        :on-delete="handleDeleteUser"
                    />
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!state.isLoading && filteredUsers.length === 0" class="text-center py-12">
            <Users class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria.</p>
        </div>
    </div>
</template>
