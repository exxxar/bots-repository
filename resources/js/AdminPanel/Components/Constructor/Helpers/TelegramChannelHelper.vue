<template>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fa-brands fa-telegram"></i>
        </button>

        <div
            style="min-width:300px;"
            class="dropdown-menu cursor-pointer text-muted p-2">
            <form v-on:submit.prevent="requestTelegramChannelId">

                <div class="input-group">
                    <div class="form-floating">
                        <input type="text"
                               class="form-control" id="search-description-text"
                               v-model="channelLink"
                               @change="checkLink"
                               placeholder="@telegram_channel" required/>
                        <label for="">Ссылка на канал</label>
                    </div>
                    <button
                        :disabled="!token"
                        class="btn btn-outline-primary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>


                </div>


                <div v-if="!token" class="alert alert-danger mb-2" role="alert">
                    Вам необходимо указать тоукен бота!
                </div>


            </form>

        </div>
    </div>
</template>
<script>
export default {
    props: ["token", "param"],
    data() {
        return {

            channelLink: null,

        }
    },

    mounted() {

    },
    methods: {
        checkLink() {
            if (this.channelLink.indexOf("https://t.me/") !== -1)
                this.channelLink = "@" + (this.channelLink.split("https://t.me/")[1] || this.channelLink)
        },

        requestTelegramChannelId() {
            this.$store.dispatch("requestTelegramChannelId", {
                dataObject: {
                    token: this.token,
                    channel: this.channelLink
                }
            }).then((resp) => {
                if (resp.ok)
                    this.$emit("callback", {
                        param: this.param || null,
                        text: resp.result.chat.id
                    })

                if (!resp.ok) {
                    this.$notify({
                        title: "Конструктор ботов",
                        text: resp.description,
                        type: 'error'
                    });

                    this.$emit("callback", {
                        param: this.param || null,
                        text: null
                    })
                }
            }).catch(() => {

            })
        },
    }
}
</script>
<style lang="scss">
.item-with-text {
    max-height: 300px;
    max-width: 500px;
    min-width: 100% !important;
    overflow-y: auto;
}
</style>
