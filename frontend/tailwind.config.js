/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            colors: {
                canvas: '#FAFAF9',
                surface: '#FFFFFF',
                'border': '#E5E7EB',
                accent: {
                    DEFAULT: '#F59E0B',
                    hover: '#D97706',
                    light: '#FEF3C7'
                },
                cyber: {
                    DEFAULT: '#1F2937',
                    light: '#374151',
                },
                status: {
                    in: '#10B981',
                    low: '#F59E0B',
                    out: '#EF4444',
                },
                foreground: '#1F2937',
                muted: '#78716C',
                'slate': {
                    950: '#1C1917',
                    900: '#292524',
                    800: '#E7E5E4',
                    500: '#78716C',
                    100: '#F5F5F4',
                },
                'indigo': {
                    500: '#F59E0B',
                    700: '#D97706',
                },
                'cyan': {
                    400: '#1F2937',
                    300: '#374151',
                },
                'emerald': {
                    400: '#10B981',
                },
                'amber': {
                    300: '#F59E0B',
                },
                'rose': {
                    400: '#EF4444',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Geist', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
