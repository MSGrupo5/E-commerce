import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

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
                oxanium: ["Oxanium", "sans-serif"],
                jakarta: ["Plus Jakarta Sans", "sans-serif"],
            },
            colors: {
                background: "#0b0b0f",
                surface: "#141419",
                border: "#1e1e2a",
                primary: "#6c63ff",
                accent: "#00d4ff",
                text: "#f1f5f9",
                muted: "#94a3b8",
                success: "#10b981",
                error: "#ef4444",
                warning: "#f59e0b",
            },
            fontSize: {
                h1: "40px",
                h2: "32px",
                h3: "24px",
                h4: "20px",
                h5: "16px",
                h6: "14px",
                body: "16px",
                small: "13px",
                label: "12px",
            },
        },
    },

    plugins: [forms],
};
