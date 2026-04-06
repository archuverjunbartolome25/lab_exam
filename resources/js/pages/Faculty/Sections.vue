<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { Users, BookOpen, Search, Plus, Mail, Phone } from 'lucide-vue-next';

// Define props for data passed from controller
const props = defineProps<{
    sections: Array<{
        section_id: number;
        section: string;
        courses: number[];
        num_of_stud: number;
        courses_info?: Array<{
            id: number;
            code: string;
            name: string;
            credits: number;
        }>;
        enrolled_students?: Array<{
            stud_id: number;
            fname: string;
            mname?: string;
            lname: string;
            ext?: string;
            stud_num: string;
            program: string;
            standing: string;
            academic_status?: string;
            current_gpa?: number;
            active_violations?: number;
            skills?: Array<{
                id: number;
                name: string;
                level: string;
            }>;
        }>;
    }>;
}>();

const searchQuery = ref('');
const programFilter = ref('all');
const yearLevelFilter = ref('all');
const selectedSection = ref<typeof props.sections[0] | null>(null);

// Generate section options for dropdown
const sectionOptions = computed(() => {
    let sections = props.sections;
    
    // Filter by program
    if (programFilter.value !== 'all') {
        const isIT = programFilter.value === 'BSIT';
        sections = sections.filter(section => {
            const sectionIsIT = section.section.includes('IT');
            return isIT ? sectionIsIT : !sectionIsIT;
        });
    }
    
    // Filter by year level
    if (yearLevelFilter.value !== 'all') {
        sections = sections.filter(section => {
            const yearMatch = section.section.match(/^(\d+)/);
            if (yearMatch) {
                const year = parseInt(yearMatch[1]);
                const yearMap: { [key: string]: number } = {
                    '1st Year': 1,
                    '2nd Year': 2,
                    '3rd Year': 3,
                    '4th Year': 4,
                    'Irregular': 5
                };
                return yearMap[yearLevelFilter.value] === year;
            }
            return false;
        });
    }
    
    return sections.sort((a, b) => {
        const aMatch = a.section.match(/^(\d+)/);
        const bMatch = b.section.match(/^(\d+)/);
        const aYear = aMatch ? parseInt(aMatch[1]) : 0;
        const bYear = bMatch ? parseInt(bMatch[1]) : 0;
        return aYear - bYear;
    });
});

// Select section function
const selectSection = (section: any) => {
    selectedSection.value = section;
};

// Back to sections list
const backToSections = () => {
    selectedSection.value = null;
};

// Program options
const programOptions = [
    { value: 'all', label: 'All Programs' },
    { value: 'BSIT', label: 'BSIT' },
    { value: 'BSCS', label: 'BSCS' },
];

// Year level options including Irregular
const yearLevelOptions = [
    { value: 'all', label: 'All Year Levels' },
    { value: '1st Year', label: '1st Year' },
    { value: '2nd Year', label: '2nd Year' },
    { value: '3rd Year', label: '3rd Year' },
    { value: '4th Year', label: '4th Year' },
    { value: 'Irregular', label: 'Irregular' },
];

// Helper functions
const getGpaColor = (gpa?: number) => {
    if (!gpa) return 'text-gray-500';
    if (gpa >= 3.5) return 'text-green-600';
    if (gpa >= 3.0) return 'text-blue-600';
    if (gpa >= 2.5) return 'text-yellow-600';
    return 'text-red-600';
};

const getViolationColor = (count?: number) => {
    if (!count || count === 0) return 'bg-green-100 text-green-800';
    if (count <= 2) return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
};

const getSkillLevelColor = (level: string) => {
    switch (level) {
        case 'beginner': return 'bg-blue-100 text-blue-800';
        case 'intermediate': return 'bg-green-100 text-green-800';
        case 'advanced': return 'bg-purple-100 text-purple-800';
        case 'expert': return 'bg-orange-100 text-orange-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

// Pagination for each section
const currentPages = ref<{ [key: string]: number }>({});
const itemsPerPage = 10;

const getPaginatedStudents = (section: any) => {
    const students = section.enrolled_students || [];
    const sectionKey = section.section;
    const currentPage = currentPages.value[sectionKey] || 1;
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return students.slice(start, end);
};

const getTotalPages = (section: any) => {
    const students = section.enrolled_students || [];
    return Math.ceil(students.length / itemsPerPage);
};

const setCurrentPage = (section: string, page: number) => {
    currentPages.value[section] = page;
};

const nextPage = (section: string) => {
    const current = currentPages.value[section] || 1;
    const total = getTotalPages({ section, enrolled_students: props.sections.find(s => s.section === section)?.enrolled_students || [] });
    if (current < total) {
        currentPages.value[section] = current + 1;
    }
};

const prevPage = (section: string) => {
    const current = currentPages.value[section] || 1;
    if (current > 1) {
        currentPages.value[section] = current - 1;
    }
};

// Reset pagination when filters change
watch([programFilter, yearLevelFilter], () => {
    currentPages.value = {};
});
</script>

<template>
    <FacultyLayout>
        <Head title="Faculty Sections" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Sections Management</h1>
                <p class="text-gray-600 mt-2">Manage and view all course sections</p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <!-- Filter Row -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search sections..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                            />
                        </div>
                    </div>

                    <!-- Program Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                        <select 
                            v-model="programFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        >
                            <option 
                                v-for="option in programOptions" 
                                :key="option.value" 
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Year Level Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                        <select 
                            v-model="yearLevelFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        >
                            <option 
                                v-for="option in yearLevelOptions" 
                                :key="option.value" 
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">{{ sectionOptions.length }}</span> sections found
                    </div>
                    <Link 
                        href="/faculty/sections/create"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                    >
                        <Plus class="h-4 w-4" />
                        Add Section
                    </Link>
                </div>
            </div>

            <!-- Selected Section Students View -->
            <div v-if="selectedSection" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Section Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button 
                                @click="backToSections"
                                class="text-gray-500 hover:text-gray-700 flex items-center space-x-2 mr-4"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Back to Sections
                            </button>
                            <h3 class="text-xl font-bold text-gray-900">{{ selectedSection.section }}</h3>
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                                {{ selectedSection.section.includes('IT') ? 'BSIT' : 'BSCS' }}
                            </span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                {{ selectedSection.enrolled_students?.length || 0 }} Students
                            </span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            <span>{{ selectedSection.courses?.length || 0 }} Courses</span>
                        </div>
                    </div>
                </div>

                <!-- Student Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    GPA
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Violations
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Skills
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="student in getPaginatedStudents(selectedSection)" :key="student.stud_id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-orange-600">{{ student.fname.charAt(0) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ student.fname }} {{ student.mname ? student.mname + ' ' : '' }}{{ student.lname }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ student.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ student.stud_num }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="student.academic_status === 'Regular' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" 
                                          class="px-2 py-1 rounded-full text-xs font-medium">
                                        {{ student.academic_status || 'Regular' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold" :class="getGpaColor(student.current_gpa)">
                                        {{ student.current_gpa?.toFixed(2) || 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getViolationColor(student.active_violations)" class="px-2 py-1 rounded-full text-xs font-medium">
                                        {{ student.active_violations || 0 }} Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div v-if="student.skills && student.skills.length > 0" class="flex flex-wrap gap-1">
                                        <span v-for="skill in student.skills.slice(0, 2)" :key="skill.id" 
                                              :class="getSkillLevelColor(skill.level)" 
                                              class="px-2 py-1 rounded-full text-xs">
                                            {{ skill.name }}
                                        </span>
                                        <span v-if="student.skills.length > 2" class="text-xs text-gray-500">
                                            +{{ student.skills.length - 2 }}
                                        </span>
                                    </div>
                                    <span v-else class="text-xs text-gray-400">No skills</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="!selectedSection.enrolled_students || selectedSection.enrolled_students.length === 0" class="text-center py-8">
                    <div class="text-gray-400 text-lg mb-2">No students enrolled</div>
                    <div class="text-gray-500 text-sm">This section has no enrolled students yet</div>
                </div>

                <!-- Pagination -->
                <div v-else-if="getTotalPages(selectedSection) > 1" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing 
                            <span class="font-medium">{{ ((currentPages[selectedSection.section] || 1) - 1) * itemsPerPage + 1 }}</span> 
                            to 
                            <span class="font-medium">{{ Math.min((currentPages[selectedSection.section] || 1) * itemsPerPage, selectedSection.enrolled_students.length) }}</span> 
                            of 
                            <span class="font-medium">{{ selectedSection.enrolled_students.length }}</span> 
                            students
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button
                                @click="prevPage(selectedSection.section)"
                                :disabled="(currentPages[selectedSection.section] || 1) === 1"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            
                            <div class="flex items-center space-x-1">
                                <button
                                    v-for="page in Math.min(getTotalPages(selectedSection), 5)"
                                    :key="page"
                                    @click="setCurrentPage(selectedSection.section, page)"
                                    :class="[
                                        'px-3 py-1 text-sm border rounded-md',
                                        (currentPages[selectedSection.section] || 1) === page 
                                            ? 'bg-orange-500 text-white border-orange-500' 
                                            : 'border-gray-300 hover:bg-gray-100'
                                    ]"
                                >
                                    {{ page }}
                                </button>
                                
                                <span v-if="getTotalPages(selectedSection) > 5" class="px-2 text-gray-500">...</span>
                                <button
                                    v-if="getTotalPages(selectedSection) > 5"
                                    @click="setCurrentPage(selectedSection.section, getTotalPages(selectedSection))"
                                    :class="[
                                        'px-3 py-1 text-sm border rounded-md',
                                        (currentPages[selectedSection.section] || 1) === getTotalPages(selectedSection) 
                                            ? 'bg-orange-500 text-white border-orange-500' 
                                            : 'border-gray-300 hover:bg-gray-100'
                                    ]"
                                >
                                    {{ getTotalPages(selectedSection) }}
                                </button>
                            </div>
                            
                            <button
                                @click="nextPage(selectedSection.section)"
                                :disabled="(currentPages[selectedSection.section] || 1) === getTotalPages(selectedSection)"
                                class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sections Overview Table -->
            <div v-else-if="sectionOptions.length > 0" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Section
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Program
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Students
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Courses
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="section in sectionOptions" :key="section.section_id" class="hover:bg-gray-50 cursor-pointer" @click="selectSection(section)">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-orange-600">{{ section.section }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ section.section }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ section.section.includes('IT') ? 'BSIT' : 'BSCS' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <Users class="h-4 w-4 text-gray-400 mr-2" />
                                        <span class="text-sm text-gray-900">{{ section.enrolled_students?.length || 0 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <BookOpen class="h-4 w-4 text-gray-400 mr-2" />
                                        <span class="text-sm text-gray-900">{{ section.courses?.length || 0 }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                        View Students →
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="text-gray-400 text-lg mb-2">No sections found</div>
                <div class="text-gray-500 text-sm">
                    Try adjusting your filters or add new sections
                </div>
            </div>
        </div>
    </FacultyLayout>
</template>
