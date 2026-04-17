<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { Search, Plus, Download, FileText, TrendingUp, Users, Calendar, BarChart3 } from 'lucide-vue-next';

const reports = ref([
    { id: 1, title: 'Student Performance Report', type: 'Performance', date: '2024-03-10', students: 156, status: 'Ready' },
    { id: 2, title: 'Attendance Report', type: 'Attendance', date: '2024-03-09', students: 145, status: 'Ready' },
    { id: 3, title: 'Grade Distribution Report', type: 'Grades', date: '2024-03-08', students: 156, status: 'Processing' },
    { id: 4, title: 'Course Enrollment Report', type: 'Enrollment', date: '2024-03-07', students: 200, status: 'Ready' },
    { id: 5, title: 'Faculty Workload Report', type: 'Faculty', date: '2024-03-06', students: 12, status: 'Ready' }
]);

const searchQuery = ref('');
const selectedType = ref('all');

const filteredReports = ref(reports.value);

const getStatusColor = (status) => {
    switch(status) {
        case 'Ready': return 'bg-green-100 text-green-800';
        case 'Processing': return 'bg-yellow-100 text-yellow-800';
        case 'Failed': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getTypeIcon = (type) => {
    switch(type) {
        case 'Performance': return TrendingUp;
        case 'Attendance': return Calendar;
        case 'Grades': return BarChart3;
        case 'Enrollment': return Users;
        case 'Faculty': return FileText;
        default: return FileText;
    }
};
</script>

<template>
    <FacultyLayout>
        <Head title="Faculty Reports" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">REPORTS</h1>
                <p class="text-gray-600 mt-2">Generate and manage academic reports</p>
            </div>

            <!-- Filters and Actions -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search reports by title or type..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        />
                    </div>
                    <select 
                        v-model="selectedType"
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                    >
                        <option value="all">All Types</option>
                        <option value="Performance">Performance</option>
                        <option value="Attendance">Attendance</option>
                        <option value="Grades">Grades</option>
                        <option value="Enrollment">Enrollment</option>
                        <option value="Faculty">Faculty</option>
                    </select>
                    <Link 
                        href="/faculty/reports/create"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg flex items-center justify-center space-x-2 transition-colors"
                    >
                        <Plus class="h-4 w-4" />
                        Generate Report
                    </Link>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <FileText class="h-6 w-6 text-orange-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Reports</p>
                            <p class="text-2xl font-bold text-gray-900">{{ reports.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <TrendingUp class="h-6 w-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Ready</p>
                            <p class="text-2xl font-bold text-green-600">4</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <Calendar class="h-6 w-6 text-yellow-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Processing</p>
                            <p class="text-2xl font-bold text-yellow-600">1</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <Download class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Downloads</p>
                            <p class="text-2xl font-bold text-blue-600">23</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="report in filteredReports" 
                    :key="report.id"
                    class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl hover:border-orange-300 transition-all"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                                <component :is="getTypeIcon(report.type)" class="h-5 w-5 text-orange-600" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ report.title }}</h3>
                                <p class="text-sm text-gray-600">{{ report.type }} Report</p>
                            </div>
                        </div>
                        <span :class="getStatusColor(report.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                            {{ report.status }}
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <Calendar class="h-4 w-4 mr-2" />
                            {{ report.date }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <Users class="h-4 w-4 mr-2" />
                            {{ report.students }} students
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <Link 
                                href="#"
                                class="text-orange-600 hover:text-orange-900 flex items-center space-x-1"
                            >
                                <Download class="h-4 w-4" />
                                <span class="text-sm">Download</span>
                            </Link>
                            <Link 
                                href="#"
                                class="text-blue-600 hover:text-blue-900 flex items-center space-x-1"
                            >
                                <FileText class="h-4 w-4" />
                                <span class="text-sm">View</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FacultyLayout>
</template>
