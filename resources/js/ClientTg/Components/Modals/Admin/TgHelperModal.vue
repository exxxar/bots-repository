<template>


    <div :id="'tg-helper-modal'" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="210" data-menu-effect="menu-over"
         style="height: 210px;display: block;">
        <p class="mb-1 p-2">Укажите в полне ниже ссылку на ПУБЛИЧНЫЙ канал, в котором уже состоит БОТ в качестве администратора канала</p>

        <form v-on:submit.prevent="requestTelegramChannelId" class="p-2">
            <input type="text"
                   class="form-control mb-2" id="search-description-text"
                   v-model="channelLink"
                   @change="checkLink"
                   placeholder="@telegram_channel" required/>

            <button
                class="btn btn-m btn-full rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">Узнать id канала
            </button>
        </form>

    </div>


</template>
<script>
export default {
    data() {
        return {
            param: null,
            channelLink: null,

        }
    },
    mounted() {

        window.addEventListener("open-tg-helper-modal", (e) => {
            this.param = e.detail.param
            $('#tg-helper-modal').showMenu();
        } );
    },
    methods:{
        checkLink() {
            if (this.channelLink.indexOf("https://t.me/") !== -1)
                this.channelLink = "@" + (this.channelLink.split("https://t.me/")[1] || this.channelLink)
        },

        requestTelegramChannelId() {
            this.$store.dispatch("requestTelegramChannelId", {
                dataObject: {
                    channel: this.channelLink
                }
            }).then((resp) => {
                if (resp.ok)
               this.$botPages.telegramChannelCallback(this.param, resp.result.chat.id)

                $('#tg-helper-modal').hideMenu();
                if (resp.ok)
                    this.$botNotification.success("Отлично", "Канал успешно найден!")
                if (!resp.ok) {
                    this.$botNotification.warning("Ошибочка!", "Неверно указанный канал")
                    this.$botPages.telegramChannelCallback(this.param, null)
                }
            }).catch(() => {

            })
        },
    }
}
</script>

