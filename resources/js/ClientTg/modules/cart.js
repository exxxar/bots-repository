export default {
    add(product = null){
        window.dispatchEvent(new CustomEvent('add-to-cart', {
            detail:{
                product: product,
            }
        }));
    },

}
