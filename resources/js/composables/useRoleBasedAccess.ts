import { computed } from 'vue';
import { useAuth } from './useAuth';

// Role permissions configuration
const ROLE_PERMISSIONS = {
    admin: [
        'view_dashboard',
        'view_users',
        'edit_users',
        'delete_users',
        'view_reports',
        'generate_reports',
        'manage_settings',
        'view_all_data'
    ],
    faculty: [
        'view_dashboard',
        'view_users',
        'edit_users',
        'view_reports',
        'generate_reports',
        'manage_settings'
    ],
    user: [
        'view_dashboard',
        'view_own_profile'
    ]
} as const;

// Route access control
const ROUTE_ACCESS = {
    '/faculty/dashboard': ['admin', 'faculty', 'user'],
    '/faculty/students': ['admin', 'faculty'],
    '/faculty/users': ['admin', 'faculty'],
    '/faculty/reports': ['admin', 'faculty'],
    '/faculty/settings': ['admin', 'faculty'],
    '/admin/dashboard': ['admin'],
    '/admin/users': ['admin'],
    '/admin/reports': ['admin']
} as const;

// Component access control
export const useRoleBasedAccess = () => {
    const { user, userRole } = useAuth();

    // Computed properties for role checks
    const isAdmin = computed(() => userRole.value === 'admin');
    const isFaculty = computed(() => userRole.value === 'faculty');
    const isUser = computed(() => userRole.value === 'user');
    const isLoggedIn = computed(() => !!user.value);

    // Permission checking
    const hasPermission = (permission: string) => {
        const role = userRole.value;
        if (!role) return false;
        return ROLE_PERMISSIONS[role as keyof typeof ROLE_PERMISSIONS]?.includes(permission as any) || false;
    };

    const hasAnyPermission = (permissions: string[]) => {
        return permissions.some(permission => hasPermission(permission));
    };

    const hasAllPermissions = (permissions: string[]) => {
        return permissions.every(permission => hasPermission(permission));
    };

    // Route access checking
    const canAccessRoute = (route: string) => {
        const role = userRole.value;
        const allowedRoles = ROUTE_ACCESS[route as keyof typeof ROUTE_ACCESS];
        return allowedRoles?.includes(role) || false;
    };

    const getAccessibleRoutes = () => {
        const role = userRole.value;
        return Object.entries(ROUTE_ACCESS)
            .filter(([_, allowedRoles]) => allowedRoles.includes(role))
            .map(([route]) => route);
    };

    // Navigation items based on role
    const getNavigationItems = () => {
        const role = userRole.value;
        
        const baseNavigation = [
            {
                title: 'Dashboard',
                href: '/faculty/dashboard',
                icon: 'LayoutDashboard',
                permission: 'view_dashboard'
            }
        ];

        if (role === 'admin') {
            return [
                ...baseNavigation,
                {
                    title: 'User Management',
                    href: '/admin/users',
                    icon: 'Users',
                    permission: 'view_users'
                },
                {
                    title: 'Reports',
                    href: '/admin/reports',
                    icon: 'FileText',
                    permission: 'view_reports'
                },
                {
                    title: 'Settings',
                    href: '/admin/settings',
                    icon: 'Settings',
                    permission: 'manage_settings'
                }
            ];
        }

        if (role === 'faculty') {
            return [
                ...baseNavigation,
                {
                    title: 'Users',
                    href: '/faculty/students',
                    icon: 'Users',
                    permission: 'view_users'
                },
                {
                    title: 'Reports',
                    href: '/faculty/reports',
                    icon: 'FileText',
                    permission: 'view_reports'
                },
                {
                    title: 'Settings',
                    href: '/faculty/settings',
                    icon: 'Settings',
                    permission: 'manage_settings'
                }
            ];
        }

        if (role === 'user') {
            return [
                ...baseNavigation,
                {
                    title: 'My Profile',
                    href: '/profile',
                    icon: 'User',
                    permission: 'view_own_profile'
                }
            ];
        }

        return baseNavigation;
    };

    // Component visibility helpers
    const canViewComponent = (requiredPermissions: string[]) => {
        return hasAnyPermission(requiredPermissions);
    };

    const canEditComponent = (requiredPermissions: string[]) => {
        return hasAllPermissions(requiredPermissions);
    };

    // Redirect helpers
    const redirectToLogin = () => {
        window.location.href = '/login';
    };

    const redirectToDashboard = () => {
        const role = userRole.value;
        if (role === 'admin') {
            window.location.href = '/admin/dashboard';
        } else if (role === 'faculty') {
            window.location.href = '/faculty/dashboard';
        } else {
            window.location.href = '/dashboard';
        }
    };

    const checkRouteAccess = (currentRoute: string) => {
        if (!isLoggedIn.value) {
            redirectToLogin();
            return false;
        }

        if (!canAccessRoute(currentRoute)) {
            console.warn(`Access denied to route: ${currentRoute} for role: ${userRole.value}`);
            redirectToDashboard();
            return false;
        }

        return true;
    };

    return {
        // Role checks
        isAdmin,
        isFaculty,
        isUser,
        isLoggedIn,
        
        // Permission checks
        hasPermission,
        hasAnyPermission,
        hasAllPermissions,
        
        // Route access
        canAccessRoute,
        getAccessibleRoutes,
        checkRouteAccess,
        
        // Navigation
        getNavigationItems,
        
        // Component access
        canViewComponent,
        canEditComponent,
        
        // Redirects
        redirectToLogin,
        redirectToDashboard
    };
};
