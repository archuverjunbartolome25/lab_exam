import { useTheme as useGlobalTheme } from '@/stores/globalState';

// Re-export theme composable for easier access
export const useTheme = () => {
    const {
        theme,
        currentTheme,
        setTheme,
        toggleTheme
    } = useGlobalTheme();

    return {
        theme,
        currentTheme,
        setTheme,
        toggleTheme
    };
};
