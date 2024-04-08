<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="container pt-5">
        <h6>Ваши доступные слоты</h6>

        <div class="row">
            <div class="col-md-6">
                <div class="progress" role="progressbar" aria-label="Success striped example" aria-valuenow="25"
                     aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-striped bg-success"
                         v-bind:style="{'width': slotsPercent+'%'}"></div>
                </div>
            </div>
            <div class="col-md-4 d-flex justify-content-between">
                <p>Число слотов <strong>{{ getSelf.manager.max_bot_slot_count }}</strong>.
                    Отображается ботов <strong>{{ summarySlotCount }}</strong> шт. под вашим управлением</p>


            </div>
            <div class="col-md-2">
                <a href="#" @click="callback(12)">Пополнить слоты</a>
            </div>
        </div>


        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-12">
                <div class="input-group mb-3">
                    <input type="search" class="form-control "
                           placeholder="Поиск бота"
                           aria-label="Поиск бота"
                           v-model="search"
                           @keydown.enter="loadBots(0)"
                           aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary "
                            @click="loadBots(0)"
                            type="button"
                            id="button-addon2">Найти
                    </button>
                </div>

                <div class="w-100 d-flex justify-content-center flex-wrap">
                    <p class="mb-0 text-primary font-bold"><small>Тип сортировки</small></p>
                    <ul class="list-group d-flex flex-row w-100 justify-content-center">
                        <li class="list-inline-item mr-2 cursor-pointer" @click="loadAndOrder('id')">
                          <span v-if="direction === 'desc'&&order === 'id'"><i
                              class="fa-solid fa-caret-down"></i></span>
                            <span v-if="direction === 'asc'&&order === 'id'"><i
                                class="fa-solid fa-caret-up"></i></span>
                            Id
                        </li>
                        <li class="list-inline-item mr-2 cursor-pointer" @click="loadAndOrder('title')">
                        <span v-if="direction === 'desc'&&order === 'title'"><i
                            class="fa-solid fa-caret-down"></i></span>
                            <span v-if="direction === 'asc'&&order === 'title'"><i
                                class="fa-solid fa-caret-up"></i></span>
                            Название бота
                        </li>
                        <li class="list-inline-item mr-2 cursor-pointer" @click="loadAndOrder('bot_domain')">
                        <span v-if="direction === 'desc'&&order === 'bot_domain'"><i
                            class="fa-solid fa-caret-down"></i></span>
                            <span v-if="direction === 'asc'&&order === 'bot_domain'"><i
                                class="fa-solid fa-caret-up"></i></span>
                            Домен бота
                        </li>
                        <li class="list-inline-item mr-2 cursor-pointer" @click="loadAndOrder('updated_at')">
                        <span v-if="direction === 'desc'&&order === 'updated_at'"><i
                            class="fa-solid fa-caret-down"></i></span>
                            <span v-if="direction === 'asc'&&order === 'updated_at'"><i
                                class="fa-solid fa-caret-up"></i></span>
                            Дата обновления
                        </li>
                    </ul>


                </div>


            </div>


        </div>

        <div class="row my-2" v-if="bots.length>0">
            <div class="col-12">
                <Pagination
                    v-on:pagination_page="nextBots"
                    v-if="bots_paginate_object"
                    :pagination="bots_paginate_object"/>
            </div>
        </div>

    </div>

    <div class="container pb-5" v-if="bots.length>0">
        <h6>Ваши созданные боты <a href="/bot-page" v-if="(getSelf.is_admin||false)">перейти в раздел</a></h6>
        <div class="row row-cols-1 row-cols-lg-3 row-cols-md-1 g-4">
            <div class="col" v-for="bot in bots">

                <div class="card h-100">
                    <img
                        class="card-img-top"
                        v-lazy="'/images-by-bot-id/'+bot.id+'/'+bot.image">

                    <div class="card-body">
                        <h6 class="card-title text-center">#{{  bot.id }}</h6>
                        <h5 class="card-title text-center">{{ bot.title || bot.id }}</h5>
                        <p class="card-text text-center">
                            {{ bot.short_description || 'Без описания' }}
                        </p>
                        <p class="text-center"><small>{{$filters.currentFull(bot.updated_at)}}</small></p>
                    </div>
                    <div class="card-footer "
                         v-bind:class="{'bg-danger':(bot.bot_token||'').length<40,'bg-success':(bot.bot_token||'').length>=40}"
                    >
                        <button
                            type="button"
                            @click="gotoBot(bot)"
                            class="btn btn-link text-white w-100">Редактировать
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="row my-5">

            <div class="col-12">
                <Pagination
                    v-on:pagination_page="nextBots"
                    v-if="bots_paginate_object"
                    :pagination="bots_paginate_object"/>
            </div>
        </div>
    </div>

    <div class="container" v-else>
        <div class="alert alert-info" role="alert">
            <p>У вас еще нет созданных ботов, попробуйте с чего-то простого;)</p>

        </div>

        <div class="d-flex justify-content-center">
            <button class="btn btn-primary" @click="callback(0)">Поехали создавать</button>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {

    data() {
        return {
            search: null,
            bots: [],
            direction: 'desc',
            order: 'updated_at',
            bots_paginate_object: null
        }
    }, computed: {
        ...mapGetters(['getBots', 'getBotsPaginateObject']),
        getSelf() {
            return window.profile || null
        },
        slotsCount(){
            return this.getSelf.manager.max_bot_slot_count
        },
        slotsPercent() {
            if (!this.bots_paginate_object)
                return (this.bots.length / (this.summarySlotCount + this.slotsCount)) * 100

            return ((this.bots_paginate_object.meta.total || 0) / (this.summarySlotCount + this.slotsCount)) * 100
        },
        summarySlotCount() {
            if (!this.bots_paginate_object)
                return 0
            return this.bots_paginate_object.meta.total
        }
    },
    mounted() {
        this.loadBots(0)
    },
    methods: {
        nextBots(index) {
            this.loadBots(index)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadBots(0)
        },
        loadBots(page = 0) {
            this.$store.dispatch("loadBots", {
                dataObject: {
                    search: this.search,
                    order: this.order,
                    direction: this.direction,
                },
                page: page,
                size: 20
            }).then(() => {
                this.bots = this.getBots || []
                this.bots_paginate_object = this.getBotsPaginateObject || null
            })
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        gotoBot(bot) {
            this.loadCurrentBot(bot)
            localStorage.setItem("cashman_set_botform_step_index", 0)
            localStorage.setItem("cashman_set_botpage_step_index", 2)
            window.location.href = '/bot-page'

        },
        callback(index) {
            this.$emit("callback", index)
        }
    }
}
</script>
<style lang="scss">

.slot-list {
    display: flex;
    justify-content: start;
    align-items: start;
    flex-wrap: wrap;


    .slot-wrapper {
        padding: 5px;

        .slot {
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 5px;


            position: relative;

            &.opened {
                box-shadow: 2px 2px 3px 0px #a7a4a4;
                border: 1px #185018 solid;

            }

            &.closed {
                box-shadow: 2px 2px 3px 0px #a7a4a4 inset;
                display: flex;
                justify-content: center;
                align-items: center;
                background: url('../images/cashman.jpg');
                background-size: cover;
                background-blend-mode: color;
                border: 1px #d8d8d8 solid;
            }

        }
    }
}
</style>
