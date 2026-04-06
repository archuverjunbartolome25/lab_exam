<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
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
        title: 'Create Faculty',
        href: '/faculty/create',
    },
];

const form = useForm({
    faculty_id: '',
    first_name: '',
    last_name: '',
    middle_name: '',
    email: '',
    phone: '',
    birth_date: '',
    gender: '',
    address: '',
    city: '',
    province: '',
    postal_code: '',
    employment_type: '',
    department: '',
    position: '',
    specialization: '',
    hire_date: '',
    status: 'Active',
    salary: '',
    education_background: '',
    certifications: [],
    bio: '',
});

const submit = () => {
    form.post('/api/faculty', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Create Faculty" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <Link
                        href="/faculty"
                        class="mr-4 p-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        <ArrowLeft class="w-5 h-5" />
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Faculty</h1>
                        <p class="text-gray-600 dark:text-gray-400">Add a new faculty member to the system</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Faculty ID *
                                </label>
                                <input
                                    v-model="form.faculty_id"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="e.g., FAC-2024-001"
                                />
                                <div v-if="form.errors.faculty_id" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.faculty_id }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    First Name *
                                </label>
                                <input
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.first_name" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.first_name }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Last Name *
                                </label>
                                <input
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.last_name" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.last_name }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Middle Name
                                </label>
                                <input
                                    v-model="form.middle_name"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email *
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Phone
                                </label>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Birth Date *
                                </label>
                                <input
                                    v-model="form.birth_date"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.birth_date" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.birth_date }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Gender *
                                </label>
                                <select
                                    v-model="form.gender"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div v-if="form.errors.gender" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.gender }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Address Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Address *
                                </label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.address" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.address }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    City *
                                </label>
                                <input
                                    v-model="form.city"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.city" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.city }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Province *
                                </label>
                                <input
                                    v-model="form.province"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.province" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.province }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Postal Code *
                                </label>
                                <input
                                    v-model="form.postal_code"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.postal_code" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.postal_code }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Employment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Employment Type *
                                </label>
                                <select
                                    v-model="form.employment_type"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                >
                                    <option value="">Select Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Adjunct">Adjunct</option>
                                </select>
                                <div v-if="form.errors.employment_type" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.employment_type }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Department *
                                </label>
                                <select
                                    v-model="form.department"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                >
                                    <option value="">Select Department</option>
                                    <option value="Computer Science">Computer Science</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Information Systems">Information Systems</option>
                                    <option value="Mathematics">Mathematics</option>
                                </select>
                                <div v-if="form.errors.department" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.department }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Position *
                                </label>
                                <input
                                    v-model="form.position"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="e.g., Professor, Assistant Professor"
                                />
                                <div v-if="form.errors.position" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.position }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Specialization
                                </label>
                                <input
                                    v-model="form.specialization"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="e.g., Web Development, Database Systems"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Hire Date *
                                </label>
                                <input
                                    v-model="form.hire_date"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <div v-if="form.errors.hire_date" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.hire_date }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Status *
                                </label>
                                <select
                                    v-model="form.status"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                >
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="On Leave">On Leave</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.status }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Salary
                                </label>
                                <input
                                    v-model="form.salary"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="0.00"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Additional Information</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Education Background
                                </label>
                                <textarea
                                    v-model="form.education_background"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="e.g., PhD in Computer Science from XYZ University"
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Biography
                                </label>
                                <textarea
                                    v-model="form.bio"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    placeholder="Brief professional biography..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4">
                        <Link
                            href="/faculty"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                        >
                            <Save class="w-4 h-4 mr-2" />
                            {{ form.processing ? 'Saving...' : 'Save Faculty' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
