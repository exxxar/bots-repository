
<template>
    <div class="container py-5 px-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    В данный момент этот раздел находится на техническом обслуживании! В ближайшее время всё заработает:) Спасибо за ожидание!
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <img v-lazy="'/images/cashman.jpg'" class="w-100" style="object-fit: cover;" alt="">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="tg.close()"
                        class="btn btn-outline-success w-100 p-3">Вернуться в бота
                </button>
            </div>
        </div>
    </div>


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
