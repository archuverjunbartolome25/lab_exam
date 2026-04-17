<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Users, Mail, Phone, MoreVertical } from 'lucide-vue-next';

// Props interface
interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    course: string;
    year: string;
    status: string;
    enrolledDate: string;
}

// Define props with TypeScript interface
const props = defineProps<{
    user: User;
    onEdit?: (user: User) => void;
    onDelete?: (user: User) => void;
}>();

// Computed properties
const userInitials = computed(() => {
    return props.user.name.charAt(0).toUpperCase();
});

const formattedUserId = computed(() => {
    return props.user.id.toString().padStart(6, '0');
});

const statusColor = computed(() => {
    return props.user.status === 'active' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
});

// Event handlers
const handleEdit = () => {
    if (props.onEdit) {
        props.onEdit(props.user);
    }
};

const handleDelete = () => {
    if (props.onDelete) {
        props.onDelete(props.user);
    }
};

const handleMoreOptions = () => {
    console.log('More options for user:', props.user.id);
};
</script>

<template>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <!-- User Info -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-600">{{ userInitials }}</span>
                    </div>
                </div>
                <div class="ml-4">
                    <Link 
                        :href="`/faculty/users/${user.id}`" 
                        class="text-sm font-medium text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300"
                    >
                        {{ user.name }}
                    </Link>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        ID: {{ formattedUserId }}
                    </div>
                </div>
            </div>
        </td>

        <!-- Contact Info -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-white flex items-center mb-1">
                <Mail class="h-4 w-4 mr-1 text-gray-400" />
                {{ user.email }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                <Phone class="h-4 w-4 mr-1 text-gray-400" />
                {{ user.phone }}
            </div>
        </td>

        <!-- Course & Year -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-white">{{ user.course }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ user.year }}</div>
        </td>

        <!-- Status -->
        <td class="px-6 py-4 whitespace-nowrap">
            <span :class="statusColor" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ user.status }}
            </span>
        </td>

        <!-- Enrolled Date -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            {{ user.enrolledDate }}
        </td>

        <!-- Actions -->
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button 
                @click="handleEdit"
                class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300 mr-3"
            >
                Edit
            </button>
            <button 
                @click="handleMoreOptions"
                class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
            >
                <MoreVertical class="h-4 w-4" />
            </button>
        </td>
    </tr>
</template>
