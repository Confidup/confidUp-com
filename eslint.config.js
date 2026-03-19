export default [
  {
    files: ['wp-content/themes/confidup/assets/js/**/*.js', 'wp-content/plugins/confidup-core/**/*.js'],
    rules: {
      'no-unused-vars': 'warn',
      'no-console': 'warn',
      'eqeqeq': 'error',
    },
  },
];
