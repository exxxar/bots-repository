<script setup>
import BotMenuConstructor from "@/ClientTg/Components/V1/Admin/Keyboards/KeyboardConstructor.vue";
import KeyboardList from "@/ClientTg/Components/V1/Admin/Keyboards/KeyboardList.vue";
</script>

<template>
    <form v-on:submit.prevent="submitMail" v-if="currentBot">


        <p class="my-2">Выберите канал рассылки:</p>
        <div class="btn-group-vertical w-100 mb-2" role="group" aria-label="Vertical button group">
            <button
                v-if="currentBot.main_channel!=null"
                type="button"
                class="btn p-3 mb-2"
                v-bind:class="{'btn-primary':mailForm.channel === currentBot.main_channel, 'btn-secondary':mailForm.channel !== currentBot.main_channel}"
                @click="toggleMainChannel">Главный канал
            </button>

            <button
                v-if="currentBot.order_channel!=null"
                type="button"
                class="btn p-3"
                v-bind:class="{'btn-primary':mailForm.channel === currentBot.order_channel, 'btn-secondary':mailForm.channel !== currentBot.order_channel}"
                @click="toggleOrderChannel">Канал заказов
            </button>
        </div>


        <div class="form-floating mb-3">
            <input
                maxlength="255"
                v-model="mailForm.channel"
                type="search" class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">id канала</label>
        </div>

        <div class="form-floating mb-2">
            <textarea class="form-control"
                      v-model="mailForm.text"
                      maxlength="4096"
                      style="min-height: 100px"
                      placeholder="Leave a comment here" id="floatingTextarea" required></textarea>
            <label for="floatingTextarea">Текстовое содержимое рассылки
                <span class="small"
                      v-if="mailForm.text">
                    {{ mailForm.text.length }}/4096
                </span>
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input"
                   v-model="need_page_images"
                   type="checkbox"
                   id="need-page-images">
            <label class="form-check-label" for="need-page-images">
                Изображения на странице (максимум 10)
            </label>
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

        <div class="form-check">
            <input class="form-check-input"
                   v-model="need_inline_menu"
                   type="checkbox" id="need-inline-menu">
            <label class="form-check-label" for="need-inline-menu">
                Меню под текстом страницы
            </label>
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
                v-model="mailForm.inline_keyboard"/>


        </div>


        <button
            type="submit"
            class="btn btn-primary p-3 w-100">
            <i class="fa-regular fa-paper-plane mr-2"></i> Отправить в канал
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

    computed: {
        currentBot() {
            return window.currentBot
        }
    },
    mounted() {

        window.addEventListener("select-telegram-channel-id", (e) => {
            this.mailForm[e.detail.param] = e.detail.channel
        });
    },

    methods: {
        toggleMainChannel() {
            this.mailForm.channel = this.currentBot.main_channel
        },
        toggleOrderChannel() {
            this.mailForm.channel = this.currentBot.order_channel
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

                this.$notify({
                    title: 'Отлично',
                    text: "Сообщение успешно отправлено в канал!",
                    type: 'success'
                })


            }).catch(err => {
                this.$notify({
                    title: 'Упс...',
                    text: "Ошибка отправки сообщения в канал!",
                    type: 'error'
                })
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
                    this.$notify({
                        title: 'Отлично',
                        text: "Канал успешно найден!",
                        type: 'success'
                    })
                if (!resp.ok) {
                    this.$notify({
                        title: 'Ошибочка',
                        text: "Неверно указанный канал!",
                        type: 'error'
                    })
                    this.mailForm.channel = null
                }
            }).catch(() => {

            })
        },

    }
}
</script>
