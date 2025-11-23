import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Plus Jakarta Sans', 'SF Pro Display', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'pastel-blue': {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                },
                'soft-gray': {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                },
            },
            boxShadow: {
                'neumorphism': '8px 8px 16px #d1d5db, -8px -8px 16px #ffffff',
                'neumorphism-sm': '4px 4px 8px #d1d5db, -4px -4px 8px #ffffff',
                'neumorphism-lg': '12px 12px 24px rgba(0, 0, 0, 0.08), -12px -12px 24px rgba(255, 255, 255, 0.8)',
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [forms],
};
