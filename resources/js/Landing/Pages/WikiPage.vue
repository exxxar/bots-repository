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
                            <ol class="breadcrumb swiper-wrapper" itemscope="itemscope" itemtype="http://schema.org/BreadcrumbList" id="swiper-wrapper-f4158bd32485395b" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
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
                                    <a href="/wiki/" itemprop="item">
                                        <span itemprop="name">База знаний</span>
                                        <meta itemprop="position" content="2">
                                    </a>
                                </li>


                            </ol>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></nav>		<h1>База знаний NextIt</h1>
                    </div>
                </div>
                <!-- start sWiki-->
                <div class="sWiki section" id="sWiki">
                    <div class="container">
                        <div class="search-block">
                            <div class="search-block__form-wrap">
                                <form>
                                    <input type="text" name="searchWiki" placeholder="Поиск по ключевым словам">
                                    <button> <span class="focus">/</span><span class="cancel">Esc</span></button>
                                </form>
                            </div>
                            <div class="search-block__wrap">
                                <div class="search-block__result" id="searchResult">
                                </div>
                            </div>
                        </div>
                        <div class="sWiki__row row">
                            <div class="col-lg-9">
                                <div class="sWiki__wrap">
                                    <div class="dd-group dd-group-js hrefs-js">
                                        <div class="dd-group__item active" id="section-1">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Старт								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-1">
                                                <a class="wikiItem" href="/wiki/reg">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Регистрация на сервисе NextIt										</div>
                                                        <p>Первым делом для того, чтобы начать пользоваться сервисом NextIt и подключить телеграм бота, необходимо зарегистрироваться. Для регистрации выполните следующие шаги.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/create-bot">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Создание бота и получение токена в Telegram										</div>
                                                        <p>Для создания бота и получения API токена выполните следующие шаги.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/open-bot">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Интеграция бота в NextIt										</div>
                                                        <p>После авторизации на сервисе NextIt у вас появится возможность создать бота в разделе "Мои чат-боты".</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/cabinet-nextit">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Обзор личного кабинета NextIt										</div>
                                                        <p>После успешной интеграции бота в NextIt, вам будет доступен личный кабинет бота, где вы сможете управлять своим ботом и настройками.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-2">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Каталог								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-2">
                                                <a class="wikiItem" href="/wiki/types-goods">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Товары										</div>
                                                        <p>На странице «Товары» вы увидите список всех доступных товаров в вашем чат-боте, включая уже созданные по умолчанию товары.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/creating-editing-categories">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Категории										</div>
                                                        <p>На странице «Категории» вы увидите список всех доступных категорий товаров в каталоге, включая уже созданные по умолчанию категории.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/tags-product">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Теги										</div>
                                                        <p>На странице «Теги» вы увидите список всех созданных тегов. Чтобы создать новый тег нажмите кнопку “Добавить”.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/additional-options-product">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Опции										</div>
                                                        <p>На странице «Опции» вы увидите список всех созданных опций. Чтобы создать новую опцию нажмите кнопку “Добавить”.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-3">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Заказы								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-3">
                                                <a class="wikiItem" href="/wiki/work-orders">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Управление заказом										</div>
                                                        <p>На странице «Заказы» вы увидите список всех заказов из вашего чат-бота.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-4">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Клиенты								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-4">
                                                <a class="wikiItem" href="/wiki/client-profile">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Управление клиентами										</div>
                                                        <p>На странице «Клиенты» вы увидите список всех клиентов из вашего чат-бота.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-5">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Страницы								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-5">
                                                <a class="wikiItem" href="/wiki/create-pages">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Создание и редактирование страницы										</div>
                                                        <p>Страница – это раздел в чат-боте, который может содержать необходимую статическую информацию.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-6">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Маркетинг								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-6">
                                                <a class="wikiItem" href="/wiki/setting-bonus-system">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Бонусная система										</div>
                                                        <p>Чтобы использовать бонусную систему, перейдите в подраздел «Основное» раздела «Маркетинг» и убедитесь, что бонусная система включена.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/evaluation-order-dishes">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Обратная связь к заказу										</div>
                                                        <p>Чтобы получать обратную связь по заказам, перейдите в подраздел «Основное» раздела «Маркетинг» и убедитесь, что запрос оценки заказа включен.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/promocodes">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Промокоды										</div>
                                                        <p>Промокод дает возможность предоставить клиенту скидку на этапе оформления заказа в чат-боте.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/stocks">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Акции										</div>
                                                        <p>Акции – это баннеры в виде привычных «историй», которые отображаются над каталогом товаров в чат-боте и не имеют ограничения по времени.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/qr-links">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            QR-ссылки										</div>
                                                        <p>QR-ссылки необходимы для отслеживания количества регистраций клиентов в чат-боте, а также их источника.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-7">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Рассылки								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-7">
                                                <a class="wikiItem" href="/wiki/creating-mailing-list">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Создание рассылки										</div>
                                                        <p>Чтобы создать рассылку, перейдите в раздел «Рассылки» и нажмите на кнопку “Добавить”.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-8">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Оформление бота								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-8">
                                                <a class="wikiItem" href="/wiki/visual-design-bot">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Визуальное оформление бота										</div>
                                                        <p>Визуальное оформление бота осуществляется через @botfather в Telegram.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-9">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    WebApp								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-9">
                                                <a class="wikiItem" href="/wiki/settings-webapp">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            WebApp										</div>
                                                        <p>В этом разделе вы можете узнать, как изменить системное текстовое наполнение webApp, а также настроить отображение различных элементов в webApp части бота.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-10">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Шаблоны сообщений								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-10">
                                                <a class="wikiItem" href="/wiki/message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Что такое шаблоны сообщений										</div>
                                                        <p>Шаблоны сообщений представляют собой автоматические уведомления для клиентов и пользователей в Telegram-боте, которые срабатывают при достижении определённых событий.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/events-for-message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            События										</div>
                                                        <p>На данный момент доступны следующие события, на которые вы можете настроить шаблоны сообщений.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/variables-for-message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Переменные										</div>
                                                        <p>Переменные, содержат определенную информацию, например: «стоимость доставки», «номер заказа» или «сумма товаров в корзине», и т. д.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/cycles-for-message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Циклы										</div>
                                                        <p>Для события «Новый заказ» или «NEW_ORDER» также предусмотрена возможность использования цикла, чтобы вывести список заказанных товаров и информацию о каждом из них.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/logical-operators-for-message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Логические операторы										</div>
                                                        <p>Логический оператор представляет собой конкретное условие, при котором можно вывести ту или иную информацию в текстовом виде или с помощью переменной.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/example-message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Примеры шаблонов сообщений										</div>
                                                        <p>Примеры шаблонов сообщений для событий, для ролей: клиент и пользователь.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/create-and-edit-message-templates">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Создать или редактировать шаблон сообщения										</div>
                                                        <p>Чтобы настроить шаблоны сообщений по событиям, перейдите в раздел «Настройки» &gt; «Шаблоны сообщений». Нажмите кнопку «Добавить».</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-11">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Уведомления								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-11">
                                                <a class="wikiItem" href="/wiki/notification-settings">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Подключить уведомления										</div>
                                                        <p>Вы можете получать уведомления о заказах несколькими способами.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-12">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Точки продаж								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-12">
                                                <a class="wikiItem" href="/wiki/point-sale-settings">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Основные настройки точки продаж										</div>
                                                        <p> разделе настроек точки продаж вы можете настроить следующие условия.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/delivery-payment-methods">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Настройки способов получения и оплаты										</div>
                                                        <p>В точке продажи хотя бы один способ получения должен быть активен. Для этого в выбранной точке продаж перейдите во вкладку “Самовывоз” или “Доставка” и укажите способы оплаты.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/additional-fields">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Настройка дополнительных полей										</div>
                                                        <p>Дополнительные поля для заказов можно привязывать к способам оплаты в точках продаж.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/cart-limits">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Лимиты для корзины										</div>
                                                        <p>Лимиты корзины настраиваются для каждой точки продаж. Чтобы задать лимиты, перейдите в нужную точку продаж, откройте вкладку "Самовывоз" или "Доставка".</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/delivery-areas">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Зоны и условия доставки										</div>
                                                        <p>Чтобы способ получения "Доставка" работал, необходимо создать одну или несколько зон доставки.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/work-schedule">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            График работы точки продаж										</div>
                                                        <p>Чтобы настроить режим работы, перейдите в нужную точку продаж и откройте раздел "Режим работы".</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-13">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Пользователи								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-13">
                                                <a class="wikiItem" href="/wiki/user-setup">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Группы пользователей и доступы										</div>
                                                        <p>Для управления доступом к личному кабинету вы можете создавать группы пользователей с различными уровнями доступа.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/user-invite">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Приглашение пользователей										</div>
                                                        <p>Для того чтобы пригласить пользователя к управлению ботом, выполните следующие шаги.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-14">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Дополнительные поля								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-14">
                                                <a class="wikiItem" href="/wiki/create-additional-fields">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Создание дополнительных полей										</div>
                                                        <p>Дополнительные поля позволяют владельцу бота запрашивать необходимую информацию от клиента.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-15">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Статусы заказов								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-15">
                                                <a class="wikiItem" href="/wiki/order-statuses">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Создание и редактирование статусов заказов										</div>
                                                        <p>Чтобы управлять статусами заказов, перейдите в раздел Настройки &gt; Статусы заказов.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-16">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Способы оплаты								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-16">
                                                <a class="wikiItem" href="/wiki/add-payment-method">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Добавление способа оплаты										</div>
                                                        <p>Для добавления и редактирования способов оплаты перейдите в раздел Настройки &gt; Способы оплаты.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/connect-online-payment">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Подключение онлайн-оплаты										</div>
                                                        <p>Чтобы принимать онлайн-платежи за заказы в вашем боте, необходимо выбрать и подключить онлайн-кассу.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-17">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Служебные настройки								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-17">
                                                <a class="wikiItem" href="/wiki/api-key-bot">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            API ключ бота										</div>
                                                        <p>API-ключ, который связывает бота и NextIt, находится в разделе Настройки &gt; Служебные.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/work-mode">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Режим работы: онлайн-магазин или витрина										</div>
                                                        <p>По умолчанию в настройках установлен режим "Онлайн-магазин", который позволяет клиентам добавлять товары в корзину и оформлять заказы.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/setting-currency">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Валюта										</div>
                                                        <p>Для смены валюты бота перейдите в раздел "Настройки" &gt; "Служебные" и выберите необходимую валюту.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/service-mode">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Режим обслуживания										</div>
                                                        <p>Чтобы включить режим обслуживания, перейдите в раздел "Настройки" &gt; "Служебные" и рядом с опцией "Режим обслуживания" снимите галочку.</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/delete-bot">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Удаление бота навсегда										</div>
                                                        <p>Для того чтобы удалить чат-бот из NextIt, перейдите в раздел "Настройки" &gt; "Служебные" и нажмите кнопку Удалить чат-бота навсегда".</p>
                                                    </div>
                                                </a>
                                                <a class="wikiItem" href="/wiki/nextit-api">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            NextIt API										</div>
                                                        <p>Для того чтобы получить API токен NextIt, перейдите в раздел Настройки" &gt; "API" и создайте новый токен, нажав кнопку "Добавить".</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-18">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Тарифы								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-18">
                                                <a class="wikiItem" href="/wiki/how-tariffs-work">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Как работают тарифы										</div>
                                                        <p>Тарифные планы на сервисе NextIt работают с ежедневным списанием согласно выбранному тарифу — оплата списывается раз в сутки.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="dd-group__item active" id="section-19">
                                            <div class="dd-group__head dd-head-js">
                                                <h5>
                                                    Продвижение бота								</h5>
                                            </div>
                                            <div class="dd-group__content dd-content-js active" id="section-19">
                                                <a class="wikiItem" href="/wiki/qr-code-bot">
                                                    <div class="wikiItem__content">
                                                        <!-- <div class="wikiItem__head">
                                                            <div class="wikiItem__step">
                                                                 шаг
                                                            </div>
                                                        </div> -->
                                                        <div class="wikiItem__title">
                                                            Как продвигать бота										</div>
                                                        <p>Чтобы пригласить клиентов в ваш Telegram-бот, воспользуйтесь QR-ссылкой. Для этого перейдите в раздел "Маркетинг" &gt; "QR-ссылки".</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 d-none d-lg-block sticky-content-js">
                                <div class="nav-block" style="position: relative;">
                                    <div class="nav-block__content sticky-js" style="">
                                        <a class="nav-block__item" href="#section-1">
                                            Старт						</a>
                                        <a class="nav-block__item" href="#section-2">
                                            Каталог						</a>
                                        <a class="nav-block__item" href="#section-3">
                                            Заказы						</a>
                                        <a class="nav-block__item" href="#section-4">
                                            Клиенты						</a>
                                        <a class="nav-block__item" href="#section-5">
                                            Страницы						</a>
                                        <a class="nav-block__item" href="#section-6">
                                            Маркетинг						</a>
                                        <a class="nav-block__item" href="#section-7">
                                            Рассылки						</a>
                                        <a class="nav-block__item" href="#section-8">
                                            Оформление бота						</a>
                                        <a class="nav-block__item" href="#section-9">
                                            WebApp						</a>
                                        <a class="nav-block__item" href="#section-10">
                                            Шаблоны сообщений						</a>
                                        <a class="nav-block__item" href="#section-11">
                                            Уведомления						</a>
                                        <a class="nav-block__item" href="#section-12">
                                            Точки продаж						</a>
                                        <a class="nav-block__item" href="#section-13">
                                            Пользователи						</a>
                                        <a class="nav-block__item" href="#section-14">
                                            Дополнительные поля						</a>
                                        <a class="nav-block__item" href="#section-15">
                                            Статусы заказов						</a>
                                        <a class="nav-block__item" href="#section-16">
                                            Способы оплаты						</a>
                                        <a class="nav-block__item" href="#section-17">
                                            Служебные настройки						</a>
                                        <a class="nav-block__item" href="#section-18">
                                            Тарифы						</a>
                                        <a class="nav-block__item" href="#section-19">
                                            Продвижение бота						</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end sWiki-->

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
