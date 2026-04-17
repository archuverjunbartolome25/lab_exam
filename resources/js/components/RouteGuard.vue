<script setup lang="ts">
import { onMounted, watch } from 'vue';
import { useRoleBasedAccess } from '@/composables/useRoleBasedAccess';

interface Props {
    requiredPermissions?: string[];
    requiredRole?: 'admin' | 'faculty' | 'user';
    fallbackRoute?: string;
}

const props = withDefaults(defineProps<Props>(), {
    requiredPermissions: () => [],
    fallbackRoute: '/dashboard'
});

const { checkRouteAccess, hasPermission, hasAnyPermission } = useRoleBasedAccess();

// Check access on component mount
onMounted(() => {
    const currentPath = window.location.pathname;
    
    // Check role-based route access
    if (!checkRouteAccess(currentPath)) {
        console.warn('Access denied: insufficient role permissions');
        return;
    }
    
    // Check specific permissions if required
    if (props.requiredPermissions.length > 0 && !hasAnyPermission(props.requiredPermissions)) {
        console.warn('Access denied: insufficient permissions', props.requiredPermissions);
        return;
    }
    
    // Check specific role if required
    if (props.requiredRole) {
        const { isAdmin, isFaculty, isUser } = useRoleBasedAccess();
        const hasRequiredRole = props.requiredRole === 'admin' && isAdmin.value ||
                              props.requiredRole === 'faculty' && isFaculty.value ||
                              props.requiredRole === 'user' && isUser.value;
        
        if (!hasRequiredRole) {
            console.warn('Access denied: insufficient role', props.requiredRole);
            return;
        }
    }
});

// Watch for route changes
watch(() => window.location.pathname, (newPath) => {
    checkRouteAccess(newPath);
});
</script>

<template>
    <div v-if="$slots.default">
        <slot />
    </div>
</template>
