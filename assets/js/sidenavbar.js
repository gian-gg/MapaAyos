document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.hamburger'); // or use ID if needed
    const sidebar = document.querySelector('.mobile-sidebar');
  
    hamburger.addEventListener('click', function (e) {
      e.stopPropagation(); // Prevent click from bubbling
      sidebar.classList.toggle('active');
    });
  
    // Optional: click outside the sidebar to close it
    document.addEventListener('click', function (e) {
      if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });
  });
  