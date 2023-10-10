<script setup>
import BotMenuConstructor from "@/ClientTg/Components/Admin/Keyboards/KeyboardConstructor.vue";
import KeyboardList from "@/ClientTg/Components/Admin/Keyboards/KeyboardList.vue";
import PagesList from "@/ClientTg/Components/Admin/Pages/PagesList.vue";
import BotSlugListSimple from "@/ClientTg/Components/Admin/Slugs/BotSlugListSimple.vue";
import DialogList from "@/ClientTg/Components/Admin/Dialogs/BotDialogGroupListSimple.vue";
import PageRules from "@/ClientTg/Components/Admin/Pages/PageRules.vue";
import InlineInjectionsHelper from "@/AdminPanel/Components/Constructor/Helpers/InlineInjectionsHelper.vue";
</script>
<template>
    <div class="card card-style">
        <div class="content mb-0">
            <form
                v-if="bot"
                v-on:submit.prevent="submitPage">


                <a href="javascript:void(0)"
                   class="w-100 text-center d-block"
                   v-if="pageForm.id||need_clean"
                   @click="clearForm"><i class="fa-solid fa-xmark mr-1"></i> Очистить форму</a>


                <div class="w-100 d-flex justify-content-between py-4" v-if="pageForm.id">
                    <span class="ml-1" v-if="need_show_qr_and_link">Отобразить QR-код и ссылку на страницу</span>
                    <span class="ml-1" v-else>Не отображать QR-код и ссылку на страницу</span>
                    <div class="custom-control ios-switch mr-5">
                        <input type="checkbox"
                               v-model="need_show_qr_and_link"
                               class="ios-input" id="need_show_qr_and_link">
                        <label class="custom-control-label" for="need_show_qr_and_link"></label>
                    </div>

                </div>

                <div class="mb-2" v-if="pageForm.id&&need_show_qr_and_link">
                    <p>Ссылка на текущую страницу: <span class="font-weight-bold" @click="copyToClipBoard(pageLink)">{{ pageLink }}</span> </p>
                    <div class="d-flex justify-content-center">
                        <img v-lazy="qr" style="width:300px;height:300px;">
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="bot-domain">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div> Команда должна начинаться с символва . и *<br>
                                        Например: .*Меню<br>
                                    </div>
                                </template>
                            </Popper>
                            Команда
                        </div>
                         <Popper>
                        <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                        <template #content>
                            <div>Нужно
                            </div>
                        </template>
                    </Popper>

                    </label>
                    <input type="text" class="form-control"
                           placeholder="Команда"
                           aria-label="Команда"
                           v-model="pageForm.command"
                           maxlength="255"
                           aria-describedby="bot-domain" required>
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="bot-domain">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Напишите для себя пояснение для чего нужна данная страница.
                                        Это поможет другим менеджерам лучше понять Вас.
                                    </div>
                                </template>
                            </Popper>
                            Описание страницы
                        </div>
                         <Popper>
                        <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                        <template #content>
                            <div>Нужно
                            </div>
                        </template>
                    </Popper>

                    </label>
                    <textarea type="text" class="form-control"
                              placeholder="Описание страницы"
                              aria-label="Описание страницы"
                              v-model="pageForm.comment"
                              maxlength="255"
                              aria-describedby="bot-domain" required>
            </textarea>
                </div>

                <div class="mb-2">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="pageForm.is_external"
                               type="checkbox" id="is_external">
                        <label class="form-check-label" for="is_external">
                           Внешнее управление страницей
                        </label>
                    </div>
                </div>

                <div v-if="!pageForm.is_external">
                <div class="mb-2">
                    <label
                        class="form-label d-flex justify-content-between mt-2"
                        id="bot-domain">
                        <div>

                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>
                                        Текстовый редактор. Данный текст будет в таком<br>
                                        же виде отображен в посте в телеграм.
                                    </div>
                                </template>
                            </Popper>
                            Контент
                        </div>
                         <Popper>
                        <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                        <template #content>
                            <div>Нужно
                            </div>
                        </template>
                    </Popper>


                        <!--                        <InlineInjectionsHelper param="content" v-on:callback="injectContent"/>-->
                    </label>


                    <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="pageForm.content"
                                          maxlength="4096"
                                          placeholder="Введите текст"
                                          id="floatingTextarea2" style="min-height: 100px"></textarea>
                        <label for="floatingTextarea2">Содержимое страницы <span
                            v-if="pageForm.content">{{ pageForm.content.length }}/4096 </span></label>
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


                    <div class="d-flex flex-wrap justify-content-center">
                        <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            +
                            <input type="file" id="photos"
                                   multiple
                                   accept="image/*" @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>

                        <div class="mb-2 mr-2 img-preview"
                             v-if="photos.length>0"
                             v-for="(img, index) in photos">
                            <img v-lazy="getPhoto(img).imageUrl"/>

                            <div class="remove">
                                <a href="javascript:void(0)" @click="removePhoto(index)">Удалить</a>
                            </div>
                        </div>
                    </div>
                    <h6 v-if="pageForm.images.length>0">Ранее загруженные фотографии</h6>
                    <div class="d-flex flex-wrap justify-content-center" v-if="pageForm.images.length>0">

                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in pageForm.images">
                            <img
                                v-lazy="'/images-by-bot-id/'+bot.id+'/'+img">
                            <div class="remove">
                                <a href="javascript:void(0)" @click="removeImage(index)">Удалить</a>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="mb-2">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_reply_menu"
                               type="checkbox" id="need-reply-menu">
                        <label class="form-check-label" for="need-reply-menu">
                            Нижнее меню страницы
                        </label>
                    </div>
                </div>
                <div class="mb-2" v-if="need_reply_menu">

                    <h6>Конструктор нижнего меню</h6>



                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between mt-2" id="bot-domain">
                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div> Заголовок для отображения в нижнем меню
                                        </div>
                                    </template>
                                </Popper>
                                Команда
                            </div>


                        </label>
                        <input type="text" class="form-control"
                               placeholder="Заголовок нижнего меню"
                               aria-label="Заголовок нижнего меню"
                               v-model="pageForm.reply_keyboard_title"
                               maxlength="255"
                               aria-describedby="bot-domain">
                    </div>

                    <button class="btn mb-2 w-100" type="button"
                            v-bind:class="{'btn-outline-primary':!showReplyTemplateSelector,'btn-primary':showReplyTemplateSelector}"
                            @click="showReplyTemplateSelector = !showReplyTemplateSelector"
                    >

                        <span v-if="!showReplyTemplateSelector">  Открыть шаблоны меню</span>
                        <span v-else> Скрыть шаблоны меню</span>
                    </button>


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

                    <h6>Конструктор меню в сообщении</h6>
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
                        :edited-keyboard="pageForm.inline_keyboard"/>


                </div>

                <div class="mb-2">
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


                <div class="mb-2" v-if="need_attach_page">
                    <p
                        class="d-flex justify-content-between mb-0 align-items-center"
                        v-if="pageForm.next_page_id">Связано со страницей #{{ pageForm.next_page_id }} <a
                        href="javascript:void(0)"
                        class="btn font-16 text-danger font-weight-bold"
                        @click="pageForm.next_page_id = null"><i class="fa-solid fa-trash"></i></a></p>
                    <PagesList
                        :current="pageForm.id"
                        v-on:callback="attachPage"
                        :editor="false"/>
                </div>

                <div class="mb-2">
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


                <div class="mb-2" v-if="need_attach_slug">
                    <p v-if="pageForm.next_bot_menu_slug_id"
                       class="d-flex justify-content-between mb-0 align-items-center">Связано со скриптом
                        #{{ pageForm.next_bot_menu_slug_id }}
                        <a
                            href="javascript:void(0)"
                            class="btn font-16 text-danger font-weight-bold"
                            @click="pageForm.next_bot_menu_slug_id = null"><i class="fa-solid fa-trash"></i></a></p>
                    <BotSlugListSimple
                        :global="true"
                        v-on:callback="associateSlug"
                        :bot="bot"/>
                </div>

                <div class="mb-2">
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


                <div class="mb-2" v-if="need_attach_dialog">
                    <p v-if="pageForm.next_bot_dialog_command_id"
                       class="d-flex justify-content-between mb-0 align-items-center">
                        Связано с диалогом
                        #{{ pageForm.next_bot_dialog_command_id }}
                        <a
                            href="javascript:void(0)"
                            class="btn font-16 text-danger font-weight-bold"
                            @click="pageForm.next_bot_dialog_command_id = null"><i class="fa-solid fa-trash"></i></a></p>
                    <DialogList
                        v-on:select-dialog="associateDialog"/>
                </div>

                <div class="mb-2">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_rules"
                               type="checkbox" id="need-rules">
                        <label class="form-check-label" for="need-rules">
                            Нужны правила загрузки страницы
                        </label>
                    </div>
                </div>

                </div>
                <div class="mb-2" v-if="need_rules">
                    <PageRules
                        :bot="bot"
                        :rules-form="pageForm"
                    />
                </div>


                <button class="bg-highlight btn btn-m font-900 text-uppercase btn-center-xl mb-3 w-100">Сохранить
                    страницу
                </button>

            </form>

        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["page"],
    data() {
        return {
            need_show_qr_and_link:false,
            need_clean: false,
            load: false,
            photos: [],
            showReplyTemplateSelector: false,
            showInlineTemplateSelector: false,


            need_page_images: false,
            need_rules: false,
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
                reply_keyboard_title: null,
                reply_keyboard: null,
                inline_keyboard: null,
                is_external: false,

                next_page_id: null,
                next_bot_dialog_command_id: null,
                next_bot_menu_slug_id: null,


                rules_if: null,
                rules_else_page_id: null,
                rules_if_message: null,
                rules_else_message: null,
            },
        }
    },
    watch: {

        'need_rules': function (newVal, oldVal) {
            if (!this.need_rules) {
                this.pageForm.rules_if = null
                this.pageForm.rules_else_page_id = null
                this.pageForm.rules_if_message = null
                this.pageForm.rules_else_message = null
            }

        },
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

                if (this.pageForm.next_page_id != null)
                    this.need_attach_page = true

                if (this.pageForm.rules_if != null)
                    this.need_rules = true

                this.need_clean = true
            },
            deep: true
        }
    },

    computed: {
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        },
        pageLink() {
            if (!this.pageForm.id)
                return "Ссылка недоступна"

            let tmpId = "";
            for (let i = 0; i < 10 - this.pageForm.id.length ; i++)
                tmpId += "0"
            tmpId += this.pageForm.id;

            return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("004" + tmpId);
        }
    },
    mounted() {
        this.loadBot();

        if (this.page) {
            let page = this.page
            this.photos = []
            this.pageForm = {
                id: page.id,
                slug_id: page.slug ? page.slug.id : null,
                content: page.content,
                command: page.slug ? page.slug.command : null,
                slug: page.slug ? page.slug.slug : null,
                comment: page.slug ? page.slug.comment : null,
                images: page.images || [],
                reply_keyboard_title: page.reply_keyboard_title || null,
                reply_keyboard_id: page.reply_keyboard_id || null,
                inline_keyboard_id: page.inline_keyboard_id || null,
                reply_keyboard: page.replyKeyboard || null,
                inline_keyboard: page.inlineKeyboard || null,
                next_page_id: page.next_page_id || null,
                next_bot_dialog_command_id: page.next_bot_dialog_command_id || null,
                next_bot_menu_slug_id: page.next_bot_menu_slug_id || null,
                is_external: page.is_external || false,

                rules_if: page.rules_if || null,
                rules_else_page_id: page.rules_else_page_id || null,

                rules_if_message: page.rules_if_message || null,
                rules_else_message: page.rules_else_message || null,
            }
        } else
            this.clearForm()


    },

    methods: {
        copyToClipBoard(text){
            navigator.clipboard.writeText(text).then(()=> {
                console.log("copy",text)
                this.$botNotification.notification(
                    "Конструктор страниц",
                    "Ссылка скопирована в буфер",
                );
            }).catch((err)=> {
                this.$botNotification.warning(
                    "Копирование",
                    "Ошибка копирования",
                );
            })
        },
        loadBot() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

            })
        },
        associateDialog(item) {
            this.pageForm.next_bot_dialog_command_id = item.id
        },
        associateSlug(item) {
            this.pageForm.next_bot_menu_slug_id = item.id
        },
        attachPage(item) {

            if (item.id != this.pageForm.id)
                this.pageForm.next_page_id = item.id
            else
                this.$botNotification.warning(
                    "Конструктор страниц",
                    "Вы не можете связать данную страницу с собой",
                );
        },
        injectContent(data) {
            if (this.pageForm.content)
                this.pageForm.content += data.text
            else
                this.pageForm.content = data.text
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
                reply_keyboard_title: null,
                reply_keyboard: null,
                inline_keyboard: null,
                is_external: false,

                reply_keyboard_id: null,
                inline_keyboard_id: null,

                next_page_id: null,

                next_bot_dialog_command_id: null,
                next_bot_menu_slug_id: null,

                rules_if_message: null,
                rules_else_message: null,

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
            this.need_rules = false

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

                this.$botNotification.success(
                    "Конструктор страниц",
                    (this.pageForm.id == null ? "Страница успешно создана!" : "Страница успешно обновлена!"),
                );

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
