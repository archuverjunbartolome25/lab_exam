<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Users, UserCheck, BookOpen, CalendarDays, Calendar, Search, LayoutDashboard, FileText } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const stats = ref({
    totalStudents: 0,
    totalFaculty: 0,
    totalCourses: 0,
    totalEvents: 0,
});

const recentActivities = ref<any[]>([]);

onMounted(async () => {
    try {
        // Fetch stats
        const statsResponse = await fetch('/api/dashboard/stats');
        const statsData = await statsResponse.json();
        stats.value = statsData;

        // Fetch recent activities
        const activitiesResponse = await fetch('/api/dashboard/recent-activities');
        const activitiesData = await activitiesResponse.json();
        
        // Map API data to component format
        recentActivities.value = activitiesData.map((activity: any) => ({
            id: activity.id,
            description: activity.message,
            time: activity.time,
            icon: getIconComponent(activity.icon),
        }));
    } catch (error) {
        console.error('Failed to fetch dashboard data:', error);
        // Fallback data
        stats.value = {
            totalStudents: 0,
            totalFaculty: 0,
            totalCourses: 0,
            totalEvents: 0,
        };
        recentActivities.value = [];
    }
});

// Helper function to map icon names to components
const getIconComponent = (iconName: string) => {
    const iconMap: { [key: string]: any } = {
        'Users': Users,
        'BookOpen': BookOpen,
        'Calendar': Calendar,
        'CalendarDays': CalendarDays,
        'FileText': FileText,
    };
    return iconMap[iconName] || Users;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                            <Users class="w-6 h-6 text-orange-600 dark:text-orange-300" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ stats.totalStudents }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Students</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                            <UserCheck class="w-6 h-6 text-orange-600 dark:text-orange-300" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ stats.totalFaculty }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Faculty</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                            <BookOpen class="w-6 h-6 text-orange-600 dark:text-orange-300" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ stats.totalCourses }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Courses</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                            <CalendarDays class="w-6 h-6 text-orange-600 dark:text-orange-300" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ stats.totalEvents }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Events</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Recent Activity
                </h2>
                <div class="space-y-4">
                    <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                        <div class="flex-shrink-0">
                            <component :is="activity.icon" class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-900 dark:text-white">{{ activity.description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ activity.time }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
