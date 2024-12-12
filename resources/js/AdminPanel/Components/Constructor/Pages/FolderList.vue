<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск папки \ страницы"
                   aria-label="Поиск бота"
                   v-model="search"
                   aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary"
                    @click="loadFolders"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>

    <div class="row" v-if="folders.length>0">
        <!--            <div class="col-12 mb-3">
                        <button type="button" class="btn btn-outline-success w-100"
                                @click="selectFolder(null)">Создать новую страницу</button>
                    </div>-->

        <div class="col-12" v-if="folders.length>7">
            <Pagination
                v-on:pagination_folder="nextFolders"
                v-if="folders_paginate_object"
                :pagination="folders_paginate_object"/>
        </div>

        <div class="col-12 mb-3">
            <ul class="list-group w-100">

                <li class="list-group-item cursor-pointer folder-menu-item btn btn-outline-info mb-1"
                    v-bind:class="{'border border-warning':folder.deleted_at!=null}"
                    v-for="(folder, index) in folders"
                >
                    <div class=" d-flex justify-content-between ">

                        <strong
                            @click="selectFolder(folder)">#{{ folder.id || 'Не указано' }}
                            <span v-if="folder.slug">{{ folder.title || 'Не указано' }}</span>

                            <i class="fa-solid fa-check-double text-danger ml-2"></i>

                        </strong>


                        <div v-if="editor">

                            <div class="dropdown">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li v-if="folder.deleted_at != null"><a class="dropdown-item"
                                                                          @click="restoreFolder(folder.id)"><i
                                        class="fa-solid fa-copy mr-1"></i>Восстановить</a></li>
                                    <li v-if="folder.deleted_at != null"><a class="dropdown-item"
                                                                          @click="forceRemoveFolder(folder.id)">
                                        <i class="fa-solid fa-ban mr-1"></i>Удалить полностью</a></li>
                                    <li><a class="dropdown-item" @click="duplicateFolder(folder.id)"><i
                                        class="fa-solid fa-copy mr-1"></i>Дублировать</a></li>
                                    <li v-if="folder.deleted_at == null"><a class="dropdown-item"
                                                                          @click="removeFolder(folder.id)"><i
                                        class="fa-solid fa-trash mr-1"></i>Удалить</a></li>

                                </ul>
                            </div>

                        </div>

                    </div>
                </li>
            </ul>

        </div>

        <div class="col-12">
            <Pagination
                v-on:pagination_folder="nextFolders"
                v-if="folders_paginate_object"
                :pagination="folders_paginate_object"/>
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
    props: ["editor", "current", "selected"],
    data() {
        return {
            bot: null,
            current_page: 0,
            loading: true,
            folders: [],
            search: null,
            folders_paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getPageFolders', 'getCurrentBot', 'getPageFoldersPaginateObject']),
    },
    mounted() {
        this.loadCurrentBot().then(() => {
            if (this.bot)
                this.current_page = localStorage.getItem(`cashman_folderlist_${this.bot.id}_folder_index`) || 0

            this.loadFolders();
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
        selectFolder(folder) {
            this.$emit("callback", folder)

        },
        nextFolders(index) {
            this.current_page = index
            if (this.bot)
                localStorage.setItem(`cashman_folderlist_${this.bot.id}_folder_index`, this.current_page)
            this.loadFolders()
        },
        duplicateFolder(id) {
            this.loading = true
            this.$store.dispatch("duplicateFolder", {
                dataObject: {
                    folderId: id
                },
            }).then(resp => {
                this.loading = false
                this.loadFolders()
            }).catch(() => {
                this.loading = false
            })
        },
        forceRemoveFolder(id) {
            this.loading = true
            this.$store.dispatch("forceRemoveFolder", {
                dataObject: {
                    folderId: id
                },
            }).then(resp => {
                this.loading = false

                this.loadFolders()
            }).catch(() => {
                this.loading = false
            })
        },
        restoreFolder(id) {
            this.loading = true
            this.$store.dispatch("restoreFolder", {
                dataObject: {
                    folderId: id
                },
            }).then(resp => {
                this.loading = false
                this.loadFolders()
            }).catch(() => {
                this.loading = false
            })
        },
        removeFolder(id) {
            this.loading = true
            this.$store.dispatch("removeFolder", {
                dataObject: {
                    folderId: id
                },
            }).then(resp => {
                this.loading = false
                this.loadFolders()
            }).catch(() => {
                this.loading = false
            })
        },
        loadFolders() {

            this.loading = true
            this.$store.dispatch("loadPageFolders", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search || null,
                },
                page: this.current_page || 0
            }).then(resp => {

                this.loading = false
                this.folders = this.getPageFolders
                this.folders_paginate_object = this.getPageFoldersPaginateObject

                if (this.folders.length === 0)
                    localStorage.setItem(`cashman_folderlist_${this.bot.id}_folder_index`, 0)

            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss">
.folder-menu-item {
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
