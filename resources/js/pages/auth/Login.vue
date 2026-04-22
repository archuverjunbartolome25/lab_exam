<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Eye, EyeOff, User, Mail, Lock, AlertCircle, CheckCircle, ArrowLeft } from 'lucide-vue-next';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
    canRegister?: boolean;
}>();

// Form state
const showPassword = ref(false);
const processing = ref(false);

// Form data
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Validation errors
const errors = computed(() => ({
    email: form.errors.email,
    password: form.errors.password,
}));

// Submit handler
const submit = () => {
    processing.value = true;
    form.post('/login', {
        onFinish: () => {
            processing.value = false;
        },
    });
};

// Toggle password visibility
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

// Get input type for password field
const passwordType = computed(() => showPassword.value ? 'text' : 'password');
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- User Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                    <User class="h-8 w-8 text-white" />
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    User Portal
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Sign in to access your account
                </p>
            </div>
            
            <!-- Login Form -->
            <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
                <!-- Status Message -->
                <div v-if="status" class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <CheckCircle class="h-5 w-5 text-green-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ status }}
                            </p>
                        </div>
                    </div>
                </div>

                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-4">
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Mail class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autocomplete="email"
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-3 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400',
                                        form.errors.email ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-blue-500'
                                    ]"
                                    placeholder="user@example.com"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                {{ form.errors.email }}
                            </p>
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Lock class="h-5 w-5 text-gray-400" />
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="passwordType"
                                    required
                                    autocomplete="current-password"
                                    :class="[
                                        'appearance-none relative block w-full pl-10 pr-10 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400',
                                        form.errors.password ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 text-gray-900 focus:border-blue-500'
                                    ]"
                                    placeholder="Enter your password"
                                />
                                <button
                                    type="button"
                                    @click="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <Eye v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                    <EyeOff v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                                {{ form.errors.password }}
                            </p>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Remember me
                            </label>
                        </div>
                        <Link
                            v-if="canResetPassword"
                            href="/forgot-password"
                            class="text-sm text-blue-600 hover:text-blue-500 transition-colors"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            :disabled="processing"
                            :class="[
                                'group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white transform transition-all duration-200 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2',
                                processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 focus:ring-blue-500'
                            ]"
                        >
                            <span class="flex items-center">
                                <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <User v-else class="w-5 h-5 mr-2" />
                                {{ processing ? 'Signing in...' : 'Sign In' }}
                            </span>
                        </button>
                    </div>
                </form>
                
                <!-- Register Link -->
                <div v-if="canRegister" class="text-center pt-4 border-t border-gray-100">
                    <Link href="/register" class="inline-flex items-center text-sm text-gray-600 hover:text-blue-600 transition-colors">
                        Don't have an account? Sign up
                    </Link>
                </div>
                
                <!-- Admin Login Link -->
                <div class="text-center">
                    <Link href="/admin/login" class="inline-flex items-center text-sm text-gray-600 hover:text-orange-600 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-1" />
                        Admin Login
                    </Link>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    <User class="inline w-4 h-4 mr-1" />
                    Secure user access - Protected by authentication
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.min-h-screen {
    animation: fadeIn 0.5s ease-out;
}
</style>
