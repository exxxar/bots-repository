<template>
    <!-- start header-->
    <header class="header" id="header">
        <!-- start top-nav-->
        <div class="top-nav block-with-lazy">
            <div class="container">
                <div class="top-nav__row row">
                    <div class="col col-lg-auto">
                        <a class="top-nav__logo" href="/">
                            <a class="bg-light px-3 text-gradient fs-4 fw-lighter" href="#page-top"> Next IT </a>
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
                                            <!--                                                <li class="menu-item " itemprop="item">
                                                                                                <a href="/tarif" itemprop="url">Тарифы</a></li>

                                                                                            <li class="menu-item " itemprop="item">
                                                                                                <a href="/blog" itemprop="url">Блог</a></li>
                                                                                            <li class="menu-item " itemprop="item">
                                                                                                <a href="/wiki" itemprop="url">База знаний</a></li>

                                                                                            <li class="menu-item " itemprop="item">
                                                                                                <a href="/contact" itemprop="url">Контакты</a></li>

                                                                                            <li class="menu-item menu-item-has-children menu-dropdown&#45;&#45;js">
                                                                                                <div class="dropdownBtn dropdownBtn&#45;&#45;js">Еще</div>
                                                                                                <ul class="sub-menu dropdown-container">
                                                                                                    <li class="menu-item">
                                                                                                        <a class="disabled" href="#" itemprop="url"
                                                                                                           onclick="return false">
                                                                                                            Бот под ключ<span class="badge bg-success">скоро</span>
                                                                                                        </a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </li>-->
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
                           data-bs-toggle="modal"
                           data-bs-target="#feedbackModal"
                           href="#">Оставить заявку</a>
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
</template>
<script>
export default {

}
</script>
