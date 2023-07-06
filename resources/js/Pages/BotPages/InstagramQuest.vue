<script setup>
defineProps({
    rules: String
});
</script>
<template>
<div class="row">
    <div class="col-12 mb-2">
        <div class="card">
            <div class="card-body">
                <p v-if="rules" v-html="rules"></p>
            </div>
        </div>

    </div>
    <div class="col-12 d-flex justify-content-center align-items-center " style="padding-top: 100px;">


    </div>

    <div class="col-12 p-5 mt-2">
        <button
            type="submit" class="btn btn-outline-info p-2">
            Завершить квест
        </button>
    </div>

    <div class="col-12 p-5 mt-2">
        <button
            @click="closeQuest"
            type="button" class="btn btn-outline-info p-2">
            Вернуться в бота
        </button>
    </div>
</div>

</template>
<script>

export default {
    name: "App",

    data() {
        return {

        };
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
    methods: {
        closeQuest(){
          this.tg.close()
        },
    },
};
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 20px;
    font-weight: 900;
}
</style>
