module.exports = {
  content: [
    "./assets/**/*.{css,js}",
    "./templates/**/*.html.twig",
    "./src/Form/Type/*Type.php",
    "vendor/symfony/twig-bridge/Resources/views/Form/tailwind_2_layout.html.twig"
  ],
  theme: {
    extend: {
      keyframes: {
        'fade-in': {
          '0%': {
            opacity: '0',
            transform: 'translateY(-10px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)'
          },
        },
        'fade-out': {
          'from': {
              opacity: '1',
              transform: 'translateY(0px)'
          },
          'to': {
              opacity: '0',
              transform: 'translateY(10px)'
          },
      },
      },
      animation: {
        'fade-in': 'fade-in 0.15s ease-in',
        'fade-out': 'fade-out 0.15s ease-out',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
