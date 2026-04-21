<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Eye, EyeOff, Shield, Mail, Lock, AlertCircle, CheckCircle, ArrowLeft, User, Phone, MapPin, Calendar, BookOpen, Users } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Props from controller
const props = defineProps<{
    user: {
        id: number;
        user_num: string;
        fname: string;
        lname: string;
        email: string;
        phone?: string;
        address?: string;
        birth_date?: string;
        gender?: string;
        role: string;
        status: string;
        created_at: string;
        updated_at: string;
    };
}>();

// Form data - pre-populate with existing user data
const form = useForm({
    // Personal Information
    first_name: props.user.fname,
    last_name: props.user.lname,
    email: props.user.email,
    phone: props.user.phone || '',
    address: props.user.address || '',
    birth_date: props.user.birth_date || '',
    gender: props.user.gender || '',
    
    // User Account Information
    role: props.user.role,
    status: props.user.status,
});

// Reactive state
const processing = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const showConfirmModal = ref(false);

// Form options
const genderOptions = [
    { value: 'male', label: 'Male' },
    { value: 'female', label: 'Female' },
    { value: 'other', label: 'Other' }
];

const roleOptions = [
    { value: 'admin', label: 'Administrator' },
    { value: 'faculty', label: 'Faculty Member' },
    { value: 'student', label: 'Student' }
];

const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'suspended', label: 'Suspended' }
];

// Validation errors
const errors = computed(() => ({
    first_name: form.errors.first_name,
    last_name: form.errors.last_name,
    email: form.errors.email,
    phone: form.errors.phone,
    address: form.errors.address,
    birth_date: form.errors.birth_date,
    gender: form.errors.gender,
    role: form.errors.role,
    status: form.errors.status,
}));

// Get role description
const getRoleDescription = (role: string) => {
    switch(role) {
        case 'admin': return 'Full system access with administrative privileges';
        case 'faculty': return 'Can manage courses, grades, and student records';
        case 'student': return 'Can view courses, grades, and personal information';
        default: return '';
    }
};

// Get role color
const getRoleColor = (role: string) => {
    switch(role) {
        case 'admin': return 'text-red-600';
        case 'faculty': return 'text-blue-600';
        case 'student': return 'text-green-600';
        default: return 'text-gray-600';
    }
};

// Computed properties for role-based display
const isAdmin = computed(() => form.role === 'admin');
const isFaculty = computed(() => form.role === 'faculty');
const isStudent = computed(() => form.role === 'student');
const roleDescription = computed(() => getRoleDescription(form.role));
const roleColor = computed(() => getRoleColor(form.role));

// Form submission
const submit = () => {
    showConfirmModal.value = true;
};

const confirmUpdate = () => {
    processing.value = true;
    
    form.put(`/admin/users/${props.user.id}`, {
        onFinish: () => {
            processing.value = false;
        },
        onSuccess: () => {
            showConfirmModal.value = false;
            // Redirect back to users list
        },
        onError: () => {
            processing.value = false;
            showConfirmModal.value = false;
        }
    });
};

const closeConfirmModal = () => {
    showConfirmModal.value = false;
};

// Get input type for password fields
const passwordInputType = computed(() => showPassword.value ? 'text' : 'password');
const confirmPasswordInputType = computed(() => showConfirmPassword.value ? 'text' : 'password');

// Toggle password visibility
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPassword = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};
</script>

<template>
    <Head title="Edit User - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Update user information and permissions</p>
            </div>
            <Link href="/admin/users" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Users
            </Link>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <form @submit.prevent="submit" class="p-6 space-y-8">
                <!-- Personal Information Section -->
                <div>
                    <div class="flex items-center mb-4">
                        <User class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.first_name"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.first_name }"
                            />
                            <p v-if="errors.first_name" class="text-red-500 text-sm mt-1">{{ errors.first_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.last_name"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.last_name }"
                            />
                            <p v-if="errors.last_name" class="text-red-500 text-sm mt-1">{{ errors.last_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.email }"
                            />
                            <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number
                            </label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.phone }"
                            />
                            <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address
                            </label>
                            <input
                                v-model="form.address"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.address }"
                            />
                            <p v-if="errors.address" class="text-red-500 text-sm mt-1">{{ errors.address }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Birth Date
                            </label>
                            <input
                                v-model="form.birth_date"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.birth_date }"
                            />
                            <p v-if="errors.birth_date" class="text-red-500 text-sm mt-1">{{ errors.birth_date }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gender
                            </label>
                            <select
                                v-model="form.gender"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.gender }"
                            >
                                <option value="">Select Gender</option>
                                <option v-for="option in genderOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="errors.gender" class="text-red-500 text-sm mt-1">{{ errors.gender }}</p>
                        </div>
                    </div>
                </div>

                <!-- Role and Status Section -->
                <div>
                    <div class="flex items-center mb-4">
                        <Shield class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Role and Status</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                User Role <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.role"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.role }"
                            >
                                <option value="">Select Role</option>
                                <option v-for="option in roleOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="errors.role" class="text-red-500 text-sm mt-1">{{ errors.role }}</p>
                            <p v-if="form.role" class="text-sm text-gray-500 mt-1">{{ roleDescription }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Account Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                :class="{ 'border-red-500': errors.status }"
                            >
                                <option value="">Select Status</option>
                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="errors.status" class="text-red-500 text-sm mt-1">{{ errors.status }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <Link
                        href="/admin/users"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        :class="[
                            'px-6 py-2 rounded-lg text-white font-medium transition-colors',
                            processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600'
                        ]"
                    >
                        <span v-if="processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating User...
                        </span>
                        <span v-else class="flex items-center">
                            <Users class="w-5 h-5 mr-2" />
                            Update User
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 transition-opacity z-40" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl shadow-black/20 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <CheckCircle class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Update User
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to update this user's information?
                                </p>
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900">{{ form.first_name }} {{ form.last_name }}</p>
                                    <p class="text-sm text-gray-500">{{ form.email }}</p>
                                    <p class="text-sm text-gray-500">Role: {{ form.role }}</p>
                                    <p class="text-sm text-gray-500">Status: {{ form.status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        @click="confirmUpdate"
                        :disabled="processing"
                        :class="[
                            'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm',
                            processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'
                        ]"
                    >
                        <span v-if="processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating...
                        </span>
                        <span v-else>Update User</span>
                    </button>
                    <button
                        type="button"
                        @click="closeConfirmModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
