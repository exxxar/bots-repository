<script setup>
import Pagination from '@/ClientTg/Components/Pagination.vue';

</script>
<template>

    <div class="search-box bg-theme rounded-xs shadow-xs bottom-0 mb-2">
        <i class="fa fa-search"></i>
        <input type="text"
               v-model="search"
               class="border-0" placeholder="Поиск страницы">
    </div>


    <button class="bg-highlight btn btn-m font-900 text-uppercase btn-center-xl mb-3 w-100"
            @click="loadPages"
            type="button"
            id="button-addon2">Найти
    </button>


    <ul v-if="pages.length>0"
        class="list-group w-100">

        <li class="list-group-item cursor-pointer page-menu-item btn btn-outline-info mb-1"

            v-for="(page, index) in pages"
        >
            <div class=" d-flex justify-content-between align-items-center">
                <strong @click="selectPage(page)">#{{ page.id || 'Не указано' }}
                    <span v-if="page.slug">{{ page.slug.command || 'Не указано' }}</span>
                    <span v-if="current&&current===page.id"><i class="fa-solid fa-lock"></i></span>
                </strong>

                <button
                    v-if="editor"
                    class="btn btn-outline-secondary" type="button"
                        @click="openPageMenuModal(page.id)"
                        aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>



            </div>


        </li>
    </ul>


    <Pagination
        :simple="true"
        v-on:pagination_page="nextPages"
        v-if="pages_paginate_object"
        :pagination="pages_paginate_object"/>

    <div class="alert alert-warning" role="alert" v-else>
        Созданных страниц не найдено!
    </div>




</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: [ "current","editor"],
    data() {
        return {
            bot: null,
            loading: true,
            pages: [],
            search: null,
            pages_paginate_object: null,
            need_new_page: false,
        }
    },
  /*  watch: {
        search: function (oldVal, newVal) {
            this.loadPages()
        }
    },*/
    computed: {
        ...mapGetters(['getPages', 'getPagesPaginateObject']),
    },
    mounted() {
        this.loadPages();


        window.addEventListener("reload-page-list", (e) => {
            console.log("Test reload-page-list")
            this.loadPages();
        } );
    },
    methods: {
        openPageMenuModal(pageId){
            this.$botPages.open(pageId)
        },
        selectPage(page) {
            this.$emit("callback", page)
            this.$notify("Вы выбрали страницу из списка! Все остальные действия будут производится для этой страницы");
        },
        nextPages(index) {
            this.loadPages(index)
        },

        loadPages(pageIndex = 0) {
            this.loading = true
            this.$store.dispatch("loadPages", {
                dataObject: {
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
