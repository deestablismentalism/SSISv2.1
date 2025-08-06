function menu()
{
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');

    if(!sidebar.classList.contains('active'))
    {
        document.querySelector('.dashboard-ul').classList.remove('active');
        document.querySelector('.subjects-ul').classList.remove('active');
        document.querySelector('.teachers-ul').classList.remove('active');
    }
}

function dashboarddrop()
{
    var dashboard = document.querySelector('.dashboard-ul');
    dashboard.classList.toggle('active');

    if (!document.querySelector('.sidebar').classList.contains('active'))
    {
        dashboard.classList.remove('active');
    }
}
function accountDrop() {
    var account = document.querySelector('.account-settings-btn-content-wrapper');
    account.classList.toggle('show');
}
document.addEventListener('DOMContentLoaded', function() {
    
});
