<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Search, Calendar, Clock, MapPin, Users, BookOpen, Plus, Edit, Trash2, Filter, Download } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Mock data for schedules
const schedules = ref([
    {
        id: 1,
        course_code: 'CCS101',
        course_name: 'Introduction to Computer Science',
        section: 'A',
        faculty_name: 'John Smith',
        faculty_id: 'FAC001',
        room: 'Room 301',
        building: 'Building A',
        day: 'Monday',
        start_time: '08:00',
        end_time: '09:30',
        semester: 'Fall 2024',
        students_enrolled: 45,
        max_capacity: 50,
        status: 'active'
    },
    {
        id: 2,
        course_code: 'CCS101',
        course_name: 'Introduction to Computer Science',
        section: 'B',
        faculty_name: 'Sarah Johnson',
        faculty_id: 'FAC002',
        room: 'Room 302',
        building: 'Building A',
        day: 'Monday',
        start_time: '10:00',
        end_time: '11:30',
        semester: 'Fall 2024',
        students_enrolled: 48,
        max_capacity: 50,
        status: 'active'
    },
    {
        id: 3,
        course_code: 'CCS201',
        course_name: 'Data Structures',
        section: 'A',
        faculty_name: 'Michael Brown',
        faculty_id: 'FAC003',
        room: 'Room 303',
        building: 'Building B',
        day: 'Tuesday',
        start_time: '14:00',
        end_time: '15:30',
        semester: 'Fall 2024',
        students_enrolled: 35,
        max_capacity: 40,
        status: 'active'
    },
    {
        id: 4,
        course_code: 'CCS201',
        course_name: 'Data Structures',
        section: 'B',
        faculty_name: 'Emily Davis',
        faculty_id: 'FAC004',
        room: 'Room 304',
        building: 'Building B',
        day: 'Wednesday',
        start_time: '09:00',
        end_time: '10:30',
        semester: 'Fall 2024',
        students_enrolled: 38,
        max_capacity: 40,
        status: 'active'
    },
    {
        id: 5,
        course_code: 'CCS301',
        course_name: 'Software Engineering',
        section: 'A',
        faculty_name: 'John Smith',
        faculty_id: 'FAC001',
        room: 'Room 305',
        building: 'Building C',
        day: 'Thursday',
        start_time: '13:00',
        end_time: '14:30',
        semester: 'Fall 2024',
        students_enrolled: 42,
        max_capacity: 45,
        status: 'active'
    },
    {
        id: 6,
        course_code: 'CCS301',
        course_name: 'Software Engineering',
        section: 'B',
        faculty_name: 'Sarah Johnson',
        faculty_id: 'FAC002',
        room: 'Lab 201',
        building: 'Building C',
        day: 'Friday',
        start_time: '15:00',
        end_time: '16:30',
        semester: 'Fall 2024',
        students_enrolled: 28,
        max_capacity: 30,
        status: 'cancelled'
    }
]);

// Search and filters
const searchQuery = ref('');
const selectedDay = ref('');
const selectedFaculty = ref('');
const selectedBuilding = ref('');
const selectedStatus = ref('');
const selectedSemester = ref('Fall 2024');

// Filter options
const days = [
    { value: '', label: 'All Days' },
    { value: 'Monday', label: 'Monday' },
    { value: 'Tuesday', label: 'Tuesday' },
    { value: 'Wednesday', label: 'Wednesday' },
    { value: 'Thursday', label: 'Thursday' },
    { value: 'Friday', label: 'Friday' },
    { value: 'Saturday', label: 'Saturday' }
];

const facultyOptions = [
    { value: '', label: 'All Faculty' },
    { value: 'FAC001', label: 'John Smith' },
    { value: 'FAC002', label: 'Sarah Johnson' },
    { value: 'FAC003', label: 'Michael Brown' },
    { value: 'FAC004', label: 'Emily Davis' }
];

const buildings = [
    { value: '', label: 'All Buildings' },
    { value: 'Building A', label: 'Building A' },
    { value: 'Building B', label: 'Building B' },
    { value: 'Building C', label: 'Building C' }
];

const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'active', label: 'Active' },
    { value: 'cancelled', label: 'Cancelled' },
    { value: 'postponed', label: 'Postponed' }
];

const semesters = [
    { value: 'Fall 2024', label: 'Fall 2024' },
    { value: 'Summer 2024', label: 'Summer 2024' },
    { value: 'Spring 2024', label: 'Spring 2024' }
];

// Computed filtered schedules
const filteredSchedules = computed(() => {
    return schedules.value.filter(schedule => {
        const matchesSearch = !searchQuery.value || 
            schedule.course_code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            schedule.course_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            schedule.section.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            schedule.faculty_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            schedule.room.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        const matchesDay = !selectedDay.value || schedule.day === selectedDay.value;
        const matchesFaculty = !selectedFaculty.value || schedule.faculty_id === selectedFaculty.value;
        const matchesBuilding = !selectedBuilding.value || schedule.building === selectedBuilding.value;
        const matchesStatus = !selectedStatus.value || schedule.status === selectedStatus.value;
        const matchesSemester = !selectedSemester.value || schedule.semester === selectedSemester.value;
        
        return matchesSearch && matchesDay && matchesFaculty && matchesBuilding && matchesStatus && matchesSemester;
    });
});

// Statistics
const stats = computed(() => {
    const total = filteredSchedules.value.length;
    const active = filteredSchedules.value.filter(s => s.status === 'active').length;
    const cancelled = filteredSchedules.value.filter(s => s.status === 'cancelled').length;
    const totalStudents = filteredSchedules.value.reduce((sum, s) => sum + s.students_enrolled, 0);
    const totalCapacity = filteredSchedules.value.reduce((sum, s) => sum + s.max_capacity, 0);
    const occupancyRate = totalCapacity > 0 ? Math.round((totalStudents / totalCapacity) * 100) : 0;
    
    return {
        total,
        active,
        cancelled,
        totalStudents,
        totalCapacity,
        occupancyRate
    };
});

// Helper functions
const getStatusColor = (status: string) => {
    switch(status) {
        case 'active': return 'bg-green-100 text-green-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        case 'postponed': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getOccupancyColor = (enrolled: number, capacity: number) => {
    const percentage = (enrolled / capacity) * 100;
    if (percentage >= 90) return 'text-red-600';
    if (percentage >= 75) return 'text-yellow-600';
    return 'text-green-600';
};

const getTimeColor = (time: string) => {
    const hour = parseInt(time.split(':')[0]);
    if (hour < 10) return 'text-blue-600'; // Morning
    if (hour < 14) return 'text-green-600'; // Late morning
    if (hour < 17) return 'text-orange-600'; // Afternoon
    return 'text-purple-600'; // Evening
};

const deleteSchedule = (id: number) => {
    if (confirm('Are you sure you want to delete this schedule?')) {
        // In a real application, this would make an API call
        const index = schedules.value.findIndex(s => s.id === id);
        if (index > -1) {
            schedules.value.splice(index, 1);
        }
    }
};

const exportSchedule = () => {
    // In a real application, this would generate and download a file
    alert('Export functionality would be implemented here');
};
</script>

<template>
    <Head title="Schedule Management - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Schedule Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage class schedules and room assignments</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="exportSchedule" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                    <Download class="h-4 w-4 mr-2" />
                    Export
                </button>
                <Link href="/admin/schedules/create" class="flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                    <Plus class="h-4 w-4 mr-2" />
                    Add Schedule
                </Link>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <Calendar class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Schedules</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <Clock class="h-6 w-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.active }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                        <Calendar class="h-6 w-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.cancelled }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <Users class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalStudents }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <BookOpen class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Occupancy</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.occupancyRate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search schedules..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>
                
                <select 
                    v-model="selectedSemester"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="semester in semesters" :key="semester.value" :value="semester.value">
                        {{ semester.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedDay"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="day in days" :key="day.value" :value="day.value">
                        {{ day.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedFaculty"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="faculty in facultyOptions" :key="faculty.value" :value="faculty.value">
                        {{ faculty.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedBuilding"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="building in buildings" :key="building.value" :value="building.value">
                        {{ building.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedStatus"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                        {{ status.label }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Course & Section
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Faculty
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Schedule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Students
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="schedule in filteredSchedules" :key="schedule.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ schedule.course_code }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ schedule.course_name }} - Section {{ schedule.section }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ schedule.faculty_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ schedule.faculty_id }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <Calendar class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ schedule.day }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <Clock class="h-4 w-4 mr-1 text-gray-400" />
                                        <span :class="getTimeColor(schedule.start_time)">
                                            {{ schedule.start_time }} - {{ schedule.end_time }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <MapPin class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ schedule.room }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ schedule.building }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ schedule.students_enrolled }} / {{ schedule.max_capacity }}
                                </div>
                                <div class="text-sm" :class="getOccupancyColor(schedule.students_enrolled, schedule.max_capacity)">
                                    {{ Math.round((schedule.students_enrolled / schedule.max_capacity) * 100) }}% full
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusColor(schedule.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ schedule.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <Link 
                                        :href="`/admin/schedules/${schedule.id}`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                    >
                                        <Calendar class="h-4 w-4" />
                                    </Link>
                                    <Link 
                                        :href="`/admin/schedules/${schedule.id}/edit`"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                    <button
                                        @click="deleteSchedule(schedule.id)"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        <Trash2 class="h-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div v-if="filteredSchedules.length === 0" class="text-center py-12">
                <Calendar class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No schedules found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Try adjusting your search or filters
                </p>
            </div>
        </div>
    </div>
</template>
