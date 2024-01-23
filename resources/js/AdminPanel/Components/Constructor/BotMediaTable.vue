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
                    @click="loadMedia(0)"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row" v-if="media.length>0">
        <div class="col-12 col-md-12 mb-3">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Подпись</th>
                    <th scope="col">File Id</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in media">
                    <th scope="row">{{ item.id }}</th>
                    <td>
                        <span class="badge bg-info rounded-pill">
                            {{ item.type }}
                        </span>
                    </td>
                    <td><p style="word-break: break-all;">{{ item.caption ?? 'Без подписи' }}</p></td>
                    <td><p style="word-break: break-all;">{{ item.file_id }}</p></td>
                    <td>
                        <div style="min-width:200px;">
                            <a href="javascript:void(0)" class="btn btn-info mr-2" @click="showPreview(item)"><i class="fa-solid fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-info mr-2" @click="showPreviewInTg(item.id)"><i class="fa-brands fa-telegram"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger text-danger" @click="remove(item.id)"><i class="fa-solid fa-trash"></i></a>
                        </div>

                    </td>
                </tr>

                </tbody>
            </table>

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


    <!-- Modal -->
    <div class="modal fade" id="preview-modal" tabindex="-1" aria-labelledby="preview-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div v-if="previewContent!=null" class="d-flex justify-content-center align-items-center">
                        <div class="video-circle" v-if="previewContent.type=='video_note'">
                            <video
                                v-if="previewContent.type==='video'||previewContent.type=='video_note'"
                                controls
                                poster="/images/load.gif">
                                <source
                                    :src="'/file-by-file-id/'+ previewContent.file_id"
                                    type="video/mp4"/>
                            </video>
                        </div>

                        <div v-if="previewContent.type==='video'">
                            <video
                                controls
                                poster="/images/load.gif">
                                <source
                                    :src="'/file-by-file-id/'+ previewContent.file_id"
                                    type="video/mp4"/>
                            </video>
                        </div>

                        <img v-if="previewContent.type=='photo'" v-lazy="'/file-by-file-id/'+ previewContent.file_id" alt="">
                        <audio v-if="previewContent.type==='audio'||previewContent.type==='voice'" controls
                               :src="'/file-by-file-id/'+ previewContent.file_id"></audio>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            bot: null,
            previewContent:null,
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
            this.loadMedia();
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
                    text: 'Медиа контент удален'
                })
            }).catch(() => {

            })
        },
        showPreview(media) {
            this.previewContent = null
            this.$nextTick(()=>{
                this.previewContent = media

                const previewModal = new bootstrap.Modal('#preview-modal', {})
                previewModal.show()
            })

        },
        showPreviewInTg(mediaId) {
            this.$store.dispatch("showMediaPreview", {
                dataObject: {
                    mediaId: mediaId,
                },
            }).then(resp => {
                this.$notify({
                    title: 'Превью медиа-контента',
                    text: 'Отправлено в бота'
                })
            }).catch(() => {

            })
        },
        loadMedia(page = 0) {
            this.loading = true
            this.$store.dispatch("loadMedia", {
                dataObject: {
                    bot_id: this.bot.id || null,
                    search: this.search,
                    needVideo: true,
                    needVideoNote: true,
                    needPhoto: true,
                    needAudio: true,
                    needDocument: true,
                    needSticker: true,
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
