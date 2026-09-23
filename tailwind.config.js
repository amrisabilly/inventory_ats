/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#1E3A5F',
          light: '#2C5282',
          dark: '#152B47',
        },
        secondary: '#8C6A4E',
        surface: '#F7F8FA',
        'surface-card': '#FFFFFF',
        border: '#E2E5E9',
        'text-primary': '#1A202C',
        'text-secondary': '#64748B',
        success: '#2F855A',
        warning: '#C05621',
        danger: '#C53030',
        info: '#2B6CB0',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        panel: '0 1px 3px rgba(26, 32, 44, 0.08), 0 1px 2px rgba(26, 32, 44, 0.04)',
      },
    },
  },
  plugins: [],
}