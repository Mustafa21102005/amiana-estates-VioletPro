import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "selector",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            colors: {
                navy: "rgb(var(--navy) / <alpha-value>)",
                gold: "rgb(var(--gold) / <alpha-value>)",
                ivory: "rgb(var(--ivory) / <alpha-value>)",
                mist: "rgb(var(--mist) / <alpha-value>)",
                charcoal: "rgb(var(--charcoal) / <alpha-value>)",
            },
            fontFamily: {
                serif: [
                    '"Cormorant Garamond"',
                    ...defaultTheme.fontFamily.serif,
                ],
                sans: ["Montserrat", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
