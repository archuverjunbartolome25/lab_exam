<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import StudentLayout from '@/layouts/StudentLayout.vue';
import { 
    BookOpen, 
    Calendar, 
    Award,
    CalendarDays,
    TrendingUp,
    Clock,
    User,
    Search,
    BookMarked,
    Target,
    Bell
} from 'lucide-vue-next';

const studentInfo = ref({
    name: 'John Smith',
    id: '202100123',
    program: 'BSCS',
    yearLevel: 3,
    gpa: 3.75,
    enrollmentStatus: 'Regular',
});

const currentCourses = ref([
    {
        id: 1,
        code: 'CCS101',
        title: 'Computer Fundamentals',
        units: 3,
        instructor: 'Maria Garcia',
        schedule: 'MWF 8:00-10:00 AM',
        room: 'Lab 201',
        grade: 'A-'
    },
    {
        id: 2,
        code: 'CCS102',
        title: 'Programming 1',
        units: 3,
        instructor: 'John Reyes',
        schedule: 'TTH 1:00-3:00 PM',
        room: 'Room 305',
        grade: 'B+'
    },
    {
        id: 3,
        code: 'CCS201',
        title: 'Data Structures',
        units: 3,
        instructor: 'Maria Garcia',
        schedule: 'MWF 3:00-5:00 PM',
        room: 'Lab 202',
        grade: 'In Progress'
    }
]);

const upcomingEvents = ref([
    {
        id: 1,
        title: 'Midterm Examination Week',
        date: 'March 15-19, 2026',
        type: 'academic',
        location: 'All Rooms'
    },
    {
        id: 2,
        title: 'CCS Sports Fest',
        date: 'March 25, 2026',
        type: 'extracurricular',
        location: 'CCS Grounds'
    },
    {
        id: 3,
        title: 'Career Guidance Seminar',
        date: 'April 2, 2026',
        type: 'guidance',
        location: 'Conference Hall A'
    }
]);

const recentGrades = ref([
    {
        id: 1,
        course: 'CCS101',
        title: 'Computer Fundamentals',
        grade: 'A-',
        units: 3,
        semester: '1st Sem 2026',
        date: 'October 2025'
    },
    {
        id: 2,
        course: 'MATH101',
        title: 'College Algebra',
        grade: 'B+',
        units: 3,
        semester: '1st Sem 2026',
        date: 'October 2025'
    }
]);

const searchQuery = ref('');
</script>

<template>
    <StudentLayout>
        <Head title="Student Dashboard" />

        <div class="space-y-6">
            <!-- Welcome Header -->
            <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ studentInfo.name }}!</h1>
                        <div class="flex items-center space-x-4 text-orange-50">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">
                                ID: {{ studentInfo.id }}
                            </span>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ studentInfo.program }} - Year {{ studentInfo.yearLevel }}
                            </span>
                            <span class="bg-yellow-400 text-green-900 px-3 py-1 rounded-full text-sm font-semibold">
                                GPA: {{ studentInfo.gpa }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-sm text-orange-50">View-Only Access</p>
                            <p class="text-xs text-orange-100 mt-1">Browse & Search Mode</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                            <BookOpen class="h-6 w-6 text-orange-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Current Courses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ currentCourses.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                            <Award class="h-6 w-6 text-orange-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Current GPA</p>
                            <p class="text-2xl font-bold text-gray-900">{{ studentInfo.gpa }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                            <Calendar class="h-6 w-6 text-orange-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Units</p>
                            <p class="text-2xl font-bold text-gray-900">{{ currentCourses.reduce((sum, course) => sum + course.units, 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                            <Target class="h-6 w-6 text-orange-500" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Year Level</p>
                            <p class="text-2xl font-bold text-gray-900">{{ studentInfo.yearLevel }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Current Courses -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <BookOpen class="h-5 w-5 mr-2 text-green-600" />
                                Current Courses
                            </h3>
                            <div class="flex items-center space-x-2">
                                <div class="relative">
                                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                                    <input 
                                        v-model="searchQuery"
                                        type="text" 
                                        placeholder="Search courses..."
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-for="course in currentCourses" :key="course.id" class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">
                                        {{ course.code }}
                                    </div>
                                    <h4 class="font-semibold text-gray-900">{{ course.title }}</h4>
                                </div>
                                <div class="text-right">
                                    <span v-if="course.grade !== 'In Progress'" 
                                          class="bg-blue-100 text-blue-800 text-sm font-semibold px-2 py-1 rounded-full">
                                        {{ course.grade }}
                                    </span>
                                    <span v-else class="bg-orange-100 text-orange-800 text-sm font-semibold px-2 py-1 rounded-full">
                                        {{ course.grade }}
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <User class="h-4 w-4 mr-1" />
                                    <span>{{ course.instructor }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Clock class="h-4 w-4 mr-1" />
                                    <span>{{ course.schedule }}</span>
                                </div>
                                <div class="flex items-center">
                                    <BookOpen class="h-4 w-4 mr-1" />
                                    <span>{{ course.room }}</span>
                                </div>
                                <div class="flex items-center">
                                    <Award class="h-4 w-4 mr-1" />
                                    <span>{{ course.units }} units</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <CalendarDays class="h-5 w-5 mr-2 text-purple-600" />
                            Upcoming Events
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-for="event in upcomingEvents" :key="event.id" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div v-if="event.type === 'academic'" class="bg-red-100 rounded-full p-2">
                                    <Calendar class="h-4 w-4 text-red-600" />
                                </div>
                                <div v-else-if="event.type === 'extracurricular'" class="bg-blue-100 rounded-full p-2">
                                    <Target class="h-4 w-4 text-blue-600" />
                                </div>
                                <div v-else class="bg-green-100 rounded-full p-2">
                                    <Bell class="h-4 w-4 text-green-600" />
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ event.title }}</p>
                                <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                                    <span>{{ event.date }}</span>
                                    <span>•</span>
                                    <span>{{ event.location }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Grades -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <Award class="h-5 w-5 mr-2 text-blue-600" />
                            Recent Grades
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-for="grade in recentGrades" :key="grade.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ grade.course }} - {{ grade.title }}</p>
                                <p class="text-xs text-gray-500">{{ grade.semester }} • {{ grade.units }} units</p>
                            </div>
                            <div class="text-right">
                                <span class="bg-blue-100 text-blue-800 text-lg font-bold px-3 py-1 rounded-full">
                                    {{ grade.grade }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Access</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <Link href="/student/courses" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                            <BookOpen class="h-8 w-8 text-green-600 mb-2 group-hover:scale-110 transition-transform" />
                            <span class="text-sm font-medium text-gray-700">All Courses</span>
                        </Link>
                        <Link href="/student/grades" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                            <Award class="h-8 w-8 text-blue-600 mb-2 group-hover:scale-110 transition-transform" />
                            <span class="text-sm font-medium text-gray-700">Grade History</span>
                        </Link>
                        <Link href="/student/schedule" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                            <Calendar class="h-8 w-8 text-purple-600 mb-2 group-hover:scale-110 transition-transform" />
                            <span class="text-sm font-medium text-gray-700">Class Schedule</span>
                        </Link>
                        <Link href="/student/events" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                            <CalendarDays class="h-8 w-8 text-orange-600 mb-2 group-hover:scale-110 transition-transform" />
                            <span class="text-sm font-medium text-gray-700">Events</span>
                        </Link>
                        <Link href="/student/records" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                            <BookMarked class="h-8 w-8 text-red-600 mb-2 group-hover:scale-110 transition-transform" />
                            <span class="text-sm font-medium text-gray-700">My Records</span>
                        </Link>
                        <Link href="/student/tracker" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg hover:shadow-md transition-all group">
                            <Target class="h-8 w-8 text-indigo-600 mb-2 group-hover:scale-110 transition-transform" />
                            <span class="text-sm font-medium text-gray-700">Academic Tracker</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- View Only Notice -->
            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">View-Only Access</h3>
                        <p class="text-sm text-green-700 mt-1">You can browse and search all your academic information. For any changes or updates, please contact the system administrator or your faculty advisor.</p>
                    </div>
                </div>
            </div>
        </div>
    </StudentLayout>
</template>
