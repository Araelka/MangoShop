document.addEventListener('DOMContentLoaded', function () {
    const userMenuContainers = document.querySelectorAll('.user-menu-container');

    userMenuContainers.forEach(container => {
        const button = container.querySelector('.user-menu-button');
        const menu = container.querySelector('.user-dropdown-menu');
        let hideTimer = null;

        button.addEventListener('mouseenter', () => {
            clearTimeout(hideTimer);
            menu.style.display = 'block';
            menu.style.opacity = '1';
            menu.style.transform = 'translateY(0)';
        });

        menu.addEventListener('mouseenter', () => {
            clearTimeout(hideTimer);
        });

        const startHideTimer = () => {
            hideTimer = setTimeout(() => {
                menu.style.display = 'none';
                menu.style.opacity = '0';
                menu.style.transform = 'translateY(-10px)';
            }, 400); 
        };

        button.addEventListener('mouseleave', startHideTimer);
        menu.addEventListener('mouseleave', startHideTimer);
    });
});