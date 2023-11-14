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

    reloadPageList() {
        window.dispatchEvent(new CustomEvent("reload-page-list"));
    },

    map() {
        window.dispatchEvent(new CustomEvent("open-map-modal"));
    },

    mapCallback(param){
        window.dispatchEvent(new CustomEvent("select-map-coords", {
            detail: {
                param: param
            }
        }));
    },

    rules() {
        window.dispatchEvent(new CustomEvent("open-rules-modal"));
    },
    selectRule(rule) {
        window.dispatchEvent(new CustomEvent("select-rule-event", {
            detail: {
                rule: rule,
            }
        }));
    },
    telegramChannelHelper(param) {
        window.dispatchEvent(new CustomEvent("open-tg-helper-modal",{
            detail: {
                param: param,
            }
        }));
    },
    telegramChannelCallback(param, chatId){
        window.dispatchEvent(new CustomEvent("select-telegram-channel-id", {
            detail: {
                channel: chatId,
                param: param
            }
        }));
    },
    notes(param) {
        window.dispatchEvent(new CustomEvent("open-notes-modal",{
            detail: {
                param: param
            }
        }));
    },
    selectNote(note, param) {
        window.dispatchEvent(new CustomEvent("select-notes-event", {
            detail: {
                note: note,
                param: param
            }
        }));
    },


}
