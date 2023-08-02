export default {
    show(item = null){
        window.dispatchEvent(new CustomEvent('show-chashback-info', {
            detail:{
                item: item,
            }
        }));
    },

    eventInfo(item = null){
        window.dispatchEvent(new CustomEvent('show-event-info', {
            detail:{
                item: item,
            }
        }));
    },

}
