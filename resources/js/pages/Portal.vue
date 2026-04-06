<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="max-w-4xl w-full">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    CCS Comprehensive Profiling System
                </h1>
                <p class="text-lg text-gray-600">
                    Choose your portal to access the system
                </p>
            </div>

            <!-- Portal Cards -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Student Portal -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 hover:shadow-xl transition-shadow">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Student Portal</h2>
                        <p class="text-gray-600 mb-6">
                            View your academic records, grades, and schedule
                        </p>
                        <Link 
                            :href="route('student.dashboard')"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                        >
                            Enter Portal
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Faculty Portal -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 hover:shadow-xl transition-shadow">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Faculty Portal</h2>
                        <p class="text-gray-600 mb-6">
                            View student information, courses, and schedules
                        </p>
                        <Link 
                            :href="route('faculty.dashboard')"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Enter Portal
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Admin Portal -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 hover:shadow-xl transition-shadow">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Admin Portal</h2>
                        <p class="text-gray-600 mb-6">
                            Manage students, faculty, courses, and system settings
                        </p>
                        <Link 
                            :href="route('admin.login')"
                            class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors"
                        >
                            Login Required
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-12 text-gray-500 text-sm">
                <p>Student and Faculty portals are view-only for browsing and searching</p>
                <p class="mt-1">Admin portal requires authentication for full CRUD operations</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
