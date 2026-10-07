import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ink: "#171A21",
                paper: "#FBFAF7",
                primary: { DEFAULT: "#1F3A5F", dark: "#142840" },
                accent: { DEFAULT: "#FF5A36", soft: "#FFE4DB" },
                line: "#E4E1D9",
                muted: "#6B6558",
                success: "#1F9D73",
                warning: "#D98C1F",
                danger: "#D93F3F",
            },
            borderRadius: { sm: "4px" },
        },
    },

    plugins: [forms],
};
