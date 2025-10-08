const Notification = {
    container: null,
    init() {
        this.container = document.createElement("div");
        this.container.id = "global-notifications";
        this.container.style.position = "absolute";
        this.container.style.top = "50%";
        this.container.style.right = "50%";
        this.container.style.zIndex = "9999";
        this.container.style.transform = "translate(50%, -50%)";
        document.body.appendChild(this.container);
    },

    
    
    show({ type = "notification", title = "", message = "" }) {
        let icon = "";
        let headingClass = "";
        let svgClass = "";
        let textColor = "";
    
        if (type === "success") {
            svgClass = "notification-svg";
            headingClass = "notification-prompt-heading";
            textColor = "green-text";
            icon = `
                <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" class="${svgClass}">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                    </path>
                </svg>`;
        } else if (type === "error") {
            svgClass = "error-notification-svg";
            headingClass = "error-notification-prompt-heading";
            textColor = "red-text";
            icon = `
                <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" class="${svgClass}">
                    <path fill-rule="evenodd"
                        d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-8-4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 6a1 1 0 100 2 1 1 0 000-2z">
                    </path>
                </svg>`;
        }

        const wrapper = document.createElement("div");
        wrapper.innerHTML = `
            <div class="notifications-container">
                <div class="${type}">
                    <div class="flex">
                        <div class="flex-shrink-0">${icon}</div>
                        <div class="notification-prompt-wrap">
                            <p class="${headingClass}">${title}</p>
                            <div class="notification-prompt ${textColor}"><p>${message}</p></div>
                            <div class="notification-button-container">
                                <button class="notification-button-secondary" type="button">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        const notification = wrapper.firstElementChild;
        notification.querySelector(".notification-button-secondary")
            .addEventListener("click", () => notification.remove());

        this.container.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }
};

document.addEventListener("DOMContentLoaded", () => Notification.init());
