<script setup>
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
import PlayerForm from "@/ClientTg/Components/V1/Shop/PlayerForm.vue";
</script>
<template>

    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <h4>Правила данного задания</h4>
                <p
                    class="alert-light alert"
                    v-if="rules">
                    {{ rules }}
                </p>

                <p
                    class="alert-light alert"
                    else>
                    Вам необходима загрузить скриншот из социальной сети, на котором видно что вы поделились нашим
                    рекламным банером и он находился у вас на странице какое-то время.
                </p>


                <p v-if="canPlay" class="text-center">Ваши попытки: <strong class="fw-bold text-primary">{{
                        action.current_attempts || 0
                    }}</strong> из <strong class="fw-bold text-primary">{{
                        action.max_attempts || 1
                    }}</strong></p>
                <p
                    @click="lose"
                    class="alert alert-light text-center fw-bold text-danger"
                    v-else>Вы израсходовали все ваши попытки</p>

                <div class="card" v-if="canPlay">
                    <div class="card-body">
                        <label for="photos"
                               v-if="!photo"
                               style="font-size:14px;min-height:300px;"
                               class="photo-loader my-2 d-flex justify-content-center align-items-center flex-column text-center w-100">
                            <i class="fa-brands fa-instagram"></i>
                            <input type="file" id="photos"
                                   accept="image/*" @change="onChangePhotos"
                                   style="display:none;"/>
                            <p class="mt-3 font-16"> Нажмите для выбора фотографии</p>
                        </label>
                        <div
                            style="font-size:14px;min-height:300px;"
                            class="img-preview w-100 overflow-hidden my-2"
                            v-if="photo">
                            <img v-lazy="getPhoto().imageUrl" class="w-100 object-fit-cover">
                            <div class="remove">
                                <a @click="removePhoto"
                                   class="btn btn-outline-light rounded-5 p-3  shadow-s">
                                    <i class="fa-regular fa-trash-can mr-2 text-primary"></i> Удалить фото</a>
                            </div>
                        </div>

                        <button
                            v-if="photo"
                            @click="submit"
                            type="button"
                            class="btn btn-primary p-3 w-100">
                            Получить приз
                        </button>


                    </div>
                </div>


            </div>
            <!--            <div class="col-12">
                            <div class="card" v-if="canPlay&&!hasProfileData">
                                <div class="card-body">
                                    <h3>Анкета участника акции</h3>

                                    <p>
                                        Для участия в конкурсе и дальнейшего получения приза необходимо заполнить данную анкету! Укажите своё
                                        имя и номер телефона чтоб менеджер
                                        мог выдать Вам приз по итогу.
                                    </p>
                                </div>

                                <div class="card-footer bg-transparent">
                                    <button type="button"
                                            @click="callbackPlayerForm"
                                            class="btn btn-primary w-100 p-3">
                                        Приступить к заданию
                                    </button>
                                </div>
                            </div>

                        </div>-->
        </div>
    </div>

    <!--    <CallbackForm/>-->


</template>
<script>

export default {
    name: "App",
    data() {
        return {
            rules: null,
            sending: false,
            action: null,
            photo: null,
            hasProfileData: false,
            instaForm: {
                comment: null,
                name: null,
                phone: null,
            },
        };
    },
    computed: {
        canPlay() {
            if (!this.action)
                return false

            return this.action.current_attempts < this.action.max_attempts
        },

    },
    mounted() {
        this.loadServiceData().then(() => {
            this.prepareUserData()
        })


    }
    ,
    methods: {
        lose() {
            this.$notify({
                title: 'Упс!',
                text: "Вы израсходовали все попытки!",
                type: "error"
            })
        },
        prepareUserData() {
            return this.$store.dispatch("instagramQuestPrepare").then((response) => {
                this.action = response.action

                if (!this.canPlay)
                    this.$notify({
                        title: 'Упс!',
                        text: "Вы израсходовали все попытки!",
                        type: "error"
                    })

            })
        },

        loadServiceData() {
            return this.$store.dispatch("instagramQuestLoadData").then((response) => {
                this.rules = response.rules
            })
        },

        removePhoto() {
            this.photo = null
        },
        getPhoto() {
            return {imageUrl: URL.createObjectURL(this.photo)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.photo = files[0]
            //this.success = true
        },
        callbackPlayerForm(form) {
            this.instaForm = {...this.instaForm, ...form}
            this.hasProfileData = true
        },
        submit() {
            let data = new FormData();

            Object.keys(this.instaForm)
                .forEach(key => {
                    const item = this.instaForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('photo', this.photo);

            this.$store.dispatch("instagramQuestResult", {
                instaForm: data,
            }).then((response) => {

                this.sending = false
                this.instaForm = {
                    comment: null,
                    name: null,
                    phone: null,
                }

                this.photo = null
                this.hasProfileData = false

                this.$notify({
                    title: 'Вы выиграли!',
                    text: "Наш менеджер свяжется с вами для дальнейших инструкций.",
                    type: "success"
                })

                setTimeout(() => {
                    this.prepareUserData()
                }, 5000)

            }).catch(err => {

            })

        },
    },
}
;
</script>
<style lang="scss" scoped>

.img-preview {
    position: relative;

    .photo-loader {
        width: 100px;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        background: white;
        border-radius: 10px;
        border: 1px lightgray solid;
        position: relative;
    }

    .remove {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #0000009e;
    }
}
</style>
