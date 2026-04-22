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
const form = ref({
    // Personal Information
    fname: '',
    mname: '',
    lname: '',
    ext: '',
    gender: '',
    bday: '',
    email: '',
    contact_num: '',
    address: '',
    
    // Academic Information
    stud_num: '',
    guardian: '',
    program: '',
    program_code: '',
    section_id: '',
    course: '',
    achievements: '',
    skills: '',
    affiliations: '',
    violations: '',
    medicalRecord: '',
    standing: '',
    academic_status: 'Enrolled',
});

// Validation errors
const errors = ref<{ [key: string]: string }>({
    fname: '',
    mname: '',
    lname: '',
    ext: '',
    gender: '',
    bday: '',
    email: '',
    contact_num: '',
    address: '',
    stud_num: '',
    guardian: '',
    program: '',
    program_code: '',
    section_id: '',
    course: '',
    achievements: '',
    skills: '',
    affiliations: '',
    violations: '',
    medicalRecord: '',
    standing: '',
    academic_status: '',
});

// Gender options
const genderOptions = [
    { value: 'Male', label: 'Male' },
    { value: 'Female', label: 'Female' },
    { value: 'Other', label: 'Other' }
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
const submit = async () => {
    processing.value = true;
    
    // Clear previous errors
    Object.keys(errors.value).forEach(key => {
        errors.value[key] = '';
    });
    
    try {
        // Debug: Log the form data being sent
        console.log('Form data being sent:', form.value);
        
        const response = await fetch('/api/admin/students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(form.value)
        });
        
        const data = await response.json();
        
        // Debug: Log the response
        console.log('API Response:', response.status, data);
        
        if (response.ok) {
            // Success - redirect to students list
            alert('Student created successfully!');
            window.location.href = '/admin/students';
        } else {
            // Handle validation errors
            if (data.errors) {
                console.log('Validation errors:', data.errors);
                Object.keys(data.errors).forEach(key => {
                    if (errors.value.hasOwnProperty(key)) {
                        errors.value[key] = data.errors[key][0];
                    }
                });
            } else if (data.message) {
                alert('Error: ' + data.message);
            } else {
                alert('Error creating student. Please try again.');
            }
        }
    } catch (error) {
        console.error('Error creating student:', error);
        alert('Network error. Please check your connection and try again.');
    } finally {
        processing.value = false;
    }
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
    form.value.stud_num = `STU${year}${random}`;
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
                            <label for="fname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                First Name *
                            </label>
                            <input
                                id="fname"
                                v-model="form.fname"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.fname ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter first name"
                            />
                            <p v-if="errors.fname" class="mt-1 text-sm text-red-600">
                                {{ errors.fname }}
                            </p>
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="mname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Middle Name
                            </label>
                            <input
                                id="mname"
                                v-model="form.mname"
                                type="text"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.mname ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter middle name"
                            />
                            <p v-if="errors.mname" class="mt-1 text-sm text-red-600">
                                {{ errors.mname }}
                            </p>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="lname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Last Name *
                            </label>
                            <input
                                id="lname"
                                v-model="form.lname"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.lname ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter last name"
                            />
                            <p v-if="errors.lname" class="mt-1 text-sm text-red-600">
                                {{ errors.lname }}
                            </p>
                        </div>

                        <!-- Extension -->
                        <div>
                            <label for="ext" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Extension (Jr., Sr., etc.)
                            </label>
                            <input
                                id="ext"
                                v-model="form.ext"
                                type="text"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.ext ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Jr., Sr., III, etc."
                            />
                            <p v-if="errors.ext" class="mt-1 text-sm text-red-600">
                                {{ errors.ext }}
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
                                        errors.email ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="student@example.com"
                                />
                            </div>
                            <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                                {{ errors.email }}
                            </p>
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <label for="contact_num" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Contact Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Phone class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="contact_num"
                                    v-model="form.contact_num"
                                    type="tel"
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        errors.contact_num ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="+63 912-345-6789"
                                />
                            </div>
                            <p v-if="errors.contact_num" class="mt-1 text-sm text-red-600">
                                {{ errors.contact_num }}
                            </p>
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="bday" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Birth Date *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Calendar class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="bday"
                                    v-model="form.bday"
                                    type="date"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        errors.bday ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                />
                            </div>
                            <p v-if="errors.bday" class="mt-1 text-sm text-red-600">
                                {{ errors.bday }}
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
                                    errors.gender ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Gender</option>
                                <option v-for="option in genderOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="errors.gender" class="mt-1 text-sm text-red-600">
                                {{ errors.gender }}
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
                                        errors.address ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Enter full address"
                                />
                            </div>
                            <p v-if="errors.address" class="mt-1 text-sm text-red-600">
                                {{ errors.address }}
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
                        <!-- Student Number -->
                        <div>
                            <label for="stud_num" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Student Number *
                            </label>
                            <div class="flex space-x-2">
                                <input
                                    id="stud_num"
                                    v-model="form.stud_num"
                                    type="text"
                                    required
                                    :class="[
                                        'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                        errors.stud_num ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                    ]"
                                    placeholder="Enter student number"
                                />
                                <button
                                    type="button"
                                    @click="generateStudentId"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm"
                                >
                                    Generate
                                </button>
                            </div>
                            <p v-if="errors.stud_num" class="mt-1 text-sm text-red-600">
                                {{ errors.stud_num }}
                            </p>
                        </div>

                        <!-- Guardian -->
                        <div>
                            <label for="guardian" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Guardian Name *
                            </label>
                            <input
                                id="guardian"
                                v-model="form.guardian"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.guardian ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter guardian name"
                            />
                            <p v-if="errors.guardian" class="mt-1 text-sm text-red-600">
                                {{ errors.guardian }}
                            </p>
                        </div>

                        <!-- Program -->
                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Program *
                            </label>
                            <select
                                id="program"
                                v-model="form.program"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.program ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Program</option>
                                <option value="BSIT">Bachelor of Science in Information Technology</option>
                                <option value="BSCS">Bachelor of Science in Computer Science</option>
                            </select>
                            <p v-if="errors.program" class="mt-1 text-sm text-red-600">
                                {{ errors.program }}
                            </p>
                        </div>

                        <!-- Program Code -->
                        <div>
                            <label for="program_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Program Code *
                            </label>
                            <input
                                id="program_code"
                                v-model="form.program_code"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.program_code ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="BSIT or BSCS"
                            />
                            <p v-if="errors.program_code" class="mt-1 text-sm text-red-600">
                                {{ errors.program_code }}
                            </p>
                        </div>

                        <!-- Section ID -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Section ID *
                            </label>
                            <input
                                id="section_id"
                                v-model="form.section_id"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.section_id ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter section ID"
                            />
                            <p v-if="errors.section_id" class="mt-1 text-sm text-red-600">
                                {{ errors.section_id }}
                            </p>
                        </div>

                        <!-- Course -->
                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Course *
                            </label>
                            <input
                                id="course"
                                v-model="form.course"
                                type="text"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.course ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter course"
                            />
                            <p v-if="errors.course" class="mt-1 text-sm text-red-600">
                                {{ errors.course }}
                            </p>
                        </div>

                        <!-- Standing -->
                        <div>
                            <label for="standing" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Academic Standing *
                            </label>
                            <select
                                id="standing"
                                v-model="form.standing"
                                required
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.standing ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Standing</option>
                                <option v-for="option in yearLevelOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="errors.standing" class="mt-1 text-sm text-red-600">
                                {{ errors.standing }}
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
                                    errors.academic_status ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                            >
                                <option value="">Select Status</option>
                                <option value="Enrolled">Enrolled</option>
                                <option value="Not Enrolled">Not Enrolled</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Graduated">Graduated</option>
                                <option value="Dropped">Dropped</option>
                            </select>
                            <p v-if="errors.academic_status" class="mt-1 text-sm text-red-600">
                                {{ errors.academic_status }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div>
                    <div class="flex items-center mb-4">
                        <BookOpen class="h-5 w-5 text-orange-500 mr-2" />
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Additional Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Achievements -->
                        <div class="md:col-span-2">
                            <label for="achievements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Achievements
                            </label>
                            <textarea
                                id="achievements"
                                v-model="form.achievements"
                                rows="3"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.achievements ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter student achievements"
                            ></textarea>
                            <p v-if="errors.achievements" class="mt-1 text-sm text-red-600">
                                {{ errors.achievements }}
                            </p>
                        </div>

                        <!-- Skills -->
                        <div class="md:col-span-2">
                            <label for="skills" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Skills
                            </label>
                            <textarea
                                id="skills"
                                v-model="form.skills"
                                rows="3"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.skills ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter student skills"
                            ></textarea>
                            <p v-if="errors.skills" class="mt-1 text-sm text-red-600">
                                {{ errors.skills }}
                            </p>
                        </div>

                        <!-- Affiliations -->
                        <div class="md:col-span-2">
                            <label for="affiliations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Affiliations
                            </label>
                            <textarea
                                id="affiliations"
                                v-model="form.affiliations"
                                rows="3"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.affiliations ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter student affiliations"
                            ></textarea>
                            <p v-if="errors.affiliations" class="mt-1 text-sm text-red-600">
                                {{ errors.affiliations }}
                            </p>
                        </div>

                        <!-- Violations -->
                        <div class="md:col-span-2">
                            <label for="violations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Violations
                            </label>
                            <textarea
                                id="violations"
                                v-model="form.violations"
                                rows="3"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.violations ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter student violations"
                            ></textarea>
                            <p v-if="errors.violations" class="mt-1 text-sm text-red-600">
                                {{ errors.violations }}
                            </p>
                        </div>

                        <!-- Medical Record -->
                        <div class="md:col-span-2">
                            <label for="medicalRecord" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Medical Record
                            </label>
                            <textarea
                                id="medicalRecord"
                                v-model="form.medicalRecord"
                                rows="3"
                                :class="[
                                    'appearance-none relative block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent',
                                    errors.medicalRecord ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-orange-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white'
                                ]"
                                placeholder="Enter medical record information"
                            ></textarea>
                            <p v-if="errors.medicalRecord" class="mt-1 text-sm text-red-600">
                                {{ errors.medicalRecord }}
                            </p>
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
