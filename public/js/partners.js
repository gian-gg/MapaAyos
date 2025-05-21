    document.addEventListener('DOMContentLoaded', () => {
        const scrollingContent = document.querySelector('.scrolling-content');
        if (!scrollingContent) return;

        const logosArray = Array.from(scrollingContent.children);
        const cloneCount = 2; // Create 2 full copies for looping

        for (let i = 0; i < cloneCount; i++) {
        logosArray.forEach(logo => {
            const clone = logo.cloneNode(true);
            scrollingContent.appendChild(clone);
        });
        }

        const container = document.querySelector('.scrolling-wrapper');
        let scrollAmount = 0;
        const speed = 0.8;

        function scrollLogos() {
        scrollAmount += speed;
        // Reset when scrolled past the width of the original set
        if (scrollAmount >= scrollingContent.scrollWidth / (cloneCount + 1)) {
            scrollAmount = 0;
        }
        container.scrollLeft = scrollAmount;
        requestAnimationFrame(scrollLogos);
        }

        requestAnimationFrame(scrollLogos);
    });