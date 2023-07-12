export default {
    notification(title, text){
        window.dispatchEvent(new CustomEvent('notification', {
            detail:{
                title: title,
                text: text
            }
        }));
    },



}
