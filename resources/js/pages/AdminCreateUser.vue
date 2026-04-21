<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Eye, EyeOff, Shield, Mail, Lock, AlertCircle, CheckCircle, ArrowLeft, User, Phone, MapPin, Calendar, BookOpen, Users } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

// Reactive state
const processing = ref(false);
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const showConfirmModal = ref(false);

// Form data
const form = useForm({
    // Personal Information
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    address: '',
    birth_date: '',
    gender: '',
    
    // User Account Information
    password: '',
    password_confirmation: '',
    
    // Role and Permissions
    role: 'user',
    status: 'active'
});

// Validation errors
const errors = computed(() => ({
    first_name: form.errors.first_name,
    last_name: form.errors.last_name,
    email: form.errors.email,
    phone: form.errors.phone,
    address: form.errors.address,
    birth_date: form.errors.birth_date,
    gender: form.errors.gender,
    password: form.errors.password,
    password_confirmation: form.errors.password_confirmation,
    role: form.errors.role,
    status: form.errors.status
}));

// Form options
const genderOptions = [
    { value: 'male', label: 'Male' },
    { value: 'female', label: 'Female' },
    { value: 'other', label: 'Other' }
];

const roleOptions = [
    { value: '', label: 'Select Role' },
    { value: 'admin', label: 'Administrator' },
    { value: 'faculty', label: 'Faculty Member' },
    { value: 'student', label: 'Student' }
];

const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'suspended', label: 'Suspended' }
];

const departmentOptions = [
    { value: '', label: 'Select Department' },
    { value: 'Computer Science', label: 'Computer Science' },
    { value: 'Mathematics', label: 'Mathematics' },
    { value: 'Physics', label: 'Physics' },
    { value: 'Engineering', label: 'Engineering' },
    { value: 'Business', label: 'Business Administration' },
    { value: 'Arts', label: 'Arts & Sciences' }
];

// Form submission
const submit = () => {
    // Show confirmation modal instead of direct submission
    showConfirmModal.value = true;
};

// Confirm and submit form
const confirmSubmit = () => {
    processing.value = true;
    showConfirmModal.value = false;
    
    form.post('/admin/users/create', {
        onFinish: () => {
            processing.value = false;
        },
        onSuccess: () => {
            // Reset form or redirect
        },
        onError: () => {
            processing.value = false;
        }
    });
};

// Cancel confirmation
const cancelSubmit = () => {
    showConfirmModal.value = false;
};

// Toggle password visibility
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPassword = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};

// Get input type for password fields
const passwordType = computed(() => showPassword.value ? 'text' : 'password');
const confirmPasswordType = computed(() => showConfirmPassword.value ? 'text' : 'password');

// Generate name from first and last name (if needed for future use)
const generateDisplayName = () => {
    // This function can be used if needed for display purposes
    return form.name;
};

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
</script>

<template>
    <Head title="Add User - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New User</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new user account with appropriate permissions</p>
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
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                First Name *
                            </label>
                            <input
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.first_name ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter first name"
                            />
                            <p v-if="form.errors.first_name" class="mt-1 text-sm text-red-600">
                                {{ form.errors.first_name }}
                            </p>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Last Name *
                            </label>
                            <input
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.last_name ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter last name"
                            />
                            <p v-if="form.errors.last_name" class="mt-1 text-sm text-red-600">
                                {{ form.errors.last_name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email Address *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Mail class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.email ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="user@example.com"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Phone class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.phone ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="+1 234-567-8900"
                                />
                            </div>
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Birth Date
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Calendar class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="birth_date"
                                    v-model="form.birth_date"
                                    type="date"
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.birth_date ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                />
                            </div>
                            <p v-if="form.errors.birth_date" class="mt-1 text-sm text-red-600">
                                {{ form.errors.birth_date }}
                            </p>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gender
                            </label>
                            <select
                                id="gender"
                                v-model="form.gender"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.gender ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Gender</option>
                                <option v-for="option in genderOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">
                                {{ form.errors.gender }}
                            </p>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <MapPin class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.address ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Enter full address"
                                />
                            </div>
                            <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                                {{ form.errors.address }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Role Selection Section -->
                <div>
                    <div class="flex items-center mb-4">
                        <Shield class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Role Selection</h2>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                v-for="option in roleOptions.filter(r => r.value !== '')"
                                :key="option.value"
                                @click="form.role = option.value"
                                :class="[
                                    'p-4 rounded-lg border-2 cursor-pointer transition-all',
                                    form.role === option.value
                                        ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20'
                                        : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'
                                ]"
                            >
                                <div class="text-center">
                                    <div class="text-lg font-semibold mb-1">{{ option.label }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ getRoleDescription(option.value) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" v-model="form.role" required />
                        <p v-if="form.errors.role" class="mt-2 text-sm text-red-600">
                            {{ form.errors.role }}
                        </p>
                    </div>
                </div>

                <!-- Account Information Section (Role-based) -->
                <div v-if="form.role">
                    <div class="flex items-center mb-4">
                        <Shield class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ form.role.charAt(0).toUpperCase() + form.role.slice(1) }} Account Information
                        </h2>
                        <span :class="roleColor" class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-opacity-10">
                            {{ form.role.toUpperCase() }}
                        </span>
                    </div>
                    
                    <!-- User Account Fields (Unified for all roles) -->
                    <div :class="[
                        'rounded-lg border p-6',
                        isAdmin ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' :
                        isFaculty ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' :
                        'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800'
                    ]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <h3 :class="[
                                    'text-lg font-semibold mb-4',
                                    isAdmin ? 'text-red-900 dark:text-red-100' :
                                    isFaculty ? 'text-blue-900 dark:text-blue-100' :
                                    'text-green-900 dark:text-green-100'
                                ]">
                                    {{ form.role.charAt(0).toUpperCase() + form.role.slice(1) }} User Details
                                </h3>
                                <p :class="[
                                    'text-sm mb-4',
                                    isAdmin ? 'text-red-700 dark:text-red-300' :
                                    isFaculty ? 'text-blue-700 dark:text-blue-300' :
                                    'text-green-700 dark:text-green-300'
                                ]">
                                    Creating a {{ form.role }} user for the users table with the following structure:
                                </p>
                                <div class="bg-white dark:bg-gray-800 rounded p-3 text-sm font-mono">
                                    id | user_num | fname | lname | email | password | role | status
                                </div>
                            </div>
                            
                            <!-- Role-specific user number format -->
                            <div class="md:col-span-2">
                                <label :class="[
                                    'block text-sm font-medium mb-2',
                                    isAdmin ? 'text-red-900 dark:text-red-100' :
                                    isFaculty ? 'text-blue-900 dark:text-blue-100' :
                                    'text-green-900 dark:text-green-100'
                                ]">
                                    {{ form.role.charAt(0).toUpperCase() + form.role.slice(1) }} Number (Auto-generated)
                                </label>
                                <div class="bg-white dark:bg-gray-800 rounded p-3 text-sm">
                                    <span v-if="isAdmin">Will be generated as ADMIN### format</span>
                                    <span v-else-if="isFaculty">Will be generated as FAC### format</span>
                                    <span v-else>Will be generated as STU#### format</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Section (Common for both roles) -->
                <div v-if="form.role">
                    <div class="flex items-center mb-4">
                        <Lock class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Password Setup</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Password *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Lock class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="passwordType"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.password ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Enter password"
                                />
                                <button
                                    type="button"
                                    @click="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <Eye v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                    <EyeOff v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Confirm Password *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Lock class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    :type="confirmPasswordType"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.password_confirmation ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Confirm password"
                                />
                                <button
                                    type="button"
                                    @click="toggleConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <Eye v-if="!showConfirmPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                    <EyeOff v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                </button>
                            </div>
                            <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                {{ form.errors.password_confirmation }}
                            </p>
                        </div>

                        </div>
                </div>

                
                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <Link href="/admin/users" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
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
                            Creating User...
                        </span>
                        <span v-else class="flex items-center">
                            <Users class="w-5 h-5 mr-2" />
                            Create User
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="cancelSubmit"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <AlertCircle class="h-6 w-6 text-orange-600" />
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Confirm User Creation
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to create this user? Please review the information below:
                                </p>
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg text-sm">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><strong>Name:</strong> {{ form.first_name }} {{ form.last_name }}</div>
                                        <div><strong>Email:</strong> {{ form.email }}</div>
                                        <div><strong>Role:</strong> {{ form.role }}</div>
                                        <div><strong>Status:</strong> {{ form.status }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        @click="confirmSubmit"
                        :disabled="processing"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="processing" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating...
                        </span>
                        <span v-else>Yes, Create User</span>
                    </button>
                    <button
                        type="button"
                        @click="cancelSubmit"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
