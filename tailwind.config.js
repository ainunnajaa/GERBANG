import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                '15': '3.75rem', // custom height between h-14 (3.5rem) and h-16 (4rem)
            },
            colors: {
                'primary-blue': '#0000F4',
                'primary-blue-dark': '#0000D4',
            },
        },
    },

    plugins: [forms],
};
