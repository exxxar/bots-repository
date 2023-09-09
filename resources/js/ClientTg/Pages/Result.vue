<script setup>
defineProps({
   message: {
        type:String
   }
});


import Layout from "ClientTg@/Layouts/ShopLayout.vue";
</script>
<template>
    <p>{{message}}</p>
</template>
<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {}
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {

    },
    methods: {}
}
</script>
