<script setup>
import Pagination from '@/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск бота"
                   aria-label="Поиск бота"
                   v-model="search"
                   aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary"
                    @click="loadBots"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row" v-if="bots.length>0">
        <div class="col-12 mb-3">
            <ul class="list-group w-100">
                <li class="list-group-item active cursor-pointer"
                    v-if="!editor"
                    @click="selectBot(null)">Создать нового бота
                </li>
                <li class="list-group-item cursor-pointer"
                    v-for="(bot, index) in bots"
                    @click="selectBot(bot)">Выбрать для редактирования <strong>{{
                        bot.bot_domain || 'Не указано'
                    }}</strong></li>
            </ul>

        </div>

        <div class="col-12">
            <Pagination

                v-on:pagination_page="nextBots"
                v-if="bots_paginate_object"
                :pagination="bots_paginate_object"/>
        </div>

    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                У выбранной компании нет созданных ботов!
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["companyId", "editor"],
    data() {
        return {
            loading: true,
            bots: [],
            search: null,
            bots_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getBots', 'getBotsPaginateObject']),
    },
    mounted() {
        this.loadBots();
    },
    methods: {
        selectBot(bot) {
            this.$emit("callback", bot)
            this.$notify("Вы выбрали бота из списка! Все остальные действия будут производится для этого бота.");
        },
        nextBots(index) {
            this.loadBots(index)
        },
        loadBots(page = 0) {
            this.loading = true
            this.$store.dispatch("loadBots", {
                dataObject: {
                    companyId: this.companyId || null,
                    search: this.search
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.bots = this.getBots
                this.bots_paginate_object = this.getBotsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
