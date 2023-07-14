export default {
    toggle(){
        window.dispatchEvent(new CustomEvent('preloader-toggle'));
    },
    hide(){
        window.dispatchEvent(new CustomEvent('preloader-hide'));
    },
    show(){
        window.dispatchEvent(new CustomEvent('preloader-show'));
    },

}
