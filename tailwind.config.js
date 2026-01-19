/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.html.twig",
    "./assets/**/*.js",
    "!./public/build/**",
    "!./node_modules/**",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}