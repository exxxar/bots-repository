<script setup>
import QrCode from "@/ClientTg/Components/V1/Shop/Helpers/QrCode.vue";
</script>
<template>
    <div id="qr-code" class="menu menu-box-bottom menu-box-detached rounded-m d-block"
         style="height:70vh; display:block;"
         data-menu-effect="menu-over">

        <div class="w-100 p-3" v-if="code">
            <p class="mb-0 text-center">Дайте отсканировать этот QR-код</p>
            <QrCode :code="code"></QrCode>
        </div>


    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            code: null
        }
    },
    mounted() {
        window.addEventListener("show-qr-code", (e) => {
            this.code = null
            this.$nextTick(() => {
                this.code = e.detail.code || null
                $('#qr-code').showMenu();
            })
        });


    },
    methods: {}
}
</script>
<style>

</style>
