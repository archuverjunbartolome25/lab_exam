<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { ref, onMounted } from 'vue';
import { FileText, Download, Calendar, Filter, TrendingUp, Users, BookOpen, Award } from 'lucide-vue-next';

defineOptions({
    layout: FacultyLayout,
});

const reports = ref([
    {
        id: 1,
        title: 'Student Performance Report',
        description: 'Comprehensive analysis of student grades and performance metrics',
        type: 'performance',
        generatedDate: '2024-01-15',
        generatedBy: 'Dr. Smith',
        size: '2.4 MB',
        downloads: 45,
    },
    {
        id: 2,
        title: 'Attendance Summary',
        description: 'Monthly attendance report for all courses',
        type: 'attendance',
        generatedDate: '2024-01-10',
        generatedBy: 'Prof. Johnson',
        size: '1.8 MB',
        downloads: 32,
    },
    {
        id: 3,
        title: 'Course Completion Rates',
        description: 'Analysis of course completion and dropout rates',
        type: 'analytics',
        generatedDate: '2024-01-05',
        generatedBy: 'Dr. Williams',
        size: '3.1 MB',
        downloads: 28,
    },
    {
        id: 4,
        title: 'Faculty Workload Report',
        description: 'Detailed breakdown of faculty teaching loads and responsibilities',
        type: 'faculty',
        generatedDate: '2024-01-01',
        generatedBy: 'Admin',
        size: '1.2 MB',
        downloads: 15,
    },
]);

const selectedType = ref('all');
const selectedDateRange = ref('all');

const filteredReports = ref(reports.value);

onMounted(() => {
    filterReports();
});

function filterReports() {
    filteredReports.value = reports.value.filter(report => {
        const matchesType = selectedType.value === 'all' || report.type === selectedType.value;
        const matchesDate = selectedDateRange.value === 'all' || checkDateRange(report.generatedDate);
        
        return matchesType && matchesDate;
    });
}

function checkDateRange(date: string): boolean {
    const reportDate = new Date(date);
    const now = new Date();
    const daysDiff = Math.floor((now.getTime() - reportDate.getTime()) / (1000 * 60 * 60 * 24));
    
    switch (selectedDateRange.value) {
        case '7days':
            return daysDiff <= 7;
        case '30days':
            return daysDiff <= 30;
        case '90days':
            return daysDiff <= 90;
        default:
            return true;
    }
}

function getReportIcon(type: string) {
    switch (type) {
        case 'performance':
            return TrendingUp;
        case 'attendance':
            return Calendar;
        case 'analytics':
            return Award;
        case 'faculty':
            return Users;
        default:
            return FileText;
    }
}

function getReportColor(type: string) {
    switch (type) {
        case 'performance':
            return 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300';
        case 'attendance':
            return 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300';
        case 'analytics':
            return 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300';
        case 'faculty':
            return 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300';
        default:
            return 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-300';
    }
}

function downloadReport(reportId: number) {
    console.log(`Downloading report ${reportId}`);
    // In a real app, this would trigger a file download
}

const stats = ref({
    totalReports: 24,
    thisMonth: 8,
    totalDownloads: 342,
    avgDownloads: 14.3,
});
</script>

<template>
    <Head title="Reports" />
    
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reports</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Generate and manage system reports</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <FileText class="h-6 w-6 text-blue-600 dark:text-blue-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Reports</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.totalReports }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <Calendar class="h-6 w-6 text-green-600 dark:text-green-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.thisMonth }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <Download class="h-6 w-6 text-purple-600 dark:text-purple-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Downloads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.totalDownloads }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                        <TrendingUp class="h-6 w-6 text-orange-600 dark:text-orange-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Downloads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.avgDownloads }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <select
                        v-model="selectedType"
                        @change="filterReports"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Types</option>
                        <option value="performance">Performance</option>
                        <option value="attendance">Attendance</option>
                        <option value="analytics">Analytics</option>
                        <option value="faculty">Faculty</option>
                    </select>
                    <select
                        v-model="selectedDateRange"
                        @change="filterReports"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Time</option>
                        <option value="7days">Last 7 Days</option>
                        <option value="30days">Last 30 Days</option>
                        <option value="90days">Last 90 Days</option>
                    </select>
                </div>
                <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    Generate New Report
                </button>
            </div>
        </div>

        <!-- Reports List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div v-for="report in filteredReports" :key="report.id" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div :class="getReportColor(report.type)" class="p-3 rounded-lg">
                                <component :is="getReportIcon(report.type)" class="h-6 w-6" />
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ report.title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ report.description }}</p>
                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500 dark:text-gray-400">
                                    <span>Generated: {{ report.generatedDate }}</span>
                                    <span>By: {{ report.generatedBy }}</span>
                                    <span>Size: {{ report.size }}</span>
                                    <span>Downloads: {{ report.downloads }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="downloadReport(report.id)"
                                class="px-3 py-2 text-sm bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center"
                            >
                                <Download class="h-4 w-4 mr-1" />
                                Download
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <Filter class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredReports.length === 0" class="text-center py-12">
            <FileText class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No reports found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filter criteria or generate a new report.</p>
        </div>
    </div>
</template>
