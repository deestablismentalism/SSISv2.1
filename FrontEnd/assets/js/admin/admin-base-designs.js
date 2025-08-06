document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-button');
    const sidebar = document.querySelector('.sidebar');
    sidebarToggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });

    //check the non drop down links
    let currentPath = window.location.pathname.toLowerCase();
    const adminNavigations = document.querySelectorAll('admin-nav-links');
    console.log(adminNavigations);

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