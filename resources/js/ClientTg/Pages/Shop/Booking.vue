<script setup>

import Pagination from 'ClientTg@/Components/Pagination.vue'
import CallbackForm from "ClientTg@/Components/Shop/CallbackForm.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style">
        <div class="content">

            <p class="mb-0">
                Вы можете выбрать администратора из списка активных администраторов
                и прислать ему запрос <span v-if="type===0">на начисление <strong>CashBack</strong>.</span>
                <span v-if="type===1">на бронирование столика. Обязательно укажите свой <b>номер телефона</b> для обратной связи.</span>
                К запросу Вы можете прикрепить текстовое сообщение, которое также получит выбранный администратор.
            </p>

            <div class="row mb-0 px-3">
                <div class="col-6 p-1">
                    <button
                        type="button"
                        @click="type=0"
                        class="btn btn-border btn-m btn-full  rounded-sm text-uppercase font-900 w-100"
                        v-bind:class="{'bg-highlight':type===0,'border-highlight color-highlight bg-theme':type!==0}"
                    >
                        Начислить CashBack
                    </button>
                </div>
                <div class="col-6 p-1">
                    <button
                        type="button"
                        @click="type=1"
                        class="btn btn-border btn-m btn-full  rounded-sm text-uppercase font-900 w-100"
                        v-bind:class="{'bg-highlight':type===1,'border-highlight color-highlight bg-theme':type!==1}"
                    >
                        Забронировать столик
                    </button>
                </div>
            </div>


        </div>
    </div>

    <CallbackForm :type="'booking'" v-if="type===1"/>

    <div class="card card-style" v-if="type===0&&admins.length>0">
        <div class="content mb-2">
            <h3>Список администраторов</h3>
            <p>
                Список всех администраторов системы с их статусами и последним временем онлайн
            </p>

            <div class="list-group list-boxes">
                <a href="#"
                   @click.prevent="sendRequest(item, index)"
                   v-for="(item, index) in admins"
                   v-bind:class="{'border-green1-dark':item.is_work,'border-red1-dark':!item.is_work}"
                   class="border  rounded-s shadow-xs">
                    <i class="fa font-20 fa-mobile color-blue2-dark"></i>
                    <span>{{ item.user.fio_from_telegram || item.user.name || 'Не указано' }} ({{
                            $filters.timeAgo(item.updated_at)
                        }})</span>
                    <strong>{{ item.phone || 'Номер телефона не указан' }}</strong>
                    <u class="color-green1-dark" v-if="item.is_work">В сети</u>
                    <u class="color-red2-light" v-else>Не в сети</u>
                    <i class="fa fa-check-circle color-green1-dark" v-if="item.is_work"></i>
                    <i class="fa fa-times-circle color-red2-light" v-else></i>
                </a>

            </div>

            <Pagination
                class="mt-2"
                v-on:pagination_page="nextAdminPage"
                v-if="admins_paginate_object"
                :pagination="admins_paginate_object"/>

            <ReturnToBot class="mb-2"/>
        </div>
    </div>

    <div class="card card-style bg-28"
         v-if="type===0&&admins.length===0"
         data-card-height="130" style="height: 130px;">
        <div class="card-center">
            <h3 class="color-white font-700 text-center mb-0">Список администраторов</h3>
            <p class="color-white text-center opacity-60 mt-n1 mb-0">К сожалению, администраторы не найдены:(</p>
        </div>
        <div class="card-overlay bg-highlight opacity-90"></div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            admins: [],
            admins_paginate_object: null,
            type: 0,

        }
    },
    computed: {
        ...mapGetters(['getAdmins',
            'getAdminsPaginateObject']),
        self() {
            return window.self
        },
        currentBot() {
            return window.currentBot
        }
    },
    mounted() {
        this.loadAdmins()
    },
    methods: {
        nextAdminPage(index) {
            this.loadAdmins(index)
        },
        sendRequest(admin, index) {

            if (!admin.is_work){
                this.$botNotification.warning("Упс!", "Администратор офлайн!")
                return;
            }

            this.$store.dispatch("requestAdmin", {
                dataObject: {
                    bot_id: this.currentBot.id,
                    admin_telegram_chat_id: admin.telegram_chat_id,
                    user_telegram_chat_id: this.self.telegram_chat_id,
                },
            }).then(resp => {
                this.$botNotification.success("Отлично!", "Выбранный Администратор оповещен!")

            }).catch(() => {

            })
        },
        loadAdmins(page = 0) {
            this.$store.dispatch("loadAdmins", {
                dataObject: {
                    bot_domain: this.currentBot.bot_domain
                },
                page: page
            }).then(resp => {
                this.admins = this.getAdmins
                this.admins_paginate_object = this.getAdminsPaginateObject
            }).catch(() => {

            })
        },
    }
}
</script>
