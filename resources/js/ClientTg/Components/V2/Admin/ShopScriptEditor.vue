<template>

</template>
<script>
export default {
    data(){
        return {
            form:{
                yandex_geocoder:null,
                free_shipping_starts_from:0,
                min_base_delivery_price:0,
                price_per_km:80,
                payment_info:80,
                main_text:80,
                delivery_price_text:80,
                min_price:80,
                is_disabled:false,
                use_payment_system:false,
                can_use_cash:true,
                can_use_card:true,
                need_pay_after_call:false,
                disabled_text:null,
            }
        }
    }
}
</script>
