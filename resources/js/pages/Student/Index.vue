<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    students: Array<{
        stud_id: number;
        stud_num: string;
        fname: string;
        mname?: string;
        lname: string;
        ext?: string;
        program: string;
        standing: string;
        current_gpa?: number;
        total_credits?: number;
        active_violations?: number;
        program_code?: string;
        affiliations?: Array<{ type: string; name: string }>;
        skills?: Array<{ category: string; name: string; level: string }>;
    }>;
    filters?: {
        program?: string;
        standing?: string;
        skills?: string;
        affiliations?: string;
        violations?: string;
    };
}>();

const searchQuery = ref('');
const selectedProgram = ref(props.filters?.program || 'all');
const selectedStanding = ref(props.filters?.standing || 'all');
const selectedSkill = ref(props.filters?.skills || '');
const selectedAffiliation = ref(props.filters?.affiliations || '');
const violationFilter = ref(props.filters?.violations || 'all');
const currentPage = ref(1);
const perPage = 12;

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
];

const filteredStudents = computed(() => {
    let filtered = props.students;

    // Search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(student => 
            student.fname.toLowerCase().includes(query) ||
            student.lname.toLowerCase().includes(query) ||
            student.stud_num.toLowerCase().includes(query) ||
            student.program.toLowerCase().includes(query)
        );
    }

    // Program filter
    if (selectedProgram.value !== 'all') {
        filtered = filtered.filter(student => student.program === selectedProgram.value);
    }

    // Standing filter
    if (selectedStanding.value !== 'all') {
        filtered = filtered.filter(student => student.standing === selectedStanding.value);
    }

    // Violation filter
    if (violationFilter.value !== 'all') {
        if (violationFilter.value === 'none') {
            filtered = filtered.filter(student => (student.active_violations || 0) === 0);
        } else if (violationFilter.value === 'has') {
            filtered = filtered.filter(student => (student.active_violations || 0) > 0);
        }
    }

    // Skills filter
    if (selectedSkill.value) {
        filtered = filtered.filter(student => 
            student.skills?.some(skill => 
                skill.name.toLowerCase().includes(selectedSkill.value.toLowerCase()) ||
                skill.category.toLowerCase().includes(selectedSkill.value.toLowerCase())
            )
        );
    }

    // Affiliation filter
    if (selectedAffiliation.value) {
        filtered = filtered.filter(student => 
            student.affiliations?.some(affiliation => 
                affiliation.name.toLowerCase().includes(selectedAffiliation.value.toLowerCase()) ||
                affiliation.type.toLowerCase().includes(selectedAffiliation.value.toLowerCase())
            )
        );
    }

    return filtered;
});

const paginatedStudents = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return filteredStudents.value.slice(start, end);
});

const totalPages = computed(() => Math.ceil(filteredStudents.value.length / perPage));

const applyFilters = () => {
    const params = new URLSearchParams();
    if (selectedProgram.value !== 'all') params.set('program', selectedProgram.value);
    if (selectedStanding.value !== 'all') params.set('standing', selectedStanding.value);
    if (selectedSkill.value) params.set('skills', selectedSkill.value);
    if (selectedAffiliation.value) params.set('affiliations', selectedAffiliation.value);
    if (violationFilter.value !== 'all') params.set('violations', violationFilter.value);
    
    router.get(`/students?${params.toString()}`);
};

const clearFilters = () => {
    selectedProgram.value = 'all';
    selectedStanding.value = 'all';
    selectedSkill.value = '';
    selectedAffiliation.value = '';
    violationFilter.value = 'all';
    searchQuery.value = '';
    router.get('/students');
};

const getGpaColor = (gpa?: number) => {
    if (!gpa) return 'text-gray-500';
    if (gpa >= 3.5) return 'text-green-600';
    if (gpa >= 3.0) return 'text-blue-600';
    if (gpa >= 2.5) return 'text-yellow-600';
    return 'text-red-600';
};

const getViolationColor = (count?: number) => {
    if (!count || count === 0) return 'bg-green-100 text-green-800';
    if (count <= 2) return 'bg-yellow-100 text-yellow-800';
    return 'bg-red-100 text-red-800';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Students" />

        <div class="space-y-6">
            <!-- Search and Filters -->
            <Card class="p-6">
                <div class="space-y-4">
                    <!-- Search Bar -->
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <Input
                                v-model="searchQuery"
                                placeholder="Search students by name, ID, or program..."
                                class="w-full"
                            />
                        </div>
                        <Button @click="applyFilters">Apply Filters</Button>
                        <Button variant="outline" @click="clearFilters">Clear</Button>
                    </div>

                    <!-- Filter Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <Select v-model="selectedProgram">
                            <SelectTrigger>
                                <SelectValue placeholder="Select Program" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Programs</SelectItem>
                                <SelectItem value="BSIT">BSIT</SelectItem>
                                <SelectItem value="BSCS">BSCS</SelectItem>
                                <SelectItem value="BSM">BSM</SelectItem>
                                <SelectItem value="BSA">BSA</SelectItem>
                            </SelectContent>
                        </Select>

                        <Select v-model="selectedStanding">
                            <SelectTrigger>
                                <SelectValue placeholder="Select Standing" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Standing</SelectItem>
                                <SelectItem value="1st Year">1st Year</SelectItem>
                                <SelectItem value="2nd Year">2nd Year</SelectItem>
                                <SelectItem value="3rd Year">3rd Year</SelectItem>
                                <SelectItem value="4th Year">4th Year</SelectItem>
                                <SelectItem value="5th Year">5th Year</SelectItem>
                            </SelectContent>
                        </Select>

                        <Input
                            v-model="selectedSkill"
                            placeholder="Filter by skills..."
                        />

                        <Input
                            v-model="selectedAffiliation"
                            placeholder="Filter by affiliations..."
                        />

                        <Select v-model="violationFilter">
                            <SelectTrigger>
                                <SelectValue placeholder="Violations" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Students</SelectItem>
                                <SelectItem value="none">No Violations</SelectItem>
                                <SelectItem value="has">Has Violations</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </Card>

            <!-- Results Summary -->
            <Card class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Student Directory</h2>
                        <p class="text-gray-600">Showing {{ paginatedStudents.length }} of {{ filteredStudents.length }} students</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Badge class="bg-blue-100 text-blue-800">
                            Total: {{ filteredStudents.length }}
                        </Badge>
                        <Badge v-if="searchQuery || selectedProgram !== 'all' || selectedStanding !== 'all'" class="bg-orange-100 text-orange-800">
                            Filtered
                        </Badge>
                    </div>
                </div>
            </Card>

            <!-- Student Grid -->
            <div v-if="paginatedStudents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <Link
                    v-for="student in paginatedStudents"
                    :key="student.stud_id"
                    :href="`/students/${student.stud_id}`"
                    class="block group"
                >
                    <Card class="p-6 hover:shadow-lg transition-shadow cursor-pointer border-gray-200 group-hover:border-orange-300">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                <span class="text-lg font-bold text-orange-600">{{ student.fname.charAt(0) }}</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 group-hover:text-orange-600">
                                    {{ student.fname }} {{ student.lname }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ student.stud_num }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Program:</span>
                                <Badge>{{ student.program }}</Badge>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Standing:</span>
                                <span class="text-sm">{{ student.standing }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">GPA:</span>
                                <span class="text-sm font-bold" :class="getGpaColor(student.current_gpa)">
                                    {{ student.current_gpa?.toFixed(2) || 'N/A' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Violations:</span>
                                <Badge :class="getViolationColor(student.active_violations)">
                                    {{ student.active_violations || 0 }} Active
                                </Badge>
                            </div>
                        </div>

                        <!-- Quick Info Badges -->
                        <div v-if="student.skills || student.affiliations" class="mt-4 flex flex-wrap gap-1">
                            <Badge v-for="skill in (student.skills || []).slice(0, 2)" :key="skill.name" class="text-xs bg-blue-100 text-blue-800">
                                {{ skill.name }}
                            </Badge>
                            <Badge v-for="affiliation in (student.affiliations || []).slice(0, 1)" :key="affiliation.name" class="text-xs bg-green-100 text-green-800">
                                {{ affiliation.name }}
                            </Badge>
                        </div>
                    </Card>
                </Link>
            </div>

            <!-- Empty State -->
            <Card v-else class="p-12 text-center">
                <div class="text-gray-500">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-xl font-semibold mb-2">No students found</h3>
                    <p>Try adjusting your search criteria or filters to find students.</p>
                </div>
            </Card>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-center space-x-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage === 1"
                    @click="currentPage--"
                >
                    Previous
                </Button>
                
                <div class="flex space-x-1">
                    <Button
                        v-for="page in totalPages"
                        :key="page"
                        :variant="currentPage === page ? 'default' : 'outline'"
                        size="sm"
                        @click="currentPage = page"
                    >
                        {{ page }}
                    </Button>
                </div>
                
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="currentPage === totalPages"
                    @click="currentPage++"
                >
                    Next
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
