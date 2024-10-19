<script setup>
import KeyboardConstructor from "@/ClientTg/Components/V2/Admin/Keyboard/KeyboardConstructor.vue";
import KeyboardList from "@/ClientTg/Components/V1/Admin/Keyboards/KeyboardList.vue";
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
import MailingTable from "@/ClientTg/Components/V2/Admin/Mail/MailingTable.vue";
</script>

<template>
    <form v-on:submit.prevent="submitMail" v-if="currentBot&&load_form" class="py-3">

        <div class="form-floating mb-2">
            <textarea class="form-control"
                      v-model="mailForm.message"
                      maxlength="4096"
                      style="min-height: 100px"
                      placeholder="Leave a comment here" id="floatingTextarea" required></textarea>
            <label for="floatingTextarea">Текстовое содержимое рассылки
                <span class="small"
                      v-if="mailForm.message">
                    {{ (mailForm.message || '').length }}/4096
                </span>
            </label>
        </div>


        <div class="form-check">
            <input class="form-check-input"
                   v-model="need_page_images"
                   type="checkbox"
                   id="need-page-images">
            <label class="form-check-label" for="need-page-images">
                Изображения в рассылке (максимум 10)
            </label>
        </div>

        <div class="mb-2" v-if="need_page_images">
            <div class="divider my-3">Изображения</div>
            <p class="mb-2" v-if="(mailForm.images||[]).length>0">Выбрано <span
                class="fw-bold text-primary">{{ mailForm.images.length }}</span> из <span class="fw-bold text-primary">10</span>
                изображений. Нажмите <span class="fw-bold text-danger text-decoration-underline cursor-pointer"
                                           @click="clearImages">очистить</span> чтоб отменить выбор изображений.</p>
            <button type="button" class="btn btn-outline-light text-primary w-100 p-3" data-bs-toggle="modal"
                    data-bs-target="#select-images">
                <i class="fa-solid fa-image mr-2"></i> Выбрать изображение
            </button>

            <!-- Modal -->
            <div class="modal fade" id="select-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                 aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-body">
                            <BotMediaList
                                :need-photo="true"
                                :selected="mailForm.images"
                                v-on:select="selectImages"></BotMediaList>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    v-bind:class="{'btn-secondary':(mailForm.images||[]).length===0,'btn-primary':(mailForm.images||[]).length>0}"
                                    class="btn  w-100 p-3" data-bs-dismiss="modal">
                                <span v-if="(mailForm.images||[]).length===0">Закрыть</span>
                                <span v-if="(mailForm.images||[]).length>0">Выбрать и закрыть</span>
                            </button>
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
            <div class="divider my-3">Меню</div>
            <button class="btn mb-2 w-100 p-3 btn-outline-light text-primary" type="button"
                    data-bs-toggle="modal" data-bs-target="#show-keyboard-template-list"
            >
                Открыть шаблоны меню
            </button>

            <!-- Modal -->
            <div class="modal fade" id="show-keyboard-template-list" data-bs-backdrop="static" data-bs-keyboard="false"
                 tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-body">
                            <KeyboardList
                                class="mb-2"
                                :type="'inline'"
                                v-on:select="selectInlineKeyboard"
                                :select-mode="true"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary p-3 w-100"
                                    id="show-keyboard-template-list-btn-close"
                                    data-bs-dismiss="modal">Выбрать и закрыть
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <KeyboardConstructor
                :type="'inline'"
                v-if="!showInlineTemplateSelector"
                v-on:save="saveInlineKeyboard"
                :edited-keyboard="mailForm.inline_keyboard"/>


        </div>

        <div class="alert alert-light mb-2">
            Вы можете указать <span class="fw-bold text-primary">любое время</span> для рассылки! Наши рассылки
            запускаются <span class="fw-bold text-primary">каждые 2 часа</span> и будут выполнены, если указанное вами
            время уже наступило.
        </div>
        <div class="form-floating mb-2">
            <input type="datetime-local" class="form-control"
                   v-model="mailForm.cron_time" required
                   id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Планируемое время</label>
        </div>

        <button
            type="submit" class="btn btn-primary w-100 p-3">
            <i class="fa-regular fa-paper-plane mr-2"></i> Поставить в очередь
        </button>


    </form>

    <div class="divider my-3"><i class="fa-solid fa-hourglass-half  text-primary mr-2"></i> Рассылки в очереди</div>


    <MailingTable
        v-on:select="selectMailing"
        v-if="load"></MailingTable>
    <div
        v-else
        class="alert alert-light d-flex justify-content-center align-items-center flex-column py-3"
        role="alert">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Загружаем...</span>
        </div>
        <p class="mt-3 mb-0">Загружаем...</p>
    </div>
</template>
<script>

export default {

    data() {
        return {
            load_form: true,
            load: true,
            showInlineTemplateSelector: false,

            need_page_images: false,
            need_inline_menu: false,

            mailForm: {
                id: null,
                message: null,
                inline_keyboard: null,
                cron_time: null,
                images: [],
            },
        }
    },
    watch: {
        'need_inline_menu': {
            handler: function (newValue) {
                if (!this.need_inline_menu) {
                   // this.mailForm.inline_keyboard = null
                }
            },
            deep: true
        },
        'need_page_images': {
            handler: function (newValue) {
                if (!this.need_page_images) {
                    this.mailForm.images = []
                }
            },
            deep: true
        }
    },
    computed: {
        currentBot() {
            return window.currentBot
        }
    },
    methods: {
        clearImages() {
            this.mailForm.images = []

            this.$notify({
                title: "Отлично!",
                text: "Изображения успешно очищены!",
                type: "success"
            });
        },
        selectMailing(item) {

            if (item.inline_keyboard)
                this.need_inline_menu = true

            this.load_form = false
            this.$nextTick(() => {

                this.mailForm.id = item.id || null
                this.mailForm.cron_time = item.cron_time ? this.$filters.local(item.cron_time) : null
                this.mailForm.images = item.images || []
                this.mailForm.message = item.content || null
                this.load_form = true

                if (this.mailForm.images.length > 0)
                    this.need_page_images = true

                if (item.inline_keyboard) {
                    this.mailForm.inline_keyboard = {
                        menu:JSON.parse(item.inline_keyboard),
                    }
                }

                window.scroll(0, 0)
            })


        },
        selectImages(item) {
            if (!this.mailForm.images || typeof this.mailForm.images == "string")
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
            this.load = false
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


            this.$store.dispatch("storeQueue", {
                queueForm: data
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
                    title: "Отлично!",
                    text: "Сообщение успешно отправлено в очередь рассылки!",
                    type: "success"
                });

            }).catch(err => {
                this.$notify({
                    title: "Упс...",
                    text: "Ошибка отправки сообщения в очередь",
                    type: "error"
                });
            })

        },
        saveInlineKeyboard(keyboard) {
            this.mailForm.inline_keyboard = keyboard
        },
        selectInlineKeyboard(keyboard) {

            this.showInlineTemplateSelector = true
            this.$nextTick(() => {
                this.mailForm.inline_keyboard = keyboard

                this.showInlineTemplateSelector = false;
            })

            let modal = document.querySelector("#show-keyboard-template-list-btn-close")
            if (modal)
                modal.click()
        },

        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.images.splice(index, 1)
        },
        removeImage(index) {
            this.mailForm.images.splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.images.push(files[i])
        },


    }
}
</script>
