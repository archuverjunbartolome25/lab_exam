<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';

const page = usePage();
const isAdmin = computed(() => (page.props as any).isAdmin);
const Layout = computed(() => isAdmin.value ? AdminLayout : FacultyLayout);
import { 
    ArrowLeft, 
    User, 
    BookOpen, 
    Award, 
    Star, 
    Users, 
    AlertTriangle, 
    Heart, 
    Plus,
    Edit,
    Trash2,
    Save,
    X
} from 'lucide-vue-next';

const props = defineProps<{
    student: any;
    section: any;
    courses: any[];
    availableCourses: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/faculty/dashboard',
    },
    {
        title: 'Students',
        href: '/faculty/students',
    },
    {
        title: `${props.student.fname} ${props.student.lname}`,
        href: `/faculty/students/${props.student.stud_id}`,
    },
];

// Reactive form visibility states
const showCourseForm = ref(false);
const showAchievementForm = ref(false);
const showSkillForm = ref(false);
const showAffiliationForm = ref(false);
const showViolationForm = ref(false);
const showMedicalForm = ref(false);

// Forms for different sections
const courseForm = useForm({
    course_id: '',
});

const achievementForm = useForm({
    title: '',
    description: '',
    date: '',
    type: 'Academic',
    level: 'School',
});

const skillForm = useForm({
    name: '',
    level: 'Beginner',
    category: 'Technical',
});

const affiliationForm = useForm({
    name: '',
    type: 'Organization',
    role: '',
    start_date: '',
    end_date: '',
});

const violationForm = useForm({
    type: '',
    description: '',
    severity: 'Minor',
    date: '',
    status: 'Active',
});

const medicalForm = useForm({
    condition: '',
    description: '',
    diagnosis_date: '',
    treatment: '',
    medications: '',
    doctor: '',
    emergency_contact: '',
});

// Methods for managing different sections
const addCourse = () => {
    courseForm.post(`/faculty/students/${props.student.stud_id}/courses`, {
        onSuccess: () => {
            courseForm.reset();
            showCourseForm.value = false;
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Course add error:', errors);
        },
    });
};

const addAchievement = () => {
    achievementForm.post(`/faculty/students/${props.student.stud_id}/achievements`, {
        onSuccess: () => {
            achievementForm.reset();
            showAchievementForm.value = false;
            // Force page reload to show new achievement
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Achievement add error:', errors);
        },
    });
};

const addSkill = () => {
    skillForm.post(`/faculty/students/${props.student.stud_id}/skills`, {
        onSuccess: () => {
            skillForm.reset();
            showSkillForm.value = false;
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Skill add error:', errors);
        },
    });
};

const addAffiliation = () => {
    affiliationForm.post(`/faculty/students/${props.student.stud_id}/affiliations`, {
        onSuccess: () => {
            affiliationForm.reset();
            showAffiliationForm.value = false;
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Affiliation add error:', errors);
        },
    });
};

const addViolation = () => {
    violationForm.post(`/faculty/students/${props.student.stud_id}/violations`, {
        onSuccess: () => {
            violationForm.reset();
            showViolationForm.value = false;
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Violation add error:', errors);
        },
    });
};

const addMedical = () => {
    medicalForm.post(`/faculty/students/${props.student.stud_id}/medical`, {
        onSuccess: () => {
            medicalForm.reset();
            showMedicalForm.value = false;
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Medical add error:', errors);
        },
    });
};

const deleteItem = (type: string, id: number) => {
    if (confirm('Are you sure you want to delete this item?')) {
        const prefix = isAdmin.value ? '/admin' : '/faculty';
        useForm().delete(`${prefix}/students/${props.student.stud_id}/${type}/${id}`, {
            onSuccess: () => {
                window.location.reload();
            },
            onError: (errors) => {
                console.error('Delete error:', errors);
                alert('Error deleting item. Please try again.');
            },
        });
    }
};
</script>

<template>
    <Head :title="`${student.fname} ${student.lname} - Student Profile`" />

    <component :is="Layout" :breadcrumbs="breadcrumbs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <Link
                            href="/faculty/students"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <ArrowLeft class="w-4 h-4 mr-2" />
                            Back to Students
                        </Link>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                {{ student.fname }} {{ student.lname }} {{ student.ext }}
                            </h1>
                            <p class="text-gray-600">Student ID: {{ student.stud_num }} | {{ student.program }} - {{ section?.section }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <User class="w-8 h-8 text-blue-500 mr-3" />
                        <h3 class="text-lg font-semibold">Personal Info</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><strong>Email:</strong> {{ student.email }}</p>
                        <p><strong>Phone:</strong> {{ student.contact_num }}</p>
                        <p><strong>Gender:</strong> {{ student.gender }}</p>
                        <p><strong>Address:</strong> {{ student.address }}</p>
                        <p><strong>Standing:</strong> {{ student.standing }}</p>
                        <p><strong>Status:</strong> {{ student.academic_status }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <BookOpen class="w-8 h-8 text-green-500 mr-3" />
                        <h3 class="text-lg font-semibold">Academic Info</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><strong>Program:</strong> {{ student.program }}</p>
                        <p><strong>Section:</strong> {{ section?.section || 'N/A' }}</p>
                        <p><strong>Courses:</strong> {{ courses.length }} enrolled</p>
                        <p><strong>Total Credits:</strong> {{ courses.reduce((sum, course) => sum + course.credits, 0) }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <Star class="w-8 h-8 text-yellow-500 mr-3" />
                        <h3 class="text-lg font-semibold">Overview</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><strong>Achievements:</strong> {{ student.achievements?.length || 0 }}</p>
                        <p><strong>Skills:</strong> {{ student.skills?.length || 0 }}</p>
                        <p><strong>Affiliations:</strong> {{ student.affiliations?.length || 0 }}</p>
                        <p><strong>Violations:</strong> {{ student.violations?.length || 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Courses Section -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Enrolled Courses</h3>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="course in courses" :key="course.id" class="border rounded-lg p-4">
                            <h4 class="font-semibold">{{ course.code }}</h4>
                            <p class="text-sm text-gray-600">{{ course.name }}</p>
                            <p class="text-sm">{{ course.credits }} credits</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Section -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Award class="w-6 h-6 text-yellow-500 mr-2" />
                            <h3 class="text-lg font-semibold">Recent Achievements</h3>
                        </div>
                    </div>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <div v-if="!student.achievements || student.achievements.length === 0" class="text-center py-8">
                        <div class="text-gray-400 text-lg mb-2">No achievements yet</div>
                        <div class="text-gray-500 text-sm">Click "Add Achievement" to record student accomplishments</div>
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="achievement in student.achievements" :key="achievement.id" class="border rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold">{{ achievement.title }}</h4>
                                <p class="text-sm text-gray-600">{{ achievement.description }}</p>
                                <p class="text-xs text-gray-500">{{ achievement.achievement_date }} | {{ achievement.type }} | {{ achievement.level }}</p>
                            </div>
                            <button v-if="isAdmin" @click="deleteItem('achievements', achievement.id)" class="text-red-500 hover:text-red-700">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Code class="w-6 h-6 text-purple-500 mr-2" />
                            <h3 class="text-lg font-semibold">Skills</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        <span v-for="skill in student.skills" :key="skill.id" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                            {{ skill.name }} ({{ skill.level }})
                        </span>
                    </div>
                </div>
            </div>

            <!-- Affiliations Section -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Users class="w-6 h-6 text-green-500 mr-2" />
                            <h3 class="text-lg font-semibold">Affiliations</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="affiliation in student.affiliations" :key="affiliation.id" class="border rounded-lg p-4">
                            <div>
                                <h4 class="font-semibold">{{ affiliation.name }}</h4>
                                <p class="text-sm text-gray-600">{{ affiliation.role }} | {{ affiliation.type }}</p>
                                <p class="text-xs text-gray-500">{{ affiliation.start_date }} - {{ affiliation.end_date || 'Present' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disciplinary Records Section -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <AlertTriangle class="w-6 h-6 text-red-500 mr-2" />
                            <h3 class="text-lg font-semibold">Disciplinary Records</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="violation in student.violations" :key="violation.id" class="border rounded-lg p-4">
                            <div>
                                <h4 class="font-semibold">{{ violation.type }}</h4>
                                <p class="text-sm text-gray-600">{{ violation.description }}</p>
                                <p class="text-xs text-gray-500">{{ violation.date }} | {{ violation.severity }} | {{ violation.status }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Records Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Heart class="w-6 h-6 text-pink-500 mr-2" />
                            <h3 class="text-lg font-semibold">Medical Records</h3>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="record in student.medicalRecord" :key="record.id" class="border rounded-lg p-4">
                            <div>
                                <h4 class="font-semibold">{{ record.condition }}</h4>
                                <p class="text-sm text-gray-600">{{ record.description }}</p>
                                <p class="text-xs text-gray-500">{{ record.diagnosis_date }} | Dr. {{ record.doctor }}</p>
                                <p class="text-xs text-gray-500">Treatment: {{ record.treatment }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
