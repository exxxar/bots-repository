export default {
    notification(title, text){
        window.dispatchEvent(new CustomEvent('notification', {
            detail:{
                title: title,
                text: text
            }
        }));
    },
    successBox(title, text){
        window.dispatchEvent(new CustomEvent('success_box', {
            detail:{
                title: title,
                text: text
            }
        }));
    }
}
