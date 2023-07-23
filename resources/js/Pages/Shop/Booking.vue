<script setup>

import Pagination from '@/Components/Shop/Helpers/Pagination.vue'


</script>
<template>
    <div class="card card-style" >
        <div class="content">

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card border-success  ">
                            <div class="card-body">
                                <p>
                                    Вы можете выбрать администратора из списка активных администраторов
                                    и прислать ему запрос <span v-if="type==0">на начисление <strong>CashBack</strong>.</span>
                                    <span v-if="type==1">на бронирование столика. Обязательно укажите свой <b>номер телефона</b> для обратной связи.</span>
                                    К запросу вы можете прикрепить текстовое сообщение, которое также получит выбранный администратор.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div  >
                            <div class="btn-group mb-3" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio"
                                       v-model="type"
                                       class="btn-check"
                                       value="0"
                                       name="btnradio"
                                       id="btnradio1" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="btnradio1">Начислить CashBack</label>

                                <input type="radio"
                                       class="btn-check"
                                       v-model="type"
                                       value="1"
                                       name="btnradio"
                                       id="btnradio2" autocomplete="off">
                                <label class="btn btn-outline-primary" for="btnradio2">Забронировать столик</label>
                            </div>

                            <div class="input-group mb-3" v-if="type==1">
                                <span class="input-group-text" id="booking-phone">Телефон</span>
                                <input type="text" class="form-control"
                                       v-mask="'+7(###)###-##-##'"
                                       v-model="phone"
                                       placeholder="+7(000)000-00-00"
                                       aria-label="vipForm-phone" aria-describedby="booking-phone">
                            </div>

                            <textarea type="text"
                                      v-model="message"
                                      placeholder="Сообщение администратору"
                                      class="form-control w-100  mb-3"/>

                        </div>
                        <div
                            v-if="admins.length>0"
                            class="list-group">
                            <a href="#"
                               @click="sendRequest(item, index)"
                               :key="'admin'+index"
                               v-for="(item, index) in admins"
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{item.user.fio_from_telegram||item.user.name || 'Не указано'}}</h5>
                                    <small class="text-muted">{{ $filters.timeAgo(item.updated_at) }}</small>
                                </div>
                                <p class="mb-1">+{{item.user.phone||'Номер телефона не указан'}}</p>
                                <small class="text-muted">
                                    <span class="badge text-bg-primary">Администратор</span>
                                </small>

                            </a>

                        </div>
                        <div
                            v-else
                            class="alert alert-warning" role="alert">
                            К сожалению активных администраторов на данный момент нет, как только они будут в сети вы сможете запросить у них начисление бонусных баллов!
                        </div>
                    </div>
                    <div class="col-12" v-if="admins.length>0">
                        <Pagination
                            class="mt-2"
                            v-on:pagination_page="nextAdminPage"
                            v-if="admins_paginate_object"
                            :pagination="admins_paginate_object"/>
                    </div>
                </div>

        </div>
    </div>




</template>
<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {

            loading: false,
            admins:[],
            admins_paginate_object: null,
            message:null,
            type:0,
            phone:null

        }
    },
    computed: {
        ...mapGetters(['getAdmins',
            'getAdminsPaginateObject']),
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        },
        currentBot(){
            return window.currentBot
        }
    },
    mounted() {
        this.loadAdmins()
    },
    methods: {
        nextAdminPage(index){
            this.loadAdmins(index)
        },
        sendRequest(botUser, index){
            this.loading = true
            this.$store.dispatch("requestAdmin", {
                dataObject: {
                    bot_id: this.currentBot.id,
                    admin_telegram_chat_id: botUser.telegram_chat_id,
                    user_telegram_chat_id: this.tgUser.id,
                    message:this.message,
                    type: this.type,
                    phone: this.phone
                },
            }).then(resp=> {
                this.loading = false
                this.tg.close()
            }).catch(() => {
                this.loading = false
            })
            // this.tg.close()
        },
        loadAdmins(page = 0){
            this.loading = true
            this.$store.dispatch("loadAdmins", {
                dataObject: {
                    bot_domain: this.currentBot.bot_domain
                },
                page:page
            }).then(resp=> {
                this.loading = false
                this.admins = this.getAdmins
                this.admins_paginate_object = this.getAdminsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
