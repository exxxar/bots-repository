<script setup>
import CallbackForm from "@/Components/Shop/CallbackForm.vue";
import ReturnToBot from "@/Components/Shop/Helpers/ReturnToBot.vue";
import PlayerForm from "@/Components/Shop/PlayerForm.vue";
</script>
<template>
    <div class="card card-style" v-if="rules">
        <div class="content">
            <h4>Правила данного задания</h4>
            <p>
                {{ rules }}
            </p>

            <p v-if="canPlay">Ваши попытки: <strong>{{
                    action.current_attempts || 0
                }}</strong> из <strong>{{
                    action.max_attempts || 1
                }}</strong></p>
            <p
                @click="lose"
                style="font-weight:900; color:red;" v-else>Вы израсходовали все ваши попытки</p>
        </div>
    </div>

    <div class="card card-style" v-if="canPlay&&hasProfileData">
        <div class="content">
            <div class="card mb-3">
                <div class="card-body">
                    <label for="photos"
                           class="photo-loader ml-2 d-flex justify-content-center align-items-center flex-column text-center">
                        <i class="fa-brands fa-instagram font-36"></i>
                        <input type="file" id="photos"
                               accept="image/*" @change="onChangePhotos"
                               style="display:none;"/>
                        <p class="mt-3 font-24"> Нажмите для выбора фотографии</p>
                    </label>
                    <div class="img-preview"
                         v-if="photo">
                        <img v-lazy="getPhoto().imageUrl" class="w-100 object-fit-cover">
                        <div class="remove mt-3">
                            <a @click="removePhoto"
                               class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s border-danger text-danger">
                                <i class="fa-regular fa-trash-can"></i> Удалить фото</a>
                        </div>
                    </div>
                </div>
            </div>

            <button
                v-if="photo"
                @click="submit"
                type="button"
                class="btn btn-m btn-full rounded-xs text-uppercase font-900 shadow-s bg-green1-light w-100 mt-3">
               Получить приз
            </button>

            <ReturnToBot class="mb-3"/>
        </div>
    </div>


    <PlayerForm v-if="canPlay&&!hasProfileData"
                v-on:callback="callbackPlayerForm"/>

    <CallbackForm/>



</template>
<script>

import baseJS from '@/modules/custom.js'

export default {
    name: "App",
    data() {
        return {
            rules: null,
            sending: false,
            action: null,
            photo: null,
            hasProfileData:false,
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
            this.$botNotification.warning("Упс!", "Вы израсходовали все попытки!")
        },
        prepareUserData() {
            return this.$store.dispatch("instagramQuestPrepare").then((response) => {
                this.action = response.action

                if (!this.canPlay)
                    this.$botNotification.warning("Упс!", "Вы израсходовали все попытки!")
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
        callbackPlayerForm(form){
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

                this.$botNotification.success("Вы выиграли!", "Наш менеджер свяжется с вами для дальнейших инструкций.")

                setTimeout(()=>{
                    this.prepareUserData()
                }, 5000)

            }).catch(err => {

            })

        },
    },
}
;
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 14px;
    font-weight: 900;
    margin: 0 !important;
}

</style>
