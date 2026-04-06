<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    student: {
        stud_id: number;
        stud_num: string;
        fname: string;
        mname?: string;
        lname: string;
        ext?: string;
        program: string;
        standing: string;
        email?: string;
        contact_num?: string;
        affiliations?: Array<{
            id: number;
            type: string;
            name: string;
            role?: string;
            start_date?: string;
            end_date?: string;
            description?: string;
        }>;
        skills?: Array<{
            id: number;
            category: string;
            name: string;
            level: string;
            description?: string;
            acquired_date?: string;
            certificate?: string;
        }>;
        violations?: Array<{
            id: number;
            type: string;
            severity: string;
            description: string;
            violation_date: string;
            status: string;
            sanction?: string;
        }>;
        achievements?: Array<{
            id: number;
            type: string;
            title: string;
            description?: string;
            achievement_date: string;
            level?: string;
            awarder?: string;
        }>;
        academic_history?: Array<{
            id: number;
            course_code: string;
            course_name: string;
            semester: string;
            year: number;
            grade?: number;
            credits: number;
            grade_letter?: string;
            status: string;
        }>;
    };
}>();

const activeTab = ref('overview');

const fullName = computed(() => {
    let name = `${props.student.fname} ${props.student.lname}`;
    if (props.student.mname) {
        name = `${props.student.fname} ${props.student.mname} ${props.student.lname}`;
    }
    if (props.student.ext) {
        name += ` ${props.student.ext}`;
    }
    return name;
});

const gpa = computed(() => {
    if (!props.student.academic_history) return 0.0;
    const completedCourses = props.student.academic_history.filter(course => course.status === 'completed');
    if (completedCourses.length === 0) return 0.0;
    const totalGrade = completedCourses.reduce((sum, course) => sum + (course.grade || 0), 0);
    return (totalGrade / completedCourses.length).toFixed(2);
});

const totalCredits = computed(() => {
    if (!props.student.academic_history) return 0;
    return props.student.academic_history
        .filter(course => course.status === 'completed')
        .reduce((sum, course) => sum + course.credits, 0);
});

const activeViolations = computed(() => {
    if (!props.student.violations) return 0;
    return props.student.violations.filter(v => v.status === 'pending').length;
});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
    {
        title: fullName.value,
        href: `/students/${props.student.stud_id}`,
    },
];

const getSeverityColor = (severity: string) => {
    switch (severity) {
        case 'minor': return 'bg-yellow-100 text-yellow-800';
        case 'major': return 'bg-orange-100 text-orange-800';
        case 'critical': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getSkillLevelColor = (level: string) => {
    switch (level) {
        case 'beginner': return 'bg-blue-100 text-blue-800';
        case 'intermediate': return 'bg-green-100 text-green-800';
        case 'advanced': return 'bg-purple-100 text-purple-800';
        case 'expert': return 'bg-orange-100 text-orange-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="`${fullName} - Student Profile`" />

        <div class="space-y-6">
            <!-- Student Overview Card -->
            <Card class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center">
                            <span class="text-xl font-bold text-orange-600">{{ student.fname.charAt(0) }}</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ fullName }}</h1>
                            <p class="text-gray-600">{{ student.stud_num }} • {{ student.program }} • {{ student.standing }}</p>
                            <p class="text-sm text-gray-500">{{ student.email }} • {{ student.contact_num }}</p>
                        </div>
                    </div>
                    <div class="text-right space-y-2">
                        <div class="text-sm">
                            <span class="font-medium">GPA:</span>
                            <span class="font-bold text-orange-600">{{ gpa }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium">Credits:</span>
                            <span class="font-bold">{{ totalCredits }}</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-medium">Violations:</span>
                            <Badge :class="activeViolations > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                {{ activeViolations }} Active
                            </Badge>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Detailed Information Tabs -->
            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="grid w-full grid-cols-5">
                    <TabsTrigger value="overview">Overview</TabsTrigger>
                    <TabsTrigger value="academic">Academic</TabsTrigger>
                    <TabsTrigger value="skills">Skills</TabsTrigger>
                    <TabsTrigger value="affiliations">Affiliations</TabsTrigger>
                    <TabsTrigger value="violations">Violations</TabsTrigger>
                </TabsList>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Recent Achievements -->
                        <Card class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Recent Achievements</h3>
                            <div v-if="student.achievements && student.achievements.length > 0" class="space-y-2">
                                <div v-for="achievement in student.achievements.slice(0, 3)" :key="achievement.id" 
                                     class="flex items-center space-x-3 p-2 bg-gray-50 rounded">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <span class="text-xs font-bold text-yellow-600">🏆</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ achievement.title }}</p>
                                        <p class="text-xs text-gray-500">{{ achievement.achievement_date }}</p>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No achievements recorded</p>
                        </Card>

                        <!-- Top Skills -->
                        <Card class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Top Skills</h3>
                            <div v-if="student.skills && student.skills.length > 0" class="space-y-2">
                                <div v-for="skill in student.skills.slice(0, 3)" :key="skill.id" 
                                     class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm font-medium">{{ skill.name }}</span>
                                    <Badge :class="getSkillLevelColor(skill.level)">{{ skill.level }}</Badge>
                                </div>
                            </div>
                            <p v-else class="text-sm text-gray-500">No skills recorded</p>
                        </Card>

                        <!-- Current Status -->
                        <Card class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Current Status</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Program:</span>
                                    <span class="text-sm font-bold">{{ student.program }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Standing:</span>
                                    <span class="text-sm font-bold">{{ student.standing }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium">Active Violations:</span>
                                    <Badge :class="activeViolations > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                        {{ activeViolations }}
                                    </Badge>
                                </div>
                            </div>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Academic History Tab -->
                <TabsContent value="academic" class="space-y-4">
                    <Card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Academic History</h3>
                        <div v-if="student.academic_history && student.academic_history.length > 0">
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-left p-2 font-medium">Course</th>
                                            <th class="text-left p-2 font-medium">Semester</th>
                                            <th class="text-left p-2 font-medium">Grade</th>
                                            <th class="text-left p-2 font-medium">Credits</th>
                                            <th class="text-left p-2 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="course in student.academic_history" :key="course.id" class="border-b">
                                            <td class="p-2">
                                                <div>
                                                    <p class="font-medium">{{ course.course_name }}</p>
                                                    <p class="text-sm text-gray-500">{{ course.course_code }}</p>
                                                </div>
                                            </td>
                                            <td class="p-2">{{ course.semester }} {{ course.year }}</td>
                                            <td class="p-2">
                                                <span v-if="course.grade" class="font-bold">{{ course.grade }}</span>
                                                <Badge v-if="course.grade_letter" class="ml-2">{{ course.grade_letter }}</Badge>
                                            </td>
                                            <td class="p-2">{{ course.credits }}</td>
                                            <td class="p-2">
                                                <Badge :class="course.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                                    {{ course.status }}
                                                </Badge>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No academic history recorded</p>
                    </Card>
                </TabsContent>

                <!-- Skills Tab -->
                <TabsContent value="skills" class="space-y-4">
                    <Card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Skills & Competencies</h3>
                        <div v-if="student.skills && student.skills.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="skill in student.skills" :key="skill.id" class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium">{{ skill.name }}</h4>
                                    <Badge :class="getSkillLevelColor(skill.level)">{{ skill.level }}</Badge>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ skill.category }}</p>
                                <p v-if="skill.description" class="text-sm text-gray-500 mb-2">{{ skill.description }}</p>
                                <div v-if="skill.certificate" class="text-xs text-gray-500">
                                    <span class="font-medium">Certificate:</span> {{ skill.certificate }}
                                </div>
                                <div v-if="skill.acquired_date" class="text-xs text-gray-500">
                                    <span class="font-medium">Acquired:</span> {{ skill.acquired_date }}
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No skills recorded</p>
                    </Card>
                </TabsContent>

                <!-- Affiliations Tab -->
                <TabsContent value="affiliations" class="space-y-4">
                    <Card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Affiliations & Organizations</h3>
                        <div v-if="student.affiliations && student.affiliations.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="affiliation in student.affiliations" :key="affiliation.id" class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium">{{ affiliation.name }}</h4>
                                    <Badge>{{ affiliation.type }}</Badge>
                                </div>
                                <p v-if="affiliation.role" class="text-sm text-gray-600 mb-2">Role: {{ affiliation.role }}</p>
                                <p v-if="affiliation.description" class="text-sm text-gray-500 mb-2">{{ affiliation.description }}</p>
                                <div class="text-xs text-gray-500">
                                    <span v-if="affiliation.start_date">{{ affiliation.start_date }}</span>
                                    <span v-if="affiliation.start_date && affiliation.end_date"> - </span>
                                    <span v-if="affiliation.end_date">{{ affiliation.end_date }}</span>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No affiliations recorded</p>
                    </Card>
                </TabsContent>

                <!-- Violations Tab -->
                <TabsContent value="violations" class="space-y-4">
                    <Card class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Disciplinary Records</h3>
                        <div v-if="student.violations && student.violations.length > 0" class="space-y-4">
                            <div v-for="violation in student.violations" :key="violation.id" class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium">{{ violation.type }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <Badge :class="getSeverityColor(violation.severity)">{{ violation.severity }}</Badge>
                                        <Badge :class="violation.status === 'pending' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                            {{ violation.status }}
                                        </Badge>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ violation.description }}</p>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Date: {{ violation.violation_date }}</span>
                                    <span v-if="violation.sanction">Sanction: {{ violation.sanction }}</span>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No violations recorded</p>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
