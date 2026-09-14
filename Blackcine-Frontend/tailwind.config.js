/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.html",
    "./assets/js/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#E50914',
        secondary: '#141414',
        accent: '#FF6B6B',
        gold: '#FFD700',
      },
      borderRadius: {
        'xl': '12px',
        '2xl': '20px',
      },
      boxShadow: {
        'glow': '0 0 30px rgba(229, 9, 20, 0.3)',
        'glow-lg': '0 0 60px rgba(229, 9, 20, 0.4)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'pulse-slow': 'pulse 3s infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: ["dark"],
  },
}
