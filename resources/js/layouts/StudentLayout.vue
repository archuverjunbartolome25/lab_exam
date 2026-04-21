<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { 
    BookOpen, 
    CalendarDays, 
    Calendar, 
    FileText,
    GraduationCap,
    Award,
    User,
    Settings,
    LogOut
} from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import { type BreadcrumbItem } from '@/types';
import { syncWithPageProps } from '@/stores/globalState';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/student/dashboard',
    },
];

const sidebarNavItems = [
    {
        title: 'Dashboard',
        href: '/student/dashboard',
        icon: GraduationCap,
    },
    {
        title: 'My Courses',
        href: '/student/courses',
        icon: BookOpen,
    },
    {
        title: 'Schedule',
        href: '/student/schedule',
        icon: Calendar,
    },
    {
        title: 'Grades',
        href: '/student/grades',
        icon: Award,
    },
    {
        title: 'Events',
        href: '/student/events',
        icon: CalendarDays,
    },
    {
        title: 'Profile',
        href: '/student/profile',
        icon: User,
    },
    {
        title: 'Reports',
        href: '/student/reports',
        icon: FileText,
    },
    {
        title: 'Settings',
        href: '/student/settings',
        icon: Settings,
    },
];

const isSidebarOpen = ref(false);
const isSidebarCollapsed = ref(false);

// Get page props and user data
const page = usePage();
const authUser = computed(() => page.props.auth?.user);

// Get user initials for avatar
const userInitials = computed(() => {
    if (!authUser.value) return 'S';
    const firstName = String(authUser.value.fname || '');
    const lastName = String(authUser.value.lname || '');
    return (firstName.charAt(0) + lastName.charAt(0)).toUpperCase() || 'S';
});

// Get full name
const fullName = computed(() => {
    if (!authUser.value) return 'Student User';
    return `${String(authUser.value.fname || '')} ${String(authUser.value.lname || '')}`.trim() || 'Student User';
});

// Sync with page props on mount
onMounted(() => {
    syncWithPageProps(page.props);
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
        <Head title="Student Dashboard" />

        <!-- Mobile sidebar backdrop -->
        <div
            v-if="isSidebarOpen"
            class="fixed inset-0 z-40 lg:hidden"
            @click="isSidebarOpen = false"
        >
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>

        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 z-50 bg-white dark:bg-gray-800 shadow-lg transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:fixed"
            :class="[
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
                isSidebarCollapsed ? 'lg:w-16' : 'lg:w-64'
            ]"
        >
            <div class="flex flex-col h-full">
                <!-- Header -->
                <div class="flex items-center justify-between h-16 px-4 bg-orange-400 dark:bg-orange-500 flex-shrink-0">
                    <div class="flex items-center">
                        <AppLogo class="h-8 w-8 text-white" :show-text="!isSidebarCollapsed" />
                    </div>

                </div>

                <!-- Navigation -->
                <nav class="flex-1 mt-5 px-2 overflow-y-auto">
                    <div class="space-y-2">
                        <Link
                            v-for="item in sidebarNavItems"
                            :key="item.href"
                            :href="item.href"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 hover:bg-orange-100 dark:hover:bg-orange-800 hover:text-orange-600 dark:hover:text-orange-200 transition-colors duration-200 group"
                            :title="isSidebarCollapsed ? item.title : ''"
                        >
                            <component :is="item.icon" class="h-5 w-5 flex-shrink-0" :class="isSidebarCollapsed ? '' : 'mr-3'" />
                            <span v-if="!isSidebarCollapsed" class="truncate">{{ item.title }}</span>
                        </Link>
                    </div>
                </nav>

                <!-- User section -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <div class="flex items-center" :class="isSidebarCollapsed ? 'justify-center' : 'space-x-3'">
                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-medium text-gray-600">{{ userInitials }}</span>
                        </div>
                        <div v-if="!isSidebarCollapsed" class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ fullName }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ authUser?.email || 'student@labexam.com' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div :class="isSidebarCollapsed ? 'lg:pl-16' : 'lg:pl-64'" class="flex-1 flex flex-col transition-all duration-300">
            <!-- Top header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                    <button
                        @click="isSidebarOpen = true"
                        class="lg:hidden text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 p-2 rounded-md"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex items-center space-x-4">
                        <button
                            @click="isSidebarCollapsed = !isSidebarCollapsed"
                            class="hidden lg:flex text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 p-2 rounded-md transition-colors"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">CCS Student Portal</h1>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
