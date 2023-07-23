<template>
    <button
        @click="close"
        type="button"
        class="btn btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
        Вернуться в бота
    </button>
</template>
<script>
export default {
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
    },
    methods:{
        close(){

            if (!this.tg) {
                this.$botNotification.warning("Ошибочка",
                    "Вы находитесь не в боте!",
                );
                return;
            }


            this.tg.close()
        }
    }
}
</script>
