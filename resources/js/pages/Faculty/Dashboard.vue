<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { 
    BookOpen, 
    Calendar, 
    Users, 
    CalendarDays,
    Clock,
    Search,
    TrendingUp,
    BookMarked,
    UserCheck
} from 'lucide-vue-next';

// Define props for data passed from controller
const props = defineProps({
    stats: {
        type: Object,
        required: true
    }
});

const searchTab = ref('students');
const searchQuery = ref('');
</script>

<template>
    <FacultyLayout>
        <Head title="Faculty Dashboard" />

        <div class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                            <Users class="h-6 w-6 text-orange-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Students</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.total_students }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                            <BookOpen class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Courses</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.total_courses }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                            <CalendarDays class="h-6 w-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Sections</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.total_sections }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Interface -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <!-- Search Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button
                            @click="searchTab = 'students'"
                            :class="[
                                searchTab === 'students'
                                    ? 'border-orange-500 text-orange-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200'
                            ]"
                        >
                            <Users class="h-4 w-4 inline mr-2" />
                            Search Students
                        </button>
                        <button
                            @click="searchTab = 'sections'"
                            :class="[
                                searchTab === 'sections'
                                    ? 'border-orange-500 text-orange-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200'
                            ]"
                        >
                            <BookOpen class="h-4 w-4 inline mr-2" />
                            Search Sections
                        </button>
                        <button
                            @click="searchTab = 'courses'"
                            :class="[
                                searchTab === 'courses'
                                    ? 'border-orange-500 text-orange-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200'
                            ]"
                        >
                            <CalendarDays class="h-4 w-4 inline mr-2" />
                            Search Courses
                        </button>
                    </nav>
                </div>

                <!-- Search Content -->
                <div class="p-6">
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                :placeholder="searchTab === 'students' ? 'Search students by name, ID, or email...' : 
                                            searchTab === 'sections' ? 'Search sections by name or course...' :
                                            'Search courses by code or title...'"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                            />
                        </div>
                    </div>
            </div>

            <!-- Quick Access Actions -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Access</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link href="/faculty/courses" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                        <BookOpen class="h-8 w-8 text-blue-600 mb-2 group-hover:scale-110 transition-transform" />
                        <span class="text-sm font-medium text-gray-700">View Courses</span>
                    </Link>
                    <Link href="/faculty/students" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                        <Users class="h-8 w-8 text-green-600 mb-2 group-hover:scale-110 transition-transform" />
                        <span class="text-sm font-medium text-gray-700">View Students</span>
                    </Link>
                    <Link href="/faculty/schedule" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                        <Calendar class="h-8 w-8 text-purple-600 mb-2 group-hover:scale-110 transition-transform" />
                        <span class="text-sm font-medium text-gray-700">View Schedule</span>
                    </Link>
                    <Link href="/faculty/events" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                        <CalendarDays class="h-8 w-8 text-orange-600 mb-2 group-hover:scale-110 transition-transform" />
                        <span class="text-sm font-medium text-gray-700">View Events</span>
                    </Link>
                </div>
            </div>

            <!-- View Only Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">View-Only Access</h3>
                        <p class="text-sm text-blue-700 mt-1">You can browse and search all information. For any modifications, please contact system administrator.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </FacultyLayout>
</template>
