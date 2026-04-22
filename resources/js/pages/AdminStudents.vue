<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
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
    BookOpen,
    Award,
    AlertTriangle,
    CheckCircle,
    ChevronLeft,
    ChevronRight,
    Download,
    Upload,
    Eye,
    FileText
} from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Student data
const students = ref<any[]>([]);

// Search and filter
const searchQuery = ref('');
const selectedDepartment = ref('all');
const selectedYear = ref('all');

// Pagination
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Computed filtered students
const filteredStudents = computed(() => {
    return students.value.filter(student => {
        const matchesSearch = student.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            student.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            student.studentId.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesDepartment = selectedDepartment.value === 'all' || student.department === selectedDepartment.value;
        const matchesYear = selectedYear.value === 'all' || student.year === selectedYear.value;
        return matchesSearch && matchesDepartment && matchesYear;
    });
});

// Paginated students
const paginatedStudents = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredStudents.value.slice(start, end);
});

// Pagination info
const totalPages = computed(() => Math.ceil(filteredStudents.value.length / itemsPerPage.value));

// Filter options
const departmentOptions = [
    { value: 'all', label: 'All Departments' },
    { value: 'BSIT', label: 'Information Technology' },
    { value: 'BSCS', label: 'Computer Science' },
    { value: 'Unknown', label: 'Unknown' }
];

const yearOptions = [
    { value: 'all', label: 'All Years' },
    { value: '1st', label: '1st Year' },
    { value: '2nd', label: '2nd Year' },
    { value: '3rd', label: '3rd Year' },
    { value: '4th', label: '4th Year' }
];


// Stats
const stats = ref({
    total: 0,
    bsitStudents: 0,
    bscsStudents: 0,
    totalCourses: 0,
    totalAchievements: 0
});

// Methods
const getStatusColor = (status: string) => {
    return status === 'active' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';
};

const getGPAColor = (gpa: string) => {
    const gpaValue = parseFloat(gpa);
    if (gpaValue >= 3.7) return 'text-green-600 bg-green-50';
    if (gpaValue >= 3.0) return 'text-blue-600 bg-blue-50';
    return 'text-yellow-600 bg-yellow-50';
};

const getYearColor = (year: string) => {
    const colors: Record<string, string> = {
        '1st': 'text-purple-600 bg-purple-50',
        '2nd': 'text-blue-600 bg-blue-50',
        '3rd': 'text-green-600 bg-green-50',
        '4th': 'text-orange-600 bg-orange-50'
    };
    return colors[year] || 'text-gray-600 bg-gray-50';
};

const changePage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

// Fetch real student data and stats from API
onMounted(async () => {
    try {
        // Fetch students
        const studentsResponse = await fetch('/api/admin/students');
        const studentsData = await studentsResponse.json();
        students.value = studentsData;

        // Fetch stats
        const statsResponse = await fetch('/api/admin/student-stats');
        const statsData = await statsResponse.json();
        stats.value = statsData;
    } catch (error) {
        console.error('Failed to fetch data:', error);
    }
});
</script>

<template>
    <Head title="Student Management" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Student Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage student records, enrollment, and academic performance</p>
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
                <Link href="/admin/students/create" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <UserPlus class="h-4 w-4 mr-2" />
                    Add Student
                </Link>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Students</p>
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">BSIT Students</p>
                        <p class="text-2xl font-bold text-blue-600 mt-2">{{ stats.bsitStudents }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <Users class="h-6 w-6 text-blue-600" />
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">BSCS Students</p>
                        <p class="text-2xl font-bold text-green-600 mt-2">{{ stats.bscsStudents }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <Users class="h-6 w-6 text-green-600" />
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Courses</p>
                        <p class="text-2xl font-bold text-orange-600 mt-2">{{ stats.totalCourses }}</p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-lg">
                        <BookOpen class="h-6 w-6 text-orange-600" />
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
                            placeholder="Search students..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white w-full md:w-64"
                        />
                    </div>
                    
                    <!-- Department Filter -->
                    <select v-model="selectedDepartment" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option v-for="option in departmentOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                    
                    <!-- Year Filter -->
                    <select v-model="selectedYear" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option v-for="option in yearOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                    
                                    </div>
                
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ paginatedStudents.length }} of {{ filteredStudents.length }} students
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Student
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Student ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Department
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Year
                            </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="student in paginatedStudents" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ student.name.charAt(0) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ student.name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                            <Mail class="h-3 w-3 mr-1" />
                                            {{ student.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ student.studentId }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ student.department }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getYearColor(student.year)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    {{ student.year }}
                                </span>
                            </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <Edit class="h-4 w-4" />
                                    </button>
                                    <button class="text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
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
                            <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredStudents.length) }}</span>
                            of
                            <span class="font-medium">{{ filteredStudents.length }}</span>
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
</template>
