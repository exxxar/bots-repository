<script setup>
import BotMenuConstructor from "@/ClientTg/Components/Admin/Keyboards/KeyboardConstructor.vue";
import KeyboardList from "@/ClientTg/Components/Admin/Keyboards/KeyboardList.vue";
import BotMediaList from "@/ClientTg/Components/BotMediaList.vue";
</script>

<template>
    <form v-on:submit.prevent="submitMail" v-if="currentBot">


        <div class="mb-2">
            <label class="form-label d-flex justify-content-between align-items-center mb-1">
                Текстовое содержимое рассылки
                <Popper>
                    <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                    <template #content>
                        <div>Нужно
                        </div>
                    </template>
                </Popper>

            </label>

            <p class="mb-1 text-center"><span
                v-if="mailForm.message">{{ mailForm.message.length }}/4096 </span></p>
            <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="mailForm.message"
                                          maxlength="4096"
                                          placeholder="Введите текст"
                                          id="floatingTextarea2" style="min-height: 100px" required></textarea>
            </div>

        </div>

        <div class="mb-2">
            <input type="datetime-local" class="form-control w-100" v-model="mailForm.cron_time" required/>
        </div>


        <div class="mb-2">
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

        <div class="mb-2" v-if="need_page_images">
            <BotMediaList
                :need-photo="true"
                :selected="mailForm.images"
                v-on:select="selectImages"></BotMediaList>
        </div>



        <div class="mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_inline_menu"
                       type="checkbox" id="need-inline-menu">
                <label class="form-check-label" for="need-inline-menu">
                    Меню под текстом страницы
                </label>
            </div>
        </div>
        <div class="mb-2" v-if="need_inline_menu">

            <button class="btn mb-2 w-100" type="button"
                    v-bind:class="{'btn-outline-primary':!showInlineTemplateSelector,'btn-primary':showInlineTemplateSelector}"
                    @click="showInlineTemplateSelector = !showInlineTemplateSelector"
            >

                <span v-if="!showInlineTemplateSelector">  Открыть шаблоны меню</span>
                <span v-else> Скрыть шаблоны меню</span>
            </button>

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




            <button
                type="submit" class="btn btn-m btn-full my-3
                rounded-xl text-uppercase font-900 shadow-s bg-green2-dark w-100">
                Поставить сообщение в очередь
            </button>


    </form>
</template>
<script>
import {mapGetters} from "vuex";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    components: { VueDatePicker },
    data() {
        return {

            load: false,
            photos: [],
            showInlineTemplateSelector: false,

            need_page_images: false,
            need_inline_menu: false,

            mailForm: {
                message: '',
                inline_keyboard: null,
                cron_time: null,
                images: [],
            },
        }
    },

    computed: {
        currentBot() {
            return window.currentBot
        }
    },
    mounted() {


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
                this.$botNotification.success(
                    "Отлично!",
                    "Сообщение успешно отправлено в очередь рассылки!"
                );

                this.$emit("callback")
            }).catch(err => {
                this.$botNotification.warning(
                    "Упс...",
                    "Ошибка отправки сообщения в очередь"
                );
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
        requestChannelId(param) {
            this.$botPages.telegramChannelHelper(param);
        },

        requestTelegramChannelId() {
            const reg = new RegExp('^\d+$')

            if (reg.test(this.mailForm.channel))
                return;

            if (this.mailForm.channel.indexOf("https://") !== -1)
                this.mailForm.channel = "@" + (this.mailForm.channel.split("https://t.me/")[1])


            this.$store.dispatch("requestTelegramChannelId", {
                dataObject: {
                    channel: this.mailForm.channel
                }
            }).then((resp) => {
                if (resp.ok)
                    this.mailForm.channel = resp.result.chat.id


                if (resp.ok)
                    this.$botNotification.success("Отлично", "Канал успешно найден!")
                if (!resp.ok) {
                    this.$botNotification.warning("Ошибочка!", "Неверно указанный канал")
                    this.mailForm.channel = null
                }
            }).catch(() => {

            })
        },

    }
}
</script>
