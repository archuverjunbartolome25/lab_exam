<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

const page = usePage();
const isAdmin = computed(() => (page.props as any).isAdmin);
const Layout = computed(() => isAdmin.value ? AdminLayout : FacultyLayout);
import { Search, Plus, Edit, Trash2, FileText, TrendingUp, Users } from 'lucide-vue-next';

const grades = ref([
    { id: 1, studentName: 'John Smith', studentId: '2021001', course: 'CCS101', section: 'A', midterm: 85, final: 92, average: 88.5, status: 'Passed' },
    { id: 2, studentName: 'Jane Doe', studentId: '2021002', course: 'CCS101', section: 'A', midterm: 78, final: 85, average: 81.5, status: 'Passed' },
    { id: 3, studentName: 'Mike Johnson', studentId: '2021003', course: 'CCS101', section: 'A', midterm: 92, final: 88, average: 90.0, status: 'Passed' },
    { id: 4, studentName: 'Sarah Wilson', studentId: '2021004', course: 'CCS102', section: 'B', midterm: 88, final: 91, average: 89.5, status: 'Passed' },
    { id: 5, studentName: 'Tom Brown', studentId: '2021005', course: 'CCS102', section: 'B', midterm: 75, final: 82, average: 78.5, status: 'Passed' }
]);

const searchQuery = ref('');
const selectedCourse = ref('all');

const filteredGrades = ref(grades.value);

const getStatusColor = (status) => {
    return status === 'Passed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
};

const getGradeColor = (grade) => {
    if (grade >= 90) return 'text-green-600 font-bold';
    if (grade >= 80) return 'text-orange-600 font-semibold';
    if (grade >= 70) return 'text-yellow-600';
    return 'text-red-600';
};
</script>

<template>
    <component :is="Layout">
        <Head title="Faculty Grades" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">GRADES</h1>
                <p class="text-gray-600 mt-2">Manage and view student grades</p>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search by student name or ID..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        />
                    </div>
                    <select 
                        v-model="selectedCourse"
                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    >
                        <option value="">Select Course</option>
                        <option value="CCS101">CCS101 - Introduction to Computer Science</option>
                        <option value="CCS201">CCS201 - Data Structures</option>
                    </select>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <Users class="h-6 w-6 text-orange-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Students</p>
                            <p class="text-2xl font-bold text-gray-900">{{ grades.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <TrendingUp class="h-6 w-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Average Grade</p>
                            <p class="text-2xl font-bold text-green-600">85.6</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <FileText class="h-6 w-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Passed</p>
                            <p class="text-2xl font-bold text-blue-600">{{ grades.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <Edit class="h-6 w-6 text-red-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Failed</p>
                            <p class="text-2xl font-bold text-red-600">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grades Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Student Grades</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Midterm</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="grade in filteredGrades" :key="grade.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                            <span class="text-sm font-semibold text-orange-600">{{ grade.studentName.charAt(0) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ grade.studentName }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ grade.studentId }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ grade.course }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ grade.section }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="getGradeColor(grade.midterm)">{{ grade.midterm }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="getGradeColor(grade.final)">{{ grade.final }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="getGradeColor(grade.average)">{{ grade.average.toFixed(1) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusColor(grade.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ grade.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Actions removed -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </component>
</template>
