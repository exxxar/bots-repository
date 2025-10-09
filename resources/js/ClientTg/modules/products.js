export default {
    show(productItem) {
        console.log("SHOW EVENT", productItem)
        window.dispatchEvent(new CustomEvent("product-info-event", {
            detail: {
                product: productItem,
            }
        }));
    },



}
