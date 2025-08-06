document.addEventListener('DOMContentLoaded', function(){
    const searchBox = document.getElementById('search');
    const  searchEnrollee = document.getElementById('query-table');
    const originalTable = searchEnrollee.innerHTML; 
    searchBox.addEventListener('keyup', function (){
        const query = this.value.trim();
        console.log(originalTable);
        if (!query) {
            searchEnrollee.innerHTML = originalTable;
        }
        else {
            fetch('../../../BackEnd/api/admin/fetchSearchEnrolleeView.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                query: query
            })
            })
            .then(response => response.text())
            .then(data=> {
                searchEnrollee.innerHTML = "";
                searchEnrollee.innerHTML = data;
            })
            .catch(error => console.error('Error loading data:', error))
            
        }
    });
});