<script setup lang="ts">
// =====================================================
// PART 6: ADVANCED FEATURES (SERRANO)
// =====================================================
// Add search filter in Users page
// Add role-based routing (Admin vs User view)
// Prevent access to /reports if not admin

import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { Search, Plus, Download, FileText, TrendingUp, Users, Calendar, BarChart3, Shield, Eye, Edit, Trash2 } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';
import RouteGuard from '@/components/RouteGuard.vue';
import { useRoleBasedAccess } from '@/composables/useRoleBasedAccess';

defineOptions({
    layout: AdminLayout,
});

// Role-based access control - Prevent non-admin access
const { isAdmin, canEditComponent } = useRoleBasedAccess();

// Admin-only reports data
const reports = ref([
    { 
        id: 1, 
        title: 'System Performance Report', 
        type: 'System', 
        date: '2024-03-10', 
        users: 156, 
        status: 'Ready',
        sensitivity: 'confidential',
        category: 'admin'
    },
    { 
        id: 2, 
        title: 'User Activity Report', 
        type: 'Security', 
        date: '2024-03-09', 
        users: 145, 
        status: 'Ready',
        sensitivity: 'internal',
        category: 'admin'
    },
    { 
        id: 3, 
        title: 'Grade Distribution Report', 
        type: 'Academic', 
        date: '2024-03-08', 
        users: 156, 
        status: 'Processing',
        sensitivity: 'public',
        category: 'faculty'
    },
    { 
        id: 4, 
        title: 'Course Enrollment Report', 
        type: 'Enrollment', 
        date: '2024-03-07', 
        users: 200, 
        status: 'Ready',
        sensitivity: 'public',
        category: 'faculty'
    },
    { 
        id: 5, 
        title: 'Faculty Workload Report', 
        type: 'Faculty', 
        date: '2024-03-06', 
        users: 12, 
        status: 'Ready',
        sensitivity: 'internal',
        category: 'admin'
    }
]);

const searchQuery = ref('');
const selectedType = ref('all');
const selectedCategory = ref('all');

const filteredReports = computed(() => {
    return reports.value.filter(report => {
        const matchesSearch = report.title.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesType = selectedType.value === 'all' || report.type === selectedType.value;
        const matchesCategory = selectedCategory.value === 'all' || report.category === selectedCategory.value;
        
        return matchesSearch && matchesType && matchesCategory;
    });
});

const getStatusColor = (status: string) => {
    switch(status) {
        case 'Ready': return 'bg-green-100 text-green-800';
        case 'Processing': return 'bg-yellow-100 text-yellow-800';
        case 'Failed': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getSensitivityColor = (sensitivity: string) => {
    switch(sensitivity) {
        case 'confidential': return 'bg-red-100 text-red-800';
        case 'internal': return 'bg-orange-100 text-orange-800';
        case 'public': return 'bg-green-100 text-green-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getTypeIcon = (type: string) => {
    switch(type) {
        case 'System': return TrendingUp;
        case 'Security': return Shield;
        case 'Academic': return BarChart3;
        case 'Enrollment': return Users;
        case 'Faculty': return FileText;
        default: return FileText;
    }
};

const handleGenerateReport = (reportId: number) => {
    console.log('Generating admin report:', reportId);
    // Admin-only report generation logic
};

const handleDeleteReport = (reportId: number) => {
    if (confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
        const index = reports.value.findIndex(r => r.id === reportId);
        if (index > -1) {
            reports.value.splice(index, 1);
        }
    }
};

const handleDownloadReport = (reportId: number) => {
    console.log('Downloading admin report:', reportId);
    // Admin-only download logic with audit trail
};
</script>

<template>
    <Head title="Admin Reports" />

        <div class="p-6">
            <!-- Admin Access Header - Role-based UI -->
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <Shield class="h-6 w-6 text-red-600 dark:text-red-400 mr-3" />
                    <div>
                        <h1 class="text-2xl font-bold text-red-900 dark:text-red-100">Admin Reports</h1>
                        <p class="text-red-700 dark:text-red-300 mt-1">
                            Administrative reports with elevated access privileges
                        </p>
                    </div>
                </div>
            </div>

            <!-- Admin Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                            <Shield class="h-6 w-6 text-red-600 dark:text-red-300" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Admin Reports</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ reports.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                            <Eye class="h-6 w-6 text-orange-600 dark:text-orange-300" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Views</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">1,247</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <Users class="h-6 w-6 text-purple-600 dark:text-purple-300" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">User Access</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">All Staff</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search admin reports..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                    </div>
                    <select
                        v-model="selectedType"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Types</option>
                        <option value="System">System</option>
                        <option value="Security">Security</option>
                        <option value="Academic">Academic</option>
                        <option value="Enrollment">Enrollment</option>
                        <option value="Faculty">Faculty</option>
                    </select>
                    <select
                        v-model="selectedCategory"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Categories</option>
                        <option value="admin">Admin Only</option>
                        <option value="faculty">Faculty Access</option>
                    </select>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="report in filteredReports" :key="report.id" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <div :class="getSensitivityColor(report.sensitivity)" class="p-3 rounded-lg">
                                    <component :is="getTypeIcon(report.type)" class="h-6 w-6" />
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ report.title }}</h3>
                                        <span :class="getStatusColor(report.status)" class="px-2 py-1 text-xs font-semibold rounded">
                                            {{ report.status }}
                                        </span>
                                        <span :class="getSensitivityColor(report.sensitivity)" class="px-2 py-1 text-xs font-semibold rounded">
                                            {{ report.sensitivity }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span>Type: {{ report.type }}</span>
                                        <span>Category: {{ report.category }}</span>
                                        <span>Date: {{ report.date }}</span>
                                        <span>Users: {{ report.users }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    @click="handleDownloadReport(report.id)"
                                    class="px-3 py-2 text-sm bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center"
                                >
                                    <Download class="h-4 w-4 mr-1" />
                                    Download
                                </button>
                                <button
                                    v-if="canEditComponent(['generate_reports'])"
                                    @click="handleGenerateReport(report.id)"
                                    class="px-3 py-2 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center"
                                >
                                    <Plus class="h-4 w-4 mr-1" />
                                    Generate
                                </button>
                                <button
                                    v-if="canEditComponent(['delete_reports'])"
                                    @click="handleDeleteReport(report.id)"
                                    class="px-3 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center"
                                >
                                    <Trash2 class="h-4 w-4 mr-1" />
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="mt-6 flex justify-center">
                <button class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                    <Shield class="h-5 w-5 mr-2" />
                    Generate System-Wide Admin Report
                </button>
            </div>
        </div>
</template>
