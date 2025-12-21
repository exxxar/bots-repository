<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>
    <p class="my-3">Введите номер телефона (или фрагмент) пользователя или его Ф.И.О.</p>

    <form v-on:submit.prevent="loadUsers(0)">

        <div class="form-floating mb-3">
            <input type="text"
                   v-model="search"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Кого ищем?</label>
        </div>


        <div class="d-flex w-100" style="overflow-x: scroll;">
            <div class="scroll d-flex px-2" style="min-width: 1300px;">

                <div class="form-check form-switch mx-2">
                    <input class="form-check-input"
                           v-model="need_admins"
                           value="false"
                           type="checkbox" role="switch" id="need-admins-checkbox">
                    <label class="form-check-label" for="need-admins-checkbox">Только администраторы</label>
                </div>

                <div class="form-check form-switch mx-2">
                    <input class="form-check-input"
                           v-model="need_with_phone"
                           value="false"
                           type="checkbox" role="switch" id="need-with-phone-checkbox">
                    <label class="form-check-label" for="need-with-phone-checkbox">Только с телефоном</label>
                </div>

                <div class="form-check form-switch mx-2">
                    <input class="form-check-input"
                           v-model="need_deliveryman"
                           value="false"
                           type="checkbox" role="switch" id="need-deliveryman">
                    <label class="form-check-label" for="need-deliveryman">Только доставщики</label>
                </div>

                <div class="form-check form-switch mx-2">
                    <input class="form-check-input"
                           v-model="need_without_phone"
                           value="false"
                           type="checkbox" role="switch" id="need-without-phone-checkbox">
                    <label class="form-check-label" for="need-without-phone-checkbox">Только без телефоном</label>
                </div>

                <div class="form-check form-switch mx-2">
                    <input class="form-check-input"
                           v-model="need_vip"
                           value="false"
                           type="checkbox" role="switch" id="need-vip-checkbox">
                    <label class="form-check-label" for="need-vip-checkbox">Только VIP</label>
                </div>

                <div class="form-check form-switch mx-2">
                    <input class="form-check-input"
                           v-model="need_not_vip"
                           value="false"
                           type="checkbox" role="switch" id="need-not-vip-checkbox">
                    <label class="form-check-label" for="need-not-vip-checkbox">Только не VIP</label>
                </div>


            </div>

        </div>


        <button type="submit"
                class="btn btn-primary w-100 p-3 my-3">
            <i class="fa-solid fa-magnifying-glass mr-2"></i>Искать
        </button>
    </form>

    <template v-if="users&&!loading">
        <p class="w-100 text-center mb-2 small">Найдено пользователей
            <span class="text-primary fw-bold"> {{ users_paginate_object.meta.total }} </span>
        </p>
        <ul class="list-group">
            <li
                @click="selectUser(item)"
                v-for="(item, index) in users"
                v-bind:class="{'bg-primary text-white':item.id == (selectedBotUser||{id:null}).id}"
                class="list-group-item d-flex justify-content-between">
                <div>
                    <i class="fa-solid fa-a text-danger mr-2" v-if="item.is_admin"></i>
                    <i class="fa-solid fa-u text-success mr-2" v-else></i>
                    <span
                        v-if="(item.name || item.fio_from_telegram || 'Не указано').length>26"
                        style="font-size:12px;">{{ (item.name || item.fio_from_telegram || 'Не указано').substring(0,26) }}...</span>
                    <span
                        style="font-size:12px;"
                        v-else>{{ item.name || item.fio_from_telegram || 'Не указано' }}</span>
                </div>

                <strong style="font-size:12px;">{{ item.phone || 'Телефон не указан' }}</strong>

            </li>

        </ul>


        <Pagination
            :simple="true"
            class="mt-4"
            v-on:pagination_page="nextUsers"
            v-if="users_paginate_object"
            :pagination="users_paginate_object"/>
    </template>

    <div class="card card-info mb-3">
        <div class="card-body">
            <p class="mb-2"> <i class="fa-solid fa-a text-danger mr-2" ></i> - администратор системы</p>
            <p class="mb-0"> <i class="fa-solid fa-u text-success mr-2" ></i> - пользователь</p>

        </div>
    </div>
    <a href="javascript:void(0)"
       @click="downloadBotUsers"
       class="btn btn-outline-info w-100 p-3 mb-2">
        <i class="fa-regular fa-file-excel mr-2"></i> Скачать список пользователей
    </a>

    <a href="javascript:void(0)"
       @click="downloadCashBackHistory"
       class="btn btn-outline-info w-100 p-3 mb-2">
        <i class="fa-regular fa-file-excel mr-2"></i> Скачать историю начисления
    </a>
</template>
<script>
import {mapGetters} from "vuex";
import {saveAs} from 'file-saver';

export default {
    props: ["selectedBotUser"],
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
        downloadBotUsers() {
            this.$notify({
                title:'Внимание!',
                text:'Начался формироваться документ статистики!'
            });
            this.$store.dispatch("downloadBotUsers").then((resp) => {
                //saveAs(resp.data, 'users.xlsx');

                this.$notify({
                    title:'Отлично!',
                    text:'Документ успешно сформирован!',
                    type:'success'
                });


            }).catch(() => {

                this.$notify({
                    title:'Упс...!',
                    text:'Что-то пошло не так...!',
                    type:'error'
                });
            })
        },
        downloadCashBackHistory() {
            this.$notify({
                title:'Внимание!',
                text:'Начался формироваться документ статистики!'
            });

            this.$store.dispatch("downloadCashBackHistory").then((resp) => {
                //  saveAs(resp.data, 'cashback.xlsx');

                this.$notify({
                    title:'Отлично!',
                    text:'Документ успешно сформирован!',
                    type:'success'
                });

            }).catch(() => {
                this.$notify({
                    title:'Упс...!',
                    text:'Что-то пошло не так...!',
                    type:'error'
                });
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
                    need_vip: this.need_vip,
                    need_not_vip: this.need_not_vip,
                    need_deliveryman: this.need_deliveryman,
                    need_with_phone: this.need_with_phone,
                    need_without_phone: this.need_without_phone,

                },
                page: page
            }).then(resp => {
                this.loading = false
                this.users = this.getUsers
                this.users_paginate_object = this.getUsersPaginateObject
                this.$emit("cancel")
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
