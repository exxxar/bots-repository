<script setup>
import Notifications from "@/ClientTg/Components/V1/Modals/Notifications.vue";
import AddToCartModal from "@/ClientTg/Components/V1/Modals/AddToCartModal.vue";
import CashBackItemInfoModal from "@/ClientTg/Components/V1/Modals/CashBackItemInfoModal.vue";
import QrCodeModal from "@/ClientTg/Components/V1/Modals/QrCodeModal.vue";
import EventCallbackForm from "@/ClientTg/Components/V1/Modals/EventCallbackForm.vue";


import ShareMenuBar from "@/ClientTg/Components/V1/Modals/ShareMenuBar.vue";
import HighlightsMenuBar from "@/ClientTg/Components/V1/Modals/HighlightsMenuBar.vue";
import Preloader from "@/ClientTg/Components/V1/Shop/Helpers/Preloader.vue";
import SideBar from "@/ClientTg/Components/V1/Modals/SideBar.vue";
import SideBarAdmin from "@/ClientTg/Components/V1/Modals/Admin/SideBarAdmin.vue";


import PageMenuModal from "@/ClientTg/Components/V1/Modals/Admin/PageMenuModal.vue";
import RulesModal from "@/ClientTg/Components/V1/Modals/Admin/RulesModal.vue";
import KeyboardMenuModal from "@/ClientTg/Components/V1/Modals/Admin/KeyboardMenuModal.vue";
import TgHelperModal from "@/ClientTg/Components/V1/Modals/Admin/TgHelperModal.vue";
import MapModal from "@/ClientTg/Components/V1/Modals/Admin/MapModal.vue";
import NotesModal from "@/ClientTg/Components/V1/Modals/Admin/NotesModal.vue";

defineProps({
    bot: {
        type: Object,
    },
    slug_id: {
        type: String,
    },
});


import Layout from "@/ClientTg/Layouts/V1Layout.vue";
</script>
<template>
    <Layout>

        <template #default>
            <div
                v-if="self"
                class="page-content" style="min-height: 667px;">

                <div class="page-title page-title-small">
                    <h2><a
                        href="javascript:void(0)"
                        @click="$router.back()"><i class="fa fa-arrow-left"></i>
                        {{ $route.meta.title || 'Меню' }}</a></h2>
                    <a

                        href="javascript:void(0)"
                        class="bg-fade-gray1-dark shadow-xl d-flex justify-content-center align-items-center font-18 bot-avatar">
                        <img v-lazy="logo" style="width:50px;object-fit: cover; border-radius: 50%;" alt=""></a>
                </div>


                <div class="card header-card shape-rounded" style="height: 115px;">
                    <div class="card-overlay bg-highlight opacity-95"></div>
                    <div class="card-overlay dark-mode-tint"></div>
                    <div class="card-bg preload-img" data-src="/shop/images/pictures/20s.jpg"></div>
                </div>

                <router-view
                    :bot="bot"/>

                <!-- footer and footer card-->

                <div class="footer">
                    <div class="card card-style mb-0">
                        <a href="#" class="footer-title p-4" >{{ currentBot.company.title || 'CashMan:Shopify' }}</a>
                        <p class="text-center font-12 mt-n1 mb-3 opacity-70">
                            Добавь <span class="color-highlight">красок</span> в свою жизнь
                        </p>

                        <p class="text-center font-12 mt-n1 mb-3 opacity-70">
                            Сервис Разработан ООО "Некст Групп"
                        </p>
                        <div class="text-center mb-3">
                            <a href="https://wa.me/79384341473?text=%D0%94%D0%BE%D0%B1%D1%80%D1%8B%D0%B9%20%D0%B4%D0%B5%D0%BD%D1%8C%2C%20%D1%85%D0%BE%D1%87%D1%83%20%D1%81%D0%B5%D0%B1%D0%B5%20%D1%82%D0%B0%D0%BA%D0%BE%D0%B9%20%D0%B6%D0%B5%20%D0%B1%D0%BE%D1%82!ph"
                               target="_blank"
                               class="icon p-2 icon-xs rounded-l shadow-l mr-1 bg-facebook text-white">
                                WatsApp разработчика
                            </a>
                        </div>

                        <div class="text-center mb-3">
                            <a href="https://vk.com/nextgroupit"
                               target="_blank"
                               class="icon p-2 icon-xs rounded-l shadow-l mr-1 bg-facebook text-white">
                               ВК разработчика
                            </a>
                        </div>

                        <div class="text-center mb-3">
                            <a href="https://t.me/nextitgroup_bot"
                               target="_blank"
                               class="icon p-2 icon-xs rounded-l shadow-l mr-1 bg-facebook text-white">
                                Телеграм разработчика
                            </a>
                        </div>



<!--                        <p class="boxed-text-l">
                            {{ currentBot.company.description || 'Описание вашего магазина' }}
                        </p>
                        <div class="text-center mb-3">
                            <a href="#" v-if="currentBot.company.email" @click="open('mailTo:'+currentBot.company.email)"
                               class="icon icon-xs rounded-sm shadow-l mr-1 bg-facebook text-white"><i
                                class="fa-solid fa-at"></i></a>
                            <a href="#" v-if="currentBot.company.links" @click="open(currentBot.company.links[0])"
                               class="icon icon-xs rounded-sm shadow-l mr-1 bg-vk text-white">
                                <i class="fa-brands fa-vk"></i></a>
                            <a href="#" v-if="currentBot.company.phones" @click="open('tel:'+currentBot.company.phones[0])"
                               class="icon icon-xs rounded-sm shadow-l mr-1 bg-phone text-white"><i
                                class="fa fa-phone"></i></a>
                            <a href="#" data-menu="menu-share"
                               class="icon icon-xs rounded-sm mr-1 shadow-l bg-red2-dark text-white"><i
                                class="fa fa-share-alt"></i></a>
                            <a href="#" class="back-to-top icon icon-xs rounded-sm shadow-l bg-highlight text-white "><i
                                class="fa fa-arrow-up"></i></a>
                        </div>-->
                        <p class="footer-copyright pb-3 mb-1">© CashMan <span id="copyright-year">2024</span>.
                            Все
                            Права защищены.</p>
                    </div>
                    <div class="footer-card card shape-rounded" data-card-height="230" style="height: 230px;">
                        <div class="card-overlay bg-highlight opacity-95"></div>
                        <div class="card-bg preload-img" data-src="/shop/images/pictures/20s.jpg"></div>
                    </div>
                </div>
            </div>

        </template>

        <template #modals>
            <Preloader/>
            <AddToCartModal/>
            <CashBackItemInfoModal/>
            <QrCodeModal/>
            <EventCallbackForm/>
            <Notifications/>

            <ShareMenuBar/>
            <HighlightsMenuBar/>

            <SideBar/>
            <SideBarAdmin/>
            <PageMenuModal />
            <KeyboardMenuModal/>
            <RulesModal/>
            <TgHelperModal/>
            <MapModal/>
            <MapModal/>
            <NotesModal/>
        </template>

    </Layout>
</template>

<script>
import {mapGetters} from "vuex";


export default {
    computed: {
        ...mapGetters(['getSelf']),
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
        self() {
            return window.self || null
        },
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
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
    created() {
        window.currentScript = this.slug_id || null
        window.currentBot = this.bot.data

        this.$store.dispatch("loadSelf").then(() => {
            window.self = this.getSelf
        })

        this.$botNotification.success(
            "Главная",
            "Успешно!",
        );

    },
    methods: {
        open(url) {
            this.tg.openLink(url)
        },
    }

}
</script>

<style>
.bot-avatar {
    position: absolute;
    top: 20px;
    right: 20px;
    border-radius: 50%;
}

.bg-vk {
    background-color: #007bff;
    color: white;
}

.footer-card {
bottom:-80px;
}

::-webkit-scrollbar {
    /* display: block; */
}

.popper {
    //transform: translateX(0px) !important;
    z-index: 1000 !important;
}
</style>

