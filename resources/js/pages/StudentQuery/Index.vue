<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { Search, Filter, Users, Trophy, Target } from 'lucide-vue-next';

const searchQuery = ref('');
const selectedSkill = ref('');
const selectedAffiliation = ref('');
const queryType = ref<'skill' | 'affiliation' | 'advanced'>('skill');

const predefinedSkills = [
    'Basketball',
    'Programming',
    'Web Development',
    'Database Design',
    'Mobile Development',
    'Data Analysis',
    'Machine Learning',
    'Cybersecurity',
    'Network Administration',
    'Graphic Design',
    'Project Management'
];

const predefinedAffiliations = [
    'Basketball Team',
    'Programming Club',
    'Student Government',
    'Debate Society',
    'Drama Club',
    'Music Society',
    'Dance Troupe',
    'Research Assistant',
    'Teaching Assistant',
    'Library Assistant',
    'IT Support',
    'Web Development Team',
    'Game Development Club',
    'Hackathon Participant',
    'Open Source Contributor'
];

const runSkillQuery = () => {
    if (!selectedSkill.value.trim()) {
        return;
    }
    router.get(`/query/skill?skill=${encodeURIComponent(selectedSkill.value)}`);
};

const runAffiliationQuery = () => {
    if (!selectedAffiliation.value.trim()) {
        return;
    }
    router.get(`/query/affiliation?affiliation=${encodeURIComponent(selectedAffiliation.value)}`);
};

const runAdvancedQuery = () => {
    const params = new URLSearchParams();
    if (selectedSkill.value) params.append('skill', selectedSkill.value);
    if (selectedAffiliation.value) params.append('affiliation', selectedAffiliation.value);
    
    router.get(`/query/advanced?${params.toString()}`);
};

const quickQuery = (query: string) => {
    if (queryType.value === 'skill') {
        selectedSkill.value = query;
        runSkillQuery();
    } else {
        selectedAffiliation.value = query;
        runAffiliationQuery();
    }
};
</script>

<template>
    <FacultyLayout>
        <Head title="Student Query System" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Student Query System</h1>
                <p class="text-gray-600 text-lg">
                    Find students by skills, affiliations, or activities
                </p>
            </div>

            <!-- Query Type Selection -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-center space-x-4">
                    <button 
                        @click="queryType = 'skill'"
                        :class="queryType === 'skill' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <Target class="h-5 w-5 mr-2" />
                        Query by Skill
                    </button>
                    <button 
                        @click="queryType = 'affiliation'"
                        :class="queryType === 'affiliation' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <Users class="h-5 w-5 mr-2" />
                        Query by Affiliation
                    </button>
                    <button 
                        @click="queryType = 'advanced'"
                        :class="queryType === 'advanced' ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-700'"
                        class="px-6 py-3 rounded-lg font-medium transition-colors"
                    >
                        <Filter class="h-5 w-5 mr-2" />
                        Advanced Query
                    </button>
                </div>
            </div>

            <!-- Skill Query Panel -->
            <div v-if="queryType === 'skill'" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Query by Skill</h2>
                
                <!-- Quick Access Buttons -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Quick Access</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        <button 
                            v-for="skill in predefinedSkills"
                            :key="skill"
                            @click="quickQuery(skill)"
                            class="bg-orange-100 hover:bg-orange-200 text-orange-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            {{ skill }}
                        </button>
                    </div>
                </div>

                <!-- Custom Skill Input -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Custom Skill</h3>
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-1">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input 
                                v-model="selectedSkill"
                                type="text" 
                                placeholder="Enter skill name (e.g., Basketball, Programming)..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                @keyup.enter="runSkillQuery"
                            />
                        </div>
                        <button 
                            @click="runSkillQuery"
                            :disabled="!selectedSkill.trim()"
                            class="bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                        >
                            Search Students
                        </button>
                    </div>
                </div>
            </div>

            <!-- Affiliation Query Panel -->
            <div v-if="queryType === 'affiliation'" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Query by Affiliation</h2>
                
                <!-- Quick Access Buttons -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Quick Access</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <button 
                            v-for="affiliation in predefinedAffiliations"
                            :key="affiliation"
                            @click="quickQuery(affiliation)"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                        >
                            {{ affiliation }}
                        </button>
                    </div>
                </div>

                <!-- Custom Affiliation Input -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Custom Affiliation</h3>
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-1">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input 
                                v-model="selectedAffiliation"
                                type="text" 
                                placeholder="Enter affiliation name (e.g., Basketball Team, Programming Club)..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                @keyup.enter="runAffiliationQuery"
                            />
                        </div>
                        <button 
                            @click="runAffiliationQuery"
                            :disabled="!selectedAffiliation.trim()"
                            class="bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 text-white px-6 py-3 rounded-lg font-medium transition-colors"
                        >
                            Search Students
                        </button>
                    </div>
                </div>
            </div>

            <!-- Advanced Query Panel -->
            <div v-if="queryType === 'advanced'" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Advanced Query</h2>
                <p class="text-gray-600 mb-6">
                    Combine multiple filters for precise results
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Skill Filter -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Skill Filter</h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
                                <button 
                                    v-for="skill in predefinedSkills"
                                    :key="skill"
                                    @click="selectedSkill = skill"
                                    :class="selectedSkill === skill ? 'bg-orange-500 text-white' : 'bg-orange-100 hover:bg-orange-200 text-orange-700'"
                                    class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                >
                                    {{ skill }}
                                </button>
                            </div>
                            <input 
                                v-model="selectedSkill"
                                type="text" 
                                placeholder="Or enter custom skill..."
                                class="w-full mt-3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                            />
                        </div>
                    </div>

                    <!-- Affiliation Filter -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Affiliation Filter</h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <button 
                                    v-for="affiliation in predefinedAffiliations.slice(0, 6)"
                                    :key="affiliation"
                                    @click="selectedAffiliation = affiliation"
                                    :class="selectedAffiliation === affiliation ? 'bg-blue-500 text-white' : 'bg-blue-100 hover:bg-blue-200 text-blue-700'"
                                    class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                >
                                    {{ affiliation }}
                                </button>
                            </div>
                            <input 
                                v-model="selectedAffiliation"
                                type="text" 
                                placeholder="Or enter custom affiliation..."
                                class="w-full mt-3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-center">
                    <button 
                        @click="runAdvancedQuery"
                        :disabled="!selectedSkill.trim() && !selectedAffiliation.trim()"
                        class="bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 text-white px-8 py-3 rounded-lg font-medium transition-colors"
                    >
                        <Trophy class="h-5 w-5 mr-2" />
                        Run Advanced Query
                    </button>
                </div>
            </div>

            <!-- Demo Examples -->
            <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                <h2 class="text-xl font-bold text-blue-900 mb-4">
                    <Trophy class="h-6 w-6 inline mr-2" />
                    Live Demo Examples
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">🏀 Basketball Skill Query</h3>
                        <p class="text-gray-600 mb-3">Find all students with basketball skills</p>
                        <button 
                            @click="quickQuery('Basketball')"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Try Basketball Query
                        </button>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">💻 Programming Skill Query</h3>
                        <p class="text-gray-600 mb-3">Find all students with programming skills</p>
                        <button 
                            @click="quickQuery('Programming')"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            Try Programming Query
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </FacultyLayout>
</template>
