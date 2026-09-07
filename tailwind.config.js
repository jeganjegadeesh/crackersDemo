/** @type {import("tailwindcss").Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        maroon: {
          50: "#fbeeec",
          100: "#f5d6d0",
          600: "#8a2a24",
          700: "#7a1f1f",
          800: "#5c1717",
          900: "#3a1a1a",
        },
      },
    },
  },
  plugins: [],
};
