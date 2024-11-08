<script setup>

</script>
<template>

    <div class="container py-3" v-if="self">
        <h3>Загрузка файлов</h3>

        <p class="alert alert-light">
            Внимание! Вы можете загрузить до <strong class="fw-bold text-primary">10</strong> фотографий, <strong
            class="fw-bold text-primary">1</strong> видео-ролик и <strong class="fw-bold text-primary">1</strong>
            документ.
        </p>

        <form v-on:submit.prevent="submitCallback">


            <h6 class="my-2 text-center fw-bold">Прикрепить фотографию
            </h6>

            {{test||null}}

            <div class="form-floating mb-2">
                <input type="file" id="menu-photos-upload" accept="image/*"
                       @change="onChangePhotos"
                       class="form-control"
                       ref="file" multiple="multiple"
                       :disabled="(callbackForm.images||[]).length===10"
                       placeholder="name@example.com"
                >
                <label for="menu-photos-upload">Фотографии</label>
            </div>

            <ol class="list-group list-group-numbered mb-2">
                <li class="list-group-item d-flex justify-content-between align-items-start"
                    v-for="(photo, index) in callbackForm.images">
                    <div class="ms-2 me-auto" style="word-break: break-all;">
                        {{ photo.name || 'не указан' }}
                    </div>
                    <span
                        @click="removePhoto(index)"
                        class="badge text-bg-primary rounded-pill cursor-pointer">
                        удалить
                    </span>
                </li>
            </ol>

            <div class="form-check mb-2">
                <input class="form-check-input"
                       v-model="need_video"
                       type="checkbox" value="" id="need_attach_video">
                <label class="form-check-label" for="need_attach_video">
                    Нужно прикрепить видео
                </label>
            </div>

            <template v-if="need_video">
                <h6 class="mb-2 text-center fw-bold">Прикрепить видео</h6>

                <div class="form-floating mb-2">
                    <input type="file" id="menu-video" accept="video/*"
                           :disabled="(callbackForm.videos||[]).length===1"
                           @change="onChangeVideos"
                           class="form-control"
                           placeholder="name@example.com">
                    <label for="menu-video">Видео</label>
                </div>

                <ol class="list-group list-group-numbered mb-2">
                    <li class="list-group-item d-flex justify-content-between align-items-start"
                        v-for="(video, index) in callbackForm.videos">
                        <div class="ms-2 me-auto" style="word-break: break-all;">
                            {{ video.name || 'не указан' }}
                        </div>
                        <span
                            @click="removeVideo(index)"
                            class="badge text-bg-primary rounded-pill cursor-pointer">
                        удалить
                    </span>
                    </li>
                </ol>
            </template>

            <div class="form-check mb-2">
                <input class="form-check-input"
                       v-model="need_document"
                       type="checkbox" value="" id="need_attach_file">
                <label class="form-check-label" for="need_attach_file">
                    Нужно прикрепить файл
                </label>
            </div>

            <template v-if="need_document">
                <h6 class="mb-2 text-center fw-bold">Прикрепить файл</h6>

                <div class="form-floating mb-2">
                    <input type="file" id="menu-document" accept="application/pdf"
                           :disabled="(callbackForm.documents||[]).length===1"
                           @change="onChangeDocuments"
                           class="form-control"
                           placeholder="name@example.com">
                    <label for="menu-document">Документ</label>
                </div>

                <ol class="list-group list-group-numbered mb-2">
                    <li class="list-group-item d-flex justify-content-between align-items-start"
                        v-for="(doc, index) in callbackForm.documents">
                        <div class="ms-2 me-auto" style="word-break: break-all;">
                            {{ doc.name || 'не указан' }}
                        </div>
                        <span
                            @click="removeDocument(index)"
                            class="badge text-bg-primary rounded-pill cursor-pointer">
                        удалить
                    </span>
                    </li>
                </ol>

            </template>

            <div class="form-floating">
                    <textarea class="form-control"
                              style="height:200px;line-height:150%;"
                              v-model="callbackForm.message"
                              placeholder="Leave a comment here"
                              id="callbackForm-message"></textarea>
                <label for="callbackForm-message">Пояснение</label>
            </div>

            <nav

                class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
                style="border-radius:10px 10px 0px 0px;">
                <button type="submit"
                        :disabled="!canSend"
                        class="btn btn-primary mt-2 p-3 w-100">
                    Отправить сообщение
                </button>
            </nav>
        </form>


    </div>

</template>
<script>

import {mapGetters} from "vuex";

export default {

    data() {
        return {
            file: null,
            sending: false,
            need_video: false,
            need_document: false,
            callbackForm: {
                message: null,
                images: [],
                videos: [],
                documents: []
            },
        }
    },
    watch: {
        'need_video': {
            handler: function (newValue) {
                if (!this.need_video)
                    this.callbackForm.videos = []
            },
            deep: true
        },
        'need_document': {
            handler: function (newValue) {
                if (!this.need_video)
                    this.callbackForm.documents = []
            },
            deep: true
        },
    },
    computed: {
        ...mapGetters(['getSelf']),
        self() {
            return this.getSelf
        },
        test(){
            let tmp = "";
            if (!this.$refs.file)
                return "";

            if ((this.$refs.file.files||[]).length===0)
                return "";

            for (let i = 0; i < this.$refs.file.files.length; i++ ){
                let file = this.$refs.file.files[i];
                tmp = 'files[' + i + ']'
            }

            return tmp
        },
        canSend() {
            return !this.sending && ((this.callbackForm.images || []).length > 0 ||
                (this.callbackForm.videos || []).length > 0 ||
                (this.callbackForm.documents || []).length > 0)
        },
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {

    },
    methods: {
        onChangePhotos(e) {
            const files = e.target.files || []

            for (let i = 0; i < files.length; i++)
                this.callbackForm.images.push(files[i])
        },
        onChangeVideos(e) {
            const files = e.target.files || []
            for (let i = 0; i < files.length; i++) {
                this.callbackForm.videos.push(files[i])

            }

        },
        onChangeDocuments(e) {
            const files = e.target.files || []
            for (let i = 0; i < files.length; i++)
                this.callbackForm.documents.push(files[i])
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.callbackForm.images.splice(index, 1)
        },
        removeVideo(index) {
            this.callbackForm.videos.splice(index, 1)
        },
        removeDocument(index) {
            this.callbackForm.documents.splice(index, 1)
        },
        onAdvancedUpload(e){

        },
        submitCallback() {
            let data = new FormData();

            this.sending = true
            Object.keys(this.callbackForm)
                .forEach(key => {
                    const item = this.callbackForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.callbackForm.images.length > 0) {
                this.callbackForm.images.forEach(img => {
                    data.append('photos[]', img);
                })

                data.delete("images")
            }

            if (this.callbackForm.documents.length > 0) {
                this.callbackForm.documents.forEach(doc => {
                    data.append('documents[]', doc);
                })

                data.delete("documents")
            }

            if (this.callbackForm.videos.length > 0) {
                this.callbackForm.videos.forEach(video => {
                    data.append('videos[]', video);
                })

                data.delete("videos")
            }

            this.$store.dispatch("uploadFileForm", {
                callbackForm: data

            }).then((response) => {

                this.callbackForm = {
                    message: null,
                    videos: [],
                    documents: [],
                    images: []
                }

                this.$notify({
                        title: "Загрузка файлов",
                        text: "Спасибо, файлы загружены",
                    },
                );

                this.sending = false

                this.tg.close()
            }).catch(err => {
                this.sending = false
            })
        },
    }
}
</script>
