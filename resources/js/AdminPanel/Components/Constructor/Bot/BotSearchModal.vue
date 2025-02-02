<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
import SlotCount from "@/AdminPanel/Components/Constructor/Bot/SlotCount.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
</script>
<template>

    <div class="dropdown">
        <button
            :class="customClass"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <template v-if="!bot">
                Бот не выбран
            </template>
            <template v-else>
                {{ bot.bot_domain || 'Без имени' }}
            </template>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="javascript:void(0)" @click="openModal"><i
                class="fa-regular fa-hand-pointer mr-2"></i> Выбрать бота</a></li>

            <template v-if="bot">
                <li><a :href="'https://t.me/'+(bot.bot_domain||'botfather')"
                       class="dropdown-item"
                       target="_blank"><i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Перейти в бот</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)"
                       @click="resetBot"><i class="fa-solid fa-power-off mr-2"></i> Завершить работу с ботом</a></li>
            </template>

        </ul>
    </div>


    <!-- Modal -->
    <div class="modal fade" :id="'bot-search-modal-'+(id||'local')" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Поиск ваших ботов</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <ul class="nav nav-tabs" v-if="profile?.is_admin||false">
                        <li class="nav-item">
                            <a
                                @click="search_modal_tab = 0"
                                v-bind:class="{'active':search_modal_tab===0}"
                                class="nav-link" aria-current="page" href="javascript:void(0)">Базовый поиск</a>
                        </li>
                        <li class="nav-item">
                            <a
                                @click="search_modal_tab = 1"
                                v-bind:class="{'active':search_modal_tab===1}"
                                class="nav-link" href="javascript:void(0)">Административный поиск</a>
                        </li>
                    </ul>

                    <template v-if="search_modal_tab===0">
                        <div class="px-2 py-3">
                            <div class="container">
                                <SlotCount
                                    v-if="bots.length>0"
                                    :bots-count="bots_paginate_object?.meta?.total||0"></SlotCount>


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
                                                <li class="list-inline-item mr-2 cursor-pointer"
                                                    @click="loadAndOrder('id')">
                          <span v-if="direction === 'desc'&&order === 'id'"><i
                              class="fa-solid fa-caret-down"></i></span>
                                                    <span v-if="direction === 'asc'&&order === 'id'"><i
                                                        class="fa-solid fa-caret-up"></i></span>
                                                    Id
                                                </li>
                                                <li class="list-inline-item mr-2 cursor-pointer"
                                                    @click="loadAndOrder('title')">
                        <span v-if="direction === 'desc'&&order === 'title'"><i
                            class="fa-solid fa-caret-down"></i></span>
                                                    <span v-if="direction === 'asc'&&order === 'title'"><i
                                                        class="fa-solid fa-caret-up"></i></span>
                                                    Название бота
                                                </li>
                                                <li class="list-inline-item mr-2 cursor-pointer"
                                                    @click="loadAndOrder('bot_domain')">
                        <span v-if="direction === 'desc'&&order === 'bot_domain'"><i
                            class="fa-solid fa-caret-down"></i></span>
                                                    <span v-if="direction === 'asc'&&order === 'bot_domain'"><i
                                                        class="fa-solid fa-caret-up"></i></span>
                                                    Домен бота
                                                </li>
                                                <li class="list-inline-item mr-2 cursor-pointer"
                                                    @click="loadAndOrder('updated_at')">
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
                                <h6>Ваши созданные боты <a href="/bot-page" v-if="(profile?.is_admin||false)">перейти в
                                    раздел</a></h6>
                                <div class="row row-cols-1 row-cols-lg-4 row-cols-md-1 g-4">
                                    <div class="col" v-for="bot in bots">

                                        <div class="card h-100">
                                            <img
                                                class="card-img-top w-100 object-fit-cover"
                                                style="max-height:150px;"
                                                v-lazy="'/images-by-bot-id/'+bot.id+'/'+bot.image">

                                            <div class="card-body">
                                                <h6 class="card-title text-center">#{{ bot.id }}</h6>
                                                <h5 class="card-title text-center" style="font-size:14px;">
                                                    {{ bot.title || bot.id }}</h5>
                                                <!--                                        <p class="card-text text-center">
                                                                                            {{ bot.short_description || 'Без описания' }}
                                                                                        </p>-->
                                                <p class="card-text text-center mb-2">
                                                    Баланс {{ bot.balance || '0' }} руб
                                                </p>
                                                <p class="card-text text-center mb-2">
                                                    Тариф {{ bot.tax_per_day || '0' }} руб\день
                                                </p>

                                                <p class="text-center mb-2">
                                                    <small>{{ $filters.currentFull(bot.updated_at) }}</small></p>
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
                        </div>

                    </template>

                    <template v-if="search_modal_tab===1">
                        <div class="px-2 py-3">
                            <BotList v-on:callback="gotoBot"></BotList>
                        </div>

                    </template>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["customClass", "bot", "id"],
    data() {

        return {
            search_modal_tab: 0,
            direction: 'desc',
            order: 'updated_at',
            searchModal: null,
            filters: [
                {
                    name: 'Активные',
                    icon: 'fa-brands fa-telegram',
                    slug: 'active'
                },
                {
                    name: 'Архивные',
                    icon: 'fa-solid fa-box-archive',
                    slug: 'archive'
                }
            ],
            selectedFilters: [],

            loading: true,
            bots: [],

            search: null,
            bots_paginate_object: null,
        }
    },
    watch: {
    /*    selectedFilters: {
            handler: function (newValue) {
                this.loadBots();
            },
            deep: true
        }*/
    },
    computed: {
        ...mapGetters(['getBots', 'getBotsPaginateObject', 'getCurrentCompany', 'getCurrentBot']),
        profile() {
            return window.profile || null
        },

        filteredBots() {
            if (!this.bots)
                return [];


            if (this.selectedFilters.length === 0 && this.search == null)
                return this.bots

            if (this.selectedFilters.length === 0 && this.search != null)
                return this.bots.filter(item => (item.bot_domain || '')
                    .trim()
                    .toLowerCase()
                    .indexOf(this.search
                        .trim()
                        .toLowerCase()) !== -1)

            let tmpBots = [];
            this.selectedFilters.forEach(filter => {
                switch (filter.slug) {
                    case 'active':
                        this.bots.filter(item => item.deleted_at == null).forEach(item => {
                            tmpBots.push(item)
                        })
                        break;
                    case 'archive':
                        this.bots.filter(item => item.deleted_at != null).forEach(item => {
                            tmpBots.push(item)
                        })
                        break;
                }
            })

            if (this.search == null)
                return tmpBots

            return tmpBots.filter(item => (item.bot_domain || '')
                .trim()
                .toLowerCase()
                .indexOf(this.search
                    .trim()
                    .toLowerCase()) !== -1)


        }
    },
    mounted() {
        this.searchModal = new bootstrap.Modal(document.getElementById('bot-search-modal-' + (this.id || 'local')), {
            backdrop: false
        })

        this.selectFilter('active')
    },
    methods: {
        gotoBot(bot) {
            this.selectBot(bot)
        },
        openModal() {
            this.loadBots();
            this.searchModal.show()
        },
        resetBot() {
            window.dispatchEvent(new CustomEvent('reset-current_bot-from-navbar-event'));
            this.$store.dispatch("resetCurrentBot").then(() => {

            })
        },
        hasRole(role) {
            return window.hasRole(role) || false
        },
        loadBotsByIds() {

            this.loading = true
            this.$store.dispatch("loadBotsByIds", {
                ids: []
            }).then(resp => {
                this.loading = false
            }).catch(() => {
                this.loading = false
            })
        },

        selectFilter(slug) {
            let tmpFilter = this.filters.find(item => item.slug === slug)

            if (tmpFilter && this.selectedFilters.filter(item => item.slug === slug).length === 0)
                this.selectedFilters.push(tmpFilter)


        },
        removeSelectedFilter(slug) {
            let index = this.selectedFilters.findIndex(item => item.slug === slug)
            this.selectedFilters.splice(index, 1)
        },
        selectBot(bot) {
            this.searchModal.hide()

            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                //this.bot = this.getCurrentBot
            })

            this.$emit("select-bot", bot)

            this.$notify("Вы выбрали бота из списка! Все остальные действия будут производится для этого бота.");
        },
        nextBots(index) {
            this.loadBots(index)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadBots(0)
        },
        loadBots(page = 0) {
            this.loading = true
            this.$store.dispatch("loadBots", {
                dataObject: {
                    companyId: this.companyId || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction,
                    filters: this.selectedFilters.map(o => o["slug"])
                },
                page: page,
                size: 20
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
