<script setup>
import ImageSlider from "@/Mobile/Components/Base/ImageSlider.vue";
</script>
<template>
    <div id="appCapsule">

        <div class="header-large-title">
            <h1 class="title">Discover</h1>
            <h4 class="subtitle">Welcome to Mobilekit</h4>
        </div>


        <template  v-if="page">
            <div class="section mt-3 mb-3">
                <div class="card">
                    <div
                        class="card-body d-flex justify-content-between align-items-end">
                        <p class="mb-0" v-html="page.content"></p>
                    </div>
                </div>
            </div>

            <div class="section mb-3">
<!--                <div class="section-title">Multiple Carousel</div>-->
                <!-- carousel multiple -->

                <div class="card">
                    <div class="card-body">
                        <div class="row row-cols-3">
                            <div class="col" v-for="img in page.images">
                                <img v-lazy="img"
                                     class="w-100"
                                     alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- * carousel multiple -->
            </div>

            <div class="section mt-3 mb-3">
                <div class="card">
                    <div
                        class="card-body d-flex justify-content-between align-items-end">
                        <div
                            v-if="page.inlineKeyboard.menu.length>0"
                            class="buttons w-100">
                            <div v-for="row in page.inlineKeyboard.menu" class="d-flex justify-content-between flex-wrap">

                                <div class="d-flex justify-content-between w-100">
                                    <div class="chat-reply-btn w-100" v-for="col in row">
                                        <button
                                            class="btn btn-outline-primary w-100">{{ col.text || col || '-' }}
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>


        </template>


        <div class="section mt-3 mb-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="card-subtitle">Discover</h6>
                        <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                            Dark Mode
                        </h5>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodecontent">
                        <label class="form-check-label" for="darkmodecontent"></label>
                    </div>

                </div>
            </div>
        </div>



        <!-- app footer -->
        <div class="appFooter">
            <img src="assets/img/logo.png" alt="icon" class="footer-logo mb-2">
            <div class="footer-title">
                Copyright © Mobilekit <span class="yearNow"></span>. All Rights Reserved.
            </div>
            <div>Mobilekit is PWA ready Mobile UI Kit Template.</div>
            Great way to start your mobile websites and pwa projects.

            <div class="mt-2">
                <a href="#" class="btn btn-icon btn-sm btn-facebook">
                    <ion-icon name="logo-facebook"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-twitter">
                    <ion-icon name="logo-twitter"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-linkedin">
                    <ion-icon name="logo-linkedin"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-instagram">
                    <ion-icon name="logo-instagram"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-whatsapp">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-secondary goTop">
                    <ion-icon name="arrow-up-outline"></ion-icon>
                </a>
            </div>

        </div>
        <!-- * app footer -->

    </div>
</template>
<script>
export default {
    data(){
        return {
            page:null
        }
    },
    mounted(){
        this.loadPageById()
    },
    methods:{
        loadPageById(){
            this.$store.dispatch("loadPageById",{
                id: this.$route.params.id
            }).then((resp) => {
                this.page = resp.data
            })
        }
    }
}
</script>
<style lang="scss">
.chat-reply-btn,
.chat-inline-btn {

    padding: 1px;

    &:nth-last-child {
        padding-right: 0;
    }

    &:nth-of-type(1) {
        padding-left: 0;
    }

    a,
    button {
        // font-size: 8px;
        font-size: 12px;
        color: black;

        padding: 10px;

        border-color: #b4b4b4;

        margin-bottom: 3px;

        &:hover {
            border-color: #185018;
        }
    }
}

.chat-reply-btn {
    button {
        font-size: 10px;
    }
}
</style>
