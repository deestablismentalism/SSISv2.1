document.addEventListener('DOMContentLoaded', function() {
    // Active navigation link
    const currentPath = window.location.pathname.toLowerCase();
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link=> {
        const linkPath = new URL(link.href).pathname.toLowerCase();
        if(linkPath === currentPath) {
            link.classList.add('active');
        }
    })
    
    // Account dropdown
    const button = document.getElementById('account-drop');
    const dropDown = document.querySelector('.account-settings-btn-content-wrapper');
    button.addEventListener('click', function(event) {
        event.stopPropagation();
       dropDown.classList.toggle('show');
    });
    dropDown.addEventListener('click', function(event) {
        event.stopPropagation();
    });
    document.addEventListener('click', function() {
        dropDown.classList.remove('show');
    });

    // Hamburger menu functionality (Mobile only)
    const hamburgerMenu = document.getElementById('hamburger-menu');
    const navContainer = document.getElementById('nav-container');
    const navOverlay = document.getElementById('nav-overlay');
    const body = document.body;

    if (hamburgerMenu && navContainer && navOverlay) {
        // Toggle menu on hamburger click
        hamburgerMenu.addEventListener('click', function(event) {
            event.stopPropagation();
            toggleMobileMenu();
        });

        // Close menu when clicking overlay
        navOverlay.addEventListener('click', function() {
            closeMobileMenu();
        });

        // Close menu when clicking nav links (mobile)
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 767) {
                    closeMobileMenu();
                }
            });
        });

        // Close menu on window resize if screen becomes larger
        window.addEventListener('resize', function() {
            if (window.innerWidth > 767) {
                closeMobileMenu();
            }
        });

        // Prevent body scroll when menu is open
        function toggleMobileMenu() {
            hamburgerMenu.classList.toggle('active');
            navContainer.classList.toggle('active');
            navOverlay.classList.toggle('active');
            body.style.overflow = navContainer.classList.contains('active') ? 'hidden' : '';
        }

        function closeMobileMenu() {
            hamburgerMenu.classList.remove('active');
            navContainer.classList.remove('active');
            navOverlay.classList.remove('active');
            body.style.overflow = '';
        }

        // Close menu on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navContainer.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    }
});

