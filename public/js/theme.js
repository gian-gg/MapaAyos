document.addEventListener('DOMContentLoaded', () => {
    const themeIcon = document.getElementById('themeIcon');
    const themeToggleBtn = document.getElementById('themeToggleBtn');

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark');
        themeIcon.classList.remove('bi-brightness-high');
        themeIcon.classList.add('bi-moon');
    } else {
        document.body.classList.remove('dark');
        themeIcon.classList.remove('bi-moon');
        themeIcon.classList.add('bi-brightness-high');
    }

    themeToggleBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark');

        if (document.body.classList.contains('dark')) {
            themeIcon.classList.remove('bi-brightness-high');
            themeIcon.classList.add('bi-moon');
            localStorage.setItem('theme', 'dark');
        } else {
            themeIcon.classList.remove('bi-moon');
            themeIcon.classList.add('bi-brightness-high');
            localStorage.setItem('theme', 'light');
        }
    });
});