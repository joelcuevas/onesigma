/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Noto Sans', 'ui-sans-serif', 'system-ui'],
        'serif': ['Noto Serif', 'ui-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

