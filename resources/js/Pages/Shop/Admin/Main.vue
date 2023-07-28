<script setup>

import UserInfo from '@/Components/UserInfo.vue';
import Pagination from '@/Components/Shop/Helpers/Pagination.vue'
import ReturnToBot from "@/Components/Shop/Helpers/ReturnToBot.vue";
//todo: добавить историю кэшбека, добавить сумму кэшбэка на текущий момент
</script>
<template>


    <div class="card card-style bg-theme pb-0">
        <div class="content ">
            <div class="tab-controls mb-5 tabs-round tab-animated tabs-medium tabs-rounded shadow-xl">
                <a
                    href="#"
                    v-bind:class="{'bg-blue2-dark color-white':tab===0}"
                    @click.prevent="tab=0"
                    style="width: 50%;">
                    <i class="fa fa-heart"></i> Инфо
                </a>

                <a
                    href="#"
                    v-bind:class="{'bg-blue2-dark color-white':tab===1}"
                    @click.prevent="tab=1"
                    style="width: 50%;">
                    <i class="fa fa-star"></i>
                    CashBack
                </a>

            </div>

            <div v-if="tab===0">
                <h3>Информация о пользователе</h3>
                <p>
                    Ваша персональная информация
                </p>
                <UserInfo
                    v-if="botUser"
                    :bot-user="botUser"></UserInfo>
                <ReturnToBot></ReturnToBot>
            </div>

            <div v-if="tab===1">
                <div v-if="cashback.length>0"
                     class="list-group list-boxes w-100">

                    <a
                        @click.prevent="showInfo(item)"
                        v-bind:class="{'border-green2-dark':item.operation_type,'border-red1-dark':!item.operation_type}"
                        href="#"
                        v-for="(item, index) in cashback" class="border  rounded-s shadow-xs">
                        <i class="fa font-20 fa-mobile" v-bind:class="{'color-green2-dark':item.operation_type,'color-red1-dark':!item.operation_type}"></i>
                        <span>{{ item.amount || 0 }} руб. (чек {{ item.money_in_check || 0 }} руб.)</span>
                        <strong>{{ item.employee.fio_from_telegram || 'Не указано' }}</strong>
                        <u class="color-green2-dark" v-if="item.operation_type">Начисление</u>
                        <u class="color-red1-dark" v-else>Списание</u>
                        <i class="fa fa-circle-up color-green2-dark" v-if="item.operation_type"></i>
                        <i class="fa-regular fa-circle-down color-red1-dark" v-else></i>
                    </a>

                </div>
                <p v-else>У пользователя еще нет операций с CashBack-ом</p>

                <Pagination
                    class="mt-2"
                    v-on:pagination_page="nextCashBackPage"
                    v-if="cashback_paginate_object"
                    :pagination="cashback_paginate_object"/>
            </div>
        </div>
    </div>
    <div class="card card-style bg-theme pb-0">
        <div class="content ">
            <a
                href="#"
                @click.prevent="openSection(5)"
                v-bind:class="{'bg-blue2-dark text-white':section===5, 'color-blue2-dark':section!==5}"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark ">Отметить пользователя в заведении</a>

                <form v-on:submit.prevent="acceptUserInLocation" v-if="section===5">
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">Отметить
                        </button>
                    </div>
                </form>

            <a
                href="#"
                @click.prevent="openSection(1)"
                v-bind:class="{'bg-blue2-dark text-white':section===1, 'color-blue2-dark':section!==1}"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark  ">Списать
                CashBack</a>


                <form v-on:submit.prevent="removeCashBack" v-if="section===1">
                    <p>У пользователя <strong>{{ cashBack.amount || 0 }} руб</strong> CashBack</p>
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">Отправить
                        </button>
                    </div>
                </form>



            <a
                href="#"
                @click.prevent="openSection(2)"
                v-bind:class="{'bg-blue2-dark text-white':section===2, 'color-blue2-dark':section!==2}"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark ">Начислить
                CashBack</a>

                <form v-on:submit.prevent="addCashBack" v-if="section===2">
                    <p>У пользователя <strong>{{ cashBack.amount || 0 }} руб</strong> CashBack</p>
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">Отправить
                        </button>
                    </div>
                </form>


            <a
                href="#"
                @click.prevent="openSection(3)"
                v-bind:class="{'bg-blue2-dark text-white':section===3, 'color-blue2-dark':section!==3}"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark ">Добавить
                администратора</a>



                <form v-on:submit.prevent="addAdmin" v-if="section===3">
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">Подтвредить
                        </button>
                    </div>
                </form>



            <a
                href="#"
                @click.prevent="openSection(4)"
                v-bind:class="{'bg-blue2-dark text-white':section===4, 'color-blue2-dark':section!==4}"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark  ">Убрать
                администратора</a>


                <form v-on:submit.prevent="removeAdmin" v-if="section===4">
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">Подтвредить
                        </button>
                    </div>
                </form>

        </div>
    </div>
    <!--
        <div class="container pt-3 pb-3"

             v-if="user&&botUser">
    &lt;!&ndash;        <div class="row mb-2">
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

            </div>&ndash;&gt;
    &lt;!&ndash;        <div class="row mb-2" v-if="tab===0">

                <div class="col-12">
                    <UserInfo :bot-user="botUser"></UserInfo>
                </div>

            </div>&ndash;&gt;

    &lt;!&ndash;        <div class="row mb-2" v-if="tab===1">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <ul v-if="cashback.length>0" class="list-group w-100">
                                <li v-for="(item, index) in cashback" class="list-group-item">
                                    <span>{{ item.money_in_check || 0 }} руб.</span>,
                                    <span>{{ item.description || 'Не указано' }}</span>,
                                    <span>{{ item.operation_type ? 'Начисление' : 'Списание' }}</span>
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

            </div>&ndash;&gt;

            <div class="row">
                <div class="col-12">
                    &lt;!&ndash;                <a @click="scanQR()" class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme">Сканировать QR</a>
                                    <div class="hr"></div>&ndash;&gt;
                    <a @click="openLink(5)"
                       v-bind:class="{'btn-primary text-white':section===5}"
                       class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme">Отметить пользователя в заведении</a>
                    <a @click="openLink(1)"
                       v-bind:class="{'btn-primary text-white':section===1}"
                       class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme">Списать
                        CashBack</a>
                    <a @click="openLink(2)"
                       v-bind:class="{'btn-primary text-white':section===2}"
                       class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme">Начислить
                        CashBack</a>
                    <a @click="openLink(3)"
                       v-bind:class="{'btn-primary text-white':section===3}"
                       class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme">Добавить
                        администратора</a>
                    <a @click="openLink(4)"
                       v-bind:class="{'btn-primary text-white':section===4}"
                       class="btn btn-outline-primary w-100 mb-2">Убрать
                        администратора</a>
                </div>
            </div>

            <div class="row" v-if="section===2">
                <form v-on:submit.prevent="addCashBack">
                    <p>У пользователя <strong>{{ cashBack.amount || 0 }} руб</strong> CashBack</p>
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light">Отправить
                        </button>
                    </div>
                </form>
            </div>

            <div class="row" v-if="section===1">
                <form v-on:submit.prevent="removeCashBack">
                    <p>У пользователя <strong>{{ cashBack.amount || 0 }} руб</strong> CashBack</p>
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light">Отправить
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light">Подтвредить
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light">Подтвредить
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
                            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light">Отметить
                        </button>
                    </div>
                </form>
            </div>

            <div class="row" style="padding-bottom:300px;">

            </div>
        </div>
        <div class="container" v-else>
            <div class="row">
                <div class="alert alert-warning" role="alert">
                    Не найден пользователь!
                </div>
            </div>
        </div>
    -->


</template>
<script>
import baseJS from "@/modules/custom";
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            botUser: null,
            statistic: null,
            loading: false,

            tab: 0,
            section: 0,
            cashback: [],
            referrals: [],

            cashback_paginate_object: null,
            referrals_paginate_object: null,

            locationForm: {
                info: null,
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
        ...mapGetters(['getSelf', 'getCashBack',
            'getCashBackPaginateObject']),

    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf
            this.loadCashBack()

        },
        'tab': function () {
            if (this.tab === 1)
                this.loadCashBack()
        }
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
        }
    },


    methods: {
        showInfo(item) {
            console.log(item)
            this.$cashback.show(item)
        },
        nextCashBackPage(index) {
            this.loadCashBack(index)
        },
        loadCashBack(page = 0) {
            this.$store.dispatch("loadCashBack", {
                dataObject: {
                    telegram_chat_id: this.botUser.telegram_chat_id,
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.cashback = this.getCashBack
                this.cashback_paginate_object = this.getCashBackPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
        acceptUserInLocation() {
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
        scanQR() {
            this.tg.showScanQrPopup({
                text: 'test'
            }, (data) => {
                document.write("<br>" + data);
                this.tg.closeScanQrPopup()
                return true
            })
        },
        openSection(section) {
            console.log("section", section)
            this.section = section
            /*  this.tg.openLink(url, {
                  try_instant_view: true,
              })*/
        }
    }
}
</script>
