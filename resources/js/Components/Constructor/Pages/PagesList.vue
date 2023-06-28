<script setup>
import Pagination from '@/Components/Pagination.vue';
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
                        id="button-addon2">Найти</button>
            </div>
        </div>

        <div class="row" v-if="pages.length>0">
<!--            <div class="col-12 mb-3">
                <button type="button" class="btn btn-outline-success w-100"
                        @click="selectPage(null)">Создать новую страницу</button>
            </div>-->
            <div class="col-12 mb-3">
                <ul class="list-group w-100">

                    <li class="list-group-item cursor-pointer page-menu-item btn btn-outline-info mb-1 d-flex justify-content-between"
                        @click="selectPage(page)"
                        v-for="(page, index) in pages"
                       >
                        <strong>#{{ page.id || 'Не указано' }} {{ page.slug.command || 'Не указано' }}
                        <span v-if="current&&current===page.id"><i class="fa-solid fa-lock"></i></span>
                        </strong>

                        <div v-if="editor">
                            <button type="button"
                                    @click="duplicatePage(page.id)"
                                    class="btn btn-outline-success mr-2"><i class="fa-solid fa-copy"></i></button>

                            <button type="button"
                                    @click="removePage(page.id)"
                                    class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </div>

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
    props:[ "editor", "current"],
    data() {
        return {
            bot:null,
            loading: true,
            pages:[],
            search: null,
            pages_paginate_object:null,
            need_new_page:false,
        }
    },
    watch:{
        search: function(oldVal, newVal) {
            this.loadPages()
        }
    },
    computed: {
        ...mapGetters(['getPages', 'getCurrentBot','getPagesPaginateObject']),
    },
    mounted() {
        this.loadCurrentBot().then(()=>{
            this.loadPages();
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
        selectPage(page) {
            this.$emit("callback", page)
            this.$notify("Вы выбрали страницу из списка! Все остальные действия будут производится для этой страницы");
        },
        nextPages(index){
          this.loadPages(index)
        },
        duplicatePage(id){
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
        removePage(id){
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
        loadPages(pageIndex = 0) {
            this.loading = true
            this.$store.dispatch("loadPages", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search
                },
                page: pageIndex
            }).then(resp => {
                this.loading = false
                this.pages = this.getPages
                this.pages_paginate_object = this.getPagesPaginateObject
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
        width: 200px;
        padding: 5px;
    }
}
</style>
