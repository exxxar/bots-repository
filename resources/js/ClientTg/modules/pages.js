export default {
    open(pageId) {
        window.dispatchEvent(new CustomEvent("open-page-menu-modal", {
            detail: {
                id: pageId,
            }
        }));
    },

    keyboard(select, keyboard) {
        window.dispatchEvent(new CustomEvent("open-keyboard-editor", {
            detail: {
                select: select,
                keyboard: keyboard
            }
        }));
    },



}
