<script setup>
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
import BotSlugList from "@/AdminPanel/Components/Constructor/Slugs/BotSlugList.vue";
import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";

import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";

import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import Page from "@/AdminPanel/Components/Constructor/Pages/Page.vue"
import ImageMenu from "@/AdminPanel/Components/Constructor/ImageMenu.vue";
import BotDialogGroupList from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogGroupList.vue";
import Shop from "@/AdminPanel/Components/Constructor/Shop/Shop.vue";
import AmoForm from "@/AdminPanel/Components/Constructor/Amo/AmoForm.vue";
</script>
<template>

    <div class="card card-style bg-1"
         v-if="bot"
         style="height: 580px;">
        <div class="card-center">
            <div class="w-100 d-flex justify-content-center p-3">
                <img
                    class="object-cover" style="width:100px; border-radius:50%;"
                    v-lazy="'/images-by-company-id/'+bot.company_id+'/'+bot.image" alt="">
            </div>

            <h2 class="color-white font-700 text-center mb-2">
                {{ bot.bot_domain || 'Нет домена' }}
            </h2>
            <p class="color-white text-center opacity-60 mt-n1 my-1">
                <a href="#" class="color-white"><i class="fa-solid fa-sack-dollar"></i> Баланс {{ bot.balance || '0' }}
                    ₽</a>
            </p>
            <p class="color-white text-center opacity-60 mt-n1 my-1">
                <i class="fa-solid fa-hand-holding-dollar"></i> Тариф {{ bot.tax_per_day || '0' }} ₽/день
            </p>

            <a href="javascript:void(0)"
               @click="goToStep(0)"
               v-bind:class="{'bg-highlight':step===0,'bg-gray2-light': step!==0}"
               class="btn btn-m font-900 text-uppercase btn-center-xl mb-2">
                <i class="fa-solid fa-info mr-1"></i> Информация о боте
            </a>
            <a href="javascript:void(0)"
               @click="step=1"
               v-bind:class="{'bg-highlight':step===1,'bg-gray2-light': step!==1}"
               class="btn btn-m font-900 text-uppercase btn-center-xl mb-2">
                <i class="fa-solid fa-file mr-2"></i> Страницы бота
            </a>
            <a href="javascript:void(0)"
               @click="step=2"
               v-bind:class="{'bg-highlight':step===2,'bg-gray2-light': step!==2}"
               class="btn btn-m font-900 text-uppercase btn-center-xl mb-2">
                <i class="fa-solid fa-list-check mr-2"></i> Настройка AMO
            </a>
            <a href="javascript:void(0)"
               @click="step=3"
               v-bind:class="{'bg-highlight':step===3,'bg-gray2-light': step!==3}"
               class="btn btn-m font-900 text-uppercase btn-center-xl mb-2">
                <i class="fa-brands fa-shopify mr-2"></i> Настройка Магазина
            </a>
            <a href="javascript:void(0)"
               @click="step=3"
               v-bind:class="{'bg-highlight':step===3,'bg-gray2-light': step!==3}"
               class="btn btn-m font-900 text-uppercase btn-center-xl mb-2">
                <i class="fa-brands fa-shopify mr-2"></i> Настройка меню
            </a>
            <a href="javascript:void(0)"
               @click="step=3"
               v-bind:class="{'bg-highlight':step===3,'bg-gray2-light': step!==3}"
               class="btn btn-m font-900 text-uppercase btn-center-xl">
                <i class="fa-brands fa-shopify mr-2"></i> Настройка клавиатур
            </a>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>

    <form v-on:submit.prevent="addBot">
        <div class="card card-style" v-if="step===0">
            <div class="content">
                <div class="mb-0">
                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between mt-2" id="bot-domain">
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Строго взять из BotFather! ТО что при создании с окончанием на "bot"</div>
                                </template>
                            </Popper>
                            Доменное имя бота из BotFather
                            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                        </label>
                        <input type="text" class="form-control"
                               placeholder="Имя бота"
                               aria-label="Имя бота"
                               v-model="botForm.bot_domain"
                               maxlength="255"
                               aria-describedby="bot-domain" required>
                        <p v-if="botForm.bot_domain">Проверить работу бота <a
                            :href="'https://t.me/'+botForm.bot_domain"
                            target="_blank">@{{
                                botForm.bot_domain
                            }}</a>
                        </p>
                    </div>

                    <div class="mb-2">
                        <label
                            class="form-label d-flex justify-content-between mt-2 d-flex justify-content-between flex-wrap"
                            id="bot-token">

                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Взять из BotFater при создании бота! Длинная нечитаемая подсвеченная
                                            строка!
                                        </div>
                                    </template>
                                </Popper>
                                Токен бота
                            </div>

                            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

                            <a href="https://t.me/botfather" class="w-100" target="_blank">Создать нового бота в
                                ТГ</a>
                        </label>
                        <input type="text" class="form-control"
                               placeholder="Токен"
                               aria-label="Токен"
                               v-model="botForm.bot_token"
                               maxlength="255"
                               aria-describedby="bot-token" required>
                    </div>


                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between mt-2" id="bot-token-dev">Токен бота
                            (для тестирования)</label>
                        <input type="text" class="form-control"
                               placeholder="Токен"
                               aria-label="Токен"
                               v-model="botForm.bot_token_dev"
                               maxlength="255"
                               aria-describedby="bot-token-dev">
                    </div>


                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <label
                                class="form-label d-flex justify-content-between mt-2 d-flex justify-content-center w-100"
                                id="bot-order-channel">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Ввести адрес ссылки на канал в форму после добавления тоукена
                                            </div>
                                        </template>
                                    </Popper>
                                    Канал для заказов (id) <a href="" class="ml-2"><i
                                    class="fa-brands fa-telegram"></i></a>
                                </div>
                                <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>

                            </label>

                            <!--                                <TelegramChannelHelper
                                                                :token="botForm.bot_token"
                                                                :param="'order_channel'"
                                                                v-on:callback="addTextTo"
                                                            />-->
                        </div>
                        <input type="text" class="form-control"
                               placeholder="id канала"
                               aria-label="id канала"
                               v-model="botForm.order_channel"
                               maxlength="255"
                               aria-describedby="bot-order-channel">
                    </div>


                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <label class="form-label d-flex justify-content-between mt-2" id="bot-main-channel">Канал
                                для постов (id,рекламный)</label>

                            <!--                                <TelegramChannelHelper
                                                                :token="botForm.bot_token"
                                                                :param="'main_channel'"
                                                                v-on:callback="addTextTo"
                                                            />-->
                        </div>
                        <input type="text" class="form-control"
                               placeholder="id канала"
                               aria-label="id канала"
                               v-model="botForm.main_channel"
                               maxlength="255"
                               aria-describedby="bot-main-channel">
                    </div>


                </div>
                <div class="mb-0" v-if="botForm.bot_token">


                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <label class="form-label d-flex justify-content-between mb-0  flex-wrap"
                                   id="bot-description">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Отобразится пользователю при первом запуске</div>
                                        </template>
                                    </Popper>
                                    Приветственное сообщение

                                </div>
                                <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>


                                <small class="text-gray-400 w-100" style="font-size:10px;"
                                       v-if="botForm.welcome_message">
                                    Длина текста {{ botForm.welcome_message.length }}</small>
                            </label>


                        </div>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое приветствие при запуске бота"
                                  aria-label="Текстовое приветствие при запуске бота"
                                  v-model="botForm.welcome_message"
                                  aria-describedby="bot-description" required>
                    </textarea>
                    </div>


                    <div class="mb-2">
                        <div class="d-flex justify-content-between flex-wrap">
                            <label class="form-label d-flex justify-content-between w-100 mb-0" id="bot-description">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Для меню "О Боте"</div>
                                        </template>
                                    </Popper>
                                    Описание бота
                                </div>
                                <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>


                            </label>
                            <small class="text-gray-400 w-100" style="font-size:10px;"
                                   v-if="botForm.description">
                                Длина текста {{ botForm.description.length }} / 255</small>

                        </div>

                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое описание бота"
                                  aria-label="Текстовое описание бота"
                                  v-model="botForm.description"
                                  aria-describedby="bot-description" required>
                    </textarea>
                    </div>


                    <div class="mb-2">

                        <div class="d-flex justify-content-between flex-wrap">
                            <label class="form-label d-flex justify-content-between w-100 mb-0"
                                   id="bot-maintenance-message">

                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Для меню "О Боте"</div>
                                        </template>
                                    </Popper>
                                    Режим тех. работ
                                </div>
                                <span class="badge rounded-pill bg-danger px-3 text-white m-0">Нужно</span>


                            </label>
                            <small class="text-gray-400 w-100" style="font-size:10px;"
                                   v-if="botForm.maintenance_message">
                                Длина текста {{ botForm.maintenance_message.length }} / 255 </small>
                        </div>
                        <textarea type="text" class="form-control"
                                  placeholder="Текстовое сообщение"
                                  aria-label="Текстовое сообщение"
                                  v-model="botForm.maintenance_message"
                                  maxlength="255"
                                  aria-describedby="bot-maintenance-message" required>
                    </textarea>

                    </div>


                    <div class="mb-2">

                        <label class="form-label d-flex justify-content-between mt-2" id="bot-level-1">
                            Уровень 1 CashBack, %
                            <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                        </label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_1"
                               max="50"
                               min="0"
                               aria-describedby="bot-level-1" required>

                    </div>

                    <div class="mb-2">

                        <label class="form-label d-flex justify-content-between mt-2" id="bot-level-2">Уровень 2
                            CashBack, %</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_2"
                               max="50"
                               min="0"
                               aria-describedby="bot-level-2">

                    </div>

                    <div class="mb-2">

                        <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">Уровень 3
                            CashBack, %</label>
                        <input type="number" class="form-control"
                               placeholder="%"
                               aria-label="уровень CashBack"
                               v-model="botForm.level_3"
                               max="50"
                               min="0"
                               aria-describedby="bot-level-3">

                    </div>

                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="need_payments"
                                   type="checkbox"
                                   id="need-payments">
                            <label class="form-check-label" for="need-payments">
                                Необходимо подключить платежную систему
                            </label>
                        </div>

                    </div>

                    <div class=" mb-2" v-if="need_payments">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   :value="botForm.auto_cashback_on_payments"
                                   v-model="botForm.auto_cashback_on_payments"
                                   id="bot-auto-cashback-on-payments">
                            <label class="form-check-label" for="bot-auto-cashback-on-payments">
                                Начислять CashBack автоматически после успешной оплаты
                            </label>
                        </div>

                        <div class="mb-2">

                            <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Если в боте планируется оплата, то необходимо через BotFather привязать
                                                нужную
                                                платежную систему и указать в данном поле полученный токен
                                            </div>
                                        </template>
                                    </Popper>
                                    Токен платежной системы
                                </div>
                                <a href="https://t.me/botfather" target="_blank">Подключить</a>
                            </label>


                            <input type="text" class="form-control"
                                   placeholder="Токен"
                                   aria-label="уровень CashBack"
                                   v-model="botForm.payment_provider_token"
                                   aria-describedby="bot-level-3">
                        </div>
                    </div>

                    <div class=" mb-2">
                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="need_shop"
                                   type="checkbox"
                                   id="need-shop">
                            <label class="form-check-label" for="need-shop">
                                Необходимо интегрировать магазин в бота
                            </label>
                        </div>

                    </div>

                    <div class=" mb-2" v-if="need_shop">


                        <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Ссылка на страницу ВК с товарами для вашего магазина в боте
                                        </div>
                                    </template>
                                </Popper>
                                ВК-группа
                            </div>

                            <a href="https://vk.com/groups?w=groups_create" target="_blank">Создать</a>
                        </label>


                        <input type="url" class="form-control"
                               placeholder="Ссылка на группу ВК"
                               aria-label="ссылка на группу ВК"
                               v-model="botForm.vk_shop_link"
                               aria-describedby="vk_shop_link">

                    </div>

                    <div class="divider divider-small my-3 bg-highlight "></div>

                    <div class="mb-2">

                        <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">

                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Ссылка на страницу ВК с товарами для вашего магазина в боте
                                        </div>
                                    </template>
                                </Popper>
                                Информационная ссылка
                            </div>

                            <a target="_blank"
                                                     href="https://telegra.ph">Создать</a>
                        </label>
                        <input type="text" class="form-control"
                               placeholder="Ссылка ресурс telegraph"
                               aria-label="Ссылка ресурс telegraph"
                               maxlength="255"
                               v-model="botForm.info_link"
                               :aria-describedby="'bot-info-link'">

                    </div>

                    <div class="divider divider-small my-3  bg-highlight"></div>
                    <h6>Ссылки на соц. сети</h6>


                    <div class="d-flex justify-content-between mb-2 flex-wrap"
                         :key="'social-link'+index"
                         v-for="(item, index) in botForm.social_links">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <small>Ссылка</small>

                            <button
                                type="button"
                                @click="removeItem('social_links', index)"
                                class="btn btn-link text-danger"><i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control mb-2 w-100"
                               placeholder="Название ссылки"
                               aria-label="Название ссылки"
                               maxlength="255"
                               v-model="botForm.social_links[index].title"
                               :aria-describedby="'bot-social-link-'+index" required>

                        <input type="text" class="form-control  mb-2 "
                               placeholder="Ссылка на соц.сеть"
                               aria-label="Ссылка на соц.сеть"
                               maxlength="255"
                               v-model="botForm.social_links[index].url"/>


                    </div>
                    <button
                        type="button"
                        @click="addSocialLinks()"
                        class="btn btn-border btn-m btn-full mb-2 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                        Добавить еще ссылку
                    </button>
                    <div class="divider divider-small my-3  bg-highlight"></div>
                    <h6 class="d-flex justify-content-between">Аватар для бота
                        <span class="badge rounded-pill bg-danger px-3 py-2 text-white m-0">Нужно</span>
                    </h6>
                    <div class="photo-preview d-flex justify-content-center flex-wrap w-100">
                        <label for="bot-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            <span>+</span>
                            <input type="file" id="bot-photos" multiple accept="image/*"
                                   @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in botForm.photos"
                             v-if="botForm.photos">
                            <img v-lazy="getPhoto(img).imageUrl">
                            <div class="remove">
                                <a @click="removePhoto(index)"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-else>
                            <img v-lazy="'/images-by-bot-id/'+bot.id+'/'+botForm.image">
                            <div class="remove">
                                <a @click="removePhoto()"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>

                    </div>


                    <button
                        :disabled="!botForm.bot_token"
                        type="submit"
                        class="btn btn-m btn-full mb-0 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                        Сохранить настройки
                    </button>

                </div>


            </div>
        </div>

        <div class="card card-style" v-if="step===7">
            <div class="content">
                <AmoForm
                    :data="botForm.amo"
                    v-if="!load"
                />
            </div>
        </div>

    </form>


    <!--
        <div v-if="step===8">
            <Shop v-if="!load"/>
        </div>


        <div v-if="step===5">
            <ImageMenu
                v-if="!load"
            />
        </div>


        <div v-if="step===1">
            <KeyboardList
                :select-mode="false"
                v-if="!load"/>
        </div>

        <div v-if="step===6">
            <BotDialogGroupList
                v-if="!load"/>
        </div>

        <div v-if="step===2">
            <BotSlugList
                v-if="!load"
            />
        </div>

        <div v-if="step===3">
            <BotUserList
                v-if="!load"/>
        </div>

        <div class="row" v-if="step===4">
            <div class="col-12 col-md-8" v-if="!load">
                <Page
                    v-if="!loadPage"
                    :page="page"
                    v-on:callback="pageCallback"/>
            </div>

            <div class="col-12 col-md-4" v-if="!load">
                <PagesList
                    v-if="!loadPageList"
                    :editor="true"
                    v-on:callback="pageListCallback"/>

            </div>
        </div>
        </form>-->
</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            page: null,
            step: 0,
            load: false,
            loadPage: false,
            loadPageList: false,
            need_payments: false,
            need_shop: false,
            command: null,
            bot: null,
            botForm: {
                is_template: false,
                auto_cashback_on_payments: false,
                template_description: null,
                bot_domain: null,
                bot_token: null,
                bot_token_dev: null,
                order_channel: null,
                main_channel: null,
                vk_shop_link: null,
                balance: null,
                tax_per_day: null,
                welcome_message: null,
                image: null,
                description: null,
                info_link: null,
                social_links: [],
                maintenance_message: null,
                payment_provider_token: null,
                level_1: 10,
                level_2: 0,
                level_3: 0,
                photos: [],
                selected_bot_template_id: null,
                pages: [],
                amo: null
            },
        }
    },
    watch: {
        'need_payments': function (oVal, nVal) {
            if (!this.need_payments) {
                this.botForm.auto_cashback_on_payments = false
            }
        },
    },

    mounted() {


        this.loadBotAdminConfig();

    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data
                console.log(resp.data)

                this.botForm = {
                    id: this.bot.id || null,
                    is_template: this.bot.is_template || false,
                    auto_cashback_on_payments: this.bot.auto_cashback_on_payments || false,
                    template_description: this.bot.template_description || null,
                    bot_domain: this.bot.bot_domain || null,
                    bot_token: this.bot.bot_token || null,
                    bot_token_dev: this.bot.bot_token_dev || null,
                    order_channel: this.bot.order_channel || null,
                    main_channel: this.bot.main_channel || null,
                    balance: this.bot.balance || null,
                    tax_per_day: this.bot.tax_per_day || null,
                    vk_shop_link: this.bot.vk_shop_link || null,

                    image: this.bot.image || null,

                    description: this.bot.description || null,

                    info_link: this.bot.info_link || null,

                    social_links: this.bot.social_links || [],

                    maintenance_message: this.bot.maintenance_message || null,
                    welcome_message: this.bot.welcome_message || null,
                    payment_provider_token: this.bot.payment_provider_token || null,

                    level_1: this.bot.level_1 || 10,
                    level_2: this.bot.level_2 || 0,
                    level_3: this.bot.level_3 || 0,

                    photos: this.bot.photos || [],

                    amo: this.bot.amo || null,
                }

                if (this.botForm.payment_provider_token)
                    this.need_payments = true
            })
        },

        loadSlugsByBotTemplate(botId) {

            this.load = true

            this.$store.dispatch("loadBotSlugs", {
                botId: botId
            }).then((resp) => {
                this.botForm.slugs = resp

                this.$nextTick(() => {
                    this.load = false

                });
            })
        },
        loadPagesByBotTemplate(botId) {
            this.$store.dispatch("loadBotPages", {
                botId: botId
            }).then((resp) => {
                this.botForm.pages = resp
            })
        },

        getPhoto(img) {
            return {imageUrl: URL.createObjectURL(img)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.botForm.image = null
            for (let i = 0; i < files.length; i++)
                this.botForm.photos.push(files[i])
        },
        addItem(name) {
            this.botForm[name].push("")
        },
        addSocialLinks() {
            this.botForm.social_links.push({
                title: null,
                url: null
            })
        },
        removeItem(name, index) {
            this.botForm[name].splice(index, 1)
        },
        removePhoto(index) {
            if (index)
                this.botForm.photos.splice(index, 1)
            else
                this.botForm.image = null
        },
        addBot() {

            let data = new FormData();
            Object.keys(this.botForm)
                .forEach(key => {
                    const item = this.botForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            if (this.company)
                data.append("company_id", this.company.id)

            for (let i = 0; i < this.botForm.photos.length; i++)
                data.append('images[]', this.botForm.photos[i]);

            data.delete("photos")

            this.$store.dispatch((this.bot == null ? "createBot" : "updateBot"), {
                botForm: data
            }).then((response) => {

                this.$emit("callback", response.data)

                this.$notify({
                    title: "Конструктор ботов",
                    text: (this.bot == null ? "Бот успешно создан!" : "Бот успешно обновлен!"),
                    type: 'success'
                });

                if (this.bot == null)
                    this.botForm = {
                        is_template: false,
                        auto_cashback_on_payments: false,
                        template_description: null,
                        bot_domain: null,
                        bot_token: null,
                        bot_token_dev: null,
                        order_channel: null,
                        main_channel: null,
                        balance: null,
                        tax_per_day: null,

                        image: null,

                        description: null,

                        info_link: null,

                        social_links: [],

                        maintenance_message: null,
                        payment_provider_token: null,

                        level_1: 10,
                        level_2: 0,
                        level_3: 0,

                        photos: [],

                        selected_bot_template_id: null,

                        pages: [],


                    }
            }).catch(err => {

            })


        },
        addTextTo(object = {param: null, text: null}) {
            this.botForm[object.param] = object.text;

        },
        pageListCallback(page) {
            this.loadPage = true
            this.page = page
            this.$nextTick(() => {
                this.loadPage = false

            });
        },

        pageCallback(page) {
            this.loadPageList = true
            this.$nextTick(() => {
                this.loadPageList = false
            });
        },
        goToStep(step){
            this.step=step

            this.$nextTick(()=>{
                window.scrollTo(0,630)
                //document.getElementById('user-profile-info').scrollIntoView();
            })
        }
    }
}
</script>
<style lang="scss">
.popper {
    background: #06135f !important;
    color: white !important;
    padding: 10px !important;
    border-radius: 10px !important;
}


.img-preview, .photo-loader {
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 42px;
    background: white;
    border-radius: 10px;
    border: 1px lightgray solid;
    position: relative;
}

.img-preview img, .photo-loader img {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
}

.img-preview .remove {
    display: none;
    position: absolute;
    z-index: 2;

    a {
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }
}

.img-preview:hover .remove {
    display: flex;
}

.fixed-footer {

    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    min-height: 70px;
    z-index: 990;
    padding: 0px;
    box-sizing: border-box;

    border: none;
    background: transparent;

    & .container {
        background: white;
        border: 1px #e3e3e3 solid;
        padding: 10px;
        box-sizing: border-box;
        box-shadow: 0px 0px 2px 0px;
        border-radius: 10px;
    }
}

.bot-sub-menu {
    position: sticky;
    top: 55px;
    background: white;
    z-index: 1000;
}

.bot-footer-menu {
    position: sticky;
    bottom: 10px;
    background: white;
    z-index: 990;
}

.custom-group-dropdown-btn {
    border-radius: 0px 5px 5px 0px !important;
    border-left: none !important;
}
</style>
