<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';

</script>
<template>

    <div class="form-floating mb-2">

        <input type="text"
               v-model="search"
               class="form-control" placeholder="Поиск страницы">
        <label for="">  <i class="fa fa-search"></i> Поиск страниц</label>
    </div>

    <div class="form-floating my-2">
        <select
            @change="loadPages(0)"
            v-model="sort.param"
            class="form-select" id="floatingSelect" aria-label="Floating label select example">
            <option value="id">По номеру страницы</option>
            <option value="updated_at">По дате добавления</option>
        </select>
        <label for="floatingSelect">Сортировать по...</label>
    </div>
    <p v-if="sort.param!=null">Направление сортировки:
        <span
            class="fw-bold"
            @click="changeDirection('desc')"
            v-if="sort.direction==='asc'">по возрастанию <i class="fa-solid fa-caret-up"></i></span>
        <span
            class="fw-bold"
            @click="changeDirection('asc')"
            v-if="sort.direction==='desc'">по убыванию <i class="fa-solid fa-caret-down"></i></span>
    </p>

    <button class="btn btn-primary p-3 mb-3 w-100"
            @click="loadPages"
            type="button"
            id="button-addon2">Найти
    </button>


    <ul v-if="pages.length>0"
        class="list-group w-100">

        <li
            v-bind:class="{'bg-danger text-white':page.before_deleted,'bg-success text-white':page.before_duplicate}"
            class="list-group-item cursor-pointer px-2 py-2 border-light"

            v-for="(page, index) in pages"
        >
            <div class=" d-flex justify-content-between align-items-center">
                <strong @click="selectPage(page)"  >
                    <span class="badge bg-primary mr-2 rounded-5" style="font-size:10px;">#{{ page.id || 'Не указано' }}</span>
                    <span v-if="page.slug" >{{ page.slug.command || 'Не указано' }}</span>
                    <span v-if="current&&current===page.id"><i class="fa-solid fa-lock"></i></span>

                    <ul
                        class="component-icons mt-2">
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
                </strong>

                <div class="dropdown"
                     v-if="editor">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"
                               @click="duplicatePage(page.id)"
                               href="javascript:void(0)">Дублировать</a></li>
                        <li><a class="dropdown-item"
                               @click="removePage(page.id)"
                               href="javascript:void(0)">Удалить</a></li>
                    </ul>
                </div>



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
    props: ["current", "editor"],
    data() {
        return {
            bot: null,
            loading: true,
            pages: [],
            sort: {
                param: 'id',
                direction: 'desc'
            },
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
            this.loadPages();
        });
    },
    methods: {
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadOrders(0)
        },
        duplicatePage(id) {

            let preparedIndex = this.pages.findIndex(item=>item.id===id)

            if (preparedIndex!==-1)
                this.pages[preparedIndex].before_duplicate = true

            this.$store.dispatch("duplicatePage", {
                dataObject: {
                    pageId: id
                },
            }).then(resp => {

                this.$notify({
                    title: 'Управление страница',
                    text: 'Страница успешно продублирована!',
                    type: 'success'
                })

                this.sort.param = "id"
                this.sort.direction = "desc"

                this.loadPages()
            }).catch(() => {
                this.$notify({
                    title: 'Управление страница',
                    text: 'Ошибка дублирования страницы!',
                    type: 'error'
                })
            })
        },
        removePage(id) {

            let preparedIndex = this.pages.findIndex(item=>item.id===id)

            if (preparedIndex!==-1)
                this.pages[preparedIndex].before_deleted = true

            this.$store.dispatch("removePage", {
                dataObject: {
                    pageId: id
                },
            }).then(resp => {
                this.$notify({
                    title: 'Управление страница',
                    text: 'Страница успешно удалена!',
                    type: 'success'
                })

                this.loadPages()
            }).catch(() => {
                this.$notify({
                    title: 'Управление страница',
                    text: 'Ошибка удаления страницы!',
                    type: 'error'
                })
            })
        },
        selectPage(page) {
            this.$emit("callback", page)
        },
        nextPages(index) {
            this.loadPages(index)
        },

        loadPages(pageIndex = 0) {

            this.$store.dispatch("loadPages", {
                dataObject: {
                    search: this.search,
                    order_by: this.sort.param || null,
                    direction: this.sort.direction || 'asc'
                },
                page: pageIndex
            }).then(resp => {

                this.pages = this.getPages
                this.pages_paginate_object = this.getPagesPaginateObject
            }).catch(() => {

            })
        }
    }
}
</script>
<style lang="scss">
.page-menu-item {
    border-radius: 10px !important;
    margin-bottom: 5px !important;
    border: 1px green solid !important;
    color: black;
    padding: 5px !important;

    strong {
        text-overflow: clip;
        word-wrap: break-word;
        text-align: left;
        width: 200px;
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
        font-size: 10px;
        border-radius: 5px;

    }
}
</style>
