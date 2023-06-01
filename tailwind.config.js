/** @type {import('tailwindcss').Config} */
export default {
  content: ["./resources/**/*.{php,html}", "./app/**/*.{php,html}"],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms')
  ],
}

