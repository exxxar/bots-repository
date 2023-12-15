<script setup>
import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>

    <div class="mb-2">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск файла"
                   aria-label="Поиск файла"
                   v-model="search">

        </div>

        <button
            v-if="search"
            class="btn btn-outline-secondary w-100"
                @click="loadMedia(0)"
                type="button"
                id="button-addon2">Найти
        </button>
    </div>
    <div class="mb-2" v-if="media.length>0">

        <div class="card card-style mx-0 my-1 rounded-s "
             v-bind:class="{'border-green2-dark bordered':selected.indexOf(item.file_id) !=-1}"
             @click="selectMedia(item)"
             v-for="(item, index) in media"
        >
            <div class="content mx-3 my-3">
                <h6>
                    <i v-if="item.type==='video'||item.type==='video_note'"
                       class="fa-solid font-14 p-2 mr-2 fa-video rounded-xl shadow-xl bg-blue2-dark"></i>

                    <i
                        v-if="item.type==='photo'"
                        class="fa-solid p-2 mr-2 font-14 fa-camera  rounded-xl shadow-xl bg-blue2-dark"></i>

                    <i
                        v-if="item.type==='audio'"
                        class="fa-solid p-2 mr-2 font-14 fa-circle-play  rounded-xl shadow-xl bg-blue2-dark"></i>

                    <i
                        v-if="item.type==='voice'"
                        class="fa-solid p-2 mr-2 font-14 fa-microphone-lines rounded-xl shadow-xl bg-blue2-dark"></i>


                    <i
                        v-if="item.type==='document'"
                        class="fa-solid p-2 mr-2 font-14 fa-file-word rounded-xl shadow-xl bg-blue2-dark"></i>

                    <i
                        v-if="item.type==='sticker'"
                        class="fa-solid p-2 mr-2 font-14 fa-note-sticky rounded-xl shadow-xl bg-blue2-dark"></i>


                    {{ item.caption ?? 'Без подписи' }}
                    <small>({{ item.type ?? 'Без подписи' }})</small>




                </h6>
                <p class="font-6 mb-1">Идентификатор в системе: <strong>#{{item.id}}</strong></p>
                <p class="font-10 mb-1" style="line-height: 110%;">
                  {{ item.file_id }}
                </p>

                <div class="d-flex justify-content-start">

                    <a href="javascript:void(0)"
                       class="btn btn-outline-info px-2 "
                       style="min-width: 40px;"
                       @click="showPreview(item.id)"><i class="fa-solid fa-eye"></i></a>
                    <a href="javascript:void(0)"
                       style="min-width: 40px;"
                       class="btn btn-outline-danger px-2 mx-2 text-danger"
                       @click="remove(item.id)"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
        </div>
        <Pagination

            v-on:pagination_page="nextMedia"
            v-if="media_paginate_object"
            :pagination="media_paginate_object"/>


    </div>
    <div class="mb-2" v-else>

        <div class="alert alert-warning" role="alert">
            У выбранного бота нет медиа-файлов
        </div>

    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["selected", "needVideo", "needVideoNote", "needPhoto", "needDocument", "needAudio","needSticker"],
    data() {
        return {
            bot: null,

            loading: true,
            media: [],
            search: null,
            media_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getMedia', 'getMediaPaginateObject']),
    },
    mounted() {
        this.loadMedia();
    },
    methods: {
        selectMedia(item) {
            this.$emit("select", item)
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

                this.$botNotification.notification(
                    'Удаление медиа-контента',
                    'Медиа контент удален'
                )

            }).catch(() => {

            })
        },
        showPreview(mediaId) {
            this.$store.dispatch("showMediaPreview", {
                dataObject: {
                    mediaId: mediaId,
                },
            }).then(resp => {
                this.$botNotification.notification(
                    'Превью медиа-контента',
                    'Отправлено в бота'
                )
            }).catch(() => {

            })
        },
        loadMedia(page = 0) {
            this.loading = true
            this.$store.dispatch("loadMedia", {
                dataObject: {
                    search: this.search,
                    needVideo: this.needVideo || false,
                    needVideoNote: this.needVideoNote || false,
                    needPhoto: this.needPhoto || false,
                    needAudio: this.needAudio || false,
                    needDocument: this.needDocument || false,
                    needSticker: this.needSticker || false,
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
