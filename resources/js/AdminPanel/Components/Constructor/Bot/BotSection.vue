<script setup>

import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
import BotSlugList from "@/AdminPanel/Components/Constructor/Slugs/BotSlugTableList.vue";
import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";

import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import Page from "@/AdminPanel/Components/Constructor/Pages/Page.vue"
import BotDialogGroupList from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogGroupList.vue";
import Shop from "@/AdminPanel/Components/Constructor/Shop/Shop.vue";
import AmoForm from "@/AdminPanel/Components/Constructor/Amo/AmoForm.vue";
import CdekForm from "@/AdminPanel/Components/Constructor/Cdek/CdekForm.vue";
import YClientsForm from "@/AdminPanel/Components/Constructor/YClients/YClientsForm.vue";
import Mail from "@/AdminPanel/Components/Constructor/Mail/Mail.vue";
import BotFields from "@/AdminPanel/Components/Constructor/Bot/BotFields.vue";
import BotMediaTable from "@/AdminPanel/Components/Constructor/BotMediaTable.vue";
import Appointment from "@/AdminPanel/Components/Constructor/Appointment/Appointment.vue";
import Quizzes from "@/AdminPanel/Components/Constructor/Quiz/Quizzes.vue";
import InlineQuery from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQuery.vue";
import PromoCodes from "@/AdminPanel/Components/Constructor/PromoCodes/PromoCodes.vue";
import FrontPadForm from "@/AdminPanel/Components/Constructor/FrontPad/FrontPadForm.vue";
import Statistic from "@/AdminPanel/Components/Constructor/Statistic/Statistic.vue";
</script>
<template>

<!--    <div class="d-flex justify-content-center">


        <button
            class="centered-top-menu btn btn-link"
                v-if="hasRole('admin')"
                type="button" data-bs-toggle="offcanvas"
                data-bs-target="#bot-section-menu" aria-controls="staticBackdrop">
            Управление ботом <i class="fa-solid fa-screwdriver-wrench p-2 text-primary"></i>
        </button>


    </div>-->

    <div v-if="step===19" class="pb-5 mb-5">
        <CdekForm
            :data="bot.cdek||null"
            v-if="!load"
        />
    </div>
    <div v-if="step===18" class="pb-5 mb-5">
        <Statistic :bot="bot"
                   v-if="!load">
        </Statistic>
    </div>

    <div v-if="step===14" class="pb-5 mb-5">
        <Quizzes
            :bot="bot"
            v-if="!load"
        />
    </div>

    <div v-if="step===16" class="pb-5 mb-5">
        <PromoCodes
            :bot="bot"
            v-if="!load"
        />
    </div>

    <div v-if="step===13" class="pb-5 mb-5">
        <Appointment
            :bot="bot"
            v-if="!load"
        />
    </div>

    <div v-if="step===0" class="pb-5 mb-5">
        <BotForm
            v-on:callback="botCallbackUpdate"
            :bot="bot"
            v-if="!load"
        />
    </div>

    <div v-if="step===15" class="pb-5 mb-5">
        <InlineQuery
            :bot="bot"
            v-if="!load"
        />
    </div>

    <div v-if="step===7" class="pb-5 mb-5">
        <AmoForm
            :data="bot.amo"
            v-if="!load"
        />
    </div>

    <div v-if="step===17" class="pb-5 mb-5">
        <FrontPadForm
            :data="bot.frontPad"
            v-if="!load"
        />
    </div>

    <div v-if="step===12" class="pb-5 mb-5">
        <YClientsForm
            :data="bot.y_clients"
            v-if="!load"
        />
    </div>

    <div v-if="step===11" class="pb-5 pt-2 mb-5">
        <BotMediaTable
            v-if="!load"
        />
    </div>


    <div v-if="step===8" class="pb-5 mb-5">
        <Shop v-if="!load"/>
    </div>

    <div v-if="step===10" class="pb-5 mb-5 pt-2">
        <BotFields v-if="!load"/>
    </div>

    <div v-if="step===1" class="pb-5 mb-5">
        <KeyboardList
            :select-mode="false"
            v-if="!load"/>
    </div>

    <div v-if="step===6" class="pb-5 mb-5 pt-2">
        <BotDialogGroupList
            v-if="!load"/>
    </div>

    <div v-if="step===2" class="pb-5 mb-5 pt-2">
        <BotSlugList
            v-if="!load"
        />
    </div>

    <div v-if="step===3" class="pb-5 mb-5 pt-2">
        <BotUserList
            v-if="!load"/>
    </div>

    <div class="row pb-5 mb-5" v-if="step===4">
        <div class="col-12 col-md-12" v-if="!load">
            <Page
                v-if="!loadPage"
                :page="page"
                v-on:bot-settings="botSettingsCallback"
                v-on:callback="pageCallback"/>
        </div>

        <!--        <div class="col-12 col-md-4" v-if="!load">
                    <PagesList
                        :editor="true"
                        v-on:callback="pageListCallback"/>

                </div>-->
    </div>

    <div v-if="step===9" class="pb-5 mb-5">
        <Mail :bot="bot"/>
    </div>


    <div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false"
         tabindex="-1" id="bot-section-menu" aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="staticBackdropLabel">Меню управления ботом</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a
                        v-bind:class="{'active':tab===0}"
                        @click="tab=0"
                        class="nav-link" aria-current="page" href="javascript:void(0)">Информация о боте</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       v-bind:class="{'active':tab===1}"
                       @click="tab=1"
                       href="javascript:void(0)">Управление ботом</a>
                </li>

            </ul>
            <ul class="list-group list-group-flush" v-if="tab===0">
                <li class="list-group-item"><strong>ID:</strong> {{bot.id}}</li>
                <li class="list-group-item"><strong>Краткое описание:</strong> {{bot.short_description}}</li>
                <li class="list-group-item"><strong>Описание:</strong> {{bot.description}}</li>
                <li class="list-group-item"><strong>Домен бота:</strong> {{bot.bot_domain}}</li>
                <li class="list-group-item"><strong>Приветственное сообщение:</strong> {{bot.welcome_message}}</li>
                <li class="list-group-item"><strong>Основной канал:</strong> {{bot.main_channel}}</li>
                <li class="list-group-item"><strong>Канал заказов:</strong> {{bot.order_channel}}</li>
                <li class="list-group-item"><strong>Тип бота:</strong> {{bot.bot_type_id}}</li>
                <li class="list-group-item"><strong>Уровень 1:</strong> {{bot.level_1}}</li>
                <li class="list-group-item"><strong>Уровень 2:</strong> {{bot.level_2}}</li>
                <li class="list-group-item"><strong>Уровень 3:</strong> {{bot.level_3}}</li>
                <li class="list-group-item"><strong>Автокешбэк:</strong> {{bot.auto_cashback_on_payments ? 'Да' : 'Нет'}}</li>
                <li class="list-group-item"><strong>Компания:</strong> {{bot.company.title}}</li>
                <li class="list-group-item"><strong>Адрес компании:</strong> {{bot.company.address}}</li>
                <li class="list-group-item"><strong>Телефоны компании:</strong> {{(bot.company.phones||[]).join(', ')}}</li>
            </ul>
            <ul class="list-group list-group-flush bot-section-menu-group py-3" v-if="tab===1">
                <h6>Основная информация</h6>
                <li><a class="bg-primary text-white list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(0)">
                    <i class="fa-solid fa-info mr-2"></i>Базовые настройки бота</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(4)">
                    <i class="fa-solid fa-file mr-2"></i>Страницы</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(18)">
                    <i class="fa-solid fa-file mr-2"></i>Статистика</a></li>

                <h6>Рассылки и события</h6>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(9)"><i
                    class="fa-regular fa-newspaper mr-2"></i> Рассылки</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(13)">
                    <i class="fa-regular fa-calendar-days  mr-2"></i>Запись на событие (прием)</a></li>

                <h6>Квизы и промокоды</h6>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(14)">
                    <i class="fa-solid fa-q mr-2"></i>Квизы</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(16)">
                    <i class="fa-solid fa-q mr-2"></i>Промокоды</a></li>

                <h6>Настройки и запросы</h6>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(15)">
                    <i class="fa-solid fa-clipboard-list mr-2"></i>Встраиваемые запросы</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(10)">
                    <i class="fa-solid fa-code mr-2"></i>Настраиваемые поля</a></li>

                <h6>Элементы бота</h6>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(1)"><i
                    class="fa-solid fa-keyboard mr-2"></i>Все клавиатуры в боте</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(2)"><i
                    class="fa-solid fa-scroll mr-2"></i>Все скрипты в боте</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(6)"><i
                    class="fa-solid fa-comment-dots mr-2"></i>Все диалоги в боте</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(3)"><i
                    class="fa-solid fa-users mr-2"></i>Все пользователи в боте</a></li>

                <h6>Интеграции</h6>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(7)"><i
                    class="fa-solid fa-list-check mr-2"></i> AMO CRM</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(19)"><i
                    class="fa-solid fa-list-check mr-2"></i> CDEK</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(17)"><i
                    class="fa-solid fa-list-check mr-2"></i> FrontPad</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(12)"><i
                    class="fa-solid fa-list-check mr-2"></i> YClients</a></li>

                <h6>Магазин и медиа</h6>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(8)"><i
                    class="fa-brands fa-shopify mr-2"></i> Магазин</a></li>
                <li><a class="list-group-item list-group-item-action" href="javascript:void(0)" @click="setStep(11)"><i
                    class="fa-brands fa-shopify mr-2"></i> Медиа файлы бота</a></li>
            </ul>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            page: null,
            step: -1,
            tab:1,
            load: false,
            loadPage: false,
        }
    },


    mounted() {
        this.setStep(localStorage.getItem("cashman_set_botform_step_index") || 0)


        window.addEventListener('open-base-bot-params-event', (event) => {
            this.$nextTick(() => {
                this.setStep(0)
            })

        });
    },
    methods: {
        botCallbackUpdate(bot) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {

            })
            this.$emit("callback")
        },
        hasRole(role) {
            return window.hasRole(role) || false
        },
        setStep(index) {
            this.step = parseInt(index)
            localStorage.setItem("cashman_set_botform_step_index", index)
        },
        pageListCallback(page) {
            this.loadPage = true
            this.page = page
            this.$nextTick(() => {
                this.loadPage = false

            });
        },
        botSettingsCallback() {
            this.step = 0
            document.documentElement.scrollTop = 200;
            this.$nextTick(() => {
                window.dispatchEvent(new CustomEvent('add-payment-system-event'));

            })
        },
        pageCallback(page) {
            this.page = page
            this.loadPageList = true
            this.$nextTick(() => {
                this.loadPageList = false
            });
        },
        loadCurrentCompany(company = null) {
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(() => {
                this.company = this.getCurrentCompany
            })
        },
        companyListCallback(company) {
            this.load = true
            this.loadCurrentCompany(company)
            this.$nextTick(() => {
                this.load = false
            })

        },
    }
}
</script>

<style lang="scss">
.custom-dropdown {
    display: inline-block;
    position: sticky !important;
    top: 50px;
    z-index: 101;

    .btn {
        background: white;
    }
}

.bot-section-menu-group {
    .list-group-item {
        border: none;
    }
}

.centered-top-menu {
    display: inline-block;
    position: fixed !important;
    top:0px;
    z-index: 1000;
}
</style>
