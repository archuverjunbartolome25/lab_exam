<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft, Save } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Faculty',
        href: '/faculty',
    },
    {
        title: 'Students',
        href: '/faculty/students',
    },
    {
        title: 'Create Student',
        href: '/faculty/students/create',
    },
];

const props = defineProps<{
    sectionsIT: any[];
    sectionsCS: any[];
    coursesIT: any[];
    coursesCS: any[];
}>();

const form = useForm({
    student_id: '',
    first_name: '',
    last_name: '',
    middle_name: '',
    ext: '',
    email: '',
    phone: '',
    birth_date: '',
    gender: '',
    address: '',
    program: '',
    program_code: '',
    section_id: '',
    courses: [],
    standing: '',
    academic_status: 'Regular',
});

const submit = () => {
    form.post('/faculty/students/create', {
        onSuccess: () => {
            form.reset();
        },
    });
};

const getSectionsByProgram = () => {
    if (form.program === 'BSIT') {
        return props.sectionsIT;
    } else if (form.program === 'BSCS') {
        return props.sectionsCS;
    }
    return [];
};

const getCoursesByProgram = () => {
    if (form.program === 'BSIT') {
        return props.coursesIT;
    } else if (form.program === 'BSCS') {
        return props.coursesCS;
    }
    return [];
};
</script>

<template>
    <Head title="Create Student" />

    <FacultyLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create Student</h1>
                        <p class="mt-2 text-gray-600">Add a new student to the system</p>
                    </div>
                    <Link
                        href="/faculty/students"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                    >
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Students
                    </Link>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <form @submit.prevent="submit" class="space-y-6 p-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                                <input
                                    id="student_id"
                                    v-model="form.student_id"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.student_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.student_id }}
                                </div>
                            </div>

                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input
                                    id="first_name"
                                    v-model="form.first_name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.first_name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.first_name }}
                                </div>
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input
                                    id="last_name"
                                    v-model="form.last_name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.last_name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.last_name }}
                                </div>
                            </div>

                            <div>
                                <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                                <input
                                    id="middle_name"
                                    v-model="form.middle_name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label for="ext" class="block text-sm font-medium text-gray-700">Extension (Jr., Sr., etc.)</label>
                                <input
                                    id="ext"
                                    v-model="form.ext"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Jr., Sr., III, etc."
                                />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                                <input
                                    id="birth_date"
                                    v-model="form.birth_date"
                                    type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.birth_date" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.birth_date }}
                                </div>
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div v-if="form.errors.gender" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.gender }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea
                                    id="address"
                                    v-model="form.address"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                ></textarea>
                                <div v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.address }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="program" class="block text-sm font-medium text-gray-700">Program</label>
                                <select
                                    id="program"
                                    v-model="form.program"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                    @change="form.program_code = form.program"
                                >
                                    <option value="">Select Program</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                </select>
                                <div v-if="form.errors.program" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.program }}
                                </div>
                            </div>

                            <div>
                                <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
                                <select
                                    id="section_id"
                                    v-model="form.section_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Section</option>
                                    <option v-for="section in getSectionsByProgram()" :key="section.section_id" :value="section.section_id">
                                        {{ section.section }}
                                    </option>
                                </select>
                                <div v-if="form.errors.section_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.section_id }}
                                </div>
                            </div>

                            <div>
                                <label for="standing" class="block text-sm font-medium text-gray-700">Year Standing</label>
                                <select
                                    id="standing"
                                    v-model="form.standing"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Year Standing</option>
                                    <option value="1st">1st Year</option>
                                    <option value="2nd">2nd Year</option>
                                    <option value="3rd">3rd Year</option>
                                    <option value="4th">4th Year</option>
                                </select>
                                <div v-if="form.errors.standing" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.standing }}
                                </div>
                            </div>

                            <div>
                                <label for="academic_status" class="block text-sm font-medium text-gray-700">Academic Status</label>
                                <select
                                    id="academic_status"
                                    v-model="form.academic_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="Regular">Regular</option>
                                    <option value="Irregular">Irregular</option>
                                </select>
                                <div v-if="form.errors.academic_status" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.academic_status }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <Link
                            href="/faculty/students"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :disabled="form.processing"
                        >
                            <Save class="w-4 h-4 mr-2" />
                            Create Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </FacultyLayout>
</template>
