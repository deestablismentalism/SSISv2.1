export function close(modal) {
    const closeButton = document.querySelector('.close');

    closeButton.addEventListener('click', function(){
        modal.style.display = 'none';
    })
}