<script setup>

import {Head as VueHead} from '@inertiajs/vue3'

import Header from "@/Landing/Components/Header.vue"
import Footer from "@/Landing/Components/Footer.vue"
</script>
<template>

    <VueHead>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </VueHead>

    <notifications position="top right"/>

    <div class="main-wrapper">
        <Header/>

        <main>
            <slot/>
        </main>


        <Footer/>

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
                            <button
                                class="btn btn-light bg-gradient-primary-to-secondary text-white rounded-pill btn-lg"
                                id="submitButton"
                                type="submit">Отправить заявку
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>
<script>


export default {
    props: ["active"],
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
    computed: {},


    mounted() {

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

.bg-gradient-primary-to-secondary {
    background: linear-gradient(45deg, #2196F3, #03A9F4) !important;
}

.text-gradient {
    background: -webkit-linear-gradient(#2196F3, #03A9F4);
    background-clip: initial;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

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
