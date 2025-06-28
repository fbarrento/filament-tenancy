import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
      './resources/views/**/*.blade.php'
  ],
  theme: {
    extend: {},
  },
  plugins: [forms, typography],
}

