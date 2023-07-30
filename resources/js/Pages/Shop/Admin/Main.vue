<script setup>

import UserInfo from '@/Components/UserInfo.vue';
import Pagination from '@/Components/Shop/Helpers/Pagination.vue'
import ReturnToBot from "@/Components/Shop/Helpers/ReturnToBot.vue";
import UserSearchForm from "@/Components/Shop/Users/UserSearchForm.vue";
//todo: добавить историю кэшбека, добавить сумму кэшбэка на текущий момент
</script>
<template>
    <UserSearchForm v-on:select="selectUser" />
    <div v-if="request_telegram_chat_id" id="user-profile-info">
        <div class="card card-style bg-theme pb-0">
            <div class="content mb-0">
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
                            <i class="fa font-20 fa-mobile"
                               v-bind:class="{'color-green2-dark':item.operation_type,'color-red1-dark':!item.operation_type}"></i>
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

            <div class="divider-icon divider-margins bg-blue2-dark my-4"><i class="fa font-17 color-blue2-dark fa-cog bg-white"></i></div>
            <div class="content mt-0">
                <a
                    href="#"
                    @click.prevent="openSection(5)"
                    v-bind:class="{'bg-blue2-dark text-white':section===5, 'color-blue2-dark':section!==5}"
                    class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark ">Отметить
                    пользователя в заведении</a>

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
                            type="submit"
                            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
                            Отметить
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
                    <p>У пользователя <strong>{{ botUser.cashBack.amount || 0 }} руб</strong> CashBack</p>
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
                            type="submit"
                            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
                            Отправить
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
                    <p>У пользователя <strong>{{ botUser.cashBack.amount || 0 }} руб</strong> CashBack</p>
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
                            type="submit"
                            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
                            Отправить
                        </button>
                    </div>
                </form>


                <a
                    href="#"
                    @click.prevent="openSection(3)"
                    v-bind:class="{'bg-blue2-dark text-white':section===3, 'color-blue2-dark':section!==3}"
                    class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark ">
                    Назначить администратором
                </a>


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
                            type="submit"
                            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
                            Подтвредить
                        </button>
                    </div>
                </form>


                <a
                    href="#"
                    @click.prevent="openSection(4)"
                    v-bind:class="{'bg-blue2-dark text-white':section===4, 'color-blue2-dark':section!==4}"
                    class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark  ">
                  Убрать администратора
                </a>


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
                            type="submit"
                            class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
                            Подтвредить
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>


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
            request_telegram_chat_id: null,
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

        if (this.$route.query.user) {
            this.request_telegram_chat_id = this.$route.query.user

            this.loadReceiverUserData()
            this.loadCashBack()
        }
        console.log(this.$route.query.user)
        //?user=$request_telegram_chat_id
        //  if (window.location.)
    },


    methods: {
        selectUser(user){
          this.request_telegram_chat_id = user.telegram_chat_id

            this.loadReceiverUserData()
            this.loadCashBack()

            this.$nextTick(()=>{

                let ele = document.getElementById('user-profile-info');
                window.scrollTo(ele.offsetLeft,ele.offsetTop-70)

                //document.getElementById('user-profile-info').scrollIntoView();
            })
        },
        showInfo(item) {
            this.$cashback.show(item)
        },
        nextCashBackPage(index) {
            this.loadCashBack(index)
        },
        loadReceiverUserData() {
            this.loading = true
            this.$store.dispatch("loadReceiverUserData", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id
                },
            }).then(resp => {
                this.botUser = resp.data
                this.loading = false
            }).catch(() => {
                this.loading = false
            })
        },
        loadCashBack(page = 0) {
            this.$store.dispatch("loadCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id
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
        loadUsers(page = 0) {
            this.$store.dispatch("loadSearchUsers", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id
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
            this.$store.dispatch("acceptUserInLocation", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id,
                    ...this.locationForm
                }
            }).then((resp) => {
                this.loading = false
                this.locationForm.info = null
                this.$botNotification.success("Отлично!", "Вы отметили пользователя в заведении")
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        },
        removeCashBack() {
            if (!this.request_telegram_chat_id) {
                this.$botNotification.warning("Упс!", "Вы должны выбрать пользователя!")
                return
            }
            this.loading = true;
            this.$store.dispatch("removeCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id,
                    ...this.cashbackForm
                }
            }).then((resp) => {
                this.loading = false
                this.cashbackForm.amount = 0
                this.cashbackForm.info = null
                this.loadReceiverUserData()
                this.loadCashBack()
                this.$botNotification.success("Отлично!", "Вы успешно списали кэшбэк")

            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        },
        addCashBack() {
            if (!this.request_telegram_chat_id) {
                this.$botNotification.warning("Упс!", "Вы должны выбрать пользователя!")
                return
            }
            this.loading = true;
            this.$store.dispatch("addCashBack", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id,
                    ...this.cashbackForm
                }
            }).then((resp) => {
                this.loading = false
                this.cashbackForm.amount = 0
                this.cashbackForm.info = null
                this.loadReceiverUserData()
                this.loadCashBack()
                this.$botNotification.success("Отлично!", "Вы успешно зачислили кэшбэк")
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        },
        addAdmin() {
            this.loading = true;
            this.$store.dispatch("addAdmin", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id,
                    ...this.adminForm
                }
            }).then((resp) => {
                this.loading = false
                this.adminForm.info = null
                this.loadReceiverUserData()
                this.$botNotification.success("Отлично!", "Вы успешно назначили пользователя администратором")
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        },
        removeAdmin() {
            this.loading = true;
            this.$store.dispatch("removeAdmin", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id,
                    ...this.adminForm
                }

            }).then((resp) => {
                this.loading = false
                this.adminForm.info = null
                this.loadReceiverUserData()
                this.$botNotification.success("Отлично!", "Вы успешно убрали пользователя из администраторов")
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
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
