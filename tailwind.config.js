module.exports = {
  content: [
    "./assets/**/*.{css,js}",
    "./templates/**/*.html.twig",
    "./src/Form/Type/*Type.php",
    "vendor/symfony/twig-bridge/Resources/views/Form/tailwind_2_layout.html.twig"
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
