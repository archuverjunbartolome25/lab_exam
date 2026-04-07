<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
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
        useForm().delete(`/faculty/students/${props.student.stud_id}/${type}/${id}`, {
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

    <FacultyLayout :breadcrumbs="breadcrumbs">
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <BookOpen class="w-6 h-6 text-blue-500 mr-2" />
                            <h3 class="text-lg font-semibold">Courses</h3>
                        </div>
                        <button
                            @click="showCourseForm = !showCourseForm"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            <Plus class="w-4 h-4 mr-1" />
                            Add Course
                        </button>
                    </div>
                </div>

                <!-- Add Course Form -->
                <div v-if="showCourseForm" class="p-6 border-b border-gray-200 bg-gray-50">
                    <form @submit.prevent="addCourse" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select v-model="courseForm.course_id" class="rounded-md border-gray-300" required>
                            <option value="">Select Course</option>
                            <option v-for="course in availableCourses" :key="course.course_id" :value="course.course_id">
                                {{ course.course_code }} - {{ course.course }}
                            </option>
                        </select>
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                <Save class="w-4 h-4" />
                            </button>
                            <button type="button" @click="showCourseForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </form>
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
                        <button
                            @click="showAchievementForm = !showAchievementForm"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700"
                        >
                            <Plus class="w-4 h-4 mr-1" />
                            Add Achievement
                        </button>
                    </div>
                </div>

                <!-- Add Achievement Form -->
                <div v-if="showAchievementForm" class="p-6 border-b border-gray-200 bg-gray-50">
                    <form @submit.prevent="addAchievement" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input v-model="achievementForm.title" placeholder="Achievement Title" class="rounded-md border-gray-300" required />
                        <input v-model="achievementForm.description" placeholder="Description" class="rounded-md border-gray-300" required />
                        <input v-model="achievementForm.date" type="date" class="rounded-md border-gray-300" required />
                        <select v-model="achievementForm.type" class="rounded-md border-gray-300">
                            <option value="Academic">Academic</option>
                            <option value="Sports">Sports</option>
                            <option value="Cultural">Cultural</option>
                            <option value="Leadership">Leadership</option>
                        </select>
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                                <Save class="w-4 h-4" />
                            </button>
                            <button type="button" @click="showAchievementForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                <X class="w-4 h-4" />
                            </button>
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
                            <button @click="deleteItem('achievements', achievement.id)" class="text-red-500 hover:text-red-700">
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
                            <Star class="w-6 h-6 text-purple-500 mr-2" />
                            <h3 class="text-lg font-semibold">Top Skills</h3>
                        </div>
                        <button
                            @click="showSkillForm = !showSkillForm"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700"
                        >
                            <Plus class="w-4 h-4 mr-1" />
                            Add Skill
                        </button>
                    </div>
                </div>

                <!-- Add Skill Form -->
                <div v-if="showSkillForm" class="p-6 border-b border-gray-200 bg-gray-50">
                    <form @submit.prevent="addSkill" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input v-model="skillForm.name" placeholder="Skill Name" class="rounded-md border-gray-300" required />
                        <select v-model="skillForm.level" class="rounded-md border-gray-300">
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Expert">Expert</option>
                        </select>
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                <Save class="w-4 h-4" />
                            </button>
                            <button type="button" @click="showSkillForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        <span v-for="skill in student.skills" :key="skill.id" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                            {{ skill.name }} ({{ skill.level }})
                            <button @click="deleteItem('skills', skill.id)" class="ml-2 text-purple-600 hover:text-purple-800">
                                <X class="w-3 h-3" />
                            </button>
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
                        <button
                            @click="showAffiliationForm = !showAffiliationForm"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700"
                        >
                            <Plus class="w-4 h-4 mr-1" />
                            Add Affiliation
                        </button>
                    </div>
                </div>

                <!-- Add Affiliation Form -->
                <div v-if="showAffiliationForm" class="p-6 border-b border-gray-200 bg-gray-50">
                    <form @submit.prevent="addAffiliation" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input v-model="affiliationForm.name" placeholder="Organization Name" class="rounded-md border-gray-300" required />
                        <input v-model="affiliationForm.role" placeholder="Role/Position" class="rounded-md border-gray-300" required />
                        <select v-model="affiliationForm.type" class="rounded-md border-gray-300">
                            <option value="Organization">Organization</option>
                            <option value="Club">Club</option>
                            <option value="Society">Society</option>
                            <option value="Team">Team</option>
                        </select>
                        <input v-model="affiliationForm.start_date" type="date" class="rounded-md border-gray-300" required />
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <Save class="w-4 h-4" />
                            </button>
                            <button type="button" @click="showAffiliationForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="affiliation in student.affiliations" :key="affiliation.id" class="border rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold">{{ affiliation.name }}</h4>
                                <p class="text-sm text-gray-600">{{ affiliation.role }} | {{ affiliation.type }}</p>
                                <p class="text-xs text-gray-500">{{ affiliation.start_date }} - {{ affiliation.end_date || 'Present' }}</p>
                            </div>
                            <button @click="deleteItem('affiliations', affiliation.id)" class="text-red-500 hover:text-red-700">
                                <Trash2 class="w-4 h-4" />
                            </button>
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
                        <button
                            @click="showViolationForm = !showViolationForm"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                        >
                            <Plus class="w-4 h-4 mr-1" />
                            Add Violation
                        </button>
                    </div>
                </div>

                <!-- Add Violation Form -->
                <div v-if="showViolationForm" class="p-6 border-b border-gray-200 bg-gray-50">
                    <form @submit.prevent="addViolation" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input v-model="violationForm.type" placeholder="Violation Type" class="rounded-md border-gray-300" required />
                        <textarea v-model="violationForm.description" placeholder="Description" class="rounded-md border-gray-300" required></textarea>
                        <select v-model="violationForm.severity" class="rounded-md border-gray-300">
                            <option value="Minor">Minor</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Major">Major</option>
                            <option value="Severe">Severe</option>
                        </select>
                        <input v-model="violationForm.date" type="date" class="rounded-md border-gray-300" required />
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                <Save class="w-4 h-4" />
                            </button>
                            <button type="button" @click="showViolationForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="violation in student.violations" :key="violation.id" class="border rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold">{{ violation.type }}</h4>
                                <p class="text-sm text-gray-600">{{ violation.description }}</p>
                                <p class="text-xs text-gray-500">{{ violation.date }} | {{ violation.severity }} | {{ violation.status }}</p>
                            </div>
                            <button @click="deleteItem('violations', violation.id)" class="text-red-500 hover:text-red-700">
                                <Trash2 class="w-4 h-4" />
                            </button>
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
                        <button
                            @click="showMedicalForm = !showMedicalForm"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700"
                        >
                            <Plus class="w-4 h-4 mr-1" />
                            Add Medical Record
                        </button>
                    </div>
                </div>

                <!-- Add Medical Form -->
                <div v-if="showMedicalForm" class="p-6 border-b border-gray-200 bg-gray-50">
                    <form @submit.prevent="addMedical" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input v-model="medicalForm.condition" placeholder="Condition" class="rounded-md border-gray-300" required />
                        <textarea v-model="medicalForm.description" placeholder="Description" class="rounded-md border-gray-300" required></textarea>
                        <input v-model="medicalForm.diagnosis_date" type="date" class="rounded-md border-gray-300" required />
                        <textarea v-model="medicalForm.treatment" placeholder="Treatment" class="rounded-md border-gray-300"></textarea>
                        <input v-model="medicalForm.medications" placeholder="Medications" class="rounded-md border-gray-300" />
                        <input v-model="medicalForm.doctor" placeholder="Doctor" class="rounded-md border-gray-300" />
                        <div class="flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700">
                                <Save class="w-4 h-4" />
                            </button>
                            <button type="button" @click="showMedicalForm = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <div class="space-y-3">
                        <div v-for="record in student.medicalRecord" :key="record.id" class="border rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold">{{ record.condition }}</h4>
                                <p class="text-sm text-gray-600">{{ record.description }}</p>
                                <p class="text-xs text-gray-500">{{ record.diagnosis_date }} | Dr. {{ record.doctor }}</p>
                                <p class="text-xs text-gray-500">Treatment: {{ record.treatment }}</p>
                            </div>
                            <button @click="deleteItem('medical', record.id)" class="text-red-500 hover:text-red-700">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FacultyLayout>
</template>
