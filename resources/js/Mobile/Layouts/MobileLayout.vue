<script setup>

import {Head} from '@inertiajs/vue3'
import CompanyInfo from "@/ClientTg/Components/V2/Admin/CompanyInfo.vue";


</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <!-- loader -->
<!--    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>-->
    <!-- * loader -->


    <!-- App Header -->
    <div class="appHeader bg-primary scrolled">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="offcanvas" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Discover
        </div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox">
                <ion-icon name="search-outline"></ion-icon>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- Search Component -->
    <div id="search" class="appHeader">
        <form class="search-form">
            <div class="form-group searchbox">
                <input type="text" class="form-control" placeholder="Search...">
                <i class="input-icon">
                    <ion-icon name="search-outline"></ion-icon>
                </i>
                <a href="#" class="ms-1 close toggle-searchbox">
                    <ion-icon name="close-circle"></ion-icon>
                </a>
            </div>
        </form>
    </div>
    <!-- * Search Component -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="header-large-title">
            <h1 class="title">Discover</h1>
            <h4 class="subtitle">Welcome to Mobilekit</h4>
        </div>

        <div class="section full mt-3 mb-3">

            <!-- carousel multiple -->
            <div class="carousel-multiple splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d1.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">Progressive web app ready</h4>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d2.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">Reusable components</h4>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d3.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">Great for phones & tablets</h4>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d4.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">Change the styles in sass</h4>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d6.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">Sketch source file included</h4>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d7.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">RTL (Right to Left) Support</h4>
                                </div>
                            </div>
                        </li>
                        <li class="splide__slide">
                            <div class="card">
                                <img src="assets/img/sample/photo/d5.jpg" class="card-img-top" alt="image">
                                <div class="card-body pt-2">
                                    <h4 class="mb-0">Written with a code structure</h4>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- * carousel multiple -->

        </div>


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

        <div class="section mt-3 mb-3">
            <div class="card">
                <img src="assets/img/sample/photo/wide4.jpg" class="card-img-top" alt="image">
                <div class="card-body">
                    <h6 class="card-subtitle">Discover</h6>
                    <h5 class="card-title">Components</h5>
                    <p class="card-text">
                        Reusable components designed for the mobile interface and ready to use.
                    </p>
                    <a href="app-components.html" class="btn btn-primary">
                        <ion-icon name="cube-outline"></ion-icon>
                        Preview
                    </a>
                </div>
            </div>
        </div>

        <div class="section mt-3 mb-3">
            <div class="card">
                <img src="assets/img/sample/photo/wide2.jpg" class="card-img-top" alt="image">
                <div class="card-body">
                    <h6 class="card-subtitle">Discover</h6>
                    <h5 class="card-title">Pages</h5>
                    <p class="card-text">
                        Mobilekit comes with basic pages you may need and use in your projects easily.
                    </p>
                    <a href="app-pages.html" class="btn btn-primary">
                        <ion-icon name="layers-outline"></ion-icon>
                        Preview
                    </a>
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
    <!-- * App Capsule -->
    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="index.html" class="item active">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
            </div>
        </a>
        <a href="app-components.html" class="item">
            <div class="col">
                <ion-icon name="cube-outline"></ion-icon>
            </div>
        </a>
        <a href="page-chat.html" class="item">
            <div class="col">
                <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                <span class="badge badge-danger">5</span>
            </div>
        </a>
        <a href="app-pages.html" class="item">
            <div class="col">
                <ion-icon name="layers-outline"></ion-icon>
            </div>
        </a>
        <a href="#sidebarPanel" class="item" data-bs-toggle="offcanvas">
            <div class="col">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->

    <!-- App Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarPanel">
        <div class="offcanvas-body">
            <!-- profile box -->
            <div class="profileBox">
                <div class="image-wrapper">
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="imaged rounded">
                </div>
                <div class="in">
                    <strong>Julian Gruber</strong>
                    <div class="text-muted">
                        <ion-icon name="location"></ion-icon>
                        California
                    </div>
                </div>
                <a href="#" class="close-sidebar-button" data-bs-dismiss="offcanvas">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <!-- * profile box -->

            <ul class="listview flush transparent no-line image-listview mt-2">
                <li>
                    <a href="index.html" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="home-outline"></ion-icon>
                        </div>
                        <div class="in">
                            Discover
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-components.html" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="cube-outline"></ion-icon>
                        </div>
                        <div class="in">
                            Components
                        </div>
                    </a>
                </li>
                <li>
                    <a href="app-pages.html" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="layers-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Pages</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="page-chat.html" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Chat</div>
                            <span class="badge badge-danger">5</span>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="moon-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Dark Mode</div>
                            <div class="form-check form-switch">
                                <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodesidebar">
                                <label class="form-check-label" for="darkmodesidebar"></label>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="listview-title mt-2 mb-1">
                <span>Friends</span>
            </div>
            <ul class="listview image-listview flush transparent no-line">
                <li>
                    <a href="page-chat.html" class="item">
                        <img src="assets/img/sample/avatar/avatar7.jpg" alt="image" class="image">
                        <div class="in">
                            <div>Sophie Asveld</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="page-chat.html" class="item">
                        <img src="assets/img/sample/avatar/avatar3.jpg" alt="image" class="image">
                        <div class="in">
                            <div>Sebastian Bennett</div>
                            <span class="badge badge-danger">6</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="page-chat.html" class="item">
                        <img src="assets/img/sample/avatar/avatar10.jpg" alt="image" class="image">
                        <div class="in">
                            <div>Beth Murphy</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="page-chat.html" class="item">
                        <img src="assets/img/sample/avatar/avatar2.jpg" alt="image" class="image">
                        <div class="in">
                            <div>Amelia Cabal</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="page-chat.html" class="item">
                        <img src="assets/img/sample/avatar/avatar5.jpg" alt="image" class="image">
                        <div class="in">
                            <div>Henry Doe</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!-- sidebar buttons -->
        <div class="sidebar-buttons">
            <a href="#" class="button">
                <ion-icon name="person-outline"></ion-icon>
            </a>
            <a href="#" class="button">
                <ion-icon name="archive-outline"></ion-icon>
            </a>
            <a href="#" class="button">
                <ion-icon name="settings-outline"></ion-icon>
            </a>
            <a href="#" class="button">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
        </div>
        <!-- * sidebar buttons -->
    </div>
    <!-- * App Sidebar -->

    <!-- welcome notification  -->
    <div id="notification-welcome" class="notification-box">
        <div class="notification-dialog android-style">
            <div class="notification-header">
                <div class="in">
                    <img src="assets/img/icon/72x72.png" alt="image" class="imaged w24">
                    <strong>Mobilekit</strong>
                    <span>just now</span>
                </div>
                <a href="#" class="close-button">
                    <ion-icon name="close"></ion-icon>
                </a>
            </div>
            <div class="notification-content">
                <div class="in">
                    <h3 class="subtitle">Welcome to Mobilekit</h3>
                    <div class="text">
                        Mobilekit is a PWA ready Mobile UI Kit Template.
                        Great way to start your mobile websites and pwa projects.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * welcome notification -->


    <!--    <header
            v-if="!needHideMenu"
            data-bs-theme="dark">
            <div class="navbar shadow shadow-sm">
                <div class="container flex-row-reverse p-2">

                    <p class="mb-0  fw-bold d-flex flex-column"
                       style="font-size:12px;"
                       v-if="bot.company">
                        <span v-if="bot.company.address" class="text-primary">
                              <i class="fa-solid fa-location-dot mr-1"></i> {{ bot.company.address }}
                        </span>
                        <span class="text-primary" v-else>{{ bot.title || 'Магазин' }}</span>

                        <span v-if="(bot.company.phones||[]).length>0"
                              class="small d-flex justify-content-end">
                        <a href="javascript:void(0)" class=" text-secondary fw-bold">{{ bot.company.phones[0] }}</a>
                    </span>
                    </p>
                    <p class="mb-0 text-primary"
                       style="font-size:12px;"
                       v-else>{{ bot.title || 'Бот' }}</p>
                    <button class="btn btn-link rounded-0 border-0 p-1" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </header>-->

    <slot/>

<!--    <footer class="text-body-secondary" style="padding:0px 0px 90px 0px;">

        <div class="container d-flex justify-content-center flex-column align-items-center">

            <button
                v-if="$route.name!='FeedBackV2'"
                @click="goTo('FeedBackV2')"
                class="btn btn-link mb-2 w-100 p-3 text-primary">Обратная связь
            </button>


            <p class="text-center mb-3">
                <span v-html="bot.company.description"></span>
                <br>
                <a
                    v-if="(getSelf||{is_admin:false}).is_admin"
                    data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
                    href="javascript:void(0)" class="text-primary ml-2" style="font-size:12px;"><i
                    class="fa-solid fa-pen-to-square"></i> редактировать</a>
            </p>


            <p class="mb-3 text-center" v-if="bot.company.address"><i
                class="fa-solid fa-map-location-dot mr-2"></i>{{ bot.company.address }}</p>
            <p class="mb-0">{{ bot.company.title }}©{{ (new Date()).getFullYear() }}</p>
            <p class="d-flex justify-content-center my-3">
                <a href="javascript:void(0)" @click="scrollTop"><i class="fa-solid fa-arrow-up mr-2"></i>Вернуться
                    наверх</a>
            </p>
        </div>
    </footer>


    <div class="offcanvas offcanvas-start custom-offcanvas"
         style="width: 70%;border-radius: 0px 10px 10px 0px;"
         tabindex="-1" id="sidebar-menu" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title" id="offcanvasExampleLabel">{{ bot.title || 'Магазин' }}</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>


        <div class="offcanvas-body">

            <ul class="list-unstyled">
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='MenuV2'}"
                    @click="goTo('MenuV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Главное меню</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='ProfileV2'}"
                    @click="goTo('ProfileV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{preparedMenuItem['profile']?.title || 'Профиль'}} </a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='CatalogV2'}"
                    @click="goTo('CatalogV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{preparedMenuItem['shop']?.title || 'Магазин'}} </a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='ShopCartV2'}"
                    @click="goTo('ShopCartV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{preparedMenuItem['basket']?.title || 'Корзина'}} <span class="fw-bold" v-if="cartTotalCount>0">({{ cartTotalCount }})</span></a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='OrdersV2'}"
                    @click="goTo('OrdersV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{preparedMenuItem['history']?.title || 'История заказов'}} </a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='CashBackV2'}"
                    @click="goTo('CashBackV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > CashBack</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='FeedBackV2'}"
                    @click="goTo('FeedBackV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Оставить отзыв</a></li>
            </ul>

            <div class="border-top my-3 "></div>
            <div
                class="p-2"
                v-if="bot.company">
                <ul class="list-unstyled">
                    <li v-if="(bot.company.phones||[]).length>0"><p class="mb-0">Телефон</p></li>
                    <li v-if="(bot.company.phones||[]).length>0"
                        class="mb-2">
                        <a
                            target="_blank"
                            :href="'tel:'+bot.company.phones[0]"
                            class="text-decoration-none fw-bold">{{ bot.company.phones[0] || '-' }}</a>
                    </li>
                    <li v-if="links.inst||links.vk"><p class="mb-0">Ссылки</p></li>
                    <li v-if="links.inst">
                        <a target="_blank"
                           :href="'http://instagram.com/'+links.inst"
                           style="font-size:12px;"
                           class="text-primary">
                            <i class="fa-brands fa-instagram mr-1"></i>
                            {{ links.inst || 'ссылка' }}
                        </a>
                    </li>
                    <li v-if="links.vk">
                        <a target="_blank"
                           :href="'https://vk.com/'+links.vk"
                           style="font-size:12px;"
                           class="text-primary">
                            <i class="fa-brands fa-vk mr-1"></i>
                            {{ links.vk || 'ссылка' }}
                        </a>
                    </li>
                    <li v-if="bot.company.email"><p class="mb-0">Почта</p></li>
                    <li><a :href="'mailto:'+bot.company.email"
                           target="_blank"
                           v-if="bot.company.email"
                           class="text-decoration-none fw-bold"
                    >{{ bot.company.email }}</a></li>
                    <li v-if="links.site"><p class="mb-0">Сайт</p></li>
                    <li><a :href="links.site"
                           target="_blank"
                           v-if="links.site"
                           class="text-decoration-none fw-bold"
                    >{{ links.site }}</a></li>
                </ul>
            </div>

                        <div class="dropdown" v-if="(getSelf||{is_admin:false}).is_admin">
                            <button class="btn btn-light w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                Тема оформления
                            </button>
                            <ul class="dropdown-menu w-100 bg-light">
                                <li v-for="(theme, index) in themes">
                                    <button type="button"
                                            @click="switchTheme(index)"
                                            v-bind:class="{'active':currentTheme.indexOf(theme.href)!=-1}"
                                            class="list-group-item list-group-item-action p-2 w-100 text-primary"
                                            aria-current="true">
                                        {{ theme.title || '-' }}
                                    </button>
                                </li>
                            </ul>
                        </div>
        </div>
    </div>

    &lt;!&ndash; Modal &ndash;&gt;
    <div class="modal fade" id="edit-shop-footer-description-modal" tabindex="-1"
         aria-labelledby="edit-shop-footer-description" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <CompanyInfo></CompanyInfo>

                </div>
            </div>
        </div>
    </div>-->
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            currentTheme: '',
            themes: [
                {
                    title:'Тема 1',
                    href: '/theme1.bootstrap.min.css',
                },
                {
                    title:'Тема 2',
                    href: '/theme2.bootstrap.min.css',
                },
                {
                    title:'Тема 3',
                    href: '/theme3.bootstrap.min.css',
                },
                {
                    title:'Тема 4',
                    href: '/theme4.bootstrap.min.css',
                },
                {
                    title:'Тема 5',
                    href: '/theme5.bootstrap.min.css',
                },
                {
                    title:'Тема 6',
                    href: '/theme6.bootstrap.min.css',
                },
                {
                    title:'Тема 7',
                    href: '/theme7.bootstrap.min.css',
                },
                {
                    title:'Тема 8',
                    href: '/theme8.bootstrap.min.css',
                },
                {
                    title:'Тема 9',
                    href: '/theme9.bootstrap.min.css',
                },
                {
                    title:'Тема 10',
                    href: '/theme10.bootstrap.min.css',
                }
                ,
                {
                    title:'Тема 11',
                    href: '/theme11.bootstrap.min.css',
                },
                {
                    title:'Тема 12',
                    href: '/theme12.bootstrap.min.css',
                },
                {
                    title:'Тема 13',
                    href: '/theme13.bootstrap.min.css',
                },
                {
                    title:'Тема 14',
                    href: '/theme14.bootstrap.min.css',
                },
                {
                    title:'Тема 15',
                    href: '/theme15.bootstrap.min.css',
                },
                {
                    title:'Тема 16',
                    href: '/theme16.bootstrap.min.css',
                }
               /* {
                    title: 'Тема 3',
                    href: '/theme5.bootstrap.min.css',
                },
                {
                    title: 'Тема 4',
                    href: '/theme6.bootstrap.min.css',
                },

                {
                    title: 'Тема 5',
                    href: '/theme8.bootstrap.min.css',
                },
                {
                    title: 'Тема 6',
                    href: '/theme9.bootstrap.min.css',
                },
                {
                    title: 'Тема 7',
                    href: '/theme10.bootstrap.min.css',
                }
                ,
                {
                    title: 'Тема 8',
                    href: '/theme11.bootstrap.min.css',
                },
                {
                    title: 'Тема 9',
                    href: '/theme12.bootstrap.min.css',
                },
                {
                    title: 'Тема 10',
                    href: '/theme13.bootstrap.min.css',
                },
                {
                    title: 'Тема 11',
                    href: '/theme14.bootstrap.min.css',
                },
                {
                    title: 'Тема 12',
                    href: '/theme15.bootstrap.min.css',
                },
                {
                    title: 'Тема 13',
                    href: '/theme16.bootstrap.min.css',
                }*/
            ]
        }
    },
    watch: {
        $route(newRouteValue) {
            let theme = localStorage.getItem("cashman_global_client_theme") || null

            if (theme) {
                this.$nextTick(() => {
                    this.currentTheme = theme
                })
            }


          //  this.$preloader.show();
            this.$nextTick(() => {
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            })
        },
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount']),
        tg() {
            return window.Telegram.WebApp;
        },
        bot() {
            return window.currentBot
        },
        preparedMenuItem() {
            if (!this.bot.config)
                return []

            let data = this.bot.config["icons"] || []

            let arr = [];
            data.forEach(item => {
                arr[item.slug] = item
            })


            return arr
        },
        needHideMenu(){
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.has('hide_menu') || this.$route.meta.hide_menu
        },
        links() {
            return {
                inst: (this.bot.company.links || {inst: null}).inst || null,
                vk: (this.bot.company.links || {vk: null}).vk || null,
                map_link: (this.bot.company.links || {map_link: null}).map_link || null,
                site: (this.bot.company.links || {site: null}).site || null,
            }
        }
    },

    mounted() {


        let theme = localStorage.getItem("cashman_global_client_theme_"+(this.bot.bot_domain || 'any_bot')) || null

        if (theme) {
            this.$nextTick(() => {
                this.currentTheme = theme
            })
        }

        this.changeTheme(this.tg.colorScheme)

        this.tg.expand()

        this.tg.BackButton.hide()

    },
    methods: {
        switchTheme(index) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = this.themes[index].href //`./theme${index}.bootstrap.min.css`
            localStorage.setItem("cashman_global_client_theme_"+(this.bot.bot_domain || 'any_bot'), changeTheme.href)


            this.$nextTick(() => {
                this.currentTheme = changeTheme.href
            })


        },
        goTo(name) {
            this.$router.push({name: name})
        },

        changeTheme(name) {
            let themes = document.querySelectorAll("[data-bs-theme]")

            themes.forEach(item => {
                item.setAttribute("data-bs-theme", name)
            })
        },
        scrollTop() {
            window.scrollTo(0, 80);
        },
        openLink(url) {
            this.tg.openLink(url, {
                try_instant_view: true
            })
        },
        closeShop() {
            this.tg.close()
        },

    },


}
</script>

<style lang="scss">
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}


body {
    font-size: .875rem;
}

.feather {
    width: 16px;
    height: 16px;
}

/*
 * Sidebar
 */

.sidebar {
    position: fixed;
    top: 0;
    /* rtl:raw:
    right: 0;
    */
    bottom: 0;
    /* rtl:remove */
    left: 0;
    z-index: 100; /* Behind the navbar */
    padding: 48px 0 0; /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

@media (max-width: 767.98px) {
    .sidebar {
        top: 5rem;
    }
}

.sidebar-sticky {
    height: calc(100vh - 48px);
    overflow-x: hidden;
    overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}

.sidebar .nav-link .feather {
    margin-right: 4px;
    color: #727272;
}

.sidebar .nav-link.active {
    color: #2470dc;
}

.sidebar .nav-link:hover .feather,
.sidebar .nav-link.active .feather {
    color: inherit;
}

.sidebar-heading {
    font-size: .75rem;
}

/*
 * Navbar
 */

.navbar-brand {
    padding-top: .75rem;
    padding-bottom: .75rem;
    background-color: rgba(0, 0, 0, .25);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .navbar-toggler {
    top: .25rem;
    right: 1rem;
}

.navbar .form-control {
    padding: .75rem 1rem;
}

.form-control-dark {
    color: #fff;
    background-color: rgba(255, 255, 255, .1);
    border-color: rgba(255, 255, 255, .1);
}

.form-control-dark:focus {
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}

.cursor-pointer {
    cursor: pointer;
}

.ml-2 {
    margin-left: 10px;
}

.mr-2 {
    margin-right: 10px;
}


.bot-label {
    border-radius: 5px;
    border: 1px white solid;
    height: 40px;

    p {
        color: white;
        padding: 10px;
        box-sizing: border-box;
    }
}

/*.custom-offcanvas {
    border: none;
    box-shadow: inset 1px 1px rgba(255, 255, 255, .2), inset -1px -1px rgba(255, 255, 255, .1), 1px 3px 24px -1px rgba(0, 0, 0, .15);
    background-color: #000000b3;
    background-image: linear-gradient(125deg, rgba(255, 255, 255, .3), rgba(255, 255, 255, .2) 70%);
    -webkit-backdrop-filter: blur(5px);
    backdrop-filter: blur(5px);
    color:white;
}*/
</style>
