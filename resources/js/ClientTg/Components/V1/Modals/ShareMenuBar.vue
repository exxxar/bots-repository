<script setup>
import QrCode from "@/ClientTg/Components/V1/Shop/Helpers/QrCode.vue";
</script>
<template>
    <div id="menu-share" class="menu menu-box-bottom menu-box-detached rounded-m d-block"
         style="height: 420px;">

        <h1 class="text-center font-700 font-26 mt-3 pt-2">Поделиться контактами</h1>
        <p class="boxed-text-xl under-heading m-0 p-0">
            Делитесь ссылкой с друзьями
        </p>

        <div class="divider divider-margins"></div>

        <div class="row text-center mr-4 ml-4 mb-0">
            <div class="col-3 mb-n2">
                <a
                    href="#"
                    @click="open('https://www.facebook.com/sharer/sharer.php?u='+link)"
                   class="icon icon-l bg-facebook rounded-s shadow-l text-white">
                    <i class="fab fa-facebook-f font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">Facebook</p>
            </div>
            <div class="col-3 mb-n2">
                <a
                    href="#"
                    @click="open('https://twitter.com/home?status='+link)"
                    class="icon icon-l bg-twitter rounded-s shadow-l text-white">
                    <i class="fab fa-twitter font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">Twitter</p>
            </div>
            <div class="col-3 mb-n2">
                <a href="#"
                   @click="open('https://vk.com/share.php?url='+link)"
                   class=" icon icon-l bg-linkedin rounded-s shadow-l text-white">
                    <i class="fa-brands fa-vk font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">VK</p>
            </div>
            <div class="col-3 mb-n2">
                <a href="#"
                   @click="open('mailto:?body='+link)"
                   class=" icon icon-l bg-mail rounded-s shadow-l text-white">
                    <i class="fa fa-envelope font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">Email</p>
            </div>
            <div class="col-3 mb-n2">
                <a href="#"
                   @click="open('whatsapp://send?text='+link)"
                   class=" icon icon-l bg-whatsapp rounded-s shadow-l text-white">
                    <i class="fab fa-whatsapp font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">WhatsApp</p>
            </div>
            <div class="col-3 mb-n2">
                <a href="#"
                   @click="copy"
                   class="shareToCopyLink icon icon-l bg-blue2-dark rounded-s shadow-l text-white">
                    <i class="fa fa-link font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">Копировать</p>
            </div>
            <div class="col-3 mb-n2">
                <a href="#"
                   @click="open('https://pinterest.com/pin/create/button/?url='+link)"
                   class="icon icon-l bg-pinterest rounded-s shadow-l text-white">
                    <i class="fab fa-pinterest-p font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">Pinterest</p>
            </div>
            <div class="col-3 mb-n2">
                <a href="#"
                   class="close-menu icon icon-l bg-red2-dark rounded-s shadow-l text-white">
                    <i class="fa fa-times font-22"></i><br>
                </a>
                <p class="font-11 opacity-70">Закрыть</p>
            </div>
        </div>

        <div class="divider divider-margins mt-n1 mb-3"></div>

        <QrCode code="001"/>

        <div class="divider divider-margins mt-n1 mb-3"></div>

        <p class="footer-copyright font-10 text-center pb-3 mb-1">© CashMan <span id="copyright-year">2023</span>.
            Все
            Права защищены.</p>
    </div>
</template>
<script>
export default {
    computed: {
        self() {
            return window.self
        },
        tg() {
            return window.Telegram.WebApp;
        },
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        },
        link() {
            return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("001" + this.self.telegram_chat_id);
        }
    },
    methods: {
        open(url) {
            this.tg.openLink(url)
        },
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
