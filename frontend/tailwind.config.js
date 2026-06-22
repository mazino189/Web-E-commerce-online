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
            },
            fontFamily: {
                sans: ['Inter', 'Geist', 'system-ui', 'sans-serif'],
                mono: ['JetBrains Mono', 'Fira Code', 'monospace'],
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'typing': 'typing 1.2s steps(3, end) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                typing: {
                    '0%, 100%': { opacity: '0.2' },
                    '50%': { opacity: '1' },
                },
            },
        },
    },
    plugins: [],
}
