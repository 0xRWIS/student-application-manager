/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",              // Files in the root
    "./src/**/*.php",       // Files in src folder
    "./templates/**/*.php", // Files in templates folder
    "./auth/**/*.php",      // Files in auth folder
  ],
  // ... rest of config
}