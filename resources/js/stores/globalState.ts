// =====================================================
// PART 4: GLOBAL STATE MANAGEMENT (FERRIOL)
// =====================================================
// Create a global state for:
// Logged-in user (Admin/User) 
// System theme OR access role 
// Requirement:
// Show logged-in user name in all pages

import { reactive, computed, readonly } from 'vue';

// TypeScript interfaces
interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'faculty' | 'user';
    avatar?: string;
    department?: string | null;
}

interface GlobalState {
    user: User | null;
    theme: 'light' | 'dark' | 'system';
    isLoading: boolean;
    notifications: Array<{
        id: number;
        message: string;
        type: 'success' | 'error' | 'warning' | 'info';
        timestamp: Date;
    }>;
}

// Create reactive global state (Single Source of Truth)
const state = reactive<GlobalState>({
    // Logged-in user data - persisted across all pages
    user: null,
    // System theme setting - light/dark/system
    theme: 'system',
    // Global loading state
    isLoading: false,
    // Global notifications array
    notifications: []
});

// Computed properties
const isLoggedIn = computed(() => state.user !== null);
const userRole = computed(() => state.user?.role || 'user');
const userName = computed(() => state.user?.name || 'Guest');
const userInitials = computed(() => {
    if (!state.user?.name) return 'G';
    return state.user.name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
});

// Theme management
const currentTheme = computed(() => {
    if (state.theme === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return state.theme;
});

// Actions
const actions = {
    // User management
    setUser: (user: User | null) => {
        state.user = user;
        // Persist to localStorage
        if (user) {
            localStorage.setItem('user', JSON.stringify(user));
        } else {
            localStorage.removeItem('user');
        }
    },

    updateUser: (userData: Partial<User>) => {
        if (state.user) {
            state.user = { ...state.user, ...userData };
            localStorage.setItem('user', JSON.stringify(state.user));
        }
    },

    // Role management
    setRole: (role: 'admin' | 'faculty' | 'user') => {
        if (state.user) {
            state.user.role = role;
            localStorage.setItem('user', JSON.stringify(state.user));
        }
    },

    upgradeRole: (newRole: 'admin' | 'faculty' | 'user') => {
        if (state.user) {
            // Only allow role upgrades (user → faculty → admin)
            const roleHierarchy = { user: 0, faculty: 1, admin: 2 };
            const currentLevel = roleHierarchy[state.user.role] || 0;
            const newLevel = roleHierarchy[newRole] || 0;
            
            if (newLevel >= currentLevel) {
                state.user.role = newRole;
                localStorage.setItem('user', JSON.stringify(state.user));
                actions.addNotification(`Role upgraded to ${newRole}`, 'success');
            } else {
                actions.addNotification('Cannot downgrade role', 'error');
            }
        }
    },

    logout: () => {
        state.user = null;
        state.notifications = [];
        localStorage.removeItem('user');
        localStorage.removeItem('auth_token');
    },

    // Theme management
    setTheme: (theme: 'light' | 'dark' | 'system') => {
        state.theme = theme;
        localStorage.setItem('theme', theme);
        applyTheme(theme);
    },

    toggleTheme: () => {
        const newTheme = state.theme === 'light' ? 'dark' : 
                        state.theme === 'dark' ? 'system' : 'light';
        actions.setTheme(newTheme);
    },

    // Loading state
    setLoading: (loading: boolean) => {
        state.isLoading = loading;
    },

    // Notifications
    addNotification: (message: string, type: 'success' | 'error' | 'warning' | 'info' = 'info') => {
        const notification = {
            id: Date.now(),
            message,
            type,
            timestamp: new Date()
        };
        state.notifications.push(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            actions.removeNotification(notification.id);
        }, 5000);
    },

    removeNotification: (id: number) => {
        const index = state.notifications.findIndex(n => n.id === id);
        if (index > -1) {
            state.notifications.splice(index, 1);
        }
    },

    clearNotifications: () => {
        state.notifications = [];
    }
};

// Helper function to apply theme
function applyTheme(theme: 'light' | 'dark' | 'system') {
    const html = document.documentElement;
    const actualTheme = theme === 'system' ? 
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : 
        theme;
    
    if (actualTheme === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
}

// Initialize from localStorage and Inertia page props
const initialize = () => {
    // Load user from localStorage
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
        try {
            state.user = JSON.parse(savedUser);
        } catch (error) {
            console.error('Failed to parse saved user:', error);
            localStorage.removeItem('user');
        }
    }

    // Load theme from localStorage
    const savedTheme = localStorage.getItem('theme') as 'light' | 'dark' | 'system';
    if (savedTheme) {
        state.theme = savedTheme;
    }
    
    applyTheme(state.theme);

    // Listen for system theme changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (state.theme === 'system') {
                applyTheme('system');
            }
        });
    }
};

// Function to sync with Inertia page props
export const syncWithPageProps = (pageProps: any) => {
    if (pageProps.auth?.user) {
        const authUser = pageProps.auth.user;
        const formattedUser: User = {
            id: authUser.id,
            name: `${authUser.fname || ''} ${authUser.lname || ''}`.trim() || 'User',
            email: authUser.email,
            role: authUser.role,
            department: authUser.department || null,
        };
        
        state.user = formattedUser;
        localStorage.setItem('user', JSON.stringify(formattedUser));
    }
};

// Initialize on import
initialize();

// Export state and actions
export const useGlobalState = () => ({
    state: readonly(state),
    isLoggedIn,
    userRole,
    userName,
    userInitials,
    currentTheme,
    ...actions
});

// Export individual composables
export const useAuth = () => ({
    user: readonly(state).user,
    isLoggedIn,
    userRole,
    userName,
    userInitials,
    login: actions.setUser,
    logout: actions.logout,
    updateUser: actions.updateUser
});

export const useTheme = () => ({
    theme: readonly(state).theme,
    currentTheme,
    setTheme: actions.setTheme,
    toggleTheme: actions.toggleTheme
});

export const useNotifications = () => ({
    notifications: readonly(state).notifications,
    add: actions.addNotification,
    remove: actions.removeNotification,
    clear: actions.clearNotifications
});
