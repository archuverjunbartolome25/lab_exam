<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
    Users, 
    UserPlus, 
    Edit, 
    Trash2, 
    Search, 
    Filter,
    MoreHorizontal,
    Mail,
    Phone,
    Calendar,
    MapPin,
    Shield,
    UserCheck,
    UserX,
    ChevronLeft,
    ChevronRight,
    Download,
    Upload,
    AlertTriangle
} from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Props from controller
const props = defineProps<{
    users: Array<{
        id: number;
        user_num: string;
        name: string;
        fname: string;
        lname: string;
        email: string;
        role: string;
        status: string;
        join_date: string;
        last_login: string;
    }>;
}>();

// Local state
const users = ref(props.users);

// Search and filter
const searchQuery = ref('');
const selectedRole = ref('all');
const selectedStatus = ref('all');

// Modal states
const showDeleteModal = ref(false);
const selectedUser = ref<any>(null);

// Pagination
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Computed filtered users
const filteredUsers = computed(() => {
    return users.value.filter(user => {
        const matchesSearch = user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesRole = selectedRole.value === 'all' || user.role === selectedRole.value;
        const matchesStatus = selectedStatus.value === 'all' || user.status === selectedStatus.value;
        return matchesSearch && matchesRole && matchesStatus;
    });
});

// Paginated users
const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredUsers.value.slice(start, end);
});

// Pagination info
const totalPages = computed(() => Math.ceil(filteredUsers.value.length / itemsPerPage.value));

// Role options
const roleOptions = [
    { value: 'all', label: 'All Roles' },
    { value: 'admin', label: 'Admin' },
    { value: 'faculty', label: 'Faculty' },
    { value: 'student', label: 'Student' }
];

// Status options
const statusOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' }
];

// Stats
const stats = computed(() => ({
    total: users.value.length,
    active: users.value.filter(u => u.status === 'active').length,
    inactive: users.value.filter(u => u.status === 'inactive').length,
    admins: users.value.filter(u => u.role === 'admin').length,
    faculty: users.value.filter(u => u.role === 'faculty').length,
    students: users.value.filter(u => u.role === 'student').length
}));

// Methods
const getStatusColor = (status: string) => {
    return status === 'active' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';
};

const getRoleColor = (role: string) => {
    const colors: Record<string, string> = {
        admin: 'text-purple-600 bg-purple-50',
        faculty: 'text-blue-600 bg-blue-50',
        student: 'text-green-600 bg-green-50'
    };
    return colors[role] || 'text-gray-600 bg-gray-50';
};

const getRoleIcon = (role: string) => {
    const icons: Record<string, any> = {
        admin: Shield,
        faculty: UserCheck,
        student: Users
    };
    return icons[role] || Users;
};

// Edit and delete functions
const editUser = (userId: number) => {
    router.get(`/admin/users/${userId}/edit`);
};

const deleteUser = (userId: number) => {
    const user = users.value.find(u => u.id === userId);
    selectedUser.value = user;
    showDeleteModal.value = true;
};

const confirmDeleteUser = () => {
    if (selectedUser.value) {
        router.delete(`/admin/users/${selectedUser.value.id}`, {
            onSuccess: () => {
                closeDeleteModal();
            },
            onError: (errors) => {
                console.error('Error deleting user:', errors);
                closeDeleteModal();
            }
        });
    }
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    selectedUser.value = null;
};

const changePage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};
</script>

<template>
    <Head title="User Management" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage system users, roles, and permissions</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                    <Upload class="h-4 w-4 mr-2" />
                    Import
                </button>
                <button class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                    <Download class="h-4 w-4 mr-2" />
                    Export
                </button>
                <Link href="/admin/users/create" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <UserPlus class="h-4 w-4 mr-2" />
                    Add User
                </Link>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ stats.total }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <Users class="h-6 w-6 text-blue-600" />
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Admins</p>
                        <p class="text-2xl font-bold text-red-600 mt-2">{{ stats.admins }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg">
                        <Shield class="h-6 w-6 text-red-600" />
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Faculty</p>
                        <p class="text-2xl font-bold text-purple-600 mt-2">{{ stats.faculty }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <UserCheck class="h-6 w-6 text-purple-600" />
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Students</p>
                        <p class="text-2xl font-bold text-orange-600 mt-2">{{ stats.students }}</p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-lg">
                        <Users class="h-6 w-6 text-orange-600" />
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Users</p>
                        <p class="text-2xl font-bold text-green-600 mt-2">{{ stats.active }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <UserCheck class="h-6 w-6 text-green-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <Search class="h-5 w-5 text-gray-400" />
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search users..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white w-full md:w-64"
                        />
                    </div>
                    
                    <!-- Role Filter -->
                    <select v-model="selectedRole" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option v-for="option in roleOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                    
                    <!-- Status Filter -->
                    <select v-model="selectedStatus" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>
                
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ paginatedUsers.length }} of {{ filteredUsers.length }} users
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Join Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">{{ user.name.charAt(0) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                            <Mail class="h-3 w-3 mr-1" />
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusColor(user.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    {{ user.status.charAt(0).toUpperCase() + user.status.slice(1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ user.join_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button 
                                        @click="editUser(user.id)"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        title="Edit User"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </button>
                                    <button 
                                        @click="deleteUser(user.id)"
                                        class="text-gray-400 hover:text-red-600 dark:hover:text-red-400"
                                        title="Delete User"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="More Options">
                                        <MoreHorizontal class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Showing
                            <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                            to
                            <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredUsers.length) }}</span>
                            of
                            <span class="font-medium">{{ filteredUsers.length }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <ChevronLeft class="h-5 w-5" />
                            </button>
                            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                                {{ currentPage }}
                            </button>
                            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <ChevronRight class="h-5 w-5" />
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 transition-opacity z-40" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl shadow-black/20 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <AlertTriangle class="h-6 w-6 text-red-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Delete User
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this user? This action cannot be undone.
                                </p>
                                <div v-if="selectedUser" class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900">{{ selectedUser.name }}</p>
                                    <p class="text-sm text-gray-500">{{ selectedUser.email }}</p>
                                    <p class="text-sm text-gray-500">Role: {{ selectedUser.role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        @click="confirmDeleteUser"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Delete User
                    </button>
                    <button
                        type="button"
                        @click="closeDeleteModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
