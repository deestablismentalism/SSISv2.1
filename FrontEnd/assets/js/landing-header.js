document.addEventListener('DOMContentLoaded', function() {
    // Normalize current path to lowercase
    let currentPath = window.location.pathname.toLowerCase();
    
    // Treat root path as '/index.php'
    if (currentPath === '/ssis/') {
        currentPath = '/ssis/index.php';
    }

    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        // Normalize link path to lowercase
        const linkPath = new URL(link.href).pathname.toLowerCase();

        if (linkPath === currentPath) {
            link.classList.add('active');
        }
    });
});