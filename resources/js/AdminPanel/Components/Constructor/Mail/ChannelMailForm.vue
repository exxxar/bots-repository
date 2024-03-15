<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";


import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
</script>

<template>
    <form v-on:submit.prevent="submitMail" v-if="bot">

        <div class="col-md-12 col-12">
            <div class="mb-3">
                <button
                    v-if="mailForm.channel!=null"
                    type="button"
                    class="btn btn-outline-info mr-2"
                    @click="mailForm.channel = null"><i class="fa-solid fa-xmark"></i></button>

                <button
                    type="button"
                    class="btn mr-2"
                    v-bind:class="{'btn-info':mailForm.channel === bot.main_channel, 'btn-outline-info':mailForm.channel !== bot.main_channel}"
                    @click="mailForm.channel = bot.main_channel">Главный канал
                </button>


                <button
                    type="button"
                    class="btn"
                    v-bind:class="{'btn-info':mailForm.channel === bot.order_channel, 'btn-outline-info':mailForm.channel !== bot.order_channel}"
                    @click="mailForm.channel = bot.order_channel">Канал заказов
                </button>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between flex-wrap al">

                    <label class="form-label" id="bot-main-channel">Канал для постов (id,рекламный)</label>

                    <div class="d-flex flex-wrap align-items-center">
                        <TelegramChannelHelper
                            :token="bot.bot_token"
                            :param="'channel'"
                            v-on:callback="addTextTo"
                        />

                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>

                    </div>


                </div>
                <input type="text" class="form-control"
                       placeholder="id канала"
                       aria-label="id канала"
                       v-model="mailForm.channel"
                       maxlength="255"
                       aria-describedby="bot-main-channel" required>
            </div>
        </div>

        <div class="col-12 mb-2">
            <label class="form-label d-flex justify-content-between align-items-center mb-2" id="bot-domain">
                Текстовое содержимое страницы
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>

            </label>


            <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="mailForm.text"
                                          maxlength="4096"
                                          placeholder="Введите текст"
                                          id="floatingTextarea2" style="min-height: 100px" required></textarea>
                <label for="floatingTextarea2">Содержимое страницы <span
                    v-if="mailForm.text">{{ mailForm.text.length }}/4096 </span></label>
            </div>

        </div>


        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_page_images"
                       type="checkbox"
                       id="need-page-images">
                <label class="form-check-label" for="need-page-images">
                    Изображения на странице (максимум 10)
                </label>
            </div>

        </div>

        <div class="col-12 mb-2" v-if="need_page_images">
            <div class="card mb-3">
                <div class="card-header">
                    <h6>Изображения на странице</h6>
                </div>
                <div class="card-body d-flex justify-content-start">

                    <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                        +
                        <input type="file" id="photos"
                               multiple
                               accept="image/*" @change="onChangePhotos"
                               style="display:none;"/>

                    </label>
                    <div class="row">
                        <div class="col-12 d-flex flex-wrap" v-if="photos.length>0">
                            <div class="mb-2 img-preview" style="margin-right: 10px;"
                                 v-for="(img, index) in photos">
                                <img v-lazy="getPhoto(img).imageUrl"/>

                                <div class="remove">
                                    <a @click="removePhoto(index)">Удалить</a>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>


        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_inline_menu"
                       type="checkbox" id="need-inline-menu">
                <label class="form-check-label" for="need-inline-menu">
                    Меню под текстом страницы
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
                Отправить сообщение в канал
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
            photos: [],
            showInlineTemplateSelector: false,

            need_page_images: false,
            need_inline_menu: false,


            bot: null,
            mailForm: {
                text: '',
                inline_keyboard: null,
                channel: null,
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

            if (this.photos.length > 0)
                for (let i = 0; i < this.photos.length; i++) {
                    data.append('photos[]', this.photos[i]);
                }

            /*   if (this.mailForm.images.length === 0 || typeof this.mailForm.images == 'string')
                   data.delete("images")*/

            this.$store.dispatch("sendToChannel", {
                mailForm: data
            }).then((response) => {
                this.load = true

                this.photos = []
                this.need_inline_menu = false
                this.need_page_images = false
                this.mailForm = {
                    text: '',
                    channel: null,
                    inline_keyboard: null
                }
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Сообщение успещно отправлено!",
                    type: 'success'
                });
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
