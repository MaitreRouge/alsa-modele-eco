/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.{php,html}", "./app/**/*.{php,html}"],
    safelist: [
        {
            pattern: /bg-+/, // You can display all the colors that you need
            // variants: ['lg', 'hover', 'focus', 'lg:hover'],      // Optional
        },
        {
            pattern: /text-+/, // You can display all the colors that you need
            // variants: ['lg', 'hover', 'focus', 'lg:hover'],      // Optional
        },
        {
            pattern: /fill-+/, // You can display all the colors that you need
            // variants: ['lg', 'hover', 'focus', 'lg:hover'],      // Optional
        },
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms')
    ],
}

