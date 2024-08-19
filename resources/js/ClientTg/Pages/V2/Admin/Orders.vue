<script setup>
import OrderList from "@/ClientTg/Components/V2/Admin/Orders/OrderList.vue";
</script>
<template>
    <div class="container">
        <OrderList></OrderList>
    </div>
</template>
<script>

export default {

    data() {

    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },

}
</script>
<style>

</style>
