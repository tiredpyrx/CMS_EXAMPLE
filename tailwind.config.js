/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    // darkMode: ['class', 'selector'],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                    800: "#1e40af",
                    900: "#1e3a8a",
                    950: "#172554",
                },
                secondary: {
                    50: "#85d9a1",
                    100: "#73d393",
                    200: "#62ce86",
                    300: "#50c878",
                    400: "#48b46c",
                    500: "#40a060",
                    600: "#388c54",
                    700: "#307848",
                    800: "#28643c",
                    900: "#205030",
                    950: "#183c24",
                },
            },
        },
    },
    plugins: [],
};
