<template>
    <div id="menu-share" class="menu menu-box-bottom menu-box-detached rounded-m d-block"
         style="height: 420px;">

        <h1 class="text-center font-700 font-26 mt-3 pt-2">Поделиться контактами</h1>
        <p class="boxed-text-xl under-heading m-0 p-0">
           Делитесь ссылкой с друзьями
        </p>
        <img v-lazy="qr" class="w-100 p-3 object-fit-cover my-0 " alt="">
        <div class="divider divider-margins"></div>

        <div class="row text-center mr-4 ml-4 mb-0">
            <div class="col-3 mb-n2">
                <a target="_blank" :href="'https://www.facebook.com/sharer/sharer.php?u='+link" class="shareToFacebook icon icon-l bg-facebook rounded-s shadow-l"><i class="fab fa-facebook-f font-22"></i><br></a>
                <p class="font-11 opacity-70">Facebook</p>
            </div>
            <div class="col-3 mb-n2">
                <a target="_blank"  :href="'https://twitter.com/home?status='+link" class="shareToTwitter icon icon-l bg-twitter rounded-s shadow-l"><i class="fab fa-twitter font-22"></i><br></a>
                <p class="font-11 opacity-70">Twitter</p>
            </div>
            <div class="col-3 mb-n2">
                <a target="_blank"  :href="'https://vk.com/share.php?url='+link" class="shareToLinkedIn icon icon-l bg-linkedin rounded-s shadow-l"><i class="fa-brands fa-vk font-22"></i><br></a>
                <p class="font-11 opacity-70">VK</p>
            </div>
            <div class="col-3 mb-n2">
                <a target="_blank"  :href="'mailto:?body='+link" class="shareToMail icon icon-l bg-mail rounded-s shadow-l"><i class="fa fa-envelope font-22"></i><br></a>
                <p class="font-11 opacity-70">Email</p>
            </div>
            <div class="col-3 mb-n2">
                <a target="_blank"  :href="'whatsapp://send?text='+link" class="shareToWhatsApp icon icon-l bg-whatsapp rounded-s shadow-l"><i class="fab fa-whatsapp font-22"></i><br></a>
                <p class="font-11 opacity-70">WhatsApp</p>
            </div>
            <div class="col-3 mb-n2">
                <a
                   @click="copy"
                   class="shareToCopyLink icon icon-l bg-blue2-dark rounded-s shadow-l"><i class="fa fa-link font-22"></i><br></a>
                <p class="font-11 opacity-70">Копировать</p>
            </div>
            <div class="col-3 mb-n2">
                <a target="_blank"  :href="'https://pinterest.com/pin/create/button/?url='+link" class="shareToPinterest icon icon-l bg-pinterest rounded-s shadow-l"><i class="fab fa-pinterest-p font-22"></i><br></a>
                <p class="font-11 opacity-70">Pinterest</p>
            </div>
            <div class="col-3 mb-n2">
                <a   href="#" class="close-menu icon icon-l bg-red2-dark rounded-s shadow-l"><i class="fa fa-times font-22"></i><br></a>
                <p class="font-11 opacity-70">Закрыть</p>
            </div>
        </div>

        <div class="divider divider-margins mt-n1 mb-3"></div>
        <p class="footer-copyright font-10 text-center pb-3 mb-1">© CashMan <span id="copyright-year">2023</span>.
            Все
            Права защищены.</p>
    </div>
</template>
<script>
export default {
    computed:{
        self(){
          return window.self
        },
        currentBot(){
            return window.currentBot
        },
        qr(){
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data="+this.link
        },
        link(){
            return "https://t.me/"+this.currentBot.bot_domain+"?start="+btoa("001"+this.self.telegram_chat_id);
        }
    },
    methods:{
        copy(){
            navigator.clipboard.writeText("https://t.me/"+this.currentBot.bot_domain)
        }
    }
}
</script>
