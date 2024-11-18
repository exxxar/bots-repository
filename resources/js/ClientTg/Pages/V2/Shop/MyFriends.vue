<script setup>

import Pagination from '@/ClientTg/Components/V1/Pagination.vue'


</script>
<template>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h6 class="opacity-75 my-3" >Ваш текущий баланс</h6>

                <ul class="list-group">
                    <li
                        class="list-group-item d-flex justify-content-between p-3"
                        aria-current="true">
                        <span>Получено баллов</span>
                        <span class="text-primary fw-bold">{{ self.cashBack.amount || 0 }} ₽</span>
                    </li>
                </ul>
            </div>
            <div class="col-12">
                <template v-if="self.cashBack">
                    <h6 class="opacity-75 my-3" v-if="(self.cashBack.subs||[]).length>0">Специальные начисления</h6>

                    <ul class="list-group" v-if="(self.cashBack.subs||[]).length>0">
                        <li class="list-group-item d-flex justify-content-between p-3"
                            v-for="sub in self.cashBack.subs"
                            aria-current="true">
                            <span>{{ sub.title || '-' }}</span>
                            <span class="text-primary fw-bold">{{ sub.amount || 0 }} ₽</span>
                        </li>
                    </ul>
                </template>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="my-3"><i class="fa-solid fa-list-check mr-1 text-primary"></i> История операция</h5>
            </div>
            <div class="col-12">
                <ul class="list-group" v-if="cashback.length>0">
                    <li
                        v-for="(item, index) in cashback"
                        @click="open(item)"

                        class="list-group-item d-flex flex-column">

                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <i class="fa fa-circle-up text-success mr-2" style="font-size:24px;" v-if="item.operation_type"></i>
                                <i class="fa-regular fa-circle-down text-danger mr-2" style="font-size:24px;"  v-else></i>

                                <span class="fw-bold">{{ item.amount || 0 }} руб. </span>
                            </div>


                            <span class="text-primary fw-bold">чек {{ item.money_in_check || 0 }} руб.</span>
                        </div>

                        <div class="w-100 mt-2" v-if="item.is_open">
                            <table class="table table-borderless  rounded-sm shadow-l m-0  p-0"  style="overflow: hidden;">
                                <thead>
                                <tr class="bg-gray1-dark">
                                    <th scope="col" class="color-theme">Параметр</th>
                                    <th scope="col" class="color-theme">Значение</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">Тип операции</th>
                                    <td class="fw-bold text-primary">{{item.operation_type ? 'Начисление':'Списание'}}</td>

                                </tr>
                                <tr>
                                    <th scope="row">Сумма баллов, руб</th>
                                    <td class="fw-bold text-primary">{{item.amount || 0}}</td>

                                </tr>

                                <tr v-if="item.operation_type">
                                    <th scope="row">Уровень начисления</th>
                                    <td class="fw-bold text-primary">{{item.level || 0}}</td>

                                </tr>
                                <tr v-if="item.operation_type">
                                    <th scope="row">Сумма в чеке, руб</th>
                                    <td class="fw-bold text-primary">{{item.money_in_check || 0}}</td>

                                </tr>
                                <tr>
                                    <th scope="row">Дата операции</th>
                                    <td class="fw-bold text-primary">{{$filters.current(item.created_at)}}</td>

                                </tr>
                                <tr>
                                    <th scope="row">Описание операции</th>
                                    <td class="fw-bold text-primary">{{item.description || 'Нет описания'}}</td>

                                </tr>

                                <tr>
                                    <th scope="row">TG id сотрудника</th>
                                    <td class="fw-bold text-primary">{{item.employee.telegram_chat_id || 'Не указано'}}</td>

                                </tr>

                                <tr>
                                    <th scope="row">Имя сотрудника</th>
                                    <td class="fw-bold text-primary">{{item.employee.fio_from_telegram || 'Не указано'}}</td>

                                </tr>

                                <tr>
                                    <th scope="row">Телефон сотрудника</th>
                                    <td class="fw-bold text-primary">{{item.employee.phone || 'Не указано'}}</td>

                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </li>

                </ul>

                <div v-else class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <i class="fa-brands fa-bitcoin mb-3" style="font-size:36px;"></i>

                        <p>Операций с баллами еще нет:(</p>
                    </div>
                </div>
                <Pagination

                    v-on:pagination_page="nextCashBackPage"
                    v-if="cashback_paginate_object"
                    :pagination="cashback_paginate_object"/>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            friends: [],
            cashback_paginate_object: null,
        }
    },
    watch:{
        'self': {
            handler: function (newValue) {
                this.loadCashBack()
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'getCashBack',
            'getCashBackPaginateObject']),
        self() {
            return this.getSelf
        },
        tg() {
            return window.Telegram.WebApp;
        },

    },
    mounted() {
        if (this.self)
            this.loadCashBack()

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        open(item){
            item.is_open = !(item.is_open || false)

        },
        nextCashBackPage(index) {
            this.loadCashBack(index)
        },
        loadCashBack(page = 0) {
            this.$store.dispatch("loadFriends", {
                dataObject: {
                    bot_user_id: this.self.id
                },
                page: page
            }).then(resp => {

                this.friends = this.getCashBack
                this.cashback_paginate_object = this.getCashBackPaginateObject


            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
