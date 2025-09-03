document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-button');
    const sidebar = document.querySelector('.sidebar');
    sidebarToggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
    const path = window.location.pathname;
    const page = path.split("/").pop();
    const viewSection = document.querySelector('.sections-ul');
    if(page == 'admin_view_section.php') {
        viewSection.classList.add('show');
        const section = viewSection.querySelector('span');
        section.classList.add('active');
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
    //toggle dropw down for other sidebar components
    document.querySelectorAll('button.dropdown').forEach(button => {
        button.addEventListener('click', function() {
            const dropContent = this.parentElement.nextElementSibling; // or just `this`
            if (dropContent.classList.contains('drop-content')) {
                dropContent.classList.toggle('show');
                // Your dropdown logic here
            } else {
                console.log("Normal button clicked");
            }
        });
    });
});