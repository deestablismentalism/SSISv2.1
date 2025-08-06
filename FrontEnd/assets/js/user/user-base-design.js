document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname.toLowerCase();
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link=> {
        const linkPath = new URL(link.href).pathname.toLowerCase();
        if(linkPath === currentPath) {
            link.classList.add('active');
        }
    })
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


});