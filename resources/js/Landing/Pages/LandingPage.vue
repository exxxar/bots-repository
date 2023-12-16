<script setup>
import Layout from "../Layouts/ClientLayout.vue";
import VisitCardConstructor from "../Components/Constructor/VisitCardConstructor.vue";
import ChatWindow from "../Components/Chat/ChatWindow.vue";
import ChatDialog from "../Components/Chat/ChatDialog.vue";
import AudioPlayer from 'vue3-audio-player'
import 'vue3-audio-player/dist/style.css'
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
                                <h1 class="display-1 lh-1 mb-3">СИСТЕМА ЛОЯЛЬНОСТИ</h1>
                                <p class="lead fw-normal text-muted mb-5">для увеличения прибыли
                                    в любом бизнесе
                                    с эффективными
                                    инструментами маркетинга</p>
                                <div class="d-flex flex-column flex-lg-row align-items-center">
                                    <!--                                    <a class="me-lg-3 mb-4 mb-lg-0" href="#!"><img class="app-badge"
                                                                                                                       style="height: 125px;"
                                                                                                                       src="/landing/images/history.jpg"
                                                                                                                       alt="..."/></a>-->

                                    <AudioPlayer
                                        class="w-100"
                                        :option="{
        src: '/promo.mp3',
        title: 'Наша история',

   }"
                                    />
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

                                                <source src="/videos/vid3.mp4" type="video/mp4"/>
<!--                                                <source src="/landing/assets/img/demo-screen.mp4" type="video/mp4"/>
                                       -->     </video>
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
                            <h1 class="text-white mb-5 custom-h1-1">Наши преимущества</h1>
                            <div class="custom-text-list mb-4">
                                <ul>
                                    <li><i class="fa-solid fa-check"></i> Удобное меню в телеграм</li>
                                    <li><i class="fa-solid fa-check"></i> КэшБэк сервис</li>
                                    <li><i class="fa-solid fa-check"></i> Реферальная программа</li>
                                    <li><i class="fa-solid fa-check"></i> Колесо фортуны</li>
                                    <li><i class="fa-solid fa-check"></i> Встроенный магазин</li>
                                    <li><i class="fa-solid fa-check"></i> Оплаты, рассылки, интеграции и т.д.</li>
                                </ul>
                            </div>
                            <img src="landing/images/04-телефон.png" alt="..."/>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <a href="/cashman.pdf" target="_blank" class="btn btn-outline-light p-3 shadow" style="min-width: 300px;">
                            Скачать презентацию
                        </a>
                    </div>
                </div>
            </aside>

            <section class="bg-light py-5">
                <div class="container px-5">
                    <div class="row gx-5 justify-content-center">
                        <div class="col-xxl-8">
                            <div class="text-center my-5">
                                <h2 class="display-5 fw-bolder"><span class="text-gradient d-inline">РЕФЕРАЛЬНАЯ ПРОГРАММА</span>
                                </h2>
                                <p class="lead fw-light mb-4">в виде бонусов
                                    за приглашения и колеса
                                    фортуны
                                    мотивирует клиентов
                                    приглашать друзей.</p>
                                <p class="text-muted d-flex justify-content-center align-items-center">База растет сама
                                    по себе
                                    и легко управляется
                                    через телеграм<i class="fa-brands fa-telegram text-gradient fs-2 ml-2"></i></p>
                                <div class="d-flex justify-content-center fs-2 gap-4 flex-column">
                                    <img src="landing/images/08-человечки.png" alt=""
                                         style="max-height:300px;object-fit:contain;">

                                    <div class="card  shadow bg-gradient-primary-to-secondary">
                                        <div class="card-body">
                                            <h2 class="text-white text-uppercase "><strong>КэшБэк сервис = </strong>
                                            </h2>
                                            <h2 class="text-white text-uppercase">клиенты = деньги = свобода</h2>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="cases" class="py-5">
                <div class="container px-sm-5 px-0 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span
                            class="text-gradient d-inline">Кейсы</span></h1>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-11 col-xl-9 col-xxl-8">
                            <!-- Project Card 1-->
                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="p-5">
                                                <h2 class="fw-bolder">Чат бот бизнес</h2>
                                                <ul>
                                                    <li>
                                                        <p>Удобное меню в телеграм</p>
                                                    </li>
                                                    <li>
                                                        <p>КэшБэк сервис</p>
                                                    </li>
                                                    <li>
                                                        <p>Многоуровневая реферальная программа</p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Сбор и управление клиентской базой
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Колесо фортуны
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>Воронка продаж</p>
                                                    </li>
                                                    <li>
                                                        <p>Пуш-уведомления</p>
                                                    </li>
                                                    <li>
                                                        <p>Оценки и отзывы</p>
                                                    </li>
                                                    <li>
                                                        <p>Инстаграм квест</p>
                                                    </li>
                                                    <li>
                                                        <p>Рассылки по базе</p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Принудительная подписка на Ваш телеграм-канал
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>Полная поддержка на протяжении абонентской платы</p>
                                                    </li>
                                                </ul>

                                                <div class="d-flex justify-content-center mt-5">
                                                    <button type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#feedbackModal"
                                                            class="btn bg-gradient-primary-to-secondary rounded-pill text-white shadow px-5 py-3">
                                                        Заказать
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                            <img class="img-fluid p-3"
                                                 src="/landing/images/21-телефон чат бот бизнес.png"
                                                 alt="...">
                                        </div>
                                    </div>
                                    <!--                                    <div class="d-flex align-items-center ">
                                                                            <div class="p-5">
                                                                                <h2 class="fw-bolder">Колесо фортуны</h2>
                                                                                <ul>
                                                                                    <li>Увеличивает конверсию рекламы в 10 раз</li>
                                                                                    <li>Клиенты рекомендуют друзьям</li>
                                                                                    <li>С помощью разовых скидок и КэшБэка приводит клиента и делает его постоянным</li>
                                                                                </ul>
                                                                            </div>
                                                                            <img class="img-fluid p-3" src="landing/images/09-колесо.png"
                                                                                 alt="...">
                                                                        </div>-->
                                </div>
                            </div>
                            <!-- Project Card 2-->
                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                            <img class="img-fluid p-3"
                                                 src="/landing/images/23-телефон чат бот бизнес магазин.png"
                                                 alt="...">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="p-5">
                                                <h2 class="fw-bolder">Чат бот бизнес + магазин</h2>
                                                <ul>
                                                    <li>
                                                        <p>Удобное меню в телеграм</p>
                                                    </li>
                                                    <li>
                                                        <p>КэшБэк сервис</p>
                                                    </li>
                                                    <li>
                                                        <p>Многоуровневая реферальная программа</p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Сбор и управление клиентской базой
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Колесо фортуны
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>Воронка продаж</p>
                                                    </li>
                                                    <li>
                                                        <p>Пуш-уведомления</p>
                                                    </li>
                                                    <li>
                                                        <p>Оценки и отзывы</p>
                                                    </li>
                                                    <li>
                                                        <p>Инстаграм квест</p>
                                                    </li>
                                                    <li>
                                                        <p>Рассылки по базе</p>
                                                    </li>
                                                    <li>
                                                        <p>Полная поддержка на протяжении абонентской платы</p>
                                                    </li>
                                                    <li>
                                                        <p>Встроенный магазин</p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Стильный дизайн
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Автоматическая оплата товаров и услуг
                                                        </p>
                                                    </li>
                                                </ul>

                                                <div class="d-flex justify-content-center mt-5">
                                                    <button type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#feedbackModal"
                                                            class="btn bg-gradient-primary-to-secondary rounded-pill text-white shadow px-5 py-3">
                                                        Заказать
                                                    </button>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--                                    <div class="d-flex align-items-center ">
                                                                            <div class="p-5">
                                                                                <h2 class="fw-bolder">Колесо фортуны</h2>
                                                                                <ul>
                                                                                    <li>Увеличивает конверсию рекламы в 10 раз</li>
                                                                                    <li>Клиенты рекомендуют друзьям</li>
                                                                                    <li>С помощью разовых скидок и КэшБэка приводит клиента и делает его постоянным</li>
                                                                                </ul>
                                                                            </div>
                                                                            <img class="img-fluid p-3" src="landing/images/09-колесо.png"
                                                                                 alt="...">
                                                                        </div>-->
                                </div>
                            </div>
                            <!-- Project Card 3-->
                            <div class="card overflow-hidden shadow rounded-4 border-0 mb-5">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="p-5">
                                                <h2 class="fw-bolder">Чат бот визитка</h2>
                                                <ul>
                                                    <li>
                                                        <p>Удобное меню в телеграм</p>
                                                    </li>

                                                    <li>
                                                        <p>
                                                            Сбор и управление клиентской базой
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>
                                                            Ведение блога
                                                        </p>
                                                    </li>

                                                </ul>

                                                <img src="images/27-логотип внизу.png" alt="">

                                                <div class="d-flex justify-content-center mt-5">
                                                    <button type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#feedbackModal"
                                                            class="btn bg-gradient-primary-to-secondary rounded-pill text-white shadow px-5 py-3">
                                                        Заказать
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                            <img class="img-fluid p-3"
                                                 src="landing/images/25-телефон чат бот визитка.png"
                                                 alt="...">
                                        </div>
                                    </div>
                                    <!--                                    <div class="d-flex align-items-center ">
                                                                            <div class="p-5">
                                                                                <h2 class="fw-bolder">Колесо фортуны</h2>
                                                                                <ul>
                                                                                    <li>Увеличивает конверсию рекламы в 10 раз</li>
                                                                                    <li>Клиенты рекомендуют друзьям</li>
                                                                                    <li>С помощью разовых скидок и КэшБэка приводит клиента и делает его постоянным</li>
                                                                                </ul>
                                                                            </div>
                                                                            <img class="img-fluid p-3" src="landing/images/09-колесо.png"
                                                                                 alt="...">
                                                                        </div>-->
                                </div>
                            </div>

                            <div class="w-100 d-flex justify-content-center mt-5">
                                <button
                                    data-bs-toggle="modal"
                                    data-bs-target="#feedbackModal"
                                    class="btn btn-outline-primary p-3">Хочу попробовать вживую</button>
                            </div>


                        </div>
                    </div>
                </div>
            </section>

            <!--            <section id="cases">
                            <div class="container px-5">

                                <div class="row gx-5 justify-content-center">
                                    <div class="col-12">
                                        <ChatDialog :domain="'isushibot'"></ChatDialog>
                                    </div>
                                </div>


                            </div>
                        </section>- -->

            <section class="py-5 bg-gradient-primary-to-secondary text-white" v-if="!need_start">
                <div class="container px-5 my-5">
                    <h1 class="text-white text-center mb-5 custom-h1-1">Этапы создания</h1>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-md-6">

                            <div class="custom-text-list mb-4 fs-6">
                                <ol>
                                    <li>
                                        <p>Оставляем заявку на создание (имя, номер телефона, ниша)</p>
                                    </li>

                                    <li>
                                        <p>Наш менеджер связывается с Вами и берёт всю информацию
                                            для создания Чат Бота
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            Во время создания Бота мы отправляем рекомендации по
                                            внедрению инструментов маркетинга
                                            и согласовываем всё
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            После создания Чат Бота мы предоставляем стратегию
                                            развития и внедрения системы в Ваш Бизнес
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            Через 2 недели после запуска анализируем результат и статистику,
                                            улучшаем и настраиваем рассылку по базе
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            На протяжении всего времени абонентской платы (месяц-год) мы
                                            поддерживаем систему технически, помогаем и настраиваем новые инструменты,
                                            меняем информацию в Боте при необходимости
                                        </p>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
                            <img class="img-fluid" src="landing/images/20-робот.png" style="width:300px;">


                        </div>
                    </div>

                    <div class="row gx-5 justify-content-center mt-5">
                        <div class="col-md-6">
                            <div class="card shadow" style="border-radius:75px;">
                                <div class="card-body">
                                    <h3 class="text-gradient text-center">Срок создания бота</h3>
                                    <p class="text-gradient text-center" style="font-size: 56px;line-height: 100%;">до
                                        <strong>7</strong> дней</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--                    <div class="text-center">
                                            <h2 class="display-4 fw-bolder mb-4">Давайте создадим что-нибудь вместе!</h2>
                                            <a class="btn btn-outline-light btn-lg px-5 py-3 fs-6 fw-bolder"
                                               @click="need_start = true">Я создам!</a>
                                            <a class="btn btn-outline-light btn-lg px-5 py-3 fs-6 fw-bolder ml-2"
                                               data-bs-toggle="modal" data-bs-target="#feedbackModal">Позвать помошника</a>
                                        </div>-->
                </div>
            </section>

            <section class="py-5">
                <div class="container px-sm-5 px-0 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span
                            class="text-gradient">Инструменты маркетинга</span></h1>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <div class="col-lg-8">
                            <div class="d-flex justify-content-center">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <h2 class="fw-bolder">Колесо фортуны</h2>
                                    <ul style="list-style:none;text-align:center;">
                                        <li>Увеличивает конверсию рекламы в 10 раз</li>
                                        <li>Клиенты рекомендуют друзьям</li>
                                        <li>С помощью разовых скидок и КэшБэка приводит клиента и делает его
                                            постоянным
                                        </li>
                                    </ul>

                                    <img class="img-fluid p-3"
                                         style="max-width:300px;"
                                         src="landing/images/09-колесо.png"
                                         alt="...">
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-8">

                            <div class="d-flex justify-content-center">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <h2 class="fw-bolder">Магазин в телеге</h2>
                                    <ul style="list-style:none;text-align:center;">
                                        <li>Выбор товара или услуг</li>
                                        <li>Оплата + доставка</li>
                                        <li>Автоматизированное управление</li>
                                        <li>Полностью заменяет интернет-магазин</li>
                                        <li>Встроенная воронка продаж</li>
                                        <li>КэшБэк</li>
                                        <li>Рекомендации друзьям</li>
                                        <li>Рассылка новых товаров и акций</li>
                                    </ul>

                                    <img class="img-fluid"
                                         style="max-width:500px;"
                                         src="landing/images/11-телефон с магазином.png"
                                         alt="...">
                                </div>

                            </div>


                        </div>


                        <div class="w-100 d-flex justify-content-center mt-5">
                            <button
                                data-bs-toggle="modal"
                                data-bs-target="#feedbackModal"
                                class="btn btn-outline-primary p-3">Хочу попробовать вживую</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="cta" style="background-image: url('/landing/images/imgpreview.jpg');">
                <div class="cta-content">
                    <div class="container px-5">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="text-white display-1 lh-1 mb-4">
                                    А вот что<br>мы уже создали
                                </h2>
                                <a class="btn btn-outline-light py-3 px-4 rounded-pill mb-3"
                                   data-bs-toggle="modal"
                                   data-bs-target="#feedbackModal"
                                   href="#prices"
                                   target="_blank">
                                    Начни создавать с нами</a>

                            </div>
                            <div class="col-md-6">
                                <perfect-scrollbar
                                    ref="scroll"
                                    class="ps-container w-100">

                                    <div class="row">
                                        <div class="col-md-4" v-for="bot in bots">
                                            <a
                                                target="_blank"
                                                :href="'https://t.me/'+(bot.bot_domain||'nextitgroup_bot')"
                                                class="card bot-card">
                                                <div class="card-body">
                                                    <img
                                                        v-if="bot.image"
                                                        v-lazy="'/images-by-bot-id/'+bot.id+'/'+bot.image">
                                                    <i v-else class="fa-solid fa-robot"></i>
                                                    <p class="mb-0"> {{ bot.bot_domain }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </perfect-scrollbar>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- App constructor section-->
            <section id="constructor" class="py-5 bg-gradient-primary-to-secondary text-white" v-if="need_start">
                <div class="container px-5 my-5">


                    <!--                    <VisitCardConstructor :start="0"/>

                                        <div class="w-100 d-flex justify-content-center mt-5">
                                            <button
                                                title="Перезапустить"
                                                class="btn btn-link" @click="need_start = false">
                                                <i class="fa-solid fa-arrows-rotate text-white fw-bold" style="font-size:26px;"></i>
                                            </button>
                                        </div>-->

                </div>
            </section>
            <!-- App features section-->
            <section id="features">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center">
                        <div class="col-lg-8 order-lg-1 mb-5 mb-lg-0">
                            <div class="container-fluid px-5">
                                <h1 class="text-center mb-4 text-gradient">ЭФФЕКТИВНОСТЬ СИСТЕМЫ ЛОЯЛЬНОСТИ</h1>
                                <h4 class="text-center">от <img src="/landing/images/18-лого в эффективности.png"
                                                                style="width:200px;" class="mx-5" alt=""> в цифрах </h4>
                                <div class="row gx-5">
                                    <div class="col-md-6 mb-5">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-phone icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">90%</h3>
                                            <p class="text-muted mb-0">гостей присоединяются к системе, чтобы получить
                                                КэшБэк
                                                и выиграть подарок или скидку в колесе фортуны
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-5">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-cash-coin icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">69%</h3>
                                            <p class="text-muted mb-0">гостей списывают КэшБэк в течении месяца (т.е.
                                                возвращаются снова)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-5 mb-md-0">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class="bi-people-fill icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">32%</h3>
                                            <p class="text-muted mb-0">гостей делятся рефералкой с 1-3 друзьями</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Feature item-->
                                        <div class="text-center">
                                            <i class=" bi-gift icon-feature text-gradient d-block mb-3"></i>
                                            <h3 class="font-alt">80%</h3>
                                            <p class="text-muted mb-0">гостей используют полученный подарок в колесе
                                                фортуны в первые 2 недели</p>
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
                                    <div class="device" data-device="iPhoneX" data-orientation="portrait"
                                         data-color="black">
                                        <div class="screen bg-black">
                                            <video muted="muted" autoplay="" loop=""
                                                   style="max-width: 100%; height: 100%">
                                                <source src="/videos/vid2.mp4" type="video/mp4"/>
                                            </video>
                                        </div>
                                    </div>
                                    <!--                                    <div class="row">
                                                                            <div class="col-12">
                                                                                <ChatDialog :domain="'isushibot'"></ChatDialog>
                                                                            </div>
                                                                        </div>-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic features section-->
            <section class="bg-light">
                <div class="container px-5">
                    <div class="row gx-5 align-items-center justify-content-center justify-content-lg-center">
                        <div class="col-12 col-lg-8">
                            <h2 class="display-4 lh-1 mb-4 text-center">Интеграции с другими системами</h2>
                            <p class="lead fw-normal text-muted mb-5 mb-lg-0">Наши программисты готовы подключить любую
                                интересующую вас систему,
                                которая уже есть у нас, или же добавить абсолютно новую интеграцию и всё это в рамках
                                подписки!</p>

                            <div class="row">
                                <div class="col-md-6 p-5 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img-fluid" src="landing/images/amocrm-logo-white_2_.svg" alt="">
                                </div>
                                <div class="col-md-6 p-5 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img-fluid" src="landing/images/15-лого2.png" alt="">
                                </div>
                                <div class="col-md-6 p-5 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img-fluid" src="landing/images/16-лого3.png" alt="">
                                </div>
                                <div class="col-md-6 p-5 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img-fluid" src="landing/images/17-лого4.png" alt="">
                                </div>
                                <div class="col-md-6 p-5 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img-fluid" src="landing/images/yclients.png" alt="">
                                </div>
                                <div class="col-md-6 p-5 col-12 d-flex justify-content-center align-items-center">
                                    <img class="img-fluid" src="landing/images/Bitrix24-logo-rusvg_.png" alt="">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- Call to action section-->
            <section class="cta" id="lets-start" style="background-image:url('/landing/images/robot-cherep-nauchno-fantasticheskie-fantastika.jpg')">
                <div class="cta-content">
                    <div class="container px-5">
                        <h2 class="text-white display-1 lh-1 mb-4">
                            Прекрати ждать
                            <br/>
                            Начни создавать
                        </h2>
                        <a class="btn btn-outline-light py-3 px-4 rounded-pill"
                           data-bs-toggle="modal"
                           data-bs-target="#feedbackModal"
                           href="#prices"
                           target="_blank">Поехали!</a>
                    </div>
                </div>
            </section>

            <section id="prices">
                <div class="container px-5">

                    <div class="row gx-5 justify-content-center">
                        <div class="col-xxl-8">
                            <div class="text-center my-5"><h2 class="display-5 fw-bolder"><span
                                class="text-gradient d-inline">Наши тарифы</span></h2>
                                <p class="lead fw-light mb-4">Хотим предоставить наиболее удобные тарифы для каждого</p>
                                <p class="text-muted">Наша команда окажется каждому клиенту полный комплекс наших услуг и индивидуальный подход.
                                Менеджеры помогут собрать Вашу систему на нашей платформе. А программисты воплотят самые смелые Ваши идеи.
                                </p>

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
                                    <li class="list-group-item" v-for="item in services">{{ item.title }}</li>

                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3" style="border:none;min-height:290px;">
                                <div class="card-header bg-white pt-4">
                                    <h4 class="text-center">Визитка</h4>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p><i class="fa-solid fa-check mr-2"></i> Многоуровневые автоворонки</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Первый месяц бесплатно</p>
                                    <h2 class="gray">2083
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


                                    <li class="have list-group-item text-center"
                                        v-for="item in services"
                                        style="border:none;">
                                        <i v-if="item.in_start" class="fa-solid fa-check mr-2 text-success"></i>
                                        <i v-if="!item.in_start" class="fa-solid fa-xmark mr-2 text-danger"></i>
                                    </li>


                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3" style="border:none;min-height:290px;">
                                <div class="card-header bg-white pt-4">
                                    <h4 class="text-center">Бизнес</h4>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p><i class="fa-solid fa-check mr-2"></i> Автоворонки и рассылки</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Первый месяц бесплатно</p>
                                    <h2 class="gray">2580
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
                                    <li class="have list-group-item text-center"
                                        v-for="item in services"
                                        style="border:none;">
                                        <i v-if="item.in_business" class="fa-solid fa-check mr-2 text-success"></i>
                                        <i v-if="!item.in_business" class="fa-solid fa-xmark mr-2 text-danger"></i>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card mb-3" style="border:none;min-height:290px;">
                                <div class="card-header bg-white pt-4">
                                    <h4 class="text-center">Бизнес+магазин</h4>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <p><i class="fa-solid fa-check mr-2"></i> Полная автоматизация</p>
                                    <p><i class="fa-solid fa-check mr-2"></i> Первый месяц бесплатно</p>
                                    <h2 class="gray">2916
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
                                    <li class="have list-group-item text-center"
                                        v-for="item in services"
                                        style="border:none;">
                                        <i v-if="item.in_premium" class="fa-solid fa-check mr-2 text-success"></i>
                                        <i v-if="!item.in_premium" class="fa-solid fa-xmark mr-2 text-danger"></i>
                                    </li>

                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="bg-gradient-primary-to-secondary" id="download">
                <div class="container px-5">
                    <div class=" d-flex align-items-center justify-content-center ">
                        <div class="image">
                            <img src="landing/images/19-лупа.png" alt="">
                        </div>
                        <h1 class="text-white text-uppercase">
                            <strong>Рост базы клиентов 450%</strong><br>
                            в месяц
                        </h1>


                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <button type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#feedbackModal"
                                class="btn btn-outline-light p-3 shadow" style="min-width:300px;">Оформить<br>
                            заявку сейчас
                        </button>
                    </div>

                </div>
            </section>


            <!-- Footer-->
            <footer class="bg-black text-center py-5">
                <div class="container px-5">
                    <div class="text-white-50 small">
                        <div class="mb-2">&copy; Your-cashman.com 2023</div>
                        <a href="#!">Политика конфиденциальности</a>
                        <span class="mx-1">&middot;</span>
                        <a href="#!">Правила использования</a>
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
                            <form id="contactForm" v-on:submit.prevent="submitMail">
                                <!-- Name input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control"
                                           v-model="feedbackForm.name"
                                           id="name" type="text" placeholder="Введите ваше имя..."
                                           data-sb-validations="required" required/>
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
                                           data-sb-validations="required" required/>
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
                                        placeholder="Текст вашего сообщения" style="height: 10rem"
                                        data-sb-validations="required" required></textarea>
                                    <label for="message">Сообщение менеджеру</label>
                                    <div class="invalid-feedback" data-sb-feedback="message:required">A message is
                                        required.
                                    </div>
                                </div>
                                <!-- Submit success message-->
                                <!---->
                                <!-- This is what your users will see when the form-->
                                <!-- has successfully submitted-->
                                <div v-if="success"
                                     id="submitSuccessMessage">
                                    <div class="text-center mb-3">
                                        <div class="fw-bolder">Спасибо! Вы успешно отправили заявку!</div>
                                       Наш менеджер свяжется с вами!
                                        <br/>
                                    </div>
                                </div>
                                <!-- Submit error message-->
                                <!---->
                                <!-- This is what your users will see when there is-->
                                <!-- an error submitting the form-->
                                <div
                                    v-if="error"
                                    id="submitErrorMessage">
                                    <div class="text-center text-danger mb-3">Ошибка отправки заявки</div>
                                </div>
                                <!-- Submit Button-->
                                <div class="d-grid">
                                    <button class="btn btn-primary rounded-pill btn-lg" id="submitButton"
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
            success:false,
            error:false,
            need_start: false,
            services: [
                {
                    title: "Сбор заявок",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Редактор ботов",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Работа с медиа-файлами",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Внутренняя CRM",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Редактор ботов",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Перенос клиентской базы",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Инструменты оператора",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Статистика",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },

                {
                    title: "Запись/бронирование",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Система Cashback",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Колесо фортуны",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Instagram Quest",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Работа с API",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },

                {
                    title: "Высокая нагрузка",
                    in_start: true,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Оплаты в боте",
                    in_start: false,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Программируемая логика",
                    in_start: false,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Рассылки",
                    in_start: false,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Bitrix, AmoCRM",
                    in_start: false,
                    in_business: true,
                    in_premium: true
                },
                {
                    title: "Интеграция интернет-магазина",
                    in_start: false,
                    in_business: false,
                    in_premium: true
                },
                {
                    title: "Внутренний Landing-page",
                    in_start: false,
                    in_business: false,
                    in_premium: true
                }

            ],
            bots: [],
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
        ...mapGetters(['getBots']),
    },
    mounted() {
        this.loadBots()
    },
    methods: {
        submitMail() {
            this.success = false
            this.error = false

            let data = new FormData();
            Object.keys(this.feedbackForm)
                .forEach(key => {
                    const item = this.feedbackForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });



            this.$store.dispatch("sendToChannel", {
                mailForm: data
            }).then((response) => {
                this.load = true

                this.success = true
                let currentDate = new Date();
                let currentTime = currentDate.getHours() + ":" + currentDate.getMinutes()

                this.feedbackForm = {
                    name: null,
                    email: null,
                    phone: null,
                    time: currentTime,
                    message: 'Добрый день! Заинтересовала данная система, хочу запросить перезвон менеджера для обсуждения деталей!'
                }
                this.$notify({
                    title: "CashMan",
                    text: "Сообщение отправлено!",
                    type: 'success'
                });
            }).catch(err => {

                this.error = true
                this.$notify({
                    title: "CashMan",
                    text: "Упс... ошибочка!",
                    type: 'error'
                });
            })


        },
        play() {
            let audio = new Audio('/promo.mp3');
            audio.play();
        },
        loadBots() {
            this.$store.dispatch("loadSimpleBots").then(() => {
                this.bots = this.getBots || []
            })
        }
    }
}
</script>
<style lang="scss">
.custom-text-list {
    ul {
        list-style: none;
        font-size: 24px;
        font-weight: 100;
        color: white;
    }
}

.custom-h1-1 {
    font-weight: 100;
    font-size: 54px;
}

.ps-container {
    position: relative;
    height: 500px;
    width: 100%;
    max-height: inherit;
    padding: 15px;
    box-sizing: border-box;
}

.bot-card {
    background: transparent;
    border: 1px white solid;
    margin-bottom: 5px;
    height: 220px !important;
    overflow: hidden;
    text-decoration: none;

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;


        i {
            padding: 40px;
            font-size: 40px;
            color: white;
        }

        img {
            width: 100%;
            object-fit: cover;
            height: 130px;
        }

        p {
            padding: 10px;
            text-align: center;
            color: white;
            font-size: 12px;
            word-wrap: break-word;
            word-break: break-word;
        }
    }


    &:hover {
        background-color: white;
        cursor: pointer;
        i,
        p {
            color: black;
        }
    }
}
</style>
