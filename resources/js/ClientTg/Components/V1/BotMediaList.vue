<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';
</script>
<template>

    <div class="mb-2">

        <div class="form-floating">
            <input type="search"
                   v-model="search"
                   class="form-control" id="floatingInput" placeholder="Поиск файла">
            <label for="floatingInput">Поиск файла</label>
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

        <div class="card  border rounded-2 p-0 mb-2"
             v-bind:class="{'border-success':(selected||[]).indexOf(item.file_id) !=-1,'border-light':(selected||[]).indexOf(item.file_id) ==-1}"
             @click="selectMedia(item)"
             v-for="(item, index) in media"
        >
            <div class="card-body p-2">
                <div class="d-flex">
                    <i v-if="item.type==='video'||item.type==='video_note'"
                       class="fa-solid  p-2 fa-video"></i>

                    <i
                        v-if="item.type==='photo'"
                        class="fa-solid p-2 fa-camera  r"></i>

                    <i
                        v-if="item.type==='audio'"
                        class="fa-solid p-2 fa-circle-play  "></i>

                    <i
                        v-if="item.type==='voice'"
                        class="fa-solid p-2 fa-microphone-lines"></i>


                    <i
                        v-if="item.type==='document'"
                        class="fa-solid p-2  fa-file-word "></i>

                    <i
                        v-if="item.type==='sticker'"
                        class="fa-solid p-2  fa-note-sticky "></i>


                    <p class="d-flex justify-content-between p-1 w-100 mb-0"><strong>#{{ item.id }}</strong> <small>тип: {{ item.type ?? 'Без подписи' }}</small></p>






                </div>

                <p class="mb-1">  {{ item.caption ?? 'Без подписи' }}</p>
<!--                <p class="font-10 mb-1" style="line-height: 110%;">
                    {{ item.file_id }}
                </p>-->

                <div class="d-flex justify-content-between">

                    <a href="javascript:void(0)"
                       class="btn btn-outline-primary px-2 "
                       style="min-width: 40px;"
                       @click="showPreview(item.id)"><i class="fa-solid fa-eye"></i> посмотреть</a>
                    <a href="javascript:void(0)"
                       style="min-width: 40px;"
                       class="btn btn-outline-danger px-2 text-danger"
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
    props: ["selected", "needVideo", "needVideoNote", "needPhoto", "needDocument", "needAudio", "needSticker"],
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
