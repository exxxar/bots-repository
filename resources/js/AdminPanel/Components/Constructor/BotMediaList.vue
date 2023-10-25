<script setup>
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск файла"
                   aria-label="Поиск файла"
                   v-model="search">
            <button class="btn btn-outline-secondary"
                    @click="loadMedia"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row" v-if="media.length>0">
        <div class="col-12 col-md-12 mb-3">

            <ul class="list-group">
                <li @click="selectMedia(item)"
                    v-bind:class="{'border-info':selected === item.file_id}"
                    class="list-group-item d-flex justify-content-between align-items-start"
                    v-for="(item, index) in media">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ item.caption ?? 'Без подписи' }} <span
                            class="badge bg-info rounded-pill">{{ item.type }}</span></div>
                        <small>{{ item.file_id }}</small>
                        <a href="#" class="btn btn-link" @click="showPreview(item.id)">Показать превью</a>
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ item.id }}</span>
                </li>
            </ul>

        </div>

        <div class="col-12">
            <Pagination

                v-on:pagination_page="nextMedia"
                v-if="media_paginate_object"
                :pagination="media_paginate_object"/>
        </div>

    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                У выбранного бота нет медиа-файлов
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["selected"],
    data() {
        return {
            bot: null,
            needVideo: false,
            needVideoNote: false,
            needPhoto: false,
            loading: true,
            media: [],
            search: null,
            media_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getMedia', 'getMediaPaginateObject', 'getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot().then(() => {
            this.loadUsers();
        })

    },
    methods: {
        selectMedia(item) {
            this.$emit("select", item)
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        nextMedia(index) {
            this.loadMedia(index)
        },
        remove(mediaId) {
            this.$store.dispatch("removeMedia", {
                dataObject: {
                    mediaId: mediaId,
                },
            }).then(resp => {

                this.loadMedia()

                this.$notify({
                    title: 'Удаление медиа-контента',
                    message: 'Медиа контент удален'
                })
            }).catch(() => {

            })
        },
        showPreview(mediaId) {
            this.$store.dispatch("showMediaPreview", {
                dataObject: {
                    mediaId: mediaId,
                },
            }).then(resp => {
                this.$notify({
                    title: 'Превью медиа-контента',
                    message: 'Отправлено в бота'
                })
            }).catch(() => {

            })
        },
        loadMedia(page = 0) {
            this.loading = true
            this.$store.dispatch("loadMedia", {
                dataObject: {
                    botId: this.bot.id || null,
                    search: this.search,
                    needVideo: this.needVideo,
                    needVideoNote: this.needVideoNote,
                    needPhoto: this.needPhoto,
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.media = this.getMedia
                this.media_paginate_object = this.getMediaPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
