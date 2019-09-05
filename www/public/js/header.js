// Execute all code only after DOM content is loaded
window.addEventListener('DOMContentLoaded', function() {
  createLogoClickListener();
});

function createLogoClickListener() {
  var logoContainerEl = document.querySelector('.logo-container');

  logoContainerEl.addEventListener('click', function() {
    // Redirect to dashboard (or login page if not logged in, which is handled by serverside routing)
    window.location.replace('/dashboard');
  });
}
