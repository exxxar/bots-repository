<script setup>

</script>
<template>

</template>
<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {

        }
    },
    computed: {
        ...mapGetters(['getCashBack',
            'getCashBackPaginateObject']),
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {

    },
    methods: {


    }
}
</script>
