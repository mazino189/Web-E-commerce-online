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
                    DEFAULT: '#6366F1',
                    hover: '#4338CA',
                },
                cyber: {
                    DEFAULT: '#22D3EE',
                    light: '#67E8F9',
                },
                status: {
                    in: '#34D399',
                    low: '#FCD34D',
                    out: '#FB7185',
                },
                foreground: '#F1F5F9',
                muted: '#64748B',
                'slate': {
                    950: '#020617',
                    900: '#0F172A',
                    800: '#1E293B',
                    500: '#64748B',
                    100: '#F1F5F9',
                },
                'indigo': {
                    500: '#6366F1',
                    700: '#4338CA',
                },
                'cyan': {
                    400: '#22D3EE',
                    300: '#67E8F9',
                },
                'emerald': {
                    400: '#34D399',
                },
                'amber': {
                    300: '#FCD34D',
                },
                'rose': {
                    400: '#FB7185',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Geist', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
