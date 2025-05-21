document.addEventListener('DOMContentLoaded', function () {

  var myOffcanvas = document.getElementById('mobileSidebar');
  var offcanvas = new bootstrap.Offcanvas(myOffcanvas);

  const hamburger = document.querySelector('.hamburger');
  hamburger.addEventListener('click', function (e) {
    e.stopPropagation(); 
    offcanvas.show(); 
  });

  document.addEventListener('click', function (e) {
    if (!myOffcanvas.contains(e.target) && !hamburger.contains(e.target)) {
      offcanvas.hide(); 
    }
  });

  const navLinks = document.querySelectorAll('.offcanvas-body .nav-link');
  navLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
      setTimeout(function() {
        offcanvas.hide();
      }, 200);
    });
  });
});
