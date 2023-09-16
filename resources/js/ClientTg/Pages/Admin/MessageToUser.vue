<script setup>

import UserInfo from '@/ClientTg/Components/UserInfo.vue';
import Pagination from '@/ClientTg/Components/Pagination.vue'
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
import UserSearchForm from "@/ClientTg/Components/Shop/Users/UserSearchForm.vue";
//todo: добавить историю кэшбека, добавить сумму кэшбэка на текущий момент
</script>
<template>
    <div v-if="request_telegram_chat_id" id="user-profile-info">
        <div class="card card-style bg-theme pb-0">
            <div class="content mb-0">

                <UserInfo
                    v-if="botUser"
                    :bot-user="botUser"></UserInfo>
                <ReturnToBot class="mb-3"></ReturnToBot>

            </div>
        </div>

        <div class="card card-style" v-if="action">
            <div class="content ">
                <h6 class="text-center">Личная накопительная карта пользвателя #{{action.slug_id}}</h6>
                <p class="mb-0 text-center font-weight-bold">{{action.slug.command || 'Не указана'}}</p>
                <p class="mb-0 text-center font-weight-bold">
                    <span v-if="reloadable"><i class="fa-solid fa-rotate mr-1 color-green2-light"></i>После обмена перезагружается по новой</span>
                    <span v-else><i class="fa-solid fa-triangle-exclamation  mr-1 color-red1-light"></i>Единоразовая акция</span>
                </p>
                <p class="mb-0">{{action.slug.comment || 'Не указан'}}</p>

                <ul class="d-flex justify-content-around flex-wrap save-up">
                    <li v-bind:class="{'active': n <= action.current_attempts}"
                        @click="n <= action.current_attempts ? completeClick() : requestClick()"
                        v-for="n in (action.max_attempts || 10)">
                        <span v-if="n <= action.current_attempts" v-html="icon"
                              v-bind:style="{'color':icon_color}"></span>
                        <span v-else><i class="fa-solid fa-question"></i></span>
                    </li>

                </ul>
                <button type="button"
                        v-if="!need_show_form"
                        @click.prevent="need_show_form = true"
                        :disabled="action.current_attempts!==action.max_attempts"
                        class="btn btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-green2-light w-100 mb-1">
                    Обменять бонусы
                </button>

                <form v-on:submit.prevent="exchange" v-if="need_show_form">
                    <div class="mb-3">
                        <label for="user-name" class="form-label">Запросите имя пользователя</label>
                        <input type="text"
                               class="form-control"
                               id="user-name"
                               v-model="userForm.name"
                               placeholder="Имя пользователя" required>
                    </div>

                    <div class="mb-3">
                        <label for="user-phone" class="form-label">Запросите телефон пользователя</label>
                        <input type="text"
                               class="form-control"
                               id="user-phone"
                               v-mask="'+7(###)###-##-##'"
                               v-model="userForm.phone"
                               placeholder="Номер телефона пользователя" required>
                    </div>

                    <div class="mb-1">
                        <button
                            type="submit"
                            class="btn btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                            Обменять бонусы
                        </button>
                    </div>
                </form>

                <ReturnToBot></ReturnToBot>
            </div>
        </div>
    </div>


</template>
<script>
//import baseJS from "./modules/custom.js";
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            action: null,
            botUser: null,
            loading: false,
            icon: null,
            icon_color: null,
            reloadable: false,
            request_telegram_chat_id: null,
            need_show_form: false,
            userForm: {
                name: null,
                phone: null,
            }

        }
    },
    computed: {
        ...mapGetters(['getSelf', 'getCashBack',
            'getCashBackPaginateObject']),
        currentBot() {
            return window.currentBot
        }

    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

        },
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
        }


        const urlParams = new URLSearchParams(window.location.search);
        const user = JSON.parse(urlParams.get('user'));

        if (user) {
            this.request_telegram_chat_id = user

            this.loadActionData()

        }


        //?user=$request_telegram_chat_id
        //  if (window.location.)
    },


    methods: {
        requestClick() {
            if (this.loading) {
                this.$botNotification.warning("Упс!", "Вы уже выполняете это действие")
                return
            }

            this.loading = true
            this.$store.dispatch("bonusProductCheck", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id
                },
            }).then(resp => {
                this.loading = false
                this.loadActionData()
                this.$botNotification.success("Отлично!", "Вы успешно отметили бонус пользователю")
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так!")
            })
        },
        exchange() {
            this.loading = true
            this.$store.dispatch("bonusProductExchange", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id,
                    ...this.userForm
                },
            }).then(resp => {
                this.loading = false
                this.need_show_form = false
                this.loadActionData()
                this.$botNotification.success("Отлично!", "Вы успешно обменяли бонусы пользователя на приз!")
            }).catch(() => {
                this.loading = false
                this.need_show_form = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так!")
            })
        },
        completeClick() {
            this.$botNotification.success("Отлично!", "В этой ячейке уже есть отметка о бонусе:)")
        },

        loadActionData() {
            this.loading = true
            this.$store.dispatch("loadActionData", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id
                },
            }).then(resp => {
                this.action = resp.action

                this.icon = resp.icon
                this.icon_color = resp.icon_color
                this.reloadable = resp.reloadable

                this.botUser = resp.action.bot_user

                this.userForm.name = this.botUser.name || this.botUser.fio_from_telegram || null
                this.userForm.phone = this.botUser.phone || null

                this.loading = false
            }).catch(() => {
                this.loading = false
            })
        },

    }
}
</script>
