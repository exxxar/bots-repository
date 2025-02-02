<script setup>
import Layout from "../Layouts/ClientNewLayout.vue";
import Header from "@/Landing/Components/Header.vue";
import AudioPlayer from 'vue3-audio-player'
import 'vue3-audio-player/dist/style.css'
</script>

<template>
    <Layout>
        <template #default>


            <!-- start headerBlock-->
            <div class="headerBlock section" id="headerBlock">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 order-1 order-md-0">
                            <div class="headerBlock__img-wrap skeleton">
                                <div class="img-wrap-center">
                                    <video autoplay="autoplay" loop="loop" playsinline="playsinline" muted="muted">
                                        <!-- <source src="/landingNew/storage/site/video/headerBlock-bg-video.webm" type="video/webm"/> -->
                                        <source src="/landingNew/storage/site/video/cashman.mp4" type="video/mp4"/>
                                    </video>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 align-self-center">
                            <h1>Чат-бот в&nbsp;Telegram с&nbsp;приемом платежей для ресторанов</h1>
                            <p>Создайте WebApp чат-бота за&nbsp;5&nbsp;минут и&nbsp;получайте заказы на&nbsp;доставку и&nbsp;самовывоз
                                через популярный мессенджер Telegram</p>
                            <div class="headerBlock__btn-wrap">

                                <a class="headerBlock__btn btn btn-primary" href="/dev">Начать
                                    использовать</a>

                                <a class="headerBlock__btn btn btn-outline-secondary" target="_blank"
                                   href="https://t.me/GastroPab_Ezh_bot">
                                    Открыть демо-бота
                                </a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="headerBlock__stories-wrap">
                                <div class="headerBlock__inner-row row">
                                    <div class="col-auto">
                                        <a class="headerBlock__stories-item headerBlock__stories-item--js active"
                                           href="#" data-src="modal-stories" data-fancybox="modal"
                                           style="--iconBgColor: linear-gradient(0deg, rgba(245, 176, 77, 0.12), rgba(245, 176, 77, 0.12))">
                                            <div class="headerBlock__icon-wrap"><img
                                                src="/landingNew/storage/site/img/stories-icon-1.png" alt="" loading="lazy"/>
                                            </div>
                                            <p>1000 <br class=mobile-br> бонусных ₽<br> за регистрацию</p>
                                        </a>
                                    </div>
                                    <!--<div class="col-auto">-->
                                    <!--	<a class="headerBlock__stories-item headerBlock__stories-item--js active" href="#" data-src="modal-stories" data-fancybox="modal" style="--iconBgColor: linear-gradient(0deg, rgba(111, 100, 236, 0.12), rgba(111, 100, 236, 0.12))">-->
                                    <!--		<div class="headerBlock__icon-wrap"><img src="/landingNew/storage/site/img/stories-icon-2.png" alt="" loading="lazy"/>-->
                                    <!--		</div>-->
                                    <!--		<p>Начислим 50% сверху, <br class=mobile-br> при пополнении баланса от 2000 ₽</p>-->
                                    <!--	</a>-->
                                    <!--</div>-->
                                    <div class="col-auto">
                                        <a class="headerBlock__stories-item headerBlock__stories-item--js active"
                                           href="#" data-src="modal-stories" data-fancybox="modal"
                                           style="--iconBgColor: linear-gradient(0deg, rgba(75, 177, 75, 0.12), rgba(75, 177, 75, 0.12))">
                                            <div class="headerBlock__icon-wrap"><img
                                                src="/landingNew/storage/site/img/stories-icon-3.png" alt="" loading="lazy"/>
                                            </div>
                                            <p>Зарабатывайте <br> с нами</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end headerBlock-->
            <!-- start sReasons-->
            <section class="sReasons section" id="sReasons">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>5 причин начать пользоваться сервисом</h2>
                    </div>
                    <div class="sReasons__row row">
                        <div class="col-6 col-lg-4">
                            <div class="sReasons__item">
                                <div class="sReasons__img-wrap">
                                    <div class="img-wrap-center">
                                        <img src="/landingNew/storage/site/img/1.gif" alt="">
                                    </div>
                                </div>
                                <h6>Меню заведения</h6>
                                <p>Ваш клиент может ознакомиться с&nbsp;меню вашего ресторана в&nbsp;чат-боте</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="sReasons__item">
                                <div class="sReasons__img-wrap">
                                    <div class="img-wrap-center">
                                        <img src="/landingNew/storage/site/img/2.gif" alt="">
                                    </div>
                                </div>
                                <h6>Заказы</h6>
                                <p>Получайте заказы на&nbsp;доставку и&nbsp;самовывоз с&nbsp;онлайн-оплатой или оплатой
                                    наличными, с&nbsp;расчетом стоимости доставки</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="sReasons__item">
                                <div class="sReasons__img-wrap">
                                    <div class="img-wrap-center">
                                        <img src="/landingNew/storage/site/img/4.gif" alt="">
                                    </div>
                                </div>
                                <h6>Клиентская база</h6>
                                <p>Каждый клиент вступивший в&nbsp;чат-бот остается в&nbsp;системе. Вы&nbsp;видите
                                    информацию о&nbsp;каждом клиенте и&nbsp;их&nbsp;заказах</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="sReasons__item">
                                <div class="sReasons__img-wrap">
                                    <div class="img-wrap-center">
                                        <img src="/landingNew/storage/site/img/5.gif" alt="">
                                    </div>
                                </div>
                                <h6>Рассылки</h6>
                                <p>Стимулируйте клиентов к&nbsp;заказам или&nbsp;приглашайте в&nbsp;ресторан с&nbsp;помощью
                                    автоматизированных или ручных рассылок</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="sReasons__item">
                                <div class="sReasons__img-wrap">
                                    <div class="img-wrap-center">
                                        <img src="/landingNew/storage/site/img/3.gif" alt="">
                                    </div>
                                </div>
                                <h6>Бонусная система</h6>
                                <p>Начисляйте бонусные рубли клиентам при авторизации в&nbsp;чат-боте, а&nbsp;также
                                    кэшбэк с&nbsp;заказов</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end sReasons-->
            <!-- start sWhyTelegramBot-->
            <section class="sWhyTelegramBot sWhyTelegramBot--2 section" id="sWhyTelegramBot">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Для кого подходит наш сервис</h2>
                    </div>
                    <div class="sWhyTelegramBot__row row">
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sControl-icon-2.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Кафе, рестораны, столовые</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-13.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Кофейни</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-14.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Бары</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-15.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Отели, гостиницы с&nbsp;доставкой еды, турбазы и&nbsp;зоны отдыха</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-16.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Кальянные с&nbsp;доставкой еды</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-17.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Доставка готовой еды и&nbsp;продуктов здорового питания</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-18.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Пекарни и&nbsp;кондитерские</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="sWhyTelegramBot__item">
                                <div class="sWhyTelegramBot__icon-wrap"><img
                                    src="/landingNew/storage/site/img/svg/sCapabilities-icon-19.svg" alt="" loading="lazy"/>
                                </div>
                                <p>Небольшие супермаркеты и&nbsp;магазины</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- start sReasons-->
            <!-- start sCapabilities-->
            <section class="sCapabilities section" id="sCapabilities">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Что умеет чат-бот?</h2>
                        <p>Возможности WebApp чат-бота от&nbsp;NextIT для ваших клиентов</p>
                    </div>
                    <div class="dd-group dd-group-js">
                        <div class="dd-group__row row">
                            <div class="col-lg-6">
                                <div class="dd-group__item">
                                    <div class="dd-group__head dd-head-js">
                                        <div class="dd-group__icon-wrap"><img
                                            src="/landingNew/storage/site/img/svg/sCapabilities-icon-1.svg" alt="" loading="lazy"/>
                                        </div>
                                        <h6>Оформлять заказы</h6>
                                    </div>
                                    <div class="dd-group__content dd-content-js">Ваш клиент видит полный ассортимент
                                        меню, может добавлять блюда в&nbsp;корзину, выбирать способ оплаты и&nbsp;доставки,
                                        а&nbsp;также оплачивать часть покупки бонусными рублями
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dd-group__item">
                                    <div class="dd-group__head dd-head-js">
                                        <div class="dd-group__icon-wrap"><img
                                            src="/landingNew/storage/site/img/svg/sCapabilities-icon-2.svg" alt="" loading="lazy"/>
                                        </div>
                                        <h6>Рассчитывать стоимость доставки</h6>
                                    </div>
                                    <div class="dd-group__content dd-content-js">Чат-бот считает километраж от&nbsp;заведения
                                        до&nbsp;места выдачи заказа и&nbsp;считает стоимость доставки, а&nbsp;также вы&nbsp;можете
                                        сделать стоимость доставки по&nbsp;вашим условиям: бесплатной или&nbsp;фиксированной
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dd-group__item">
                                    <div class="dd-group__head dd-head-js">
                                        <div class="dd-group__icon-wrap"><img
                                            src="/landingNew/storage/site/img/svg/sCapabilities-icon-5.svg" alt="" loading="lazy"/>
                                        </div>
                                        <h6>Отправлять рассылки</h6>
                                    </div>
                                    <div class="dd-group__content dd-content-js">Чат-бот будет отправлять автоматические
                                        рассылки которые вы&nbsp;настроите и&nbsp;напоминать клиенту совершить заказ,
                                        забронировать стол, а&nbsp;также вы&nbsp;можете отправлять рассылки вручную всем
                                        или отдельно выбранным
                                        пользователям
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dd-group__item">
                                    <div class="dd-group__head dd-head-js">
                                        <div class="dd-group__icon-wrap"><img
                                            src="/landingNew/storage/site/img/svg/sCapabilities-icon-6.svg" alt="" loading="lazy"/>
                                        </div>
                                        <h6>Уведомлять сотрудников и клиентов</h6>
                                    </div>
                                    <div class="dd-group__content dd-content-js">Вы&nbsp;будете получать уведомления в&nbsp;Telegram,
                                        по&nbsp;SMS или звонком после каждого совершенного заказа или бронирования
                                        стола, а&nbsp;ваши клиенты будут получать автоматические текстовые уведомления о&nbsp;смене
                                        статуса заказа
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sCapabilities__qr-wrap">
                        <div class="sCapabilities__qr-img"><img src="http://qrcoder.ru/code/?https%3A%2F%2Ft.me%2FGastroPab_Ezh_bot&10&0" alt=""
                                                                loading="lazy"/>
                        </div>
                        <div class="sCapabilities__qr-content">
                            <h6>Откройте и посмотрите <img src="/landingNew/storage/site/img/eyes.png" alt="" loading="lazy"/> <a
                                target="_blank" href="https://t.me/GastroPab_Ezh_bot"><span>пример</span></a> чат-бота
                            </h6>
                            <p>Наведите камеру на QR-код</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end sCapabilities-->
            <!-- start sControl-->
            <section class="sControl section" id="sControl">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Как управлять чат-ботом?</h2>
                        <p>Возможности системы управления для ресторана и сотрудников с любого устройства</p>
                    </div>
                    <div class="dd-group dd-group-js">
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-1.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Создание чат-бота</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">Создание чат-бота не&nbsp;займет у&nbsp;вас
                                более 5&nbsp;минут. По пути создания вас проведет опытный менеджер, который вместе с вами создаст подходящее для вас решение.
                            </div>
                        </div>
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-2.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Заполнение и редактирование меню</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">
                                <ul>
                                    <li>Заполнение и&nbsp;редактирование меню ресторана происходит в&nbsp;несколько
                                        кликов
                                    </li>
                                    <li>Если закончились продукты для каких-либо блюд, вы&nbsp;можете поставить блюдо в&nbsp;стоп-лист
                                        и&nbsp;оно будет недоступно для заказа пользователям чат-бота
                                    </li>
                                    <li>В&nbsp;новой версии появилась возможность создания вариативных товаров и&nbsp;дополнительных
                                        опций к&nbsp;товарам.
                                    </li>
                                    <li>Рейтинг блюд от&nbsp;ваших клиентов, которые оставляют оценку каждому блюду
                                        после совершения заказа, а&nbsp;за&nbsp;это получают бонусные рубли (если вы&nbsp;включите
                                        эту функцию)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-3.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Управление заказами</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">
                                <ul>
                                    <li>Вы&nbsp;получаете уведомления о&nbsp;новых заказах в&nbsp;Telegram, из&nbsp;уведомления
                                        вы&nbsp;переходите в&nbsp;системе управления и&nbsp;просматриваете полную
                                        информацию о&nbsp;заказе, клиенте, платеже и&nbsp;меняете
                                        статус заказа
                                    </li>
                                    <li>Вы&nbsp;получаете автоматический звонок-уведомление о&nbsp;новом заказе, чтобы
                                        не&nbsp;пропустить ни&nbsp;один заказ (если вы&nbsp;включите эту функцию)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-5.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Клиентская база</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">
                                <ul>
                                    <li>Каждый пользователь вступивший в&nbsp;чат-бот остается в&nbsp;системе
                                        управления
                                    </li>
                                    <li>Вы&nbsp;видите информацию о&nbsp;каждом клиенте и&nbsp;его заказы, также можете
                                        заблокировать пользователя при необходимости
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-6.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Рассылки</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">Отправляйте сообщения пользователям своего
                                чат-бота по&nbsp;отдельности или сразу всем, стимулируйте пользователей к&nbsp;заказам и&nbsp;бронированию
                                столов
                            </div>
                        </div>
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap">
                                    <img src="/landingNew/storage/site/img/svg/sControl-icon-7.svg"
                                         alt="" loading="lazy"/>
                                </div>
                                <h6>Аналитика</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">Вы&nbsp;видите аналитику по&nbsp;заказам,
                                пользователям, а&nbsp;также оценки за&nbsp;блюда, количество заказов на&nbsp;конкретные
                                блюда и&nbsp;их&nbsp;популярность
                            </div>
                        </div>
                        <div class="dd-group__item">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-8.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Бонусная и реферальная система</h6>
                            </div>
                            <div class="dd-group__content dd-content-js">Тонкие настройки бонусной и&nbsp;реферальной
                                систем под ваше заведение. Стимулируйте клиентов к&nbsp;заказам бонусами за&nbsp;регистрацию
                                и&nbsp;кешбэком с&nbsp;заказов, а&nbsp;также начисляйте бонусы за&nbsp;приглашения
                                друзей
                            </div>
                        </div>
                        <div class="dd-group__item dd-group__item--settings">
                            <div class="dd-group__head dd-head-js">
                                <div class="dd-group__icon-wrap"><img src="/landingNew/storage/site/img/svg/sControl-icon-9.svg"
                                                                      alt="" loading="lazy"/>
                                </div>
                                <h6>Настройки </h6>
                            </div>
                            <div class="dd-group__content dd-content-js">
                                <ul>
                                    <li>Минимальная сумма для бесплатной доставки</li>
                                    <li>Минимальная сумма заказа</li>
                                    <li>Адрес заведения, от&nbsp;которого будет считаться стоимость доставки</li>
                                    <li>Настройка формулы расчета стоимости доставки</li>
                                    <li>Время приема заказов</li>
                                    <li>Текстовое заполнение и&nbsp;визуальное оформление чат-бота</li>
                                    <li>Способы оплаты-доставки</li>
                                    <li>Способы оплаты для самовывоза</li>
                                    <li>Валюта чат-бота</li>
                                    <li>Добавление и&nbsp;редактирование администраторов чат-бота</li>
                                    <li>Получение персонального API ключа</li>
                                    <li>Получение QR&nbsp;кода для продвижения чат-бота</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end sControl-->
            <!-- start sCompare-->
            <section class="sCompare section" id="sCompare">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Сравните чат-бот<br>с&nbsp;конкурентными решениями</h2>
                        <p>Мы&nbsp;учли все детали в&nbsp;сервисе, чтобы владельцы ресторанного бизнеса получали
                            максимум пользы и&nbsp;поддержки</p>
                    </div>
                    <div class="sCompare__table-wrap collapse-js">
                        <table>
                            <thead>
                            <tr>
                                <th></th>
                                <th>   <a class="bg-light px-3 text-gradient fs-6 fw-lighter" href="#page-top"> Next IT </a>
                                </th>
                                <th>Агрегатор</th>
                                <th>Свой сайт или&nbsp;мобильное приложение</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Комиссия с заказов</td>
                                <td>0%</td>
                                <td>30-40%</td>
                                <td>0%</td>
                            </tr>
                            <tr>
                                <td>Срок запуска</td>
                                <td>1 день</td>
                                <td>7-14 дней</td>
                                <td>30+ дней</td>
                            </tr>
                            <tr>
                                <td>Потребность в программисте</td>
                                <td>Нет</td>
                                <td>Нет</td>
                                <td>Да</td>
                            </tr>
                            <tr>
                                <td>Стоимость разработки</td>
                                <td>от 0 ₽ в месяц</td>
                                <td>-</td>
                                <td>от 60 000 ₽</td>
                            </tr>
                            <tr>
                                <td>Обновления и улучшения</td>
                                <td>Ежемесячно автоматически</td>
                                <td>Зависят от агрегатора</td>
                                <td>от 30 000 ₽ /год</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4"><span class="hide">Свернуть</span><span class="show">Развернуть</span>
                                    <svg class="icon icon-chevron-up ">
                                        <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#chevron-up"></use>
                                    </svg>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="sCompare__text">Для максимального эффекта вы&nbsp;можете использовать чат-бота для
                        получения заказов параллельно с&nbsp;агрегаторами
                    </div>

                    <a class="sCompare__btn btn btn-primary"
                       data-bs-toggle="modal"
                       data-bs-target="#feedbackModal"
                       href="#">Попробовать бесплатно</a>

                </div>
            </section>
            <!-- end sCompare-->
            <!-- start sWhyTelegramBot-->
            <section class="sWhyTelegramBot section" id="sWhyTelegramBot">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Почему вам выгодно использовать чат-бота в&nbsp;Telegram</h2>
                        <p>Заказ еды через мессенджер — будущее в&nbsp;сфере общепита</p>
                    </div>
                    <div class="sWhyTelegramBot__slider swiper defaultSwiper defaultSwiper--js">
                        <div class="swiper-wrapper">
                            <div class="sWhyTelegramBot__slide swiper-slide">
                                <div class="sWhyTelegramBot__item">
                                    <div class="sWhyTelegramBot__icon-wrap"><img
                                        src="/landingNew/storage/site/img/svg/sWhyTelegramBot-icon-1.svg" alt="" loading="lazy"/>
                                    </div>
                                    <div class="sWhyTelegramBot__title">Не платите комиссию сервису с каждого заказа
                                    </div>
                                    <p>Вы&nbsp;оплачиваете сервис за&nbsp;месяц, как абонентскую плату за&nbsp;интернет.
                                        Мы&nbsp;не&nbsp;берем комиссию с&nbsp;заказов или обращений, вся прибыль
                                        остается у&nbsp;вас</p>
                                </div>
                            </div>
                            <div class="sWhyTelegramBot__slide swiper-slide">
                                <div class="sWhyTelegramBot__item">
                                    <div class="sWhyTelegramBot__icon-wrap"><img
                                        src="/landingNew/storage/site/img/svg/sWhyTelegramBot-icon-2.svg" alt="" loading="lazy"/>
                                    </div>
                                    <div class="sWhyTelegramBot__title">Экономите на&nbsp;разработке сайта и&nbsp;приложения
                                    </div>
                                    <p>Если у&nbsp;вас нет собственного сайта и&nbsp;приложения, то&nbsp;вы&nbsp;экономите
                                        на&nbsp;их&nbsp;разработке и&nbsp;поддержке&nbsp;&mdash; получаете решение
                                        ценностью не&nbsp;менее 60000₽, от&nbsp;0₽&nbsp;в&nbsp;месяц</p>
                                </div>
                            </div>
                            <div class="sWhyTelegramBot__slide swiper-slide">
                                <div class="sWhyTelegramBot__item">
                                    <div class="sWhyTelegramBot__icon-wrap"><img
                                        src="/landingNew/storage/site/img/svg/sWhyTelegramBot-icon-3.svg" alt="" loading="lazy"/>
                                    </div>
                                    <div class="sWhyTelegramBot__title">Сервис в&nbsp;популярном мессенджере
                                    </div>
                                    <p>Количество пользователей мессенджера Telegram растет с&nbsp;каждым днем, а&nbsp;вы&nbsp;получаете
                                        конкурентное преимущество, заказы и&nbsp;лояльность клиентов</p>
                                </div>
                            </div>
                            <div class="sWhyTelegramBot__slide swiper-slide">
                                <div class="sWhyTelegramBot__item">
                                    <div class="sWhyTelegramBot__icon-wrap"><img
                                        src="/landingNew/storage/site/img/svg/sWhyTelegramBot-icon-4.svg" alt="" loading="lazy"/>
                                    </div>
                                    <div class="sWhyTelegramBot__title">Вашим клиентам не&nbsp;нужно скачивать
                                        приложение
                                    </div>
                                    <p>Ваши клиенты один раз сканируют QR-код, который перенаправит их&nbsp;на&nbsp;чат-бота,
                                        авторизовавшись в&nbsp;котором один раз, они останутся с&nbsp;вами для будущих
                                        заказов</p>
                                </div>
                            </div>
                        </div>
<!--                        <div class="sWhyTelegramBot__arrow-wrap">
                            <div class="swiper-button-hand swiper-button-hand-prev swiper-button-prev">
                                <i class="fa-solid fa-arrow-left"></i>
                            </div>
                            <div class="swiper-button-hand swiper-button-hand-next swiper-button-next">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>-->
                    </div>
                </div>
            </section>
            <!-- end sWhyTelegramBot-->
            <!-- start sChooseTariff-->
<!--            <section class="sChooseTariff section" id="sChooseTariff">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Выберите свой тариф</h2>
                    </div>
                    <div class="sChooseTariff__slider swiper defaultSwiper defaultSwiper&#45;&#45;js">
                        <div class="swiper-wrapper">
                            <div class="sChooseTariff__slide swiper-slide">
                                <div class="sChooseTariff__item">
                                    <div class="sChooseTariff__wrap">
                                        <div class="sChooseTariff__type">
                                            Free
                                        </div>
                                        <div data-price-month="0 ₽" data-price-year="0 ₽" class="h4 priceTarif">
                                            0 ₽ /день
                                        </div>
                                        <p>Цена за одного бота</p>
                                    </div>
                                    <ul>
                                        <li>До 100 клиентов</li>
                                        <li>До 20 товаров</li>
                                        <li>До 1 управляющих</li>
                                        <li>До 3 рассылок</li>
                                        <li>До 1 зон доставки</li>

                                        <li style="&#45;&#45;mark: url('../img/svg/bad.svg')">Онлайн-оплата</li>

                                        <li style="&#45;&#45;mark: url('../img/svg/bad.svg')">API</li>

                                        <li style="&#45;&#45;mark: url('../img/svg/bad.svg')">Скрытие логотипа</li>
                                    </ul>
                                    <a class="sChooseTariff__inner-btn btn btn-success" href="/cabinet">Выбрать
                                        тариф</a></div>
                            </div>
                            <div class="sChooseTariff__slide swiper-slide">
                                <div class="sChooseTariff__item">
                                    <div class="sChooseTariff__wrap">
                                        <div class="sChooseTariff__type">
                                            Start
                                        </div>
                                        <div data-price-month="129 ₽" data-price-year="129 ₽" class="h4 priceTarif">
                                            129 ₽ /день
                                        </div>
                                        <p>Цена за одного бота и одну точку продаж</p>
                                    </div>
                                    <ul>
                                        <li>До 1000 клиентов</li>
                                        <li>До 100 товаров</li>
                                        <li>До 3 управляющих</li>
                                        <li>До 5 рассылок</li>
                                        <li>До 15 зон доставки</li>

                                        <li>Онлайн оплата</li>

                                        <li style="&#45;&#45;mark: url('../img/svg/bad.svg')">API</li>

                                        <li>Скрытие логотипа</li>
                                    </ul>
                                    <a class="sChooseTariff__inner-btn btn btn-success" href="/cabinet">Выбрать
                                        тариф</a></div>
                            </div>
                            <div class="sChooseTariff__slide swiper-slide">
                                <div class="sChooseTariff__item">
                                    <div class="sChooseTariff__wrap">
                                        <div class="sChooseTariff__type">
                                            Standard
                                        </div>
                                        <div data-price-month="169 ₽" data-price-year="169 ₽" class="h4 priceTarif">
                                            169 ₽ /день
                                        </div>
                                        <p>Цена за одного бота и одну точку продаж</p>
                                    </div>
                                    <ul>
                                        <li>До 5000 клиентов</li>
                                        <li>До 250 товаров</li>
                                        <li>До 5 управляющих</li>
                                        <li>До 15 рассылок</li>
                                        <li>До 30 зон доставки</li>

                                        <li>Онлайн оплата</li>

                                        <li>API</li>

                                        <li>Скрытие логотипа</li>
                                    </ul>
                                    <a class="sChooseTariff__inner-btn btn btn-success" href="/cabinet">Выбрать
                                        тариф</a></div>
                            </div>
                            <div class="sChooseTariff__slide swiper-slide">
                                <div class="sChooseTariff__item">
                                    <div class="sChooseTariff__wrap">
                                        <div class="sChooseTariff__type">
                                            Pro
                                        </div>
                                        <div data-price-month="219 ₽" data-price-year="219 ₽" class="h4 priceTarif">
                                            219 ₽ /день
                                        </div>
                                        <p>Цена за одного бота и одну точку продаж</p>
                                    </div>
                                    <ul>
                                        <li>До 10000 клиентов</li>
                                        <li>До 1000 товаров</li>
                                        <li>До 10 управляющих</li>
                                        <li>До 30 рассылок</li>
                                        <li>До 100 зон доставки</li>

                                        <li>Онлайн оплата</li>

                                        <li>API</li>

                                        <li>Скрытие логотипа </li>
                                    </ul>
                                    <a class="sChooseTariff__inner-btn btn btn-success" href="/cabinet">Выбрать
                                        тариф</a></div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>


                    <div class="sChooseTariff__text">
                        Оплата по&nbsp;тарифу списывается ежедневно с&nbsp;баланса личного кабинета. Минимальная сумма
                        пополнения&nbsp;&mdash; 1000&nbsp;₽
                    </div>
                    &lt;!&ndash; <a class="sChooseTariff__btn btn btn-outline-primary" href="#" data-src="modal-tariffs" data-fancybox="modal">
            Все тарифы списком
        </a> &ndash;&gt;
                </div>
            </section>-->
            <!-- end sChooseTariff-->
            <!-- start sFAQBlock-->
            <section class="sFAQBlock section" id="sFAQBlock">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Ответы на популярные вопросы</h2>
                    </div>
                    <div class="sFAQBlock__wrap">
                        <h4>Как запустить чат-бота в&nbsp;Telegram?</h4>
                        <ol>
                            <li>
                                <div class="sFAQBlock__icon-wrap"><img src="/landingNew/storage/site/img/svg/sFAQBlock-icon-1.svg"
                                                                       alt="" loading="lazy"/>
                                </div>
                                <p>Регистрируетесь на&nbsp;платформе</p>
                            </li>
                            <li>
                                <div class="sFAQBlock__icon-wrap"><img src="/landingNew/storage/site/img/svg/sFAQBlock-icon-2.svg"
                                                                       alt="" loading="lazy"/>
                                </div>
                                <p>Создаете чат-бота, настраиваете его и&nbsp;заполняете меню</p>
                            </li>
                            <li>
                                <div class="sFAQBlock__icon-wrap"><img src="/landingNew/storage/site/img/svg/sFAQBlock-icon-3.svg"
                                                                       alt="" loading="lazy"/>
                                </div>
                                <p>Распечатываете рекламные материалы с&nbsp;QR-кодом и&nbsp;размещаете у&nbsp;себя в&nbsp;ресторане</p>
                            </li>
                            <li>
                                <div class="sFAQBlock__icon-wrap"><img src="/landingNew/storage/site/img/svg/sFAQBlock-icon-4.svg"
                                                                       alt="" loading="lazy"/>
                                </div>
                                <p>Ваши клиенты начинают авторизовываться в&nbsp;чат-боте и&nbsp;совершать заказы</p>
                            </li>
                            <li>
                                <div class="sFAQBlock__icon-wrap"><img src="/landingNew/storage/site/img/svg/sFAQBlock-icon-5.svg"
                                                                       alt="" loading="lazy"/>
                                </div>
                                <p>С&nbsp;каждым днем растет клиентская база и&nbsp;количество заказов</p>
                            </li>
                        </ol>
                    </div>

                    <div class="accordion accordion-flush my-5" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    <h6>Сколько это стоит?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Стоимость стартового тарифа при оплате за&nbsp;месяц начинается от&nbsp;89&nbsp;рублей
                                        в&nbsp;день. Наша матрица тарифов устроена так, чтобы каждый ресторан выбрал
                                        наиболее подходящий для себя тариф и&nbsp;при этом не&nbsp;платил
                                        за&nbsp;ненужные возможности.</p>
                                    <p>Чем дороже тариф, тем больше его ценность и&nbsp;возможности. Помимо платных тарифов
                                        у&nbsp;нас предусмотрен бесплатный тариф Free с&nbsp;ограниченным функционалом.</p>
                                    <p>Также мы&nbsp;предоставляем 14&nbsp;дней бесплатного пользования на&nbsp;тарифах
                                        Старт и&nbsp;Стандарт.</p>
                                    <a class="dd-group__btn btn btn-outline-primary" href="/tarif">Перейти в тарифы</a>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    <h6>Как мы будем узнавать о новых заказах?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>После того, как вы&nbsp;настроите чат-бота и&nbsp;разместите QR-код в&nbsp;ресторане
                                        вы&nbsp;начнете получать заказы. Уведомления о&nbsp;новых заказах вы&nbsp;увидите в&nbsp;личном
                                        кабинете, а&nbsp;также они будут приходить
                                        в&nbsp;специальный чат-бот, ссылку на&nbsp;которого вы&nbsp;найдете в&nbsp;личном
                                        кабинете своего чат-бота.</p>
                                    <p>Дополнительно вы&nbsp;можете подключить звонок на&nbsp;указанный номер телефона с&nbsp;уведомлением
                                        о&nbsp;новом заказе, это бесплатно.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                   <h6>Как изменить меню, цены, данные о ресторане после
                                       запуска чат-бота?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse " data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Управление меню и&nbsp;ценами, информацией о&nbsp;ресторане будет доступно вам в&nbsp;личном
                                        кабинете в&nbsp;любое время 24&nbsp;часа в&nbsp;сутки.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <h6>Что делать, если закончились продукты для каких-либо
                                        блюд?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse " data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Вы&nbsp;можете одним касанием поставить блюдо в&nbsp;стоп-лист в&nbsp;личном кабинете
                                        вашего чат-бота.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <h6>Как мне продвигать чат-бота?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse " data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Разместите рекламную продукцию с&nbsp;QR-кодом чат-бота в&nbsp;своем заведении:
                                        тейбл-тент, наклейки на&nbsp;стол, наклейка на&nbsp;дверь. Кладите листовки с&nbsp;QR-кодом
                                        чат-бота в&nbsp;заказы на&nbsp;доставку. Мы&nbsp;рекомендуем
                                        использовать бонусные баллы за&nbsp;авторизацию в&nbsp;чат-боте, например 300
                                        бонусных баллов, чтобы простимулировать клиентов в&nbsp;чат-боте.</p>

                                </div>
                            </div>
                        </div>


                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <h6>Сколько чат-ботов можно создать на одном аккаунте?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseSix" class="accordion-collapse collapse " data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Сколько угодно. Для каждого чат-бота необходимо выбрать собственный тариф.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <h6>Я хочу стать вашим партнером, что делать?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseSeven" class="accordion-collapse collapse " data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Ознакомиться с условиями партнерской программой можно в разделе <a href="/tarif">Партнерка</a>,
                                        а также на странице <a href="/partner-terms">Условия партнерской программы</a>.
                                        Чтобы стать партнером зарегистрируйтесь и перейдите
                                        в раздел "партнерка" в личном кабинете.</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header ">
                                <button class="accordion-button bg-transparent collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <h6>Передаете ли вы информацию третьим лицам?</h6>
                                </button>
                            </h2>
                            <div id="flush-collapseEight" class="accordion-collapse collapse " data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <p>Нет, мы&nbsp;не&nbsp;передаем информацию о&nbsp;пользователях третьим лицам. Более
                                        того, не&nbsp;все сотрудники имеют доступ к&nbsp;базе данных, а&nbsp;только
                                        ограниченный список лиц.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end sFAQBlock -->

            <section class="cta section" >
                <div class="container px-5 py-5">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="text-dark display-1 lh-1 mb-4">
                                А вот что<br>мы уже создали
                            </h2>
                            <a class="btn btn-outline-primary py-3 px-4 mb-3"
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
                                                <p class="mb-0 fw-bold text-primary"> {{ bot.bot_domain }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </perfect-scrollbar>
                        </div>
                    </div>
                </div>
            </section>
            <!-- start sReviews-->
<!--            <section class="sReviews section" id="sReviews">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>Отзывы наших клиентов</h2>
                        <div class="section-title__yandex-rate"><img src="/landingNew/storage/site/img/svg/yandexRaiting2.svg"
                                                                     alt="" loading="lazy"/>
                            <svg class="icon icon-rateStar ">
                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                            </svg>
                            4.9 из 5
                        </div>
                    </div>
                    <div class="sReviews__slider swiper defaultSwiper defaultSwiper&#45;&#45;js">
                        <div class="swiper-wrapper">
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #009FE3"><span>К</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Катерина Ю.</div>
                                            <span>6 декабря 2021</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Мы очень рады пользоваться новым направлением в приеме заказов. Телеграмм Бот
                                            не только нас и наших гостей очень порадовал, но и открыл новые возможности
                                            в приеме заказов он-лайн. Всем советуем.</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #4BB14B"><span>С</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Святослав Л.</div>
                                            <span>1 декабря 2021</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Очень крутой инструмент для доставки особенно в период нестабильной работы
                                            заведений из-за ограничений. огромное спасибо, что мы стали партнерами.</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #6F64EC"><span>Р</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Руслан Кашапов</div>
                                            <span>15 июня 2022</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Случайно наткнулся на данный бот! Настроил за 10 минут. Отличный бот, идеален
                                            для фастфуда!!!! Функционал на пять баллов!!!!</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #009FE3"><span>А</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Анонимный отзыв</div>
                                            <span>30 декабря 2022</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Сервис на бомбовом уровне. Система автоматизации приема заказов настроенная
                                            через их сервис работает без нареканий, желаю сервису продвижений и
                                            развития. Надеемся на долгосрочное сотрудничество и приятных
                                            обновлений!)</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #4BB14B"><span>В</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Вадим Репин</div>
                                            <span>24 октября 2021</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Я был первым кто подключил себе Бота, он заменяет и приложение и сайт сразу
                                            очень удобный и понятный интерфейс для любого человека. Это новое
                                            направление и не нужно переплачивать как яндексу и деливери по 40% прибыли.
                                            Если кто-то сомневается, позвоните мне я как пользователь все расскажу,
                                            пользуюсь и буду пользоваться. Расскажу о плюсах</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #6F64EC"><span>Б</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Бедоевский БЛОГ</div>
                                            <span>8 сентября 2022</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Всем привет! Ненавижу переплачивать за бренд или громкое имя, тем более так
                                            много как яндекс еде, случайно наткнулся на данный сервис. Рекомендую всем
                                            тем, кто против монополий агрегаторов!</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #F5B04D"><span>К</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Кирилл Бефидел</div>
                                            <span>24 декабря 2022</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Пока по нарастающей люди пользуются и начинают переходить в телеграм бота ,
                                            так что + одна платформа и ещё такая крутая и простая , красавцы парни ,
                                            развивайте дальше 🔥🔥🔥</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #4BB14B"><span>Р</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Рано Яхина</div>
                                            <span>19 мая 2022</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Наш ресторан очень рад, что есть такой замечательный бот. Служба
                                            доставки организована бесплатно благодаря этому боту. Служба поддержки - это
                                            терпеливые ребята, которые приходят на помощь в любое время.
                                            Спасибо Вам большое</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #6F64EC"><span>О</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Ольга Ж</div>
                                            <span>13 января 2023</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Офигенный бот, давно искал нечто подобное. Общителья поддержка, удобный
                                            интерфейс, что ещё нужно?))</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                            <div class="sReviews__slide swiper-slide">
                                <div class="sReviews__item">
                                    <div class="sReviews__head">
                                        <div class="sReviews__img-wrap" style="&#45;&#45;bgColor: #F5B04D"><span>А</span>
                                        </div>
                                        <div class="sReviews__text">
                                            <div class="h6">Антон Мельников</div>
                                            <span>8 ноября 2022</span>
                                        </div>
                                    </div>
                                    <div class="sReviews__content">
                                        <div class="sReviews__stars" data-rating="2">
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                            <svg class="icon icon-rateStar ">
                                                <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#rateStar"></use>
                                            </svg>
                                        </div>
                                        <p>Огромное спасибо команде проекта за помощь в настройках и предварительную
                                            консультацию. Служба поддержки на высоте. Отдельно хочу отметить качество
                                            продукта, визуал бота и тонкости настройки, которые выгодно отличают
                                            его от других представленных на рынке.</p>
                                        <a target="_blank"
                                           href="#">Читать отзыв
                                            на Яндекс.Карты</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sReviews__arrow-wrap">
                            <div class="swiper-button-hand swiper-button-hand-prev swiper-button-prev">
                                <svg class="icon icon-chevron-left ">
                                    <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#chevron-left"></use>
                                </svg>
                            </div>
                            <div class="swiper-button-hand swiper-button-hand-next swiper-button-next">
                                <svg class="icon icon-chevron-right ">
                                    <use xlink:href="/landingNew/storage/site/img/svg/sprite.svg#chevron-right"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </section>-->
            <!-- end sReviews-->
            <!-- start sTryIt-->
            <section class="sTryIt section" id="sTryIt">
                <div class="container">
                    <div class="sTryIt__wrap">
                        <h2>Попробуйте уже сегодня</h2>
                        <p>Создайте WebApp чат-бота за 5 минут без разработчиков и магии</p>
                        <a class="btn btn-primary"
                           data-bs-toggle="modal"
                           data-bs-target="#feedbackModal"
                           href="#">Попробовать</a>
                        <img src="/landingNew/storage/site/img/sTryIt-bg.png" alt=""/>
                    </div>
                </div>
            </section>
            <!-- end sTryIt-->


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
            success: false,
            error: false,
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
        padding: 0;
    }
}

.custom-h1-1 {
    font-weight: 100;
    font-size: 40px;
    text-align: center;
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
    background: transparent !important;
    border: 1px white solid !important;
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
        background-color: white !important;
        cursor: pointer;

        i,
        p {
            color: black;
        }
    }
}


.cta {
    .container {
        background-image: url(/landing/images/imgpreview.jpg);
        background-size: cover;
        border-radius: 10px;
    }
}
</style>
