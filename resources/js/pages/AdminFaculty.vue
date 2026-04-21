<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Search, UserCheck, Mail, Phone, MapPin, Calendar, BookOpen, Users, Eye, Edit, Trash2, Plus } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Mock data for faculty members
const faculty = ref([
    {
        id: 1,
        faculty_id: 'FAC001',
        first_name: 'John',
        last_name: 'Smith',
        email: 'john.smith@university.edu',
        phone: '+1-234-567-8901',
        department: 'Computer Science',
        position: 'Professor',
        employment_type: 'Full-time',
        status: 'active',
        hire_date: '2018-08-15',
        office: 'Room 301, Building A',
        specialization: 'Artificial Intelligence',
        total_courses: 4,
        total_students: 120
    },
    {
        id: 2,
        faculty_id: 'FAC002',
        first_name: 'Sarah',
        last_name: 'Johnson',
        email: 'sarah.johnson@university.edu',
        phone: '+1-234-567-8902',
        department: 'Computer Science',
        position: 'Associate Professor',
        employment_type: 'Full-time',
        status: 'active',
        hire_date: '2020-01-10',
        office: 'Room 302, Building A',
        specialization: 'Software Engineering',
        total_courses: 3,
        total_students: 90
    },
    {
        id: 3,
        faculty_id: 'FAC003',
        first_name: 'Michael',
        last_name: 'Brown',
        email: 'michael.brown@university.edu',
        phone: '+1-234-567-8903',
        department: 'Computer Science',
        position: 'Assistant Professor',
        employment_type: 'Full-time',
        status: 'active',
        hire_date: '2021-06-20',
        office: 'Room 303, Building A',
        specialization: 'Data Science',
        total_courses: 2,
        total_students: 60
    },
    {
        id: 4,
        faculty_id: 'FAC004',
        first_name: 'Emily',
        last_name: 'Davis',
        email: 'emily.davis@university.edu',
        phone: '+1-234-567-8904',
        department: 'Computer Science',
        position: 'Lecturer',
        employment_type: 'Part-time',
        status: 'active',
        hire_date: '2019-09-01',
        office: 'Room 304, Building A',
        specialization: 'Web Development',
        total_courses: 2,
        total_students: 40
    },
    {
        id: 5,
        faculty_id: 'FAC005',
        first_name: 'Robert',
        last_name: 'Wilson',
        email: 'robert.wilson@university.edu',
        phone: '+1-234-567-8905',
        department: 'Computer Science',
        position: 'Professor',
        employment_type: 'Full-time',
        status: 'inactive',
        hire_date: '2015-03-15',
        office: 'Room 305, Building A',
        specialization: 'Computer Networks',
        total_courses: 0,
        total_students: 0
    }
]);

// Search and filters
const searchQuery = ref('');
const selectedDepartment = ref('');
const selectedStatus = ref('');
const selectedEmploymentType = ref('');

// Filter options
const departments = [
    { value: '', label: 'All Departments' },
    { value: 'Computer Science', label: 'Computer Science' },
    { value: 'Mathematics', label: 'Mathematics' },
    { value: 'Physics', label: 'Physics' }
];

const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'on_leave', label: 'On Leave' }
];

const employmentTypes = [
    { value: '', label: 'All Types' },
    { value: 'Full-time', label: 'Full-time' },
    { value: 'Part-time', label: 'Part-time' },
    { value: 'Contract', label: 'Contract' }
];

// Computed filtered faculty
const filteredFaculty = computed(() => {
    return faculty.value.filter(member => {
        const matchesSearch = !searchQuery.value || 
            member.first_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            member.last_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            member.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            member.faculty_id.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        const matchesDepartment = !selectedDepartment.value || member.department === selectedDepartment.value;
        const matchesStatus = !selectedStatus.value || member.status === selectedStatus.value;
        const matchesEmploymentType = !selectedEmploymentType.value || member.employment_type === selectedEmploymentType.value;
        
        return matchesSearch && matchesDepartment && matchesStatus && matchesEmploymentType;
    });
});

// Statistics
const stats = computed(() => {
    const total = faculty.value.length;
    const active = faculty.value.filter(f => f.status === 'active').length;
    const fullTime = faculty.value.filter(f => f.employment_type === 'Full-time').length;
    const partTime = faculty.value.filter(f => f.employment_type === 'Part-time').length;
    
    return {
        total,
        active,
        fullTime,
        partTime,
        inactive: total - active
    };
});

// Helper functions
const getStatusColor = (status: string) => {
    switch(status) {
        case 'active': return 'bg-green-100 text-green-800';
        case 'inactive': return 'bg-red-100 text-red-800';
        case 'on_leave': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getEmploymentTypeColor = (type: string) => {
    switch(type) {
        case 'Full-time': return 'bg-blue-100 text-blue-800';
        case 'Part-time': return 'bg-purple-100 text-purple-800';
        case 'Contract': return 'bg-orange-100 text-orange-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getPositionColor = (position: string) => {
    switch(position) {
        case 'Professor': return 'bg-indigo-100 text-indigo-800';
        case 'Associate Professor': return 'bg-cyan-100 text-cyan-800';
        case 'Assistant Professor': return 'bg-teal-100 text-teal-800';
        case 'Lecturer': return 'bg-pink-100 text-pink-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const deleteFaculty = (id: number) => {
    if (confirm('Are you sure you want to delete this faculty member?')) {
        // In a real application, this would make an API call
        const index = faculty.value.findIndex(f => f.id === id);
        if (index > -1) {
            faculty.value.splice(index, 1);
        }
    }
};
</script>

<template>
    <Head title="Faculty Management - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Faculty Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage faculty information and assignments</p>
            </div>
            <Link href="/admin/faculty/create" class="flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                <Plus class="h-4 w-4 mr-2" />
                Add Faculty
            </Link>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <Users class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Faculty</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <UserCheck class="h-6 w-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.active }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <BookOpen class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Full-time</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.fullTime }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                        <Calendar class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Part-time</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.partTime }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search by name, email, or ID..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>
                
                <select 
                    v-model="selectedDepartment"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="dept in departments" :key="dept.value" :value="dept.value">
                        {{ dept.label }}
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
                
                <select 
                    v-model="selectedEmploymentType"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="type in employmentTypes" :key="type.value" :value="type.value">
                        {{ type.label }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Faculty Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Faculty Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Position
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Employment
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Courses/Students
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="member in filteredFaculty" :key="member.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                                            <UserCheck class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ member.first_name }} {{ member.last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ member.faculty_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <Mail class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ member.email }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <Phone class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ member.phone }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ member.position }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ member.department }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getEmploymentTypeColor(member.employment_type)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ member.employment_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusColor(member.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ member.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div>{{ member.total_courses }} courses</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ member.total_students }} students</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <Link 
                                        :href="`/admin/faculty/${member.id}`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                    <Link 
                                        :href="`/admin/faculty/${member.id}/edit`"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                    <button
                                        @click="deleteFaculty(member.id)"
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
            <div v-if="filteredFaculty.length === 0" class="text-center py-12">
                <UserCheck class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No faculty found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Try adjusting your search or filters
                </p>
            </div>
        </div>
    </div>
</template>
