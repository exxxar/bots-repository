<script setup>
import ManagerProfile from "@/AdminPanel/Components/Constructor/Manager/ManagerProfile.vue";
import {Head} from '@inertiajs/vue3'
import Chat from "@/AdminPanel/Components/Chat/ChatMini.vue";
import BotSearchModal from "@/AdminPanel/Components/Constructor/Bot/BotSearchModal.vue";
import PromoCodeActivateForm from "@/AdminPanel/Components/Constructor/PromoCodes/PromoCodeActivateForm.vue";


</script>
<template>
    <Head>
        <title>Кабинет администратора</title>
        <meta name="description" content="Кабинет администратора<">
    </Head>

    <notifications position="top right"/>


    <nav class="navbar navbar-expand-lg sticky-top bg-primary ">
        <div class="container">


            <BotSearchModal
                :id="'global-bot-search'"
                :custom-class="'dropdown-toggle my-2 text-white'"
                v-if="!load"
                v-on:select-bot="botSectionCallback"
                :bot="bot"></BotSearchModal>




                <button
                    class="btn text-white p-2 btn-outline-light"
                    style="font-size:12px;"
                    @click="openTariffsModal"
                    aria-controls="staticBackdrop">
                    <i class="fas fa-money-bill"></i> У вас
                    <span class="fw-bold text-white">0</span> руб, <span
                    class="fw-bold text-white">{{ slotsCount }}</span> свободных слотов
                </button>



            <div class="d-flex">
            <button
                v-if="bot"
                class="btn text-white p-2 px-3 border-light mr-2 "
                style="font-size:12px;"
                type="button" data-bs-toggle="offcanvas"
                data-bs-target="#bot-section-menu" aria-controls="staticBackdrop">
                <i class="fa-solid fa-screwdriver-wrench text-white"></i>
            </button>
            <div class="dropdown">
                <button
                    style="font-size:12px;"
                    class="btn btn-outline-light text-white dropdown-toggle p-2 " type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                    {{ profile?.name || profile?.username || profile?.telegram_chat_id || '-' }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                           data-bs-toggle="offcanvas"
                           role="button"
                           href="#profile-sidebar">Профиль</a></li>
                    <li><a
                        href="javascript:void(0)"
                        data-bs-toggle="modal" data-bs-target="#open-payment-modal"
                        class="dropdown-item">Пополнить баланс</a></li>
                    <li><a
                        href="javascript:void(0)"
                        data-bs-toggle="modal" data-bs-target="#activate-promo-code"
                        class="dropdown-item">Ввести промокод</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item">Вопросы и ответы</a></li>
                    <li><a
                        data-bs-toggle="modal"
                        data-bs-target="#feedbackModal"
                        class="dropdown-item" href="javascript:void(0)">Тех. поддержка</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="/logout">Выход</a></li>
                </ul>
            </div>
            </div>
        </div>
    </nav>

    <!-- Modal -->
    <div class="modal fade" id="open-payment-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xs">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Оплата произвольной суммы</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light">
                        Через данное окно вы можете внести абсолютно любую сумму. Рекомендуем вносить оплату согласно <a
                        href="#tariff" @click="openTariffsModal">тарифным планам</a>, так
                        вы получаете помимо средств еще и дополнительные возможности в виде слотов или скидки (и другие
                        преимущества тарифного плана).
                    </div>

                    <form v-on:submit.prevent="submitPayment">
                        <div class="form-floating mb-3">
                            <input type="number"
                                   min="0"
                                   v-model="paymentForm.amount"
                                   class="form-control" id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">Сумма для внесения, руб</label>
                        </div>

                        <button class="btn btn-primary w-100 p-3"
                                type="submit">Пополнить
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade"

         id="payment-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Выбор тарифа</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-light mb-3">
                        После осуществления оплаты обновите страницу для отображения зачисленных средств!
                    </div>

                    <div class="row" v-if="tariffs.length>0">
                        <!-- Тариф 1: Базовый -->
                        <div class="col-md-4" v-for="tariff in tariffs">
                            <div class="card payment-card  h-100 d-flex justify-content-between flex-column">
                                <h3>{{ tariff.name || 'не указано' }}</h3>
                                <div class="price">{{ tariff.price || '0' }}₽</div>
                                <ul class="list-group list-group-flush" v-if="(tariff.services||[]).length>0">
                                    <li class="list-group-item" v-for="service in tariff.services">{{ service }}</li>
                                </ul>
                                <button
                                    type="button"
                                    @click="submitPayment(tariff.price)"
                                    class="btn btn-primary p-3">Выбрать
                                </button>
                            </div>
                        </div>
                    </div>
                    <template v-else>
                        <p class="alert alert-light">
                            Подождите, мы загружаем тарифы!
                        </p>
                    </template>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4 px-0">
                <div class="pt-md-3 pt-0 pb-md-2 pb-0 mb-3">
                    <slot/>
                </div>
            </main>
        </div>
    </div>
    <!--    <div class="modal fade"
             id="selected-company-bot-info" tabindex="-1" aria-labelledby="open-construct-label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="open-construct-label">У вас выбрано</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="card border-info mb-2" v-if="company">
                            <div class="card-body">
                                <p>У Вас выбран клиент:</p>
                                <div class="d-flex justify-content-between w-100">
                                    <span>{{ company.title || 'Без имени' }} </span>
                                    <span @click="resetCompany"><i class="fa-solid fa-xmark"></i></span>
                                </div>

                            </div>
                        </div>

                        <div class="card border-info" v-if="bot">
                            <div class="card-body">
                                <p>У Вас выбран бот:</p>
                                <div class="d-flex justify-content-between w-100">
                                    <span><a :href="'https://t.me/'+(bot.bot_domain||'botfather')"
                                             target="_blank">{{ bot.bot_domain || 'Без имени' }}</a> </span>
                                    <span @click="resetBot"><i class="fa-solid fa-xmark"></i></span>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>-->

    <div class="theme-switcher d-none d-md-flex flex-column">
        <button id="switch-theme"
                data-bs-toggle="modal" data-bs-target="#theme-switch-modal"
                class="btn btn-primary">
            <i class="fa-solid fa-palette"></i>
        </button>

        <button
            @click="changeLight"
            class="btn btn-primary mt-2">
            <i class="fa-solid fa-moon"></i>
        </button>
    </div>

    <div class="modal"
         id="theme-switch-modal"
         tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Выбор темы оформления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <button type="button"
                                @click="switchTheme(index)"
                                v-for="(theme, index) in themes"
                                v-bind:class="{'active':currentTheme.indexOf(theme.href)!=-1}"
                                class="list-group-item list-group-item-action " aria-current="true">
                            {{ theme.title || '-' }}
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="activate-promo-code" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <template v-if="profile">
                    <div class="modal-body" v-if="profile.manager">
                        <h6 class="text-center">Активируйте ваш ключ и получите дополнительные слоты</h6>
                        <PromoCodeActivateForm
                            :bot="bot"></PromoCodeActivateForm>
                    </div>
                    <div class="modal-body" v-else>
                        <div class="alert alert-primary">Вы не являетесь менеджером!</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Feedback Modal-->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary p-4">
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
                             class="alert alert-success text-center my-3 rounded-3"
                             id="submitSuccessMessage">
                            <span class="fw-bolder">Спасибо! Вы успешно отправили заявку!</span><br>
                            Наш менеджер свяжется с вами!

                        </div>
                        <!-- Submit error message-->
                        <!---->
                        <!-- This is what your users will see when there is-->
                        <!-- an error submitting the form-->
                        <div
                            v-if="error"
                            class="alert alert-danger my-3 text-center rounded-2"
                            id="submitErrorMessage">
                            Ошибка отправки заявки
                        </div>
                        <!-- Submit Button-->
                        <div class="d-grid">
                            <button
                                class="btn btn-success text-white rounded-2 btn-lg"
                                id="submitButton"
                                type="submit">Отправить заявку
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-start" tabindex="-1" id="profile-sidebar" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Ваш профиль</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <ManagerProfile/>
            </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from "vuex";

export default {
    data() {

        let currentDate = new Date();
        let currentTime = currentDate.getHours() + ":" + currentDate.getMinutes()

        return {
            payment_modal: null,
            paymentForm: {
                amount: 0,
            },
            success: false,
            error: false,
            feedbackForm: {
                name: null,
                email: null,
                phone: null,
                time: currentTime,
                message: 'Добрый день! Возникли технические сложности, свяжитесь со мной!'
            },
            bot: null,
            load: false,
            is_chat_open: false,
            currentTheme: '',
            company: null,
            tariffs: [],
            themes: [
                {
                    title: 'Тема 1',
                    href: '/theme1.bootstrap.min.css',
                },
                {
                    title: 'Тема 2',
                    href: '/theme2.bootstrap.min.css',
                },
                {
                    title: 'Тема 3',
                    href: '/theme3.bootstrap.min.css',
                },
                {
                    title: 'Тема 4',
                    href: '/theme4.bootstrap.min.css',
                },
                {
                    title: 'Тема 5',
                    href: '/theme5.bootstrap.min.css',
                },
                {
                    title: 'Тема 6',
                    href: '/theme6.bootstrap.min.css',
                },
                {
                    title: 'Тема 7',
                    href: '/theme7.bootstrap.min.css',
                },
                {
                    title: 'Тема 8',
                    href: '/theme8.bootstrap.min.css',
                },
                {
                    title: 'Тема 9',
                    href: '/theme9.bootstrap.min.css',
                },
                {
                    title: 'Тема 10',
                    href: '/theme10.bootstrap.min.css',
                }
                ,
                {
                    title: 'Тема 11',
                    href: '/theme11.bootstrap.min.css',
                },
                {
                    title: 'Тема 12',
                    href: '/theme12.bootstrap.min.css',
                },
                {
                    title: 'Тема 13',
                    href: '/theme13.bootstrap.min.css',
                },
                {
                    title: 'Тема 14',
                    href: '/theme14.bootstrap.min.css',
                },
                {
                    title: 'Тема 15',
                    href: '/theme15.bootstrap.min.css',
                },
                {
                    title: 'Тема 16',
                    href: '/theme16.bootstrap.min.css',
                }
            ]
        }
    },
    computed: {
        ...mapGetters(['getErrors', 'getCurrentBot', 'getCurrentCompany']),
        profile() {
            return window.profile || null
        },
        slotsCount() {
            return this.profile?.manager?.max_bot_slot_count || 0
        },
    },
    watch: {
        getErrors: function (newVal, oldVal) {
            Object.keys(newVal).forEach(key => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: newVal[key],
                    type: 'warn'
                });
            })

        }
    },
    mounted() {

        this.payment_modal = new bootstrap.Modal(document.getElementById('payment-modal'), {})

        this.loadCurrentCompany()
        this.loadCurrentBot()

        let theme = localStorage.getItem("cashman_global_admin_theme") || null
        let light = localStorage.getItem("cashman_global_admin_light") || null

        if (theme) {
            this.$nextTick(() => {
                this.currentTheme = theme
            })
        }

        if (light) {
            this.changeLight(light)
        }

        window.addEventListener('store_current_bot-change-event', (event) => {
            this.bot = this.getCurrentBot
        });

        window.addEventListener('store_current_company-change-event', (event) => {
            this.company = this.getCurrentCompany
        });
    },

    methods: {
        openTariffsModal() {
            this.loadActualTariffs()
            this.payment_modal.show()
        },
        submitPayment(amount = 0) {
            if (amount > 0)
                this.paymentForm.amount = amount

            let data = new FormData();
            Object.keys(this.paymentForm)
                .forEach(key => {
                    const item = this.paymentForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("sendInvoice", {
                paymentForm: data
            }).then((response) => {

                this.$notify({
                    title: "Оплата услуг сервиса",
                    text: "Платежная ссылка успешно сформирована!",
                    type: 'success'
                });

                const url = response.url

                window.open(url, '_blank').focus();


            }).catch(() => {
                this.$notify({
                    title: "Оплата услуг сервиса",
                    text: "Ошибка формирования платежной ссылки",
                    type: 'error'
                });
            })
        },
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


            this.$store.dispatch("sendFeedback", {
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
        botSectionCallback() {
            window.dispatchEvent(new CustomEvent('change-current_bot-from-navbar-event'));
        },
        changeLight(mode = null) {
            let lighting = document.querySelector("html")

            if (mode != null && (mode === 'light' || mode === 'dark')) {
                lighting.setAttribute("data-bs-theme", mode);
                localStorage.setItem("cashman_global_admin_light", mode)
                return
            }

            if (lighting.hasAttribute('data-bs-theme')) {
                let type = lighting.getAttribute('data-bs-theme')
                lighting.setAttribute("data-bs-theme", type === "dark" ? "light" : "dark");
            } else {
                lighting.setAttribute("data-bs-theme", "dark");
            }


            localStorage.setItem("cashman_global_admin_light", lighting.getAttribute('data-bs-theme') || 'light')


        },
        hasRole(role) {
            return window.hasRole(role) || false
        },
        switchTheme(index) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = this.themes[index].href //`./theme${index}.bootstrap.min.css`
            localStorage.setItem("cashman_global_admin_theme", changeTheme.href)


            this.$nextTick(() => {
                this.currentTheme = changeTheme.href
            })


        },
        loadActualTariffs() {
            if (this.tariffs.length>0)
                return;

            this.$store.dispatch("getActualTariffs").then((response) => {
                this.tariffs = response || []
            })
        },
        loadCurrentCompany(company = null) {
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(() => {
                this.company = this.getCurrentCompany
            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        resetCompany() {
            this.$store.dispatch("resetCurrentCompany").then(() => {
                this.company = null

                window.dispatchEvent(new CustomEvent('store_current_company-change-event'));
            })
        },
        resetBot() {
            this.$store.dispatch("resetCurrentBot").then(() => {
                this.bot = null

                window.dispatchEvent(new CustomEvent('store_current_bot-change-event'));
            })
        },
        stopAllDialogs() {
            this.$store.dispatch("stopDialogs").then((response) => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Все диалоги остановлены",
                    type: 'success'
                });

            }).catch(err => {
            })
        },

    }
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

.border-bottom-active {
    border-bottom: 1px white solid;
}

.theme-switcher {
    position: fixed;
    bottom: 50px;
    right: 50px;
    background: white;
    /* border-radius: 50%; */
    padding: 7px;
    z-index: 1000;
    border: 1px #f7f7f7 solid;
    box-shadow: 0px 0px 1px 0px #dadada;
    border-radius: 10px;
}

.chat-dialog-btn {
    position: fixed;
    bottom: 120px;
    right: 50px;
    background: white;
    /* border-radius: 50%; */
    padding: 7px;
    z-index: 1000;
    border: 1px #f7f7f7 solid;
    box-shadow: 0px 0px 1px 0px #dadada;
    border-radius: 10px;
}


.payment-card {
    text-align: center;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.payment-card:hover {
    transform: scale(1.05);
}

.payment-card h3 {
    font-size: 1.5rem;
    margin: 0;
}

.payment-card .price {
    font-size: 2rem;
    font-weight: bold;
    margin: 20px 0;
    color: #007bff;
}

.payment-card ul {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.payment-card ul li {
    margin: 10px 0;
}

.payment-card .btn {
    width: 100%;
    margin-top: 20px;
}

.bg-gradient-primary-to-secondary {
    background: linear-gradient(45deg, #2196F3, #03A9F4) !important;
}

.text-gradient {
    background: -webkit-linear-gradient(#2196F3, #03A9F4);
    background-clip: initial;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

</style>
