<script setup>

import UserInfo from '@/Components/UserInfo.vue';
import Pagination from '@/Components/Pagination.vue';
//todo: добавить историю кэшбека, добавить сумму кэшбэка на текущий момент
defineProps({
    user: {
        type: Object,
    },
    botUser: {
        type: Object
    },
    cashBack: {
        type: Object
    }

});
</script>
<template>
    <div class="container" v-if="user&&botUser">
        <div class="row mb-2">
            <div class="col-12 d-flex justify-content-center mb-2">

                <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                    <button type="button"
                            v-bind:class="{'active':tab===0}"
                            @click="tab=0"
                            class="btn btn-outline-primary">Инфо
                    </button>
                    <button type="button"
                            v-bind:class="{'active':tab===1}"
                            @click="tab=1"
                            class="btn btn-outline-primary">
                        CashBack
                    </button>
                </div>


            </div>

        </div>
        <div class="row mb-2" v-if="tab===0">

          <div class="col-12">
              <UserInfo :bot-user="botUser"></UserInfo>
          </div>

        </div>

        <div class="row mb-2" v-if="tab===1">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <ul v-if="cashback.length>0" class="list-group w-100">
                            <li v-for="(item, index) in cashback" class="list-group-item">
                                <span>{{item.money_in_check||0}} руб.</span>,
                                <span>{{item.description||'Не указано'}}</span>,
                                <span>{{item.operation_type?'Начисление':'Списание'}}</span>
                            </li>

                        </ul>
                        <p v-else>У пользователя еще нет операций с CashBack-ом</p>

                        <Pagination
                            class="mt-2"
                            v-on:pagination_page="nextCashBackPage"
                            v-if="cashback_paginate_object"
                            :pagination="cashback_paginate_object"/>
                    </div>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-12">
<!--                <a @click="scanQR()" class="btn btn-outline-primary w-100 mb-2 ">Сканировать QR</a>
                <div class="hr"></div>-->
                <a @click="openLink(5)"
                   v-bind:class="{'btn-primary text-white':section===5}"
                   class="btn btn-outline-primary w-100 mb-2 ">Отметить пользователя в заведении</a>
                <a @click="openLink(1)"
                   v-bind:class="{'btn-primary text-white':section===1}"
                   class="btn btn-outline-primary w-100 mb-2 ">Списать
                    CashBack</a>
                <a @click="openLink(2)"
                   v-bind:class="{'btn-primary text-white':section===2}"
                   class="btn btn-outline-primary w-100 mb-2 ">Начислить
                    CashBack</a>
                <a @click="openLink(3)"
                   v-bind:class="{'btn-primary text-white':section===3}"
                   class="btn btn-outline-primary w-100 mb-2 ">Добавить
                    администратора</a>
                <a @click="openLink(4)"
                   v-bind:class="{'btn-primary text-white':section===4}"
                   class="btn btn-outline-primary w-100 mb-2">Убрать
                    администратора</a>
            </div>
        </div>

        <div class="row" v-if="section===2">
            <form v-on:submit.prevent="addCashBack">
                <p>У пользователя <strong>{{cashBack.amount||0}} руб</strong> CashBack</p>
                <div class="mb-3">
                    <label for="bill-amount" class="form-label">Сумма в чеке, руб</label>
                    <input type="number" min="0" class="form-control"
                           id="bill-amount"
                           v-model="cashbackForm.amount"
                           placeholder="Сумма" required>
                </div>

                <div class="mb-3">
                    <label for="bill-info" class="form-label">Информация о чеке, номер</label>
                    <textarea class="form-control"
                              placeholder="Информация"
                              v-model="cashbackForm.info"
                              id="bill-info" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <button
                        :disabled="loading"
                        type="submit" class="btn btn-outline-success w-100">Отправить
                    </button>
                </div>
            </form>
        </div>

        <div class="row" v-if="section===1">
            <form v-on:submit.prevent="removeCashBack">
                <p>У пользователя <strong>{{cashBack.amount||0}} руб</strong> CashBack</p>
                <div class="mb-3">
                    <label for="bill-amount" class="form-label">Сумма списания CashBack, руб</label>
                    <input type="number" min="0" class="form-control"
                           id="bill-amount"
                           v-model="cashbackForm.amount"
                           placeholder="Сумма" required>
                </div>

                <div class="mb-3">
                    <label for="bill-info" class="form-label">Причина списания</label>
                    <textarea class="form-control"
                              placeholder="Информация"
                              v-model="cashbackForm.info"
                              id="bill-info" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <button
                        :disabled="loading"
                        type="submit" class="btn btn-outline-success w-100">Отправить
                    </button>
                </div>
            </form>
        </div>

        <div class="row" v-if="section===3">
            <form v-on:submit.prevent="addAdmin">
                <div class="mb-3">
                    <label for="bill-info" class="form-label">Причина добавления администратора</label>
                    <textarea class="form-control"
                              placeholder="Информация"
                              v-model="adminForm.info"
                              id="bill-info" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <button
                        :disabled="loading"
                        type="submit" class="btn btn-outline-success w-100">Подтвредить
                    </button>
                </div>
            </form>
        </div>

        <div class="row" v-if="section===4">
            <form v-on:submit.prevent="removeAdmin">
                <div class="mb-3">
                    <label for="bill-info" class="form-label">Причина разжалования администратора</label>
                    <textarea class="form-control"
                              placeholder="Информация"
                              v-model="adminForm.info"
                              id="bill-info" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <button
                        :disabled="loading"
                        type="submit" class="btn btn-outline-success w-100">Подтвредить
                    </button>
                </div>
            </form>
        </div>

        <div class="row" v-if="section===5">
            <form v-on:submit.prevent="acceptUserInLocation">
                <div class="mb-3">
                    <label for="bill-info" class="form-label">Комменатрий</label>
                    <textarea class="form-control"
                              placeholder="Информация"
                              v-model="locationForm.info"
                              id="bill-info" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <button
                        :disabled="loading"
                        type="submit" class="btn btn-outline-success w-100">Отметить
                    </button>
                </div>
            </form>
        </div>

    </div>
    <div class="container" v-else>
        <div class="row">
            <div class="alert alert-warning" role="alert">
                Не найден пользователь!
            </div>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {
            tab: 0,
            loading: false,
            section: 0,
            cashback:[],
            referrals:[],
            cashback_paginate_object: null,
            referrals_paginate_object: null,
            locationForm:{
                info:null,
            },
            adminForm: {
                info: null
            },
            cashbackForm: {
                amount: null,
                info: null
            }

        }
    },
    computed: {
        ...mapGetters(['getCashBack',
            'getCashBackPaginateObject']),
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {
            this.loadCashBack()
    },
    methods: {

        nextCashBackPage(index){
            this.loadCashBack(index)
        },
        loadCashBack(page = 0){
            this.$store.dispatch("loadCashBack", {
                dataObject: {
                    user_id: this.botUser.user_id,
                    bot_id: this.botUser.bot_id
                },
                page:page
            }).then(resp=> {
                this.loading = false
                this.cashback = this.getCashBack
                this.cashback_paginate_object = this.getCashBackPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
        acceptUserInLocation(){
            this.loading = true;
            //this.$store.dispa
            this.$store.dispatch("acceptUserInLocation", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    bot_id: this.botUser.bot_id,
                    info: this.locationForm.info,
                    tg: this.tgUser
                }
            }).then((resp) => {
                this.loading = false
                this.locationForm.info = null
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },
        removeCashBack() {
            this.loading = true;
            //this.$store.dispa
            this.$store.dispatch("removeCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    bot_id: this.botUser.bot_id,
                    cashback: this.cashbackForm.amount,
                    info: this.cashbackForm.info,
                    tg: this.tgUser
                }
            }).then((resp) => {
                this.loading = false
                this.cashbackForm.amount = 0
                this.cashbackForm.info = null
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },
        addCashBack() {
            this.loading = true;
            this.$store.dispatch("addCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    bot_id: this.botUser.bot_id,
                    cashback: this.cashbackForm.amount,
                    info: this.cashbackForm.info,
                    tg: this.tgUser
                }
            }).then((resp) => {
                this.loading = false
                this.cashbackForm.amount = 0
                this.cashbackForm.info = null
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },
        addAdmin() {
            this.loading = true;
            this.$store.dispatch("addAdmin", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    bot_id: this.botUser.bot_id,
                    info: this.adminForm.info,
                    tg: this.tgUser
                }
            }).then((resp) => {
                this.loading = false
                this.adminForm.info = null
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },
        removeAdmin() {
            this.loading = true;
            this.$store.dispatch("removeAdmin", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    bot_id: this.botUser.bot_id,
                    info: this.adminForm.info,
                    tg:this.tgUser
                }

            }).then((resp) => {
                this.loading = false
                this.adminForm.info = null
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },
        scanQR() {
            this.tg.showScanQrPopup({
                text: 'test'
            }, (data) => {
                document.write("<br>" + data);
                this.tg.closeScanQrPopup()
                return true
            })
        },
        openLink(section) {
            this.section = section
            /*  this.tg.openLink(url, {
                  try_instant_view: true,
              })*/
        }
    }
}
</script>
