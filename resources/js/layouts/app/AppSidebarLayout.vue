<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <!-- Navigation Header -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')" class="text-xl font-bold text-gray-800 dark:text-white">
                                Lab Exam
                            </Link>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- User menu -->
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" class="flex items-center space-x-2">
                                    <Avatar class="h-8 w-8">
                                        <AvatarImage v-if="user?.avatar" :src="user.avatar" :alt="user?.name" />
                                        <AvatarFallback>{{ user?.name?.charAt(0) }}</AvatarFallback>
                                    </Avatar>
                                    <span class="hidden sm:block">{{ user?.name }}</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')">Profile</Link>
                                </DropdownMenuItem>
                                <DropdownMenuItem as-child>
                                    <Link :href="route('logout')" method="post">Logout</Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            <div class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 min-h-screen">
                <nav class="mt-8">
                    <div class="px-4 space-y-2">
                        <Link
                            :href="route('dashboard')"
                            :class="[
                                'flex items-center px-4 py-2 text-sm font-medium rounded-md',
                                page.url === route('dashboard')
                                    ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
                                    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'
                            ]"
                        >
                            <Home class="mr-3 h-5 w-5" />
                            Dashboard
                        </Link>

                        <!-- Add more navigation items as needed -->
                    </div>
                </nav>
            </div>

            <!-- Main content -->
            <div class="flex-1">
                <!-- Breadcrumbs -->
                <div v-if="breadcrumbs.length > 0" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <Breadcrumb>
                            <BreadcrumbList>
                                <BreadcrumbItem v-for="(breadcrumb, index) in breadcrumbs" :key="index">
                                    <BreadcrumbLink v-if="breadcrumb.url" :href="breadcrumb.url">
                                        {{ breadcrumb.title }}
                                    </BreadcrumbLink>
                                    <BreadcrumbPage v-else>
                                        {{ breadcrumb.title }}
                                    </BreadcrumbPage>
                                </BreadcrumbItem>
                            </BreadcrumbList>
                        </Breadcrumb>
                    </div>
                </div>

                <!-- Page content -->
                <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Home } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbPage } from '@/components/ui/breadcrumb';

type BreadcrumbItem = {
    title: string;
    url?: string;
};

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const user = computed(() => (page.props.auth as any).user);
</script>