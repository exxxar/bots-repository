<script setup>
import BotMenuConstructor from "@/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/Components/Constructor/KeyboardList.vue";
import PagesList from "@/Components/Constructor/Pages/PagesList.vue";
import BotSlugListSimple from "@/Components/Constructor/Slugs/BotSlugListSimple.vue";
import BotDialogGroupListSimple from "@/Components/Constructor/Dialogs/BotDialogGroupListSimple.vue";
</script>
<template>
    <form
        v-if="bot"
        v-on:submit.prevent="submitPage">
        <div class="col-12 mb-2 ">
            <h6 class="d-flex justify-between">
                <span>Вы создаете страницу для {{ bot.bot_domain }}</span>
                <a href="#clear-form"
                   v-if="pageForm.id||need_clean"
                   @click="clearForm">очистить форму</a>
            </h6>
        </div>
        <div class="col-12 mb-2">
            <label class="form-label" id="bot-domain">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div> Команда должна начинаться с символва . и *<br>
                            Например: .*Меню<br>
                        </div>
                    </template>
                </Popper>
                Команда
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <input type="text" class="form-control"
                   placeholder="Команда"
                   aria-label="Команда"
                   v-model="pageForm.command"
                   maxlength="255"
                   aria-describedby="bot-domain" required>
        </div>
        <div class="col-12 mb-2">
            <label class="form-label" id="bot-domain">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Напишите для себя пояснение для чего нужна данная страница.
                            Это поможет другим менеджерам лучше понять вас.
                        </div>
                    </template>
                </Popper>
                Описание страницы
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <textarea type="text" class="form-control"
                      placeholder="Описание страницы"
                      aria-label="Описание страницы"
                      v-model="pageForm.comment"
                      maxlength="255"
                      aria-describedby="bot-domain" required>
            </textarea>
        </div>
        <div class="col-12 mb-2">
            <label class="form-label" id="bot-domain">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>
                            Текстовый редактор. Данный текст будет в таком<br>
                            же виде отображен в посте в телеграм.
                        </div>
                    </template>
                </Popper>
                Текстовое содержимое страницы
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>


            <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="pageForm.content"
                                          placeholder="Введите текст"
                                          id="floatingTextarea2" style="min-height: 100px"></textarea>
                <label for="floatingTextarea2">Содержимое страницы</label>
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
                        <h6 v-if="pageForm.images.length>0">Ранее загруженные фотографии</h6>
                        <div class="col-12 d-flex flex-wrap" v-if="pageForm.images.length>0">

                            <div class="mb-2 img-preview" style="margin-right: 10px;"
                                 v-for="(img, index) in pageForm.images">
                                <img
                                    v-lazy="'/images-by-bot-id/'+bot.id+'/'+img">
                                <div class="remove">
                                    <a @click="removeImage(index)">Удалить</a>
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
                       v-model="need_reply_menu"
                       type="checkbox" id="need-reply-menu">
                <label class="form-check-label" for="need-reply-menu">
                    Нижнее меню страницы
                </label>
            </div>
        </div>
        <div class="col-12 mb-2" v-if="need_reply_menu">
            <div class="card">
                <div class="card-header d-flex justify-between align-items-center">
                    <h6>Конструктор нижнего меню</h6>

                    <button class="btn " type="button"
                            v-bind:class="{'btn-outline-primary':!showReplyTemplateSelector,'btn-primary':showReplyTemplateSelector}"
                            @click="showReplyTemplateSelector = !showReplyTemplateSelector"
                    >

                        <span v-if="!showReplyTemplateSelector">  Открыть шаблоны меню</span>
                        <span v-else> Скрыть шаблоны меню</span>
                    </button>


                </div>


                <div class="card-body">


                    <KeyboardList
                        class="mb-2"
                        :type="'reply'"
                        v-if="showReplyTemplateSelector"
                        v-on:select="selectReplyKeyboard"
                        :select-mode="true"/>

                    <BotMenuConstructor
                        v-else
                        :type="'reply'"
                        v-on:save="saveReplyKeyboard"
                        :edited-keyboard="pageForm.reply_keyboard"/>
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
                        :edited-keyboard="pageForm.inline_keyboard"/>


                </div>
            </div>


        </div>

        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_attach_page"
                       type="checkbox"
                       id="need-page-attach">
                <label class="form-check-label" for="need-page-attach">
                    Связать с другой страницей
                </label>
            </div>
        </div>


        <div class="col-12 mb-2" v-if="need_attach_page">
            <p v-if="pageForm.next_page_id">Связано со страницей #{{ pageForm.next_page_id }} <a
                class="btn btn-link"
                @click="pageForm.next_page_id = null">Очистить</a></p>
            <PagesList
                :current="pageForm.id"
                v-on:callback="attachPage"
                :editor="false"/>
        </div>

        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_attach_slug"
                       type="checkbox"
                       id="need-slug-attach">
                <label class="form-check-label" for="need-slug-attach">
                    Привязать скрипт
                </label>
            </div>
        </div>


        <div class="col-12 mb-2" v-if="need_attach_slug">
            <p v-if="pageForm.next_bot_menu_slug_id">Связано со скриптом #{{ pageForm.next_bot_menu_slug_id }} <a
                class="btn btn-link"
                @click="pageForm.next_bot_menu_slug_id = null">Очистить</a></p>
            <BotSlugListSimple v-if="bot"
                               :global="true"
                               v-on:callback="associateSlug"
                               :bot="bot"/>
        </div>

        <div class="col-12 mb-2">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_attach_dialog"
                       type="checkbox"
                       id="need-dialog-attach">
                <label class="form-check-label" for="need-dialog-attach">
                    Привязать начало диалога
                </label>
            </div>
        </div>


        <div class="col-12 mb-2" v-if="need_attach_dialog">
            <p v-if="pageForm.next_bot_dialog_command_id">Связано с диалогом #{{ pageForm.next_bot_dialog_command_id }}
                <a
                    class="btn btn-link"
                    @click="pageForm.next_bot_dialog_command_id = null">Очистить</a></p>
            <BotDialogGroupListSimple v-if="bot"
                                      v-on:select-dialog="associateDialog"
                                      :bot="bot"/>
        </div>

        <div class="col-12 mb-2">
            <button class="btn btn-outline-primary w-100 p-3">Сохранить страницу</button>
        </div>
    </form>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["page"],
    data() {
        return {
            need_clean: false,
            load: false,
            photos: [],
            showReplyTemplateSelector: false,
            showInlineTemplateSelector: false,


            need_page_images: false,
            need_inline_menu: false,
            need_reply_menu: false,
            need_attach_page: false,
            need_attach_dialog: false,
            need_attach_slug: false,

            bot: null,
            pageForm: {
                id: null,
                content: '',
                command: null,
                slug: null,
                comment: null,

                images: [],
                reply_keyboard: null,
                inline_keyboard: null,

                next_page_id: null,
                next_bot_dialog_command_id: null,
                next_bot_menu_slug_id: null,
            },
        }
    },
    watch: {

        'need_page_images': function (newVal, oldVal) {
            if (!this.need_page_images) {
                this.photos = []
                this.pageForm.images = []
            }

        },
        'need_inline_menu': function (newVal, oldVal) {

            if (!this.need_inline_menu) {
                this.pageForm.inline_keyboard = null
                this.pageForm.inline_keyboard_id = null
            }

        },
        'need_reply_menu': function (newVal, oldVal) {
            if (!this.need_reply_menu) {
                this.pageForm.reply_keyboard = null
                this.pageForm.reply_keyboard_id = null
            }
        },
        'need_attach_page': function (newVal, oldVal) {
            if (!this.need_attach_page) {
                this.pageForm.next_page_id = null

            }
        },
        'need_attach_dialog': function (newVal, oldVal) {
            if (!this.need_attach_dialog) {
                this.pageForm.next_bot_dialog_command_id = null

            }
        },
        'need_attach_slug': function (newVal, oldVal) {
            if (!this.need_attach_slug) {
                this.pageForm.next_bot_menu_slug_id = null

            }
        },

        pageForm: {
            handler: function (newValue) {
                if (this.pageForm.reply_keyboard != null)
                    this.need_reply_menu = true

                if (this.pageForm.inline_keyboard != null)
                    this.need_inline_menu = true

                if (this.pageForm.images.length > 0)
                    this.need_page_images = true

                if (this.pageForm.next_bot_dialog_command_id != null)
                    this.need_attach_dialog = true

                if (this.pageForm.next_bot_menu_slug_id != null)
                    this.need_attach_slug = true

                this.need_clean = true
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        if (this.page) {
            let page = this.page
            this.photos = []
            this.pageForm = {
                id: page.id,
                slug_id: page.slug.id || null,
                content: page.content,
                command: page.slug.command || null,
                slug: page.slug.slug || null,
                comment: page.slug.comment || null,
                images: page.images || [],
                reply_keyboard_id: page.reply_keyboard_id || null,
                inline_keyboard_id: page.inline_keyboard_id || null,
                reply_keyboard: page.replyKeyboard || null,
                inline_keyboard: page.inlineKeyboard || null,
                next_page_id: page.next_page_id || null,
                next_bot_dialog_command_id: page.next_bot_dialog_command_id || null,
                next_bot_menu_slug_id: page.next_bot_menu_slug_id || null,
            }
        } else
            this.clearForm()


        this.loadCurrentBot().then(() => {

        })
    },

    methods: {
        associateDialog(item) {
            this.pageForm.next_bot_dialog_command_id = item.id
        },
        associateSlug(item) {
            this.pageForm.next_bot_menu_slug_id = item.id
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        attachPage(item) {

            if (item.id != this.pageForm.id)
                this.pageForm.next_page_id = item.id
            else
                this.$notify({
                    title: "Конструктор страниц",
                    text: "Вы не можете связать данную страницу с собой",
                    type: 'error'
                });
        },
        clearForm() {
            this.photos = []
            this.pageForm = {
                id: null,
                content: null,
                command: null,
                slug: null,
                comment: null,
                images: [],
                reply_keyboard: null,
                inline_keyboard: null,

                reply_keyboard_id: null,
                inline_keyboard_id: null,

                next_page_id: null,

                next_bot_dialog_command_id: null,
                next_bot_menu_slug_id: null,

            }
            this.photos = []

            this.showReplyTemplateSelector = false
            this.showInlineTemplateSelector = false


            this.need_page_images = false
            this.need_inline_menu = false
            this.need_reply_menu = false
            this.need_attach_page = false
            this.need_attach_dialog = false
            this.need_attach_slug = false

            this.$nextTick(() => {
                this.need_clean = false


            })
        },
        submitPage() {
            let data = new FormData();
            Object.keys(this.pageForm)
                .forEach(key => {
                    const item = this.pageForm[key] || ''
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

            /*   if (this.pageForm.images.length === 0 || typeof this.pageForm.images == 'string')
                   data.delete("images")*/

            this.$store.dispatch((this.pageForm.id == null ? "createPage" : "updatePage"), {
                pageForm: data
            }).then((response) => {
                this.load = true

                this.$nextTick(() => {
                    this.load = false

                    this.clearForm()
                })

                this.$emit("callback", response.data)
                this.$notify({
                    title: "Конструктор ботов",
                    text: (this.pageForm.id == null ? "Страница успешно создана!" : "Страница успешно обновлена!"),
                    type: 'success'
                });
            }).catch(err => {

            })

        },
        saveInlineKeyboard(keyboard) {
            this.pageForm.inline_keyboard = keyboard
        },
        selectReplyKeyboard(keyboard) {
            this.pageForm.reply_keyboard = keyboard

            this.showReplyTemplateSelector = false;
        },
        selectInlineKeyboard(keyboard) {
            this.pageForm.inline_keyboard = keyboard

            console.log(keyboard)

            this.showInlineTemplateSelector = false;
        },
        saveReplyKeyboard(keyboard) {
            this.pageForm.reply_keyboard = keyboard
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.photos.splice(index, 1)
        },
        removeImage(index) {
            this.pageForm.images.splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },


        onChange(data) {
            this.pageForm.content = data
        },

    }
}
</script>
