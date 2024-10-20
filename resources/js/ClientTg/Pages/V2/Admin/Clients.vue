<script setup>

import UserSearchForm from "@/ClientTg/Components/V2/Admin/Clients/Users.vue";
import UserProfileCard from "@/ClientTg/Components/V2/Admin/Clients/UserProfileCard.vue";
</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs justify-content-center catalog-tabs">

                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            @click="tab=0"
                            style="font-weight:bold;"
                            v-bind:class="{'active':tab===0}"
                            aria-current="page"><i class="fa-solid fa-users mr-2"></i>Клиенты
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            @click="tab=1"
                            :disabled="!selected_bot_user"
                            style="font-weight:bold;"
                            v-bind:class="{'active':tab===1}"
                        ><i class="fa-solid fa-user-secret mr-2"></i>Результат
                        </button>
                    </li>

                </ul>
            </div>
            <div class="col-12" v-show="tab===0">
                <UserSearchForm
                    :selected-bot-user="selected_bot_user"
                    v-on:cancel="cancelUserSelected"
                    v-on:select="selectUser"/>
            </div>

            <div class="col-12" v-if="tab===1">
                <UserProfileCard
                    v-if="loadedUser"
                    v-model="selected_bot_user"></UserProfileCard>
                <div class="alert alert-light mt-2" v-else>
                    Пользователь еще не выбран!
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
            loadedUser: false,
            tab: 0,
            request_telegram_chat_id: null,
            selected_bot_user: null,


        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        },
        tg() {
            return window.Telegram.WebApp;
        },

    },

    mounted() {

        if (this.getSelf) {
            this.selected_bot_user = this.getSelf
        }

        const urlParams = new URLSearchParams(window.location.search);
        let user = JSON.parse(urlParams.get('user'));
        let needClose = urlParams.has('hide_menu') || false;

        if (user) {
            this.request_telegram_chat_id = user
            this.loadReceiverUserData()
        }

        this.tg.BackButton.show()


        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            if (needClose)
                this.tg.close()
            else
                this.$router.back()
        })

    },


    methods: {

        updateUserInfo() {
            this.selected_bot_user = null
            this.request_telegram_chat_id = null
        },
        cancelUserSelected() {
            this.request_telegram_chat_id = null
            this.selected_bot_user = null
            window.scroll(0, 0)
        },
        selectUser(user) {
            this.request_telegram_chat_id = user.telegram_chat_id

            this.loadReceiverUserData()
        },

        loadReceiverUserData() {
            this.loadedUser = false
            this.selected_bot_user = null

            this.$store.dispatch("loadReceiverUserData", {
                dataObject: {
                    user_telegram_chat_id: this.request_telegram_chat_id
                },
            }).then(resp => {
                this.selected_bot_user = resp.data
                this.request_telegram_chat_id = this.selected_bot_user.telegram_chat_id

                this.$nextTick(() => {
                    this.loadedUser = true
                    this.tab = 1
                    window.scroll(0, 0)
                })
            }).catch(() => {
                this.loadedUser = false
            })
        },

    }
}
</script>
