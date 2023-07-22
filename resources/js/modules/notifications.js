export default {
    notification(title, text, eventType = 'notification') {
        window.dispatchEvent(new CustomEvent(eventType, {
            detail: {
                title: title,
                text: text
            }
        }));
    },
    success(title, text) {
        this.notification(title, text, 'success')
    },
    warning(title, text) {
        this.notification(title, text, 'warning')
    },


}
