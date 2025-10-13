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

    

    show({ type = "success-notification", title = "", message = "" }) {
        const wrapper = document.createElement("div");
        wrapper.innerHTML = `
            <div class="notifications-container">
                <div class="${type}">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" class="success-notification-svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                </path>
                            </svg>
                        </div>
                        <div class="success-notification-prompt-wrap">
                            <p class="success-notification-prompt-heading">${title} <span class="checkmark">✓</span></p>
                            <div class="success-notification-prompt-prompt"><p>${message}</p></div>
                            <div class="success-notification-button-container">
                                <button class="success-notification-button-secondary" type="button">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        const notification = wrapper.firstElementChild;
        notification.querySelector(".success-notification-button-secondary").addEventListener("click", () => notification.remove());
        this.container.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    }
};

document.addEventListener("DOMContentLoaded", () => Notification.init());
