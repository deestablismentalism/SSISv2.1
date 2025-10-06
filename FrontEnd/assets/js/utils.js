export function close(modal) { // global close util
    const closeButton = document.querySelector('.close');

    if(closeButton) {
        closeButton.addEventListener('click', function(){
        modal.style.display = 'none';
    })
    }
}
export const loadingText = `<div class="loading"></div>`; //global loading pop up modal

export function modalHeader(backButton = false) {
    let hasBackButton = backButton === true ? '<button class="back-button"> <img src="../../assets/imgs/arrow-left-solid.svg"> </button>' : '';
    const headerContent = `<div class="modal-header">${hasBackButton}<span class="close">&times;</span> </div><br>`;   
    return headerContent;
}
export function sanitize(str) {
    const content = str.textContent
    return content
        .replace(/&/g, '&amp;')
        .replace(/</g, '$lt;')
        .replace(/>/g, ' &gt;')
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}