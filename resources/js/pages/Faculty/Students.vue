<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { Users, Search, Plus, Mail, Phone } from 'lucide-vue-next';

// Define TypeScript interfaces
interface Student {
    stud_id: number;
    stud_num: string;
    fname: string;
    mname?: string;
    lname: string;
    ext?: string;
    gender: string;
    bday: string;
    email: string;
    contact_num: string;
    address: string;
    program: string;
    program_code: string;
    standing: string;
    academic_status: string;
    guardian?: any;
    courses?: number[];
    courses_info?: Array<{
        id: number;
        code: string;
        name: string;
        credits: number;
    }>;
    // New profiling data
    affiliations?: Array<{
        id: number;
        type: string;
        name: string;
        role?: string;
    }>;
    skills?: Array<{
        id: number;
        name: string;
        level: string;
    }>;
    violations?: Array<{
        id: number;
        type: string;
        description: string;
        severity: string;
        status: string;
        violation_date: string;
    }>;
    achievements?: Array<{
        id: number;
        title: string;
        description: string;
        achievement_date: string;
        type: string;
    }>;
    academicHistory?: Array<{
        id: number;
        semester: string;
        year: number;
        gpa: number;
        units: number;
        honors?: string;
    }>;
    medicalRecord?: {
        id: number;
        stud_id: number;
        blood_type: string;
        allergies?: string;
        medications?: string;
        medical_conditions?: string;
        emergency_contact_name: string;
        emergency_contact_phone: string;
        emergency_contact_relationship: string;
        medical_history?: string;
        physician_name?: string;
        physician_phone?: string;
        last_checkup?: string;
        insurance_provider?: string;
        insurance_policy_number?: string;
    };
    // Computed properties
    current_gpa?: number;
    total_credits?: number;
    active_violations?: number;
    // Computed properties for template
    id?: number;
    name?: string;
    studNum?: string;
    section?: string;
    year?: string;
    gpa?: number;
    phone?: string;
}

// Define props for data passed from controller
const props = defineProps<{
    students: Student[];
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
const selectedStudent = ref<Partial<Student> | null>(null);
const selectedSection = ref<typeof props.sections[0] | null>(null);

// Filter states
const selectedYear = ref<string>('');
const selectedStatus = ref<string>('');
const selectedProgram = ref<string>('');

// Filter options
const yearOptions = [
    { value: '', label: 'All Years' },
    { value: '1st Year', label: '1st Year' },
    { value: '2nd Year', label: '2nd Year' },
    { value: '3rd Year', label: '3rd Year' },
    { value: '4th Year', label: '4th Year' },
];

const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'Regular', label: 'Regular' },
    { value: 'Irregular', label: 'Irregular' },
];

const programOptions = [
    { value: '', label: 'All Programs' },
    { value: 'BSIT', label: 'BSIT' },
    { value: 'BSCS', label: 'BSCS' },
];

const studentsWithComputed = computed(() => {
    return props.students.map(student => ({
        ...student,
        id: student.stud_id,
        name: student.fname + (student.mname ? ' ' + student.mname : '') + ' ' + student.lname + (student.ext ? ' ' + student.ext : ''),
        studNum: student.stud_num,
        section: student.standing,
        year: student.standing,
        phone: student.contact_num,
        gpa: student.current_gpa || 0.0,
        active_violations: student.active_violations || 0
    }));
});

const filteredStudents = computed(() => {
    let filtered = studentsWithComputed.value;
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(student => 
            student.fname?.toLowerCase().includes(query) ||
            student.lname?.toLowerCase().includes(query) ||
            student.studNum?.toLowerCase().includes(query) ||
            student.email?.toLowerCase().includes(query) ||
            student.program?.toLowerCase().includes(query)
        );
    }
    
    // Apply year filter
    if (selectedYear.value) {
        filtered = filtered.filter(student => 
            student.standing === selectedYear.value
        );
    }
    
    // Apply status filter
    if (selectedStatus.value) {
        filtered = filtered.filter(student => 
            student.academic_status === selectedStatus.value
        );
    }
    
    // Apply program filter
    if (selectedProgram.value) {
        filtered = filtered.filter(student => 
            student.program === selectedProgram.value
        );
    }
    
    return filtered;
});

const selectStudent = (student: Partial<Student>) => {
    selectedStudent.value = student;
};

const backToList = () => {
    selectedStudent.value = null;
};

const clearFilters = () => {
    searchQuery.value = '';
    selectedYear.value = '';
    selectedStatus.value = '';
    selectedProgram.value = '';
};

const hasActiveFilters = computed(() => {
    return searchQuery.value || selectedYear.value || selectedStatus.value || selectedProgram.value;
});

// Section-related functions
const selectSection = (section: any) => {
    selectedSection.value = section;
};

const backToSections = () => {
    selectedSection.value = null;
};

// Generate section options for dropdown
const sectionOptions = computed(() => {
    let sections = props.sections;
    
    // Filter by program
    if (selectedProgram.value !== '') {
        const isIT = selectedProgram.value === 'BSIT';
        sections = sections.filter(section => {
            const sectionIsIT = section.section.includes('IT');
            return isIT ? sectionIsIT : !sectionIsIT;
        });
    }
    
    // Filter by year level
    if (selectedYear.value !== '') {
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
                return yearMap[selectedYear.value] === year;
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

// Pagination
const currentPage = ref(1);
const itemsPerPage = 10;

const paginatedStudents = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredStudents.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredStudents.value.length / itemsPerPage);
});

const setCurrentPage = (page: number) => {
    currentPage.value = page;
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

// Reset pagination when filters change
watch([searchQuery, selectedYear, selectedStatus, selectedProgram], () => {
    currentPage.value = 1;
});

const getSeverityColor = (severity: string) => {
    switch (severity) {
        case 'minor': return 'bg-yellow-100 text-yellow-800';
        case 'major': return 'bg-orange-100 text-orange-800';
        case 'critical': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
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
</script>

<template>
    <FacultyLayout>
        <Head title="Faculty Students" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">STUDENTS</h1>
                <p class="text-gray-600 mt-2">View and manage all enrolled students</p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <!-- Search Row -->
                <div class="flex items-center space-x-4 mb-4">
                    <div class="relative flex-1">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search students by name, ID, email, or program..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        />
                    </div>
                    <button 
                        v-if="hasActiveFilters"
                        @click="clearFilters"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                    >
                        <span>Clear Filters</span>
                    </button>
                    <Link 
                        href="/faculty/students/create"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                    >
                        <Plus class="h-4 w-4" />
                        Add Student
                    </Link>
                </div>

                <!-- Filter Row -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                        <select 
                            v-model="selectedYear"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        >
                            <option 
                                v-for="option in yearOptions" 
                                :key="option.value" 
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Academic Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Academic Status</label>
                        <select 
                            v-model="selectedStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        >
                            <option 
                                v-for="option in statusOptions" 
                                :key="option.value" 
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Program Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                        <select 
                            v-model="selectedProgram"
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

                    <!-- Results Count -->
                    <div class="flex items-end">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ filteredStudents.length }}</span> 
                            of 
                            <span class="font-medium">{{ studentsWithComputed.length }}</span> 
                            students
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Details View -->
            <div v-if="selectedStudent" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <!-- Student Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center">
                            <span class="text-2xl font-bold text-orange-600">{{ selectedStudent.name?.charAt(0) || '?' }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ selectedStudent.name }}</h2>
                            <p class="text-gray-600">{{ selectedStudent.studNum }} • {{ selectedStudent.program }} • {{ selectedStudent.year }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="text-sm font-medium">GPA:</span>
                                <span class="text-sm font-bold" :class="getGpaColor(selectedStudent.gpa)">{{ selectedStudent.gpa?.toFixed(2) || 'N/A' }}</span>
                                <span class="text-sm font-medium">Credits:</span>
                                <span class="text-sm font-bold">{{ selectedStudent.total_credits || 0 }}</span>
                                <span class="text-sm font-medium">Violations:</span>
                                <span :class="getViolationColor(selectedStudent.active_violations)" class="px-2 py-1 rounded-full text-xs">
                                    {{ selectedStudent.active_violations || 0 }} Active
                                </span>
                            </div>
                        </div>
                    </div>
                    <button 
                        @click="backToList"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors"
                    >
                        ← Back to List
                    </button>
                </div>

                <!-- Student Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Student ID:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.studNum }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Program:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.program }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Email:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Phone:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Address:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Academic Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Year Level:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.year }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">GPA:</span>
                                <span class="text-sm font-bold" :class="getGpaColor(selectedStudent.gpa)">{{ selectedStudent.gpa?.toFixed(2) || 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Total Credits:</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedStudent.total_credits || 0 }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-gray-600">Courses:</span>
                                <div class="text-right">
                                    <span v-if="selectedStudent.courses_info && selectedStudent.courses_info.length > 0" class="text-sm font-bold text-gray-900">
                                        <span v-for="course in selectedStudent.courses_info" :key="course.id" class="inline-block bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs mr-1 mb-1">
                                            {{ course.code }}
                                        </span>
                                    </span>
                                    <span v-else class="text-sm text-gray-500">No courses assigned</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Recent Achievements -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Achievements</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div v-if="selectedStudent.achievements && selectedStudent.achievements.length > 0" class="space-y-2">
                                <div v-for="achievement in selectedStudent.achievements.slice(0, 3)" :key="achievement.id" 
                                     class="flex items-center space-x-3 p-2 bg-white rounded">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <span class="text-xs font-bold text-yellow-600">🏆</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ achievement.title }}</p>
                                        <p class="text-xs text-gray-500">{{ achievement.achievement_date }}</p>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No achievements recorded</p>
                        </div>
                    </div>

                    <!-- Top Skills -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Skills</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div v-if="selectedStudent.skills && selectedStudent.skills.length > 0" class="space-y-2">
                                <div v-for="skill in selectedStudent.skills.slice(0, 4)" :key="skill.id" 
                                     class="flex items-center justify-between p-2 bg-white rounded">
                                    <span class="text-sm font-medium">{{ skill.name }}</span>
                                    <span :class="getSkillLevelColor(skill.level)" class="px-2 py-1 rounded-full text-xs">
                                        {{ skill.level }}
                                    </span>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No skills recorded</p>
                        </div>
                    </div>

                    <!-- Affiliations -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Affiliations</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div v-if="selectedStudent.affiliations && selectedStudent.affiliations.length > 0" class="space-y-2">
                                <div v-for="affiliation in selectedStudent.affiliations.slice(0, 3)" :key="affiliation.id" 
                                     class="p-2 bg-white rounded">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium">{{ affiliation.name }}</span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ affiliation.type }}</span>
                                    </div>
                                    <p v-if="affiliation.role" class="text-xs text-gray-600">Role: {{ affiliation.role }}</p>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No affiliations recorded</p>
                        </div>
                    </div>

                    <!-- Violations -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Disciplinary Records</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div v-if="selectedStudent.violations && selectedStudent.violations.length > 0" class="space-y-2">
                                <div v-for="violation in selectedStudent.violations.slice(0, 3)" :key="violation.id" 
                                     class="p-2 bg-white rounded">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium">{{ violation.type }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span :class="getSeverityColor(violation.severity)" class="px-2 py-1 rounded-full text-xs">
                                                {{ violation.severity }}
                                            </span>
                                            <span :class="violation.status === 'pending' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'" 
                                                  class="px-2 py-1 rounded-full text-xs">
                                                {{ violation.status }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-600">{{ violation.description }}</p>
                                    <p class="text-xs text-gray-500">Date: {{ violation.violation_date }}</p>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No violations recorded</p>
                        </div>

                    <!-- Medical Records -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Medical Records</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div v-if="selectedStudent.medicalRecord" class="space-y-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Blood Information</h4>
                                        <div class="bg-white rounded p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-sm text-gray-600">Blood Type:</span>
                                                <span class="text-sm font-bold text-red-600">{{ selectedStudent.medicalRecord.blood_type }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Allergies & Medications</h4>
                                        <div class="bg-white rounded p-3 space-y-2">
                                            <div>
                                                <span class="text-sm text-gray-600">Allergies:</span>
                                                <span class="text-sm font-medium text-orange-600">{{ selectedStudent.medicalRecord.allergies || 'None' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-600">Medications:</span>
                                                <span class="text-sm font-medium text-blue-600">{{ selectedStudent.medicalRecord.medications || 'None' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Medical Conditions</h4>
                                        <div class="bg-white rounded p-3">
                                            <div>
                                                <span class="text-sm text-gray-600">Conditions:</span>
                                                <span class="text-sm font-medium text-purple-600">{{ selectedStudent.medicalRecord.medical_conditions || 'None' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Medical History</h4>
                                        <div class="bg-white rounded p-3">
                                            <p class="text-sm text-gray-700">{{ selectedStudent.medicalRecord.medical_history || 'No significant medical history' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Emergency Contact</h4>
                                        <div class="bg-white rounded p-3 space-y-2">
                                            <div>
                                                <span class="text-sm text-gray-600">Name:</span>
                                                <span class="text-sm font-medium text-gray-900">{{ selectedStudent.medicalRecord.emergency_contact_name }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-600">Phone:</span>
                                                <span class="text-sm font-medium text-green-600">{{ selectedStudent.medicalRecord.emergency_contact_phone }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-600">Relationship:</span>
                                                <span class="text-sm font-medium text-blue-600">{{ selectedStudent.medicalRecord.emergency_contact_relationship }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Physician Information</h4>
                                        <div class="bg-white rounded p-3 space-y-2">
                                            <div>
                                                <span class="text-sm text-gray-600">Physician:</span>
                                                <span class="text-sm font-medium text-gray-900">{{ selectedStudent.medicalRecord.physician_name || 'Not specified' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-600">Phone:</span>
                                                <span class="text-sm font-medium text-green-600">{{ selectedStudent.medicalRecord.physician_phone || 'Not specified' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-600">Last Checkup:</span>
                                                <span class="text-sm font-medium text-orange-600">{{ selectedStudent.medicalRecord.last_checkup || 'Not recorded' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Insurance Information</h4>
                                    <div class="bg-white rounded p-3 space-y-2">
                                        <div>
                                            <span class="text-sm text-gray-600">Provider:</span>
                                            <span class="text-sm font-medium text-blue-600">{{ selectedStudent.medicalRecord.insurance_provider || 'Not specified' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">Policy Number:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ selectedStudent.medicalRecord.insurance_policy_number || 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No medical records available</p>
                        </div>
                    </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="student in (selectedSection.enrolled_students || []).slice(0, 10)" :key="student.stud_id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-orange-600">{{ student.fname.charAt(0) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ student.fname }} {{ student.mname ? student.mname + ' ' : '' }}{{ student.lname }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ student.stud_num }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Link 
                                        :href="`/faculty/students/${student.stud_id}`"
                                        class="text-orange-600 hover:text-orange-800 text-sm font-medium"
                                    >
                                        View Details
                                    </Link>
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
