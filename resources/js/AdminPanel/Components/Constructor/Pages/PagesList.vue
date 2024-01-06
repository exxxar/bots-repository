<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск страницы"
                   aria-label="Поиск бота"
                   v-model="search"
                   aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary"
                    @click="loadPages"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_deleted"
                       type="checkbox" id="needDeleted">
                <label class="form-check-label" for="needDeleted">Отобразить удаленные</label>
            </div>
        </div>
    </div>

    <div class="row" v-if="pages.length>0">
        <!--            <div class="col-12 mb-3">
                        <button type="button" class="btn btn-outline-success w-100"
                                @click="selectPage(null)">Создать новую страницу</button>
                    </div>-->

        <div class="col-12" v-if="pages.length>7">
            <Pagination
                v-on:pagination_page="nextPages"
                v-if="pages_paginate_object"
                :pagination="pages_paginate_object"/>
        </div>

        <div class="col-12 mb-3">
            <ul class="list-group w-100">

                <li class="list-group-item cursor-pointer page-menu-item btn btn-outline-info mb-1"
                    v-bind:class="{'border border-warning':page.deleted_at!=null}"
                    v-for="(page, index) in pages"
                >
                    <div class=" d-flex justify-content-between ">

                        <strong
                            @click="selectPage(page)">#{{ page.id || 'Не указано' }}
                            <span v-if="page.slug">{{ page.slug.command || 'Не указано' }}</span>
                            <span v-else>Не привязано к команде</span>
                            <span v-if="current&&current==page.id"><i class="fa-solid fa-lock"></i></span>
                        </strong>


                        <div v-if="editor">

                            <div class="dropdown">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li v-if="page.deleted_at != null"><a class="dropdown-item"
                                                                          @click="restorePage(page.id)"><i
                                        class="fa-solid fa-copy mr-1"></i>Восстановить</a></li>
                                    <li v-if="page.deleted_at != null"><a class="dropdown-item"
                                                                          @click="forceRemovePage(page.id)">
                                        <i class="fa-solid fa-ban mr-1"></i>Удалить полностью</a></li>
                                    <li><a class="dropdown-item" @click="duplicatePage(page.id)"><i
                                        class="fa-solid fa-copy mr-1"></i>Дублировать</a></li>
                                    <li v-if="page.deleted_at == null"><a class="dropdown-item"
                                                                          @click="removePage(page.id)"><i
                                        class="fa-solid fa-trash mr-1"></i>Удалить</a></li>

                                </ul>
                            </div>

                        </div>

                    </div>

                    <ul
                        v-if="page"
                        class="component-icons">
                        <li
                            v-if="(page.images||[]).length>0">
                            <i class="fa-regular fa-images"></i>
                        </li>
                        <li
                            v-if="page.sticker">
                            <i class="fa-regular fa-note-sticky"></i>
                        </li>
                        <li
                            v-if="(page.videos||[]).length>0">
                            <i class="fa-solid fa-photo-film"></i>
                        </li>
                        <li
                            v-if="(page.audios||[]).length>0">
                            <i class="fa-regular fa-file-audio"></i>
                        </li>
                        <li
                            v-if="(page.documents||[]).length>0">
                            <i class="fa-regular fa-file-word"></i>
                        </li>
                        <li
                            v-if="page.next_page_id">
                            <i class="fa-solid fa-link"></i>
                        </li>
                        <li
                            v-if="page.next_bot_menu_slug_id">
                            <i class="fa-solid fa-scroll"></i>
                        </li>
                        <li
                            v-if="page.next_bot_dialog_command_id">
                            <i class="fa-regular fa-comment-dots"></i>
                        </li>
                        <li
                            v-if="page.rules_if">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </li>
                        <li
                            v-if="page.reply_keyboard_id">
                            <i class="fa-regular fa-keyboard"></i>
                        </li>
                        <li
                            v-if="page.inline_keyboard_id">
                            <i class="fa-solid fa-ellipsis"></i>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>

        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextPages"
                v-if="pages_paginate_object"
                :pagination="pages_paginate_object"/>
        </div>

    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                Созданных страниц не найдено!
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["editor", "current"],
    data() {
        return {
            bot: null,
            current_page: 0,
            need_deleted: false,
            loading: true,
            pages: [],
            search: null,
            pages_paginate_object: null,
            need_new_page: false,
        }
    },
    watch: {
        need_deleted: function (oldVal, newVal) {
            this.nextPages(0)
        },
        getPages: function (oldVal, newVal) {
            this.$nextTick(() => {
                if (!this.search)
                    this.pages = this.getPages
            })
        },
        search: function (oldVal, newVal) {
            this.nextPages(0)
        }
    },
    computed: {
        ...mapGetters(['getPages', 'getCurrentBot', 'getPagesPaginateObject']),
    },
    mounted() {
        this.loadCurrentBot().then(() => {
            if (this.bot)
                this.current_page = localStorage.getItem(`cashman_pagelist_${this.bot.id}_page_index`) || 0

            this.loadPages();
        })

    },
    methods: {
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot,

            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        selectPage(page) {
            this.$emit("callback", page)
            this.$notify("Вы выбрали страницу из списка! Все остальные действия будут производится для этой страницы");
        },
        nextPages(index) {

            this.current_page = index
            if (this.bot)
                localStorage.setItem(`cashman_pagelist_${this.bot.id}_page_index`, this.current_page)
            this.loadPages()
        },
        duplicatePage(id) {
            this.loading = true
            this.$store.dispatch("duplicatePage", {
                dataObject: {
                    pageId: id
                },
            }).then(resp => {
                this.loading = false
                this.loadPages()
            }).catch(() => {
                this.loading = false
            })
        },
        forceRemovePage(id) {
            this.loading = true
            this.$store.dispatch("forceRemovePage", {
                dataObject: {
                    pageId: id
                },
            }).then(resp => {
                this.loading = false

                this.loadPages()
            }).catch(() => {
                this.loading = false
            })
        },
        restorePage(id) {
            this.loading = true
            this.$store.dispatch("restorePage", {
                dataObject: {
                    pageId: id
                },
            }).then(resp => {
                this.loading = false
                this.loadPages()
            }).catch(() => {
                this.loading = false
            })
        },
        removePage(id) {
            this.loading = true
            this.$store.dispatch("removePage", {
                dataObject: {
                    pageId: id
                },
            }).then(resp => {
                this.loading = false
                this.loadPages()
            }).catch(() => {
                this.loading = false
            })
        },
        loadPages() {

            this.loading = true
            this.$store.dispatch("loadPages", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search || null,
                    needDeleted: this.need_deleted
                },
                page: this.current_page || 0
            }).then(resp => {

                this.loading = false
                this.pages = this.getPages
                this.pages_paginate_object = this.getPagesPaginateObject

                if (this.pages.length === 0)
                    localStorage.setItem(`cashman_pagelist_${this.bot.id}_page_index`, 0)

            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss">
.page-menu-item {
    strong {
        text-overflow: clip;
        word-wrap: break-word;
        text-align: left;
        //width: 200px;
        padding: 5px;
    }
}

.component-icons {
    display: flex;
    justify-content: start;
    // border-radius: 0px 5px 5px 0px;
    padding: 0;

    li {
        padding: 5px;
        cursor: pointer;
        display: flex;
        border: none;
        background-color: #088f4d;
        color: white;
        margin-right: 3px;
        font-size: 14px;
        border-radius: 0px;

    }
}
</style>
