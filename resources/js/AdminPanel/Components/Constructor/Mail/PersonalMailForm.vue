<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";

import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
</script>

<template>
    <form v-on:submit.prevent="submitMail" v-if="bot">

        <div class="col-12 mb-2">
            <label class="form-label d-flex justify-content-between align-items-center mb-2" id="bot-domain">
                Текстовое содержимое рассылки
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>

            </label>


            <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="mailForm.message"
                                          maxlength="4096"
                                          placeholder="Введите текст"
                                          id="floatingTextarea2" style="min-height: 100px" required></textarea>
                <label for="floatingTextarea2">Содержимое страницы <span
                    v-if="mailForm.message">{{ mailForm.message.length }}/4096 </span></label>
            </div>

        </div>


        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_page_images"
                       type="checkbox"
                       id="need-page-images">
                <label class="form-check-label" for="need-page-images">
                    Изображения в рассылке (максимум 10)
                </label>
            </div>

        </div>

        <div class="col-12 mb-2" v-if="need_page_images">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Изображения в рассылке</h6>
                </div>
                <div class="card-body">
                    <BotMediaList
                        :need-photo="true"
                        :selected="mailForm.images"
                        v-on:select="selectImages"></BotMediaList>
                </div>

            </div>
        </div>


        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_inline_menu"
                       type="checkbox" id="need-inline-menu">
                <label class="form-check-label" for="need-inline-menu">
                    Меню под текстом рассылки
                </label>
            </div>
        </div>
        <div class="col-12 mb-2" v-if="need_inline_menu">
            <div class="card">


                <div class="card-header d-flex justify-between align-items-center">
                    <h6>Конструктор меню в сообщении</h6>
                    <button class="btn " type="button"
                            v-bind:class="{'btn-outline-primary':!showInlineTemplateSelector,'btn-primary':showInlineTemplateSelector}"
                            @click="showInlineTemplateSelector = !showInlineTemplateSelector"
                    >

                        <span v-if="!showInlineTemplateSelector">  Открыть шаблоны меню</span>
                        <span v-else> Скрыть шаблоны меню</span>
                    </button>
                </div>


                <div class="card-body">
                    <KeyboardList
                        class="mb-2"
                        :type="'inline'"
                        v-if="showInlineTemplateSelector"
                        v-on:select="selectInlineKeyboard"
                        :select-mode="true"/>

                    <BotMenuConstructor
                        :type="'inline'"
                        v-else
                        v-on:save="saveInlineKeyboard"
                        :edited-keyboard="mailForm.inline_keyboard"/>


                </div>
            </div>


        </div>


        <div class="col-12">
            <button
                type="submit" class="btn btn-success w-100 p-3">
               Поставить сообщение в очередь
            </button>
        </div>

    </form>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {

            load: false,

            showInlineTemplateSelector: false,

            need_page_images: false,
            need_inline_menu: false,


            bot: null,
            mailForm: {
                message: '',
                inline_keyboard: null,
                images: [],
            },
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),

    },
    mounted() {
        this.loadCurrentBot()
    },

    methods: {

        selectImages(item) {
            if (!this.mailForm.images)
                this.mailForm.images = []

            let index = this.mailForm.images.indexOf(item.file_id)

            if (index !== -1)
                this.mailForm.images.splice(index, 1)
            else
                this.mailForm.images.push(item.file_id)

        },
        addTextTo(object = {param: null, text: null}) {
            this.mailForm.channel = object.text;

        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        submitMail() {
            let data = new FormData();
            Object.keys(this.mailForm)
                .forEach(key => {
                    const item = this.mailForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            if (this.bot)
                data.append("bot_id", this.bot.id)


            this.$store.dispatch("sendToQueue", {
                mailForm: data
            }).then((response) => {
                this.load = true

                this.need_inline_menu = false
                this.need_page_images = false
                this.mailForm = {
                    message: '',
                    images: null,
                    inline_keyboard: null
                }
                this.$notify({
                    title: "Рассылка",
                    text: "Сообщение успешно поставлено в очередь!",
                    type: 'success'
                });

                this.$emit("callback")
            }).catch(err => {

            })

        },
        saveInlineKeyboard(keyboard) {
            this.mailForm.inline_keyboard = keyboard
        },
        selectInlineKeyboard(keyboard) {
            this.mailForm.inline_keyboard = keyboard


            this.showInlineTemplateSelector = false;
        },

        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.photos.splice(index, 1)
        },
        removeImage(index) {
            this.mailForm.images.splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },


    }
}
</script>
