<script setup>

import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";

</script>
<template>
    <div class="card card-style bg-theme pb-0">
        <div class="content">
            <p class="mb-0">Введите номер телефона (или фрагмент) пользователя или его Ф.И.О.</p>

            <form v-on:submit.prevent="loadUsers(0)">
                <div class="search-box bg-theme rounded-s shadow-xl bottom-0">
                    <i class="fa fa-search"></i>
                    <input type="text"
                           v-model="search"
                           class="border-0" placeholder="Кого ищем?">
                </div>

                <div class="d-flex w-100" style="height:70px; overflow-x: scroll;min-width: 100%;">
                    <div class="scroll d-flex" style="min-width: 1000px;">
                        <div class="fac fac-checkbox  py-2 my-2">
                            <span></span>
                            <input id="need-admins-checkbox"
                                   v-model="need_admins"
                                   value="false"
                                   type="checkbox">
                            <label for="need-admins-checkbox">Только администраторы</label>
                        </div>

                        <div class="fac fac-checkbox  py-2 my-2">
                            <span></span>
                            <input id="need-with-phone-checkbox"
                                   v-model="need_with_phone"
                                   value="false"
                                   type="checkbox">
                            <label for="need-with-phone-checkbox">Только с телефоном</label>
                        </div>

                        <div class="fac fac-checkbox  py-2 my-2">
                            <span></span>
                            <input id="need-deliveryman"
                                   v-model="need_deliveryman"
                                   value="false"
                                   type="checkbox">
                            <label for="need-deliveryman">Только доставщики</label>
                        </div>

                        <div class="fac fac-checkbox  py-2 my-2">
                            <span></span>
                            <input id="need-without-phone-checkbox"
                                   v-model="need_without_phone"
                                   value="false"
                                   type="checkbox">
                            <label for="need-without-phone-checkbox">Только без телефоном</label>
                        </div>

                        <div class="fac fac-checkbox  py-2 my-2">
                            <span></span>
                            <input id="need-vip-checkbox"
                                   v-model="need_vip"
                                   value="false"
                                   type="checkbox">
                            <label for="need-vip-checkbox">Только вип</label>
                        </div>

                        <div class="fac fac-checkbox  py-2 my-2">
                            <span></span>
                            <input id="need-not-vip-checkbox"
                                   v-model="need_not_vip"
                                   value="false"
                                   type="checkbox">
                            <label for="need-not-vip-checkbox">Только не вип</label>
                        </div>


                    </div>

                </div>




                <button type="submit"
                        class="btn btn-m btn-full my-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>Искать
                </button>
            </form>

            <div class="card card-style mx-0 mt-3 pt-2 pb-0 mb-2" v-if="users">
                <small class="w-100 text-center p-2 mb-0">Найдено {{ users_paginate_object.meta.total }}</small>
                <div class="list-group list-custom-large">
                    <a
                        href="javascript:void(0)"
                        @click="selectUser(item)"
                        v-for="(item, index) in users">
                        <i class="fa-solid fa-a color-red2-dark" v-if="item.is_admin"></i>
                        <i class="fa-solid fa-u color-green2-dark" v-else></i>
                        <span>{{ item.name || item.fio_from_telegram || 'Не указано' }}</span>
                        <strong>{{ item.phone || 'Телефон не указан' }}</strong>
                        <i class="fa fa-angle-right mr-2"></i>
                    </a>

                </div>


                <Pagination
                    :simple="true"
                    class="mt-4"
                    v-on:pagination_page="nextUsers"
                    v-if="users_paginate_object"
                    :pagination="users_paginate_object"/>
            </div>
            <a href="javascript:void(0)"
               @click="downloadBotUsers"
               class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue1-dark color-blue1-dark bg-theme">
                <i class="fa-regular fa-file-excel mr-2"></i> Скачать список пользователей
            </a>

            <a href="javascript:void(0)"
               @click="downloadCashBackHistory"
               class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue1-dark color-blue1-dark bg-theme">
                <i class="fa-regular fa-file-excel mr-2"></i> Скачать историю CashBack
            </a>

            <ReturnToBot class="mt-3"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            loading: true,
            users: null,
            search: null,
            need_admins: false,
            need_vip: false,
            need_not_vip: false,
            need_with_phone: false,
            need_without_phone: false,
            need_deliveryman: false,
            users_paginate_object: null,

        }
    },
    computed: {
        ...mapGetters(['getUsers',
            'getUsersPaginateObject']),

    },
    mounted() {
        this.loadUsers(0)
    },
    methods: {
        downloadBotUsers(){
            this.$botNotification.notification("Внимание!", "Начался формироваться документ статистики!");
            this.$store.dispatch("downloadBotUsers").then((resp) => {
                //saveAs(resp.data, 'users.xlsx');

                this.$botNotification.success("Отлично!", "Документ успешно сформирован");

            }).catch(() => {
                this.$botNotification.warning("Упс...", "Что-то пошло не так...");
            })
        },
        downloadCashBackHistory(){
            this.$botNotification.notification("Внимание!", "Начался формироваться документ статистики!");
            this.$store.dispatch("downloadCashBackHistory").then((resp) => {
              //  saveAs(resp.data, 'cashback.xlsx');

                this.$botNotification.success("Отлично!", "Документ успешно сформирован");

            }).catch(() => {
                this.$botNotification.warning("Упс...", "Что-то пошло не так...");
            })
        },
        nextUsers(index) {
            this.loadUsers(index)
        },
        selectUser(item) {
            this.$emit("select", item)
        },
        loadUsers(page = 0) {
            this.loading = true
            this.$store.dispatch("loadUsers", {
                dataObject: {
                    search: this.search,
                    need_admins: this.need_admins,
                    need_vip:  this.need_vip,
                    need_not_vip:  this.need_not_vip,
                    need_deliveryman:  this.need_deliveryman,
                    need_with_phone:  this.need_with_phone,
                    need_without_phone:  this.need_without_phone,

                },
                page: page
            }).then(resp => {
                this.loading = false
                this.users = this.getUsers
                this.users_paginate_object = this.getUsersPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
