export function close(modal) { // global close util
    const closeButton = document.querySelector('.close');

    closeButton.addEventListener('click', function(){
        modal.style.display = 'none';
    })
}
export const loadingText = `<div class="loading"></div>`; //global loading pop up modal