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
            <div class="photo-preview d-flex justify-content-center flex-wrap w-100 my-2">
                <label for="menu-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                    <span class="text-primary fw-bold">+</span>
                    <input type="file" id="menu-photos" accept="image/*"
                           multiple
                           max="10"
                           @change="onChangePhotos"
                           style="display:none;"/>

                </label>
                <div class="mb-2 img-preview" style="margin-right: 10px;"
                     v-for="(img, index) in callbackForm.images"
                     v-if="(callbackForm.images||[]).length>0">
                    <img v-lazy="getPhoto(img).imageUrl">
                    <div class="remove">
                        <a @click="removePhoto(index)">Удалить</a>
                    </div>
                </div>

            </div>
            <h6 class="my-2 text-center fw-bold">Прикрепить видео</h6>

            <div class="form-floating mb-2">
                <input type="file" id="menu-video" accept="video/*"
                       max="10"
                       @change="onChangeVideos"
                       class="form-control"
                       placeholder="name@example.com">
                <label for="menu-video">Видео</label>
            </div>

            <ol class="list-group list-group-numbered mb-2">
                <li class="list-group-item d-flex justify-content-between align-items-start"
                    v-for="video in callbackForm.videos">
                    <div class="ms-2 me-auto" style="word-break: break-all;">
                        {{ video.name || 'не указан' }}
                    </div>
                    <span class="badge text-bg-primary rounded-pill">
                        удалить
                    </span>
                </li>
            </ol>

            <h6 class="my-3 text-center fw-bold">Прикрепить файл</h6>

            <div class="form-floating mb-2">
                <input type="file" id="menu-document" accept="application/pdf"
                       max="10"
                       @change="onChangeDocuments"
                       class="form-control"
                       placeholder="name@example.com">
                <label for="menu-document">Документ</label>
            </div>

            <ol class="list-group list-group-numbered mb-2">
                <li class="list-group-item d-flex justify-content-between align-items-start"
                    v-for="doc in callbackForm.documents">
                    <div class="ms-2 me-auto" style="word-break: break-all;">
                        {{ doc.name || 'не указан' }}
                    </div>
                    <span class="badge text-bg-primary rounded-pill">
                        удалить
                    </span>
                </li>
            </ol>


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
            callbackForm: {
                message: null,
                images: [],
                videos: [],
                documents: []
            },
        }
    },

    computed: {
        ...mapGetters(['getSelf']),
        self() {
            return this.getSelf
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
            const files = e.target.files


            for (let i = 0; i < Math.min(files.length, 9); i++)
                this.callbackForm.images.push(files[i])
        },
        onChangeVideos(e) {
            const files = e.target.files
            for (let i = 0; i < Math.min(files.length, 9); i++) {
                this.callbackForm.videos.push(files[i])

            }

        },
        onChangeDocuments(e) {
            const files = e.target.files
            for (let i = 0; i < Math.min(files.length, 9); i++)
                this.callbackForm.documents.push(files[i])
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.locationForm.images.splice(index, 1)
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
