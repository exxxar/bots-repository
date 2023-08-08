<template>
    <img v-lazy="qr" class="w-100 p-3 object-fit-cover my-0 " alt="">
    <a href="javascript:void(0)" @click.prevent="copy" class="btn btn-link w-100 text-center">Скопировать ссылку</a>
</template>
<script>
export default {
    props: {
        code: {
            type: String,
            default: '001'
        }
    },
    computed: {
        self() {
            return window.self
        },
        script(){
          return   window.currentScript;
        },
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        },
        link() {
            switch(this.code) {
                default:
                case '001':  return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa((this.code||'001') + this.self.telegram_chat_id);
                case '002':  return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("002" + this.self.telegram_chat_id + "S"+this.script);
            }

        }
    },
    methods: {
        copy() {

            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(this.link).select();
            document.execCommand("copy");
            $temp.remove();

        }
    }
}
</script>
