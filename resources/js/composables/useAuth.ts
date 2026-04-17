import { useAuth as useGlobalAuth } from '@/stores/globalState';

// Re-export auth composable for easier access
export const useAuth = () => {
    const {
        user,
        isLoggedIn,
        userRole,
        userName,
        userInitials,
        login,
        logout,
        updateUser
    } = useGlobalAuth();

    return {
        user,
        isLoggedIn,
        userRole,
        userName,
        userInitials,
        login,
        logout,
        updateUser
    };
};
