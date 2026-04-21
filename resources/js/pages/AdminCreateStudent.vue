<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Eye, EyeOff, Shield, Mail, Lock, AlertCircle, CheckCircle, ArrowLeft, User, Phone, MapPin, Calendar, BookOpen } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout,
});

defineProps<{
    departments?: Array<{ id: number; name: string }>;
    courses?: Array<{ id: number; code: string; name: string; credits: number }>;
    sections?: Array<{ id: number; name: string; year_level: string }>;
}>();

// Form state
const showPassword = ref(false);
const processing = ref(false);
const selectedCourses = ref<number[]>([]);

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
    
    // Academic Information
    student_id: '',
    department_id: '',
    section_id: '',
    year_level: '',
    academic_status: 'active',
    
    // Account Information
    password: '',
    password_confirmation: '',
    
    // Additional Information
    courses: [],
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
    student_id: form.errors.student_id,
    department_id: form.errors.department_id,
    section_id: form.errors.section_id,
    year_level: form.errors.year_level,
    password: form.errors.password,
    password_confirmation: form.errors.password_confirmation,
}));

// Gender options
const genderOptions = [
    { value: 'male', label: 'Male' },
    { value: 'female', label: 'Female' },
    { value: 'other', label: 'Other' }
];

// Year level options
const yearLevelOptions = [
    { value: '1st', label: '1st Year' },
    { value: '2nd', label: '2nd Year' },
    { value: '3rd', label: '3rd Year' },
    { value: '4th', label: '4th Year' }
];

// Academic status options
const academicStatusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'graduated', label: 'Graduated' },
    { value: 'suspended', label: 'Suspended' }
];

// Submit handler
const submit = () => {
    processing.value = true;
    form.courses = selectedCourses.value;
    form.post('/admin/students/create', {
        onFinish: () => {
            processing.value = false;
        },
    });
};

// Toggle password visibility
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

// Get input type for password field
const passwordType = computed(() => showPassword.value ? 'text' : 'password');

// Course selection
const toggleCourse = (courseId: number) => {
    const index = selectedCourses.value.indexOf(courseId);
    if (index > -1) {
        selectedCourses.value.splice(index, 1);
    } else {
        selectedCourses.value.push(courseId);
    }
};

// Generate student ID
const generateStudentId = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    form.student_id = `STU${year}${random}`;
};
</script>

<template>
    <Head title="Add Student - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Student</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new student account and profile</p>
            </div>
            <Link href="/admin/students" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Students
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
                                    placeholder="student@example.com"
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
                                Birth Date *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Calendar class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="birth_date"
                                    v-model="form.birth_date"
                                    type="date"
                                    required
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
                                Gender *
                            </label>
                            <select
                                id="gender"
                                v-model="form.gender"
                                required
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
                                Address *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <MapPin class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    required
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

                <!-- Academic Information Section -->
                <div>
                    <div class="flex items-center mb-4">
                        <BookOpen class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Academic Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student ID -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Student ID *
                            </label>
                            <div class="flex space-x-2">
                                <input
                                    id="student_id"
                                    v-model="form.student_id"
                                    type="text"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.student_id ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Enter student ID"
                                />
                                <button
                                    type="button"
                                    @click="generateStudentId"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm"
                                >
                                    Generate
                                </button>
                            </div>
                            <p v-if="form.errors.student_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.student_id }}
                            </p>
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Department *
                            </label>
                            <select
                                id="department_id"
                                v-model="form.department_id"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.department_id ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Department</option>
                                <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                    {{ dept.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.department_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.department_id }}
                            </p>
                        </div>

                        <!-- Section -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Section *
                            </label>
                            <select
                                id="section_id"
                                v-model="form.section_id"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.section_id ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Section</option>
                                <option v-for="section in sections" :key="section.id" :value="section.id">
                                    {{ section.name }} ({{ section.year_level }})
                                </option>
                            </select>
                            <p v-if="form.errors.section_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.section_id }}
                            </p>
                        </div>

                        <!-- Year Level -->
                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Year Level *
                            </label>
                            <select
                                id="year_level"
                                v-model="form.year_level"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.year_level ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Year Level</option>
                                <option v-for="option in yearLevelOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.year_level" class="mt-1 text-sm text-red-600">
                                {{ form.errors.year_level }}
                            </p>
                        </div>

                        <!-- Academic Status -->
                        <div>
                            <label for="academic_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Academic Status *
                            </label>
                            <select
                                id="academic_status"
                                v-model="form.academic_status"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    form.errors.academic_status ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Status</option>
                                <option v-for="option in academicStatusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.academic_status" class="mt-1 text-sm text-red-600">
                                {{ form.errors.academic_status }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Account Information Section -->
                <div>
                    <div class="flex items-center mb-4">
                        <Shield class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Account Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
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

                        <!-- Password Confirmation -->
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
                                    :type="passwordType"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        form.errors.password_confirmation ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Confirm password"
                                />
                            </div>
                            <p v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-600">
                                {{ form.errors.password_confirmation }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Course Selection -->
                <div>
                    <div class="flex items-center mb-4">
                        <BookOpen class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Course Enrollment</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="course in courses" :key="course.id" class="border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <label class="flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    :value="course.id"
                                    :checked="selectedCourses.includes(course.id)"
                                    @change="toggleCourse(course.id)"
                                    class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                />
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ course.code }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ course.name }}</div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ course.credits }} credits</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <Link href="/admin/students" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
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
                            Creating Student...
                        </span>
                        <span v-else class="flex items-center">
                            <User class="w-5 h-5 mr-2" />
                            Create Student
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
