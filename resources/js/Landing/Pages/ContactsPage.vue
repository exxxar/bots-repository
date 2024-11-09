<script setup>
import Layout from "../Layouts/ClientNewLayout.vue";

import AudioPlayer from 'vue3-audio-player'
import 'vue3-audio-player/dist/style.css'
</script>

<template>
    <Layout>
        <template #default>
            <Header/>

                <div class="page-head">
                    <div class="container">
                        <nav class="swiper breadcrumb-slider breadcrumb-slider--js swiper-initialized swiper-horizontal swiper-free-mode swiper-backface-hidden" aria-label="breadcrumb">
                            <ol class="breadcrumb swiper-wrapper" itemscope="itemscope" itemtype="http://schema.org/BreadcrumbList" id="swiper-wrapper-ef63a8104df3fa176" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                                <li class="breadcrumb-item swiper-slide swiper-slide-active" itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem" role="group" aria-label="1 / 2">
                                    <svg class="icon icon-chevron-breadcrumbs ">
                                        <use xlink:href="/storage/site/img/svg/sprite.svg#chevron-breadcrumbs"></use>
                                    </svg><a href="/" itemprop="item"><span itemprop="name">Главная</span>
                                    <meta itemprop="position" content="1"></a>
                                </li>


                                <li class="breadcrumb-item swiper-slide active swiper-slide-next" itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem" role="group" aria-label="2 / 2">
                                    <svg class="icon icon-chevron-breadcrumbs ">
                                        <use xlink:href="/storage/site/img/svg/sprite.svg#chevron-breadcrumbs"></use>
                                    </svg>
                                    <a href="/contact/" itemprop="item">
                                        <span itemprop="name">Контакты</span>
                                        <meta itemprop="position" content="2">
                                    </a>
                                </li>


                            </ol>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></nav>		<h1>Контактная информация</h1>
                    </div>
                </div>
                <!-- start sContact-->
                <div class="sContact section" id="sContact">
                    <div class="container">
                        <div class="sContact__row row">
                            <div class="col-md-6">
                                <div class="contactItem" style="--bgColor:var(--bs-secondary);">
                                    <div class="contactItem__img-wrap"><img src="/storage/site/img/svg/contact-telegram.svg" alt="" loading="lazy">
                                    </div>
                                    <div class="contactItem__descr">Технические проблемы и&nbsp;оперативная связь с&nbsp;поддержкой
                                    </div>
                                    <a class="contactItem__link" href="https://t.me/chatfood" target="_blank">t.me/chatfood
                                    </a>
                                    <div class="contactItem__schedule">Отвечаем каждый день с 10:00 до 18:00
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contactItem" style="--bgColor:var(--bs-warning);">
                                    <div class="contactItem__img-wrap"><img src="/storage/site/img/svg/contact-mail.svg" alt="" loading="lazy">
                                    </div>
                                    <div class="contactItem__descr">Ваши предложения, сотрудничество с&nbsp;нами и&nbsp;вакансии в&nbsp;компании
                                    </div>
                                    <a class="contactItem__link" href="mailto:n@chatfood.ru" target="_blank">n@chatfood.ru
                                    </a>
                                    <div class="contactItem__schedule">Отвечаем каждый день с 10:00 до 18:00
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contactItem" style="--bgColor:#E21A20;">
                                    <div class="contactItem__img-wrap"><img src="/storage/site/img/svg/contact-youtube.svg" alt="" loading="lazy">
                                    </div>
                                    <div class="contactItem__descr">
                                        Монтируем...
                                    </div>
                                    <!-- <a class="contactItem__btn btn btn-outline-secondary" href="undefined" target="_blank">Подписаться
                                    </a> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contactItem" style="--bgColor:var(--bs-secondary);">
                                    <div class="contactItem__img-wrap"><img src="/storage/site/img/svg/contact-telegram.svg" alt="" loading="lazy">
                                    </div>
                                    <div class="contactItem__descr">Публикуем бонусы, акции, новости об&nbsp;обновлениях и&nbsp;другие важные уведомления в&nbsp;первую очередь
                                    </div>
                                    <a class="contactItem__btn btn btn-outline-secondary" href="https://t.me/chatfood" target="_blank">Подписаться
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end sContact-->
        </template>
    </Layout>

</template>
<script>


import {mapGetters} from "vuex";

export default {
    data() {

        return {

        }
    },
    computed: {
        ...mapGetters(['getBots']),
    },
    mounted() {
        this.loadBots()
    },
    methods: {

    }
}
</script>
<style lang="scss">

</style>
