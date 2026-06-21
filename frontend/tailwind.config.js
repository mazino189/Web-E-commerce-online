/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            colors: {
                canvas: '#020617',
                surface: '#0F172A',
                'border': '#1E293B',
                accent: {
                    DEFAULT: '#06B6D4',
                    hover: '#0891B2',
                    light: '#164E63'
                },
                cyber: {
                    DEFAULT: '#06B6D4',
                    light: '#22D3EE',
                },
                status: {
                    in: '#10B981',
                    low: '#F59E0B',
                    out: '#EF4444',
                },
                foreground: '#F1F5F9',
                muted: '#94A3B8',
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
