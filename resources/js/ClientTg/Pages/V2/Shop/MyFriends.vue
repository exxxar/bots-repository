<script setup>

import Pagination from '@/ClientTg/Components/V1/Pagination.vue'


</script>
<template>

    <div class="container">

        <div class="row" v-if="getSelf?.parent_friend!=null">
            <div class="col-12">
                <h5 class="my-3"><i class="fa-solid fa-handshake mr-1 text-primary"></i> Вас пригласили</h5>
            </div>
            <div class="col-12">
                <div class="alert alert-light d-flex justify-content-between">
                    <p class="mb-0">{{getSelf.parent_friend.username}}</p>
                    <a
                        v-if="getSelf.parent_friend.username"
                        :href="'https://t.me/'+getSelf.parent_friend.username" target="_blank">@{{
                            getSelf.parent_friend.username || '-'
                        }}</a>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-12">
                <h5 class="mb-3"><i class="fa-solid fa-list-check mr-1 text-primary"></i> Приглашенные вами люди</h5>
            </div>
            <div class="col-12">
                <p>У вас {{getSelf.friends_count}} друзей</p>
                <ul class="list-group" v-if="friends.length>0">
                    <li
                        v-for="(item, index) in friends"
                        class="list-group-item d-flex flex-column">
                        <div class="d-flex justify-content-between w-100">
                            <div>
                                <i class="fa-regular fa-face-smile mr-2 text-success"></i>

                                <span class="fw-bold">
                                    <a
                                        v-if="item.username"
                                        :href="'https://t.me/'+item.username" target="_blank">@{{
                                            item.username || '-'
                                        }}</a>
                                </span>
                            </div>


                            <span class="text-primary fw-bold">
                                {{ item.fio_from_telegram || '-' }}
                            </span>
                        </div>

                    </li>

                </ul>

                <div v-else class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <i class="fa-solid fa-handshake mb-3" style="font-size:36px;"></i>
                        <p>Вы никого не пригласили:(</p>
                    </div>
                </div>
                <Pagination

                    v-on:pagination_page="nextFriendsPage"
                    v-if="friends_paginate_object"
                    :pagination="friends_paginate_object"/>
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
            friends_paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getSelf', 'getFriends',
            'getFriendsPaginateObject']),
        self() {
            return this.getSelf
        },
        tg() {
            return window.Telegram.WebApp;
        },

    },
    mounted() {
        this.loadFriends(0);

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {

        nextFriendsPage(index) {
            this.loadFriends(index)
        },
        loadFriends(page = 0) {
            this.$store.dispatch("loadFriends", {
                page: page
            }).then(resp => {

                this.friends = this.getFriends
                this.friends_paginate_object = this.getFriendsPaginateObject


            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
