document.addEventListener('DOMContentLoaded', function() {
    const adminSectionsTable = document.getElementById('admin-sections-table');
    console.log(adminSectionsTable);

    adminSectionsTable.addEventListener('click', function(e) {
        const sectionRow = e.target.closest('.section-row');
        const id = sectionRow.dataset.id;

        window.location = './admin_view_section.php?id=' + encodeURIComponent(id);
    });
});