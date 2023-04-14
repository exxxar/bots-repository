<script setup>

defineProps({
    bot: {
        type: Object,
    },
    botUser: {
        type: Object
    },

});
</script>
<template>
    <div class="container" v-if="botUser.is_admin">
        <div class="row mb-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Управление рассылкой и рекламой</h5>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" v-else>
        <div class="row">
            <div class="alert alert-warning" role="alert">
                Вы не являетесь администратором
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    methods: {

    }
}
</script>
