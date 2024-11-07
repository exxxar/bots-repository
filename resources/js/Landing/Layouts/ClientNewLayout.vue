<script setup>

import {Head} from '@inertiajs/vue3'

</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <notifications position="top right"/>

    <div class="main-wrapper">
        <!-- start header-->
        <header class="header" id="header">
            <!-- start top-nav-->
            <div class="top-nav block-with-lazy">
                <div class="container">
                    <div class="top-nav__row row">
                        <div class="col col-lg-auto">
                            <a class="top-nav__logo" href="/">
                                <img src="storage/site/img/svg/logo.svg" alt="" loading="lazy"/>
                            </a>
                        </div>
                        <div class="col-0 col-lg order-lg-0 order-last">
                            <div class="menu-mobile menu-mobile--js">
                                <div class="container p-lg-0">
                                    <div class="menu-mobile__inner">
                                        <a class="top-nav__login" href="#">
                                            <svg class="icon icon-logIn ">
                                                <use xlink:href="storage/site/img/svg/sprite.svg#logIn"></use>
                                            </svg>
                                            Кабинет
                                        </a>
                                        <nav>
                                            <ul class="menu" itemscope="itemscope"
                                                itemtype="http://schema.org/SiteNavigationElement">
                                                <li class="menu-item menu-item-active" itemprop="item">
                                                    <a href="/" itemprop="url">О сервисе</a>
                                                </li>
                                                <li class="menu-item " itemprop="item">
                                                    <a href="/tarif" itemprop="url">Тарифы</a></li>

                                                <li class="menu-item " itemprop="item">
                                                    <a href="/blog" itemprop="url">Блог</a></li>
                                                <li class="menu-item " itemprop="item">
                                                    <a href="/wiki" itemprop="url">База знаний</a></li>

                                                <li class="menu-item " itemprop="item">
                                                    <a href="/contact" itemprop="url">Контакты</a></li>

                                                <li class="menu-item menu-item-has-children menu-dropdown--js">
                                                    <div class="dropdownBtn dropdownBtn--js">Еще</div>
                                                    <ul class="sub-menu dropdown-container">
                                                        <li class="menu-item">
                                                            <a class="disabled" href="#" itemprop="url"
                                                               onclick="return false">
                                                                Бот под ключ<span class="badge bg-success">скоро</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </nav>
                                        <div class="top-nav__mob-content">
                                            <a class="top-nav__tel" href="tel:+79276990585">
                                                +7 (927) 699-05-85
                                            </a>
                                            <div class="top-nav__text">
                                                г. Самара, ул. Партизанская 86, оф. 341
                                            </div>
                                            <a class="top-nav__mail" href="mailto:">
                                                info@nextit.ru
                                            </a>
                                            <a class="top-nav__tech-help" href="https://t.me/exxxar" target="_blank">
                                                <div class="top-nav__icon-wrap">
                                                    <svg class="icon icon-telegram ">
                                                        <use
                                                            xlink:href="storage/site/img/svg/sprite.svg#telegram"></use>
                                                    </svg>
                                                </div>
                                                Техподдержка в Telegram
                                            </a>
                                            <div class="soc">
                                                <a class="soc__item" href="https://t.me/exxxar" target="_blank"
                                                   style="--iconColor: #009FE3;">
                                                    <svg class="icon icon-telegram ">
                                                        <use
                                                            xlink:href="storage/site/img/svg/sprite.svg#telegram"></use>
                                                    </svg>
                                                </a>
                                                <a class="soc__item" href="#" target="_blank"
                                                   style="--iconColor: #E21A20;">
                                                    <svg class="icon icon-youtube ">
                                                        <use xlink:href="storage/site/img/svg/sprite.svg#youtube"></use>
                                                    </svg>
                                                </a>
                                                <a class="soc__item" href="https://vk.com/exxxr" target="_blank"
                                                   style="--iconColor: #0077FF;">
                                                    <svg class="icon icon-vk ">
                                                        <use xlink:href="storage/site/img/svg/sprite.svg#vk"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                            <a class="top-nav__lang" href="/en">En<img
                                                src="storage/site/img/svg/english.svg" alt="" loading="lazy"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-auto ms-auto">
                            <a class="top-nav__btn-reg btn btn-outline-primary"
                               href="#">Войти</a>
                        </div>
                        <div class="col-auto d-lg-none">
                            <div class="toggle-menu-mobile toggle-menu-mobile--js d-lg-none">
                                <span></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end top-nav-->
        </header>
        <!-- end header-->
        <main>
            <slot/>
        </main>
        <footer class="footer section">
            <div class="container">
                <div class="footer__wrapper">
                    <div class="footer__row row">
                        <div class="col-md-3 order-1">
                            <a class="footer__logo" href="#"><img src="storage/site/img/svg/logo.svg" alt=""
                                                                  loading="lazy"/>
                            </a>
                            <div class="footer__text-wrap">
                                <p>ИП Каспор Н.А.</p>
                                <p>ИНН 636205134795</p>
                                <p>ОГРНИП 313633019800069</p>
                                <p>Соответствует 54-ФЗ и 152-ФЗ</p>
                            </div>
                        </div>
                        <div class="col-md-auto d-none d-md-block order-first order-md-2">
                            <div class="footer__wrap">
                                <div class="footer__title">Основные разделы
                                </div>
                                <ul>
                                    <li><a href="/">О сервисе</a></li>
                                    <li><a href="/tarif">Тарифы</a></li>
                                    <li><a href="/referal">Партнерка</a></li>
                                    <li><a href="/blog">Блог</a></li>
                                    <li><a href="/wiki">База знаний</a></li>
                                    <li><a href="/contact">Контакты</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto d-none d-md-block order-md-3">
                            <div class="footer__wrap footer__wrap--2">
                                <div class="footer__title">Еще</div>
                                <ul>
                                    <!-- <li>
                                <a href="/develop">API</a>
                            </li> -->
                                    <li>
                                        <a class="disabled" href="#" onclick="return false">
                                            Бот под ключ<span class="badge bg-success">скоро</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col order-md-4">
                            <div class="footer__link-wrap">

                                <a class="footer__btn btn btn-outline-primary" href="#">Личный
                                    кабинет</a>
                                <a class="footer__tel" href="tel:+79276990585">
                                    +7 (927) 699-05-85
                                </a>
                                <div class="footer__text">
                                    г. Самара, ул. Партизанская 88
                                </div>
                                <a class="footer__mail" href="mailto:">
                                    info@nextit.ru
                                </a>
                                <a class="footer__tech-help" href="https://t.me/exxxar" target="_blank">
                                    <div class="footer__icon-wrap">
                                        <svg class="icon icon-telegram ">
                                            <use xlink:href="storage/site/img/svg/sprite.svg#telegram"></use>
                                        </svg>
                                    </div>
                                    Техподдержка в Telegram
                                </a>
                                <div class="soc">
                                    <a class="soc__item" href="https://t.me/exxxar" target="_blank"
                                       style="--iconColor: #009FE3;">
                                        <svg class="icon icon-telegram ">
                                            <use xlink:href="storage/site/img/svg/sprite.svg#telegram"></use>
                                        </svg>
                                    </a>
                                    <a class="soc__item" href="#" target="_blank" style="--iconColor: #E21A20;">
                                        <svg class="icon icon-youtube ">
                                            <use xlink:href="storage/site/img/svg/sprite.svg#youtube"></use>
                                        </svg>
                                    </a>
                                    <a class="soc__item" href="https://vk.com/exxxar" target="_blank"
                                       style="--iconColor: #0077FF;">
                                        <svg class="icon icon-vk ">
                                            <use xlink:href="storage/site/img/svg/sprite.svg#vk"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 order-3 order-md-5">
                            <div class="footer__inner-row row">
                                <div class="col-md-auto"><a href="/politics">Политика конфиденциальности</a></div>
                                <div class="col-md-auto"><a href="/terms">Пользовательское соглашение</a></div>
                                <div class="col-md-auto"><a href="/partner-terms">Условия партнерской программы</a>
                                </div>
                                <div class="col-md-auto ms-auto">
                                    <a class="footer__lang" href="/en">En<img src="storage/site/img/svg/english.svg"
                                                                              alt="" loading="lazy"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer__bottom-row row">
                        <div class="col-md order-2 order-md-1">
                            <p>© 2021 - <span></span> NextIt. Все права защищены</p>
                        </div>
                        <div class="col-md text-md-center order-1 order-md-2">
                            <iframe src="https://yandex.ru/sprav/widget/rating-badge/13664003329" width="150"
                                    height="50" frameborder="0"></iframe>
                        </div>
                        <div class="col-md text-md-end order-3 order-md-3">
                            <div class="footer__scrollTop footer__scrollTop--js">Наверх
                                <div class="footer__scrollTop-icon">
                                    <svg class="icon icon-chevron-up ">
                                        <use xlink:href="storage/site/img/svg/sprite.svg#chevron-up"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div id="modal-stories" style="display: none">
            <div class="modal-slider">
                <div class="modal-slider__arrow-wrap">
                    <div class="modal-slider__status-line-wrap"><span></span>
                    </div>
                    <div class="modal-slider__slider modal-slider__slider--js swiper">
                        <div class="swiper-wrapper">
                            <div class="modal-slider__slide swiper-slide">
                                <div class="modal-stories"
                                     style="--storiesBg: linear-gradient(0deg, rgba(245, 176, 77, 0.12), rgba(245, 176, 77, 0.12))">
                                    <div class="modal-stories__icon-wrap"><img src="storage/site/img/stories-icon-1.png"
                                                                               alt="" loading="lazy"/>
                                    </div>
                                    <div class="h5">Дарим 1000 ₽ в&nbsp;качестве бонуса за регистрацию</div>
                                    <p>Просто создайте аккаунт и получите 1000 ₽ на баланс личного кабинета</p>
                                    <a class="modal-stories__btn btn btn-warning" href="/cabinet">Зарегистрироваться</a>
                                </div>
                            </div>
                            <div class="modal-slider__slide swiper-slide">
                                <div class="modal-stories"
                                     style="--storiesBg: linear-gradient(0deg, rgba(111, 100, 236, 0.12), rgba(111, 100, 236, 0.12))">
                                    <div class="modal-stories__icon-wrap"><img src="storage/site/img/stories-icon-2.png"
                                                                               alt="" loading="lazy"/>
                                    </div>
                                    <div class="h5">Начислим 50% сверху, при пополнении баланса от 2000 ₽</div>
                                    <p>Пополнить баланс можно картой онлайн или по выставленному счету.</p>
                                    <a class="modal-stories__btn btn btn-info" href="/cabinet">Пополнить баланс</a>
                                </div>
                            </div>
                            <div class="modal-slider__slide swiper-slide">
                                <div class="modal-stories"
                                     style="--storiesBg: linear-gradient(0deg, rgba(75, 177, 75, 0.12), rgba(75, 177, 75, 0.12))">
                                    <div class="modal-stories__icon-wrap"><img src="storage/site/img/stories-icon-3.png"
                                                                               alt="" loading="lazy"/>
                                    </div>
                                    <div class="h5">Зарабатывайте вместе с нами!</div>
                                    <p>Станьте партнером сервиса и&nbsp;получайте от 40 до 50% с каждого
                                        платежа привлеченного вами клиента.</p>
                                    <a class="modal-stories__btn btn btn-success" href="/referal">Узнать подробнее</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-hand swiper-button-hand-prev swiper-button-prev">
                        <svg class="icon icon-chevron-left ">
                            <use xlink:href="storage/site/img/svg/sprite.svg#chevron-left"></use>
                        </svg>
                    </div>
                    <div class="swiper-button-hand swiper-button-hand-next swiper-button-next">
                        <svg class="icon icon-chevron-right ">
                            <use xlink:href="storage/site/img/svg/sprite.svg#chevron-right"></use>
                        </svg>
                    </div>
                </div>
                <!-- #modal-stories-->
            </div>
        </div>
        <div id="modal-tariffs" style="display: none">
            <div class="modal-win">
                <div class="tariffs collapse-js">
                    <table>
                        <thead>
                        <tr>
                            <th>
                                <div class="tariffs__title">
                                    <div class="h3">Сравнение тарифов</div>
                                    <div class="tariffs__switch form-check form-switch">
                                        <label class="form-check-label"><span>За месяц</span>
                                            <input class="form-check-input flexSwitchCheckDefault" type="checkbox"
                                                   role="switch"/><span>За год</span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="tariffs-item">
                                    <div class="tariffs-item__title">Бесплатный
                                    </div>
                                    <div data-price-month="0 ₽" data-price-year="0 ₽"
                                         class="tariffs-item__price priceTarif">0 ₽
                                    </div>
                                    <p>За одного чат-бота</p>
                                    <a class="tariffs-item__btn btn btn-success" href="/cabinet">Выбрать</a>
                                </div>
                            </th>
                            <th>
                                <div class="tariffs-item">
                                    <div class="tariffs-item__title">Старт
                                    </div>
                                    <div data-price-month="2 670 ₽" data-price-year="32 485 ₽"
                                         class="tariffs-item__price priceTarif">2 670 ₽
                                    </div>
                                    <div class="tariffs-item__price-day">89 ₽ в день
                                    </div>
                                    <p>За одного чат-бота</p>
                                    <a class="tariffs-item__btn btn btn-success" href="/cabinet">Выбрать</a></div>
                            </th>
                            <th>
                                <div class="tariffs-item">
                                    <div class="tariffs-item__title"><img src="storage/site/img/fire.png" alt=""
                                                                          loading="lazy"/>Стандарт
                                    </div>
                                    <div data-price-month="3 570 ₽" data-price-year="43 435 ₽"
                                         class="tariffs-item__price priceTarif">3 570 ₽
                                    </div>
                                    <div class="tariffs-item__price-day">119 ₽ в день
                                    </div>
                                    <p>За одного чат-бота</p>
                                    <a class="tariffs-item__btn btn btn-success" href="/cabinet">Выбрать</a></div>
                            </th>
                            <th>
                                <div class="tariffs-item">
                                    <div class="tariffs-item__title">Профи
                                    </div>
                                    <div data-price-month="4 470 ₽" data-price-year="54 385 ₽"
                                         class="tariffs-item__price priceTarif">4 470 ₽
                                    </div>
                                    <div class="tariffs-item__price-day">149 ₽ в день
                                    </div>
                                    <p>За одного чат-бота</p>
                                    <a class="tariffs-item__btn btn btn-success" href="/cabinet">Выбрать</a></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Количество товаров</td>
                            <td>до&nbsp;15</td>
                            <td>до&nbsp;30</td>
                            <td>до&nbsp;80</td>
                            <td><img src="storage/site/img/svg/infinity.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Количество рассылок в&nbsp;месяц</td>
                            <td>1</td>
                            <td>до&nbsp;5</td>
                            <td>до&nbsp;10</td>
                            <td><img src="storage/site/img/svg/infinity.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Управляющие пользователи</td>
                            <td>1</td>
                            <td>до&nbsp;3</td>
                            <td>до&nbsp;7</td>
                            <td>до&nbsp;10</td>
                        </tr>
                        <tr>
                            <td>Опции товаров</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Расчет стоимости доставки</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Скрытие логотипа</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Вариативные товары</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Онлайн-оплата</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>API</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Премиум поддержка</td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/bad.svg" alt="" loading="lazy"/>
                            </td>
                            <td><img src="storage/site/img/svg/corret.svg" alt="" loading="lazy"/>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4"><span class="hide">Свернуть</span><span class="show">Развернуть</span>
                                <svg class="icon icon-chevron-up ">
                                    <use xlink:href="storage/site/img/svg/sprite.svg#chevron-up"></use>
                                </svg>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- #modal-tariffs-->
        <!-- end modals-->
    </div>
</template>
<script>


export default {
    props: ["active"],
    data() {
        return {
            load: false,
            bot: null,
            company: null
        }
    },
    computed: {},


    mounted() {

    },

    methods: {}
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
</style>
