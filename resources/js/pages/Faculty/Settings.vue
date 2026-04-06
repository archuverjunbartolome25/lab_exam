<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import FacultyLayout from '@/layouts/FacultyLayout.vue';
import { Settings, User, Bell, Shield, Palette, HelpCircle, LogOut } from 'lucide-vue-next';

const facultyInfo = ref({
    name: 'Dr. Maria Garcia',
    email: 'maria.garcia@ccs.edu',
    department: 'Computer Science',
    phone: '0912-345-6789',
    office: 'Room 301, CCS Building',
    bio: 'Professor of Computer Science with 10+ years of experience in software engineering and data structures.'
});

const notifications = ref({
    emailNotifications: true,
    smsNotifications: false,
    gradeNotifications: true,
    attendanceNotifications: true,
    dueDateReminders: true
});

const preferences = ref({
    theme: 'light',
    language: 'en',
    defaultView: 'dashboard',
    autoSave: true
});

const updateProfile = () => {
    // Handle profile update
    console.log('Profile updated');
};

const updateNotifications = () => {
    // Handle notification settings
    console.log('Notifications updated');
};

const updatePreferences = () => {
    // Handle preference settings
    console.log('Preferences updated');
};
</script>

<template>
    <FacultyLayout>
        <Head title="Faculty Settings" />

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
                <p class="text-gray-600 mt-2">Manage your faculty profile and preferences</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Settings -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <User class="h-5 w-5 text-orange-600 mr-2" />
                            <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input 
                                    v-model="facultyInfo.name"
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input 
                                    v-model="facultyInfo.email"
                                    type="email" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <select 
                                    v-model="facultyInfo.department"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                >
                                    <option>Computer Science</option>
                                    <option>Information Technology</option>
                                    <option>Software Engineering</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input 
                                    v-model="facultyInfo.phone"
                                    type="tel" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Office</label>
                                <input 
                                    v-model="facultyInfo.office"
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                <textarea 
                                    v-model="facultyInfo.bio"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                ></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button 
                                @click="updateProfile"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors"
                            >
                                Save Profile
                            </button>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <Bell class="h-5 w-5 text-orange-600 mr-2" />
                            <h2 class="text-lg font-semibold text-gray-900">Notification Preferences</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <label class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Email Notifications</span>
                                <input 
                                    v-model="notifications.emailNotifications"
                                    type="checkbox" 
                                    class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                />
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">SMS Notifications</span>
                                <input 
                                    v-model="notifications.smsNotifications"
                                    type="checkbox" 
                                    class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                />
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Grade Notifications</span>
                                <input 
                                    v-model="notifications.gradeNotifications"
                                    type="checkbox" 
                                    class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                />
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Attendance Notifications</span>
                                <input 
                                    v-model="notifications.attendanceNotifications"
                                    type="checkbox" 
                                    class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                />
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Due Date Reminders</span>
                                <input 
                                    v-model="notifications.dueDateReminders"
                                    type="checkbox" 
                                    class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                />
                            </label>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button 
                                @click="updateNotifications"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors"
                            >
                                Save Notifications
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <Link 
                                href="/faculty/profile"
                                class="flex items-center space-x-3 text-gray-700 hover:text-orange-600 p-2 rounded-lg hover:bg-orange-50 transition-colors"
                            >
                                <User class="h-4 w-4" />
                                <span class="text-sm">Edit Profile</span>
                            </Link>
                            <Link 
                                href="/faculty/security"
                                class="flex items-center space-x-3 text-gray-700 hover:text-orange-600 p-2 rounded-lg hover:bg-orange-50 transition-colors"
                            >
                                <Shield class="h-4 w-4" />
                                <span class="text-sm">Security Settings</span>
                            </Link>
                            <Link 
                                href="/faculty/help"
                                class="flex items-center space-x-3 text-gray-700 hover:text-orange-600 p-2 rounded-lg hover:bg-orange-50 transition-colors"
                            >
                                <HelpCircle class="h-4 w-4" />
                                <span class="text-sm">Help & Support</span>
                            </Link>
                        </div>
                    </div>

                    <!-- System Preferences -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <Palette class="h-5 w-5 text-orange-600 mr-2" />
                            <h3 class="text-lg font-semibold text-gray-900">Preferences</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                                <select 
                                    v-model="preferences.theme"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                >
                                    <option value="light">Light</option>
                                    <option value="dark">Dark</option>
                                    <option value="auto">Auto</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                <select 
                                    v-model="preferences.language"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent text-sm"
                                >
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                </select>
                            </div>
                            <div>
                                <label class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Auto Save</span>
                                    <input 
                                        v-model="preferences.autoSave"
                                        type="checkbox" 
                                        class="h-4 w-4 text-orange-600 rounded focus:ring-orange-400"
                                    />
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button 
                                @click="updatePreferences"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors"
                            >
                                Save Preferences
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FacultyLayout>
</template>
