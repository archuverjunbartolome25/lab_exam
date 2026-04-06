<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { Search, Filter, Users, Award, Briefcase } from 'lucide-vue-next';

const props = defineProps<{
    students: Array<{
        id: number;
        name: string;
        stud_num: string;
        program: string;
        skills: Array<{
            id: number;
            name: string;
            level: string;
        }>;
        affiliations: Array<{
            id: number;
            name: string;
            type: string;
        }>;
    }>;
    query: string;
    queryType: string;
    total: number;
    filters?: {
        skill?: string;
        affiliation?: string;
    };
}>();

const viewMode = ref<'table' | 'list' | 'cards'>('table');
const searchQuery = ref('');

// Computed filtered students
const filteredStudents = computed(() => {
    if (!searchQuery.value) return props.students;
    
    return props.students.filter(student => 
        student.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        student.stud_num.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        student.program.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const getSkillLevelColor = (level: string) => {
    switch (level.toLowerCase()) {
        case 'beginner': return 'bg-green-100 text-green-800';
        case 'intermediate': return 'bg-blue-100 text-blue-800';
        case 'advanced': return 'bg-purple-100 text-purple-800';
        case 'expert': return 'bg-orange-100 text-orange-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getAffiliationTypeColor = (type: string) => {
    switch (type.toLowerCase()) {
        case 'sports': return 'bg-green-100 text-green-800';
        case 'organization': return 'bg-blue-100 text-blue-800';
        case 'club': return 'bg-purple-100 text-purple-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <FacultyLayout>
        <Head title="Query Results" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Query Results: "{{ query }}"
                </h1>
                <p class="text-gray-600 mt-2">
                    Found {{ total }} student{{ total !== 1 ? 's' : '' }} with {{ queryType }} filter
                </p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="relative flex-1">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search results by name, ID, or program..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                        />
                    </div>
                    
                    <!-- View Mode Toggle -->
                    <div class="flex items-center space-x-2">
                        <button 
                            @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            Table
                        </button>
                        <button 
                            @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            List
                        </button>
                        <button 
                            @click="viewMode = 'cards'"
                            :class="viewMode === 'cards' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            Cards
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Count -->
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm text-gray-600">
                    Showing {{ filteredStudents.length }} of {{ total }} results
                </div>
                <Link 
                    href="/students/query"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
                >
                    <Filter class="h-4 w-4" />
                    New Query
                </Link>
            </div>

            <!-- Table View -->
            <div v-if="viewMode === 'table'" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Program
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Skills
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Affiliations
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="student in filteredStudents" :key="student.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-orange-600">{{ student.name.charAt(0) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                                            <div class="text-xs text-gray-500">{{ student.stud_num }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ student.program }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="skill in student.skills" :key="skill.id" 
                                              :class="getSkillLevelColor(skill.level)" 
                                              class="px-2 py-1 rounded-full text-xs">
                                            {{ skill.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="affiliation in student.affiliations" :key="affiliation.id" 
                                              :class="getAffiliationTypeColor(affiliation.type)" 
                                              class="px-2 py-1 rounded-full text-xs">
                                            {{ affiliation.name }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- List View -->
            <div v-else-if="viewMode === 'list'" class="space-y-4">
                <div v-for="student in filteredStudents" :key="student.id" 
                     class="bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                <span class="text-lg font-bold text-orange-600">{{ student.name.charAt(0) }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ student.name }}</h3>
                                <p class="text-sm text-gray-600">{{ student.stud_num }} • {{ student.program }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <Link 
                                :href="`/students/${student.id}`"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            >
                                View Profile
                            </Link>
                        </div>
                    </div>
                    
                    <!-- Skills and Affiliations -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Skills</h4>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="skill in student.skills" :key="skill.id" 
                                      :class="getSkillLevelColor(skill.level)" 
                                      class="px-2 py-1 rounded-full text-xs">
                                    {{ skill.name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Affiliations</h4>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="affiliation in student.affiliations" :key="affiliation.id" 
                                      :class="getAffiliationTypeColor(affiliation.type)" 
                                      class="px-2 py-1 rounded-full text-xs">
                                    {{ affiliation.name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards View -->
            <div v-else-if="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="student in filteredStudents" :key="student.id" 
                     class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                <span class="text-lg font-bold text-orange-600">{{ student.name.charAt(0) }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ student.name }}</h3>
                                <p class="text-sm text-gray-600">{{ student.stud_num }} • {{ student.program }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ student.program }}
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="space-y-4">
                        <!-- Skills Section -->
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <Award class="h-4 w-4 text-orange-500" />
                                <h4 class="text-sm font-semibold text-gray-900">Skills</h4>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="skill in student.skills" :key="skill.id" 
                                      :class="getSkillLevelColor(skill.level)" 
                                      class="px-2 py-1 rounded-full text-xs">
                                    {{ skill.name }}
                                </span>
                            </div>
                        </div>

                        <!-- Affiliations Section -->
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <Briefcase class="h-4 w-4 text-blue-500" />
                                <h4 class="text-sm font-semibold text-gray-900">Affiliations</h4>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <span v-for="affiliation in student.affiliations" :key="affiliation.id" 
                                      :class="getAffiliationTypeColor(affiliation.type)" 
                                      class="px-2 py-1 rounded-full text-xs">
                                    {{ affiliation.name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <Link 
                            :href="`/students/${student.id}`"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-center font-medium transition-colors"
                        >
                            View Full Profile
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredStudents.length === 0" class="text-center py-12">
                <Users class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <div class="text-gray-400 text-lg mb-2">No students found</div>
                <div class="text-gray-500 text-sm">
                    Try adjusting your search or query terms
                </div>
            </div>
        </div>
    </FacultyLayout>
</template>
