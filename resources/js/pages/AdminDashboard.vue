<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
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
    LogOut,
    TrendingUp,
    TrendingDown,
    Activity,
    Clock,
    AlertCircle,
    CheckCircle,
    ArrowUpRight,
    ArrowDownRight,
    MoreHorizontal,
    Search,
    Bell
} from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Dashboard stats
const stats = ref([
    {
        title: 'Total Students',
        value: '0',
        change: '+0%',
        changeType: 'increase',
        icon: Users,
        color: 'from-blue-500 to-blue-600',
        bgColor: 'bg-blue-50'
    },
    {
        title: 'Faculty Members',
        value: '0',
        change: '+0%',
        changeType: 'increase',
        icon: UserCheck,
        color: 'from-green-500 to-green-600',
        bgColor: 'bg-green-50'
    },
    {
        title: 'Active Courses',
        value: '0',
        change: '+0%',
        changeType: 'increase',
        icon: BookOpen,
        color: 'from-purple-500 to-purple-600',
        bgColor: 'bg-purple-50'
    },
    {
        title: 'Total Users',
        value: '0',
        change: '+0%',
        changeType: 'increase',
        icon: Users,
        color: 'from-indigo-500 to-indigo-600',
        bgColor: 'bg-indigo-50'
    }
]);

// Recent activities
const recentActivities = ref<any[]>([]);

// Quick actions
const quickActions = [
    {
        title: 'Add New Student',
        description: 'Register a new student in the system',
        icon: Users,
        href: '/admin/students/create',
        color: 'bg-blue-500 hover:bg-blue-600'
    },
    {
        title: 'Create Course',
        description: 'Add a new course to the curriculum',
        icon: BookOpen,
        href: '/admin/courses/create',
        color: 'bg-green-500 hover:bg-green-600'
    },
    {
        title: 'Generate Report',
        description: 'Create system reports and analytics',
        icon: FileText,
        href: '/admin/reports',
        color: 'bg-purple-500 hover:bg-purple-600'
    },
    {
        title: 'System Settings',
        description: 'Configure system preferences',
        icon: Settings,
        href: '/admin/settings',
        color: 'bg-orange-500 hover:bg-orange-600'
    }
];

// Chart data (placeholder)
const chartData = ref({
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [
        {
            label: 'Students',
            data: [65, 78, 90, 81, 96, 105],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
        },
        {
            label: 'Faculty',
            data: [28, 48, 40, 59, 66, 77],
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
        }
    ]
});

// Search functionality
const searchQuery = ref('');

// Notification count
const notificationCount = ref(5);

// Format time helper
const formatTime = (time: string) => {
    return time;
};

// Fetch real data from API
onMounted(async () => {
    try {
        // Fetch admin stats
        const statsResponse = await fetch('/api/dashboard/admin-stats');
        const statsData = await statsResponse.json();
        
        // Update stats with real data
        stats.value = [
            {
                title: 'Total Students',
                value: statsData.totalStudents || '0',
                change: `+${statsData.studentGrowth || 0}%`,
                changeType: 'increase',
                icon: Users,
                color: 'from-blue-500 to-blue-600',
                bgColor: 'bg-blue-50'
            },
            {
                title: 'Faculty Members',
                value: statsData.totalFaculty || '0',
                change: `+${statsData.facultyGrowth || 0}%`,
                changeType: 'increase',
                icon: UserCheck,
                color: 'from-green-500 to-green-600',
                bgColor: 'bg-green-50'
            },
            {
                title: 'Active Courses',
                value: statsData.totalCourses || '0',
                change: `+${statsData.courseGrowth || 0}%`,
                changeType: 'increase',
                icon: BookOpen,
                color: 'from-purple-500 to-purple-600',
                bgColor: 'bg-purple-50'
            },
            {
                title: 'Total Users',
                value: statsData.totalUsers || '0',
                change: `+${statsData.userGrowth || 0}%`,
                changeType: 'increase',
                icon: Users,
                color: 'from-indigo-500 to-indigo-600',
                bgColor: 'bg-indigo-50'
            }
        ];

        // Fetch recent activities
        const activitiesResponse = await fetch('/api/dashboard/recent-activities');
        const activitiesData = await activitiesResponse.json();
        
        // Map API data to component format
        recentActivities.value = activitiesData.map((activity: any) => ({
            id: activity.id,
            type: activity.type,
            message: activity.message,
            time: activity.time,
            icon: getIconComponent(activity.icon),
            color: activity.color
        }));
    } catch (error) {
        console.error('Failed to fetch admin dashboard data:', error);
    }
});

// Helper function to map icon names to components
const getIconComponent = (iconName: string) => {
    const iconMap: { [key: string]: any } = {
        'Users': Users,
        'UserCheck': UserCheck,
        'BookOpen': BookOpen,
        'CalendarDays': CalendarDays,
        'Calendar': Calendar,
        'FileText': FileText,
        'AlertCircle': AlertCircle,
        'CheckCircle': CheckCircle,
        'Settings': Settings,
        'BarChart3': BarChart3,
        'ShieldCheck': ShieldCheck,
        'LogOut': LogOut,
        'TrendingUp': TrendingUp,
        'TrendingDown': TrendingDown,
        'Activity': Activity,
        'Clock': Clock,
        'ArrowUpRight': ArrowUpRight,
        'ArrowDownRight': ArrowDownRight,
        'MoreHorizontal': MoreHorizontal,
        'Search': Search,
        'Bell': Bell
    };
    return iconMap[iconName] || Users;
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Welcome back! Here's what's happening in your system.</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <Search class="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>
                
                <!-- Notifications -->
                <div class="relative">
                    <button class="p-2 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white relative">
                        <Bell class="h-5 w-5" />
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="stat in stats" :key="stat.title" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ stat.title }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ stat.value }}</p>
                        <div class="flex items-center mt-2">
                            <span :class="stat.changeType === 'increase' ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
                                {{ stat.change }}
                            </span>
                            <component :is="stat.changeType === 'increase' ? ArrowUpRight : ArrowDownRight" 
                                       :class="stat.changeType === 'increase' ? 'text-green-600' : 'text-red-600'" 
                                       class="h-4 w-4 ml-1" />
                        </div>
                    </div>
                    <div :class="stat.bgColor" class="p-3 rounded-lg">
                        <component :is="stat.icon" class="h-6 w-6 text-gray-700" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activities -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activities</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-3">
                        <div :class="activity.color" class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                            <component :is="activity.icon" class="h-5 w-5" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activity.message }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ activity.time }}</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <MoreHorizontal class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <Link v-for="action in quickActions" :key="action.title" :href="action.href"
                          class="block p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-400 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div :class="action.color" class="p-2 rounded-lg text-white">
                                <component :is="action.icon" class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ action.title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ action.description }}</p>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>

        <!-- System Overview Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">System Overview</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Student and faculty growth over the last 6 months</p>
            </div>
            <div class="p-6">
                <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <BarChart3 class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <p class="text-gray-500 dark:text-gray-400">Chart visualization would go here</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Integration with Chart.js or similar library</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">System Status</p>
                        <p class="text-lg font-bold text-green-600 mt-1">Operational</p>
                    </div>
                    <CheckCircle class="h-8 w-8 text-green-500" />
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Database</p>
                        <p class="text-lg font-bold text-blue-600 mt-1">Connected</p>
                    </div>
                    <Activity class="h-8 w-8 text-blue-500" />
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Backup</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">45 minutes ago</p>
                    </div>
                    <Clock class="h-8 w-8 text-gray-500" />
                </div>
            </div>
        </div>
    </div>
</template>
