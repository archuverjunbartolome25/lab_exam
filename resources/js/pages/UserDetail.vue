<script setup lang="ts">
// =====================================================
// PART 2: DYNAMIC ROUTING (SERRANO)
// =====================================================
// Create a dynamic route:
// /users/:id
// Behavior:
// Clicking a user from the list opens their details page

import { Head, Link } from '@inertiajs/vue3';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { ref, computed } from 'vue';
import { 
    User, 
    Mail, 
    Phone, 
    Calendar, 
    MapPin, 
    BookOpen, 
    Award, 
    AlertTriangle,
    Edit,
    ArrowLeft,
    Users,
    Target,
    Heart,
    FileText
} from 'lucide-vue-next';

// Dynamic route parameters - User ID passed from URL /users/:id
defineOptions({
    layout: FacultyLayout,
});

const props = defineProps({
    // User data passed from Laravel controller via Inertia
    user: {
        type: Object,
        required: true
    },
    // Section information for the user
    section: {
        type: Object,
        default: null
    }
});

const activeTab = ref('overview');

const fullName = computed(() => {
    return `${props.user.lname}, ${props.user.fname} ${props.user.mname || ''}`.trim();
});

const getStatusColor = (status: string) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
};

const getSeverityColor = (severity: string) => {
    switch (severity) {
        case 'Minor': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        case 'Moderate': return 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300';
        case 'Major': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
        case 'Severe': return 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
    }
};
</script>

<template>
    <Head :title="`${user.fname} ${user.lname} - User Details`" />
    
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link 
                        href="/faculty/students" 
                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        <ArrowLeft class="h-5 w-5" />
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Details</h1>
                        <p class="text-gray-600 dark:text-gray-400">View and manage user information</p>
                    </div>
                </div>
                <button class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center">
                    <Edit class="h-4 w-4 mr-2" />
                    Edit User
                </button>
            </div>
        </div>

        <!-- User Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-start space-x-6">
                <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl font-bold text-gray-600">{{ user.fname.charAt(0) }}</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ fullName }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">ID: {{ user.stud_num?.toString().padStart(6, '0') || user.id?.toString().padStart(6, '0') }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <span :class="getStatusColor(user.academic_status || 'active')" class="px-2 py-1 text-xs font-semibold rounded-full">
                            {{ user.academic_status || 'Active' }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ user.program }} - {{ user.standing }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="flex space-x-8">
                <button
                    v-for="tab in ['overview', 'academic', 'activities', 'medical']"
                    :key="tab"
                    @click="activeTab = tab"
                    :class="[
                        'py-2 px-1 border-b-2 font-medium text-sm capitalize',
                        activeTab === tab
                            ? 'border-orange-500 text-orange-600 dark:text-orange-400'
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
                    ]"
                >
                    {{ tab }}
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Overview Tab -->
                <div v-if="activeTab === 'overview'" class="space-y-6">
                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <Mail class="h-5 w-5 text-gray-400 mr-3" />
                                <span class="text-gray-900 dark:text-white">{{ user.email }}</span>
                            </div>
                            <div class="flex items-center">
                                <Phone class="h-5 w-5 text-gray-400 mr-3" />
                                <span class="text-gray-900 dark:text-white">{{ user.contact_num }}</span>
                            </div>
                            <div class="flex items-center">
                                <MapPin class="h-5 w-5 text-gray-400 mr-3" />
                                <span class="text-gray-900 dark:text-white">{{ user.address }}</span>
                            </div>
                            <div class="flex items-center">
                                <Calendar class="h-5 w-5 text-gray-400 mr-3" />
                                <span class="text-gray-900 dark:text-white">{{ user.bday }} ({{ user.gender }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Academic Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Program:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ user.program }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Section:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ section?.section_name || 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Year Level:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ user.standing }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Academic Status:</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ user.academic_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Tab -->
                <div v-if="activeTab === 'academic'" class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Courses</h3>
                        <div class="space-y-2">
                            <div v-for="course in user.courses_info || []" :key="course.id" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ course.code }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ course.name }}</p>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ course.credits }} credits</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activities Tab -->
                <div v-if="activeTab === 'activities'" class="space-y-6">
                    <!-- Achievements -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <Award class="h-5 w-5 mr-2 text-orange-500" />
                            Achievements
                        </h3>
                        <div class="space-y-3">
                            <div v-for="achievement in user.achievements || []" :key="achievement.id" class="border-l-4 border-orange-500 pl-4">
                                <p class="font-medium text-gray-900 dark:text-white">{{ achievement.title }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ achievement.description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ achievement.achievement_date }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <Target class="h-5 w-5 mr-2 text-blue-500" />
                            Skills
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="skill in user.skills || []" :key="skill.id" class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full text-sm">
                                {{ skill.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Violations -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <AlertTriangle class="h-5 w-5 mr-2 text-red-500" />
                            Violations
                        </h3>
                        <div class="space-y-3">
                            <div v-for="violation in user.violations || []" :key="violation.id" class="border-l-4 border-red-500 pl-4">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ violation.type }}</p>
                                    <span :class="getSeverityColor(violation.severity)" class="px-2 py-1 text-xs font-semibold rounded">
                                        {{ violation.severity }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ violation.description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ violation.violation_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Tab -->
                <div v-if="activeTab === 'medical'" class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <Heart class="h-5 w-5 mr-2 text-red-500" />
                            Medical Records
                        </h3>
                        <div class="space-y-3">
                            <div v-for="record in user.medicalRecord || []" :key="record.id" class="border-l-4 border-red-500 pl-4">
                                <p class="font-medium text-gray-900 dark:text-white">{{ record.condition }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ record.description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Diagnosed: {{ record.diagnosis_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <button class="w-full px-4 py-2 text-left text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            Add Course
                        </button>
                        <button class="w-full px-4 py-2 text-left text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            Add Achievement
                        </button>
                        <button class="w-full px-4 py-2 text-left text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            Add Skill
                        </button>
                        <button class="w-full px-4 py-2 text-left text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            Generate Report
                        </button>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">GPA:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ user.current_gpa || 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Credits:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ user.total_credits || '0' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Achievements:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ user.achievements?.length || 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Active Violations:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ user.active_violations || 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
