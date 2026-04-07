<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    student: {
        stud_id: number;
        stud_num: string;
        fname: string;
        lname: string;
        mname?: string;
        ext?: string;
        email: string;
        contact_num: string;
        bday: string;
        gender: string;
        address: string;
        program: string;
        program_code: string;
        section_id: number;
        courses?: number[];
        standing: string;
        academic_status: string;
    };
    sectionsIT: Array<{
        section_id: number;
        section_name: string;
    }>;
    sectionsCS: Array<{
        section_id: number;
        section_name: string;
    }>;
    coursesIT: Array<{
        course_id: number;
        course_code: string;
        course: string;
        credits: number;
    }>;
    coursesCS: Array<{
        course_id: number;
        course_code: string;
        course: string;
        credits: number;
    }>;
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/faculty/students',
    },
    {
        title: `${props.fname} ${props.lname}`,
        href: `/faculty/students/${props.stud_id}`,
    },
    {
        title: 'Edit Student',
        href: `/faculty/students/${props.stud_id}/edit`,
    },
];

const form = useForm({
    first_name: props.fname,
    last_name: props.lname,
    middle_name: props.mname || '',
    ext: props.ext || '',
    email: props.email,
    phone: props.contact_num,
    birth_date: props.bday,
    gender: props.gender,
    address: props.address,
    program: props.program,
    program_code: props.program_code,
    section_id: props.section_id,
    courses: props.courses || [],
    standing: props.standing,
    academic_status: props.academic_status,
});

const submitForm = () => {
    form.put(route('students.update', props.stud_id), {
        onSuccess: () => {
            // Handle successful update
        },
        onError: (errors) => {
            console.error('Form errors:', errors);
        },
    });
};

const isIT = computed(() => props.program === 'BSIT');
const availableSections = computed(() => isIT.value ? props.sectionsIT : props.sectionsCS);
const availableCourses = computed(() => isIT.value ? props.coursesIT : props.coursesCS);
const enrolledCourses = computed(() => {
    if (!props.courses) return [];
    return isIT.value 
        ? props.coursesIT?.filter(course => props.courses?.includes(course.course_id))
        : props.coursesCS?.filter(course => props.courses?.includes(course.course_id));
});
</script>

<template>
    <AppLayout>
        <Head title="Edit Student" />

        <div class="p-6">
            <div class="mb-6">
                <Breadcrumbs :items="breadcrumbItems" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Basic Information -->
                <Card>
                    <CardHeader>
                        <CardTitle>Basic Information</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submitForm" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label for="first_name">First Name</Label>
                                    <Input
                                        id="first_name"
                                        v-model="form.first_name"
                                        type="text"
                                        required
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.first_name" />
                                </div>
                                <div>
                                    <Label for="last_name">Last Name</Label>
                                    <Input
                                        id="last_name"
                                        v-model="form.last_name"
                                        type="text"
                                        required
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.last_name" />
                                </div>
                                <div>
                                    <Label for="middle_name">Middle Name</Label>
                                    <Input
                                        id="middle_name"
                                        v-model="form.middle_name"
                                        type="text"
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.middle_name" />
                                </div>
                                <div>
                                    <Label for="ext">Extension</Label>
                                    <Input
                                        id="ext"
                                        v-model="form.ext"
                                        type="text"
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.ext" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label for="email">Email</Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.email" />
                                </div>
                                <div>
                                    <Label for="phone">Phone</Label>
                                    <Input
                                        id="phone"
                                        v-model="form.phone"
                                        type="text"
                                        required
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.phone" />
                                </div>
                            </div>

                            <div>
                                <Label for="birth_date">Birth Date</Label>
                                <Input
                                    id="birth_date"
                                    v-model="form.birth_date"
                                    type="date"
                                    required
                                    class="mt-1"
                                />
                                <InputError :message="form.errors.birth_date" />
                            </div>

                            <div>
                                <Label for="gender">Gender</Label>
                                <Select v-model="form.gender" class="mt-1">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select gender" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="Male">Male</SelectItem>
                                        <SelectItem value="Female">Female</SelectItem>
                                        <SelectItem value="Other">Other</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.gender" />
                            </div>

                            <div>
                                <Label for="address">Address</Label>
                                <Input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    required
                                    class="mt-1"
                                />
                                <InputError :message="form.errors.address" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <Label for="program">Program</Label>
                                    <Select v-model="form.program" class="mt-1">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select program" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="BSIT">BSIT</SelectItem>
                                            <SelectItem value="BSCS">BSCS</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.program" />
                                </div>
                                <div>
                                    <Label for="program_code">Program Code</Label>
                                    <Input
                                        id="program_code"
                                        v-model="form.program_code"
                                        type="text"
                                        required
                                        class="mt-1"
                                    />
                                    <InputError :message="form.errors.program_code" />
                                </div>
                                <div>
                                    <Label for="section_id">Section</Label>
                                    <Select v-model="form.section_id" class="mt-1">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select section" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem 
                                                v-for="section in availableSections" 
                                                :key="section.section_id"
                                                :value="section.section_id"
                                            >
                                                {{ section.section_name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.section_id" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <Label for="standing">Standing</Label>
                                    <Select v-model="form.standing" class="mt-1">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select standing" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="1st">1st Year</SelectItem>
                                            <SelectItem value="2nd">2nd Year</SelectItem>
                                            <SelectItem value="3rd">3rd Year</SelectItem>
                                            <SelectItem value="4th">4th Year</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.standing" />
                                </div>
                                <div>
                                    <Label for="academic_status">Academic Status</Label>
                                    <Select v-model="form.academic_status" class="mt-1">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="Regular">Regular</SelectItem>
                                            <SelectItem value="Irregular">Irregular</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.academic_status" />
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? 'Updating...' : 'Update Student' }}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <!-- Academic Information -->
                <Card>
                    <CardHeader>
                        <CardTitle>Academic Information</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div>
                                <Label for="courses">Courses</Label>
                                <div class="border rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div 
                                            v-for="course in availableCourses" 
                                            :key="course.course_id"
                                            class="flex items-center space-x-2 p-2 border rounded hover:bg-gray-50"
                                        >
                                            <input
                                                type="checkbox"
                                                :id="`course_${course.course_id}`"
                                                :value="course.course_id"
                                                v-model="form.courses"
                                                class="rounded"
                                            />
                                            <Label :for="`course_${course.course_id}`" class="flex-1">
                                                {{ course.course_code }} - {{ course.course }} ({{ course.credits }} credits)
                                            </Label>
                                        </div>
                                    </div>
                                </div>
                                <InputError :message="form.errors.courses" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-6">
                <Link 
                    :href="`/faculty/students/${props.stud_id}`"
                    class="text-gray-600 hover:text-gray-900 underline"
                >
                    Cancel
                </Link>
                <div class="text-sm text-gray-500">
                    Student ID: {{ props.stud_id }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>
