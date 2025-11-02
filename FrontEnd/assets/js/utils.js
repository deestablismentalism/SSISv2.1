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
export function generateOptions(data,selectContainer) {
    if(data) {
        data.forEach(data=>{
            const createOption = document.createElement('option');
            createOption.value = data.code;
            createOption.textContent = data.name;
            selectContainer.append(createOption);
        })
    }
}
export async function fetchAddress(url) {
    const TIME_OUT = 10000;
    const controller = new AbortController();
    const timeoutId = setTimeout(()=> controller.abort(),TIME_OUT);
    try {
        const response = await fetch(url,{
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response');
        }
        if(!response.ok) throw new Error(`Failed to fetch: ${url}`);
        if (!data || !Array.isArray(data) || data.length === 0) throw new Error('Data recieved are either empty or non-array');
        return data;
    }
    catch(error) {
        clearTimeout(timeoutId);
        if(error.name === 'AbortError') {
            throw new Error(`Request timeout: Server took too long to respond! Exited after ${TIME_OUT / 1000} seconds`);
        }
        throw error;
    }
}
export async function getRegions() {
    return await fetchAddress(`https://psgc.gitlab.io/api/regions/`);
}
export async function getProvinces(regionCode) {
    return await fetchAddress(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces`);
}
export async function getCities(provinceCode) {
    return await fetchAddress(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities`);
}
export async function getBarangays(cityCode) {
    return await fetchAddress(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays`);
}