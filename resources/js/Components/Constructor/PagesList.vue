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
            <div class="col-12 mb-3">
                <ul class="list-group w-100">
                    <li class="list-group-item active cursor-pointer"
                        @click="selectPage(null)">Создать новую страницу</li>
                    <li class="list-group-item cursor-pointer d-flex justify-content-between"
                        v-for="(page, index) in pages"
                       >
                        <strong  @click="selectPage(page)">#{{ page.id || 'Не указано' }} {{ page.slug.command || 'Не указано' }}</strong>
                        <button type="button"
                                @click="removePage(page.id)"
                                class="btn btn-outline-danger">Удалить</button>
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
    props:["botId", "editor"],
    data() {
        return {
            loading: true,
            pages:[],
            search: null,
            pages_paginate_object:null,
        }
    },
    watch:{
        search: function(oldVal, newVal) {
            this.loadPages()
        }
    },
    computed: {
        ...mapGetters(['getPages', 'getPagesPaginateObject']),
    },
    mounted() {
        this.loadPages();
    },
    methods: {
        selectPage(page) {
            this.$emit("callback", page)
            this.$notify("Вы выбрали страницу из списка! Все остальные действия будут производится для этой страницы");
        },
        nextPages(index){
          this.loadPages(index)
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
                    botId: this.botId || null,
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
