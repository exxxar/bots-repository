<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск пользователя"
                   aria-label="Поиск пользователя"
                   v-model="search">
            <button class="btn btn-outline-secondary"
                    @click="loadUsers(0)"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row" v-if="bot_users.length>0">
        <div class="col-12 col-md-6 mb-3" v-for="(botUser, index) in bot_users">
            <div class="card">
                <div class="card-body">
                    <p>Ф.И.О. из телеграма: {{ botUser.fio_from_telegram || 'Не указано' }}
                        <span
                            v-if="botUser.is_admin"
                            class="badge rounded-pill text-bg-success">Администратор</span>
                    </p>
                    <p>Имя пользователя: {{ botUser.name || 'Не указано' }}</p>
                    <p>Телефон: {{ botUser.phone || 'Не указано' }}</p>
                    <p>Почта: {{ botUser.email || 'Не указано' }}</p>
                    <p>id чата: {{ botUser.telegram_chat_id || 'Не указано' }}</p>
                </div>
                <div class="card-footer">
                    <button
                        v-if="!botUser.is_admin"
                        type="button"
                        @click="changeUserStatus(botUser.id, 1)"
                        class="btn btn-outline-success">
                        Назначить администратором
                    </button>
                    <button
                        v-else
                        type="button"
                        @click="changeUserStatus(botUser.id,0)"
                        class="btn btn-outline-success">
                        Разжаловать из администраторов
                    </button>
                </div>
            </div>
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
        changeUserStatus(id, status) {
            this.$store.dispatch("changeUserStatus", {
                dataObject: {
                    botUserId: id,
                    status: status
                }
            }).then(() => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Статус пользователя успешно изменен! Пользователь оповещен об изменении статуса!",
                    type: 'success'
                });

                this.loadUsers()
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
