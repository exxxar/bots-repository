<script setup>

</script>
<template>

    <div class="container py-3" v-if="self">
        <h3>Обратная связь</h3>

        <p class="alert alert-light">
            Уважаемый клиент, ваш отзыв будет опубликован в нашем канале <strong
            class="fw-bold text-primary">анонимно</strong>, ваши данные увидит только администрация проекта, заранее
            благодарим за обратную связь!
        </p>

        <form v-on:submit.prevent="submitCallback">


            <div class="form-floating mb-2">
                <input type="text"
                       v-model="callbackForm.name"
                       class="form-control"
                       id="callbackForm-name"
                       placeholder="name@example.com" required>
                <label for="callbackForm-name">Ваше имя
                    <span class="text-danger">*</span>
                </label>
            </div>


            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="'+7(###)###-##-##'"
                       v-model="callbackForm.phone"
                       class="form-control"
                       id="callbackForm-phone"
                       placeholder="name@example.com" required>
                <label for="callbackForm-phone">Номер телефона
                    <span class="text-danger">*</span>
                </label>
            </div>


            <div class="form-floating">
                    <textarea class="form-control"
                              style="height:200px;line-height:150%;"
                              v-model="callbackForm.message"
                              placeholder="Leave a comment here"
                              id="callbackForm-message"></textarea>
                <label for="callbackForm-message" required>Текст сообщения
                    <span class="text-danger">*</span>
                </label>
            </div>

            <h6 class="my-3 text-center fw-bold">Прикрепить фотографию
                <span class="text-danger">*</span>
            </h6>
            <div class="photo-preview d-flex justify-content-center flex-wrap w-100 my-3">
                <label for="menu-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                    <span class="text-primary fw-bold">+</span>
                    <input type="file" id="menu-photos" accept="image/*"
                           required
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

            <p
                v-if="!callbackForm.image"
                class="alert alert-light mb-2">
                Вам необходимо прикрепить изображение перед отправкой!
            </p>

            <button type="submit"
                    :disabled="sending&&(callbackForm.images||[]).length===0"
                    class="btn btn-primary mt-2 p-3 w-100">
                Отправить сообщение
            </button>
        </form>


    </div>

</template>
<script>


import {mapGetters} from "vuex";

export default {
    data() {
        return {
            sending: false,
            callbackForm: {
                name: null,
                phone: null,
                message: null,
                images: []
            },
        }
    },
    watch: {
        'getSelf': {
            handler: function (newValue) {

                this.callbackForm.name = this.self.fio_from_telegram || this.self.name
                this.callbackForm.phone = this.self.phone || null


            },
            deep: true
        },

    },
    computed: {
        ...mapGetters(['getSelf']),
        self() {
            return this.getSelf
        }
    },
    mounted() {

        if (this.self) {
            this.callbackForm.name = this.self.fio_from_telegram || this.self.name
            this.callbackForm.phone = this.self.phone || null
        }

    },
    methods: {
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.callbackForm.images.push(files[i])
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.callbackForm.images.splice(index, 1)
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

            this.$store.dispatch("feedBackForm", {
                callbackForm: data

            }).then((response) => {

                this.callbackForm = {
                    message: null,
                    name: null,
                    phone: null,
                    images: []
                }

                this.$notify({
                        title: "Обратная связь",
                        text: "Спасибо за сообщение",
                    },
                );

                this.sending = false
            }).catch(err => {
                this.sending = false
            })
        },
    }
}
</script>
