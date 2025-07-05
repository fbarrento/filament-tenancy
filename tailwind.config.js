import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
  content: [
      './vendor/filament/**/*.blade.php',
      './resources/views/**/*.blade.php',
      './resources/js/**/*.js',
      './app/Filament/**/*.php',
      './plugins/tenantforge/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [forms, typography],
}
