<script setup>
import Layout from "@/Layouts/ClientLayout.vue";
import VisitCardConstructor from "@/Components/Constructor/VisitCardConstructor.vue";
import ChatWindow from "@/Components/Chat/ChatWindow.vue";
import ChatDialog from "@/Components/Chat/ChatDialog.vue";

</script>

<template>
    <Layout>
        <template #default>


            <!-- Mashead header-->
            <header class="masthead">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-6">
                            <!-- Mashead text and app badges-->
                            <div class="mb-5 mb-lg-0 text-center text-lg-start">
                                <h1 class="display-1 lh-1 mb-3">Showcase your app beautifully.</h1>
                                <p class="lead fw-normal text-muted mb-5">Launch your mobile app landing page faster
                                    with this free, open source theme from Start Bootstrap!</p>
                                <div class="d-flex flex-column flex-lg-row align-items-center">
                                    <a class="me-lg-3 mb-4 mb-lg-0" href="#!"><img class="app-badge"
                                                                                   src="/landing/assets/img/google-play-badge.svg"
                                                                                   alt="..."/></a>
                                    <a href="#!"><img class="app-badge" src="/landing/assets/img/app-store-badge.svg"
                                                      alt="..."/></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <!-- Masthead device mockup feature-->
                            <div class="masthead-device-mockup">
                                <svg class="circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="circleGradient" gradientTransform="rotate(45)">
                                            <stop class="gradient-start-color" offset="0%"></stop>
                                            <stop class="gradient-end-color" offset="100%"></stop>
                                        </linearGradient>
                                    </defs>
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg
                                >
                                <svg class="shape-1 d-none d-sm-block" viewBox="0 0 240.83 240.83"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03"
                                          transform="translate(120.42 -49.88) rotate(45)"></rect>
                                    <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03"
                                          transform="translate(-49.88 120.42) rotate(-45)"></rect>
                                </svg
                                >
                                <svg class="shape-2 d-none d-sm-block" viewBox="0 0 100 100"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <div class="device-wrapper">
                                    <div class="device" data-device="iPhoneX" data-orientation="portrait"
                                         data-color="black">
                                        <div class="screen bg-black">
                                            <!-- PUT CONTENTS HERE:-->
                                            <!-- * * This can be a video, image, or just about anything else.-->
                                            <!-- * * Set the max width of your media to 100% and the height to-->
                                            <!-- * * 100% like the demo example below.-->
                                            <video muted="muted" autoplay="" loop=""
                                                   style="max-width: 100%; height: 100%">
                                                <source src="/landing/assets/img/demo-screen.mp4" type="video/mp4"/>
                                            </video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Quote/testimonial aside-->
            <aside class="text-center bg-gradient-primary-to-secondary">
                <div class="container px-5">
                    <div class="row gx-5 justify-content-center">
                        <div class="col-xl-8">
                            <div class="h2 fs-1 text-white mb-4">"An intuitive solution to a common problem that we all
                                face, wrapped up in a single app!"
                            </div>
                            <img src="/landing/assets/img/tnw-logo.svg" alt="..." style="height: 3rem"/>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="bg-light py-5">
                <div class="container px-5">
                    <div class="row gx-5 justify-content-center">
                        <div class="col-xxl-8">
                            <div class="text-center my-5">
                                <h2 class="display-5 fw-bolder"><span class="text-gradient d-inline">About Me</span>
                                </h2>
                                <p class="lead fw-light mb-4">My name is Start Bootstrap and I help brands grow.</p>
                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit
                                    dolorum itaque qui unde quisquam consequatur autem. Eveniet quasi nobis aliquid
                                    cumque officiis sed rem iure ipsa! Praesentium ratione atque dolorem?</p>
                                <div class="d-flex justify-content-center fs-2 gap-4">
                                    <a class="text-gradient" href="#!"><i class="bi bi-twitter"></i></a>
                                    <a class="text-gradient" href="#!"><i class="bi bi-linkedin"></i></a>
                                    <a class="text-gradient" href="#!"><i class="bi bi-github"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-5">
                <div class="container px-5 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Наши кейсы</span></h1>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-11 col-xl-9 col-xxl-8">
                            <!-- Project Card 1-->
                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="p-5">
                                            <h2 class="fw-bolder">Project Name 1</h2>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum
                                                illum aperiam placeat esse? Mollitia omnis minima saepe recusandae
                                                libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!</p>
                                        </div>
                                        <img class="img-fluid" src="https://dummyimage.com/300x400/343a40/6c757d"
                                             alt="...">
                                    </div>
                                </div>
                            </div>
                            <!-- Project Card 2-->
                            <div class="card overflow-hidden shadow rounded-4 border-0">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center">
                                        <div class="p-5">
                                            <h2 class="fw-bolder">Project Name 2</h2>
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius at enim eum
                                                illum aperiam placeat esse? Mollitia omnis minima saepe recusandae
                                                libero, iste ad asperiores! Explicabo commodi quo itaque! Ipsam!</p>
                                        </div>
                                        <img class="img-fluid" src="https://dummyimage.com/300x400/343a40/6c757d"
                                             alt="...">
                                    </div>
                                </div>
                            </div>


<!--
                            <div class="w-100 d-flex justify-content-center mt-5">
                                <button class="btn btn-outline-primary p-3">Хочу попробовать вживую</button>
                            </div>
-->


                        </div>
                    </div>
                </div>
            </section>

            <section id="cases">
                <div class="container px-5">

                    <div class="row gx-5 justify-content-center">
                        <div class="col-12">
                            <ChatDialog :domain="'isushibot'"></ChatDialog>
                        </div>
                    </div>


                </div>
            </section>-

            <section class="py-5 bg-gradient-primary-to-secondary text-white" v-if="!need_start">
                <div class="container px-5 my-5">
                    <div class="text-center">
                        <h2 class="display-4 fw-bolder mb-4">Давайте создадим что-нибудь вместе!</h2>
                        <a class="btn btn-outline-light btn-lg px-5 py-3 fs-6 fw-bolder"
                           @click="need_start = true">Я создам!</a>
                        <a class="btn btn-outline-light btn-lg px-5 py-3 fs-6 fw-bolder ml-2"
                           data-bs-toggle="modal" data-bs-target="#feedbackModal">Позвать помошника</a>
                    </div>
                </div>
            </section>

            <!-- App constructor section-->
            <section id="constructor" class="py-5 bg-gradient-primary-to-secondary text-white" v-if="need_start">
                <div class="container px-5 my-5">
                    <VisitCardConstructor :start="0"/>

                    <div class="w-100 d-flex justify-content-center mt-5">
                        <button
                            title="Перезапустить"
                            class="btn btn-link" @click="need_start = false">
                            <i class="fa-solid fa-arrows-rotate text-white fw-bold" style="font-size:26px;"></i>
                        </button>
                    </div>

                </div>
            </section>
            <!-- App features section-->
            <section id="features">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-8 order-lg-1 mb-5 mb-lg-0">
                            <div class="container-fluid px-5">
                                <div class="row gx-5">
                                    <div class="col-md-6 mb-5">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-phone icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">Device Mockups</h3>
                                            <p class="text-muted mb-0">Ready to use HTML/CSS device mockups, no
                                                Photoshop required!</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-5">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-camera icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">Flexible Use</h3>
                                            <p class="text-muted mb-0">Put an image, video, animation, or anything else
                                                in the screen!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-5 mb-md-0">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-gift icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">Free to Use</h3>
                                            <p class="text-muted mb-0">As always, this theme is free to download and use
                                                for any purpose!</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-patch-check icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">Open Source</h3>
                                            <p class="text-muted mb-0">Since this theme is MIT licensed, you can use it
                                                commercially!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 order-lg-0">
                            <!-- Features section device mockup-->
                            <div class="features-device-mockup">
                                <svg class="circle" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="circleGradient" gradientTransform="rotate(45)">
                                            <stop class="gradient-start-color" offset="0%"></stop>
                                            <stop class="gradient-end-color" offset="100%"></stop>
                                        </linearGradient>
                                    </defs>
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg
                                >
                                <svg class="shape-1 d-none d-sm-block" viewBox="0 0 240.83 240.83"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03"
                                          transform="translate(120.42 -49.88) rotate(45)"></rect>
                                    <rect x="-32.54" y="78.39" width="305.92" height="84.05" rx="42.03"
                                          transform="translate(-49.88 120.42) rotate(-45)"></rect>
                                </svg
                                >
                                <svg class="shape-2 d-none d-sm-block" viewBox="0 0 100 100"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="50" cy="50" r="50"></circle>
                                </svg>
                                <div class="device-wrapper">
<!--                                    <div class="device" data-device="iPhoneX" data-orientation="portrait"
                                         data-color="black">
                                        <div class="screen bg-black">
                                            <iframe src="/web/vape888bot"
                                                    style="height:100%;width:100%;"
                                            ></iframe>
                                        </div>
                                    </div>-->
                                    <div class="row">
                                        <div class="col-12">
                                            <ChatDialog :domain="'isushibot'"></ChatDialog>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic features section-->
            <section class="bg-light">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center justify-content-center justify-content-lg-between">
                        <div class="col-12 col-lg-5">
                            <h2 class="display-4 lh-1 mb-4">Enter a new age of web design</h2>
                            <p class="lead fw-normal text-muted mb-5 mb-lg-0">This section is perfect for featuring some
                                information about your application, why it was built, the problem it solves, or anything
                                else! There's plenty of space for text here, so don't worry about writing too much.</p>
                        </div>
                        <div class="col-sm-8 col-md-6">
                            <div class="px-5 px-sm-0"><img class="img-fluid rounded-circle"
                                                           src="https://source.unsplash.com/u8Jn2rzYIps/900x900"
                                                           alt="..."/></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Call to action section-->
            <section class="cta">
                <div class="cta-content">
                    <div class="container px-5">
                        <h2 class="text-white display-1 lh-1 mb-4">
                            Stop waiting.
                            <br/>
                            Start building.
                        </h2>
                        <a class="btn btn-outline-light py-3 px-4 rounded-pill"
                           href="https://startbootstrap.com/theme/new-age" target="_blank">Download for free</a>
                    </div>
                </div>
            </section>

            <section id="prices">
                <div class="container px-5">

                    <div class="row gx-5 justify-content-center">
                        <div class="col-xxl-8">
                            <div class="text-center my-5"><h2 class="display-5 fw-bolder"><span
                                class="text-gradient d-inline">Наши тарифы</span></h2>
                                <p class="lead fw-light mb-4">My name is Start Bootstrap and I help brands grow.</p>
                                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                    Fugit dolorum itaque qui unde quisquam consequatur autem. Eveniet quasi nobis
                                    aliquid cumque officiis sed rem iure ipsa! Praesentium ratione atque
                                    dolorem?</p>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="card bg-gradient-primary-to-secondary shadow text-white mb-5">
                                <div class="card-header pt-4">
                                    <h4 class="text-center">Индивидуальный</h4>
                                </div>
                                <div class="card-body  px-3 py-4">
                                    <p><i class="fa-solid fa-check mr-2"></i> Соберем тариф под Ваши нужды</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Рассылки неограничены</p>

                                    <button
                                        data-bs-toggle="modal" data-bs-target="#feedbackModal"
                                        type="button" class="btn btn-outline-light w-100">Обсудить тариф
                                    </button>
                                </div>

                            </div>
                            <div class="content">
                                <h3 class="text-secondary"> Список услуг</h3>
                                <ul id="services" class="list-group list-group-flush">
                                    <li class="list-group-item">Online-chat</li>
                                    <li class="list-group-item">Бот для мессенджеров</li>
                                    <li class="list-group-item">Бот для Whatsapp</li>
                                    <li class="list-group-item">Сбор заявок</li>
                                    <li class="list-group-item">Графический редактор</li>
                                    <li class="list-group-item">Отправка медиафайлов</li>
                                    <li class="list-group-item">CRM</li>
                                    <li class="list-group-item">Перенос клиентской базы</li>
                                    <li class="list-group-item">Инструменты оператора</li>
                                    <li class="list-group-item">Статистика</li>
                                    <li class="list-group-item">Рассылки</li>
                                    <li class="list-group-item">Bitrix, AmoCRM</li>
                                    <li class="list-group-item">Работа с API</li>
                                    <li class="list-group-item">Запись/бронирование</li>
                                    <li class="list-group-item">Работа с Google таблицами</li>
                                    <li class="list-group-item">Программируемая логика</li>
                                    <li class="list-group-item">Высокая нагрузка</li>
                                    <li class="list-group-item">Оплаты в боте</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3" style="border:none;min-height:290px;">
                                <div class="card-header bg-white pt-4">
                                    <h4 class="text-center">Базовый</h4>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p><i class="fa-solid fa-check mr-2"></i> Многоуровневые автоворонки</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Первый месяц бесплатно</p>
                                    <h2 class="gray">1099
                                        <small>₽ / мес</small>
                                    </h2>

                                    <button
                                        data-bs-toggle="modal" data-bs-target="#feedbackModal"
                                        class="btn btn-outline-primary w-100">Попробовать
                                    </button>
                                </div>

                            </div>
                            <div class="content">
                                <h5 class="text-primary text-center">Для старта</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>

                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3" style="border:none;min-height:290px;">
                                <div class="card-header bg-white pt-4">
                                    <h4 class="text-center">Стандартный</h4>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p><i class="fa-solid fa-check mr-2"></i> Автоворонки и рассылки</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Первый месяц бесплатно</p>
                                    <h2 class="gray">1099
                                        <small>₽ / мес</small>
                                    </h2>

                                    <button
                                        data-bs-toggle="modal" data-bs-target="#feedbackModal"
                                        class="btn btn-outline-primary w-100">Попробовать
                                    </button>
                                </div>

                            </div>
                            <div class="content">
                                <h5 class="text-primary text-center">Для малого бизнеса</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>

                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3" style="border:none;min-height:290px;">
                                <div class="card-header bg-white pt-4">
                                    <h4 class="text-center">Премиум</h4>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p><i class="fa-solid fa-check mr-2"></i> Полная автоматизация</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Первый месяц бесплатно</p>
                                    <h2 class="gray">1099
                                        <small>₽ / мес</small>
                                    </h2>

                                    <button data-bs-toggle="modal" data-bs-target="#feedbackModal"
                                            class="btn btn-outline-primary w-100">Попробовать
                                    </button>
                                </div>

                            </div>
                            <div class="content">
                                <h5 class="text-primary text-center">Для компаний</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-check mr-2 text-success"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>
                                    <li class="have list-group-item text-center" style="border:none;"><i
                                        class="fa-solid fa-xmark mr-2 text-danger"></i></li>

                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </section>


            <!-- App badge section-->
            <section class="bg-gradient-primary-to-secondary" id="download">
                <div class="container px-5">
                    <h2 class="text-center text-white font-alt mb-4">Get the app now!</h2>
                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center">
                        <a class="me-lg-3 mb-4 mb-lg-0" href="#!"><img class="app-badge"
                                                                       src="/landing/assets/img/google-play-badge.svg"
                                                                       alt="..."/></a>
                        <a href="#!"><img class="app-badge" src="/landing/assets/img/app-store-badge.svg"
                                          alt="..."/></a>
                    </div>
                </div>
            </section>


            <!-- Footer-->
            <footer class="bg-black text-center py-5">
                <div class="container px-5">
                    <div class="text-white-50 small">
                        <div class="mb-2">&copy; Your Website 2023. All Rights Reserved.</div>
                        <a href="#!">Privacy</a>
                        <span class="mx-1">&middot;</span>
                        <a href="#!">Terms</a>
                        <span class="mx-1">&middot;</span>
                        <a href="#!">FAQ</a>
                    </div>
                </div>
            </footer>
            <!-- Feedback Modal-->
            <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-gradient-primary-to-secondary p-4">
                            <h5 class="modal-title font-alt text-white" id="feedbackModalLabel">Заказать обратную
                                связь</h5>
                            <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body border-0 p-4">
                            <form id="contactForm" data-sb-form-api-token="API_TOKEN">
                                <!-- Name input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control"
                                           v-model="feedbackForm.name"
                                           id="name" type="text" placeholder="Enter your name..."
                                           data-sb-validations="required"/>
                                    <label for="name">Ваше Ф.И.О.</label>
                                    <div class="invalid-feedback" data-sb-feedback="name:required">Является обязательным
                                    </div>
                                </div>
                                <!-- Email address input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control"
                                           v-model="feedbackForm.email"
                                           id="email" type="email" placeholder="name@example.com"/>
                                    <label for="email">Ваша почта</label>
                                    <div class="invalid-feedback" data-sb-feedback="email:required">An email is
                                        required.
                                    </div>
                                    <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.
                                    </div>
                                </div>
                                <!-- Phone number input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control"
                                           v-mask="'+7(###)###-##-##'"
                                           v-model="feedbackForm.phone"
                                           id="phone" type="text" placeholder="+7(123)456-78-90"
                                           data-sb-validations="required"/>
                                    <label for="phone">Номер телефона</label>
                                    <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is
                                        required.
                                    </div>
                                </div>
                                <!-- Phone number input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="time"
                                           v-model="feedbackForm.time"
                                           type="time" placeholder="12:00"/>
                                    <label for="phone">Когда вам удобно?</label>
                                    <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is
                                        required.
                                    </div>
                                </div>
                                <!-- Message input-->
                                <div class="form-floating mb-3">
                                    <textarea
                                        v-model="feedbackForm.message"
                                        class="form-control" id="message" type="text"
                                        placeholder="Enter your message here..." style="height: 10rem"
                                        data-sb-validations="required"></textarea>
                                    <label for="message">Сообщение менеджеру</label>
                                    <div class="invalid-feedback" data-sb-feedback="message:required">A message is
                                        required.
                                    </div>
                                </div>
                                <!-- Submit success message-->
                                <!---->
                                <!-- This is what your users will see when the form-->
                                <!-- has successfully submitted-->
                                <div class="d-none" id="submitSuccessMessage">
                                    <div class="text-center mb-3">
                                        <div class="fw-bolder">Form submission successful!</div>
                                        To activate this form, sign up at
                                        <br/>
                                        <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                    </div>
                                </div>
                                <!-- Submit error message-->
                                <!---->
                                <!-- This is what your users will see when there is-->
                                <!-- an error submitting the form-->
                                <div class="d-none" id="submitErrorMessage">
                                    <div class="text-center text-danger mb-3">Error sending message!</div>
                                </div>
                                <!-- Submit Button-->
                                <div class="d-grid">
                                    <button class="btn btn-primary rounded-pill btn-lg disabled" id="submitButton"
                                            type="submit">Отправить заявку
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </template>
    </Layout>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {

        let currentDate = new Date();
        let currentTime = currentDate.getHours() + ":" + currentDate.getMinutes()

        return {
            need_start: false,
            feedbackForm: {
                name: null,
                email: null,
                phone: null,
                time: currentTime,
                message: 'Добрый день! Заинтересовала данная система, хочу запросить перезвон менеджера для обсуждения деталей!'
            }
        }
    },
    computed: {
        ...mapGetters(['getCurrentCompany']),
    },
    mounted() {

    },
    methods: {}
}
</script>
