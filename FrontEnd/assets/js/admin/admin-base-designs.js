document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-button');
    const sidebar = document.querySelector('.sidebar');
    
    // Set sidebar as active by default (open on page load)
    if (sidebar && window.innerWidth > 768) {
        sidebar.classList.toggle('active');
    }
    
    // Desktop sidebar toggle
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Mobile menu toggle
    createMobileMenuToggle();
    
    const path = window.location.pathname;
    const page = path.split("/").pop();
    const viewSection = document.querySelector('.sections-ul');
    if(page == 'admin_view_section.php' && viewSection) {
        viewSection.classList.add('show');
        const section = viewSection.querySelector('span');
        if (section) {
            section.classList.add('active');
        }
    }
    
    //check the non drop down links
    let currentPath = window.location.pathname.toLowerCase();
    const adminNavigations = document.querySelectorAll('.admin-nav-links');

    adminNavigations.forEach(link=> {
        const linkPath = new URL(link.href).pathname.toLowerCase();
        if(linkPath == currentPath) {
            link.classList.toggle('active');
        }
    })
    
    //toggle dropdown for other sidebar components
    document.querySelectorAll('button.dropdown').forEach(button => {
        button.addEventListener('click', function() {
            const dropContent = this.parentElement.nextElementSibling;
            if (dropContent && dropContent.classList.contains('drop-content')) {
                dropContent.classList.toggle('show');
            } else {
                console.log("Normal button clicked");
            }
        });
    });
    
    // Handle window resize
    handleResponsiveMenu();
    window.addEventListener('resize', handleResponsiveMenu);
});

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
    
    // Close sidebar when clicking overlay
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