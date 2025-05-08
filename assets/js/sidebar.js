/* for dashboard sidebar collapse */ 

document.addEventListener('DOMContentLoaded', function () {
    const toggleIcon = document.querySelector('.bi.bi-layout-sidebar');
    const dashboard = document.querySelector('.dashboard');
    const sidebar = document.querySelector('.sidebar');

    if (toggleIcon && dashboard && sidebar) {
        toggleIcon.style.cursor = 'pointer';

        // Disable transition temporarily
        dashboard.classList.add('no-transition');
        sidebar.classList.add('no-transition');

        // Apply saved sidebar state from localStorage
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            dashboard.classList.add('collapsed-sidebar');
            sidebar.classList.add('collapsed');
        } else {
            dashboard.classList.remove('collapsed-sidebar');
            sidebar.classList.remove('collapsed');
        }

        // Re-enable transition after applying state
        setTimeout(() => {
            dashboard.classList.remove('no-transition');
            sidebar.classList.remove('no-transition');
        }, 50);

        toggleIcon.addEventListener('click', function () {
            dashboard.classList.toggle('collapsed-sidebar');
            sidebar.classList.toggle('collapsed');

            // Save the current state to localStorage
            const isCollapsed = dashboard.classList.contains('collapsed-sidebar');
            localStorage.setItem('sidebarCollapsed', isCollapsed.toString());
        });
    }
});
