<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>
    <!--    <div class="row mb-2">
            <div class="col-12">
                <button type="button"
                        @click="show=!show"
                        class="btn btn-outline-success p-3 w-100">
                    <span v-if="!show"><i class="fa-solid fa-robot"></i> Открыть список ботов</span>
                    <span v-else><i class="fa-regular fa-square-minus"></i> Свернуть список ботов</span>
                </button>
            </div>
        </div>-->

    <div v-if="show">
        <div class="row">
            <div class="col-md-12 d-flex flex-column">

                <div class="d-flex">
                    <div class="dropdown mr-2">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            Фильтры
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li v-for="item in filters"><a class="dropdown-item"
                                                           @click="selectFilter(item.slug)"
                                                           href="#filter"><i
                                v-bind:class="item.icon"
                                class="mr-2"></i> {{ item.name || 'Не указано' }}</a></li>

                        </ul>
                    </div>


                    <div class="input-group mb-3">
                        <input type="search" class="form-control "
                               placeholder="Поиск бота"
                               aria-label="Поиск бота"
                               v-model="search"
                               aria-describedby="button-addon2">
                        <button class="btn btn-outline-secondary "
                                @click="loadBots"
                                type="button"
                                id="button-addon2">Найти
                        </button>
                    </div>
                </div>
                <p v-if="selectedFilters.length>0" class="mt-2">
                    <span class="badge bg-info mr-1" v-for="filter in selectedFilters">{{ filter.name || 'не указан' }}
                     <a
                         @click="removeSelectedFilter(filter.slug)"
                         class="ml-1 text-white" href="#filter"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                </p>
            </div>
        </div>
        <div class="row" v-if="bots.length>0">
            <div class="col-12 mb-3">
                <ul class="list-group w-100">
                    <li class="list-group-item active cursor-pointer  btn btn-outline-info"
                        v-if="!editor"
                        @click="selectBot(null)">Создать нового бота
                    </li>
                    <li class="list-group-item cursor-pointer btn mb-1 d-flex  align-items-center justify-between"
                        v-bind:class="{'btn-outline-info':bot.deleted_at==null,'btn-outline-secondary border-secondary':bot.deleted_at!=null}"
                        v-for="(bot, index) in filteredBots"
                    >
                        <strong
                            @click="selectBot(bot)"
                            style="word-wrap: break-word;"><i
                            v-bind:class="{'text-danger':bot.deleted_at!=null}"
                            class="fa-solid fa-robot mr-2"></i>{{
                                bot.bot_domain || 'Не указано'
                            }}
                        </strong>

                        <span class="badge bg-info"
                              v-if="bot.is_template">{{ bot.template_description || 'Шаблон без названия' }}
                    </span>

                        <div>
                        <button class="btn btn-outline-info mr-2"
                                type="button"
                                @click="duplicate(bot.id)"
                                title="Дублировать" >
                            <i class="fa-regular fa-copy"></i>
                        </button>
                        <button class="btn btn-outline-info"
                                type="button"
                                @click="addToArchive(bot.id)"
                                title="В архив" v-if="bot.deleted_at==null"><i
                            class="fa-solid fa-boxes-packing"></i></button>

                            <button class="btn btn-outline-info mr-2"
                                    @click="extractFromArchive(bot.id)"
                                    title="Из архива" v-if="bot.deleted_at!=null"><i
                                class="fa-solid fa-box-open"></i></button>

                            <button class="btn btn-danger  mr-2 "
                                    @click="forceDelete(bot.id)"
                                    title="Удалить на совсем" v-if="bot.deleted_at!=null">
                                <i class="fa-solid fa-trash-can text-white"></i>
                            </button>
                        </div>


                    </li>
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
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["companyId", "editor"],
    data() {
        return {
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
            show: true,
            loading: true,
            bots: [],
            search: null,
            bots_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getBots', 'getBotsPaginateObject','getCurrentCompany']),
        filteredBots() {
            if (!this.bots)
                return [];


            if (this.selectedFilters.length === 0 && this.search == null)
                return this.bots

            if (this.selectedFilters.length === 0 && this.search != null)
                return this.bots.filter(item => (item.bot_domain||'')
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

            return tmpBots.filter(item=>(item.bot_domain||'')
                .trim()
                .toLowerCase()
                .indexOf(this.search
                    .trim()
                    .toLowerCase())!==-1)


        }
    },
    mounted() {
        this.loadBots();
        this.selectFilter('active')
    },
    methods: {
        duplicate(id){
            if (!this.getCurrentCompany){
                this.$notify("У Вас не выбран клиент!");
                return;
            }
            this.$store.dispatch("duplicateBot", {
                dataObject:{
                    bot_id: id,
                    company_id: this.getCurrentCompany.id
                }
            }).then(resp => {
                let currentPage = this.bots_paginate_object.meta.current_page || 0
                this.loadBots(currentPage)
                this.$notify("Указанный бот успешно продублирован");
            })
        },
        addToArchive(id) {
            this.$store.dispatch("removeBot", {
                botId: id
            }).then(resp => {
                let currentPage = this.bots_paginate_object.meta.current_page || 0
                this.loadBots(currentPage)
                this.$notify("Указанный бот успешно перемещен в архив");
            })
        },
        forceDelete(id){
            this.$store.dispatch("forceDeleteBot", {
                botId: id
            }).then(resp => {
                let currentPage = this.bots_paginate_object.meta.current_page || 0
                this.loadBots(currentPage)
                this.$notify("Указанный бот успешно перемещен из архива");
            })
        },
        extractFromArchive(id) {
            this.$store.dispatch("restoreBot", {
                botId: id
            }).then(resp => {
                let currentPage = this.bots_paginate_object.meta.current_page || 0
                this.loadBots(currentPage)
                this.$notify("Указанный бот успешно перемещен из архива");
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
            this.$emit("callback", bot)
            this.show = false
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
