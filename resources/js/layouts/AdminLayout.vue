<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
    Users, 
    UserCheck, 
    BookOpen, 
    CalendarDays, 
    Calendar, 
    Settings, 
    BarChart3,
    FileText,
    ShieldCheck,
    LogOut
} from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import { type BreadcrumbItem } from '@/types';

const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
    },
];

// Get authenticated admin user
const authUser = computed(() => page.props.auth?.user);

// Get user initials for avatar
const userInitials = computed(() => {
    if (!authUser.value) return 'A';
    const firstName = String(authUser.value.fname || '');
    const lastName = String(authUser.value.lname || '');
    return (firstName.charAt(0) + lastName.charAt(0)).toUpperCase() || 'A';
});

// Get full name
const fullName = computed(() => {
    if (!authUser.value) return 'Admin User';
    return `${String(authUser.value.fname || '')} ${String(authUser.value.lname || '')}`.trim() || 'Admin User';
});

const sidebarNavItems = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
        icon: BarChart3,
    },
    {
        title: 'Users',
        href: '/admin/users',
        icon: Users,
    },
    {
        title: 'Students',
        href: '/admin/students',
        icon: Users,
    },
    {
        title: 'Faculty',
        href: '/admin/faculty',
        icon: UserCheck,
    },
    {
        title: 'Courses',
        href: '/admin/courses',
        icon: BookOpen,
    },
    {
        title: 'Schedules',
        href: '/admin/schedules',
        icon: Calendar,
    },
    {
        title: 'Events',
        href: '/admin/events',
        icon: CalendarDays,
    },
    {
        title: 'Reports',
        href: '/admin/reports',
        icon: FileText,
    },
    {
        title: 'Settings',
        href: '/admin/settings',
        icon: Settings,
    },
];

const isSidebarOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
        <Head title="Admin Dashboard" />

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
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex-shrink-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center justify-between h-16 px-4 bg-orange-400 dark:bg-orange-500">
                <div class="flex items-center">
                    <AppLogo class="h-8 w-8 text-white" />
                    <span class="ml-2 text-white font-semibold">Admin Panel</span>
                </div>
                <button
                    @click="isSidebarOpen = false"
                    class="lg:hidden text-white hover:bg-orange-700 p-2 rounded-md"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-5 px-2">
                <div class="space-y-2">
                    <Link
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        :href="item.href"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 hover:bg-orange-100 dark:hover:bg-orange-800 hover:text-orange-600 dark:hover:text-orange-200 transition-colors duration-200"
                    >
                        <component :is="item.icon" class="mr-3 h-5 w-5" />
                        {{ item.title }}
                    </Link>
                </div>
            </nav>

            <!-- User section -->
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-600">{{ userInitials }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ fullName }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ authUser?.email || 'admin@labexam.com' }}</p>
                    </div>
                </div>
                <Link
                    href="/admin/logout"
                    method="post"
                    as="button"
                    class="w-full flex items-center px-4 py-2 text-sm font-medium rounded-md text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                >
                    <LogOut class="mr-3 h-5 w-5" />
                    Logout
                </Link>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-h-0 lg:ml-64">
            <!-- Top header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
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
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">CCS Admin Dashboard</h1>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-auto">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Your page content goes here -->
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
