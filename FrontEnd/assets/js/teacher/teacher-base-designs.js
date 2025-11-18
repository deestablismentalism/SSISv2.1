document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-button');
    const sidebar = document.querySelector('.sidebar');
    
    // Set sidebar as active by default on desktop
    if (sidebar && window.innerWidth > 768) {
        sidebar.classList.add('active');
    }
    
    // Desktop sidebar toggle
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Mobile menu toggle
    createMobileMenuToggle();
    
    // Highlight active navigation link
    let currentPath = window.location.pathname.toLowerCase();
    const teacherNavigations = document.querySelectorAll('.teacher-nav-links');

    teacherNavigations.forEach(link => {
        const linkPath = new URL(link.href).pathname.toLowerCase();
        if(linkPath === currentPath) {
            link.classList.add('active');
        }
    });
    
    // Handle window resize
    handleResponsiveMenu();
    window.addEventListener('resize', handleResponsiveMenu);
});

function accountDrop() {
    var account = document.querySelector('.account-settings-btn-content-wrapper');
    account.classList.toggle('show');
}

function createMobileMenuToggle() {
    // Check if button already exists
    if (document.querySelector('.mobile-menu-toggle')) {
        return;
    }
    
    // Create mobile menu toggle button
    const mobileToggle = document.createElement('button');
    mobileToggle.className = 'mobile-menu-toggle';
    mobileToggle.setAttribute('aria-label', 'Toggle menu');
    mobileToggle.innerHTML = '<span></span>';
    
    document.body.appendChild(mobileToggle);
    
    const sidebar = document.querySelector('.sidebar');
    
    mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        mobileToggle.classList.toggle('active');
        document.body.classList.toggle('sidebar-open');
    });
    
    // Close sidebar when clicking outside
    document.body.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            sidebar.classList.contains('active') && 
            !sidebar.contains(e.target) && 
            !mobileToggle.contains(e.target)) {
            sidebar.classList.remove('active');
            mobileToggle.classList.remove('active');
            document.body.classList.remove('sidebar-open');
        }
    });
    
    // Close sidebar when clicking links (mobile only)
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    sidebar.classList.remove('active');
                    mobileToggle.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                }, 200);
            }
        });
    });
}

function handleResponsiveMenu() {
    const sidebar = document.querySelector('.sidebar');
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    
    if (window.innerWidth > 768) {
        // Desktop mode - keep sidebar open
        if (sidebar && !sidebar.classList.contains('active')) {
            sidebar.classList.add('active');
        }
        if (mobileToggle) {
            mobileToggle.classList.remove('active');
        }
        document.body.classList.remove('sidebar-open');
    } else {
        // Mobile mode - start closed
        if (sidebar) {
            sidebar.classList.remove('active');
        }
    }
}
