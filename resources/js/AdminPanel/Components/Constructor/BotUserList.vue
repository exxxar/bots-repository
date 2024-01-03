<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row mt-2">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск пользователя"
                   aria-label="Поиск пользователя"
                   v-on:keydown.enter="loadUsers(0)"
                   v-model="search">
            <button class="btn btn-outline-secondary"
                    @click="loadUsers(0)"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row" v-if="bot_users.length>0">
        <div class="col-12 col-md-12 mb-3" >

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ф.И.О. из телеграмма</th>
                    <th scope="col">Имя пользователя</th>
                    <th scope="col">Домен</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">TG id</th>
                    <th scope="col">Админ</th>
                    <th scope="col">VIP</th>
                    <th scope="col">Доставщик</th>
                    <th scope="col">Менеджер</th>
                    <th scope="col">За работой</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(botUser, index) in bot_users">
                    <th scope="row">{{botUser.id}}</th>
                    <td>{{ botUser.fio_from_telegram || 'Не указано' }}</td>
                    <td>{{ botUser.name || 'Не указано' }}</td>
                    <td>
                        <a :href="'https://t.me/'+ botUser.username" target="_blank" v-if="botUser.username">@{{ botUser.username }}</a>
                        <span v-else>Не указано</span>
                    </td>

                    <td>{{ botUser.phone || 'Не указано' }}</td>
                    <td>{{ botUser.telegram_chat_id || 'Не указано' }}</td>
                    <td>
                        <span
                            @click="changeUserStatus(index, 0, 'is_admin')"
                            v-if="botUser.is_admin"><i class="fa-solid fa-check text-success"></i></span>
                        <span
                            @click="changeUserStatus(index, 1, 'is_admin')"
                            v-else><i class="fa-solid fa-xmark text-danger"></i></span>
                    </td>
                    <td>
                        <span
                            @click="changeUserStatus(index, 0, 'is_vip')"
                            v-if="botUser.is_vip"><i class="fa-solid fa-check text-success"></i></span>
                        <span
                            @click="changeUserStatus(index, 1, 'is_vip')"
                            v-else><i class="fa-solid fa-xmark text-danger"></i></span>
                    </td>
                    <td>
                        <span
                            @click="changeUserStatus(index, 0, 'is_deliveryman')"
                            v-if="botUser.is_deliveryman"><i class="fa-solid fa-check text-success"></i></span>
                        <span
                            @click="changeUserStatus(index, 1, 'is_deliveryman')"
                            v-else><i class="fa-solid fa-xmark text-danger"></i></span>
                    </td>
                    <td>
                        <span
                            @click="changeUserStatus(index, 0, 'is_manager')"
                            v-if="botUser.is_manager"><i class="fa-solid fa-check text-success"></i></span>
                        <span
                            @click="changeUserStatus(index, 1, 'is_manager')"
                            v-else><i class="fa-solid fa-xmark text-danger"></i></span>
                    </td>
                    <td>
                        <span
                            @click="changeUserStatus(index, 0, 'is_work')"
                            v-if="botUser.is_work"><i class="fa-solid fa-check text-success"></i></span>
                        <span
                            @click="changeUserStatus(index, 1, 'is_work')"
                            v-else><i class="fa-solid fa-xmark text-danger"></i></span>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <div class="col-12">
            <Pagination

                v-on:pagination_page="nextUsers"
                v-if="bot_users_paginate_object"
                :pagination="bot_users_paginate_object"/>
        </div>

    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                У выбранного бота нет пользователей
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            bot:null,
            loading: true,
            bot_users: [],
            search: null,
            bot_users_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getBotUsers', 'getBotUsersPaginateObject','getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot().then(()=>{
            this.loadUsers();
        })

    },
    methods: {
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        changeUserStatus(index, status, type="is_admin") {


            this.bot_users[index][type] = status === 1

            this.$store.dispatch("changeUserStatus", {
                dataObject: {
                    bot_user_id: this.bot_users[index].id,
                    status: status,
                    type: type
                }
            }).then(() => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Статус пользователя успешно изменен! Пользователь оповещен об изменении статуса!",
                    type: 'success'
                });

                //this.loadUsers()
            }).catch(() => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка изменения статуса",
                    type: 'error'
                });
            })
        },
        nextUsers(index) {
            this.loadUsers(index)
        },
        loadUsers(page = 0) {
            this.loading = true
            this.$store.dispatch("loadBotUsers", {
                dataObject: {
                    botId: this.bot.id|| null,
                    search: this.search
                },
                size: 100,
                page: page
            }).then(resp => {
                this.loading = false
                this.bot_users = this.getBotUsers
                this.bot_users_paginate_object = this.getBotUsersPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
