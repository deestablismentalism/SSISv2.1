import{close,loadingText, modalHeader} from '../utils.js';
document.addEventListener('DOMContentLoaded', function(){
    const editAddressBtn = document.getElementById('edit-address');
    const editCredentialsBtn = document.getElementById('edit-credentials');
    const editPersonalInformationBtn = document.getElementById('edit-personal-information');
    const modal = document.getElementById('information-modal');
    const modalContent = document.getElementById('information-modal-content');
    
    console.log(modalContent);

    editAddressBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditAddress();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            
        }
        catch(error) {
            alert(error.message);
            console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })
    

    editCredentialsBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditIdentifiers();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            
        }
        catch(error) {
            alert(error.message);
            console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })

    editPersonalInformationBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditPersonalInformation();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            
        }
        catch(error) {
            alert(error.message);
            console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })
});


async function fetchEditIdentifiers() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditIdentifiers.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}

async function fetchEditAddress() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditAddress.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}

async function fetchEditPersonalInformation() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditPersonalInformation.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}