<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Search, Calendar, Clock, MapPin, Users, Plus, Edit, Trash2, Filter, Download, Star, AlertCircle } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Mock data for events
const events = ref([
    {
        id: 1,
        title: 'CCS Department Welcome Assembly',
        description: 'Annual welcome assembly for all CCS department students and faculty',
        type: 'assembly',
        category: 'academic',
        start_date: '2024-09-15',
        end_date: '2024-09-15',
        start_time: '09:00',
        end_time: '11:00',
        location: 'Main Auditorium',
        building: 'Building A',
        organizer: 'CCS Department',
        organizer_email: 'ccs@university.edu',
        max_participants: 500,
        current_participants: 342,
        status: 'upcoming',
        priority: 'high',
        is_recurring: false,
        registration_required: true,
        registration_deadline: '2024-09-10'
    },
    {
        id: 2,
        title: 'Programming Competition 2024',
        description: 'Annual programming competition for CCS students',
        type: 'competition',
        category: 'academic',
        start_date: '2024-10-20',
        end_date: '2024-10-21',
        start_time: '09:00',
        end_time: '17:00',
        location: 'Computer Lab 301',
        building: 'Building B',
        organizer: 'Computer Science Club',
        organizer_email: 'csclub@university.edu',
        max_participants: 100,
        current_participants: 87,
        status: 'upcoming',
        priority: 'medium',
        is_recurring: false,
        registration_required: true,
        registration_deadline: '2024-10-15'
    },
    {
        id: 3,
        title: 'Faculty Development Workshop',
        description: 'Professional development workshop for CCS faculty members',
        type: 'workshop',
        category: 'professional',
        start_date: '2024-08-25',
        end_date: '2024-08-25',
        start_time: '14:00',
        end_time: '17:00',
        location: 'Conference Room 201',
        building: 'Building A',
        organizer: 'HR Department',
        organizer_email: 'hr@university.edu',
        max_participants: 50,
        current_participants: 45,
        status: 'completed',
        priority: 'medium',
        is_recurring: false,
        registration_required: true,
        registration_deadline: '2024-08-20'
    },
    {
        id: 4,
        title: 'Career Fair 2024',
        description: 'Annual career fair with industry partners and recruiters',
        type: 'fair',
        category: 'career',
        start_date: '2024-11-08',
        end_date: '2024-11-08',
        start_time: '10:00',
        end_time: '16:00',
        location: 'Multi-purpose Hall',
        building: 'Building C',
        organizer: 'Career Services',
        organizer_email: 'careers@university.edu',
        max_participants: 1000,
        current_participants: 654,
        status: 'upcoming',
        priority: 'high',
        is_recurring: true,
        registration_required: false,
        registration_deadline: null
    },
    {
        id: 5,
        title: 'Research Symposium',
        description: 'Student and faculty research presentations',
        type: 'symposium',
        category: 'academic',
        start_date: '2024-12-05',
        end_date: '2024-12-06',
        start_time: '09:00',
        end_time: '18:00',
        location: 'Lecture Hall 101',
        building: 'Building A',
        organizer: 'Research Office',
        organizer_email: 'research@university.edu',
        max_participants: 200,
        current_participants: 156,
        status: 'upcoming',
        priority: 'high',
        is_recurring: false,
        registration_required: true,
        registration_deadline: '2024-11-30'
    },
    {
        id: 6,
        title: 'Weekly Department Meeting',
        description: 'Regular weekly department meeting for faculty and staff',
        type: 'meeting',
        category: 'administrative',
        start_date: '2024-08-19',
        end_date: '2024-08-19',
        start_time: '15:00',
        end_time: '16:30',
        location: 'Conference Room 201',
        building: 'Building A',
        organizer: 'CCS Department',
        organizer_email: 'ccs@university.edu',
        max_participants: 30,
        current_participants: 28,
        status: 'completed',
        priority: 'medium',
        is_recurring: true,
        registration_required: false,
        registration_deadline: null
    }
]);

// Search and filters
const searchQuery = ref('');
const selectedType = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const selectedPriority = ref('');
const selectedMonth = ref('');

// Filter options
const eventTypes = [
    { value: '', label: 'All Types' },
    { value: 'assembly', label: 'Assembly' },
    { value: 'workshop', label: 'Workshop' },
    { value: 'competition', label: 'Competition' },
    { value: 'fair', label: 'Fair' },
    { value: 'symposium', label: 'Symposium' },
    { value: 'meeting', label: 'Meeting' },
    { value: 'seminar', label: 'Seminar' }
];

const categories = [
    { value: '', label: 'All Categories' },
    { value: 'academic', label: 'Academic' },
    { value: 'professional', label: 'Professional' },
    { value: 'career', label: 'Career' },
    { value: 'administrative', label: 'Administrative' },
    { value: 'social', label: 'Social' }
];

const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'upcoming', label: 'Upcoming' },
    { value: 'ongoing', label: 'Ongoing' },
    { value: 'completed', label: 'Completed' },
    { value: 'cancelled', label: 'Cancelled' }
];

const priorityOptions = [
    { value: '', label: 'All Priorities' },
    { value: 'high', label: 'High' },
    { value: 'medium', label: 'Medium' },
    { value: 'low', label: 'Low' }
];

const months = [
    { value: '', label: 'All Months' },
    { value: '01', label: 'January' },
    { value: '02', label: 'February' },
    { value: '03', label: 'March' },
    { value: '04', label: 'April' },
    { value: '05', label: 'May' },
    { value: '06', label: 'June' },
    { value: '07', label: 'July' },
    { value: '08', label: 'August' },
    { value: '09', label: 'September' },
    { value: '10', label: 'October' },
    { value: '11', label: 'November' },
    { value: '12', label: 'December' }
];

// Computed filtered events
const filteredEvents = computed(() => {
    return events.value.filter(event => {
        const matchesSearch = !searchQuery.value || 
            event.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            event.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            event.location.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            event.organizer.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        const matchesType = !selectedType.value || event.type === selectedType.value;
        const matchesCategory = !selectedCategory.value || event.category === selectedCategory.value;
        const matchesStatus = !selectedStatus.value || event.status === selectedStatus.value;
        const matchesPriority = !selectedPriority.value || event.priority === selectedPriority.value;
        const matchesMonth = !selectedMonth.value || event.start_date.substring(5, 7) === selectedMonth.value;
        
        return matchesSearch && matchesType && matchesCategory && matchesStatus && matchesPriority && matchesMonth;
    });
});

// Statistics
const stats = computed(() => {
    const total = filteredEvents.value.length;
    const upcoming = filteredEvents.value.filter(e => e.status === 'upcoming').length;
    const ongoing = filteredEvents.value.filter(e => e.status === 'ongoing').length;
    const completed = filteredEvents.value.filter(e => e.status === 'completed').length;
    const highPriority = filteredEvents.value.filter(e => e.priority === 'high').length;
    const totalParticipants = filteredEvents.value.reduce((sum, e) => sum + e.current_participants, 0);
    
    return {
        total,
        upcoming,
        ongoing,
        completed,
        highPriority,
        totalParticipants
    };
});

// Helper functions
const getStatusColor = (status: string) => {
    switch(status) {
        case 'upcoming': return 'bg-blue-100 text-blue-800';
        case 'ongoing': return 'bg-green-100 text-green-800';
        case 'completed': return 'bg-gray-100 text-gray-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getPriorityColor = (priority: string) => {
    switch(priority) {
        case 'high': return 'bg-red-100 text-red-800';
        case 'medium': return 'bg-yellow-100 text-yellow-800';
        case 'low': return 'bg-green-100 text-green-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getTypeColor = (type: string) => {
    switch(type) {
        case 'assembly': return 'bg-purple-100 text-purple-800';
        case 'workshop': return 'bg-indigo-100 text-indigo-800';
        case 'competition': return 'bg-orange-100 text-orange-800';
        case 'fair': return 'bg-pink-100 text-pink-800';
        case 'symposium': return 'bg-cyan-100 text-cyan-800';
        case 'meeting': return 'bg-teal-100 text-teal-800';
        case 'seminar': return 'bg-lime-100 text-lime-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getCategoryColor = (category: string) => {
    switch(category) {
        case 'academic': return 'bg-blue-100 text-blue-800';
        case 'professional': return 'bg-purple-100 text-purple-800';
        case 'career': return 'bg-green-100 text-green-800';
        case 'administrative': return 'bg-orange-100 text-orange-800';
        case 'social': return 'bg-pink-100 text-pink-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getParticipantColor = (current: number, max: number) => {
    const percentage = (current / max) * 100;
    if (percentage >= 90) return 'text-red-600';
    if (percentage >= 75) return 'text-yellow-600';
    if (percentage >= 50) return 'text-blue-600';
    return 'text-green-600';
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
};

const deleteEvent = (id: number) => {
    if (confirm('Are you sure you want to delete this event?')) {
        // In a real application, this would make an API call
        const index = events.value.findIndex(e => e.id === id);
        if (index > -1) {
            events.value.splice(index, 1);
        }
    }
};

const exportEvents = () => {
    // In a real application, this would generate and download a file
    alert('Export functionality would be implemented here');
};
</script>

<template>
    <Head title="Event Management - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Event Management</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage university events and activities</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="exportEvents" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                    <Download class="h-4 w-4 mr-2" />
                    Export
                </button>
                <Link href="/admin/events/create" class="flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                    <Plus class="h-4 w-4 mr-2" />
                    Add Event
                </Link>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <Calendar class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <Clock class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Upcoming</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.upcoming }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <AlertCircle class="h-6 w-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ongoing</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.ongoing }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-100 dark:bg-gray-900 rounded-lg">
                        <Calendar class="h-6 w-6 text-gray-600 dark:text-gray-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.completed }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                        <Star class="h-6 w-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">High Priority</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.highPriority }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <Users class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Participants</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalParticipants }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search events..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                </div>
                
                <select 
                    v-model="selectedType"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="type in eventTypes" :key="type.value" :value="type.value">
                        {{ type.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedCategory"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="category in categories" :key="category.value" :value="category.value">
                        {{ category.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedStatus"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="status in statusOptions" :key="status.value" :value="status.value">
                        {{ status.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedPriority"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="priority in priorityOptions" :key="priority.value" :value="priority.value">
                        {{ priority.label }}
                    </option>
                </select>
                
                <select 
                    v-model="selectedMonth"
                    class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option v-for="month in months" :key="month.value" :value="month.value">
                        {{ month.label }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Events Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Event Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Organizer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Participants
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status & Priority
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="event in filteredEvents" :key="event.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ event.title }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ event.description.substring(0, 80) }}{{ event.description.length > 80 ? '...' : '' }}
                                    </div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span :class="getTypeColor(event.type)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ event.type }}
                                        </span>
                                        <span :class="getCategoryColor(event.category)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ event.category }}
                                        </span>
                                        <span v-if="event.is_recurring" class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Recurring
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <Calendar class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ formatDate(event.start_date) }}
                                    </div>
                                    <div v-if="event.start_date !== event.end_date" class="text-gray-500 dark:text-gray-400">
                                        to {{ formatDate(event.end_date) }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <Clock class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ event.start_time }} - {{ event.end_time }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center">
                                        <MapPin class="h-4 w-4 mr-1 text-gray-400" />
                                        {{ event.location }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ event.building }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ event.organizer }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ event.organizer_email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ event.current_participants }} / {{ event.max_participants }}
                                </div>
                                <div class="text-sm" :class="getParticipantColor(event.current_participants, event.max_participants)">
                                    {{ Math.round((event.current_participants / event.max_participants) * 100) }}% full
                                </div>
                                <div v-if="event.registration_required" class="text-xs text-gray-500 dark:text-gray-400">
                                    Registration required
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <span :class="getStatusColor(event.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ event.status }}
                                    </span>
                                    <span :class="getPriorityColor(event.priority)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ event.priority }} priority
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <Link 
                                        :href="`/admin/events/${event.id}`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                    >
                                        <Calendar class="h-4 w-4" />
                                    </Link>
                                    <Link 
                                        :href="`/admin/events/${event.id}/edit`"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                    <button
                                        @click="deleteEvent(event.id)"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        <Trash2 class="h-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div v-if="filteredEvents.length === 0" class="text-center py-12">
                <Calendar class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No events found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Try adjusting your search or filters
                </p>
            </div>
        </div>
    </div>
</template>
