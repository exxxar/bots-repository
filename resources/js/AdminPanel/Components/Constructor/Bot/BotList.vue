<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>
    <!--      <div class="row mb-2">
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
        <div class="row" v-if="favorites.length>0">
            <div class="col-md-12">
                <h4 >Боты в работе</h4>
                <p class="mb-0">Количество ботов в работе {{ favorites.length || 0 }}</p>
                <table
                    class="table mb-5">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Домен</th>
                        <th scope="col">Название</th>
                        <th scope="col">Шаблон</th>
                        <th scope="col">Дата изменения</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(bot, index) in favorites"

                        v-bind:class="{'border-info':bot.deleted_at==null,'border-danger':bot.deleted_at!=null}">
                        <th scope="row">{{ bot.id }}</th>
                        <td @click="selectBot(bot)"><i
                            v-bind:class="{'text-danger':bot.deleted_at!=null}"
                            class="fa-solid fa-robot mr-2"></i> {{ bot.bot_domain || 'Не указано' }}
                        </td>
                        <td @click="selectBot(bot)">{{ bot.title || 'Не указано' }}</td>
                        <td>
                            <span v-if="bot.is_template" class="badge bg-primary">
                                {{ bot.template_description || 'Не указано' }}
                            </span>
                            <span v-else>Не является шаблоном</span>
                        </td>
                        <td>{{ $filters.current(bot.updated_at) }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-outline-danger mr-2"
                                        type="button"
                                        v-bind:class="{'btn-danger text-white':inBotFav(bot.id)}"
                                        @click="addToFavorite(bot.id)"
                                        title="Добавить в избранное">
                                    <i class="fa-regular fa-star"></i>
                                </button>
                                <button class="btn btn-outline-primary mr-2"
                                        type="button"
                                        @click="moveOrderFavBot(bot.id, 1)"
                                        title="Переместить вниз">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <button class="btn btn-outline-primary mr-2"
                                        type="button"
                                        @click="moveOrderFavBot(bot.id,0)"
                                        title="Переместить вверх">
                                    <i class="fa-solid fa-chevron-up"></i>
                                </button>
                            </div>

                        </td>
                    </tr>


                    </tbody>
                </table>
                <hr>
            </div>
        </div>
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
                    <span class="badge bg-primary mr-1" v-for="filter in selectedFilters">{{
                            filter.name || 'не указан'
                        }}
                     <a
                         @click="removeSelectedFilter(filter.slug)"
                         class="ml-1 text-white" href="#filter"><i class="fa-solid fa-xmark"></i></a>
                    </span>
                </p>
            </div>
        </div>

        <div class="row" v-if="bots_paginate_object">
            <div class="col-12">
                <h4>Все боты</h4>
                <p class="mb-0">Количество найденных ботов {{ bots_paginate_object.meta.total || 0 }}</p>
                <p class="mb-0">Количество результатов на странице {{ filteredBots.length || 0 }}</p>
                <p>
                    Тип отображения списка:
                    <span v-on:click="displayType=0"
                          v-bind:class="{'bg-primary':displayType===0,'bg-secondary':displayType!==0}"
                          class="badge cursor-pointer mr-2">Карточки</span>
                    <span
                        v-bind:class="{'bg-primary':displayType===1,'bg-secondary':displayType!==1}"
                        v-on:click="displayType=1"
                        class="badge cursor-pointer mr-2">Таблица</span>
                </p>
            </div>
        </div>
        <div class="row" v-if="bots.length>0">
            <div class="col-12 mb-3">
                <button type="button" class="btn btn-outline-info"
                        v-if="!editor"
                        @click="selectBot(null)">Создать нового бота
                </button>
            </div>

            <div class="col-12 mb-3" v-if="displayType===1">



                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('bot_domain')">Домен</th>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('title')">Название</th>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('template_description')">Шаблон</th>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(bot, index) in filteredBots"

                        v-bind:class="{'border-info':bot.deleted_at==null,'border-danger':bot.deleted_at!=null}">
                        <th scope="row">{{ bot.id }}</th>
                        <td @click="selectBot(bot)"><i
                            v-bind:class="{'text-danger':bot.deleted_at!=null}"
                            class="fa-solid fa-robot mr-2"></i> {{ bot.bot_domain || 'Не указано' }}
                        </td>
                        <td @click="selectBot(bot)">{{ bot.title || 'Не указано' }}</td>
                        <td>
                            <span v-if="bot.is_template" class="badge bg-primary">
                                {{ bot.template_description || 'Не указано' }}
                            </span>
                            <span v-else>Не является шаблоном</span>
                        </td>
                        <td>{{ $filters.current(bot.updated_at) }}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-outline-danger mr-2"
                                        type="button"
                                        v-bind:class="{'btn-danger text-white':inBotFav(bot.id)}"
                                        @click="addToFavorite(bot.id)"
                                        title="Добавить в избранное">
                                    <i class="fa-regular fa-star"></i>
                                </button>
                                <button class="btn btn-outline-info mr-2"
                                        type="button"
                                        @click="duplicate(bot.id)"
                                        title="Дублировать">
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

                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>

            <div class="col-lg-4 col-12 mb-3"
                 v-if="displayType===0"
                 v-for="(bot, index) in filteredBots">
                <div class="card w-100"
                     v-bind:class="{'border-info':bot.deleted_at==null,'border-secondary':bot.deleted_at!=null}">

                    <div class="card-body">
                        <p
                            class="d-flex justify-content-between"
                            @click="selectBot(bot)"
                            style="word-wrap: break-word;">
                            <span>
                                <i
                                    v-bind:class="{'text-danger':bot.deleted_at!=null}"
                                    class="fa-solid fa-robot mr-2"></i>

                                {{
                                    bot.bot_domain || 'Не указано'
                                }}
                            </span>
                            <span class="badge bg-info"
                                  v-if="bot.is_template">{{
                                    bot.template_description || 'Шаблон без названия'
                                }}            </span>
                        </p>


                    </div>

                    <div class="card-footer d-flex">

                        <button class="btn btn-outline-info mr-2"
                                type="button"
                                @click="duplicate(bot.id)"
                                title="Дублировать">
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

                </div>

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
            displayType: 1,
            direction: 'desc',
            order: 'updated_at',
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
            favorites: [],
            search: null,
            bots_paginate_object: null,
        }
    },
    watch: {
        'displayType': function (oldArg, newArg) {
            localStorage.setItem("cashman_set_botlist_display_type", this.displayType)
        }
    },
    computed: {
        ...mapGetters(['getBots', 'getBotsPaginateObject', 'getCurrentCompany', 'getBotFavorites', 'inBotFav']),
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
        this.loadBots();

        this.selectFilter('active')

        this.displayType = parseInt(localStorage.getItem("cashman_set_botlist_display_type") || 0)
    },
    methods: {
        loadBotsByIds() {
            if (this.getBotFavorites.length == 0) {
                this.favorites = []
                return
            }

            this.loading = true
            this.$store.dispatch("loadBotsByIds", {
                ids: this.getBotFavorites
            }).then(resp => {
                this.loading = false
                this.favorites = resp
            }).catch(() => {
                this.loading = false
            })
        },
        prepareFavorites() {
            this.$store.dispatch("loadBotFavs").then(resp => {
                this.loadBotsByIds()
            })
        },
        moveOrderFavBot(id, direction) {
            this.$store.dispatch("swapBotInFav", {
                id: id,
                direction: direction
            }).then(resp => {
                this.loadBotsByIds()
            })
        },
        addToFavorite(id) {
            if (this.inBotFav(id)) {
                this.$store.dispatch("removeFromBotFavorites", id).then(resp => {
                    this.$notify("Указанный бот успешно удален из избранного");

                    this.loadBotsByIds()
                })
                return;
            }
            this.$store.dispatch("addBotToFavorites", id).then(resp => {
                this.$notify("Указанный бот успешно добавлен в избранное");
                this.loadBotsByIds()
            })
        },
        duplicate(id) {
            if (!this.getCurrentCompany) {
                this.$notify("У Вас не выбран клиент!");
                return;
            }
            this.$store.dispatch("duplicateBot", {
                dataObject: {
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
        forceDelete(id) {
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
                    direction: this.direction
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                this.bots = this.getBots
                this.bots_paginate_object = this.getBotsPaginateObject

                this.prepareFavorites()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
