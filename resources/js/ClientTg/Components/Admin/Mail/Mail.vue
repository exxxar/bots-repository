<script setup>
import BotMenuConstructor from "@/ClientTg/Components/Admin/Keyboards/KeyboardConstructor.vue";
import KeyboardList from "@/ClientTg/Components/Admin/Keyboards/KeyboardList.vue";
</script>

<template>
    <form v-on:submit.prevent="submitMail" v-if="currentBot">


            <div class="mb-3">
                <button
                    v-if="mailForm.channel!=null"
                    type="button"
                    class="btn btn-outline-info"
                    @click="mailForm.channel = null"><i class="fa-solid fa-xmark"></i></button>
                ,
                <button
                    type="button"
                    class="btn"
                    v-bind:class="{'btn-info':mailForm.channel === currentBot.main_channel, 'btn-outline-info':mailForm.channel !== currentBot.main_channel}"
                    @click="mailForm.channel = currentBot.main_channel">Главный канал
                </button>
                ,

                <button
                    type="button"
                    class="btn"
                    v-bind:class="{'btn-info':mailForm.channel === currentBot.order_channel, 'btn-outline-info':mailForm.channel !== currentBot.order_channel}"
                    @click="mailForm.channel = currentBot.order_channel">Канал заказов
                </button>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between flex-wrap align-items-center mb-2">

                    <label class="form-label" id="bot-main-channel">Канал для постов</label>
                    <span @click="requestChannelId('channel')"><i class="fa-brands fa-telegram mr-2 color-blue2-dark"></i></span>
                     <Popper>
                        <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                        <template #content>
                            <div>Нужно
                            </div>
                        </template>
                    </Popper>


                </div>
                <input type="text" class="form-control"
                       placeholder="id канала"
                       aria-label="id канала"
                       v-model="mailForm.channel"
                       maxlength="255"
                       aria-describedby="bot-main-channel" required>
            </div>


        <div class="mb-2">
            <label class="form-label d-flex justify-content-between align-items-center mb-2" id="bot-domain">
                Текстовое содержимое страницы
                 <Popper>
                        <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                        <template #content>
                            <div>Нужно
                            </div>
                        </template>
                    </Popper>

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


        <div class="mb-2">
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

        <div class="mb-2" v-if="need_page_images">

            <h6>Изображения на странице</h6>

            <div class="d-flex justify-content-center flex-wrap">

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
            type="submit" class="btn btn-m btn-full mb-1 rounded-s text-uppercase font-900 shadow-s bg-green2-dark w-100">
            Отправить сообщение в канал
        </button>

    </form>
</template>
<script>



export default {

    data() {
        return {

            load: false,
            photos: [],
            showInlineTemplateSelector: false,

            need_page_images: false,
            need_inline_menu: false,

            mailForm: {
                text: '',
                inline_keyboard: null,
                channel: null,
            },
        }
    },
    computed:{
        currentBot(){
            return window.currentBot
        }
    },
    mounted() {

        window.addEventListener("select-telegram-channel-id", (e) => {
            this.mailForm[e.detail.param] = e.detail.channel
        } );
    },

    methods: {
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


            if (this.photos.length > 0)
                for (let i = 0; i < this.photos.length; i++) {
                    data.append('photos[]', this.photos[i]);
                }

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
                this.$botNotification.success(
                    "Отлично!",
                    "Сообщение успешно отправлено в канал!"
                 );
            }).catch(err => {
                this.$botNotification.warning(
                    "Упс...",
                    "Ошибка отправки сообщения в канал"
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
        requestChannelId(param){
            this.$botPages.telegramChannelHelper(param);
        }


    }
}
</script>
