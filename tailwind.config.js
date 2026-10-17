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
                sans: ['Inter Variable', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                surface: '#FFFFFF',   // area kerja & tabel
                card: '#F1F5F9',      // sidebar & card
                ink: '#1E293B',       // teks
                brand: {
                    DEFAULT: '#0D9488', // CTA
                    dark: '#0F766E',    // hover & teks link
                },
            },
        },
    },

    plugins: [forms],
};
