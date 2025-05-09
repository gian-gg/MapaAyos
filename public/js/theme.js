const themeIcon = document.getElementById('themeIcon');
const themeToggleBtn = document.getElementById('themeToggleBtn');

themeToggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark');

    if (document.body.classList.contains('dark')) {
        // Light → Dark: change icon to moon
        themeIcon.classList.remove('bi-brightness-high');
        themeIcon.classList.add('bi-moon');
    } else {
        // Dark → Light: change icon to sun
        themeIcon.classList.remove('bi-moon');
        themeIcon.classList.add('bi-brightness-high');
    }
});