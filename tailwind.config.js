/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      backgroundColor: {
        'custom-brown': '#876445'
      },
      textColor: {
        'custom-beige': '#F9E0BB'
      },
      borderColor: {
        'custom-brown': '#876445'
      }
    },
  },
  plugins: [],
}

