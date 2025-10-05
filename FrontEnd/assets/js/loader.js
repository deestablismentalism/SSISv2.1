const Loader = (function () {
    let loaderEl;

    function init() {
        loaderEl = document.getElementById("loading-overlay");
        if (!loaderEl) {
            console.warn("Loader element not found. Make sure #loading-overlay exists in your HTML.");
        }
    }   

    function show() {
        if (!loaderEl) init();
        if (loaderEl) loaderEl.style.display = "flex";
    }

    function hide() {
        if (loaderEl) loaderEl.style.display = "none";
    }

    return { show, hide };
})();
