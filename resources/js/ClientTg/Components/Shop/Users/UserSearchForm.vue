<script setup>

import Pagination from "@/ClientTg/Components/Shop/Helpers/Pagination.vue";
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";

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
                           class="border-0" placeholder="Кого ищим?">
                </div>

                <div class="fac fac-checkbox  py-2 my-2">
                    <span></span>
                    <input id="box3-fac-checkbox"
                           v-model="need_admins"
                           value="false"
                           type="checkbox">
                    <label for="box3-fac-checkbox">Только администраторы</label>
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
                    need_admins: this.need_admins
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
