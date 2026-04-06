<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { UserCheck, Plus, Search, Edit, Trash2, Eye, Filter } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Faculty',
        href: '/faculty',
    },
];

const faculty = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const filters = ref({
    department: '',
    employment_type: '',
    status: '',
});

const fetchFaculty = async () => {
    loading.value = true;
    try {
        const params = new URLSearchParams({
            page: currentPage.value.toString(),
            per_page: '15',
        });

        if (searchQuery.value) {
            params.append('search', searchQuery.value);
        }

        Object.entries(filters.value).forEach(([key, value]) => {
            if (value) params.append(key, value);
        });

        const response = await fetch(`/api/faculty?${params}`);
        const data = await response.json();
        faculty.value = data.data;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Failed to fetch faculty:', error);
    } finally {
        loading.value = false;
    }
};

const deleteFaculty = async (id: number) => {
    if (!confirm('Are you sure you want to delete this faculty member?')) return;

    try {
        await fetch(`/api/faculty/${id}`, {
            method: 'DELETE',
        });
        await fetchFaculty();
    } catch (error) {
        console.error('Failed to delete faculty:', error);
    }
};

onMounted(fetchFaculty);

const handleSearch = () => {
    currentPage.value = 1;
    fetchFaculty();
};

const handleFilter = () => {
    currentPage.value = 1;
    fetchFaculty();
};

const changePage = (page: number) => {
    currentPage.value = page;
    fetchFaculty();
};
</script>

<template>
    <Head title="Faculty" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Faculty</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage faculty information and records</p>
                </div>
                <Link
                    href="/faculty/create"
                    class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                >
                    <Plus class="w-4 h-4 mr-2" />
                    Add Faculty
                </Link>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <input
                            v-model="searchQuery"
                            @input="handleSearch"
                            type="text"
                            placeholder="Search faculty..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        />
                    </div>
                    <select
                        v-model="filters.department"
                        @change="handleFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    >
                        <option value="">All Departments</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Information Systems">Information Systems</option>
                        <option value="Mathematics">Mathematics</option>
                    </select>
                    <select
                        v-model="filters.employment_type"
                        @change="handleFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    >
                        <option value="">All Types</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Adjunct">Adjunct</option>
                    </select>
                    <select
                        v-model="filters.status"
                        @change="handleFilter"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    >
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                </div>
            </div>

            <!-- Faculty Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Faculty ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Department
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Position
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-if="loading">
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Loading...
                                </td>
                            </tr>
                            <tr v-else-if="faculty.length === 0">
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No faculty found
                                </td>
                            </tr>
                            <tr v-for="member in faculty" :key="member.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ member.faculty_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ member.last_name }}, {{ member.first_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ member.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ member.department }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ member.position }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'px-2 py-1 text-xs rounded-full': true,
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': member.employment_type === 'Full-time',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': member.employment_type === 'Part-time',
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300': member.employment_type === 'Contract',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': member.employment_type === 'Adjunct',
                                        }"
                                    >
                                        {{ member.employment_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="{
                                            'px-2 py-1 text-xs rounded-full': true,
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': member.status === 'Active',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': member.status === 'Inactive',
                                            'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': member.status === 'On Leave',
                                        }"
                                    >
                                        {{ member.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <Link
                                            :href="`/faculty/${member.id}`"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            :href="`/faculty/${member.id}/edit`"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="deleteFaculty(member.id)"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing page {{ currentPage }} of {{ totalPages }}
                        </div>
                        <div class="flex space-x-2">
                            <button
                                @click="changePage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
                            >
                                Previous
                            </button>
                            <button
                                @click="changePage(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
